@extends('layouts.app')
@section('title', __( 'lang_v1.all_sales'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1>@lang( 'Receive Payment')
    </h1>
</section>

<!-- Main content -->
<section class="content no-print">
    {{-- @component('components.filters', ['title' => __('report.filters')]) --}}
    <form class="form-group mt-10" action="{{ route('receivePayment_create') }}" method="POST" id="receive_payment" name="receive_payment">
        @csrf
        @include('pos-payment.partials.payment_list_filters')
    </form>
        {{-- @if(!empty($sources))
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('sell_list_filter_source',  __('lang_v1.sources') . ':') !!}

                    {!! Form::select('sell_list_filter_source', $sources, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all') ]); !!}
                </div>
            </div>
        @endif --}}
    {{-- @endcomponent --}}
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'Payment List')])
        @if(auth()->user()->can('direct_sell_pos.view'))
        @php
            $custom_labels = json_decode(session('business.custom_labels'), true);
         @endphp
            <table class="table table-bordered table-striped ajax_view" id="sell_table">
                <thead>
                    <tr>
                        <th>@lang('messages.action')</th>
                        <th>@lang('Payment ID')</th>
                        <th>@lang('Receive Date')</th>
                        <th>@lang('Customer Name')</th>
                        <th>@lang('Received Amount ($)')</th>
                        <th>@lang('Payment Mode')</th>
                        <th>@lang('No. of Order')</th>
                        <th>@lang('Reference ID')</th>
                        <th>@lang('Remark')</th>
                        <th>@lang('Status')</th>

                        {{-- <th>@lang('messages.action')</th>
                        <th>@lang('Order No')</th>
                        <th>@lang('Order Date')</th>
                        <th>@lang('Customer')</th>
                        <th>@lang('Order Amount')</th>
                        <th>@lang('Order Items')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Shipping Type')</th> --}}

                        {{-- <th>@lang('messages.date')</th>
                        <th>@lang('sale.invoice_no')</th>
                        <th>@lang('sale.customer_name')</th>
                        <th>@lang('lang_v1.contact_no')</th>
                        <th>@lang('sale.location')</th>
                        <th>@lang('sale.payment_status')</th>
                        <th>@lang('lang_v1.payment_method')</th>
                        <th>@lang('sale.total_amount')</th>
                        <th>@lang('sale.total_paid')</th>
                        <th>@lang('lang_v1.sell_due')</th>
                        <th>@lang('lang_v1.sell_return_due')</th>
                        <th>@lang('lang_v1.shipping_status')</th>
                        <th>@lang('lang_v1.total_items')</th>
                        <th>@lang('lang_v1.types_of_service')</th>
                        <th>{{ $custom_labels['types_of_service']['custom_field_1'] ?? __('lang_v1.service_custom_field_1' )}}</th>
                        <th>{{ $custom_labels['sell']['custom_field_1'] ?? '' }}</th>
                        <th>{{ $custom_labels['sell']['custom_field_2'] ?? ''}}</th>
                        <th>{{ $custom_labels['sell']['custom_field_3'] ?? ''}}</th>
                        <th>{{ $custom_labels['sell']['custom_field_4'] ?? ''}}</th>
                        <th>@lang('lang_v1.added_by')</th>
                        <th>@lang('sale.sell_note')</th>
                        <th>@lang('sale.staff_note')</th>
                        <th>@lang('sale.shipping_details')</th>
                        <th>@lang('restaurant.table')</th>
                        <th>@lang('restaurant.service_staff')</th> --}}
                    </tr>
                </thead>
                <tbody></tbody>
                {{-- <tfoot>
                    <tr class="bg-gray font-17 footer-total text-center">
                        <td colspan="1"><strong>@lang('sale.total'):</strong></td>
                        <td class="footer_payment_status_count"></td>
                        <td class="payment_method_count"></td>
                        <td class="footer_sale_total"></td>
                        <td class="footer_total_paid"></td>
                        <td class="footer_total_remaining"></td>
                        <td class="footer_total_sell_return_due"></td>
                        <td colspan="2"></td>
                        <td class="service_type_count"></td>
                        <td colspan="7"></td>
                    </tr>
                </tfoot> --}}
            </table>
        @endif
    @endcomponent
</section>
<!-- /.content -->
<div class="modal fade payment_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>

<div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>

  <!-- Modal -->
  <div class="modal fade due_payment" id="" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            
        </div>
      </div>
    </div>
  </div>
<!-- This will be printed -->
<!-- <section class="invoice print_section" id="receipt_section">
</section> -->

@stop

@section('javascript')
<script type="text/javascript">
$(document).ready( function(){
    //Date range as a button
    $('#sell_list_filter_date_range').daterangepicker(
        dateRangeSettings,
        function (start, end) {
            $('#sell_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
            sell_table.ajax.reload();
        }
    );
    $('#sell_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
        $('#sell_list_filter_date_range').val('');
        sell_table.ajax.reload();
    });
    sell_table = $('#sell_table').DataTable({
        processing: true,
        serverSide: true,
        aaSorting: [[1, 'desc']],
        "ajax": {
            "url": "/receive_payment",
            "data": function ( d ) {
                if($('#sell_list_filter_date_range').val()) {
                    var start = $('#sell_list_filter_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                    var end = $('#sell_list_filter_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                    d.start_date = start;
                    d.end_date = end;
                }
                d.is_direct_sale = 1;

                d.location_id = $('#sell_list_filter_location_id').val();
                d.customer_id = $('#sell_list_filter_customer_id').val();
                d.payment_status = $('#sell_list_filter_payment_status').val();
                d.created_by = $('#created_by').val();
                d.sales_cmsn_agnt = $('#sales_cmsn_agnt').val();
                d.service_staffs = $('#service_staffs').val();

                if($('#shipping_status').length) {
                    d.shipping_status = $('#shipping_status').val();
                }

                if($('#sell_list_filter_source').length) {
                    d.source = $('#sell_list_filter_source').val();
                }

                if($('#only_subscriptions').is(':checked')) {
                    d.only_subscriptions = 1;
                }

                d = __datatable_ajax_callback(d);
            }
        },
        scrollY:        "75vh",
        // scrollX:        true,
        scrollCollapse: true,
        columns: [
            { data: 'action', name: 'action', orderable: false, "searchable": false},
            { data: 'payment_id', name: 'payment_id'  },
            { data: 'receive_date', name: 'receive_date'  },
            { data: 'conatct_name', name: 'conatct_name'  },
            { data: 'receive_amount', name: 'receive_amount'  },
            { data: 'payment_mode', name: 'payment_mode', "searchable": false},
            { data: 'no_of_order', name: 'no_of_order'  },
            { data: 'ref_id', name: 'ref_id'},
            { data: 'remark', name: 'remark'},
            { data: 'status', name: 'status'},
            // { data: 'mobile', name: 'contacts.mobile'},
            // { data: 'business_location', name: 'bl.name'},
            // { data: 'payment_status', name: 'payment_status'},
            // { data: 'payment_methods', orderable: false, "searchable": false},
            // { data: 'final_total', name: 'final_total'},
            // { data: 'total_paid', name: 'total_paid', "searchable": false},
            // { data: 'total_remaining', name: 'total_remaining'},
            // { data: 'return_due', orderable: false, "searchable": false},
            // { data: 'shipping_status', name: 'shipping_status'},
            // { data: 'total_items', name: 'total_items', "searchable": false},
            // { data: 'types_of_service_name', name: 'tos.name', @if(empty($is_types_service_enabled)) visible: false @endif},
            // { data: 'service_custom_field_1', name: 'service_custom_field_1', @if(empty($is_types_service_enabled)) visible: false @endif},
            // { data: 'custom_field_1', name: 'transactions.custom_field_1', @if(empty($custom_labels['sell']['custom_field_1'])) visible: false @endif},
            // { data: 'custom_field_2', name: 'transactions.custom_field_2', @if(empty($custom_labels['sell']['custom_field_2'])) visible: false @endif},
            // { data: 'custom_field_3', name: 'transactions.custom_field_3', @if(empty($custom_labels['sell']['custom_field_3'])) visible: false @endif},
            // { data: 'custom_field_4', name: 'transactions.custom_field_4', @if(empty($custom_labels['sell']['custom_field_4'])) visible: false @endif},
            // { data: 'added_by', name: 'u.first_name'},
            // { data: 'additional_notes', name: 'additional_notes'},
            // { data: 'staff_note', name: 'staff_note'},
            // { data: 'shipping_details', name: 'shipping_details'},
            // { data: 'table_name', name: 'tables.name', @if(empty($is_tables_enabled)) visible: false @endif },
            // { data: 'waiter', name: 'ss.first_name', @if(empty($is_service_staff_enabled)) visible: false @endif },
        ],
        "fnDrawCallback": function (oSettings) {
            __currency_convert_recursively($('#sell_table'));
        },
        // "footerCallback": function ( row, data, start, end, display ) {
        //     var footer_sale_total = 0;
        //     var footer_total_paid = 0;
        //     var footer_total_remaining = 0;
        //     var footer_total_sell_return_due = 0;
        //     for (var r in data){
        //         footer_sale_total += $(data[r].final_total).data('orig-value') ? parseFloat($(data[r].final_total).data('orig-value')) : 0;
        //         footer_total_paid += $(data[r].total_paid).data('orig-value') ? parseFloat($(data[r].total_paid).data('orig-value')) : 0;
        //         footer_total_remaining += $(data[r].total_remaining).data('orig-value') ? parseFloat($(data[r].total_remaining).data('orig-value')) : 0;
        //         footer_total_sell_return_due += $(data[r].return_due).find('.sell_return_due').data('orig-value') ? parseFloat($(data[r].return_due).find('.sell_return_due').data('orig-value')) : 0;
        //     }

        //     $('.footer_total_sell_return_due').html(__currency_trans_from_en(footer_total_sell_return_due));
        //     $('.footer_total_remaining').html(__currency_trans_from_en(footer_total_remaining));
        //     $('.footer_total_paid').html(__currency_trans_from_en(footer_total_paid));
        //     $('.footer_sale_total').html(__currency_trans_from_en(footer_sale_total));

        //     $('.footer_payment_status_count').html(__count_status(data, 'payment_status'));
        //     $('.service_type_count').html(__count_status(data, 'types_of_service_name'));
        //     $('.payment_method_count').html(__count_status(data, 'payment_methods'));
        // },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(6)').attr('class', 'clickable_td');
        }
    });

    $(document).on('change', '#sell_list_filter_location_id, #sell_list_filter_customer_id, #sell_list_filter_payment_status, #created_by, #sales_cmsn_agnt, #service_staffs, #shipping_status, #sell_list_filter_source',  function() {
        sell_table.ajax.reload();
    });

    $('#only_subscriptions').on('ifChanged', function(event){
        sell_table.ajax.reload();
    });

});

</script>
<script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
<script>
    $(document).ready( function(){     
        
            $("#receive_payment").validate({
            rules: {
                'customer_id': {
                    required: true,
                },
                'receive_amount': {
                    required: true,
                    min: 0.01,
                },
                'receive_date': {
                    required: true,
                },
                'payment_mode': { 
                    required: true, 
                }
            },
            messages: {
                'customer_id': {
                    required: "Customer name is required."
                },
                'receive_amount': {
                    required: "Receive amount is required.",
                    min: "Receive amount is required.",
                },
                'receive_date': { 
                    required: "Receive date is required."
                },
                'payment_mode': { 
                    required: "Payment mode is required."
                }
            },
        });

        $('#customer_id').on('change', function() {
            $('.due_payment').attr('id', '');
            var id = $(this).val();
            var selectedValue = '1';
            var html="";
            var html1="";
            var due_amount = 0;
            $.ajax({
                data: { id: id },
                url: "{{ route('payment.details') }}",
                type: 'GET',
                success: function(response) {
                    if(response.data.length > 0) {
                        $('.due_payment').attr('id', 'exampleModal111');
                         html = '<table class="table table-bordered receivetable">' +
                        '<thead style="background-color:#24394D; color:#fff;">' +
                        '<tr>' +
                        '<th>Action</th>' +
                        '<th>Order No.</th>' +
                        '<th>Order Date</th>' +
                        '<th>Order Amount</th>' +
                        '<th>Paid Amount</th>' +
                        '<th>Due Amount</th>' +
                        '</tr>' +
                        '</thead>' +
                        '<tbody>'; // Start of tbody

                        $.each(response.data, function(key, val) {
                            due_amount += parseFloat(val.final_total);                           
                            html += '<tr>' +
                                '<td>' +
                                '<div class="form-group">' +
                                '<input type="checkbox" class="form-check-input" name="selected_ids[]" value="' + val.id + '">' +
                                '</div>' +
                                '</td>' +
                                '<td>' + val.ref_no + '</td>' +
                                '<td>' + val.transaction_date + '</td>' +
                                '<td>' + val.final_total + '</td>' +
                                '<td>0.00</td>' +
                                '<td>' + val.final_total + '</td>' +
                                '</tr>';
                        });
                        html += '<tr>' +
                        '<td colspan="5" style="text-align: right">Total</td>' +
                        '<td>' + due_amount + '</td>'+
                        '</tr>';
                        console.log(due_amount);
                        $('#due_amount').val(due_amount.toFixed(2));
                        html += '</tbody></table>';
                        $(".order_data").html(html);

                        // MODEL
                        html1 = '<table class="table table-bordered receivetable">' +
                        '<thead style="background-color:#24394D ; color:#fff;">' +
                        '<tr>' +
                        // '<th>Action</th>' +
                        '<th>Order no.</th>' +
                        '<th>Order date</th>' +
                        '<th>Amt Due ($)</th>';
                        $.each(response.data, function(key, val) {
                        html1 += '</tr>' +
                        '</thead>' +
                        '<tbody>' +
                        '<tr>' +
                        // '<td><div class="form-group"><input type="checkbox" height="30px" width="30px"></div></td>' +
                        '<td>'+ val.ref_no + '</td>' +
                        '<td>' + val.transaction_date + '</td>' +
                        '<td>' + val.final_total + '</td>' +
                        '</tr>';
                        });
                        html1 += '</tbody>' +
                        '</table>';

                    $(".modal-body").html(html1);
                    }  else {
                        $('.receivetable').load(location.href + ' .receivetable');
                    }                

                },
                error: function(error) {
                    // Handle the error response
                    console.error('An error occurred during the AJAX request', error);
                }
            });
        });
    });
</script>
@endsection