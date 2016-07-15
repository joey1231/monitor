<?php

namespace App\Libraries;

use Parser;
use SoapBox\Formatter\Formatter;
use Log;
use App\Libraries\Array2XML;

/**
 * Class Sears
 *
 * Uses for process to sears
 *
 * @package App\Libraries
 *
 */
class Sears
{
    /**
     * CreateXML to create xml of order to send to sear
     * @param $order
     * @return mixed
     */
    public static function createXML($order)
    {
        $shipping_method = '';
        // sanity check for type shipping method
        if ($order->shipping_method == 'UPS: Ground') {
            $shipping_method = 'Ground';
        } else {
            if ($order->shipping_method == 'UPS: 2nd Day Air') {
                $shipping_method = 'Two-day';
            } elseif ($$order->shipping_method == 'UPS: Next Day Air') {
                $shipping_method = 'Next-day';
            }
        }
        // set the orderdata array
        $orderdata = array();
        $orderdata['@attributes'] = array(
            'xsi:schemaLocation' => 'http://seller.marketplace.sears.com/oms/v1 https://seller.marketplace.sears.com/SellerPortal/s/schema/rest/oms/import/v1/mcfbs-order.xsd',
            'xmlns'=>'http://seller.marketplace.sears.com/oms/v1',
            'xmlns:xsi'=>'http://www.w3.org/2001/XMLSchema-instance'
        );
        $orderdata['orders'] = array();
        $orderdata['orders']['order'] = array();
        $orderdata['orders']['order']['order-details'] = array();
        $orderdata['orders']['order']['order-details']['seller-order-id'] = str_replace('-', '', $order->order_id);
        $orderdata['orders']['order']['order-details']['displayable-order-id'] = str_replace('-', '', $order->order_id);
        $orderdata['orders']['order']['order-details']['displayable-order-date-and-time'] = date('Y-m-d',
                strtotime($order->order_date)) . 'T' . date('H:i:s', strtotime($order->order_date));
        $orderdata['orders']['order']['order-details']['shipping-sla'] = $shipping_method;

        //Shipping Address
        $orderdata['orders']['order']['order-details']['shipping-address']['ship-to-first-name'] = preg_replace('/[^A-Za-z0-9\s]/',
            '', $order->ship_to_first_name);
        $orderdata['orders']['order']['order-details']['shipping-address']['ship-to-last-name'] = preg_replace('/[^A-Za-z0-9\s]/',
            '', $order->ship_to_last_name);
        $orderdata['orders']['order']['order-details']['shipping-address']['ship-to-street-address'] = preg_replace('/[^A-Za-z0-9\s]/',
            '', $order->ship_to_address1);

        $ssal2= array();

        if ($order->ship_to_address2 != '') {
            $ssal2[] = preg_replace('/[^A-Za-z0-9\s]/', '', $order->ship_to_address2);
        }
        if (($order->ship_to_company != '')) {
            $ssal2[] = preg_replace('/[^A-Za-z0-9\s]/', '', $order->ship_to_company);
        }
        if (sizeof($ssal2) != 0) {
            $orderdata['orders']['order']['order-details']['shipping-address']['ship-to-street-address-line2'] = implode(' ',
                $ssal2);
        }

        
         $orderdata['orders']['order']['order-details']['shipping-address']['ship-to-city'] = preg_replace('/[^A-Za-z0-9\s]/', '', $order->ship_to_city);

        $orderdata['orders']['order']['order-details']['shipping-address']['ship-to-state'] = (strtolower($order->ship_to_state)!='dc')?strtoupper($order->ship_to_state):str_replace('dc','WA',strtolower($order->ship_to_state));

        $orderdata['orders']['order']['order-details']['shipping-address']['ship-to-zip'] = ((strlen(preg_replace('/[^0-9]/', '',  $order->ship_to_zip))>5)?substr(preg_replace('/[^0-9]/', '',  $order->ship_to_zip),0,5):preg_replace('/[^0-9]/', '',  $order->ship_to_zip));


        if ($order->email != '') {
            $orderdata['orders']['order']['order-details']['shipping-address']['ship-to-email'] = $order->email;
        }

        if ($order->day_phone != '') {
            $orderdata['orders']['order']['order-details']['shipping-address']['ship-to-phone'] = ((strlen(preg_replace('/[^0-9]/', '',$order->day_phone))>5)?substr(preg_replace('/[^0-9]/', '',$order->day_phone),-10):preg_replace('/[^0-9]/', '', $order->day_phone));

        }
        //end of shipping address


        $orderdata['orders']['order']['order-details']['displayable-order-total-shipping-and-handling'] = '$' . number_format($order->shipping_handling_total,
                2);


        $ic = 0;
        $items = $order->items()->get();
        foreach ($items as $item) {
            if ($item->kit == 'N') {
                if ($item->send_to_sears == 'Y') {
                    $orderdata['orders']['order']['line-item-details'][$ic] = array(
                        'seller-item-id' => (($item->item_id=='RCHBULLY4Z')?'RCHBULLY8Z':(($item->item_id=='FLEABULLY4Z')?'FLEABULLY8Z':$item->item_id)), 
                        'quantity' => $item->quantity,
                        'requested-fulfillment-policy' => "All Available",
                        'displayable-item-price' => '$' . number_format($item->total_cost_with_discount, 2),
                        'displayable-sales-tax' => '$0.00'
                    );
                    $orderdata['orders']['order']['line-item-details'][$ic]['displayable-message'] = "All Available";
                    $ic++;
                }
            }
        }


       // $xml = Formatter::make($orderdata, Formatter::ARR);
      //  $tempxml = $xml->toXml();
        $xml = Array2XML::createXML('mcfbs-order-feed', $orderdata);
        $tempxml = $xml->saveXML();
        libxml_use_internal_errors(true);

        return $tempxml;

    }

