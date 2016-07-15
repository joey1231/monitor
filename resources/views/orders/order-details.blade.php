@extends('master')
@section('container')
    <form action="{{url('order/'.$order->order_id)}}" method='POST'>
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading ui-draggable-handle">                                    
                                    <div class="panel-title-box">
                                        <h3>{{$order->order_id}}</h3>
                                        <span>Ordered Item/s</span>
                                    </div>
                                    <ul class="panel-controls">
                                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                                    </ul>                                   
                                </div>
                                <div class="panel-body">
                                    <table class="table table-condensed table-bordered table-responsive">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Quantity</th>
                                                <th>Description</th>
                                                <th>Discount</th>
                                                <th>Amount</th>
                                                <th>Kit</th>
                                                <th>Kit Component</th>
                                                <th>Inventory</th>
                                                <th>Available</th>
                                                <th>Location</th>
                                                <th>Status</th>
                                                <th style="text-align: center;">
                                                    <a  data-toggle="tooltip" data-placement="right" title="Add Item" href="{{url('item/create/'.$order->order_id)}}">
                                                        <span class="fa fa-plus-square"></span>
                                                    </a>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            
                                            $subtotal = 0;
                                            $discount = 0;
                                        ?>
                                           @foreach($order->items()->get() as $item)
                                            <tr>
                                                <td>{{$item->item_id}}</td>
                                                <td>{{$item->quantity}}</td>
                                                <td>{{$item->description}}</td>
                                                <td>{{$item->discount}}</td>
                                                <td style="text-align: right!important;">{{$item->cost}}</td>
                                                <td>{{$item->kit}}</td>
                                                <td>{{$item->kit_component}}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>{{$item->send_to_sears}}</td>
                                                <td>
                                                    <a  data-toggle="tooltip" data-placement="right" title="Edit Item" href="{{url('item/'.$item->id)}}">
                                                        <span class="fa fa-edit"></span>
                                                    </a> 
                                                    <a data-toggle="tooltip" data-placement="right" title="Delete Item" href="javascript:;" id="{{$item->id}}"  class='item-delete'

                                                       >
                                                        <span class="fa fa-trash-o"></span>
                                                    </a>
                                                </td>
                                            </tr>
                                            @if($item->kit=='N')
                                            <?php
                                                $subtotal+=$item->cost; $discount+=$item->discount; 
                                             ?>
                                            @endif
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="12" style="text-align: right">
                                                <a href="javascript:;"
                                                   onclick="$('.costsview').toggle(function(){
                                                           if($(this).is('hidden')){
                                                               $(this).addClass('hidden');
                                                           }else{
                                                               $(this).removeClass('hidden');
                                                           }
                                                       });
                                                       $('.costsedit').toggle(function(){
                                                           if($(this).is('hidden')){
                                                               $(this).addClass('hidden');
                                                           }else{
                                                               $(this).removeClass('hidden');
                                                           }
                                                       });
                                                       "><span class="fa fa-edit"></span>Update Costs</a>
                                                <button name="update_costs" class="btn btn-sm btn-primary costsedit hidden">Update</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right" colspan="4">Subtotal before discounts</td>
                                            <td align="right"><div class="costsview">{{ number_format($order->subtotal,2) }} </div><div class="costsedit hidden"><input type="text" name="subtotal" value="{{$order->subtotal}}" /></div></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td align="right" colspan="4">Discounts</td>
                                            <td align="right"><div class="costsview">{{ number_format($order->subtotal_discount,2) }} </div><div class="costsedit hidden"><input type="text" name="subtotal_discount" value="{{$order->subtotal_discount}}" /></div></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td align="right" colspan="4">Subtotal</td>
                                            <td align="right">{{ number_format($order->subtotal - $order->subtotal_discount,2) }}</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>

                                        </tr>
                                        <tr>
                                            <td align="right" colspan="4">Tax Rate</td>
                                            <td align="right"><div class="costsview">{{ number_format($order->tax_rate,2) }}</div><div class="costsedit hidden"><input type="text" name="tax_rate" value="{{$order->tax_rate}}" /></div></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td align="right" colspan="4">Tax</td>
                                            <td align="right"><div class="costsview">{{number_format($order->tax,2)}}</div><div class="costsedit hidden"><input type="text" name="tax" value="{{$order->tax}}" /></div></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td align="right" colspan="4">Shipping/Handling</td>
                                            <td align="right"><div class="costsview">{{ number_format($order->shipping_handling_total,2) }}</div><div class="costsedit hidden"><input type="text" name="shipping_handling_total" value="{{$order->shipping_handling_total}}" /></div></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td align="right" colspan="4">Shipping/Handling Discount</td>
                                            <td align="right"><div class="costsview">{{ number_format($order->shipping_handling_total_discount,2)}}</div><div class="costsedit hidden"><input type="text" name="shipping_handling_total_discount" value="{{$order->shipping_handling_total_discount}}" /></div></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td align="right" colspan="4">Total</td>
                                            <td align="right"><div class="costsview">{{ number_format($order->total,2) }}</div><div class="costsedit hidden"><input type="text" name="total" value="{{$order->total}}" /></div></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        </tfoot>

                                    </table>
                                </div>
                            </div>                            
                        </div>
                    </div>
    </form>
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
                                    {{date_default_timezone_get()}}
                                </div>                            
                            </div>                        
                            <!-- END WIDGET CLOCK -->
                            
                            <div class="panel panel-default">
                                <div class="panel-heading ui-draggable-handle">
                                    <h3 class="panel-title"><span class="fa fa-info-circle"></span> General Info</h3>                                
                                    <ul class="panel-controls">
                                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                                    </ul>                                   
                                </div>
                                <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-md-6 control-label text-left">Test Order</label>
                                            <div class="col-md-6">
                                                <span class="help-block">{{$order->test_order}}</span>
                                            </div>
                                        </div>
									<div class="form-group">
                                            <label class="col-md-6 control-label text-left">Address Check</label>
                                            <div class="col-md-6">
                                                
												@if($address_status=="OK")
												 
													<span class="label label-success">{{ $address_status }}</span>
											@else
													<span class="label label-warning"><{{ $address_status }}</span>									
												@endif
                                            </div>
                                        </div>
                                </div>
                            </div>
                            
                            <div class="panel panel-default">
                                <div class="panel-heading ui-draggable-handle">
                                    <h3 class="panel-title"><span class="fa fa-info-circle"></span> Sears Status</h3>                                
                                    <ul class="panel-controls">
                                        <li><a  data-toggle="tooltip" data-placement="bottom" title="Update Sears Status" href="{{url('order/manualgetstatus/'.$order->order_id)}}"  class=""><span class="fa fa-refresh"></span></a></li>
                                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                                    </ul>                                   
                                </div>
                                <div class="panel-body">
                                    <form action="#" role="form" class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label text-left">Status</label>
                                            <div class="col-md-8">
                                                <span class="help-block">{{ $order->searstat }}</span>
                                            </div>                                                                                
                                        </div>                                    
                                        <div class="form-group">
                                            <label class="col-md-4 control-label text-left">Sent</label>
                                            <div class="col-md-8">
                                                <span class="help-block">{{ $order->sent_to_sears }}</span>
                                            </div>                                        
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label text-left">Sent By</label>
                                            <div class="col-md-8">
                                                <span class="help-block">{{ $order->sent_to_sears_by }}</span>
                                            </div>                                        
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label text-left">Sent Date</label>
                                            <div class="col-md-8">
                                                <span class="help-block">{{ $order->sent_to_sears_date =="0000-00-00 00:00:00" ? "": $order->sent_to_sears_date }}</span>
                                            </div>                                        
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label text-left">Sears Response</label>
                                            <div class="col-md-8">
                                                <span class="help-block">{{ $order->sears_response }}</span>
                                            </div>                                        
                                        </div>
                                    </form>
                                    
                            <hr />
                           @if( $order->sent_to_sears =='N' )
                            <button id="{{$order->order_id}}"
                               class="btn btn-warning btn-block btn-sm text-left send-to-sear"
                               
                               ><span class="fa fa-mail-forward"></span> Send to Sears</button>
                            @endif
                           <!-- 
                            <em>Order was scheduled for sending by </em>
                           -->
                                </div>
                            </div>
                            
                            <div class="panel panel-default">
                                <div class="panel-heading ui-draggable-handle">
                                    <h3 class="panel-title"><span class="fa fa-info-circle"></span> ActiveCampaign Status</h3>                                
                                    <ul class="panel-controls">
                                        <li><a  data-toggle="tooltip" data-placement="bottom" title="Update Accounting Status" href="{{url('order/'.$order->order_id)}}"  class=""><span class="fa fa-refresh"></span></a></li>
                                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                                    </ul>                                   
                                </div>
                                <div class="panel-body">
                                    <form action="#" role="form" class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label text-left">Sent</label>
                                            <div class="col-md-8">
                                                <span class="help-block">{{ $order->sent_to_ac }}</span>
                                            </div>                                        
                                        </div>                                  
                                        <div class="form-group">
                                            <label class="col-md-4 control-label text-left">Sent By</label>
                                            <div class="col-md-8">
                                                <span class="help-block">{{ $order->sent_to_ac_by }}</span>
                                            </div>                                        
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label text-left">Sent Date</label>
                                            <div class="col-md-8">
                                                <span class="help-block">{{ $order->sent_to_ac_date =="0000-00-00 00:00:00" ? "": $order->sent_to_ac_date }}</span>
                                            </div>                                        
                                        </div>
                                    </form>
                                    
                            <hr />
                          @if($order->sent_to_ac=='N')
                            <button id="{{$order->order_id}}"
                               class="btn btn-info btn-block btn-sm text-left send-to-ac"

                               ><span class="fa fa-mail-forward"></span> Send to AC</button>
                           @endif
                                </div>
                            </div>
                            
                            <div class="panel panel-default">
                                <div class="panel-heading ui-draggable-handle">
                                    <h3 class="panel-title"><span class="fa fa-info-circle"></span> Tracking</h3>                                
                                    <ul class="panel-controls">
                                        <li><a  data-toggle="tooltip" data-placement="bottom" title="Fetch Tracking" href="javascript:;" onclick="" class=""><span class="fa fa-refresh"></span></a></li>
                                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                                    </ul>                                   
                                </div> 
                                <div class="panel-body list-group">                                     
                                   @foreach($order->trackings()->get() as $tracking)
                                    <a target="_blank" href="{{$tracking->trackingurl}}" class="list-group-item">{{$tracking->trackingno}}</a>
                                   @endforeach
                                </div>
                            </div>
							@if($order->special_instructions !="" )

                                <div class="panel panel-default">
                                    <div class="panel-heading ui-draggable-handle">
                                        <h3 class="panel-title"><span class="fa fa-info-circle"></span> Special Instructions</h3>                                
                                        <ul class="panel-controls">
                                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                            <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                                        </ul>                                   
                                    </div> 
                                    <div class="panel-body">                                     
                                       {{nl2br($order->special_instructions)}}
                                    </div>
                                </div>
                            @endif
                            @if($order->merchant_notes !="" )

                                <div class="panel panel-default">
                                    <div class="panel-heading ui-draggable-handle">
                                        <h3 class="panel-title"><span class="fa fa-info-circle"></span> Merchant Notes</h3>                                
                                        <ul class="panel-controls">
                                            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                            <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                                        </ul>                                   
                                    </div> 
                                    <div class="panel-body">
                                        {{nl2br($order->merchant_notes)}}
                                    </div>
                                </div>
                            @endif

                        </div>
                        <form action="{{url('order/'.$order->order_id)}}" method='POST'>
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-9">                            
                                <div class="panel panel-default" style="overflow: auto;">
                                <div class="panel-heading">
                                    <div class="panel-title-box">
                                        <h3>{{$order->order_id}}</h3>
                                        <span>Order Details</span>
                                    </div>
                                    <ul class="panel-controls" style="margin-top: 2px;">
                                        <li><a href="#" class="panel-fullscreen"><span class="fa fa-expand"></span></a></li>
                                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>                                     
                                    </ul>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-condensed">
                                        <tbody>
                                            <tr>
                                                <td>Order ID:</td><td colspan="3">{{$order->order_id}}</td>
                                            </tr>
                                            <tr>
                                                <td>Order Date:</td><td colspan="3">{{$order->order_date}}</td>
                                            </tr>
                                            <tr>
                                                <td>Order Type:</td><td colspan="3">{{$order->type}}</td>
                                            </tr>
                                            <tr>
                                                <td>Current Stage:</td><td colspan="3">{{$order->current_stage}}</td>
                                            </tr>
                                            <tr>
                                                <td>Payment Status:</td><td colspan="3">{{$order->payment_status}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <th colspan="2">Bill To 
                                                    <a href="javascript:;" 
                                                       onclick="$('.billtoview').toggle(function(){
                                                           if($(this).is('hidden')){
                                                               $(this).addClass('hidden');
                                                           }else{
                                                               $(this).removeClass('hidden');
                                                           }
                                                       });
                                                       $('.billtoedit').toggle(function(){
                                                           if($(this).is('hidden')){
                                                               $(this).addClass('hidden');
                                                           }else{
                                                               $(this).removeClass('hidden');
                                                           }
                                                       });
                                                       ">
                                                        <span class="fa fa-edit"></span>
                                                    </a>
                                                    <button name="submit_bill_to" class="btn btn-primary btn-sm billtoedit hidden pull-right">Submit</button>
                                                </th>
                                                <th colspan="2">Ship To 
                                                    <a href="javascript:;" 
                                                       onclick="$('.shiptoview').toggle(function(){
                                                           if($(this).is('hidden')){
                                                               $(this).addClass('hidden');
                                                           }else{
                                                               $(this).removeClass('hidden');
                                                           }
                                                       });
                                                       $('.shiptoedit').toggle(function(){
                                                           if($(this).is('hidden')){
                                                               $(this).addClass('hidden');
                                                           }else{
                                                               $(this).removeClass('hidden');
                                                           }
                                                       });
                                                       "><span class="fa fa-edit"></span></a>
                                                       <button name="submit_ship_to" class="btn btn-primary btn-sm shiptoedit hidden pull-right">Submit</button>
                                                </th>
                                            </tr>
                                            <tr>
                                                <td>Company:</td><td><div class="billtoview">{{$order->bill_to_company}}</div><div class="billtoedit hidden"><input type="text" name="bill_to_company" value="{{$order->bill_to_company}}" /></div></td>
                                                <td>Company:</td><td><div class="shiptoview">{{$order->ship_to_company}}</div><div class="shiptoedit hidden"><input type="text" name="ship_to_company" value="{{$order->ship_to_company}}" /></div></td>
                                            </tr>
                                            <tr>
                                                <td>Name:</td><td><div class="billtoview">{{$order->bill_to_first_name}} {{$order->bill_to_last_name}} </div><div class="billtoedit hidden"><input type="text" name="bill_to_first_name" value="{{$order->bill_to_first_name}}" /> <input type="text" name="bill_to_last_name" value="{{$order->bill_to_last_name}}" /></div></td>
                                                <td>Name:</td><td><div class="shiptoview">{{$order->ship_to_first_name}} {{$order->ship_to_last_name}}</div><div class="shiptoedit hidden"><input type="text" name="ship_to_first_name" value="{{$order->ship_to_first_name}}" /> <input type="text" name="ship_to_last_name" value="{{$order->ship_to_last_name}}" /></div></td>
                                            </tr>
                                            <tr>
                                                <td>Address:</td><td><div class="billtoview">{{$order->bill_to_address1}}</div><div class="billtoedit hidden"><input type="text" name="bill_to_address1" value="{{$order->bill_to_address1}}" /></div></td>
                                                <td>Address:</td><td><div class="shiptoview">{{$order->ship_to_address1}}</div><div class="shiptoedit hidden"><input type="text" name="ship_to_address1" value="{{$order->ship_to_address1}}" /></div></td>
                                            </tr>
                                            <tr>
                                                <td>Address Line 2:</td><td><div class="billtoview">{{$order->bill_to_address2}}</div><div class="billtoedit hidden"><input type="text" name="bill_to_address2" value="{{$order->bill_to_address2}}" /></div></td>
                                                <td>Address Line 2:</td><td><div class="shiptoview">{{$order->ship_to_address2}}</div><div class="shiptoedit hidden"><input type="text" name="ship_to_address2" value="{{$order->ship_to_address2}}" /></div></td>
                                            </tr>
                                            <tr>
                                                <td>City:</td><td><div class="billtoview">{{$order->bill_to_city}}</div><div class="billtoedit hidden"><input type="text" name="bill_to_city" value="{{$order->payment_status}}" /></div></td>
                                                <td>City:</td><td><div class="shiptoview">{{$order->ship_to_city}}</div><div class="shiptoedit hidden"><input type="text" name="ship_to_city" value="{{$order->payment_status}}" /></div></td>
                                            </tr>
                                            <tr>
                                                <td>State:</td><td><div class="billtoview">{{$order->bill_to_state}}</div><div class="billtoedit hidden"><input type="text" name="bill_to_state" value="{{$order->bill_to_state}}" /></div></td>
                                                <td>State:</td><td><div class="shiptoview">{{$order->ship_to_state}}</div><div class="shiptoedit hidden"><input type="text" name="payment_status" value="{{$order->ship_to_state}}" /></div></td>
                                            </tr>
                                            <tr>
                                                <td>Zip:</td><td><div class="billtoview">{{$order->bill_to_zip}}</div><div class="billtoedit hidden"><input type="text" name="bill_to_zip" value="{{$order->bill_to_zip}}" /></div></td>
                                                <td>Zip:</td><td><div class="shiptoview">{{$order->ship_to_zip}}</div><div class="shiptoedit hidden"><input type="text" name="ship_to_zip" value="{{$order->ship_to_zip}}" /></div></td>
                                            </tr>
                                            <tr>
                                                <td>Country:</td><td><div class="billtoview">{{$order->bill_to_country}} <?=($order->bill_to_country_code!='')?"(".$order->bill_to_country_code.")":"";?></div><div class="billtoedit hidden"><input type="text" name="bill_to_country" value="{{$order->bill_to_country}}" /> <input type="text" name="bill_to_country_code" value="{{$order->bill_to_country_code}}" /></div></td>
                                                <td>Country:</td><td><div class="shiptoview">{{$order->ship_to_country}} <?=($order->ship_to_country_code !='')?"(".$order->ship_to_country_code.")":"";?></div><div class="shiptoedit hidden"><input type="text" name="ship_to_country" value="{{$order->ship_to_country}}" /> <input type="text" name="ship_to_country_code" value="{{$order->ship_to_country_code}}" /></div></td>
                                            </tr>
                                            <tr>
                                                <td></td><td></td>
                                                <td>Shipping Method:</td>
                                                <td>
                                                    <div class="shiptoview"><?=$order['shipping_method'];?></div>
                                                    <div class="shiptoedit hidden">
                                                        <select name="shipping_method">
                                                            <option <?=($order->shipping_method=='UPS: Ground')?"selected":"";?> value="UPS: Ground">UPS: Ground</option>
                                                            <option <?=($order->shipping_method=='UPS: 2nd Day Air')?"selected":"";?> value="UPS: 2nd Day Air">UPS: 2nd Day Air</option>
                                                            <option <?=($order->shipping_method=='UPS: Next Day Air')?"selected":"";?> value="UPS: Next Day Air">UPS: Next Day Air</option>
                                                            <option <?=($order->shipping_method=='Freight')?"selected":"";?> value="Freight">Freight</option>
                                                            <option <?=($order->shipping_method=='Envio Desde Estados Unidos')?"selected":"";?> value="Envio Desde Estados Unidos">Envio Desde Estados Unidos</option>
                                                            <option <?=($order->shipping_method=='FedEx: Ground')?"selected":"";?> value="FedEx: Ground">FedEx: Ground</option>
                                                            <option <?=($order->shipping_method=='FedEx: International Priority')?"selected":"";?> value="FedEx: International Priority">FedEx: International Priority</option>
                                                            <option <?=($order->shipping_method=='Lugar De Recojo')?"selected":"";?> value="Lugar De Recojo">Lugar De Recojo</option>
                                                            <option <?=($order->shipping_method=='Shipwire: 2-Day')?"selected":"";?> value="Shipwire: 2-Day">Shipwire: 2-Day</option>
                                                            <option <?=($order->shipping_method=='Store Pickup')?"selected":"";?> value="Store Pickup">Store Pickup</option>
                                                            <option <?=($order->shipping_method=='UPS: 2nd Day Air AM')?"selected":"";?> value="UPS: 2nd Day Air AM">UPS: 2nd Day Air AM</option>
                                                            <option <?=($order->shipping_method=='UPS: 3 Day Select')?"selected":"";?> value="UPS: 3 Day Select">UPS: 3 Day Select</option>
                                                            <option <?=($order->shipping_method=='UPS: Canada Standard')?"selected":"";?> value="UPS: Canada Standard">UPS: Canada Standard</option>
                                                            <option <?=($order->shipping_method=='UPS: Next Day Air Early AM')?"selected":"";?> value="UPS: Next Day Air Early AM">UPS: Next Day Air Early AM</option>
                                                            <option <?=($order->shipping_method=='UPS: Next Day Air Saver')?"selected":"";?> value="UPS: Next Day Air Saver">UPS: Next Day Air Saver</option>
                                                            <option <?=($order->shipping_method=='UPS: Worldwide Expedited')?"selected":"";?> value="UPS: Worldwide Expedited">UPS: Worldwide Expedited</option>
                                                            <option <?=($order->shipping_method=='UPS: Worldwide Express')?"selected":"";?> value="UPS: Worldwide Express">UPS: Worldwide Express</option>
                                                            <option <?=($order->shipping_method=='UPS: Worldwide Express Plus')?"selected":"";?> value="UPS: Worldwide Express Plus">UPS: Worldwide Express Plus</option>
                                                            <option <?=($order->shipping_method=='USPS: Express Mail')?"selected":"";?> value="USPS: Express Mail">USPS: Express Mail</option>
                                                            <option <?=($order->shipping_method=='USPS: Express Mail International')?"selected":"";?> value="USPS: Express Mail International">USPS: Express Mail International</option>
                                                            <option <?=($order->shipping_method=='USPS: First Class')?"selected":"";?> value="USPS: First Class">USPS: First Class</option>
                                                            <option <?=($order->shipping_method=='USPS: First Class International')?"selected":"";?> value="USPS: First Class International">USPS: First Class International</option>
                                                            <option <?=($order->shipping_method=='USPS: Global Express Guaranteed')?"selected":"";?> value="USPS: Global Express Guaranteed">USPS: Global Express Guaranteed</option>
                                                            <option <?=($order->shipping_method=='USPS: Media Mail')?"selected":"";?> value="USPS: Media Mail">USPS: Media Mail</option>
                                                            <option <?=($order->shipping_method=='USPS: Parcel Post')?"selected":"";?> value="USPS: Parcel Post">USPS: Parcel Post</option>
                                                            <option <?=($order->shipping_method=='USPS: Priority Mail')?"selected":"";?> value="USPS: Priority Mail">USPS: Priority Mail</option>
                                                            <option <?=($order->shipping_method=='USPS: Priority Mail International')?"selected":"";?> value="USPS: Priority Mail International">USPS: Priority Mail International</option>
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
												<td colspan="2">&nbsp;</td>
												<td colspan="2">
												<iframe style="width:100%" src="//www.google.com/maps/embed/v1/place?q=<?=urlencode(trim($address));?>
													  &zoom=10
													  &key=AIzaSyDW_2lO_gD3jf8qdQXcCeJTrlZMg8M2op8">
												  </iframe>
												</td>
											</tr>
											


                                                    @if($order->coupons()->count()!=0)

                                            <tr>
                                                <td></td><td></td>
                                                <th colspan="2">Coupons</th>
                                            </tr>
                                                @foreach($order->coupons()->get() as $coupon)
                                            <tr>
                                                <td></td><td></td>
                                                <td colspan="2">{{ $coupon->coupon_code }}</td>
                                            </tr>
                                                @endforeach
                                                @endif
                                            <tr>
                                                <th colspan="2">
                                                    Contact Info
                                                    <a href="javascript:;" 
                                                       onclick="$('.contactview').toggle(function(){
                                                           if($(this).is('hidden')){
                                                               $(this).addClass('hidden');
                                                           }else{
                                                               $(this).removeClass('hidden');
                                                           }
                                                       });
                                                       $('.contactedit').toggle(function(){
                                                           if($(this).is('hidden')){
                                                               $(this).addClass('hidden');
                                                           }else{
                                                               $(this).removeClass('hidden');
                                                           }
                                                       });
                                                       "><span class="fa fa-edit"></span></a>
                                                       <button name="submit_contact" class="btn btn-primary btn-sm contactedit hidden pull-right">Submit</button>
                                                </th>
                                                <td></td><td></td>
                                            </tr>
                                            <tr>
                                                <td>Email:</td><td><div class="contactview">{{$order->email}}</div><div class="contactedit hidden"><input type="text" name="email" value="{{$order->email}}" /></div></td>
                                                <td></td><td></td>
                                            </tr>
                                            <tr>
                                                <td>Day Phone:</td><td><div class="contactview">{{$order->day_phone}}</div><div class="contactedit hidden"><input type="text" name="day_phone" value="{{$order->day_phone}}" /></div></td>
                                                <td></td><td></td>
                                            </tr>
                                            <tr>
                                                <td>Evening Phone:</td><td><div class="contactview">{{$order->evening_phone}}</div><div class="contactedit hidden"><input type="text" name="evening_phone" value="{{$order->evening_phone}}" /></div></td>
                                                <td></td><td></td>
                                            </tr>
                                            <tr><td colspan="4">&nbsp;</td></tr>
                                            <tr>
                                                <td>Card Type:</td><td>{{$order->card_type}}</td>
                                                <td></td><td></td>
                                            </tr>
                                            <tr>
                                                <td>Card Number:</td><td>{{$order->card_number}}</td>
                                                <td></td><td></td>
                                            </tr>
                                            <tr>
                                                <td>Exp Month:</td><td>{{$order->card_exp_month}}</td>
                                                <td></td><td></td>
                                            </tr>
                                            <tr>
                                                <td>Exp Year:</td><td>{{$order->card_Exp_year}}</td>
                                                <td></td><td></td>
                                            </tr>
                                        </tbody>
                                    </table>                                    
                                </div>
                            </div>
                        </div>
                        </form>
                        <script>
                            var result = new Array();
                            $(document).on('click', '.send-to-ac', function () {
                                result = new Object();
                                var id = this.id;
                                result[this.id] = {message:''};
                                var agree = confirm('Do you want to send Order no. '+id+' to AC?');
                                if(agree) {
                                    populateResult(result, '#account_modal');
                                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                                    $.ajax({
                                        url: '{{url('/order/accounting')}}',
                                        data: {id: this.id, _token: CSRF_TOKEN},
                                        dataType: 'json',
                                        type: 'POST',
                                        success: function (data, status, header) {
                                            $('#account_modal div#result').html(data.message);
                                        }, error: function (data, status, header) {

                                        }

                                    })
                                }
                            });
                            $(document).on('click', '.send-to-sear', function () {
                                result = new Object();
                                var id = this.id;
                                result[this.id] ={message:''};

                                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                               var agree = confirm('Do you want to send Order no. '+id+' to Sears?');
                                if(agree){
                                    populateResult(result,'#modal');
                                    $.ajax({
                                        url: '{{url('/order/sears')}}',
                                        data: {id: id, _token: CSRF_TOKEN},
                                        dataType: 'json',
                                        type: 'POST',
                                        success: function (data, status, header) {
                                            if(status==200){
                                                result[id] = data;
                                                $('td#'+id).html("<span>"+data['message']+"</span><br/><span>Document ID : "+data['data']['document-id']+"</span>" );
                                            }else{
                                                $('td#'+id).html("<span>"+data['message']+"</span><br/><textarea>"+data['xml']+"</textarea>" );
                                            }
                                        }, error: function (data, status, header) {

                                        }

                                    })
                                }


                            });

                            function populateResult(result,modal) {
                                $(modal+' table.send-accounting-process tbody').html('');
                                var count = 1;
                                $.each(result, function (key, value)
                                {
                                    $('table.send-accounting-process tbody').append("<tr><td>" + count + "</td><td>" + key + "</td> <td id='" + key + "'><i class='fa fa-spin fa-gear'></i></td></tr>");
                                });
                                $(modal).modal({backdrop: 'static',
                                    keyboard: false});
                                $('button.close').hide();
                                $(modal).modal('show')
                                $('button.close').show();
                            }
                        </script>



                    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id='modal'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Processing send to Sears</h4>

                </div>
                <div class="modal-body">
                    <div class="row">
                        <table class="table table-responsive send-accounting-process">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Order Id</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>


                            </tbody>
                            <tfoot>

                            </tfoot>
                        </table>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div><!-- /.modal-content -->
    <div class="modal fade" tabindex="-1" role="dialog" id="account_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Processing Sean to Accounting</h4>

                </div>
                <div class="modal-body">
                    <div class="row" id="result">
                        <table class="table table-responsive send-accounting-process">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Order Id</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>


                            </tbody>
                            <tfoot>

                            </tfoot>
                        </table>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div><!-- /.modal-content -->
<script>
    $(document).on('click','.item-delete',function(){
        var agree= confirm("Do you want to delete this item?");
        if(agree){
            window.location.href="{{url('item/delete/')}}/"+this.id+"/{{$order->order_id}}"
        }
    })
</script>
@stop