<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\stickyio;

class checkout extends Controller
{
    //
    public function __construct(){

    }

    public function pageView(){
        return view('checkout');
    }


    public function processCheckout(Request $request){
        $sticky = new stickyio(env('STICKY_USER'), env('STICKY_PASS'), 'https://dnvbdemo.sticky.io/api/v1/');

        $param = [
            'firstName' => $request->input('firstName'),
            'lastName' => $request->input('lastName'),
            'shippingAddress1' => $request->input('address'),
            'shippingCity' => $request->input('city'),
            'shippingState' => $request->input('state'),
            'shippingZip' => $request->input('zip'),
            'shippingCountry' => 'US',
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'creditCardType' => 'paypal',
            'tranType' => 'sale',
            'ipAddress' => $_SERVER['REMOTE_ADDR'],
            'shippingId' => 25,
            'campaignId' => 560,
            'productId' => 239,
            'product_qty_1' => 1,
            'alt_pay_return_url' => route('upsell', ['pg'=>1])
        ];

        $result = $sticky->newOrder($param);

        $response = json_decode($result);
        
        if (!empty($response->response_code)){
            //general
            if ($response->response_code == 100){
                //on success
            }else{
                Log::error(print_r($response,true));
                return response()->json([
                    'response' => 'failed',
                    'message' => 'System Error'
                ]);
            }
        }else{
            //paypal
            preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $result, $match);

            return response()->json([
                'response' => 'pass',
                'message' => $match[0][1]
            ]);
        }

    }
}
