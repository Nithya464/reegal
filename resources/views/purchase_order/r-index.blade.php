@extends('layouts.app')
@section('title', __('lang_v1.purchase_order'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1>@lang('lang_v1.purchase_order')
    </h1>
</section>

<!-- Main content -->
<section class="content no-print">
    @component('components.filters', ['title' => __('report.filters')])
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('po_list_filter_status',  __('sale.status') . ':') !!}
                {!! Form::select('po_list_filter_status', $purchaseOrderStatuses, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
            </div>
        </div>
        @if(!empty($shipping_statuses))
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('shipping_status', __('lang_v1.shipping_status') . ':') !!}
                    {!! Form::select('shipping_status', $shipping_statuses, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
                </div>
            </div>
        @endif
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('po_list_filter_date_range', __('report.date_range') . ':') !!}
                {!! Form::text('po_list_filter_date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'readonly']); !!}
            </div>
        </div>
    @endcomponent@component('components.filters', ['title' => __('report.filters')])
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('po_list_filter_status',  __('sale.status') . ':') !!}
                {!! Form::select('po_list_filter_status', $purchaseOrderStatuses, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
            </div>
        </div>
        @if(!empty($shipping_statuses))
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('shipping_status', __('lang_v1.shipping_status') . ':') !!}
                    {!! Form::select('shipping_status', $shipping_statuses, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
                </div>
            </div>
        @endif
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('po_list_filter_date_range', __('report.date_range') . ':') !!}
                {!! Form::text('po_list_filter_date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'readonly']); !!}
            </div>
        </div>
    @endcomponent
    @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.all_purchase_orders')])
        <table class="table table-bordered table-striped ajax_view" id="purchase_order_table" style="width: 100%;">
            <thead>
                <tr>
                    <th>@lang('messages.action')</th>
                    <th>{{ __('PO No') }}</th>
                    <th>{{ __('PO Date') }}</th>
                    <th>{{ __('Location') }}</th>
                    <th>{{ __('PO Status') }}</th>
                    <th>{{ __('No of Items') }}</th>
                    <th>@lang('purchase.ref_no')</th>
                    <th>{{ __('PO Remark') }}</th>
                </tr>
            </thead>
        </table>
    @endcomponent
    <div class="modal fade edit_pso_status_modal" tabindex="-1" role="dialog"></div>
</section>
<!-- /.content -->
@stop
@section('javascript')	
@includeIf('purchase_order.common_js')
<script type="text/javascript">
    $(document).ready( function(){
        //Purchase table
        purchase_order_table = $('#purchase_order_table').DataTable({
            processing: true,
            serverSide: true,
            aaSorting: [[1, 'desc']],
            scrollY: "75vh",
            scrollX:        true,
            scrollCollapse: true,
            ajax: {
                url: '{{action([\App\Http\Controllers\PurchaseOrderRController::class, 'index'])}}',
                data: function(d) {
                    if ($('#po_list_filter_location_id').length) {
                        d.location_id = $('#po_list_filter_location_id').val();
                    }
                    if ($('#po_list_filter_supplier_id').length) {
                        d.supplier_id = $('#po_list_filter_supplier_id').val();
                    }
                    if ($('#po_list_filter_status').length) {
                        d.status = $('#po_list_filter_status').val();
                    }
                    if ($('#shipping_status').length) {
                        d.shipping_status = $('#shipping_status').val();
                    }

                    var start = '';
                    var end = '';
                    if ($('#po_list_filter_date_range').val()) {
                        start = $('input#po_list_filter_date_range')
                            .data('daterangepicker')
                            .startDate.format('YYYY-MM-DD');
                        end = $('input#po_list_filter_date_range')
                            .data('daterangepicker')
                            .endDate.format('YYYY-MM-DD');
                    }
                    d.start_date = start;
                    d.end_date = end;

                    d = __datatable_ajax_callback(d);
                },
            },
            columns: [
                { data: 'action', name: 'action', orderable: false, searchable: false },
                { data: 'po_no', name: 'po_no' },
                { data: 'transaction_date', name: 'transaction_date' },
                { data: 'location_name', name: 'BS.name' },
                { data: 'status', name: 'transactions.status' },
                { data: 'no_of_items', name: 'no_of_items', "searchable": false},
                { data: 'ref_no', name: 'ref_no' },
                { data: 'additional_notes', name: 'additional_notes' }
            ]
        });

        $(document).on(
            'change',
            '#po_list_filter_location_id, #po_list_filter_supplier_id, #po_list_filter_status, #shipping_status',
            function() {
                purchase_order_table.ajax.reload();
            }
        );

        $('#po_list_filter_date_range').daterangepicker(
        dateRangeSettings,
            function (start, end) {
                $('#po_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
               purchase_order_table.ajax.reload();
            }
        );
        $('#po_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
            $('#po_list_filter_date_range').val('');
            purchase_order_table.ajax.reload();
        });
    });
</script>
@endsection