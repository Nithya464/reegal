<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open([
            'url' => action([\App\Http\Controllers\WriteChequeController::class, 'update'], [$writecheck->id]),
            'method' => 'PUT',
            'id' => 'writecheck_edit_form',
        ]) !!}

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('Edit Cheque')</h4>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="form-group col-sm-12">
                    {{-- {!! Form::label('state_id', __( 'State' ) . ':*') !!}
              {!! Form::select('state_id', $states, $writecheck->state_id, ['class' => 'form-control','required','placeholder' => __( 'Select State' )]); !!} --}}

                    {!! Form::label('type', __('Type') . ':*') !!}
                    {!! Form::select('type', config('global.write_check_type_options'), $writecheck->type, [
                        'class' => 'form-control vendor_type',
                        'placeholder' => __('Select Option'),
                        'required',
                    ]) !!}
                </div>
                <div id="empdata">
                    <div class="form-group col-sm-12">
                        {!! Form::label('employee_id', __('Employee Name') . ':*') !!}
                        {!! Form::select('employee_id', $users, $writecheck->employee_id, [
                            'class' => 'form-control',
                            'placeholder' => __('Select Employee'),
                        ]) !!}
                    </div>
                </div>

                {{-- <div id="vendordata">
                    <div class="form-group col-sm-12">
                        {!! Form::label('vendor_id', __('Vendor Name') . ':*') !!}
                        {!! Form::select('vendor_id', $vendors, null, ['class' => 'form-control', 'placeholder' => __('Select Vendor')]) !!}
                    </div>
                </div> --}}

                @error('vendor_id')
                    <div class="invalid-feedback text-danger">
                        {{ $message }}
                    </div>
                @enderror
                <div id="vendor">
                    @if ($writecheck->type == 'vendor')
                        <div class="form-group col-sm-12">
                            {!! Form::label('vendor', __('Vendor Name') . ':*') !!}
                            {!! Form::select('vendor_id', $vendors, $writecheck->vendor_id, [
                                'class' => 'form-control',
                                'id' => '1vendor_id',
                                'placeholder' => __('Select Vendor'),
                                'required',
                            ]) !!}
                        </div>
                    @endif

                </div>

                {{-- <div id="employee">
          @if ($writecheck->type == 'employee')
          <div class="form-group col-sm-12">
            {!! Form::label('employee', __( 'Employee Name' ) . ':*') !!}
            {!! Form::select('employee_id', $users, $writecheck->employee_id, ['class' => 'form-control','id' => 'employee_id','placeholder' => __( 'Select Employee' ),'required']); !!}
          </div>
          @endif
        </div> --}}
                {{-- @error('employee_id')
          <div class="invalid-feedback text-danger">
              {{  $message }}
          </div>
      @enderror --}}

                <div class="form-group col-sm-12">
                    {!! Form::label('cheque_number', __('Cheque Number') . ':*') !!}
                    {!! Form::text('cheque_number', $writecheck->cheque_number, [
                        'class' => 'form-control',
                        'required',
                        'placeholder' => __('Enter City Name'),
                        'required',
                    ]) !!}
                </div>

                <div class="form-group col-sm-12">
                    {!! Form::label('date', __('date') . ':*') !!}
                    {{-- <div class="input-group">
              <span class="input-group-addon">
                  <i class="fa fa-calendar"></i>
              </span> --}}
                    {!! Form::date('date', $writecheck->date, ['class' => 'form-control', 'required']) !!}
                    {{-- </div> --}}
                </div>
                <div class="form-group col-sm-12">
                    {!! Form::label('cheque_amount', __('Cheque Amount') . '$:*') !!}
                    {!! Form::text('cheque_amount', $writecheck->cheque_amount, [
                        'class' => 'form-control input_number',
                        'required',
                    ]) !!}
                </div>

                <div class="form-group col-xs-12 col-sm-4 col-md-12">
                    {!! Form::label('address', __('Address') . ':*') !!}
                    {!! Form::textarea('address', $writecheck->address, [
                        'class' => 'form-control',
                        'rows' => 2,
                        'cols' => 40,
                        'required',
                    ]) !!}
                </div>

                <div class="form-group col-xs-12 col-sm-4 col-md-12">
                    {!! Form::label('Memo', __('Memo') . ':') !!}
                    {!! Form::textarea('memo', $writecheck->memo, ['class' => 'form-control', 'rows' => 1, 'cols' => 40]) !!}
                </div>

            </div>

            {{-- </div>

    </div> --}}

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
            </div>

            {!! Form::close() !!}

        </div><!-- /.modal-content -->
    </div>

    <script>
        // $('form#writecheck_edit_form').validate();
        $('#vendordata').hide();
        $('#empdata').hide();
        $('#type').each(function() {
            var type = $(this).val();
            if (type == 'vendor') { // If email is empty
                $('#employee').hide()
                $('#option').hide();
                $('#vendordata').show();
                $('#empdata').hide();
                $('[name="employee_id"]').val('');
                // $('#employee_id').val(" ");
                $('#vendor').prop('disabled', true);
            } else {
                $('#employee').prop('disabled', false);
                $('#employee').show();
                $('#option').hide();
                $('#vendordata').hide();
                $('#1vendor_id').val(" ");
                $('#empdata').show();
                $('#vendor').hide();
            }
        })
        $('#type').change(function(event) {
            var text = $(this).val();
            // alert(text);

            if (text == 'vendor') { // If email is empty
                $('#employee').hide()
                $('#option').hide();
                $('#vendordata').show();
                $('#empdata').hide();
                $('[name="employee_id"]').val('');
                // $('#employee_id').val(" ");
                $('#vendor').prop('disabled', true);
            } else {
                $('#employee').prop('disabled', false);
                $('#employee').show();
                $('#option').hide();
                $('#vendordata').hide();
                $('#1vendor_id').val(" ");
                $('#empdata').show();
                $('#vendor').hide();
            }
        });
    </script>
