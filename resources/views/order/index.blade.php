@extends('layouts.app')
@section('title', __( 'Orders' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'Orders' )
        <small>@lang( 'Manage Your Orders' )</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'All Your Orders' )])
       
        @can('order.view')
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="order_table">
                    <thead>
                        <tr>
                            <th>@lang( 'Action' )</th>
                            <th>@lang( 'Status' )</th>
                            <th>@lang( 'Order No' )</th>
                            <th>@lang( 'Order Date' )</th>
                            <th>@lang( 'Sales Person' )</th>
                            <th>@lang( 'Customer' )</th>
                            <th>@lang( 'Payable ($)' )</th>
                            <th>@lang( 'Products' )</th>
                            <th>@lang( 'Credit Memo' )</th>
                            <th>@lang( 'Packed Boxes' )</th>
                            <th>@lang( 'Shipping Type' )</th>
                            <th>@lang( 'Delivery Date' )</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcan
    @endcomponent

    <div class="modal fade state_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->

@endsection

@section('javascript')
<script>
$(document).ready(function () {
    var state_table = $('#order_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/customer_orders',
        columnDefs: [
            {   
                orderable: false,
                searchable: false,
            },
        ],
        columns: [
            { data: 'action', name: 'action' },
            { data: 'status', name: 'status' },
            { data: 'order_no', name: 'order_no' },
            { data: 'order_date', name: 'order_date' },
            { data: 'sales_person', name: 'sales_person' },
            { data: 'customer', name: 'customer' },
            { data: 'payable', name: 'payable' },
            { data: 'products', name: 'products' },
            { data: 'credit_memo', name: 'credit_memo' },
            { data: 'packed_boxes', name: 'packed_boxes' },
            { data: 'shipping_type', name: 'shipping_type' },
            { data: 'delivery_date', name: 'delivery_date' },
           
        ],
    });
});
</script>
@endsection
