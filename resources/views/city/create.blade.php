<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action([\App\Http\Controllers\CityController::class, 'store']), 'method' => 'post', 'id' => 'city_add_form' ]) !!}

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'Add City' )</h4>
    </div>

    <div class="modal-body">
      <div class="row">
        <div class="form-group col-sm-12">
              {!! Form::label('state_id', __( 'State' ) . ':*') !!}
              {!! Form::select('state_id', $states, null, ['class' => 'form-control','required','placeholder' => __( 'Select State' )]); !!}
          </div>

        <div class="form-group col-sm-12">
          {!! Form::label('city_name', __( 'City' ) . ':*') !!}
            {!! Form::text('city_name', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'Enter City Name' )]); !!}
        </div>

        <div class="form-group col-sm-12">
          {!! Form::label('status', __( 'Status' ) . ':') !!}
            {!! Form::select('status', ['1' => __('Active'), '2' => __('Inactive')], null, ['class' => 'form-control']); !!}
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