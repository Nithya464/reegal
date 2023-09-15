<div class="modal-dialog" role="document">
    <div class="modal-content">
       {!! Form::open(['url' => action([\App\Http\Controllers\PriceLevelController::class, 'update'], [$priceLevel->id]), 'method' => 'PUT', 'id' => 'price_level_edit_form' ]) !!}
  
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'Edit Price Level' )</h4>
      </div>
  
      <div class="modal-body">
        <div class="form-group">
          {!! Form::label('price_level_id', __( 'Price Level Id' ) . ':*') !!}
            {!! Form::text('price_level_id', $priceLevel->price_level_id, ['class' => 'form-control', 'required', 'placeholder' => __( 'Price Level Id' ), 'readonly']); !!}
        </div>

        <div class="form-group">
            {!! Form::label('customer_type', __( 'Customer Type' ) . ':') !!}
              {!! Form::select('customer_type', array_merge(['' => 'Please Select Customer Type'], config('global.customer_type')), $priceLevel->customer_type, ['class' => 'form-control'])!!}
        </div>

        <div class="form-group">
            {!! Form::label('name', __( 'Price Level Name' ) . ':*') !!}
            {!! Form::text('name', $priceLevel->name, ['class' => 'form-control', 'required', 'placeholder' => __( 'Price Level Name' )]); !!}
        </div>
        
        <div class="form-group">
          {!! Form::label('status', __( 'Status' ) . ':') !!}
            {!! Form::select('status', config('global.status_dropdown'), $priceLevel->status, ['class' => 'form-control']); !!}
        </div>
  
      </div>
  
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang( 'messages.update' )</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
      </div>
  
      {!! Form::close() !!}
  
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->