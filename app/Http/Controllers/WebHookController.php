<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Components\Order;
use App\Models\Components\Item;
use App\Models\Components\Coupon;
use App\Models\Components\Transaction;
use App\Models\Components\ExtendedDetail;
use Log;

class WebHookController extends Controller
{
    /**
     * Send UC postback to DB
     * 
     * @param xml php://input
     * 
     * @return str
     */
    public function SendUCtoDB(Request $request)
    {
        
        $xml_document = $request->getContent();
        //die;
        $out = '';
        // Parse the XML Document into a DOM Object
        $doc = new \DOMDocument();
        $doc->loadXML($xml_document);
        $xmldata = $doc->saveXML();

        $xmlarray = simplexml_load_string($xmldata);

        if(sizeof($xmlarray->order)!=0){
            $order = new Order();            
            $order->order_id = $xmlarray->order->order_id;
            $order->order_date = date('Y-m-d H:i:s',strtotime($xmlarray->order->order_date));
            $order->type = $xmlarray->order->order_type;
            $order->bill_to_company = $xmlarray->order->bill_to_company;
            $order->bill_to_title = $xmlarray->order->bill_to_title;
            $order->bill_to_first_name = $xmlarray->order->bill_to_first_name;
            $order->bill_to_last_name = $xmlarray->order->bill_to_last_name;
            $order->bill_to_address1 = $xmlarray->order->bill_to_address1;
            $order->bill_to_address2 = $xmlarray->order->bill_to_address2;
            $order->bill_to_city = $xmlarray->order->bill_to_city;
            $order->bill_to_state = $xmlarray->order->bill_to_state;
            $order->bill_to_zip = $xmlarray->order->bill_to_zip;
            $order->bill_to_country = $xmlarray->order->bill_to_country;
            $order->bill_to_country_code = $xmlarray->order->bill_to_country_code;
            $order->tax_country = $xmlarray->order->tax_country;
            $order->ship_to_company = $xmlarray->order->ship_to_company;
            $order->ship_to_title = $xmlarray->order->ship_to_title;
            $order->ship_to_first_name = $xmlarray->order->ship_to_first_name;
            $order->ship_to_last_name = $xmlarray->order->ship_to_last_name;
            $order->ship_to_address1 = $xmlarray->order->ship_to_address1;
            $order->ship_to_address2 = $xmlarray->order->ship_to_address2;
            $order->ship_to_city = $xmlarray->order->ship_to_city;
            $order->ship_to_state = $xmlarray->order->ship_to_state;
            $order->ship_to_zip = $xmlarray->order->ship_to_zip;
            $order->ship_to_country = $xmlarray->order->ship_to_country;
            $order->ship_to_country_code = $xmlarray->order->ship_to_country_code;
            $order->shipping_method = $xmlarray->order->shipping_method;
            $order->lift_gate = $xmlarray->order->lift_gate;
            $order->gift = $xmlarray->order->gift;
            $order->email = $xmlarray->order->email;
            $order->mailing_list = $xmlarray->order->mailing_list;
            $order->day_phone = $xmlarray->order->day_phone;
            $order->evening_phone = $xmlarray->order->evening_phone;
            $order->fax = $xmlarray->order->fax;
            $order->card_type = $xmlarray->order->card_type;
            $order->card_number = $xmlarray->order->card_number;
            $order->card_exp_month = $xmlarray->order->card_exp_month;
            $order->card_exp_year = $xmlarray->order->card_exp_year;
            $order->card_auth_ticket = $xmlarray->order->card_auth_ticket;
            $order->payment_method = $xmlarray->order->payment_method;
            $order->weight = $xmlarray->order->weight;
            $order->subtotal = $xmlarray->order->subtotal;
            $order->tax_rate = $xmlarray->order->tax_rate;
            $order->tax = $xmlarray->order->tax;
            $order->shipping_handling_total = $xmlarray->order->shipping_handling_total;
            $order->shipping_handling_total_discount = $xmlarray->order->shipping_handling_total_discount;
            $order->surcharge = $xmlarray->order->surcharge_transaction_fee;
            $order->surcharge_transaction_percentage = $xmlarray->order->surcharge_transaction_percentage;
            $order->surcharge = $xmlarray->order->surcharge;
            $order->total = $xmlarray->order->total;
            $order->currency_code = $xmlarray->order->currency_code;
            $order->actual_profit_analyzed = $xmlarray->order->actual_profit_analyzed;
            $order->actual_profit_review = $xmlarray->order->actual_profit_review;
            $order->actual_shipping = $xmlarray->order->actual_shipping;
            $order->actual_other_cost = $xmlarray->order->actual_other_cost;
            $order->actual_fullfillment = $xmlarray->order->actual_fulfillment;
            $order->actual_payment_processing = $xmlarray->order->actual_payment_processing;
            $order->actual_profit = $xmlarray->order->actual_profit;
            $order->gift_certificate_amount = $xmlarray->order->gift_certificate_amount;
            $order->special_instructions = $xmlarray->order->special_instructions;
            $order->merchant_notes = $xmlarray->order->merchant_notes;
            $order->subtotal_discount = $xmlarray->order->subtotal_discount;
            $order->gift_charge_accounting_code = $xmlarray->order->gift_charge_accounting_code;
            $order->gift_wrap_accounting_code = $xmlarray->order->gift_wrap_accounting_code;
            $order->screen_branding_theme_code = $xmlarray->order->screen_branding_theme_code;
            $order->insureship_available = $xmlarray->order->insureship_available;
            $order->insureship_separate = $xmlarray->order->insureship_separate;
            $order->insureship_wanted = $xmlarray->order->insureship_wanted;
            $order->has_customer_profile = $xmlarray->order->has_customer_profile;
            $order->customer_ip_address = $xmlarray->order->customer_ip_address;
            $order->upsell_path_code = $xmlarray->order->upsell_path_code;
            $order->test_order = $xmlarray->order->test_order;
            $order->current_stage = $xmlarray->order->current_stage;
            $order->payment_status = $xmlarray->order->payment_status;               
            $order->save();
        }
        
        if(sizeof($xmlarray->order->item)!=0){
           
            foreach($xmlarray->order->item as $key=>$items){
                $item = new Item();    
                $item->order_id = $items->order_id;
                $item->item_id = (($items->item_id=='RCHBULLY4Z')?'RCHBULLY8Z':(($items->item_id=='FLEABULLY4Z')?'FLEABULLY8Z':$items->item_id));
                $item->item_index = $items->item_index;
                $item->quantity = $items->quantity;
                $item->description = $items->description;
                $item->cost = $items->cost;
                $item->unit_cost_with_discount = $items->unit_cost_with_discount;
                $item->total_cost_with_discount = $items->total_cost_with_discount;
                $item->cogs = $items->cogs;
                $item->actual_cogs = $items->actual_cogs;
                $item->country_of_origin = $items->country_of_origin;
                $item->item_weight = $items->item_weight;
                $item->tax_free = $items->tax_free;
                $item->special_product_type = $items->special_product_type;
                $item->free_shipping = $items->free_shipping;
                $item->accounting_code = $items->accounting_code;
                $item->discount = $items->discount;
                $item->distribution_center_code = $items->distribution_center_code;
                $item->kit = $items->kit;
                $item->kit_component = $items->kit_component;
                //$order->items()->save($item);
                $item->save();
            }
        }
        if(sizeof($xmlarray->order->coupon)!=0){
            foreach($xmlarray->order->coupon as $key=>$coupons){
                $coupon = new Coupon();
                $coupon->order_id = $coupons->order_id;
                $coupon->coupon_code = $coupons->coupon_code;
                $coupon->coupon_accounting_code = $coupons->coupon_accounting_code;
                $coupon->base_coupon_code = $coupons->base_coupon_code;
                $coupon->save();
            }
        }
        if(sizeof($xmlarray->order->transaction_details->transaction_detail)!=0){
            $c = 0;
            foreach($xmlarray->order->transaction_details->transaction_detail as $tkey=>$transactions){
                
                $transaction = new Transaction();
                $transaction->id = $transactions->transaction_id;
                $transaction->gateway = $transactions->transaction_gateway;
                $transaction->created_at = date('Y-m-d H:i:s',strtotime($transactions->transaction_timestamp));
                $transaction->successful = $transactions->transaction_successful;
                $transaction->order_id = $xmlarray->order->order_id;
                $transaction->save();
                
                foreach($xmlarray->order->transaction_details->transaction_detail[$c]->extended_details->extended_detail as $extkey=>$exttransactions){
                    $exttransaction = new ExtendedDetail();                    
                    $exttransaction->transaction_id = $transactions->transaction_id;
                    $exttransaction->name = $exttransactions->extended_detail_name;
                    $exttransaction->value = $exttransactions->extended_detail_value;
                    $exttransaction->save();
                }
                $c++;                
            }            
        }
        
        echo "Order ID ".$xmlarray->order->order_id." was successfully imported via Order Placed.";
       
        //SEND TO MAILWIZZ

            $email = $xmlarray->order->email;
            $order_id = $xmlarray->order->order_id;
            $fname = $xmlarray->order->bill_to_first_name;
            $lname = $xmlarray->order->bill_to_last_name;
            $company = $xmlarray->order->bill_to_company;
            $phone = $xmlarray->order->day_phone;
            $eveningphone = $xmlarray->order->evening_phone;

            $listclass = array(
                '3' => array(
                       'members' => array(  'BBBDTR1G','BUGBUDDYRPL','BBBDTR32Z','BBBDTR55G','BBBDTR5G','BBBULLY1G','BBBULLY32Z','BBBULLY4Z','BBBULLY55G','BBBULLY5G','BBBULLY5GS', 'BBBULLY1GS', 'BBBULLY32ZS','BBBULLY8ZSPC' ),
                        'interest' => 'Bed Bugs'
                    ),
                '23'=> array(
                       'members' => array(  'RSTRZ12X1','RSTARM1G','RSTARM32Z','RSTARM4Z','RSTARM55G','RSTARM5G','RSTRZR1G','RSTRZR1GS','RSTRZR32Z','RSTRZR32ZS','RSTRZR4Z','RSTRZR55G','RSTRZR5G'),
                        'interest' => 'Rust'
                    )
            );

            $prodids = array();
            foreach($xmlarray->order->item as $key=>$items){
                if($items->kit_component=='N'){
                    $prodids[] = $items->item_id;
                }
            }
            $prodids = implode(',', $prodids);
          //  $url = "https://www.dreamwareenterprise.com/scripts/mailwizz/create_subscriber.php?Email=$email&OrderId=$order_id&BillingFirstName=$fname&BillingLastName=$lname&BillingCompany=$company&ProductIds=$prodids&ShippingDayPhone=$phone&ShippingEveningPhone=$eveningphone";
            /*
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
             * 
             */
        //END OF SENDING TO MAILWIZZ
    }
    
