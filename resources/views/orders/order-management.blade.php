@extends('master')
@section('container')
<style type="text/css">
    .orderstatdatatable tbody tr:hover{
        background: #eae4e4;
    }
</style>
<script>
    $(document).ready(function () {
        $('.ordertable').dataTable({
            "order": [[1, "desc"]], "pageLength": 50,
            "fnDrawCallback": function (oSettings) {
                $('.dataTables_paginate').hide();
                $('.dataTables_info').hide();
                $('.dataTables_length').hide();
                 $('.dataTables_filter').hide();
            }
        });
    })
</script>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row hidden" id="show_new_order_check">
                    <div class="col-md-12">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Checking New Orders</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <a href="{{url('/order/stage/?stage=Ready')}}" class="btn @if($stage=='Ready') btn-primary disabled @else btn-default  @endif">Ready <span class="label label-info">{{ $totalReady }}</span></a>
                        <a href="{{url('/order/stage/?stage=Review')}}" class="btn @if($stage=='Review') btn-primary disabled @else btn-default  @endif">Review <span class="label label-info">{{ $totalReview }}</span></a></a>
                        <a class="btn  @if($stage=='Contact') btn-primary disabled @else btn-default  @endif">Contact</a>
                        <a class="btn  @if($stage=='On Hold') btn-primary disabled @else btn-default  @endif">On Hold</a>
                    </div>
                    <div class="col-md-3">
                        <a class="btn  btn-default">Partial Processed</a>
                        <a href="{{url('/order/stage/?stage=Processed')}}" class="btn @if($stage=='Processed') btn-primary disabled @else btn-default  @endif">Processed <span class="label label-info">{{ $totalProcessed }}</span></a></a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{url('/order/stage/?stage=Partial Ship')}}" class="btn  @if($stage=='Partial Ship') btn-primary disabled @else btn-default  @endif">Partial Ship <span class="label label-info">{{ $totalPartial }}</span></a>
                        <a href="{{url('/order/stage/?stage=Shipped')}}" class="btn  @if($stage=='Shipped') btn-primary disabled @else btn-default  @endif">Shipped <span class="label label-info">{{ $totalShipped }}</span></a>
                        <a href="{{url('/order/stage/?stage=Cancelled')}}" class="btn  @if($stage=='Cancelled') btn-primary disabled @else btn-default  @endif">Cancelled <span class="label label-info">{{ $totalCancelled }}</span></a>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <table class="table ordertable table-responsive">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Order ID</th>
                            <th>Fulfillment ID</th>
                            <th>Order Date</th>
                            <th>Fulfillment Status</th>
                            <th>Total Items</th>
                            <th>Total</th>
                            <th>Issue</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $count++ }}</td>

                                <td><a href="{{url('order/'.$order->order_id)}}">{{$order->order_id}}</a></td>
                                <td>
                                    {{ str_replace('-','',$order->order_id) }}
                                </td>
                                <td>{{ date('Y-m-d g:i:s',strtotime($order->order_date)) }}</td>

                                <td>{{ $order->searstat }}</td>
                                <td>{{ count($order->items()->get()) }}</td>
                                <td>{{ $order->total }}</td>
                                <td>{{ $order->order_issue }}</td>
                                <td>

                                  @if($order->sent_to_sears == 'N'  && $order->bulk_sears == 0)

                                        <button
                                                class="btn btn-warning btn-block btn-sm text-left send-to-sear s-{{$order->order_id}}"
                                                id="{{$order->order_id}}"

                                        ><span class="fa fa-mail-forward"></span> Send to Sears
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
            <script>
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
                                console.log(data);

                            }, error: function (data, status, header) {

                            }

                        })
                    }


                });

                function populateResult(result,modal) {
                    $('table.send-accounting-process tbody').html('');
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
        </div>
    </div>
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


@stop