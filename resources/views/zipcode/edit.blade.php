<div class="modal-dialog" role="document">
    <div class="modal-content">
      {!! Form::open(['url' => action([\App\Http\Controllers\ZipcodeController::class, 'update'], [$zipcode->id]), 'method' => 'PUT', 'id' => 'zipcode_edit_form' ]) !!}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">@lang( 'Edit Zipcode' )</h4>
      </div>
  
      <div class="modal-body">
        <div class="row">
            <div class="form-group col-sm-12">
                {!! Form::label('state_id', __( 'State' ) . ':*') !!}
                {!! Form::select('state_id', $states, $zipcode->state_id, ['class' => 'form-control','required','placeholder' => __( 'Select State' )]); !!}
            </div>

            <div class="form-group col-sm-12">
                {!! Form::label('city_id', __( 'City' ) . ':*') !!}
                {!! Form::select('city_id',$cities, $zipcode->city_id, ['class' => 'form-control','required','placeholder' => __( 'Select City' )]); !!}
            </div>
  
          <div class="form-group col-sm-12">
            {!! Form::label('zipcode', __( 'Zipcode' ) . ':*') !!}
              {!! Form::text('zipcode', $zipcode->zipcode, ['class' => 'form-control', 'required', 'placeholder' => __( 'Enter Zipcode' )]); !!}
          </div>
  
          <div class="form-group col-sm-12">
            {!! Form::label('status', __( 'Status' ) . ':') !!}
              {!! Form::select('status', ['1' => __('Active'), '2' => __('Inactive')], $zipcode->status, ['class' => 'form-control']); !!}
          </div>
          
        </div>
  
      </div>
  
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
      </div>
  
      {!! Form::close() !!}
  
    </div><!-- /.modal-content -->
  </div>