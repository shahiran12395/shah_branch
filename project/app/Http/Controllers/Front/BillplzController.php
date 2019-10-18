<?php
/**
 * Created by PhpStorm.
 * User: ZESB 18
 * Date: 10/17/2019
 * Time: 3:07 PM
 */

namespace App\Http\Controllers\Front;


use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Generalsetting;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\Product;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\VendorOrder;
use Billplz\Client;
use Config;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BillplzController
{

//    public function __construct(){
//
//        $gs = Generalsetting::findOrFail(1);
//        $billplz = Client::make($gs->billplz_key);
//        $billplz->useVersion('v3');
//        $billplz->setSignatureKey($gs->billplz_x_signature);
//
//        if ($gs->billplz_mode == true) {
//            $billplz->useSandbox();
//        }
//
//    }

    public function billplz(Request $request)
    {
        $input = $request->all();
        Session::put('billplz_data', $input);

        if ($request->pass_check) {
            $users = User::where('email', '=', $request->personal_email)->get();
            if (count($users) == 0) {
                if ($request->personal_pass == $request->personal_confirm) {
                    $user = new User;
                    $user->name = $request->personal_name;
                    $user->email = $request->personal_email;
                    $user->password = bcrypt($request->personal_pass);
                    $token = md5(time() . $request->personal_name . $request->personal_email);
                    $user->verification_link = $token;
                    $user->affilate_code = md5($request->name . $request->email);
                    $user->save();
                    Auth::guard('web')->login($user);
                } else {
                    return redirect()->back()->with('unsuccess', "Confirm Password Doesn't Match.");
                }
            } else {
                return redirect()->back()->with('unsuccess', "This Email Already Exist.");
            }
        }
//        dd(Session::get('billplz_data'));

        if (!Session::has('cart')) {
            return redirect()->route('front.cart')->with('success', "You don't have any product to checkout.");
        }

        $gs = Generalsetting::findOrFail(1);
        $billplz = Client::make($gs->billplz_key);
        $billplz->useVersion('v3');
        $billplz->setSignatureKey($gs->billplz_x_signature);

        if ($gs->billplz_mode == true) {
            $billplz->useSandbox();
        }

        $bill = $billplz->bill();
        $collection_id = $gs->billplz_collection_id;

        $response = $bill->create(
            $collection_id,
            $request->email,
            null,
            $request->name,
            \Duit\MYR::given($request->total*100),
            ['callback_url' => route('billplz.callback'), 'redirect_url' => route('billplz.redirect')],
            'Maecenas eu placerat ante.'
        );

        $payment = $response->toArray();
        Session::put('payment',$payment);
        return redirect($payment['url']);

    }

    public function callback(){
        dd($_POST);
    }

    public function redirect(Request $request){

        $billplz_data = Session::get('billplz_data');
        $billplz_payment = Session::get('payment');
        $item_number = str_random(4) . time();
        $item_amount = $billplz_data['total'];
        //dd($billplz_data);

        if (Session::has('currency')) {
            $curr = Currency::find(Session::get('currency'));
        } else {
            $curr = Currency::where('is_default', '=', 1)->first();
        }

        //dd($curr);

        if ($request['billplz']['paid'] == 'true') {
            $oldCart = Session::get('cart');
            $cart = new Cart($oldCart);

            foreach ($cart->items as $key => $prod) {
                if (!empty($prod['item']['license']) && !empty($prod['item']['license_qty'])) {
                    foreach ($prod['item']['license_qty'] as $ttl => $dtl) {
                        if ($dtl != 0) {
                            $dtl--;
                            $produc = Product::findOrFail($prod['item']['id']);
                            $temp = $produc->license_qty;
                            $temp[$ttl] = $dtl;
                            $final = implode(',', $temp);
                            $produc->license_qty = $final;
                            $produc->update();
                            $temp = $produc->license;
                            $license = $temp[$ttl];
                            $oldCart = Session::has('cart') ? Session::get('cart') : null;
                            $cart = new Cart($oldCart);
                            $cart->updateLicense($prod['item']['id'], $license);
                            Session::put('cart', $cart);
                            break;
                        }
                    }
                }
            }

            $settings = Generalsetting::findOrFail(1);
            $order = new Order;

            $order['user_id'] = $billplz_data['user_id'];
            $order['cart'] = utf8_encode(bzcompress(serialize($cart),9));
            $order['totalQty'] = $billplz_data['totalQty'];
            $order['pay_amount'] = $item_amount;
            //$order['method'] = $billplz_data['method'];
            $order['customer_email'] = $billplz_data['email'];
            $order['customer_name'] = $billplz_data['name'];
            $order['customer_phone'] = $billplz_data['phone'];
            $order['order_number'] = $item_number;
            $order['shipping'] = $billplz_data['shipping'];
            $order['pickup_location'] = $billplz_data['pickup_location'];
            $order['customer_address'] = $billplz_data['address'];
            $order['customer_country'] = $billplz_data['customer_country'];
            $order['customer_city'] = $billplz_data['city'];
            $order['customer_zip'] = $billplz_data['zip'];
            $order['shipping_email'] = $billplz_data['shipping_email'];
            $order['shipping_name'] = $billplz_data['shipping_name'];
            $order['shipping_phone'] = $billplz_data['shipping_phone'];
            $order['shipping_address'] = $billplz_data['shipping_address'];
            $order['shipping_country'] = $billplz_data['shipping_country'];
            $order['shipping_city'] = $billplz_data['shipping_city'];
            $order['shipping_zip'] = $billplz_data['shipping_zip'];
            $order['order_note'] = $billplz_data['order_notes'];
            $order['coupon_code'] = $billplz_data['coupon_code'];
            $order['coupon_discount'] = $billplz_data['coupon_discount'];
            $order['payment_status'] = "paid";
            $order['currency_sign'] = $curr->sign;
            $order['currency_value'] = $curr->value;
            $order['shipping_cost'] = $billplz_data['shipping_cost'];
            $order['packing_cost'] = $billplz_data['packing_cost'];
            $order['tax'] = $billplz_data['tax'];
            $order['dp'] = $billplz_data['dp'];
            $order['txnid'] = $request['billplz']['id'];

            if ($order['dp'] == 1) {
                $order['status'] = 'completed';
            }

            if (Session::has('affilate')) {
                $val = $item_amount / 100;
                $sub = $val * $settings->affilate_charge;
                $user = User::findOrFail(Session::get('affilate'));
                $user->affilate_income += $sub;
                $user->update();
                $order['affilate_user'] = $user->name;
                $order['affilate_charge'] = $sub;
            }
            $order->save();

            if ($order->dp == 1) {
                $track = new OrderTrack;
                $track->title = 'Completed';
                $track->text = 'Your order has completed successfully.';
                $track->order_id = $order->id;
                $track->save();
            } else {
                $track = new OrderTrack;
                $track->title = 'Pending';
                $track->text = 'You have successfully placed your order.';
                $track->order_id = $order->id;
                $track->save();
            }

            $notification = new Notification;
            $notification->order_id = $order->id;
            $notification->save();

            if ($billplz_data['coupon_id'] != "") {
                $coupon = Coupon::findOrFail($billplz_data['coupon_id']);
                $coupon->used++;
                if ($coupon->times != null) {
                    $i = (int)$coupon->times;
                    $i--;
                    $coupon->times = (string)$i;
                }
                $coupon->update();

            }
            foreach ($cart->items as $prod) {
                $x = (string)$prod['stock'];
                if ($x != null) {
                    $product = Product::findOrFail($prod['item']['id']);
                    $product->stock = $prod['stock'];
                    $product->update();
                }
            }

            foreach ($cart->items as $prod) {
                $x = (string)$prod['size_qty'];
                if (!empty($x)) {
                    $product = Product::findOrFail($prod['item']['id']);
                    $x = (int)$x;
                    $x = $x - $prod['qty'];
                    $temp = $product->size_qty;
                    $temp[$prod['size_key']] = $x;
                    $temp1 = implode(',', $temp);
                    $product->size_qty = $temp1;
                    $product->update();
                }
            }

            foreach ($cart->items as $prod) {
                $x = (string)$prod['stock'];
                if ($x != null) {

                    $product = Product::findOrFail($prod['item']['id']);
                    $product->stock = $prod['stock'];
                    $product->update();
                    if ($product->stock <= 5) {
                        $notification = new Notification;
                        $notification->product_id = $product->id;
                        $notification->save();
                    }
                }
            }

            $notf = null;

            foreach ($cart->items as $prod) {
                if ($prod['item']['user_id'] != 0) {
                    $vorder = new VendorOrder;
                    $vorder->order_id = $order->id;
                    $vorder->user_id = $prod['item']['user_id'];
                    $notf[] = $prod['item']['user_id'];
                    $vorder->qty = $prod['qty'];
                    $vorder->price = $prod['price'];
                    $vorder->order_number = $order->order_number;
                    $vorder->save();
                }

            }

            if (!empty($notf)) {
                $users = array_unique($notf);
                foreach ($users as $user) {
                    $notification = new UserNotification;
                    $notification->user_id = $user;
                    $notification->order_number = $order->order_number;
                    $notification->save();
                }
            }

            $gs = Generalsetting::find(1);

            if ($gs->is_smtp == 1) {
                $data = [
                    'to' => $billplz_data['email'],
                    'type' => "new_order",
                    'cname' => $billplz_data['name'],
                    'oamount' => "",
                    'aname' => "",
                    'aemail' => "",
                    'wtitle' => "",
                    'onumber' => $item_number
                ];

                $mailer = new GeniusMailer();
                $mailer->sendAutoOrderMail($data, $order->id);
            } else {
                $to = $billplz_data['email'];
                $subject = "Your Order Placed!!";
                $msg = "Hello " . $billplz_data['name'] . "!\nYou have placed a new order.\nYour order number is " . $item_number . ".Please wait for your delivery. \nThank you.";
                $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
                mail($to, $subject, $msg, $headers);
            }
            //Sending Email To Admin
            if ($gs->is_smtp == 1) {
                $data = [
                    'to' => $gs->email,
                    'subject' => "New Order Recieved!!",
                    'body' => "Hello Admin!<br>Your store has received a new order.<br>Order Number is " . $item_number . ".Please login to your panel to check. <br>Thank you.",
                ];

                $mailer = new GeniusMailer();
                $mailer->sendCustomMail($data);
            } else {
                $to = $gs->email;
                $subject = "New Order Recieved!!";
                $msg = "Hello Admin!\nYour store has recieved a new order.\nOrder Number is " . $item_number . ".Please login to your panel to check. \nThank you.";
                $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
                mail($to, $subject, $msg, $headers);
            }

            $success_url = action('Front\PaymentController@payreturn');

            Session::put('temporder', $order);
            Session::put('tempcart', $cart);
            Session::forget('cart');
            Session::forget('already');
            Session::forget('coupon');
            Session::forget('coupon_total');
            Session::forget('coupon_total1');
            Session::forget('coupon_percentage');
            Session::forget('cart');
            Session::forget('paypal_data');
            Session::forget('paypal_items');
            return redirect($success_url);
        } else {
            $cancel_url = action('Front\PaymentController@paycancle');
            return redirect($cancel_url);
        }

    }

}