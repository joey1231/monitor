@extends('master')
@section('container')
    <style type="text/css">
        .orderstatdatatable tbody tr:hover {
            background: #eae4e4;
        }
    </style>
    <!-- START WIDGETS -->
    <div class="row">
        <div class="col-md-3 col-sm-12 col-xs-12 pull-right">
            <input type="hidden" value="{{ date("F d, Y H:i:s", time()) }}" name="serverdate"/>
            <!-- START WIDGET CLOCK -->
            <div class="widget widget-danger widget-padding-sm">
                <div class="widget-big-int plugin-clock">00:00</div>
                <div class="widget-subtitle plugin-date">Loading...</div>
                <div class="widget-controls">
                    <a href="#" class="widget-control-right widget-remove" data-toggle="tooltip" data-placement="left"
                       title="Remove Widget"><span class="fa fa-times"></span></a>
                </div>
                <div class="widget-buttons widget-c3">
                    {{ date_default_timezone_get() }}
                </div>
            </div>

            <!-- END WIDGET CLOCK -->
            <a href="{{url('/order/uctracking/?'.http_build_query($request))}}">
                <div class="widget widget-primary widget-item-icon">
                    <div class="widget-item-right">
                        <span class="fa fa-download"></span>
                    </div>
                    <div class="widget-data-left">
                        <div class="widget-title">Export Tracking No. (csv)</div>
                        <div class="widget-subtitle">For UltraCart</div>
                    </div>
                </div>
            </a>
            <a id="exportforsears" href="{{url('/order/searsexport/?'.http_build_query($request))}}">
                <div class="widget widget-primary widget-item-icon">
                    <div class="widget-item-right">
                        <span class="fa fa-download"></span>
                    </div>
                    <div class="widget-data-left">
                        <div class="widget-title">Export Orders</div>
                        <div class="widget-subtitle">For Sears</div>
                    </div>
                </div>
            </a>

        </div>


        <div class="col-md-9 col-sm-12 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Filter Orders</h3>
                    <form class="form-horizontal" role="form" method="get" action="{{url('/order')}}">
                        <div class="form-group">

                            <div class="col-md-6 no-padding">
                                <label class="col-md-5 control-label">Sears Status</label>
                                <div class="col-md-7">
                                    <select name="searstat[]" multiple class="form-control select">
                                        <option @if(isset($request['searstat'])) @if(in_array('No POs found',$request['searstat'])) selected @endif @endif>
                                            No POs found
                                        </option>
                                        <option @if(isset($request['searstat'])) @if( in_array('Processing',$request['searstat'])) selected @endif  @endif>
                                            Processing
                                        </option>
                                        <option @if(isset($request['searstat'])) @if( in_array('All Shipped',$request['searstat'])) selected @endif  @endif>
                                            All Shipped
                                        </option>
                                        <option @if(isset($request['searstat'])) @if(in_array('Partially Shipped',$request['searstat'])) selected @endif  @endif>
                                            Partially Shipped
                                        </option>
                                        <option @if(isset($request['searstat'])) @if(in_array('Combination',$request['searstat'])) selected @endif  @endif>
                                            Combination
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 no-padding">
                                <label class="col-md-6 control-label">Payment Status</label>
                                <div class="col-md-6">
                                    <select name="payment_status[]" multiple class="form-control select">
                                        <option @if(isset($request['payment_status'])) @if(in_array('Processing',$request['payment_status'])) selected @endif @endif>
                                            Processing
                                        </option>
                                        <option @if(isset($request['payment_status'])) @if(in_array('Declined',$request['payment_status'])) selected @endif @endif>
                                            Declined
                                        </option>
                                        <option @if(isset($request['payment_status'])) @if(in_array('Unprocessed',$request['payment_status'])) selected @endif @endif>
                                            Unprocessed
                                        </option>
                                        <option @if(isset($request['payment_status'])) @if(in_array('Refunded',$request['payment_status'])) selected @endif @endif>
                                            Refunded
                                        </option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <label class="col-md-6 control-label">Sent to AC</label>
                                <div class="col-md-6">
                                    <select name="sent_to_ac" class="form-control select">
                                        <option></option>
                                        <option @if(isset($request['sent_to_ac'])) @if($request['sent_to_ac'] == 'Y') selected
                                                @endif @endif value="Y">Yes
                                        </option>
                                        <option @if(isset($request['sent_to_ac'])) @if($request['sent_to_ac'] == 'N') selected
                                                @endif @endif value="N">No
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="col-md-6 control-label">Sent to Sears</label>
                                <div class="col-md-6">
                                    <select name="sent_to_sears" class="form-control select">
                                        <option></option>
                                        <option @if(isset($request['sent_to_sears'])) @if($request['sent_to_sears'] == 'Y') selected
                                                @endif @endif  value="Y">Yes
                                        </option>
                                        <option @if(isset($request['sent_to_sears'])) @if($request['sent_to_sears'] == 'N') selected
                                                @endif @endif   value="N">No
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 no-padding">
                                <div class="col-md-6">
                                    <label class="col-md-12 control-label">Order Date</label>
                                </div>
                                <div class="col-md-6 no-padding">

                                    <input type="text" class="form-control daterange" name="dtrange" value=""/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="col-md-6 control-label">Stage</label>
                                <div class="col-md-6">
                                    <select name="current_stage[]" multiple class="form-control select">
                                        <option @if(isset($request['current_stage'])) @if(in_array('AR',$request['current_stage'])) selected
                                                @endif @endif  value="AR">AR - Accounts Receivable
                                        </option>
                                        <option @if(isset($request['current_stage'])) @if(in_array('CO',$request['current_stage'])) selected
                                                @endif @endif value="CO">CO - Completed Order
                                        </option>
                                        <option @if(isset($request['current_stage'])) @if(in_array('IN',$request['current_stage'])) selected
                                                @endif @endif value="IN">IN - Inserting (Placed Order)
                                        </option>
                                        <option @if(isset($request['current_stage'])) @if(in_array('PC',$request['current_stage'])) selected
                                                @endif @endif value="PC">PC - Pending Clearance (of check orders)
                                        </option>
                                        <option @if(isset($request['current_stage'])) @if(in_array('PO',$request['current_stage'])) selected
                                                @endif @endif value="PO">PO - Pre-Order
                                        </option>
                                        <option @if(isset($request['current_stage'])) @if(in_array('QR',$request['current_stage'])) selected
                                                @endif @endif value="QR">QR - Quote Request
                                        </option>
                                        <option @if(isset($request['current_stage'])) @if(in_array('QS',$request['current_stage'])) selected
                                                @endif @endif value="QS">QS - Quote Sent
                                        </option>
                                        <option @if(isset($request['current_stage'])) @if(in_array('REJ',$request['current_stage'])) selected
                                                @endif @endif value="REJ">REJ - Rejected
                                        </option>
                                        <option @if(isset($request['current_stage'])) @if(in_array('SD',$request['current_stage'])) selected
                                                @endif @endif value="SD">SD - Shipping Department
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 no-padding">
                                <div class="col-md-6">
                                    <label class="col-md-12 control-label">Order</label>
                                </div>
                                <div class="col-md-6 no-padding">

                                    <input type="text" class="form-control" id="order_id" name="order_id" value="@if(isset($request['order_id'])){{$request['order_id']}}@endif"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="col-md-6 control-label">Store Front</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="store_front" name="store_front" value="@if(isset($request['store_front'])){{$request['store_front']}}@endif"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">

                            </div>
                            <div class="col-md-4">
                                <div class="pull-right">
                                    <button class="btn btn-success"><span class="fa fa-refresh"></span> UPDATE</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-body">
                    <h5>Upload Sears Tracking Numbers</h5>
                    <form method="post" enctype="multipart/form-data" action="{{ url('/order/tracking') }}" class="form-horizontal">
                        @if(count($errors->all())>0)
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $err)
                                    {{$err}}<br/>
                                @endforeach
                            </div>
                        @endif
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="file" name="upload_sears_tracking" multiple class="file"
                               data-preview-file-type="any"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END WIDGETS -->
    <div class="row">
        <div class="col-md-12">

            <!-- List of orders -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title-box">
                        <h3>Order Logs</h3>
                        <span>List of Orders</span>
                    </div>
                    <ul class="panel-controls pull-right">
                        <li><a href="#" class="panel-fullscreen"><span class="fa fa-expand"></span></a></li>
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                    </ul>
                    <div class="col-md-5 pull-right">

                        <div class="col-md-4">
                            <select id="selectorders" multiple="" class="form-control select">
                                <option id="notsenttosesarsopt" value="1">Not Sent to Sears</option>
                                <option id="notsenttoacopt" value="2">Not Sent to ActiveCampaign</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <a href="#" id="bulksendtoac" class="btn btn-default">Bulk Send to AC</a>
                        </div>

                        <div class="col-md-4">
                            <a href="#" id="bulksendtosears" class="btn btn-default">Bulk Send to Sears</a>
                        </div>
                    </div>
                </div>
                <script>
                    $(document).ready(function () {
                        $('.ordertable').dataTable({
                            "order": [[1, "desc"]], "pageLength": 50,
                            "fnDrawCallback": function (oSettings) {
                                $('.dataTables_paginate').hide();
                                $('.dataTables_info').hide();
                                $('.dataTables_filter').hide();
                            }
                        });
                        $('#order_id').autocomplete({
                             source: '{{url('/order/orderidautocomplete')}}' ,
                            
                        });
                         $('#store_front').autocomplete({
                             source: '{{url('/order/frontautocomplete')}}' ,
                            
                        });
                    })
                </script>
                <div class="panel-body" style="overflow:auto;">
                    <table class="table  ordertable table-responsive">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th></th>
                            <th>UC/AC Order ID</th>
                            <th>Sears Order ID</th>
                            <th>Date Ordered</th>
                            <th>Store Front</th>
                            <th>Test Order</th>
                            <th>Sears Status</th>
                            <th>Payment Status</th>
                            <th>Stage</th>
                            <th>Total Items</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $count++ }}</td>
                                <td>
                                    <input class="@if($order->sent_to_sears == 'N'  && $order->bulk_sears == 0) {{'notsenttosears'}}@endif @if($order->sent_to_ac == 'N'  && $order->bulk_ac == 0){{' notsenttoac'}}@endif"
                                           type="checkbox" name="checkorder" value="{{$order->order_id}}"/></td>
                                <td><a href="{{url('order/'.$order->order_id)}}">{{$order->order_id}}</a></td>
                                <td>
                                    @if($order->set_to_sears == 'Y' && strtolower($order->searstat) == strtolower('No POs found') )

                                        <span class='label label-warning' title='Order Not Processed by Sears'><span
                                                    class='fa fa-warning'></span> </span>

                                    @endif
                                    {{ str_replace('-','',$order->order_id) }}
                                </td>
                                <td>{{ date('Y-m-d g:i:s',strtotime($order->order_date)) }}</td>
                                <td>{{ $order->screen_branding_theme_code }}</td>
                                <td>{{ $order->test_order }}</td>
                                <td>{{ $order->searstat }}</td>
                                <td>{{ $order->payment_status }}</td>
                                <td>{{ $order->current_stage }}</td>
                                <td>{{ count($order->items()->get()) }}</td>
                                <td>{{ $order->total }}</td>
                                <td>
                                    @if($order->sent_to_ac == 'N' && $order->bulk_ac == 0)

                                        <button id="{{$order->order_id}}"
                                                class="btn btn-info btn-block btn-sm text-left send-to-ac ac-{{$order->order_id}}"

                                        ><span class="fa fa-mail-forward"></span> Send to AC
                                        </button>

                                    @endif
                                    @if($order->sent_to_sears == 'N'  && $order->bulk_sears == 0)

                                        <button
                                                class="btn btn-warning btn-block btn-sm text-left send-to-sear s-{{$order->order_id}}"
                                                id="{{$order->order_id}}"

                                        ><span class="fa fa-mail-forward"></span> Send to Sears
                                        </button>
                                    @endif
                                    @if(!true)
                                        <button id="{{$order->order_id}}" href="javascript:;"
                                                title="Scheduled for Sending to Sears"
                                                class="btn btn-warning btn-block btn-sm text-left disabled scheduled"
                                        ><span class="fa fa-clock-o" se></span> Scheduled
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="13"
                                style="text-align: right !important;">{!! $orders->appends($request)->render() !!}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="panel-footer">
                </div>
            </div>
            <!-- END List of orders -->


        </div>
        <script>
         $(document).on('click', '#bulksendtoac', function () {
          

        var orders = [];

        $.each($("input[name='checkorder']:checked"), function(){            

            orders.push($(this).val());

        });
         if(orders.length > 0){
         var agree = confirm('Do you want to send Order tp bulk?');
                if(agree) {
                    
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: '{{url('/order/ordertoacbulk')}}',
                        data: {orders: orders, _token: CSRF_TOKEN},
                        dataType: 'json',
                        type: 'POST',
                        success: function (data, status, header) {
                            alert(data.message);
                            location.reload();
                        }, error: function (data, status, header) {

                        }

                    })
                }
            }
       // alert(orders.join("|"));

        //window.location = $(this).attr('href') + "?order_id=" + orders.join("|");

        return false;

    });
          $(document).on('click', '#bulksendtosears', function () {
          

        var orders = [];

        $.each($("input[name='checkorder']:checked"), function(){            

            orders.push($(this).val());

        });
        if(orders.length > 0){
         var agree = confirm('Do you want to send Order tp bulk?');
                if(agree) {
                    
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: '{{url('/order/ordertosearsbulk')}}',
                        data: {orders: orders, _token: CSRF_TOKEN},
                        dataType: 'json',
                        type: 'POST',
                        success: function (data, status, header) {
                            alert(data.message);
                            location.reload();
                        }, error: function (data, status, header) {

                        }

                    })
                }
        }
       // alert(orders.join("|"));

        //window.location = $(this).attr('href') + "?order_id=" + orders.join("|");

        return false;

    });
         
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
                             $('.ac-'+id).hide();
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
                                $('.s-'+id).hide();
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

@stop
