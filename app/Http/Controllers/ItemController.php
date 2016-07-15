<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Models\Components\Item;
use App\Models\Components\Order;
use App\Models\Components\CronJob;
use App\Models\Components\Inventory;
use App\Libraries\Utility;
use App\Libraries\Sears;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Item::where('id',$id)->first();
        // if not found return 503 error;
        if(!$item){
            return view('errors.503');
        }

        return view('Items.edit',['order'=>$item->order()->first(),'item'=>$item,'page'=>'order']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validateRule = [
            'item_id' => 'required',
            'quantity'=>'required',
            'description' => 'required',
            'cost'=>'required',
            'unit_cost_with_discount' => 'required',
            'total_cost_with_discount'=>'required',
            'accounting_code'=>'required',
            'distribution_center_code'=>'required',
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
            return redirect()->back()->withErrors(['error' => $msg]);
        }
        $item = Item::where('id',$id)->first();
        if(!$item){
            return view('errors.503');
        }
        $item->fill($request->all());
        $item->save();
        return redirect('order/'.$item->order_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Delete Item redirect to order page
     */
    public function deleteItem($id,$order_id){

        $item = Item::where('id',$id)->where('order_id',$order_id)->first();
        // if not found return 503 error;
        if(!$item){
            return view('errors.503');
        }

        //delete item
        $item->delete();
        return redirect('order/'.$item->order_id);

    }
    /**
     * add Item to order
     */
    public function addItem($order_id){

        $order = Order::where('order_id',$order_id)->first();
        // if not found return 503 error;
        if(!$order){
            return view('errors.503');
        }
        return view('Items.add',['order'=>$order]);

    }
    /**
     * Store item
     *
     */
    public function saveItem(Request $request,$order_id){
        $validateRule = [
            'item_id' => 'required',
            'quantity'=>'required',
            'description' => 'required',
            'cost'=>'required',
            'unit_cost_with_discount' => 'required',
            'total_cost_with_discount'=>'required',
            'accounting_code'=>'required',
            'distribution_center_code'=>'required',
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
            return redirect()->back()->withErrors(['error' => $msg]);
        }
        $item = new Item();
        $item->fill($request->all());
        $item->order_id=$order_id;
        $item->save();
        return redirect('order/'.$item->order_id);
    }
    
    /**
     * Cron to fetch item inventory from sears to local DB
     * 
     */
     public function processFetchInventoryCron()
    {
        // checked if cron job
        $cron = CronJob::where('status', 0)->where('name', 'Fetch Inventory')->count();
        if ($cron == 0) {
            // create new job
            $cron_job = new CronJob();
            $cron_job->name = 'Fetch Inventory';
            $cron_job->status = 0;
            $cron_job->save();
            ob_implicit_flush(true);
            // get unique item IDs
            $items = Item::distinct()->select('item_id');
            // get ceil for paging
            $pages = ceil($items->count() / 1000);
            echo "$pages <br/>";
            // loop the pages
            for ($i = 0; $i < $pages; $i++) {
                try {
                    // get all the items by page
                    $items_data = $items->skip($i * 1000)->take(1000)->orderBy('item_id')->get();
                    // loop the orders for proccess
                    foreach ($items_data as $item) {
                       //echo $item->item_id;
                        $inventorydel = Inventory::where('item_id',$item->item_id)->first();
                        //delete item inventory
                        if(!is_null($inventorydel)){
                            $inventorydel->delete();
                        }                        
                        
                        //Get item inventory from sears
                        $xml = Sears::GetItemInventory(array($item->item_id));
                        echo $item->item_id;
                            foreach($xml->item as $key=>$searsitems){
                                foreach($searsitems->locations->location as $lockey=>$location){                                    
                                    $inventory = new Inventory();
                                    echo Utility::XMLAttribute($location, "location-id")."|";
                                    $inventory->item_id = Utility::XMLAttribute($searsitems, "seller-product-id");
                                    $inventory->program_type = Utility::XMLAttribute($searsitems, "program-type");
                                    $inventory->location_id = Utility::XMLAttribute($location, "location-id");
                                    $inventory->on_hand_quantity = $location->{'inventory-levels'}->{'on-hand-quantity'};
                                    $inventory->reserved_quantity = $location->{'inventory-levels'}->{'reserved-quantity'};
                                    $inventory->available_quantity = $location->{'inventory-levels'}->{'available-quantity'};
                                    $inventory->pick_up_now_eligible = $location->{'pick-up-now-eligible'};
                                    $inventory->sears_inventory = date('Y-m-d H:i:s',strtotime($location->{'inventory-timestamp'}));
                                    $inventory->save();
                                }
                            }
                        
                        ob_flush();
                        flush();
                        sleep(10);
                        echo date('Y-m-d H:m:s',time()).'- ended \n';
                        
                    }

                } catch (\Exception $ex) {
                    echo $ex->getMessage() . '<br/>';
                }

               // break;
            }
            $cron_job->status = 1;
            $cron_job->save();
           // ob_end_flush();
        }
    }
}
