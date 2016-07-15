@extends('master')
@section('container')
	<script type="text/javascript">
           
            $( document ).ready(function() {
                
                /* Line dashboard chart */
                
                
                Morris.Line({
                  element: 'dashboard-line-1',
                  data:{!! json_encode($graph) !!},
                  xkey: 'x',
                  ykeys: ['y'],
                  labels: ['Orders'],
                  resize: true,
                  hideHover: true,
                  xLabels: 'day',
                  gridTextSize: '10px',
                  lineColors: ['#3FBAE4','#33414E'],
                  gridLineColor: '#E5E5E5'
                });   
                           
                /* EMD Line dashboard chart */
                
            });
            
        </script>
                    <!-- START WIDGETS -->                    
                    <div class="row">
                        <div class="col-md-3 col-sm-12 col-xs-12 pull-right">
                            <input type="hidden" value="{{ date("F d, Y H:i:s", time()) }}" name="serverdate" />
                            <!-- START WIDGET CLOCK -->
                            <div class="widget widget-danger widget-padding-sm">
                                <div class="widget-big-int plugin-clock">00:00</div>                            
                                <div class="widget-subtitle plugin-date">Loading...</div>
                                <div class="widget-controls">                                
                                    <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="left" title="Remove Widget"><span class="fa fa-times"></span></a>
                                </div>                            
                                <div class="widget-buttons widget-c3">
                                    {{ date_default_timezone_get() }}
                                </div>                            
                            </div>
                            
                            {{ date_default_timezone_set('US/Eastern') }}
                            <input type="hidden" value="{{ date("F d, Y H:i:s", time()) }}" name="serverdate2" />
                            <div class="widget widget-danger widget-padding-sm">
                                <div class="widget-big-int plugin-clock2">00:00</div>                            
                                <div class="widget-subtitle plugin-date2">Loading...</div>
                                <div class="widget-controls">                                
                                    <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="left" title="Remove Widget"><span class="fa fa-times"></span></a>
                                </div>                            
                                <div class="widget-buttons widget-c3">
                                     {{ date_default_timezone_get() }}
                                </div>                            
                            </div>                        
                            <!-- END WIDGET CLOCK -->
                            
                        </div>
                        <div class="col-md-9">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title-box">
                                        <h3>Orders for this week</h3>
                                        <span>No. of orders per day</span>
                                    </div>
                                    <ul class="panel-controls" style="margin-top: 2px;">
                                        <li><a href="#" class="panel-fullscreen"><span class="fa fa-expand"></span></a></li>
                                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>                                     
                                    </ul>
                                </div>
                                <div class="panel-body padding-0">
                                    <div class="chart-holder" id="dashboard-line-1" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END WIDGETS -->
                    <div class="row">
                        <div class="col-md-6">

                            <!-- List of orders -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title-box">
                                        <h3>Order Logs</h3>
                                        <span>List of Orders</span>
                                    </div>  
                                    <ul class="panel-controls">
                                        <li><a href="#" class="panel-fullscreen"><span class="fa fa-expand"></span></a></li>
                                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                                    </ul>
                                </div>
                                <div class="panel-body">       
                                     <table class="table orderstatdatatable">
                                        <thead>
                                            <tr>
                                                <th>UC/AC Order ID</th>
                                                <th>Sears Order ID</th>
                                                <th>Date Ordered</th>  
                                                <th>Store Front</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                           @foreach($orders as $order)
											<tr>
                                                <td><a href="{{url('order/'.$order->order_id)}}">{{ $order->order_id}}</a></td>
                                                <td>{{ str_replace('-','',$order->order_id) }}</td>
                                                <td>{{ $order->order_date }}</td>  
                                                <td>{{ $order->screen_branding_theme_code }}</td>
                                            </tr>
                                           @endforeach
                                        </tbody>
                                    </table>
                                </div>      
                                <div class="panel-footer">
                                </div>                            
                            </div>
                            <!-- END List of orders -->
                            
                            <!-- List of test orders -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title-box">
                                        <h3>Test Orders</h3>
                                        <span>List of Test Orders</span>
                                    </div>  
                                    <ul class="panel-controls">
                                        <li><a href="#" class="panel-fullscreen"><span class="fa fa-expand"></span></a></li>
                                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                                    </ul>
                                </div>
                                <div class="panel-body">                                    
                                     <table class="table orderstatdatatable">
                                        <thead>
                                            <tr>
                                                <th>UC/AC Order ID</th>
                                                <th>Sears Order ID</th>
                                                <th>Date Ordered</th>  
                                                <th>Store Front</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           @foreach($test_orders as $order)
											<tr>
                                                <td><a href="{{url('order/'.$order->order_id)}}">{{ $order->order_id}}</a></td>
                                                <td>{{ str_replace('-','',$order->order_id) }}</td>
                                                <td>{{ $order->order_date }}</td>  
                                                <td>{{ $order->screen_branding_theme_code }}</td>
                                            </tr>
                                           @endforeach
                                        </tbody>
                                    </table>
                                </div>      
                                <div class="panel-footer">
                                </div>                            
                            </div>
                            <!-- END List of test orders -->
                        </div>   
                        <div class="col-md-6">                           
                            
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title-box">
                                        <h3>Order Status</h3>
                                    </div>  
                                    <ul class="panel-controls">
                                        <li><a href="#" class="panel-fullscreen"><span class="fa fa-expand"></span></a></li>
                                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                                    </ul>
                                </div>
                                <div class="panel-body">
                                     <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>UC/AC Order ID</th>
                                                <th>Sears Order ID</th>
                                                <th>Sears Status</th>
                                            </tr>
                                            {{$count=1}}

                                             @foreach($orders as $order)
                                            
											<tr>
												<td>{{ $count++ }}</td>
                                                <td><a href="{{url('order/'.$order->order_id)}}">{{ $order->order_id}}</a></td>
                                                <td>{{ str_replace('-','',$order->order_id) }}</td>
                                             
                                                <td>{{ $order->searstat }}</td>
                                            </tr>
                                           @endforeach
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>      
                                <div class="panel-footer">
                                </div>                            
                            </div> 
                            
                            
                        </div>
                    </div>
@stop