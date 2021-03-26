<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\stickyio;


class upsell extends Controller
{
    //
    public function __construct(){

    }

    public function pageView(Request $request, $pg=0){
        Log::info('Request ALL:');
        Log::info(print_r($request->all(),true));
        switch($pg){
            case 1:
                return view('upsells/upsell');
                break;
            case 2:
                return view('upsells/upsell2');
                break;
            case 3:
                return view('upsells/upsell3');
                break;
            default:
                return view('summary/summary');
        }
    }

    public function processUpsell(Request $request){

        $validator = Validator::make($request->all(),[
            'productId' => 'required',
            'orderId' => 'required',
            'shippingId' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json([
                'response' => 'failed',
                'message' => $validator->errors()
            ]);
        }else{
            $sticky = new stickyio(env('STICKY_USER'), env('STICKY_PASS'));

            $param = [
                'previousOrderId' => $validator->valid()['orderId'],
                'campaignId' => 560,
                'productId' => $validator->valid()['productId'],
                'shippingId' => $validator->valid()['shippingId'],
            ];
            $result = $sticky->newOrderCardOnFile($param);
        }

    }
}
