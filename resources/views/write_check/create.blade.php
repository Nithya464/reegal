<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action([\App\Http\Controllers\WriteChequeController::class, 'store']), 'method' => 'post', 'id' => 'write_check_add_form' ]) !!}
    @csrf
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'Add Cheque' )</h4>
    </div>
    <?php 
      $option =[];
    ?>
    <div class="modal-body">
      <div class="row">

        <div class="form-group col-sm-12">
          {!! Form::label('type',  __( 'Type' ) . ':*') !!}
          {!! Form::select('type', config('global.write_check_type_options'),null, ['class' => 'form-control vendor_type','placeholder' => __( 'Select Option' ),'required']); !!}
        </div>
        @error('type')
        <div class="invalid-feedback text-danger">
            {{ $message }}
        </div>
        @enderror
        <div id="option">
          <div class="form-group col-sm-12">
                {!! Form::label('name', __( 'Name' ) . ':*') !!}
                {!! Form::select('name', $option, null, ['class' => 'form-control','placeholder' => __( 'Select Option' )]); !!}
            </div>
          </div>

        <div id="employee">
        <div class="form-group col-sm-12">
              {!! Form::label('employee_id', __( 'Employee Name' ) . ':*') !!}
              {!! Form::select('employee_id', $users, null, ['class' => 'form-control','placeholder' => __( 'Select Employee' )]); !!}
          </div>
        </div>

        {{-- @error('employee_id')
          <div class="invalid-feedback text-danger">
              {{ $message }}
          </div>
      @enderror --}}

        <div id="vendor">
          <div class="form-group col-sm-12">
                {!! Form::label('vendor_id', __( 'Vendor Name' ) . ':*') !!}
                {!! Form::select('vendor_id', $vendors, null, ['class' => 'form-control','placeholder' => __( 'Select Vendor' )]); !!}
            </div>
        </div>

        <div class="form-group col-sm-12">
          {!! Form::label('cheque_number', __( 'Cheque Number' ) . ':*') !!}
            {!! Form::text('cheque_number', null, ['class' => 'form-control','required','placeholder' => __( 'Enter Cheque Number' )]); !!}
        </div>
        {{-- @error('cheque_number')
          <div class="invalid-feedback text-danger">
              {{ $message }}
          </div>
        @enderror --}}

        <div class="form-group col-sm-12">
         
          {!! Form::label('date', __('date') . ':*') !!}
          {{-- <div class="input-group"> --}}
              {{-- <span class="input-group-addon">
                  <i class="fa fa-calendar"></i>
              </span> --}}
              
              {!! Form::date('date', date('Y-m-d'), ['class' => 'form-control', 'required']) !!}
          {{-- </div> --}}
      </div>
      <div class="form-group col-sm-12">
        {!! Form::label('cheque_amount', __( 'Cheque Amount' ) . '$:*') !!}
        {!! Form::text('cheque_amount', null, ['class' => 'form-control input_number','required']); !!}
      </div>

      <div class="form-group col-xs-12 col-sm-4 col-md-12">
        {!! Form::label('address', __( 'Address' ) . ':*') !!}
        {!! Form::textarea('address', null, ['class' => 'form-control', 'rows' => 2, 'cols' => 40,'required']); !!}
      </div>

      <div class="form-group col-xs-12 col-sm-4 col-md-12">
        {!! Form::label('Memo', __( 'Memo' ) . ':') !!}
        {!! Form::textarea('memo', null, ['class' => 'form-control', 'rows' => 1, 'cols' => 40]); !!}
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

<script>
$('#employee').hide();
$('#vendor').hide();

// $("#projectKey").change(function() {
//             alert("test");
// });
$('form#write_check_add_form').validate();
$('#type').change( function (event) {
  var text = $(this).val();
  
  if (text == 'vendor') { // If email is empty
    $('#employee').hide()
    $('#option').hide();
    $('#vendor').show();
    $('#vendor').prop('disabled', true);
  } else {
    $('#employee').prop('disabled', false);
    $('#employee').show();
    $('#option').hide();
    $('#vendor').hide();
  }
});
  </script>