<?php
namespace App\Libraries;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ActiveCampaign
 *
 * @author Nonie
 */
class ActiveCampaign {
    public static function CreateACContact($OrderID){
         $url = 'https://optimalchemical.api-us1.com';

    

        $orderdata = view_order($orderid);



        if(sizeof($orderdata)==0){

            return;

        }



        $params = array(



            // the API Key can be found on the "Your Settings" page under the "API" tab.

            // replace this with your API Key

            'api_key'      => '5cf29300fed0001031ad28332edf952ecacf7082ae347cc58e62c189100b82e05964c2d2',



            // this is the action that adds a contact

            'api_action'   => 'contact_add',



            // define the type of output you wish to get back

            // possible values:

            // - 'xml'  :      you have to write your own XML parser

            // - 'json' :      data is returned in JSON format and can be decoded with

            //                 json_decode() function (included in PHP since 5.2.0)

            // - 'serialize' : data is returned in a serialized format and can be decoded with

            //                 a native unserialize() function

            'api_output'   => 'serialize',

        );



        // here we define the data we are posting in order to perform an update

        $post = array(  

            'email'                    => $orderdata->orders->{'order-details'}->{'shipping-address'}->{'ship-to-email'},

            'first_name'               => $orderdata->orders->{'order-details'}->{'shipping-address'}->{'ship-to-first-name'},

            'last_name'                => $orderdata->orders->{'order-details'}->{'shipping-address'}->{'ship-to-last-name'},

            'phone'                    => $orderdata->orders->{'order-details'}->{'shipping-address'}->{'ship-to-phone'},

            'tags'                     => 'api',

            //'ip4'                    => '127.0.0.1',



            // any custom fields

            //'field[345,0]'           => 'field value', // where 345 is the field ID

            //'field[%PERS_1%,0]'      => 'field value', // using the personalization tag instead (make sure to encode the key)



            // assign to lists:

            'p[4]'                   => 4, // example list ID (REPLACE '123' WITH ACTUAL LIST ID, IE: p[5] = 5)

            'status[4]'              => 1, // 1: active, 2: unsubscribed (REPLACE '123' WITH ACTUAL LIST ID, IE: status[5] = 1)

            //'form'          => 1001, // Subscription Form ID, to inherit those redirection settings

            //'noresponders[123]'      => 1, // uncomment to set "do not send any future responders"

            //'sdate[123]'             => '2009-12-07 06:00:00', // Subscribe date for particular list - leave out to use current date/time

            // use the folowing only if status=1

            'instantresponders[4]' => 1, // set to 0 to if you don't want to sent instant autoresponders

            //'lastmessage[123]'       => 1, // uncomment to set "send the last broadcast campaign"



            //'p[]'                    => 345, // some additional lists?

            //'status[345]'            => 1, // some additional lists?

        );



        // This section takes the input fields and converts them to the proper format

        $query = "";

        foreach( $params as $key => $value ) $query .= $key . '=' . urlencode($value) . '&';

        $query = rtrim($query, '& ');



        // This section takes the input data and converts it to the proper format

        $data = "";

        foreach( $post as $key => $value ) $data .= $key . '=' . urlencode($value) . '&';

        $data = rtrim($data, '& ');



        // clean up the url

        $url = rtrim($url, '/ ');



        // This sample code uses the CURL library for php to establish a connection,

        // submit your request, and show (print out) the response.

        if ( !function_exists('curl_init') ) die('CURL not supported. (introduced in PHP 4.0.2)');



        // If JSON is used, check if json_decode is present (PHP 5.2.0+)

        if ( $params['api_output'] == 'json' && !function_exists('json_decode') ) {

            die('JSON not supported. (introduced in PHP 5.2.0)');

        }



        // define a final API request - GET

        $api = $url . '/admin/api.php?' . $query;



        $request = curl_init($api); // initiate curl object

        curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response

        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)

        curl_setopt($request, CURLOPT_POSTFIELDS, $data); // use HTTP POST to send form data

        //curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment if you get no gateway response and are using HTTPS

        curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);



        $response = (string)curl_exec($request); // execute curl post and store results in $response



        // additional options may be required depending upon your server configuration

        // you can find documentation on curl options at http://www.php.net/curl_setopt

        curl_close($request); // close curl object

        

        if ( !$response ) {

            die('Nothing was returned. Do you have a connection to Email Marketing server?');

        }
        
        return $response;
    }
}
