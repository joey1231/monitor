<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
   return redirect('/dashboard');
});

// Cron jobs Proccess
Route::get('/order/proccess','OrderController@proccessCron');
Route::get('/order/proccessCronPartialShiptatus','OrderController@proccessCronPartialShiptatus');
Route::get('/order/proccessCronProcessingStatus','OrderController@proccessCronProcessingStatus');
Route::get('/order/proccessCronNullStatus','OrderController@proccessCronNullStatus');
Route::get('/order/proccessOrder','OrderController@processOrdersCron');
Route::get('/item/processinventory','ItemController@processFetchInventoryCron');
// Cron jobs Proccess
Route::get('/order/bulkacproccess','OrderController@proccessBulkAc');
Route::get('/order/bulksearsproccess','OrderController@proccessBulkSears');

Route::post('/webhook/senductodb', 'WebHookController@SendUCtoDB');
Route::post('/webhook/updateuctodb', 'WebHookController@UpdateUCtoDB');
// Start the middleware as-is; this brings up the Session controllers as well as CSRF token managers.
Route::group(['middleware' => ['web']], function () {

    // Login for users
    Route::get('/login', 'Auth\AuthController@showLoginForm');
    Route::post('/login', 'Auth\AuthController@login');
    Route::get('/logout', 'Auth\AuthController@logout');


    // Users dashboard
    Route::group(['middleware' => 'auth:users'], function () {
        Route::resource('/dashboard', 'DashboardController');

        Route::get('/order/manualgetstatus/{order_id}', 'OrderController@manualGetStatus');
        Route::get('/order/uctracking/', 'OrderController@csvTracking');
        Route::get('/order/searsexport/', 'OrderController@ExportSears');
        Route::post('/order/tracking', 'OrderController@uploadTracking');
        Route::get('/order/orderidautocomplete', 'OrderController@orderidautocomplete');
         Route::get('/order/frontautocomplete', 'OrderController@frontautocomplete');
        Route::get('/order/stage/', 'OrderController@OrderStage');
        Route::post('/order/accounting', 'OrderController@SendToAccounting');
        Route::post('/order/sears', 'OrderController@SearOrderSear');
        Route::post('/order/ordertoacbulk','OrderController@OrderToAcBulk');
        Route::post('/order/ordertosearsbulk','OrderController@OrderToSearsBulk');
        Route::resource('/order', 'OrderController');
        Route::get('/nonietest/test', 'NonieTestController@test');

        Route::get('item/delete/{id}/{order_id}', 'ItemController@deleteItem');
        Route::get('item/create/{order_id}', 'ItemController@addItem');
        Route::post('item/create/{order_id}', 'ItemController@saveItem');

        Route::resource('/item', 'ItemController');

    });
});