    /**
     * Send updated order from UC to DB
     * 
     * @param xml php://input
     * 
     * @return str
     */
    
    public function UpdateUCtoDB(Request $request){
       
        $xml_document = file_get_contents('php://input');

        //die;
        $out = '';
        // Parse the XML Document into a DOM Object
        $doc = new \DOMDocument();
        $doc->loadXML($xml_document);
        $xmldata = $doc->saveXML();

        $xmlarray = simplexml_load_string($xmldata);

        if(Order::where('order_id', $xmlarray->order->order_id)->count()==0){
            if(sizeof($xmlarray->order)!=0){
            $order = new Order();            
            $order->order_id = $xmlarray->order->order_id;
            $order->order_date = date('Y-m-d H:i:s',strtotime($xmlarray->order->order_date));
            $order->type = $xmlarray->order->order_type;
            $order->bill_to_company = $xmlarray->order->bill_to_company;
            $order->bill_to_title = $xmlarray->order->bill_to_title;
            $order->bill_to_first_name = $xmlarray->order->bill_to_first_name;
            $order->bill_to_last_name = $xmlarray->order->bill_to_last_name;
            $order->bill_to_address1 = $xmlarray->order->bill_to_address1;
            $order->bill_to_address2 = $xmlarray->order->bill_to_address2;
            $order->bill_to_city = $xmlarray->order->bill_to_city;
            $order->bill_to_state = $xmlarray->order->bill_to_state;
            $order->bill_to_zip = $xmlarray->order->bill_to_zip;
            $order->bill_to_country = $xmlarray->order->bill_to_country;
            $order->bill_to_country_code = $xmlarray->order->bill_to_country_code;
            $order->tax_country = $xmlarray->order->tax_country;
            $order->ship_to_company = $xmlarray->order->ship_to_company;
            $order->ship_to_title = $xmlarray->order->ship_to_title;
            $order->ship_to_first_name = $xmlarray->order->ship_to_first_name;
            $order->ship_to_last_name = $xmlarray->order->ship_to_last_name;
            $order->ship_to_address1 = $xmlarray->order->ship_to_address1;
            $order->ship_to_address2 = $xmlarray->order->ship_to_address2;
            $order->ship_to_city = $xmlarray->order->ship_to_city;
            $order->ship_to_state = $xmlarray->order->ship_to_state;
            $order->ship_to_zip = $xmlarray->order->ship_to_zip;
            $order->ship_to_country = $xmlarray->order->ship_to_country;
            $order->ship_to_country_code = $xmlarray->order->ship_to_country_code;
            $order->shipping_method = $xmlarray->order->shipping_method;
            $order->lift_gate = $xmlarray->order->lift_gate;
            $order->gift = $xmlarray->order->gift;
            $order->email = $xmlarray->order->email;
            $order->mailing_list = $xmlarray->order->mailing_list;
            $order->day_phone = $xmlarray->order->day_phone;
            $order->evening_phone = $xmlarray->order->evening_phone;
            $order->fax = $xmlarray->order->fax;
            $order->card_type = $xmlarray->order->card_type;
            $order->card_number = $xmlarray->order->card_number;
            $order->card_exp_month = $xmlarray->order->card_exp_month;
            $order->card_exp_year = $xmlarray->order->card_exp_year;
            $order->card_auth_ticket = $xmlarray->order->card_auth_ticket;
            $order->payment_method = $xmlarray->order->payment_method;
            $order->weight = $xmlarray->order->weight;
            $order->subtotal = $xmlarray->order->subtotal;
            $order->tax_rate = $xmlarray->order->tax_rate;
            $order->tax = $xmlarray->order->tax;
            $order->shipping_handling_total = $xmlarray->order->shipping_handling_total;
            $order->shipping_handling_total_discount = $xmlarray->order->shipping_handling_total_discount;
            $order->surcharge = $xmlarray->order->surcharge_transaction_fee;
            $order->surcharge_transaction_percentage = $xmlarray->order->surcharge_transaction_percentage;
            $order->surcharge = $xmlarray->order->surcharge;
            $order->total = $xmlarray->order->total;
            $order->currency_code = $xmlarray->order->currency_code;
            $order->actual_profit_analyzed = $xmlarray->order->actual_profit_analyzed;
            $order->actual_profit_review = $xmlarray->order->actual_profit_review;
            $order->actual_shipping = $xmlarray->order->actual_shipping;
            $order->actual_other_cost = $xmlarray->order->actual_other_cost;
            $order->actual_fullfillment = $xmlarray->order->actual_fulfillment;
            $order->actual_payment_processing = $xmlarray->order->actual_payment_processing;
            $order->actual_profit = $xmlarray->order->actual_profit;
            $order->gift_certificate_amount = $xmlarray->order->gift_certificate_amount;
            $order->special_instructions = $xmlarray->order->special_instructions;
            $order->merchant_notes = $xmlarray->order->merchant_notes;
            $order->subtotal_discount = $xmlarray->order->subtotal_discount;
            $order->gift_charge_accounting_code = $xmlarray->order->gift_charge_accounting_code;
            $order->gift_wrap_accounting_code = $xmlarray->order->gift_wrap_accounting_code;
            $order->screen_branding_theme_code = $xmlarray->order->screen_branding_theme_code;
            $order->insureship_available = $xmlarray->order->insureship_available;
            $order->insureship_separate = $xmlarray->order->insureship_separate;
            $order->insureship_wanted = $xmlarray->order->insureship_wanted;
            $order->has_customer_profile = $xmlarray->order->has_customer_profile;
            $order->customer_ip_address = $xmlarray->order->customer_ip_address;
            $order->upsell_path_code = $xmlarray->order->upsell_path_code;
            $order->test_order = $xmlarray->order->test_order;
            $order->current_stage = $xmlarray->order->current_stage;
            $order->payment_status = $xmlarray->order->payment_status;               
            $order->save();
        }
        
        if(sizeof($xmlarray->order->item)!=0){
           
            foreach($xmlarray->order->item as $key=>$items){
             $item = new Item();    
                $item->order_id = $items->order_id;
                $item->item_id = (($items->item_id=='RCHBULLY4Z')?'RCHBULLY8Z':(($items->item_id=='FLEABULLY4Z')?'FLEABULLY8Z':$items->item_id));
                $item->item_index = $items->item_index;
                $item->quantity = $items->quantity;
                $item->description = $items->description;
                $item->cost = $items->cost;
                $item->unit_cost_with_discount = $items->unit_cost_with_discount;
                $item->total_cost_with_discount = $items->total_cost_with_discount;
                $item->cogs = $items->cogs;
                $item->actual_cogs = $items->actual_cogs;
                $item->country_of_origin = $items->country_of_origin;
                $item->item_weight = $items->item_weight;
                $item->tax_free = $items->tax_free;
                $item->special_product_type = $items->special_product_type;
                $item->free_shipping = $items->free_shipping;
                $item->accounting_code = $items->accounting_code;
                $item->discount = $items->discount;
                $item->distribution_center_code = $items->distribution_center_code;
                $item->kit = $items->kit;
                $item->kit_component = $items->kit_component;
               $item->save();
            }
        }
        if(sizeof($xmlarray->order->coupon)!=0){
            foreach($xmlarray->order->coupon as $key=>$coupons){
                $coupon = new Coupon();
                $coupon->order_id = $coupons->order_id;
                $coupon->coupon_code = $coupons->coupon_code;
                $coupon->coupon_accounting_code = $coupons->coupon_accounting_code;
                $coupon->base_coupon_code = $coupons->base_coupon_code;
                $coupon->save();
            }
        }
        if(sizeof($xmlarray->order->transaction_details->transaction_detail)!=0){
            $c = 0;
            foreach($xmlarray->order->transaction_details->transaction_detail as $tkey=>$transactions){
                
                $transaction = new Transaction();
                $transaction->id = $transactions->transaction_id;
                $transaction->gateway = $transactions->transaction_gateway;
                $transaction->created_at = date('Y-m-d H:i:s',strtotime($transactions->transaction_timestamp));
                $transaction->successful = $transactions->transaction_successful;
                $transaction->order_id = $xmlarray->order->order_id;
                $transaction->save();
                
                foreach($xmlarray->order->transaction_details->transaction_detail[$c]->extended_details->extended_detail as $extkey=>$exttransactions){
                    $exttransaction = new ExtendedDetail();                    
                    $exttransaction->transaction_id = $transactions->transaction_id;
                    $exttransaction->name = $exttransactions->extended_detail_name;
                    $exttransaction->value = $exttransactions->extended_detail_value;
                    $exttransaction->save();
                }
                $c++;                
            }            
        }
            echo "Order ID ".$xmlarray->order->order_id." was successfully imported via Status Changed.";
        }else{
            if(sizeof($xmlarray->order)!=0){
                $order = Order::where('order_id', $xmlarray->order->order_id)->first();
                $order->order_date = date('Y-m-d H:i:s',strtotime($xmlarray->order->order_date));
                $order->type = $xmlarray->order->order_type;
                $order->bill_to_company = $xmlarray->order->bill_to_company;
                $order->bill_to_title = $xmlarray->order->bill_to_title;
                $order->bill_to_first_name = $xmlarray->order->bill_to_first_name;
                $order->bill_to_last_name = $xmlarray->order->bill_to_last_name;
                $order->bill_to_address1 = $xmlarray->order->bill_to_address1;
                $order->bill_to_address2 = $xmlarray->order->bill_to_address2;
                $order->bill_to_city = $xmlarray->order->bill_to_city;
                $order->bill_to_state = $xmlarray->order->bill_to_state;
                $order->bill_to_zip = $xmlarray->order->bill_to_zip;
                $order->bill_to_country = $xmlarray->order->bill_to_country;
                $order->bill_to_country_code = $xmlarray->order->bill_to_country_code;
                $order->tax_country = $xmlarray->order->tax_country;
                $order->ship_to_company = $xmlarray->order->ship_to_company;
                $order->ship_to_title = $xmlarray->order->ship_to_title;
                $order->ship_to_first_name = $xmlarray->order->ship_to_first_name;
                $order->ship_to_last_name = $xmlarray->order->ship_to_last_name;
                $order->ship_to_address1 = $xmlarray->order->ship_to_address1;
                $order->ship_to_address2 = $xmlarray->order->ship_to_address2;
                $order->ship_to_city = $xmlarray->order->ship_to_city;
                $order->ship_to_state = $xmlarray->order->ship_to_state;
                $order->ship_to_zip = $xmlarray->order->ship_to_zip;
                $order->ship_to_country = $xmlarray->order->ship_to_country;
                $order->ship_to_country_code = $xmlarray->order->ship_to_country_code;
                $order->shipping_method = $xmlarray->order->shipping_method;
                $order->lift_gate = $xmlarray->order->lift_gate;
                $order->gift = $xmlarray->order->gift;
                $order->email = $xmlarray->order->email;
                $order->mailing_list = $xmlarray->order->mailing_list;
                $order->day_phone = $xmlarray->order->day_phone;
                $order->evening_phone = $xmlarray->order->evening_phone;
                $order->fax = $xmlarray->order->fax;
                $order->card_type = $xmlarray->order->card_type;
                $order->card_number = $xmlarray->order->card_number;
                $order->card_exp_month = $xmlarray->order->card_exp_month;
                $order->card_exp_year = $xmlarray->order->card_exp_year;
                $order->card_auth_ticket = $xmlarray->order->card_auth_ticket;
                $order->payment_method = $xmlarray->order->payment_method;
                $order->weight = $xmlarray->order->weight;
                $order->subtotal = $xmlarray->order->subtotal;
                $order->tax_rate = $xmlarray->order->tax_rate;
                $order->tax = $xmlarray->order->tax;
                $order->shipping_handling_total = $xmlarray->order->shipping_handling_total;
                $order->shipping_handling_total_discount = $xmlarray->order->shipping_handling_total_discount;
                $order->surcharge = $xmlarray->order->surcharge_transaction_fee;
                $order->surcharge_transaction_percentage = $xmlarray->order->surcharge_transaction_percentage;
                $order->surcharge = $xmlarray->order->surcharge;
                $order->total = $xmlarray->order->total;
                $order->currency_code = $xmlarray->order->currency_code;
                $order->actual_profit_analyzed = $xmlarray->order->actual_profit_analyzed;
                $order->actual_profit_review = $xmlarray->order->actual_profit_review;
                $order->actual_shipping = $xmlarray->order->actual_shipping;
                $order->actual_other_cost = $xmlarray->order->actual_other_cost;
                $order->actual_fullfillment = $xmlarray->order->actual_fulfillment;
                $order->actual_payment_processing = $xmlarray->order->actual_payment_processing;
                $order->actual_profit = $xmlarray->order->actual_profit;
                $order->gift_certificate_amount = $xmlarray->order->gift_certificate_amount;
                $order->special_instructions = $xmlarray->order->special_instructions;
                $order->merchant_notes = $xmlarray->order->merchant_notes;
                $order->subtotal_discount = $xmlarray->order->subtotal_discount;
                $order->gift_charge_accounting_code = $xmlarray->order->gift_charge_accounting_code;
                $order->gift_wrap_accounting_code = $xmlarray->order->gift_wrap_accounting_code;
                $order->screen_branding_theme_code = $xmlarray->order->screen_branding_theme_code;
                $order->insureship_available = $xmlarray->order->insureship_available;
                $order->insureship_separate = $xmlarray->order->insureship_separate;
                $order->insureship_wanted = $xmlarray->order->insureship_wanted;
                $order->has_customer_profile = $xmlarray->order->has_customer_profile;
                $order->customer_ip_address = $xmlarray->order->customer_ip_address;
                $order->upsell_path_code = $xmlarray->order->upsell_path_code;
                $order->test_order = $xmlarray->order->test_order;
                $order->current_stage = $xmlarray->order->current_stage;
                
                if ( isset( $xmlarray->order->subtotal_discount_refunded ) ) {
                    $order->subtotal_discount_refunded= $xmlarray->order->subtotal_discount_refunded;
                }
                if ( isset( $xmlarray->order->subtotal_refunded ) ) {
                   $order->subtotal_refunded = $xmlarray->order->subtotal_refunded;
                }
                if ( isset( $xmlarray->order->tax_refunded ) ) {
                    $order->tax_refunded = $xmlarray->order->tax_refunded;
                }
                if ( isset( $xmlarray->order->shipping_handling_refunded ) ) {
                    $order->shipping_handling_refunded = $xmlarray->order->shipping_handling_refunded;
                }
                if ( isset( $xmlarray->order->buysafe_refunded ) ){
                    $order->buysafe_refunded = $xmlarray->order->buysafe_refunded;
                }
                if ( isset( $xmlarray->order->total_refunded ) ){
                    $order->total_refunded = $xmlarray->order->total_refunded;
                }
                if ( isset( $xmlarray->order->refund_by_user ) ){
                    $order->refund_by_user = $xmlarray->order->refund_by_user;
                }
                if ( isset( $xmlarray->order->refund_dts ) ){
                    $order->refund_dts = date('Y-m-d H:i:s',strtotime($xmlarray->order->refund_dts));
                }
                $order->save();
                
                echo "Order ID ".$xmlarray->order->order_id." was successfully updated.";
            }
        }

        //SEND TO MAILWIZZ

            $email = $xmlarray->order->email;
            $order_id = $xmlarray->order->order_id;
            $fname = $xmlarray->order->bill_to_first_name;
            $lname = $xmlarray->order->bill_to_last_name;
            $company = $xmlarray->order->bill_to_company;
            $phone = $xmlarray->order->day_phone;
            $eveningphone = $xmlarray->order->evening_phone;

            $listclass = array(
                '3' => array(
                       'members' => array(  'BBBDTR1G','BUGBUDDYRPL','BBBDTR32Z','BBBDTR55G','BBBDTR5G','BBBULLY1G','BBBULLY32Z','BBBULLY4Z','BBBULLY55G','BBBULLY5G','BBBULLY5GS', 'BBBULLY1GS', 'BBBULLY32ZS','BBBULLY8ZSPC' ),
                        'interest' => 'Bed Bugs'
                    ),
                '23'=> array(
                       'members' => array(  'RSTRZ12X1','RSTARM1G','RSTARM32Z','RSTARM4Z','RSTARM55G','RSTARM5G','RSTRZR1G','RSTRZR1GS','RSTRZR32Z','RSTRZR32ZS','RSTRZR4Z','RSTRZR55G','RSTRZR5G'),
                        'interest' => 'Rust'
                    )
            );

            $prodids = array();
            foreach($xmlarray->order->item as $key=>$items){
                if($items->kit_component=='N'){
                    $prodids[] = $items->item_id;
                }
            }
            $prodids = implode(',', $prodids);
            $url = "https://www.dreamwareenterprise.com/scripts/mailwizz/create_subscriber.php?Email=$email&OrderId=$order_id&BillingFirstName=$fname&BillingLastName=$lname&BillingCompany=$company&ProductIds=$prodids&ShippingDayPhone=$phone&ShippingEveningPhone=$eveningphone";
            
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
        //END OF SENDING TO MAILWIZZ
    }
    
}
