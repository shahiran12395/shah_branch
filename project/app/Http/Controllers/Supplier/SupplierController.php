<?php
/**
 * Created by PhpStorm.
 * User: ZESB 18
 * Date: 10/13/2019
 * Time: 9:05 PM
 */

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\VendorOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SupplierController extends Controller
{
    public $global_language;

    public function __construct()
    {
        $this->middleware('auth');

        if (Session::has('language'))
        {
            $data = DB::table('languages')->find(Session::get('language'));
            $data_results = file_get_contents(public_path().'/assets/languages/'.$data->file);
            $this->vendor_language = json_decode($data_results);
        }
        else
        {
            $data = DB::table('languages')->where('is_default','=',1)->first();
            $data_results = file_get_contents(public_path().'/assets/languages/'.$data->file);
            $this->vendor_language = json_decode($data_results);

        }

    }

    public function index(){
        $user = Auth::user();
        $pending = VendorOrder::where('user_id','=',$user->id)->where('status','=','pending')->get();
        $processing = VendorOrder::where('user_id','=',$user->id)->where('status','=','processing')->get();
        $completed = VendorOrder::where('user_id','=',$user->id)->where('status','=','completed')->get();
        return view('supplier.index',compact('user','pending','processing','completed'));
    }

}