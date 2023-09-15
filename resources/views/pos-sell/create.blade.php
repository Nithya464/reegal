@extends('layouts.app')

@php
	if (!empty($status) && $status == 'quotation') {
		$title = __('lang_v1.add_quotation');
	} else if (!empty($status) && $status == 'draft') {
		$title = __('lang_v1.add_draft');
	} else {
		$title = __('New Order');
	}

	if($sale_type == 'sales_order') {
		$title = __('lang_v1.sales_order');
	}
@endphp

@section('title', $title)

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{$title}}</h1>
</section>
<!-- Main content -->
<section class="content no-print">

@php
	$custom_labels = json_decode(session('business.custom_labels'), true);
	$common_settings = session()->get('business.common_settings');
@endphp
<input type="hidden" id="item_addition_method" value="{{$business_details->item_addition_method}}">
	{!! Form::open(['url' => action([\App\Http\Controllers\PosSellController::class, 'store']), 'method' => 'post', 'id' => 'add_sell_form', 'files' => true ]) !!}
	 @if(!empty($sale_type))
	 	<input type="hidden" id="sale_type" name="type" value="{{$sale_type}}">
	 @endif
	<div class="row">
		<div class="col-md-12 col-sm-12">
			@component('components.widget', ['class' => 'box-solid'])
				{!! Form::hidden('location_id', !empty($default_location) ? $default_location->id : null , ['id' => 'location_id', 'data-receipt_printer_type' => !empty($default_location->receipt_printer_type) ? $default_location->receipt_printer_type : 'browser', 'data-default_payment_accounts' => !empty($default_location) ? $default_location->default_payment_accounts : '']); !!}

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('ref_no', __('Order No') . ':' ) !!}
                        {!! Form::text('ref_no', null, ['class' => 'form-control','readonly']); !!}
                    </div>
                </div>
                <div class="@if(!empty($commission_agent)) col-sm-3 @else col-sm-4 @endif">
					<div class="form-group">
						{!! Form::label('transaction_date', __('Order Date') . ':*') !!}
						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</span>
							{!! Form::text('transaction_date', $default_datetime, ['class' => 'form-control', 'readonly', 'required']); !!}
						</div>
					</div>
				</div>

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('shipping_type', __('Shipping Type') . ':*' ) !!}
                        {!! Form::select('shipping_type', config('global.shipping_type'), null , ['class' => 'form-control','placeholder' => __('messages.please_select'), 'required', 'data-default' => 'percentage', 'required']); !!}
                    </div>
                </div>
                
				<div class="clearfix"></div>
				<div class="@if(!empty($commission_agent)) col-sm-3 @else col-sm-4 @endif">
					<div class="form-group">
						{!! Form::label('contact_id', __('contact.customer') . ':*') !!}
						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-user"></i>
							</span>
							{{-- <input type="hidden" id="default_customer_id" 
							value="{{ $walk_in_customer['id']}}" > --}}
                            <input type="hidden" id="default_customer_id" 
							value="" >
							{{-- <input type="hidden" id="default_customer_name" 
							value="{{ $walk_in_customer['name']}}" > --}}
                            <input type="hidden" id="default_customer_name" 
							value="" >
							{{-- <input type="hidden" id="default_customer_balance" value="{{ $walk_in_customer['balance'] ?? ''}}" >
							<input type="hidden" id="default_customer_address" value="{{ $walk_in_customer['shipping_address'] ?? ''}}" > --}}
                            <input type="hidden" id="default_customer_balance" value="" >
							<input type="hidden" id="default_customer_address" value="" >
							@if(!empty($walk_in_customer['price_calculation_type']) && $walk_in_customer['price_calculation_type'] == 'selling_price_group')
								<input type="hidden" id="default_selling_price_group" 
							value="{{ $walk_in_customer['selling_price_group_id'] ?? ''}}" >
							@endif
							{!! Form::select('contact_id', 
								[], null, ['class' => 'form-control mousetrap', 'id' => 'customer_id', 'placeholder' => 'Enter Customer name / phone', 'required']); !!}
							<span class="input-group-btn">
								<button type="button" class="btn btn-default bg-white btn-flat add_new_customer" data-name=""><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
							</span>
						</div>
						<small class="text-danger hide contact_due_text"><strong>@lang('account.customer_due'):</strong> <span></span></small>
					</div>
					<small>
					<strong>
						@lang('lang_v1.billing_address'):
					</strong>
					<div id="billing_address_div">
						{{-- {!! $walk_in_customer['contact_address'] ?? '' !!} --}}
					</div>
					<br>
					<strong>
						@lang('lang_v1.shipping_address'):
					</strong>
					<div id="shipping_address_div">
						{{-- {{$walk_in_customer['supplier_business_name'] ?? ''}},<br>
						{{$walk_in_customer['name'] ?? ''}},<br>
						{{$walk_in_customer['shipping_address'] ?? ''}} --}}
					</div>					
					</small>
				</div>
                <div class="@if(!empty($commission_agent)) col-sm-3 @else col-sm-4 @endif">
					<div class="form-group">
						{!! Form::label('sales_person_name', __('Sales Person') . ':*') !!}
						{!! Form::text('sales_person_name', null, ['class' => 'form-control', 'readonly', 'required']); !!}
						{!! Form::hidden('sales_person_id', null, ['class' => 'form-control', 'id' => 'sales_person_id', 'required']); !!}
					</div>
				</div>
				
				<div class="clearfix"></div>

		        @if((!empty($pos_settings['enable_sales_order']) && $sale_type != 'sales_order') || $is_order_request_enabled)
					<div class="col-sm-3">
						<div class="form-group">
							{!! Form::label('sales_order_ids', __('lang_v1.sales_order').':') !!}
							{!! Form::select('sales_order_ids[]', [], null, ['class' => 'form-control select2', 'multiple', 'id' => 'sales_order_ids']); !!}
						</div>
					</div>
					<div class="clearfix"></div>
				@endif
				<!-- Call restaurant module if defined -->
		        @if(in_array('tables' ,$enabled_modules) || in_array('service_staff' ,$enabled_modules))
		        	<span id="restaurant_module_span">
		        	</span>
		        @endif
			@endcomponent

			@component('components.widget', ['class' => 'box-solid'])
				<div class="col-sm-10 col-sm-offset-1">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-btn">
								<button type="button" class="btn btn-default bg-white btn-flat" data-toggle="modal" data-target="#configure_search_modal" title="{{__('lang_v1.configure_product_search')}}"><i class="fas fa-search-plus"></i></button>
							</div>
							{!! Form::text('search_product', null, ['class' => 'form-control mousetrap', 'id' => 'search_product', 'placeholder' => __('lang_v1.search_product_placeholder'),
							'disabled' => is_null($default_location)? true : false,
							'autofocus' => is_null($default_location)? false : true,
							]); !!}
							{{-- <span class="input-group-btn">
								<button type="button" class="btn btn-default bg-white btn-flat pos_add_quick_product" data-href="{{action([\App\Http\Controllers\ProductController::class, 'quickAdd'])}}" data-container=".quick_add_product_modal"><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
							</span> --}}
						</div>
					</div>
				</div>

				<div class="row col-sm-12 pos_product_div" style="min-height: 0">

					<input type="hidden" name="sell_price_tax" id="sell_price_tax" value="{{$business_details->sell_price_tax}}">

					<!-- Keeps count of product rows -->
					<input type="hidden" id="product_row_count" 
						value="0">
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
								<th class="text-center">	
									@lang('Product Id')
								</th>
                                <th class="text-center">	
									@lang('sale.product')
								</th>
                                <th class="text-center">Unit</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Total Pieces</th>
                                <th class="text-center">Unit Price</th>
                                <th class="text-center">SRP</th>
                                <th class="text-center">GP (%)</th>
                                <th class="text-center">Item Total</th>
								<th class="text-center">Discount</th>
                                {{-- <th class="text-center">Discount Amount</th> --}}
                                <th class="text-center">Net Price</th>
								{{-- <th class="text-center">
									@lang('sale.qty')
								</th>
								@if(!empty($pos_settings['inline_service_staff']))
									<th class="text-center">
										@lang('restaurant.service_staff')
									</th>
								@endif
								<th class="@if(!auth()->user()->can('edit_product_price_from_sale_screen')) hide @endif">
									@lang('sale.unit_price')
								</th>
								<th class="@if(!auth()->user()->can('edit_product_discount_from_sale_screen')) hide @endif">
									@lang('receipt.discount')
								</th>
								<th class="text-center {{$hide_tax}}">
									@lang('sale.tax')
								</th>
								<th class="text-center {{$hide_tax}}">
									@lang('sale.price_inc_tax')
								</th>
								@if(!empty($common_settings['enable_product_warranty']))
									<th>@lang('lang_v1.warranty')</th>
								@endif 
								<th class="text-center">
									@lang('sale.subtotal')
								</th>--}}
								<th class="text-center"><i class="fas fa-times" aria-hidden="true"></i></th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
					</div>
					<div class="table-responsive">
					<table class="table table-condensed table-bordered table-striped">
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
						{!! Form::label('sell_note',__('Remark')) !!}
						{!! Form::textarea('sale_note', null, ['class' => 'form-control', 'rows' => 3]); !!}
					</div>
			    </div>
				<input type="hidden" name="is_direct_sale" value="1">
			@endcomponent
			@component('components.widget', ['class' => 'box-solid'])
		
	        <div class="clearfix"></div>
			<div class="col-md-7 col-md-offset-5" id="additional_expenses_div" style="display: none;">
				<table class="table table-condensed">
					<thead>
						<tr>
							<th>@lang('lang_v1.additional_expense_name')</th>
							<th>@lang('sale.amount')</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								{!! Form::text('additional_expense_key_1', null, ['class' => 'form-control', 'id' => 'additional_expense_key_1']); !!}
							</td>
							<td>
								{!! Form::text('additional_expense_value_1', 0, ['class' => 'form-control input_number', 'id' => 'additional_expense_value_1']); !!}
							</td>
						</tr>
						<tr>
							<td>
								{!! Form::text('additional_expense_key_2', null, ['class' => 'form-control', 'id' => 'additional_expense_key_2']); !!}
							</td>
							<td>
								{!! Form::text('additional_expense_value_2', 0, ['class' => 'form-control input_number', 'id' => 'additional_expense_value_2']); !!}
							</td>
						</tr>
						<tr>
							<td>
								{!! Form::text('additional_expense_key_3', null, ['class' => 'form-control', 'id' => 'additional_expense_key_3']); !!}
							</td>
							<td>
								{!! Form::text('additional_expense_value_3', 0, ['class' => 'form-control input_number', 'id' => 'additional_expense_value_3']); !!}
							</td>
						</tr>
						<tr>
							<td>
								{!! Form::text('additional_expense_key_4', null, ['class' => 'form-control', 'id' => 'additional_expense_key_4']); !!}
							</td>
							<td>
								{!! Form::text('additional_expense_value_4', 0, ['class' => 'form-control input_number', 'id' => 'additional_expense_value_4']); !!}
							</td>
						</tr>
					</tbody>
				</table>
			</div>
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
								{!! Form::select("main_overall_discount_type", ['fixed' => __('lang_v1.fixed'), 'percentage' => __('lang_v1.percentage')], 'fixed' , ['class' => 'form-control main_overall_discount_type']); !!}
								</div>
								<div class="col-sm-6">
								{!! Form::text('main_overall_discount',@num_format(0), ['class' => 'form-control main_overall_discount']); !!}
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
								{!! Form::text('main_shipping_charges',@num_format(0), ['class' => 'form-control']); !!}
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
	@if(!empty($common_settings['is_enabled_export']) && $sale_type != 'sales_order')
		@component('components.widget', ['class' => 'box-solid', 'title' => __('lang_v1.export')])
			<div class="col-md-12 mb-12">
                <div class="form-check">
                    <input type="checkbox" name="is_export" class="form-check-input" id="is_export" @if(!empty($walk_in_customer['is_export'])) checked @endif>
                    <label class="form-check-label" for="is_export">@lang('lang_v1.is_export')</label>
                </div>
            </div>
	        @php
	            $i = 1;
	        @endphp
	        @for($i; $i <= 6 ; $i++)
	            <div class="col-md-4 export_div" @if(empty($walk_in_customer['is_export'])) style="display: none;" @endif>
	                <div class="form-group">
	                    {!! Form::label('export_custom_field_'.$i, __('lang_v1.export_custom_field'.$i).':') !!}
	                    {!! Form::text('export_custom_fields_info['.'export_custom_field_'.$i.']', !empty($walk_in_customer['export_custom_field_'.$i]) ? $walk_in_customer['export_custom_field_'.$i] : null, ['class' => 'form-control','placeholder' => __('lang_v1.export_custom_field'.$i), 'id' => 'export_custom_field_'.$i]); !!}
	                </div>
	            </div>
	        @endfor
		@endcomponent
	@endif
	@php
		$is_enabled_download_pdf = config('constants.enable_download_pdf');
		$payment_body_id = 'payment_rows_div';
		if ($is_enabled_download_pdf) {
			$payment_body_id = '';
		}
	@endphp
	@if((empty($status) || (!in_array($status, ['quotation', 'draft'])) || $is_enabled_download_pdf) && $sale_type != 'sales_order')
		@can('sell.payments')
			@component('components.widget', ['class' => 'box-solid', 'id' => $payment_body_id, 'title' => __('purchase.add_payment')])
			@if($is_enabled_download_pdf)
				<div class="well row">
					<div class="col-md-6">
						<div class="form-group">
							{!! Form::label("prefer_payment_method" , __('lang_v1.prefer_payment_method') . ':') !!}
							@show_tooltip(__('lang_v1.this_will_be_shown_in_pdf'))
							<div class="input-group">
								<span class="input-group-addon">
									<i class="fas fa-money-bill-alt"></i>
								</span>
								{!! Form::select("prefer_payment_method", $payment_types, 'cash', ['class' => 'form-control','style' => 'width:100%;']); !!}
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							{!! Form::label("prefer_payment_account" , __('lang_v1.prefer_payment_account') . ':') !!}
							@show_tooltip(__('lang_v1.this_will_be_shown_in_pdf'))
							<div class="input-group">
								<span class="input-group-addon">
									<i class="fas fa-money-bill-alt"></i>
								</span>
								{!! Form::select("prefer_payment_account", $accounts, null, ['class' => 'form-control','style' => 'width:100%;']); !!}
							</div>
						</div>
					</div>
				</div>
			@endif
			@if(empty($status) || !in_array($status, ['quotation', 'draft']))
				<div class="payment_row" @if($is_enabled_download_pdf) id="payment_rows_div" @endif>
					<div class="row">
						<div class="col-md-12 mb-12">
							<strong>@lang('lang_v1.advance_balance'):</strong> <span id="advance_balance_text"></span>
							{!! Form::hidden('advance_balance', null, ['id' => 'advance_balance', 'data-error-msg' => __('lang_v1.required_advance_balance_not_available')]); !!}
						</div>
					</div>
					@include('sale_pos.partials.payment_row_form', ['row_index' => 0, 'show_date' => true, 'show_denomination' => true])
                </div>
                <div class="payment_row">
					<div class="row">
						<div class="col-md-12">
			        		<hr>
			        		<strong>
			        			@lang('lang_v1.change_return'):
			        		</strong>
			        		<br/>
			        		<span class="lead text-bold change_return_span">0</span>
			        		{!! Form::hidden("change_return", $change_return['amount'], ['class' => 'form-control change_return input_number', 'required', 'id' => "change_return"]); !!}
			        		<!-- <span class="lead text-bold total_quantity">0</span> -->
			        		@if(!empty($change_return['id']))
			            		<input type="hidden" name="change_return_id" 
			            		value="{{$change_return['id']}}">
			            	@endif
						</div>
					</div>
					<div class="row hide payment_row" id="change_return_payment_data">
						<div class="col-md-4">
							<div class="form-group">
								{!! Form::label("change_return_method" , __('lang_v1.change_return_payment_method') . ':*') !!}
								<div class="input-group">
									<span class="input-group-addon">
										<i class="fas fa-money-bill-alt"></i>
									</span>
									@php
										$_payment_method = empty($change_return['method']) && array_key_exists('cash', $payment_types) ? 'cash' : $change_return['method'];

										$_payment_types = $payment_types;
										if(isset($_payment_types['advance'])) {
											unset($_payment_types['advance']);
										}
									@endphp
									{!! Form::select("payment[change_return][method]", $_payment_types, $_payment_method, ['class' => 'form-control col-md-12 payment_types_dropdown', 'id' => 'change_return_method', 'style' => 'width:100%;']); !!}
								</div>
							</div>
						</div>
						@if(!empty($accounts))
						<div class="col-md-4">
							<div class="form-group">
								{!! Form::label("change_return_account" , __('lang_v1.change_return_payment_account') . ':') !!}
								<div class="input-group">
									<span class="input-group-addon">
										<i class="fas fa-money-bill-alt"></i>
									</span>
									{!! Form::select("payment[change_return][account_id]", $accounts, !empty($change_return['account_id']) ? $change_return['account_id'] : '' , ['class' => 'form-control select2', 'id' => 'change_return_account', 'style' => 'width:100%;']); !!}
								</div>
							</div>
						</div>
						@endif
						@include('sale_pos.partials.payment_type_details', ['payment_line' => $change_return, 'row_index' => 'change_return'])
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-12">
							<div class="pull-right"><strong>@lang('lang_v1.balance'):</strong> <span class="balance_due">0.00</span></div>
						</div>
					</div>
				</div>
			@endif
			@endcomponent
		@endcan
	@endif
	
	<div class="row">
		{!! Form::hidden('is_save_and_print', 0, ['id' => 'is_save_and_print']); !!}
		<div class="col-sm-12 text-center">
			<button type="button" id="submit-sell" class="btn btn-primary btn-big">@lang('messages.save')</button>
			{{-- <button type="button" id="save-and-print" class="btn btn-success btn-big">@lang('lang_v1.save_and_print')</button> --}}
		</div>
	</div>
	
	@if(empty($pos_settings['disable_recurring_invoice']))
		@include('sale_pos.partials.recurring_invoice_modal')
	@endif
	
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
    	$(document).ready( function() {
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

            $('#shipping_documents').fileinput({
		        showUpload: false,
		        showPreview: false,
		        browseLabel: LANG.file_browse_label,
		        removeLabel: LANG.remove,
		    });

		    $(document).on('change', '#prefer_payment_method', function(e) {
			    var default_accounts = $('select#select_location_id').length ? 
			                $('select#select_location_id')
			                .find(':selected')
			                .data('default_payment_accounts') : $('#location_id').data('default_payment_accounts');
			    var payment_type = $(this).val();
			    if (payment_type) {
			        var default_account = default_accounts && default_accounts[payment_type]['account'] ? 
			            default_accounts[payment_type]['account'] : '';
			        var account_dropdown = $('select#prefer_payment_account');
			        if (account_dropdown.length && default_accounts) {
			            account_dropdown.val(default_account);
			            account_dropdown.change();
			        }
			    }
			});

		    function setPreferredPaymentMethodDropdown() {
			    var payment_settings = $('#location_id').data('default_payment_accounts');
			    payment_settings = payment_settings ? payment_settings : [];
			    enabled_payment_types = [];
			    for (var key in payment_settings) {
			        if (payment_settings[key] && payment_settings[key]['is_enabled']) {
			            enabled_payment_types.push(key);
			        }
			    }
			    if (enabled_payment_types.length) {
			        $("#prefer_payment_method > option").each(function() {
		                if (enabled_payment_types.indexOf($(this).val()) != -1) {
		                    $(this).removeClass('hide');
		                } else {
		                    $(this).addClass('hide');
		                }
			        });
			    }
			}
			
			setPreferredPaymentMethodDropdown();

			$('#is_export').on('change', function () {
	            if ($(this).is(':checked')) {
	                $('div.export_div').show();
	            } else {
	                $('div.export_div').hide();
	            }
	        });

			if($('.payment_types_dropdown').length){
				$('.payment_types_dropdown').change();
			}

    	});
    </script>
@endsection
