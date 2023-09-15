@extends('layouts.app')

@php
	$title = 'Update Order';
	$disabled = null;
	if(isset($isDisabled) && $isDisabled){
		$disabled = 'disabled';
	}
@endphp
@section('title', $title)

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{$title}} <small>Order Id: <span class="text-success">#{{$transaction->ref_no}}</span></small></h1>
</section>
<!-- Main content -->
<section class="content">
<input type="hidden" id="amount_rounding_method" value="{{$pos_settings['amount_rounding_method'] ?? ''}}">
<input type="hidden" id="amount_rounding_method" value="{{$pos_settings['amount_rounding_method'] ?? 'none'}}">
@if(!empty($pos_settings['allow_overselling']))
	<input type="hidden" id="is_overselling_allowed">
@endif
@if(session('business.enable_rp') == 1)
    <input type="hidden" id="reward_point_enabled">
@endif
@php
	$custom_labels = json_decode(session('business.custom_labels'), true);
	$common_settings = session()->get('business.common_settings');
@endphp
<input type="hidden" id="item_addition_method" value="{{$business_details->item_addition_method}}">
	{!! Form::open(['url' => action([\App\Http\Controllers\PosSellController::class, 'update'], [$transaction->id ]), 'method' => 'put', 'id' => 'edit_sell_form', 'files' => true ]) !!}

	{!! Form::hidden('location_id', $transaction->location_id, ['id' => 'location_id', 'data-receipt_printer_type' => !empty($location_printer_type) ? $location_printer_type : 'browser', 'data-default_payment_accounts' => $transaction->location->default_payment_accounts]); !!}

	@if($transaction->type == 'sales_order')
	 	<input type="hidden" id="sale_type" value="{{$transaction->type}}">
	@endif
	<div class="row">
		<div class="col-md-12 col-sm-12">
			@component('components.widget', ['class' => 'box-solid'])
				<div class="clearfix"></div>
                <div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('invoice_no', __('Order No :')) !!}
						{!! Form::text('invoice_no', $transaction->ref_no, ['class' => 'form-control','readonly']); !!}
					</div>
				</div>
                <div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('invoice_no', __('Order Date :')) !!}
						{!! Form::text('invoice_no', $transaction->transaction_date, ['class' => 'form-control','readonly']); !!}
					</div>
				</div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('shipping_type', __('Shipping Type') . ':*' ) !!}
                        {!! Form::select('shipping_type', config('global.shipping_type'), $transaction->sub_type , ['class' => 'form-control','placeholder' => __('messages.please_select'), 'required', 'data-default' => 'percentage', 'required',$disabled]); !!}
                    </div>
                </div>
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('contact_id', __('contact.customer') . ':*') !!}
						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-user"></i>
							</span>
							<input type="hidden" id="default_customer_id" 
							value="{{ $transaction->contact->id }}" >
							<input type="hidden" id="default_customer_name" 
							value="{{ $transaction->contact->name }}" >
							{!! Form::select('contact_id', 
								[], null, ['class' => 'form-control mousetrap', 'id' => 'customer_id', 'placeholder' => 'Enter Customer name / phone', 'required',$disabled]); !!}
							<span class="input-group-btn">
								<button type="button" class="btn btn-default bg-white btn-flat add_new_customer" data-name="" {{ $disabled }}><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
							</span>
						</div>
						<small class="text-danger @if(empty($customer_due)) hide @endif contact_due_text"><strong>@lang('account.customer_due'):</strong> <span>{{$customer_due ?? ''}}</span></small>
					</div>
					<small>
						<strong>
							@lang('lang_v1.billing_address'):
						</strong>
						<div id="billing_address_div">
							{!! $transaction->contact->contact_address ?? '' !!}
						</div>
						<br>
						<strong>
							@lang('lang_v1.shipping_address'):
						</strong>
						<div id="shipping_address_div">
							{!! $transaction->contact->supplier_business_name ?? '' !!}, <br>
							{!! $transaction->contact->name ?? '' !!}, <br>
							{!!$transaction->contact->shipping_address ?? '' !!}
						</div>						
					</small>
				</div>
                <div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('sales_person_name', __('Sales Person') . ':*') !!}
						{!! Form::text('sales_person_name', $transaction->contact->sales_person->first_name.' '.$transaction->contact->sales_person->last_name, ['class' => 'form-control', 'readonly', 'required']); !!}
						{!! Form::hidden('sales_person_id', $transaction->contact->sales_person->id , ['class' => 'form-control', 'id' => 'sales_person_id', 'required']); !!}
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('status', __('Order Status') . ':*') !!}
						{!! Form::text('status', $statuses[$transaction->status]['label'] ?? __($transaction->status), ['class' => 'form-control', 'readonly', 'required']); !!}
					</div>
				</div>
		    @endcomponent
			
			@component('components.widget', ['class' => 'box-solid'])
				<div class="col-sm-10 col-sm-offset-1">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-btn">
								<button type="button" class="btn btn-default bg-white btn-flat" data-toggle="modal" data-target="#configure_search_modal" title="{{__('lang_v1.configure_product_search')}}"><i class="fas fa-search-plus"></i></button>
							</div>
							{!! Form::text('search_product', null, ['class' => 'form-control mousetrap', 'id' => 'search_product', 'placeholder' => __('lang_v1.search_product_placeholder'),
							'autofocus' => true,$disabled
							]); !!}
						</div>
					</div>
				</div>

				<div class="row col-sm-12 pos_product_div" style="min-height: 0">

					<input type="hidden" name="sell_price_tax" id="sell_price_tax" value="{{$business_details->sell_price_tax}}">

					<!-- Keeps count of product rows -->
					<input type="hidden" id="product_row_count" 
						value="{{count($sell_details)}}">
					@php
						$hide_tax = '';
						if( session()->get('business.enable_inline_tax') == 0){
							$hide_tax = 'hide';
						}
					@endphp
					<div class="table-responsive">
					<table class="table table-condensed table-bordered table-striped table-responsive" id="pos_table">
						<thead>
							<tr>
                                <th>Product Id</th>
								<th class="text-center">	
									@lang('sale.product')
								</th>
                                <th>Unit</th>
								<th class="text-center">
									@lang('sale.qty')
								</th>
								<th>Total Pieces</th>
                                <th>
									@lang('sale.unit_price')
								</th>
                                <th>SRP</th>
                                <th>GP (%)</th>
								<th>Item Total</th>
                                <th>
									@lang('receipt.discount')
								</th>
								<th class="text-center">
									@lang('sale.subtotal')
								</th>
								@if(!$disabled)
								<th class="text-center"><i class="fas fa-times" aria-hidden="true"></i></th>
								@endif
							</tr>
						</thead>
						<tbody>
							@foreach($sell_details as $sell_line)
							{{-- {{ dd($sell_line) }} --}}
								@include('pos-sell.product_row', ['product' => $sell_line, 'row_count' => $loop->index, 'tax_dropdown' => $taxes, 'sub_units' => !empty($sell_line->unit_details) ? $sell_line->unit_details : [], 'action' => 'edit', 'is_direct_sell' => true, 'so_line' => $sell_line->so_line, 'is_sales_order' => $transaction->type == 'sales_order', 'disabled' => $disabled])
							@endforeach
						</tbody>
					</table>
					</div>
					<div class="table-responsive">
					<table class="table table-condensed table-bordered table-striped table-responsive">
						<tr>
							<td>
								<div class="pull-right">
									<b>@lang('sale.item'):</b> 
									<span class="total_quantity">0</span>
									&nbsp;&nbsp;&nbsp;&nbsp;
									<b>@lang('sale.total'): </b>
									<span class="price_total">0</span>
								</div>
							</td>
						</tr>
					</table>
					</div>
				</div>
			@endcomponent

			@component('components.widget', ['class' => 'box-solid'])
				<div class="col-md-12">
			    	<div class="form-group">
						{!! Form::label('sell_note',__('Order Note') . ':') !!}
						{!! Form::textarea('sale_note', $transaction->additional_notes, ['class' => 'form-control', 'rows' => 3, $disabled]); !!}
					</div>
			    </div>
			    <input type="hidden" name="is_direct_sale" value="1">
			@endcomponent
			@if($disabled)
			@component('components.widget', ['class' => 'box-solid'])
				<div class="col-md-12">
			    	<div class="form-group">
						{!! Form::label('sell_note',__('Payment Received ?') . ':') !!}
						<div class="radio-group">
							<div class="col-md-12">
								<div class="checkbox">
									<label>
										{!! Form::radio('payment_received', 'Yes',(($transaction->payment_received == 'Yes') ? true : false), ['class' => 'input-icheck']) !!} {{ __('Yes') }}
									</label>
									<label>
										{!! Form::radio('payment_received', 'No',(($transaction->payment_received == 'No') ? true : false), ['class' => 'input-icheck']) !!} {{ __('No') }}
									</label>
								</div>
							</div>
						</div>
					</div>
			    </div>
				<div class="col-md-12">
			    	<div class="form-group">
						{!! Form::label('staff_note',__('Account Remark') . ':') !!}
						{!! Form::textarea('staff_note', $transaction->staff_note, ['class' => 'form-control', 'rows' => 3]); !!}
					</div>
			    </div>
			    <input type="hidden" name="is_disabled" value="1">
			@endcomponent
			@endif

			@component('components.widget', ['class' => 'box-solid'])
			<div class="col-md-6 col-md-offset-6">
		    	@if(!empty($pos_settings['amount_rounding_method']) && $pos_settings['amount_rounding_method'] > 0)
		    	<small id="round_off"><br>(@lang('lang_v1.round_off'): <span id="round_off_text">0</span>)</small>
				<br/>
				<input type="hidden" name="round_off_amount" 
					id="round_off_amount" value=0>
				@endif
				<div class="row">
					<div class="col-sm-12">
						<div class="col-sm-6">
							<div class="form-group">
								{!! Form::label('main_sub_total', __('Sub Total') . ':') !!}
								{!! Form::text('main_sub_total',@num_format(0), ['class' => 'form-control','readonly']); !!}
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								{!! Form::label('main_order_total', __('Order Total') . ':') !!}
								{!! Form::text('main_order_total',@num_format(0), ['class' => 'form-control','readonly']); !!}
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<div class="col-sm-12">
								{!! Form::label('main_overall_discount', __('Overall Discount') . ':') !!}
								</div>
								<div class="col-sm-6">
								{!! Form::select("main_overall_discount_type", ['fixed' => __('lang_v1.fixed'), 'percentage' => __('lang_v1.percentage')], $transaction->discount_type , ['class' => 'form-control main_overall_discount_type',$disabled]); !!}
								</div>
								<div class="col-sm-6">
								{!! Form::text('main_overall_discount',@num_format($transaction->discount_amount), ['class' => 'form-control main_overall_discount',$disabled]); !!}
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								{!! Form::label('main_credit_memo_amount', __('Credit Memo Amount') . ':') !!}
								{!! Form::text('main_credit_memo_amount',@num_format(0), ['class' => 'form-control','readonly']); !!}
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								{!! Form::label('main_shipping_charges', __('Shipping Charges') . ':') !!}
								{!! Form::text('main_shipping_charges',@num_format($transaction->shipping_charges), ['class' => 'form-control',$disabled]); !!}
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								{!! Form::label('main_store_credit_amount', __('Store Credit Amount') . ':') !!}
								{!! Form::text('main_store_credit_amount',@num_format(0), ['class' => 'form-control','readonly']); !!}
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								{!! Form::label('main_tax_type', __('Tax Type') . ':') !!}
								{!! Form::text('main_tax_type',@num_format(0), ['class' => 'form-control','readonly']); !!}
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								{!! Form::label('main_payable_amount', __('Payable Amount') . ':') !!}
								{!! Form::text('main_payable_amount',@num_format(0), ['class' => 'form-control','readonly']); !!}
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								{!! Form::label('main_total_tax', __('Total Tax') . ':') !!}
								{!! Form::text('main_total_tax',@num_format(0), ['class' => 'form-control','readonly']); !!}
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								{!! Form::label('main_past_due_amount', __('Past Due Amount') . ':') !!}
								{!! Form::text('main_past_due_amount',@num_format(0), ['class' => 'form-control','readonly']); !!}
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								{!! Form::label('main_ml_quantity', __('ML Quantity') . ':') !!}
								{!! Form::text('main_ml_quantity',@num_format(0), ['class' => 'form-control','readonly']); !!}
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								{!! Form::label('main_open_balance', __('Open Balance') . ':') !!}
								{!! Form::text('main_open_balance',@num_format(0), ['class' => 'form-control','readonly']); !!}
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								{!! Form::label('main_ml_tax', __('ML Tax') . ':') !!}
								{!! Form::text('main_ml_tax',@num_format(0), ['class' => 'form-control','readonly']); !!}
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								{!! Form::label('main_paid_amount', __('Paid Amount') . ':') !!}
								{!! Form::text('main_paid_amount',@num_format(0), ['class' => 'form-control','readonly']); !!}
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								{!! Form::label('main_weight_quantity', __('Weight Quantity') . ':') !!}
								{!! Form::text('main_weight_quantity',@num_format(0), ['class' => 'form-control','readonly']); !!}
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								{!! Form::label('main_balance_amount', __('Balance Amount') . ':') !!}
								{!! Form::text('main_balance_amount',@num_format(0), ['class' => 'form-control','readonly']); !!}
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								{!! Form::label('main_weight_tax', __('Weight Tax') . ':') !!}
								{!! Form::text('main_weight_tax',@num_format(0), ['class' => 'form-control','readonly']); !!}
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								{!! Form::label('main_payment_method', __('Payment Method') . ':') !!}
								{!! Form::text('main_payment_method',@num_format(0), ['class' => 'form-control','readonly']); !!}
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								{!! Form::label('main_adjustment', __('Adjustment') . ':') !!}
								{!! Form::text('main_adjustment',@num_format(0), ['class' => 'form-control','readonly']); !!}
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								{!! Form::label('main_reference_no', __('Reference No') . ':') !!}
								{!! Form::text('main_reference_no',@num_format(0), ['class' => 'form-control','readonly']); !!}
							</div>
						</div>
					</div>
				</div>
		    	{{-- <div><b>@lang('sale.total_payable'): </b>
					<input type="hidden" name="final_total" id="final_total_input">
					<span id="total_payable">0</span>
				</div> --}}
		    </div>
			@endcomponent
				
		</div>
	</div>

	<div class="row">
		<div class="col-md-12 text-center">
	    	{!! Form::hidden('is_save_and_print', 0, ['id' => 'is_save_and_print']); !!}
			@if(isset($transaction->payment_received) && $transaction->payment_received)
			<button type="button" class="btn btn-primary btn-big">@lang('Pay Now')</button>
			@else
			<button type="button" class="btn btn-primary btn-big" id="submit-sell">@lang('messages.update')</button>
			@endif
	    	{{-- <button type="button" id="save-and-print" class="btn btn-success btn-big">@lang('lang_v1.update_and_print')</button> --}}
	    </div>
	</div>
	{!! Form::close() !!}
