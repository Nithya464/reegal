@if(empty($only) || in_array('sell_list_filter_customer_id', $only))
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('sell_list_filter_customer_id',  __('contact.customer') . ':') !!}
        {!! Form::select('sell_list_filter_customer_id', $customers, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
    </div>
</div>
@endif
@if(empty($only) || in_array('sell_list_filter_payment_status', $only))
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('sell_list_filter_payment_status',  __('Status') . ':') !!}
        {!! Form::select('sell_list_filter_payment_status', ['delivered' => __('Delivered'), 'close' => __('Close'), 'cancelled' => __('Cancelled')], null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
    </div>
</div>
@endif
@if(empty($only) || in_array('sell_list_filter_date_range', $only))
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('sell_list_filter_date_range', __('report.date_range') . ':') !!}
        {!! Form::text('sell_list_filter_date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'readonly']); !!}
    </div>
</div>
@endif
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('shipping_status', __('Shipping Type') . ':') !!}
        {!! Form::select('shipping_status', config('global.shipping_type'), null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
    </div>
</div>