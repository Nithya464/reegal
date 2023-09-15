<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action([\App\Http\Controllers\TaxController::class, 'update'], [$tax->id]), 'method' => 'PUT', 'id' => 'tax_edit_form' ]) !!}

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'tax.add_tax' )</h4>
    </div>

    <div class="modal-body">
      <div class="form-group">
        {!! Form::label('name', __( 'tax.tax_name' ) . ':*') !!}
          {!! Form::text('name', $tax->name, ['class' => 'form-control', 'required', 'placeholder' => __( 'tax.tax_name' ) ]); !!}
      </div>

      <div class="form-group">
        {!! Form::label('state_id', __( 'State' ) . ':*') !!}
        {!! Form::select('state_id', $states, $tax->state_id, ['class' => 'form-control','required','placeholder' => __( 'Select State' )]); !!}
      </div>

      <div class="form-group">
        {!! Form::label('tax', __( 'tax.tax' ) . '%:*') !!}
          {!! Form::text('tax', $tax->tax, ['class' => 'form-control input_number', 'required']); !!}
      </div>

      <div class="form-group">
        {!! Form::label('print_label_text', __( 'tax.print_label_text' ) . ':*') !!}
          {!! Form::text('label_text', $tax->label_text, ['class' => 'form-control', 'required', 'placeholder' => __( 'tax.print_label_text' ) ]); !!}
      </div>

      <div class="form-group">
        {!! Form::label('status', __( 'tax.status' ) . ':') !!}
          {!! Form::select('status', config('global.status_dropdown'),$tax->status, ['class' => 'form-control']); !!}
      </div>

        

    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang( 'messages.save' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

    {!! Form::close() !!}

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->