</section>

<div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
	@include('contact.create', ['quick_add' => true])
</div>
<!-- /.content -->
<div class="modal fade register_details_modal" tabindex="-1" role="dialog" 
	aria-labelledby="gridSystemModalLabel">
</div>
<div class="modal fade close_register_modal" tabindex="-1" role="dialog" 
	aria-labelledby="gridSystemModalLabel">
</div>
<!-- quick product modal -->
<div class="modal fade quick_add_product_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"></div>

@include('sale_pos.partials.configure_search_modal')

@stop

@section('javascript')
	<script src="{{ asset('js/pos-sell.js?v=' . $asset_v) }}"></script>
	<script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
	<script src="{{ asset('js/opening_stock.js?v=' . $asset_v) }}"></script>
	<!-- Call restaurant module if defined -->
    @if(in_array('tables' ,$enabled_modules) || in_array('modifiers' ,$enabled_modules) || in_array('service_staff' ,$enabled_modules))
    	<script src="{{ asset('js/restaurant.js?v=' . $asset_v) }}"></script>
    @endif
    <script type="text/javascript">
    	$(document).ready( function(){
    		$('#shipping_documents').fileinput({
		        showUpload: false,
		        showPreview: false,
		        browseLabel: LANG.file_browse_label,
		        removeLabel: LANG.remove,
		    });

		    $('#is_export').on('change', function () {
	            if ($(this).is(':checked')) {
	                $('div.export_div').show();
	            } else {
	                $('div.export_div').hide();
	            }
	        });

	        $('#status').change(function(){
    			if ($(this).val() == 'final') {
    				$('#payment_rows_div').removeClass('hide');
    			} else {
    				$('#payment_rows_div').addClass('hide');
    			}
    		});
    		$('.paid_on').datetimepicker({
                format: moment_date_format + ' ' + moment_time_format,
                ignoreReadonly: true,
            });
    	});
    </script>
@endsection
