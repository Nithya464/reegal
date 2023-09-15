<div class="modal-dialog" role="document">
    <div class="modal-content">
  
      {!! Form::open(['url' => action([\App\Http\Controllers\PriceLevelController::class, 'store']), 'method' => 'post', 'id' => $quick_add ? 'quick_add_price_level_form' : 'price_level_add_form' ]) !!}
  
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'Add Price Level' )</h4>
      </div>
  
      <div class="modal-body">
        <div class="form-group">
          {!! Form::label('price_level_id', __( 'Price Level ID' ) . ':') !!}
            {!! Form::text('price_level_id', null, ['class' => 'form-control','placeholder' => __( 'Price Level ID' ), 'readonly']); !!}
        </div>

        <div class="form-group">
            {!! Form::label('customer_type', __( 'Customer Type' ) . ':*') !!}
            {!! Form::select('customer_type', array_merge(['' => 'Please Select Customer Type'], config('global.customer_type')),null, ['class' => 'form-control', 'required', 'Placeholder' => 'Select Customer Type']); !!}
        </div>

        <div class="form-group">
            {!! Form::label('name', __( 'Price Level Name' ) . ':*') !!}
              {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'Price Level Name' ) ]); !!}
        </div>

        <div class="form-group">
          {!! Form::label('status', __( 'Status' ) . ':') !!}
            {!! Form::select('status', config('global.status_options'),1, ['class' => 'form-control']); !!}
        </div>

      </div>
  
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
      </div>
  
      {!! Form::close() !!}
  
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->