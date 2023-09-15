@extends('layouts.app')
@section('title', __('Expense Create'))

@section('content')
    <style type="text/css">



    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Expense Create</h1>
        <!-- <ol class="breadcrumb">
                                <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                                <li class="active">Here</li>
                            </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        {!! Form::open([
            'url' => action([\App\Http\Controllers\ManageExpenseController::class, 'store']),
            'method' => 'post',
            'id' => 'expense_add_form',
        ]) !!}
        <div class="box box-solid">
            <div class="box-body">
                <br><br><br>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    {!! Form::label('date', __('Date') . ':*') !!}
                                    {!! Form::date('date', now(), [
                                        'class' => 'form-control',
                                        'placeholder' => __('Select Date'),
                                        'required',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input type="hidden" name="payment_method" id="payment_method" value="cash">
                                    {!! Form::label('payment_method', __('Payment Method') . ':') !!}
                                    {!! Form::select('payment_method', ['cash' => 'Cash', 'cheque' => 'Cheque'], null, [
                                        'class' => 'form-control',
                                        'disabled',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <input type="hidden" name="payment_source_id" id="payment_source_id" value="1">
                                    {!! Form::label('payment_source_id', __('Payment Source') . ':') !!}
                                    {!! Form::select('payment_source_id', ['1' => "Today's Bank", '2' => 'Bank Of Baroda'], null, [
                                        'class' => 'form-control',
                                        'disabled',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    {!! Form::label('expense_category_id', 'Category' . ':*') !!}
                                    {!! Form::select('expense_category_id', $expense_categories, null, [
                                        'placeholder' => 'Select Category',
                                        'class' => 'form-control',
                                        'required',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    {!! Form::label('expense_amount', __('Expense Amount') . ':') !!}
                                    {!! Form::text('expense_amount', null, [
                                        'class' => 'form-control',
                                        'id'=>'expense_amount',
                                        'readonly',
                                        
                                    ]) !!}
                                    @error('expense_amount')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                    {!! Form::label('expense_amount', 'Note: This will credit same amount in petty cash.') !!}
                                </div>
                                {{-- <div class="form-group">
                                    {!! Form::label('expense_amount', __('Expense Amount') . ':') !!}
                                    {!! Form::text('expense_amount', null, [
                                        'class' => 'form-control',
                                        'id' => 'expense_amount', // Add an ID for targeting
                                    ]) !!}
                                    @error('expense_amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    {!! Form::label('expense_amount_note', 'Note: This will credit the same amount in petty cash.') !!}
                                </div> --}}
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    {!! Form::label('description', __('Description') . ':*') !!}
                                    {!! Form::textarea('description', null, [
                                        'class' => 'form-control',
                                        'required',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Bill</th>
                                        <th scope="col">Total Count</th>
                                        <th scope="col">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">$100</th>
                                        <td>
                                            {!! Form::text('bill_$100', null, ['class' => 'form-control amount', 'data-amount' => '100']) !!}
                                        </td>
                                        <td>0.00</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">$50</th>
                                        <td>
                                            {!! Form::text('bill_$50', null, ['class' => 'form-control amount', 'data-amount' => '50']) !!}
                                        </td>
                                        <td>0.00</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">$20</th>
                                        <td>
                                            {!! Form::text('bill_$20', null, ['class' => 'form-control amount', 'data-amount' => '20']) !!}
                                        </td>
                                        <td>0.00</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">$10</th>
                                        <td>
                                            {!! Form::text('bill_$10', null, ['class' => 'form-control amount', 'data-amount' => '10']) !!}
                                        </td>
                                        <td>0.00</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">$5</th>
                                        <td>
                                            {!! Form::text('bill_$5', null, ['class' => 'form-control amount', 'data-amount' => '5']) !!}
                                        </td>
                                        <td>0.00</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">$2</th>
                                        <td>
                                            {!! Form::text('bill_$2', null, ['class' => 'form-control amount', 'data-amount' => '2']) !!}
                                        </td>
                                        <td>0.00</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">$1</th>
                                        <td>
                                            {!! Form::text('bill_$1', null, ['class' => 'form-control amount', 'data-amount' => '1']) !!}
                                        </td>
                                        <td>0.00</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">50¢</th>
                                        <td>
                                            {!! Form::text('bill_50¢', null, ['class' => 'form-control amount', 'data-amount' => '0.5']) !!}
                                        </td>
                                        <td>0.00</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">25¢</th>
                                        <td>
                                            {!! Form::text('bill_25¢', null, ['class' => 'form-control amount', 'data-amount' => '0.25']) !!}
                                        </td>
                                        <td>0.00</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">10¢</th>
                                        <td>
                                            {!! Form::text('bill_10¢', null, ['class' => 'form-control amount', 'data-amount' => '0.10']) !!}
                                        </td>
                                        <td>0.00</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">5¢</th>
                                        <td>
                                            {!! Form::text('bill_5¢', null, ['class' => 'form-control amount', 'data-amount' => '0.05']) !!}
                                        </td>
                                        <td>0.00</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">1¢</th>
                                        <td>
                                            {!! Form::text('bill_1¢', null, ['class' => 'form-control amount', 'data-amount' => '0.010']) !!}
                                        </td>
                                        <td>0.00</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <th scope="row">Total Amount </th>
                                        <td id="totalAmount">0.00</td>
                                    </tr>
                                </tbody>
                            </table>


                        </div>
                    </div>
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-primary pull-right">Save</button>
                    </div>

                </div>

                <br><br><br>
            </div>
        </div>
        {!! Form::close() !!}
    </section>
    <!-- /.content -->
@endsection
@section('javascript')
    <script>
        // $('form#expense_add_form').validate({
        //     rules: {
        //         expense_amount: {
        //             number: true,
        //             min: 1
        //         }
        //     },
        //     messages: {
        //         expense_amount: {
        //             number: "Please enter a valid number",
        //             min: "Amount must be greater than or equal to 0"
        //         }
        //     }
        // });

          $('form#expense_add_form').validate();

        $(document).ready(function() {
            $('.amount').on('keyup', function() {
                var inputAmount = parseFloat($(this).val());
                if (isNaN(inputAmount)) {
                    inputAmount = 0.00;
                }
                var dataAmount = parseFloat($(this).data('amount'));
                var newAmount = inputAmount * dataAmount;
                $(this).closest('tr').find('td:last').text(newAmount.toFixed(2));

                var totalAmount = 0;
                $('.amount').each(function() {
                    var inputAmount = parseFloat($(this).val());
                    if (isNaN(inputAmount)) {
                        inputAmount = 0.00;
                    }

                    var dataAmount = parseFloat($(this).data('amount'));
                    var newAmount = inputAmount * dataAmount;
                    totalAmount += newAmount;
                });

                $('#expense_amount').val(totalAmount.toFixed(2));
                $('#totalAmount').text(totalAmount.toFixed(2));

            });




        });
    </script>
@endsection
