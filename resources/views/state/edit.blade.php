<div class="modal-dialog" role="document">
  <div class="modal-content">

    {{-- {!! Form::open(['url' => action([\App\Http\Controllers\StateController::class, 'store']), 'method' => 'post', 'id' => 'state_add_form' ]) !!} --}}
    {!! Form::open(['url' => action([\App\Http\Controllers\StateController::class, 'update'], [$state->id]), 'method' => 'PUT', 'id' => 'state_edit_form' ]) !!}

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'Edit State' )</h4>
    </div>

    <div class="modal-body">
      <div class="row">
        <div class="form-group col-sm-12">
          {!! Form::label('state_name', __( 'State Name' ) . ':*') !!}
            {!! Form::text('state_name', $state->state_name, ['class' => 'form-control', 'required', 'placeholder' => __( 'Enter State Name' )]); !!}
        </div>

        <div class="form-group col-sm-12">
          {!! Form::label('abbreviation', __( 'Abbreviation' ) . ':*') !!}
            {!! Form::text('abbreviation', $state->abbreviation, ['class' => 'form-control', 'placeholder' => __( 'Enter Abbreviation' ), 'required']); !!}
        </div>

        <div class="form-group col-sm-12">
          {!! Form::label('status', __( 'Status' ) . ':') !!}
            {!! Form::select('status', ['1' => __('Active'), '2' => __('Inactive')], $state->status, ['class' => 'form-control']); !!}
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