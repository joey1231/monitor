<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Log;
use App\Models\Components\Order;
use App\Libraries\Accounting;
use App\Libraries\Sears;
use Illuminate\Support\Facades\Validator;
use App\Libraries\Utility;
use Storage;
use Excel;
use Illuminate\Support\Facades\Auth;
use App\Models\Components\Tracking;
use App\Libraries\XLSXWriter;
use DB;
use App\Models\Components\CronJob;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $order = new Order();


        if (!empty($request->searstat)) {
            $searstat_array = $request->searstat;
            $order = $order->where(function ($query) use ($searstat_array) {
                foreach ($searstat_array as $searstat) {
                    $query->orWhere('searstat', $searstat);
                }
            });
        }
        if (!empty($request->sent_to_ac)) {

            $order = $order->where('sent_to_ac', $request->sent_to_ac);
        }
         if (!empty($request->order_id)) {

            $order = $order->where('order_id', 'like','%'. $request->order_id.'%');
        }
         if (!empty($request->store_front)) {

            $order = $order->where('screen_branding_theme_code', 'like','%'. $request->store_front.'%');
        }
        if (!empty($request->sent_to_sears)) {

            $order = $order->where('sent_to_sears', $request->sent_to_sears);
        }
        if (!empty($request->sent_to_sears)) {

            $order = $order->where('sent_to_sears', $request->sent_to_sears);
        }
        if (!empty($request->current_stage)) {
            $current_stage_array = $request->current_stage;
            $order = $order->where(function ($query) use ($current_stage_array) {
                foreach ($current_stage_array as $current_stage) {
                    $query->orWhere('current_stage', $current_stage);
                }
            });
        }
        if (!empty($request->dtrange)) {
            $date_array = explode('-', $request->dtrange);

            $order = $order->whereBetween('order_date',
                array(date('Y-m-d', strtotime($date_array[0])).' 00:00:00', date('Y-m-d', strtotime($date_array[1])).' 23:59:59'));
        }
        
        $page = isset($request->page) ? $request->page : 0;
        $orders = $order->skip($page * 50)->orderBy('order_date', 'DESC')->paginate(50);
        $counter = $page <= 1 ? 1 : ($page - 1) * 50;

        return view('orders.index', array('orders' => $orders, 'count' => $counter, 'request' => $request->all(),'page'=>'order'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info($request);
        $order = new Order();
        $order->fill($request->all());
        $order->save();
        return response(['message' => 'No error'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // get order by id
        $order = Order::where('order_id', $id)->first();
        if (!$order) {
            return redirect('order');
        }
        $address = "";
        if ($order->ship_to_address1 != '') {
            $address .= $order->ship_to_address1 . ' ';
        }
        if ($order->ship_to_address2 != '') {
            $address .= $order->ship_to_address2 . ' ';
        }
        if ($order->ship_to_city != '') {
            $address .= $order->ship_to_city . ' ';
        }
        if ($order->ship_to_state != '') {
            $address .= $order->ship_to_state . ' ';
        }
        if ($order->ship_to_country != '') {
            $address .= $order->ship_to_country . ' ';
        }
        if ($order->ship_to_zip != '') {
            $address .= $order->ship_to_zip . ' ';
        }
        $address_status = Utility::CheckAddress($address);
        return view('orders.order-details', array('order' => $order, 'address_status' => $address_status, 'address' => $address,'page'=>'order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::where('order_id', $id)->first();
        if ($order) {
            $order->fill($request->all());
            $order->save();
        }
        return redirect('order/' . $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * send to Accounting process
     *
     *  ajax call
     * @return View
     */
    public function SendToAccounting(Request $request)
    {

        $validateRule = [
            'id' => 'required',
        ];
        $validator = Validator::make($request->all(), $validateRule);
        //Check if the erros is not empty
        if ($validator->fails()) {
            //get all the errors;
            $error = $validator->errors()->all();
            $msg = '';
            foreach ($error as $i => $v) {
                $msg .= $v . '<br/>';
            }
            return response(['message' => $msg], 202);
        }
        // get order by id
        $order = Order::where('order_id', $request->id)->first();
        if (is_null($order)) {
            return response(['message' => 'Order Not found'], 202);
        }
        // call api to get information of contact
        $information = Accounting::infoContact($order->email);
        // sanity check for api response
        if ($information['result_code'] == '0') {
            // add contact via api
            $add_contact = Accounting::AddContact($order);
            // sanity check api response
            if ($add_contact['result_code'] == '0') {
                return response(['message' => $add_contact['result_message']], 202);
            }

            // call api to get information of contact
            $information = Accounting::infoContact($order->email);
        }
        // edit contact via api
        $contact_edit = Accounting::EditContact($order, $information);
        // sanity check for api response
        if ($contact_edit['result_code'] == '0') {
            return response(['message' => $add_contact['result_message']], 202);
        }

        $add_deal = Accounting::deals($order, $information);
        // sanity check for api response
        if ($add_deal['result_code'] == '0') {
            return response(['message' => $add_deal['result_message']], 202);
        }
        $add_note = Accounting::AddNote($order, $add_deal['id']);
        // sanity check for api response
        if ($add_note['result_code'] == '0') {
            return response(['message' => $add_note['result_message']], 202);
        }
        $order->sent_to_ac = 'Y';
        $order->sent_to_ac_by = Auth::guard('users')->user()->email;
        $order->sent_to_ac_date = \Carbon\Carbon::now()->toDateTimeString();
        $order->save();

        // $view = view('orders.send-accounting', ['order' => $order, 'information' => $information]);
        //return response(['message' => $view], 200);
        return response(['message' => 'Successfully send to Campaign', 'data' => $order], 200);
    }

    /**
     * Send to Sears
     *
     * Ajax call
     *
     */
    public function SearOrderSear(Request $request)
    {
        $validateRule = [
            'id' => 'required',
        ];
        $validator = Validator::make($request->all(), $validateRule);
        //Check if the erros is not empty
        if ($validator->fails()) {
            //get all the errors;
            $error = $validator->errors()->all();
            $msg = '';
            foreach ($error as $i => $v) {
                $msg .= $v . '<br/>';
            }
            return response(['message' => $msg], 202);
        }
        // get order by id
        $order = Order::where('order_id', $request->id)->first();
        // make order xml
        $xml = Sears::createXML($order);
        // sanity check if xml is valid
        if (Sears::validateXML($xml)) {
            // send the xml to sear
            $sear_response = Sears::Send($xml);
            // sanity check if successfully send
            if (isset($sear_response['document-id'])) {
                $status = Sears::GetOrderStatus($order);
                $order->searstat = $status;
                $order->sent_to_sears = 'Y';
                $order->sent_to_sears_date = \Carbon\Carbon::now()->toDateTimeString();
                $order->sent_to_sears_by = Auth::guard('users')->user()->email;
                $order->sears_response = $sear_response['document-id'];
                $order->save();
                return response(['message' => 'Successfully send to Sear', 'data' => $sear_response, 'xml' => $xml], 200);
            } else {
                return response(['message' => 'Failed to send to sear', 'xml' => $xml], 202);
            }
        } else {
            return response(['message' => 'Invalid XML', 'xml' => $xml, 'v' => Sears::validateXML($xml)], 202);
        }
    }

    /**
     *  OrderReady
     *
     *  return view
     */
    public function OrderStage(Request $request)
    {

        $order = Order::where('order_stage', $request->stage);
        $page = isset($request->page) ? $request->page : 0;
        $orders = $order->skip($page * 50)->orderBy('order_date', 'DESC')->paginate(50);
        $counter = $page <= 1 ? 1 : ($page - 1) * 50;
        return view('orders.order-management',
            array(
                'orders' => $orders,
                'count' => $counter,
                'request' => $request->all(),
                'stage' => $request->stage,
                'totalReady' => Order::where('order_stage', 'Ready')->count(),
                'totalReview' => Order::where('order_stage', 'Review')->count(),
                'totalProcessed' => Order::where('order_stage', 'Processed')->count(),
                'totalShipped' => Order::where('order_stage', 'Shipped')->count(),
                'totalCancelled' => Order::where('order_stage', 'Cancelled')->count(),
                'totalPartial' => Order::where('order_stage', 'Partial Ship')->count(),
                'page'=>'mgt'

            ));
    }

    /**
     * Upload tracking for order
     *
     */
    public function uploadTracking(Request $request)
    {

        if (!$request->hasFile('upload_sears_tracking')) {
            return redirect()->back()->withErrors(['error' => 'Empty file uploaded']);
        }
        $file = $request->file('upload_sears_tracking');
        // check if csv file
        if ($file->getClientOriginalExtension() != 'csv') {
            return redirect()->back()->withErrors(['error' => 'Invalid file format']);
        }
        // creation of csv file to be upload
        $filename = Auth::guard('users')->user()->id . '_' . \Carbon\Carbon::now()->timestamp . '.csv';
        // moving file from servers tmp directory into the sites target directory /public/tmp/contacts
        Storage::disk('tracking')->put($filename, \File::get($request->file('upload_sears_tracking')));
        $data = Excel::load(public_path(env('TRACKING_DIR')) . "/" . $filename)->all();
        $trackings = array();
        foreach ($data->toArray() as $key => $value) {

            $order_id = preg_replace("/^(\S{5})(\d{12})(\d{6})$/", "$1-$2-$3", $value['seller_order_id']);
            if (Order::where('order_id', $order_id)->count() != 0 && Tracking::where('order_id', $order_id)->where('sku', $value['seller_item_id'])->where('number', $value['tracking_number'])->count() == 0) {
                $tracking = new Tracking();
                $tracking->number = $value['tracking_number'];
                $tracking->order_id = $order_id;
                $tracking->sku = $value['seller_item_id'];
                $tracking->url = ($value['carrier'] == 'UPS' ? "http://wwwapps.ups.com/WebTracking/processInputRequest?sort_by=status&tracknums_displayed=1&TypeOfInquiryNumber=T&loc=en_US&InquiryNumber1=$value[tracking_number]&track.x=0&track.y=0" : "");
                $tracking->save();
                $trackings[] = $tracking;
            }
        }
        return view('tracking.result', ['file' => $file, 'trackings' => $trackings,'page'=>'order']);
    }

    /**
     * Export data for Sears
     *
     */
    public function ExportSears(Request $request)
    {

        try {
            $data = array(
                array(
                    'Seller Order ID',
                    'Displayable Order ID',
                    'Displayable Order Date & Time',
                    'Shipping SLA',
                    'Seller Item ID',
                    'Quantity',
                    'Requested Fulfillment Policy',
                    'Displayable Item Price',
                    'Displayable Sales Tax',
                    'Displayable Message',
                    'Ship To First Name',
                    'Ship To Middle Name',
                    'Ship To Last Name',
                    'Ship To Street Address',
                    'Ship To Street Address Line 2',
                    'Ship To City',
                    'Ship To State',
                    'Ship To Zip',
                    'Ship To Email',
                    'Ship To Phone',
                    'Displayable Order Total Shipping & Handling',
                    'Displayable Comment'
                )
            );
            $orders = DB::table('orders')->join('items', 'orders.order_id', '=', 'items.order_id')->where('items.kit', 'N')->where('items.send_to_sears', 'Y')->where('orders.test_order', 'false');
             //dd($orders->get());
            if (!empty($request->searstat)) {
                $searstat_array = $request->searstat;
                $orders = $orders->where(function ($query) use ($searstat_array) {
                    foreach ($searstat_array as $searstat) {
                        $query->orWhere('orders.searstat', $searstat);
                    }
                });
            }
            if (!empty($request->sent_to_ac)) {

                $orders = $orders->where('orders.sent_to_ac', $request->sent_to_ac);
            }
            if (!empty($request->sent_to_sears)) {

                $orders = $orders->where('orders.sent_to_sears', $request->sent_to_sears);
            }
             if (!empty($request->order_id)) {

                $ordes = $orders->where('orders.order_id', 'like','%'. $request->order_id.'%');
            }
             if (!empty($request->store_front)) {

                $orders = $orders->where('orders.screen_branding_theme_code', 'like','%'. $request->store_front.'%');
            }
            
            if (!empty($request->current_stage)) {
                $current_stage_array = $request->current_stage;
                $orders = $orders->where(function ($query) use ($current_stage_array) {
                    foreach ($current_stage_array as $current_stage) {
                        $query->orWhere('orders.current_stage', $current_stage);
                    }
                });
            }
            if (!empty($request->dtrange)) {
                    $date_array = explode('-', $request->dtrange);
                    $first_date =date('Y-m-d', strtotime($date_array[0])).' 00:00:00';
                    $second_date =date('Y-m-d', strtotime($date_array[1])).' 23:59:59';
                 
                    $orders = $orders->whereBetween('orders.order_date',
                    array($first_date, $second_date));
            } 
         
            $pages = ceil($orders->count() / 1000);
            
            for ($i = 0; $i < $pages; $i++) {
                $orders_data = $orders->skip($i * 1000)->take(1000)->get();
                foreach ($orders_data as $k => $order) {

                    $shipping_method = '';

                    if ($order->shipping_method == 'UPS: Ground') {

                        $shipping_method = 'Ground';

                    } else if ($order->shipping_method == 'UPS: 2nd Day Air') {

                        $shipping_method = 'Two-day';

                    } else if ($order->shipping_method == 'UPS: Next Day Air') {

                        $shipping_method = 'Next-day';

                    }

                    array_push($data, array(
                        str_replace('-', '', $order->order_id),
                        str_replace('-', '', $order->order_id),
                        date('Y-m-d', strtotime(($order->order_date))) . ' ' . date('H:i:s', strtotime($order->order_date)),
                        $shipping_method,
                        (($order->item_id == 'RCHBULLY4Z') ? 'RCHBULLY8Z' : (($order->item_id == 'FLEABULLY4Z') ? 'FLEABULLY8Z' : $order->item_id)),
                        $order->quantity,
                        'All Available',
                        number_format($order->total_cost_with_discount, 2),
                        '',
                        '',
                        $order->ship_to_first_name,
                        '',
                        $order->ship_to_last_name,
                        $order->ship_to_address1,
                        $order->ship_to_address2,
                        $order->ship_to_city,
                        (strtolower($order->ship_to_state) != 'dc') ? strtoupper($order->ship_to_state) : str_replace('dc', 'WA', strtolower($order->ship_to_state)),
                        ((strlen(preg_replace('/[^0-9]/', '', $order->ship_to_zip)) > 5) ? substr(preg_replace('/[^0-9]/', '', $order->ship_to_zip), 0, 5) : preg_replace('/[^0-9]/', '', $order->ship_to_zip)),
                        '',
                        ((strlen(preg_replace('/[^0-9]/', '', $order->day_phone)) > 5) ? substr(preg_replace('/[^0-9]/', '', $order->day_phone), -10) : preg_replace('/[^0-9]/', '', $order->day_phone)),
                        number_format($order->shipping_handling_total, 2),
                        ''
                    ));

                }
            }
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="FBS-Multi-Channel-Orders.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = new XLSXWriter();
            $writer->writeSheet($data, 'Data Format');
            $writer->writeToStdOut();

        } catch (\Exception $ex) {
            return response(['message' => $ex->getMessage()], 202);
        }
    }

    /**
     * export tracking csv
     *
     */
    public function csvTracking(Request $request)
    {

        $usage = DB::table('orders')->select('trackings.order_id as orderid', 'trackings.number as trackingnumber')->join('trackings', 'orders.order_id', '=', 'trackings.order_id')->where('trackings.number', '!=', '');
        if (!empty($request->searstat)) {
            $searstat_array = $request->searstat;
            $usage = $usage->where(function ($query) use ($searstat_array) {
                foreach ($searstat_array as $searstat) {
                    $query->orWhere('orders.searstat', $searstat);
                }
            });
        }
        if (!empty($request->sent_to_ac)) {

            $usage = $usage->where('orders.sent_to_ac', $request->sent_to_ac);
        }
        if (!empty($request->sent_to_sears)) {

            $usage = $usage->where('orders.sent_to_sears', $request->sent_to_sears);
        }
        if (!empty($request->order_id)) {

                $usage = $usage->where('orders.order_id', 'like','%'. $request->order_id.'%');
        }
        if (!empty($request->store_front)) {

                $usage = $usage->where('orders.screen_branding_theme_code', 'like','%'. $request->store_front.'%');
        }
        if (!empty($request->current_stage)) {
            $current_stage_array = $request->current_stage;
            $usage = $usage->where(function ($query) use ($current_stage_array) {
                foreach ($current_stage_array as $current_stage) {
                    $query->orWhere('orders.current_stage', $current_stage);
                }
            });
        }
        if (!empty($request->dtrange)) {
            $date_array = explode('-', $request->dtrange);

            $usage = $usage->whereBetween('orders.order_date',
                array(date('Y-m-d', strtotime($date_array[0])), date('Y-m-d', strtotime($date_array[1]))));
        }
        $usage = $usage->get();
        $usage_array = array_map(function ($item) {
            return (array)$item;
        }, $usage);
        try {
            // create and process the downloadable excel usage
            Excel::create('uctracking', function ($excel) use ($usage_array) {

                $excel->sheet('usage', function ($sheet) use ($usage_array) {

                    $sheet->fromArray($usage_array);

                });

            })->export('csv'); // export the excel

        } catch (\Exception $ex) {
            return response(['message' => $ex->getMessage()], 202);
        }


    }

    public function proccessCron()
    {
        set_time_limit(0);

        // checked if cron job
        $cron = CronJob::where('status', 0)->where('name', 'proccess')->count();
        if ($cron == 0) {
            //ob_start();
            ob_implicit_flush(true);
            // create new job
            $cron_job = new CronJob();
            $cron_job->name = 'proccess';
            $cron_job->status = 0;
            $cron_job->save();
            // get all order except all shipped searstat
            $orders = Order::where('searstat', 'No POs found')->orWhereNull('searstat');
            // get ceil for paging
            $pages = ceil($orders->count() / 1000);
            echo "$pages <br/>";
            // loop the poges
            for ($i = 0; $i < $pages; $i++) {
              
                    

                    $orders_data = $orders->skip($i * 1000)->take(1000)->orderBy('order_date', 'DESC')->get();
                    // loop the orders for proccess
                    foreach ($orders_data as $order) {

                         echo date('Y-m-d H:m:s',time()).'- started \n';
                        $status='';
                        // get the sears status
                           try {
                              $status = Sears::GetOrderStatus($order);
                            } catch (\Exception $ex) {
                                echo $ex->getMessage() . '<br/>';
                          }

                        $order_stage = "";
                        // check the status;
                        switch ($status) {
                            case "All Shipped":
                                $order_stage = "Shipped";
                                break;
                            case "Partially Shipped":
                                $order_stage = "Partial Ship";
                                break;
                            case"All Cancelled":
                                $order_stage = "Cancelled";
                                break;
                            case "Processing":
                                $order_stage = "Processed";
                                break;

                        }
                        // check if $order_stage is not empty;
                        if ($order_stage != '') {
                            $order->order_stage = $order_stage;
                        }
                        // set the status to searstat;
                        echo $status . " " . $order->order_id . "<br/>";
                        $order->searstat = $status;
                        // save
                        $order->save();
                      //  ob_flush();
                      //  ob_flush();
                       // flush();
                        // break;
                        sleep(10);
                        echo date('Y-m-d H:m:s',time()).'- mid ended \n';
                      
                        echo date('Y-m-d H:m:s',time()).'- ended \n';
                      //  break;
                    }

               
               //  break;
            }
              sleep(5);
            echo date('Y-m-d H:m:s',time()).'- mid ended update status \n';
            $cron_job->status = 1;
            $cron_job->save();
           // ob_end_flush();
        }

    }

    public function proccessCronNullStatus()
    {
        set_time_limit(0);

        // checked if cron job
        $cron = CronJob::where('status', 0)->where('name', 'null_status')->count();
        if ($cron == 0) {
            //ob_start();
            
            // create new job
            $cron_job = new CronJob();
            $cron_job->name = 'null_status';
            $cron_job->status = 0;
            $cron_job->save();
            ob_implicit_flush(true);
            // get all order except all shipped searstat
            $orders = Order::WhereNull('searstat');
            // get ceil for paging
            $pages = ceil($orders->count() / 1000);
            echo "$pages <br/>";
            // loop the poges
            for ($i = 0; $i < $pages; $i++) {
              
                    

                    $orders_data = $orders->skip($i * 1000)->take(1000)->orderBy('order_date', 'DESC')->get();
                    // loop the orders for proccess
                    foreach ($orders_data as $order) {

                         echo date('Y-m-d H:m:s',time()).'- started \n';
                        $status='';
                        // get the sears status
                           try {
                              $status = Sears::GetOrderStatus($order);
                            } catch (\Exception $ex) {
                                echo $ex->getMessage() . '<br/>';
                          }

                        $order_stage = "";
                        // check the status;
                        switch ($status) {
                            case "All Shipped":
                                $order_stage = "Shipped";
                                break;
                            case "Partially Shipped":
                                $order_stage = "Partial Ship";
                                break;
                            case"All Cancelled":
                                $order_stage = "Cancelled";
                                break;
                            case "Processing":
                                $order_stage = "Processed";
                                break;

                        }
                        // check if $order_stage is not empty;
                        if ($order_stage != '') {
                            $order->order_stage = $order_stage;
                        }
                        // set the status to searstat;
                        echo $status . " " . $order->order_id . "<br/>";
                        $order->searstat = $status;
                        // save
                        $order->save();
                      //  ob_flush();
                      //  ob_flush();
                       // flush();
                        // break;
                        sleep(5);
                        echo date('Y-m-d H:m:s',time()).'- mid ended \n';
                      
                        echo date('Y-m-d H:m:s',time()).'- ended \n';
                      //  break;
                    }

               
               //  break;
            }
             sleep(5);
            echo date('Y-m-d H:m:s',time()).'- mid ended mid ended update status \n';
            $cron_job->status = 1;
            $cron_job->save();
            //ob_end_flush();
        }

    }
    public function proccessCronProcessingStatus()
    {
        set_time_limit(0);

        // checked if cron job
        $cron = CronJob::where('status', 0)->where('name', 'Processing')->count();
        if ($cron == 0) {
            //ob_start();
            
            // create new job
            $cron_job = new CronJob();
            $cron_job->name = 'Processing';
            $cron_job->status = 0;
            $cron_job->save();
            ob_implicit_flush(true);
            // get all order except all shipped searstat
            $orders = Order::where('searstat', 'Processing');
            // get ceil for paging
            $pages = ceil($orders->count() / 1000);
            echo "$pages <br/>";
            // loop the poges
            for ($i = 0; $i < $pages; $i++) {
              
                    

                    $orders_data = $orders->skip($i * 1000)->take(1000)->orderBy('order_date', 'DESC')->get();
                    // loop the orders for proccess
                    foreach ($orders_data as $order) {

                         echo date('Y-m-d H:m:s',time()).'- started \n';
                        $status='';
                        // get the sears status
                           try {
                              $status = Sears::GetOrderStatus($order);
                            } catch (\Exception $ex) {
                                echo $ex->getMessage() . '<br/>';
                          }

                        $order_stage = "";
                        // check the status;
                        switch ($status) {
                            case "All Shipped":
                                $order_stage = "Shipped";
                                break;
                            case "Partially Shipped":
                                $order_stage = "Partial Ship";
                                break;
                            case"All Cancelled":
                                $order_stage = "Cancelled";
                                break;
                            case "Processing":
                                $order_stage = "Processed";
                                break;

                        }
                        // check if $order_stage is not empty;
                        if ($order_stage != '') {
                            $order->order_stage = $order_stage;
                        }
                        // set the status to searstat;
                        echo $status . " " . $order->order_id . "<br/>";
                        $order->searstat = $status;
                        // save
                        $order->save();
                    //    ob_flush();
                      //  ob_flush();
                       // flush();
                        // break;
                        sleep(10);
                        echo date('Y-m-d H:m:s',time()).'- mid ended \n';
                      
                        echo date('Y-m-d H:m:s',time()).'- ended \n';
                      //  break;
                    }

               
               //  break;
            }
              sleep(5);
            echo date('Y-m-d H:m:s',time()).'- mid ended \n';
            $cron_job->status = 1;
            $cron_job->save();
            //ob_end_flush();
        }

    }

    public function proccessCronPartialShiptatus()
    {
        set_time_limit(0);

        // checked if cron job
        $cron = CronJob::where('status', 0)->where('name', 'Partially Shipped')->count();
        if ($cron == 0) {
            //ob_start();
            
            // create new job
            $cron_job = new CronJob();
            $cron_job->name = 'Partially Shipped';
            $cron_job->status = 0;
            $cron_job->save();
            ob_implicit_flush(true);
            // get all order except all shipped searstat
            $orders = Order::where('searstat', 'Partially Shipped');
            // get ceil for paging
            $pages = ceil($orders->count() / 1000);
            echo "$pages <br/>";
            // loop the poges
            for ($i = 0; $i < $pages; $i++) {
              
                    

                    $orders_data = $orders->skip($i * 1000)->take(1000)->orderBy('order_date', 'DESC')->get();
                    // loop the orders for proccess
                    foreach ($orders_data as $order) {

                         echo date('Y-m-d H:m:s',time()).'- started \n';
                        $status='';
                        // get the sears status
                           try {
                              $status = Sears::GetOrderStatus($order);
                            } catch (\Exception $ex) {
                                echo $ex->getMessage() . '<br/>';
                          }

                        $order_stage = "";
                        // check the status;
                        switch ($status) {
                            case "All Shipped":
                                $order_stage = "Shipped";
                                break;
                            case "Partially Shipped":
                                $order_stage = "Partial Ship";
                                break;
                            case"All Cancelled":
                                $order_stage = "Cancelled";
                                break;
                            case "Processing":
                                $order_stage = "Processed";
                                break;

                        }
                        // check if $order_stage is not empty;
                        if ($order_stage != '') {
                            $order->order_stage = $order_stage;
                        }
                        // set the status to searstat;
                        echo $status . " " . $order->order_id . "<br/>";
                        $order->searstat = $status;
                        // save
                        $order->save();
                       // ob_flush();
                      //  ob_flush();
                       // flush();
                        // break;
                        sleep(10);
                        echo date('Y-m-d H:m:s',time()).'- mid ended \n';
                      
                        echo date('Y-m-d H:m:s',time()).'- ended \n';
                      //  break;
                    }

               
               //  break;
            }
              sleep(5);
            echo date('Y-m-d H:m:s',time()).'- mid ended update status\n';
            $cron_job->status = 1;
            $cron_job->save();
            //ob_end_flush();
        }

    }

    public function processOrdersCron()
    {
        // checked if cron job
        $cron = CronJob::where('status', 0)->where('name', 'checking')->count();
        if ($cron == 0) {
            // create new job
            $cron_job = new CronJob();
            $cron_job->name = 'checking';
            $cron_job->status = 0;
            $cron_job->save();
            ob_implicit_flush(true);
            // get all order except all shipped searstat
            $orders = Order::where('order_stage', 'Review')->orWhere('order_stage', 'New')->orWhereNull('order_stage');
            // get ceil for paging
            $pages = ceil($orders->count() / 1000);
            echo "$pages <br/>";
            // loop the poges
            for ($i = 0; $i < $pages; $i++) {
                try {
                    // get all the orders by page
                    $orders_data = $orders->skip($i * 1000)->take(1000)->orderBy('order_date', 'DESC')->get();
                    // loop the orders for proccess
                    foreach ($orders_data as $order) {
                        $orderclear = true;
                        $address = "";
                        $issue = array();
                        $address = "";
                        if ($order->ship_to_address1 != '') {
                            $address .= $order->ship_to_address1 . ' ';
                        }
                        if ($order->ship_to_address2 != '') {
                            $address .= $order->ship_to_address2 . ' ';
                        }
                        if ($order->ship_to_city != '') {
                            $address .= $order->ship_to_city . ' ';
                        }
                        if ($order->ship_to_state != '') {
                            $address .= $order->ship_to_state . ' ';
                        }
                        if ($order->ship_to_country != '') {
                            $address .= $order->ship_to_country . ' ';
                        }
                        if ($order->ship_to_zip != '') {
                            $address .= $order->ship_to_zip . ' ';
                        }
                        if (strtoupper(Utility::CheckAddress(trim($address))) != "OK") {
                            $orderclear = false;
                            $issue[] = "Address";
                        } else {

                        }


                        if ($orderclear == true) {
                            $order->order_stage = 'Ready';
                            $order->order_issue = '';
                        } else {
                            $order->order_stage = 'Review';
                            $order->order_issue = implode(',',$issue);
                        }
                        echo $order->order_stage." - ".$order->order_id;
                        // save
                        $order->save();
                       ob_flush();
                        flush();
                        // break;
                        sleep(10);
                        echo date('Y-m-d H:m:s',time()).'- ended \n';
                    }

                } catch (\Exception $ex) {
                    echo $ex->getMessage() . '<br/>';
                }

               // break;
            }
              sleep(5);
            echo date('Y-m-d H:m:s',time()).'- mid ended \n';
            $cron_job->status = 1;
            $cron_job->save();
           // ob_end_flush();
        }
    }
    
    public function OrderToAcBulk(Request $request){
        foreach ($request->orders as $key => $order_id) {
            $order = Order::where('order_id',$order_id)->first();
            if($order){
                $order->bulk_ac=1;
                $order->sent_to_ac_by = Auth::guard('users')->user()->email;
                $order->save();
            }
        }
        return response(['message'=>'Successfully add to bulk AC','data'=>$request->orders],200);
    }
     public function OrderToSearsBulk(Request $request){
        foreach ($request->orders as $key => $order_id) {
            $order = Order::where('order_id',$order_id)->first();
            if($order){
                $order->bulk_sears=1;
                $order->sent_to_sears_by = Auth::guard('users')->user()->email;
                $order->save();
            }
        }
        return response(['message'=>'Successfully add to bulk AC','data'=>$request->orders],200);
    }
    public function proccessBulkAc(){
         // checked if cron job
        $cron = CronJob::where('status', 0)->where('name', 'bulk_ac')->count();
        if ($cron == 0) {
            // create new job
            $cron_job = new CronJob();
            $cron_job->name = 'bulk_ac';
            $cron_job->status = 0;
            $cron_job->save();
            $orders = Order::where('bulk_ac',1)->where(function ($query) {
                
                    $query->orWhere('sent_to_ac', 'N');
                    $query->orWhereNull('sent_to_ac');
               
            })->get();
            foreach ($orders as $key => $order) {

                 // call api to get information of contact
                $information = Accounting::infoContact($order->email);
                // sanity check for api response
                if ($information['result_code'] == '0') {
                    // add contact via api
                    $add_contact = Accounting::AddContact($order);
                    // sanity check api response
                    if ($add_contact['result_code'] == '0') {

                        echo $add_contact['result_message'] .' : '.$order->id;
                        continue;
                        //return response(['message' => $add_contact['result_message']], 202);
                    }

                    // call api to get information of contact
                    $information = Accounting::infoContact($order->email);
                }
                // edit contact via api
                $contact_edit = Accounting::EditContact($order, $information);
                // sanity check for api response
                if ($contact_edit['result_code'] == '0') {
                     echo $contact_edit['result_message'] .' : '.$order->id;
                      continue;
                   // return response(['message' => $add_contact['result_message']], 202);
                }

                $add_deal = Accounting::deals($order, $information);
                // sanity check for api response
                if ($add_deal['result_code'] == '0') {
                     echo $add_deal['result_message'] .' : '.$order->id;
                     continue;
                    //return response(['message' => $add_deal['result_message']], 202);
                }
                $add_note = Accounting::AddNote($order, $add_deal['id']);
                // sanity check for api response
                if ($add_note['result_code'] == '0') {
                     echo $add_deal['result_message'] .' : '.$order->id;
                     continue;
                    //return response(['message' => $add_note['result_message']], 202);
                }
                $order->sent_to_ac = 'Y';
                $order->sent_to_ac_date = \Carbon\Carbon::now()->toDateTimeString();
                $order->save();
                echo "Success - ".$order->order_id;
            }
            $cron_job->status = 1;
            $cron_job->save();
       }
    }
    public function proccessBulkSears(){

          set_time_limit(0);
         // checked if cron job
        $cron = CronJob::where('status', 0)->where('name', 'bulk_sears')->count();
        if ($cron == 0) {
                // create new job
                $cron_job = new CronJob();
                $cron_job->name = 'bulk_sears';
                $cron_job->status = 0;
                $cron_job->save();
               ob_implicit_flush(true);
             $orders = Order::where('bulk_sears',1)->where(function ($query) {
                
                    $query->orWhere('sent_to_sears', 'N');
                    $query->orWhereNull('sent_to_sears');
               
            })->get();
            foreach ($orders as $key => $order) {
                echo date('Y-m-d H:m:s',time()).'- started \n';
                 // make order xml
                $xml = Sears::createXML($order);
                // sanity check if xml is valid
                if (Sears::validateXML($xml)) {
                    // send the xml to sear
                    $sear_response = Sears::Send($xml);

                    // sanity check if successfully send
                    if (isset($sear_response['document-id'])) {
                        $status = Sears::GetOrderStatus($order);
                        $order->searstat = $status;
                        $order->sent_to_sears = 'Y';
                        $order->sent_to_sears_date = \Carbon\Carbon::now()->toDateTimeString();
                        $order->sears_response = $sear_response['document-id'];
                        $order->save();
                          echo "\n Success - ".$order->order_id;
                        //return response(['message' => 'Successfully send to Sear', 'data' => $sear_response, 'xml' => $xml], 200);
                    } else {
                         $order->sent_to_sears = 'N';
                         $order->bulk_sears = 0;
                         
                         $order->save();
                       // return response(['message' => 'Failed to send to sear', 'xml' => $xml], 202);
                        echo " \n ERROR - ".$order->order_id;
                    }
                  
                    
                } else {
                    //return response(['message' => 'Invalid XML', 'xml' => $xml, 'v' => Sears::validateXML($xml)], 202);
                    echo " \n ERROR - ".$order->order_id;
                }
                 //ob_flush();
                //flush();
                  // sleep every 60 seconds;
                sleep(5);
                echo date('Y-m-d H:m:s',time()).'- ended \n';
            }
            $cron_job->status = 1;
            $cron_job->save();
           
       }
    }
    public function manualGetStatus($order_id){
        $order= Order::where('order_id',$order_id)->first();
        if(is_null($order)){
            return view('error.503');
        }
          $status = Sears::GetOrderStatus($order);

          $order_stage = "";
          // check the status;
          switch ($status) {
            case "All Shipped":
                $order_stage = "Shipped";
                break;
            case "Partially Shipped":
                $order_stage = "Partial Ship";
                break;
            case"All Cancelled":
                $order_stage = "Cancelled";
                break;
            case "Processing":
                $order_stage = "Processed";
                break;

             }
                        // check if $order_stage is not empty;
            if ($order_stage != '') {
                $order->order_stage = $order_stage;
            }
             // set the status to searstat;
           
             $order->searstat = $status;
               // save
             $order->save();
        return redirect('order/' . $order_id);
    }
    public function orderidautocomplete(Request $request){
        $orders = DB::table('orders')->select('order_id')->where('order_id','LIKE','%'.$request->term.'%')->take(10)->get();
        $result =array();
        foreach($orders as $order){
            $result[]=$order->order_id;
        }
        return response($result ,200);
    }
     public function frontautocomplete(Request $request){
        $orders = DB::table('orders')->select('screen_branding_theme_code')->where('screen_branding_theme_code','LIKE','%'.$request->term.'%')->take(10)->get();
        $result =array();
        foreach($orders as $order){
            $result[]=$order->screen_branding_theme_code;
        }
        return response($result ,200);
    }
}
