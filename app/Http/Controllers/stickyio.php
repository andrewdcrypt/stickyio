<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class stickyio extends Controller
{
    protected $api_user = "";
    protected $api_pass = "";
    protected $api_url = "";

    /**
     * @param $api_user sticky api username
     * @param $api_pass sticky api password
     * @param $api_url sticky JSON API URL don't include the method
     */
    public function __construct(string $api_user, string $api_pass, string $api_url){
        $this->api_user = $api_user;
        $this->api_pass = $api_pass;
        $this->api_url = $api_url;
    }

    /**
     * @return $body 100 - success or 200 - invalid response
     */
    public function validateCred(){

        $result = Http::withBasicAuth($this->api_user,$this->api_pass)->withHeaders([
            'Content-Type' => "application/json"
        ])->post($this->api_url."validate_credentials");

        $body = $result->body();

        Log::info("Validate Cred:: ");
        Log::debug(print_r($body,true));
        
        return $body;
    }

    /**
     * @param $order_info array of customer shipping/billing info
     * @return $body json response or html response if paypal
     */
    public function newOrder(array $order_info){
        if ($order_info['creditCardType'] == 'paypal'){

            $param = [
                'creditCardType' => $order_info['creditCardType'],
                'tranType' => 'sale',
                'ipAddress' => $_SERVER['REMOTE_ADDR'],
                'shippingId' => $order_info['shippingId'],
                'campaignId' => $order_info['campaignId'],
                'productId' => $order_info['productId'],
                'product_qty_1' => $order_info['product_qty_1'],
                'alt_pay_return_url' => $order_info['alt_pay_return_url'],
                'firstName' => $order_info['firstName'],
                'lastName' => $order_info['lastName'],
                'shippingAddress1' => $order_info['shippingAddress1'],
                'shippingCity' => $order_info['shippingCity'],
                'shippingState' => $order_info['shippingState'],
                'shippingZip' => $order_info['shippingZip'],
                'shippingCountry' => 'US',
                'phone' => $order_info['phone'],
                'email' => $order_info['email'],
                'billingFirstName' => $order_info['firstName'],
                'billingLastName' => $order_info['lastName'],
                'billingAddress1' => $order_info['shippingAddress1'],
                'billingCity' => $order_info['shippingCity'],
                'billingState' => $order_info['shippingState'],
                'billingZip' => $order_info['shippingZip'],
                'billingCountry' => 'US',          
            ];

            $result = Http::withBasicAuth($this->api_user,$this->api_pass)->withHeaders([
                'Content-Type' => "application/json"
            ])->post($this->api_url."new_order",$param);

            $body = $result->body();

            return $body;
        }else{
            $param = [
                'firstName' => $order_info['firstName'],
                'lastName' => $order_info['lastName'],
                'billingFirstName' => $order_info['billingFirstName'],
                'billingLastName' => $order_info['billingLastName'],
                'billingAddress1' => $order_info['billingAddress1'],
                'billingAddress2' => $order_info['billingAddress2'],
                'billingCity' => $order_info['billingCity'],
                'billingState' => $order_info['billingState'],
                'billingZip' => $order_info['billingZip'],
                'billingCountry' => $order_info['billingCountry'],
                'phone' => $order_info['phone'],
                'email' => $order_info['email'],
                'creditCardType' => $order_info['creditCardType'],
                'creditCardNumber' => $order_info['creditCardNumber'],
                'expirationDate' => $order_info['expirationDate'],
                'CVV' => $order_info['CVV'],
                'shippingId' => $order_info['shippingId'],
                'tranType' => $order_info['tranType'],
                'ipAddress' => $order_info['ipAddress'],
                'campaignId' => $order_info['campaignId'],
                'notes' => $order_info['notes'],
                'AFID' => $order_info['AFID'],
                'SID' => $order_info['SID'],
                'AFFID' => $order_info['AFFID'],
                'C1' => $order_info['C1'],
                'C2' => $order_info['C2'],
                'C3' => $order_info['C3'],
                'AID' => $order_info['AID'],
                'OPT' => $order_info['OPT'],
                'click_id' => $order_info['click_id'],
                'billingSameAsShipping' => $order_info['billingSameAsShipping'],
                'shippingAddress1' => $order_info['shippingAddress1'],
                'shippingAddress2' => $order_info['shippingAddress2'],
                'shippingCity' => $order_info['shippingCity'],
                'shippingState' => $order_info['shippingState'],
                'shippingZip' => $order_info['shippingZip'],
                'shippingCountry' => $order_info['shippingCountry'],
                'alt_pay_token' => $order_info['alt_pay_token'],
                'alt_pay_payer_id' => $order_info['alt_pay_payer_id'],
                'promoCode' => $order_info['promoCode'],
                'alt_pay_return_url' => $order_info['alt_pay_return_url'],
            ];
        }
    }

    public function orderView(array $order_ids){
        $result = Http::withBasicAuth($this->api_user,$this->api_pass)->withHeaders([
            'Content-Type' => "application/json"
        ])->post($this->api_url,[
            'order_id' => $order_ids
        ]);

        $body = $result->body();

        Log::info("orderView ::");
        Log::debug(print_r($body,true));
    }

    /**
     * @param $alt_provider array based on order info for paypal express
     * @return $body json response
     */
    public function getAlternativeProvider(array $alt_provider){
        $param = [          
            'campaign_id' => $alt_provider['campaignId'],
            'shipping_id' => $alt_provider['shippingId'],
            'product_id' => $alt_provider['productId'],
            'amount' => $alt_provider['amount'],
            'return_url' => $alt_provider['return_url'],
            'cancel_url' => $alt_provider['cancel_url'],
        ];

        $result = Http::withBasicAuth($this->api_user,$this->api_pass)->withHeaders([
            'Content-Type' => "application/json"
        ])->post($this->api_url."get_alternative_provider"."?".http_build_query($param));

        $body = $result->body();

        Log::info("getAlternativeProvider ::");
        Log::debug(print_r($result->body(),true));

        return $body;
    }

    /**
     * @param $upsell_info array based on order info from previous order aside from product
     * @return $body json response
     */
    public function newOrderCardOnFile(array $upsell_info){
        $param = [
            'previousOrderId' => $upsell_info['previousOrderId'],
            'campaignId' => $upsell_info['campaignId'],
            'productId' => $upsell_info['productId'],
            'shippingId' => $upsell_info['shippingId'],
        ];

        $result = Http::withBasicAuth($this->api_user,$this->api_pass)->withHeaders([
            'Content-Type' => "application/json"
        ])->post($this->api_url."new_order_card_on_file",$param);

        $body = $result->body();

        Log::info("New Order Card On File ::");
        Log::debug(print_r($body,true));

        return $body;
    }
    
}
