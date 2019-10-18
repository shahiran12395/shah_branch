<?php

namespace App\Http\Controllers\User;


use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Generalsetting;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use Auth;
use Carbon\Carbon;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Srmklive\PayPal\Services\ExpressCheckout;

class PaypalController extends Controller
{

    public function __construct()
    {
        //Set Spripe Keys
        $gs = Generalsetting::findOrFail(1);
        Config::set('paypal.mode', $gs->paypal_mode);
        Config::set('paypal.' . $gs->paypal_mode . '.username', $gs->paypal_username);
        Config::set('paypal.' . $gs->paypal_mode . '.password', $gs->paypal_password);
        Config::set('paypal.' . $gs->paypal_mode . '.secret', $gs->paypal_secret);

        if (Session::has('currency')) {
            $this->curr = Currency::find(Session::get('currency'));
        } else {
            $this->curr = Currency::where('is_default', '=', 1)->first();
        }

        Config::set('paypal.currency', $this->curr->name);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'shop_name' => 'unique:users',
        ], [
            'shop_name.unique' => 'This shop name has already been taken.'
        ]);
        $user = Auth::user();
        $subs = Subscription::findOrFail($request->subs_id);
        $settings = Generalsetting::findOrFail(1);
        $paypal_email = $settings->paypal_business;
        $return_url = action('User\PaypalController@payreturn');
        $cancel_url = action('User\PaypalController@paycancle');
        $notify_url = action('User\PaypalController@notify');
        $item_name = $subs->title . " Plan";
        $item_number = str_random(4) . time();
        $item_amount = $subs->price;

        $sub['user_id'] = $user->id;
        $sub['subscription_id'] = $subs->id;
        $sub['title'] = $subs->title;
        $sub['currency'] = $subs->currency;
        $sub['currency_code'] = $subs->currency_code;
        $sub['price'] = $subs->price;
        $sub['days'] = $subs->days;
        $sub['allowed_products'] = $subs->allowed_products;
        $sub['details'] = $subs->details;
        $sub['method'] = 'Paypal';

        Session::put('subscription', $sub);


        $settings = Generalsetting::findOrFail(1);
        $provider = new ExpressCheckout;
        $data['items'][] = [
            'name' => $item_name,
            'price' => $item_amount
        ];
        $data['invoice_description'] = '';
        $data['invoice_id'] = $item_number;
        $data['total'] = $item_amount;
        $data['return_url'] = $notify_url;
        $data['cancel_url'] = $cancel_url;
        $response = $provider->setExpressCheckout($data);
        Session::put('paypal_items', $data);
        return redirect($response['paypal_link']);

    }


    public function paycancle()
    {
        return redirect()->back()->with('unsuccess', 'Payment Cancelled.');
    }

    public function payreturn()
    {
        return redirect()->route('user-dashboard')->with('success', 'Vendor Account Activated Successfully');
    }


    public function notify(Request $request)
    {

        $sub = Session::get('subscription');

        $paypal_data = Session::get('paypal_data');
        $paypal_items = Session::get('paypal_items');
        $success_url = action('User\PaypalController@payreturn');
        $cancel_url = action('User\PaypalController@paycancle');

        $provider = new ExpressCheckout;
        $token = $request->token;
        $payerid = $request->PayerID;
        $response = $provider->getExpressCheckoutDetails($token);
        $item_number = $response['INVNUM'];
        $response = $provider->doExpressCheckoutPayment($paypal_items, $token, $payerid);
        $item_amount = $response['PAYMENTINFO_0_AMT'];

        if ($response['PAYMENTINFO_0_ACK'] == 'Success') {


            $order = new UserSubscription;
            $order->user_id = $sub['user_id'];
            $order->subscription_id = $sub['subscription_id'];
            $order->title = $sub['title'];
            $order->currency = $sub['currency'];
            $order->currency_code = $sub['currency_code'];
            $order->price = $sub['price'];
            $order->days = $sub['days'];
            $order->allowed_products = $sub['allowed_products'];
            $order->details = $sub['details'];
            $order->method = $sub['method'];
            $order->txnid = $response['PAYMENTINFO_0_TRANSACTIONID'];
            $order->status = 1;
            $order->save();


            $user = User::findOrFail($order->user_id);
            $package = $user->subscribes()->where('status', 1)->orderBy('id', 'desc')->first();
            $subs = Subscription::findOrFail($order->subscription_id);
            $settings = Generalsetting::findOrFail(1);


            $today = Carbon::now()->format('Y-m-d');
            $date = date('Y-m-d', strtotime($today . ' + ' . $subs->days . ' days'));
            $user->is_vendor = 2;
            if (!empty($package)) {
                if ($package->subscription_id == $order->subscription_id) {
                    $newday = strtotime($today);
                    $lastday = strtotime($user->date);
                    $secs = $lastday - $newday;
                    $days = $secs / 86400;
                    $total = $days + $subs->days;
                    $user->date = date('Y-m-d', strtotime($today . ' + ' . $total . ' days'));
                } else {
                    $user->date = date('Y-m-d', strtotime($today . ' + ' . $subs->days . ' days'));
                }
            } else {
                $user->date = date('Y-m-d', strtotime($today . ' + ' . $subs->days . ' days'));
            }

            $user->mail_sent = 1;
            $user->update();


            if ($settings->is_smtp == 1) {
                $maildata = [
                    'to' => $user->email,
                    'type' => "vendor_accept",
                    'cname' => $user->name,
                    'oamount' => "",
                    'aname' => "",
                    'aemail' => "",
                    'onumber' => "",
                ];
                $mailer = new GeniusMailer();
                $mailer->sendAutoMail($maildata);
            } else {
                $headers = "From: " . $settings->from_name . "<" . $settings->from_email . ">";
                mail($user->email, 'Your Vendor Account Activated', 'Your Vendor Account Activated Successfully. Please Login to your account and build your own shop.', $headers);
            }


            Session::forget('subscription');

            return redirect($success_url);
        } else {
            return redirect($cancel_url);
        }

    }

}