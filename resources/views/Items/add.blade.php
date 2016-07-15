@extends('master')
@section('container')
<!-- START WIDGETS -->
<div class="row">
    <div class="col-md-9">
        <form action="{{url('item/create/'.$order->order_id)}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="item_index" value="" />

            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title-box">
                        <h3>Add Item to Order</h3>
                        <span>Fill in required fields (*)</span>
                    </div>
                    <ul class="panel-controls" style="margin-top: 2px;">
                        <li><a href="{{url('order/'.$order->order_id)}}" class=""><span class="fa fa-reply"></span></a></li>
                        <li><a href="#" class="panel-fullscreen"><span class="fa fa-expand"></span></a></li>
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    @if(count($errors->all())>0)
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $err)
                                {{$err}}<br/>
                            @endforeach
                        </div>
                    @endif
                    <table class="table table-condensed">
                        <tbody>
                        <tr>
                            <td>Item ID*:</td><td><input required="" type="text" name="item_id" value="" /></td>
                        </tr>
                        <tr>
                            <td>Quantity*:</td><td><input required="" class="spinner" type="text" name="quantity" value="1" /></td>
                        </tr>
                        <tr>
                            <td>Description*:</td><td><textarea required="" name="description" rows="4" cols="50"></textarea></td>
                        </tr>
                        <tr>
                            <td>Cost*:</td><td><input required="" type="text" name="cost" value="0.00" /></td>
                        </tr>
                        <tr>
                            <td>Unit Cost w/ Discount*:</td><td><input required="" type="text" name="unit_cost_with_discount" value="0.00" /></td>
                        </tr>
                        <tr>
                            <td>Total Cost w/ Discount*:</td><td><input required="" type="text" name="total_cost_with_discount" value="0.00" /></td>
                        </tr>
                        <tr>
                            <td>Cogs:</td><td><input type="text" name="cogs" value="0.00" /></td>
                        </tr>
                        <tr>
                            <td>Actual Cogs:</td><td><input type="text" name="actual_cogs" value="0.00" /></td>
                        </tr>
                        <tr>
                            <td>Country of Origin:</td><td><input type="text" name="country_of_origin" value="" /></td>
                        </tr>
                        <tr>
                            <td>Item Weight:</td><td><input type="text" name="item_weight" value="0 LB" /></td>
                        </tr>
                        <tr>
                            <td>Tax Free:</td>
                            <td>
                                <select name="tax_free">
                                    <option value="false">False</option>
                                    <option value="true">True</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Special Product Type:</td><td><input type="text" name="special_product_type" value="" /></td>
                        </tr>
                        <tr>
                            <td>Free Shipping:</td>
                            <td>
                                <select name="free_shipping">
                                    <option value="false">False</option>
                                    <option value="true">True</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Accounting Code*:</td><td><input required="" type="text" name="accounting_code" value="" /></td>
                        </tr>
                        <tr>
                            <td>Discount:</td><td><input type="text" name="discount" value="0.00" /></td>
                        </tr>
                        <tr>
                            <td>Distribution Center Code:</td><td><input required="" type="text" name="distribution_center_code" value="" /></td>
                        </tr>
                        <tr>
                            <td>Kit:</td>
                            <td>
                                <select name="kit">
                                    <option value="N">No</option>
                                    <option value="Y">Yes</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Kit Component:</td>
                            <td>
                                <select name="kit_component">
                                    <option value="N">No</option>
                                    <option value="Y">Yes</option>
                                </select>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
                <div class="panel-footer">
                    <button name="add_item" class="btn btn-primary pull-right">Submit</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-3 pull-right">
        <input type="hidden" value="<?=date("F d, Y H:i:s", time());?>" name="serverdate" />
        <!-- START WIDGET CLOCK -->
        <div class="widget widget-danger widget-padding-sm">
            <div class="widget-big-int plugin-clock">00:00</div>
            <div class="widget-subtitle plugin-date">Loading...</div>
            <div class="widget-controls">
                <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="left" title="Remove Widget"><span class="fa fa-times"></span></a>
            </div>
            <div class="widget-buttons widget-c3">
                <?=date_default_timezone_get();?>
            </div>
        </div>
        <!-- END WIDGET CLOCK -->

        <div class="panel panel-default">
            <div class="panel-heading ui-draggable-handle">
                <h3 class="panel-title"><span class="fa fa-info-circle"></span> Order Info</h3>
                <ul class="panel-controls">
                    <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                    <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                </ul>
            </div>
            <div class="panel-body">
                <form action="#" role="form" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-4 control-label text-left">Order No.</label>
                        <div class="col-md-8">
                            <span class="help-block">{{$order->order_id}}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label text-left">Order Date</label>
                        <div class="col-md-8">
                            <span class="help-block">{{$order->order_date}}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label text-left">Company</label>
                        <div class="col-md-8">
                            <span class="help-block">{{$order->bill_to_company}}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label text-left">Bill To</label>
                        <div class="col-md-8">
                            <span class="help-block">{{$order->bill_to_first_name}} {{$order->bill_to_last_name}}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label text-left">Ship To</label>
                        <div class="col-md-8">
                            <span class="help-block">{{$order->ship_to_first_name}} {{$order->ship_to_last_name}}</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
    @stop
