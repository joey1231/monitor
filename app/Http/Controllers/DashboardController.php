<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Components\Order;
use Log;
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::take(100)->orderBy('created_at','DESC')->get();
        $test_orders = Order::where('test_order','true')->take(100)->get();
        $weekly =$this->graph();
       return view('dashboard.index',array('orders'=>$orders,'test_orders'=>$test_orders,'graph'=>$weekly,'page'=>'dashboard'));
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
        Log::info($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

    public function graph(){
     $data = array();
      // get the monday of this week
      $date = date("Y-m-d",strtotime('monday this week'));

      $data[$date]=0;
      for($i=1 ; $i < 7; $i++){
            $data[date('Y-m-d',strtotime("+$i day",strtotime($date)))]=0;
      }
      $data_graph =array();
      // process the date
      foreach($data as $d=>$i){

            $c = Order::GrapDataWeekly($d)->count();

            $data_graph[]=array('x'=> $d,'y'=>  $c > 0 ?  $c : 0 );
      }
      return $data_graph;
    }
}
