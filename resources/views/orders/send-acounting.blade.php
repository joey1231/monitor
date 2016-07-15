<!-- START WIDGETS -->
<div class="row">
    <div class="col-md-9">


        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title-box">
                    <h3><a target="_blank" href="#">{{$order->order_id}}</a></h3>
                    <span>AC Details</span>
                </div>
                <ul class="panel-controls" style="margin-top: 2px;">
                    <li><a href="#" class="panel-fullscreen"><span class="fa fa-expand"></span></a></li>
                    <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                    <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                </ul>
            </div>
            <div class="panel-body">
                <h3>Customer Information</h3>
                Email: {{$order->email}}<br />
                First Name: {{$order->bill_to_first_name}}<br />
                Last Name: {{$order->bill_to_last_name}}<br />
                Phone:  {{$order->day_phone}}<br />
                Company: {{$order->bill_to_company}}><br />
                <hr />
                <h3>Contact Order Data</h3>
                Total Spent: {{$information['fields'][1]['val']}}<br />
                Total Orders: {{$information['fields'][5]['val']}}<br />
                <hr />
                <h3>Add Deal</h3>
                Title: {{$order->order_id}}<br />
                Value: {{$order->total}}<br />
                <hr />
                <h3>Add Deal Note</h3>
                Sub-Total: {{number_format($order->subtotal-$order->subtotal_discount,2)}}<br />
                Shipping: {{$order->shipping_handling_total}}<br />
                Discounts: {{number_format($order->subtotal_discount,2)}}<br />
                Total: {{$order->total}}<

            </div>
        </div>

    </div>
</div>