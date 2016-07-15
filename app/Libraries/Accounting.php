<?php

namespace App\Libraries;

use Parser;

/**
 * Class Accounting
 *
 * Uses for process all for accounting
 *
 * @package App\Libraries
 *
 */
class Accounting
{
    /**
     *  get information by email
     *
     * @param $email
     * @return $result
     */
    public static function infoContact($email)
    {
        $url = env('OPTIMAL_CLINIC');
        // set the api parameters
        $params = array(
            'api_key' => env('OPTIMAL_API_KEY'),
            'api_action' => 'contact_view_email',
            'api_output' => 'json',
            'email' => $email,
        );

        // build the api url
        $api = $url . '/admin/api.php?' . http_build_query($params);
        // start the curl
        $request = curl_init($api);
        // set to 0 to eliminate header info from response
        curl_setopt($request, CURLOPT_HEADER, 0);
        // Returns response data instead of TRUE(1)
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        // uncomment if you get no gateway response and are using HTTPS
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);


        $response = curl_exec($request);
        curl_close($request);
        $result = Parser::json($response);
        return $result;
    }

    /**
     * add contact to optimal clinic
     *
     */
    public static function AddContact($order)
    {

        $url = env('OPTIMAL_CLINIC');
        // set the api parameters
        $params = array(
            'api_key' => env('OPTIMAL_API_KEY'),
            'api_action' => 'contact_add',
            'api_output' => 'json',

        );
        // here we define the data we are posting in order to perform an update
        $post = array(
            'email' => $order->email,
            'first_name' => $order->bill_to_first_name,
            'last_name' => $order->bill_to_last_name,
            'phone' => $order->day_phone,
            'orgname' => ($order->bill_to_company != '' ? $order->bill_to_company : ''),
            'tags' => 'api',
            'p[4]' => 4,
            'status[4]' => 1,
            'instantresponders[4]' => 1,

        );
        // build the api url
        $api = $url . '/admin/api.php?' . http_build_query($params);
        // start the curl
        $curl = curl_init($api);
        // build the api post
        $parameter_query = http_build_query($post);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parameter_query);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // uncomment if you get no gateway response and are using HTTPS
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($curl);

        curl_close($curl);
        $result = Parser::json($response);
        return $result;
    }

    /**
     * Contact edit API call
     *
     * return $result
     */
    public static function EditContact($order, $information)
    {
        $total_spend = empty($information['fields'][1]['val']) ? 0 : $information['fields'][1]['val'];
        $total_order = empty($information['fields'][5]['val']) ? 0 : $information['fields'][5]['val'];
        $total_quantity = 0;
        $items = $order->items()->get();
        foreach ($items as $item) {
            if ($item->kit == 'N') {
                $total_quantity += $item->quantity;
            }
        }
        $url = env('OPTIMAL_CLINIC');
        // set the api parameters
        $params = array(
            'api_key' => env('OPTIMAL_API_KEY'),
            'api_action' => 'contact_edit',
            'api_output' => 'json',

        );

        $post = array(
            'id' => $information['id'],
            // example contact ID to modify
            'phone' => $order->day_phone,
            'orgname' => $order->bill_to_company,
            'field[%EVENING_PHONE%,0]' => $order->evening_phone,
            // using the personalization tag instead (make sure to encode the key)
            'field[%TOTAL_SPENT%,0]' => floatval($total_spend) + $order->total,
            // using the personalization tag instead (make sure to encode the key)
            'field[%TOTAL_ORDERS%,0]' => $total_order + $total_quantity,
            'p[4]' => 4
        );
        // build the api url
        $api = $url . '/admin/api.php?' . http_build_query($params);
        // start the curl
        $curl = curl_init($api);
        // build the api post
        $parameter_query = http_build_query($post);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parameter_query);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // uncomment if you get no gateway response and are using HTTPS
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($curl);

        curl_close($curl);
        $result = Parser::json($response);
        return $result;
    }

    /**
     * Add deals to contact
     *
     * return $result
     */
    public static function deals($order, $information)
    {
        $url = env('OPTIMAL_CLINIC');
        // set the api parameters
        $params = array(
            'api_key' => env('OPTIMAL_API_KEY'),
            'api_action' => 'deal_add',
            'api_output' => 'json',

        );
        // here we define the data we are posting in order to perform an update
        $post = array(
            'title' => $order->order_id,
            'value' => $order->total,
            'value_format' => 'dollars',
            'currency' => 'usd',
            'pipeline' => '2',
            'stage' => '7',
            'owner' => '1',
            'contactid' => $information['id'],
            'contact_name' => $information['first_name'] . ' ' . $information['last_name'],
            'organization' => $order->bill_to_company
        );

        // build the api url
        $api = $url . '/admin/api.php?' . http_build_query($params);
        // start the curl
        $curl = curl_init($api);
        // build the api post
        $parameter_query = http_build_query($post);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parameter_query);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // uncomment if you get no gateway response and are using HTTPS
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($curl);

        curl_close($curl);
        $result = Parser::json($response);
        return $result;
    }

    /**
     * Addnote to deal of the contact
     * @param $order
     * @param $id
     *
     */
    public static function AddNote($order, $id)
    {
        $url = env('OPTIMAL_CLINIC');
        // set the api parameters
        $params = array(
            'api_key' => env('OPTIMAL_API_KEY'),
            'api_action' => 'deal_note_add',
            'api_output' => 'json',

        );
        $subtotal = number_format($order->subtotal - $order->subtotal_discount, 2);
        $note = $order->order_id. "\n";
        $items = $order->items()->get();
        foreach ($items as $item) {
            if ($item->kit == 'N') {
                $note .= "\n" . $item->item_id . "-(" . $item->quantity . ")-" . $item->cost;
            }
        }
        $note .= "\n\nSubtotal: " . $subtotal;
        $note .= "\nShipping: " . $order->shipping_handling_total;
        $note .= "\nDiscounts: -" . number_format($order->subtotal_discount, 2);
        $note .= "\nTotal: " .$order->total;
        // here we define the data we are posting in order to perform an update
        $post = array(
            'note' => $note,
            'dealid' => $id,
            'owner' => '1'
        );

        // build the api url
        $api = $url . '/admin/api.php?' . http_build_query($params);
        // start the curl
        $curl = curl_init($api);
        // build the api post
        $parameter_query = http_build_query($post);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parameter_query);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // uncomment if you get no gateway response and are using HTTPS
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($curl);

        curl_close($curl);
        $result = Parser::json($response);
        return $result;

    }
}