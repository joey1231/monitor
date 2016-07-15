@extends('master')
@section('container')
<div class="row">
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title-box">
                    <h3>Sears Tracking Upload</h3>
                    <span>Upload Details</span>
                </div>
                <ul class="panel-controls" style="margin-top: 2px;">
                    <li><a href="#" class="panel-fullscreen"><span class="fa fa-expand"></span></a></li>
                    <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                    <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                </ul>
            </div>
            <div class="panel-body">
                <p>Upload: {{ $file->getClientOriginalName()}}</p>
                <p>Type: {{ $file->getClientOriginalExtension()}}</p>
                <p>Size: {{ $file->getSize()}} </p>
                <p>MimeType: {{ $file->getMimeType()}} </p>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>SKU</th>
                        <th>Tracking No.</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($trackings as $i=>$tracking)
                            <td>{{ $tracking->order_id }}</td>
                            <td>{{ $tracking->sku }}</td>
                            <td>{{ $tracking->number }}</td>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
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
    </div>
</div>
   @stop