    /**
     * Validate XML to send in sear
     * @param $xml
     * @return mixed
     */
    public static function validateXML($xml)
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xml,LIBXML_PARSEHUGE);

        libxml_use_internal_errors(true);

        $v= $doc->schemaValidate(env('SEAR_URL') .'/SellerPortal/s/schema/rest/oms/import/v1/mcfbs-order.xsd');
        Log::info(libxml_get_errors());
        return $v;
    }

    public static function Send($xml)
    {
        $ch = curl_init();
        $putData = tmpfile();//loading into local temp file to send quickly
        fwrite($putData, $xml);
        fseek($putData, 0);

        $curlheader[] = "Content-Type:application/xml";
        curl_setopt($ch, CURLOPT_URL, env('SEAR_URL') ."/SellerPortal/api/oms/mcfbs/v1?email=".env('SEAR_EMAIL')."&password=".env('SEAR_PASSWORD'));//put url
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_INFILE, $putData);
        curl_setopt($ch, CURLOPT_INFILESIZE, strlen($xml));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $curlheader);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        if(curl_errno($ch))
        {
            $result = curl_errno($ch)." - ".curl_error($ch);
        }
        fclose($putData);
        curl_close($ch);
        $result = Parser::xml($result);
        return $result;
    }
    public static function GetOrderStatus($order){
        date_default_timezone_set('Etc/GMT');
        
        $time =date("H:i:s")."Z";
        $date = date("Y-m-d")."T";
        $key_string=env('SEAR_SELLER_ID').":".env('SEAR_EMAIL').":$date$time";
        
        $signature =hash_hmac('sha256', $key_string, env('SEAR_SECRET_KEY'));//
        $url="https://seller.marketplace.sears.com/SellerPortal/api/oms/fbs/purchaseorder/v2?sellerId=23673&sellerOrderId=".str_replace('-','',$order->order_id);;
        $ch = curl_init($url);
        $header[] = 'Content-length: 0';
          
        $header[] = 'Authorization:HMAC-SHA256 emailaddress='.env('SEAR_EMAIL').',timestamp='.$date.$time.',signature='.$signature;
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);    // get the url contents

        $data = curl_exec($ch); // execute curl request
         curl_close($ch);
         
        if(strtolower($data)!='rate limited'){
            $result = Parser::xml($data);
            if(isset($result['purchase-order']['po-status'])){
                return $result['purchase-order']['po-status'];
            }else if(isset($result['error-detail'])){
                return $result['error-detail'];
            }
        }else{
            return "";
        }
    }
    
    public static function GetItemInventory($item_ids)
    {
        $url = "https://seller.marketplace.sears.com/SellerPortal/api/inventory/v5?itemIds=".implode(',',$item_ids)."&email=".env('SEAR_EMAIL')."&password=".env('SEAR_PASSWORD');
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);    // get the url contents
        $data = curl_exec($ch); // execute curl request

        if(curl_errno($ch))
        {
            echo 'error:' . curl_error($ch);
            echo "<br /><hr />".curl_errno($ch);
        }
        curl_close($ch);
        
        if(strtolower($data)!='rate limited'){
            return simplexml_load_string($data);
        }else{
            return "";
        }
    }

}