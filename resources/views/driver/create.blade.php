<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action([\App\Http\Controllers\DriverController::class, 'store']), 'method' => 'post', 'id' => 'driver_add_form' ]) !!}

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'Add Driver' )</h4>
    </div>

    <div class="modal-body">
      <div class="form-group">
        {!! Form::label('first_name', __( 'First Name' ) . ':*') !!}
          {!! Form::text('first_name', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'First Name' ) ]); !!}
      </div>

      <div class="form-group">
        {!! Form::label('last_name', __( 'Last Name' ) . ':*') !!}
          {!! Form::text('last_name', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'Last Name' ) ]); !!}
      </div>
      
      <div class="form-group">
        {!! Form::label('email', __( 'email' ) . ':*') !!}
          {!! Form::text('email', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'Email' ) ]); !!}
      </div>
      
      <div class="form-group">
        {!! Form::label('phone_number', __('Phone Number') . ':*') !!}  
        {!! Form::number('phone_number', null, ['class' => 'form-control', 'required', 'placeholder' => __('Phone Number')]); !!}
      </div>

      <div class="form-group">
        {!! Form::label('state_id', __( 'State' ) . ':*') !!}
        {!! Form::select('state_id', $states, null, ['class' => 'form-control','required','placeholder' => __( 'Select State' )]); !!}
      </div>

      <div class="form-group">
        {!! Form::label('city_id', __( 'City' ) . ':*') !!}
        {!! Form::select('city_id', $cities, null, ['class' => 'form-control','required','placeholder' => __( 'Select City' )]); !!}
      </div>

      <div class="form-group">
        {!! Form::label('status', __( 'Status' ) . ':') !!}
          {!! Form::select('status', config('global.status_dropdown'),null, ['class' => 'form-control']); !!}
      </div>
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

    {!! Form::close() !!}

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->