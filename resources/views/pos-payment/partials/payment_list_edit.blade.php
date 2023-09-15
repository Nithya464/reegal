<input type="hidden" name="id" value="{{ $payment_list->id }}">
<div class="col-md-3">
    <div class="form-group">
        <label for="all">Payment ID</label>
        <input type="text" placeholder="Payment ID" id="paymentid" name="paymentid" class="form-control" value="{{ $payment_list->payment_id }}" readonly>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="all">Customer Name <span class="error">*</span></label>
        <input type="text" placeholder="Payment ID" id="paymentid" name="paymentid" class="form-control" value="{{ $payment_list->customer_name }}" readonly>
    </div>
</div>

    {{-- <div class="col-md-3">
        <div class="form-group">
            <label for="select"> Customer Name <span class="error">*</span></label>
            <select class="form-control" id="customer_id" value="" name="customer_id">
                <option value="">Select Customer</option>
                @foreach ($customers as $customer)
                <option @if($customer->id == $payment_list->customer_id) Selected @endif value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach                    
            </select>
        </div>
    </div> --}}

<div class="col-md-3">
    <div class="form-group">
        <label for="bod">Due Amount</label>
        <div id="dueamount" class="input-group date input-color" value required>
            <span class="input-group-addon"><i class="fa fa-eye" data-toggle="modal" data-target="#exampleModal111" aria-hidden="true"></i></span>
            <input class="form-control align-right" id="due_amount" name="due_amount" type="text" value="{{ $payment_list->due_amount }}" readonly>
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label for="bod">Receive Date <span class="error">*</span></label>
        <input class="form-control" type="date" data-date-format="dd/mm/yyyy" value="{{ $payment_list->receive_date }}" name="receive_date" id="receive_date">
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="rcamount">Receive Amount <span class="error">*</span></label>
        <div id="receiveamount" class="input-group date input-color" value required>
            <span class="input-group-addon">&#36;</span>
            <input class="form-control align-right" type="text" id="receive_amount" name="receive_amount" value="{{ $payment_list->receive_amount }}">
        </div>
    </div>
</div>
{{-- @if (empty($only) || in_array('sell_list_filter_payment_status', $only))
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('sell_list_filter_payment_status',  __('purchase.payment_status') . ':') !!}
        {!! Form::select('sell_list_filter_payment_status', ['paid' => __('lang_v1.paid'), 'due' => __('lang_v1.due'), 'partial' => __('lang_v1.partial'), 'overdue' => __('lang_v1.overdue')], null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
    </div>
</div>
@endif --}}
{{-- @if (empty($only) || in_array('sell_list_filter_date_range', $only))
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('sell_list_filter_date_range', __('report.date_range') . ':') !!}
            {!! Form::text('sell_list_filter_date_range', null, [
                'placeholder' => __('lang_v1.select_a_date_range'),
                'class' => 'form-control',
                'readonly',
            ]) !!}
        </div>
    </div>
@endif --}}
{{-- @if (empty($only) || in_array('payment_mode', $only))
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('payment_mode', __('Payment Mode') . ':') !!}
            {!! Form::select(
                'payment_mode',
                [ __('Cash'),__('Check'), __('Credit Card'),__('Electronic Transfer'),__('Money Order')],
                null,
                ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('Select Payment Mode')],
            ) !!}
        </div>
    </div>
@endif --}}
<div class="col-md-3">
    <div class="form-group">
        <label for="select"> Payment Mode <span class="error">*</span></label>
        <select class="form-control" id="payment_mode" name="payment_mode" value="">
            <option value="">Select Payment Mode</option>
            <option @if($payment_list->payment_mode == 'Cash') selected @endif value="Cash">Cash</option>                   
            <option @if($payment_list->payment_mode == 'Check') selected @endif value="Check">Check</option>                   
            <option @if($payment_list->payment_mode == 'Credit Card') selected @endif value="Credit Card">Credit Card</option>                   
            <option @if($payment_list->payment_mode == 'Electronic Transfer') selected @endif value="Electronic Transfer">Electronic Transfer</option>                   
            <option @if($payment_list->payment_mode == 'Money Order') selected @endif value="Money Order">Money Order</option>                   
        </select>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="rcamount">Reference ID</label>
        <input class="form-control" type="text" id="referenceid" name="reference_id" value="{{ $payment_list->reference_id }}">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label for="rcamount">Remark</label>
        <textarea class="form-control" type="textarea" id="remark" name="remark" min-height="150px" width="100%">{{ $payment_list->remark }}</textarea>
    </div>
</div>

<!-- table -->
       
    <div class="order_data">   
        <table class="table table-bordered receivetable">
            <thead style="background-color:#24394D ; color:#fff;">
                <tr>
                    <th >Action</th>
                    <th>Order no.</th>
                    <th>Order date</th>
                    <th>Order Amount</th>
                    <th>Paid Amount</th>
                    <th>Due Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php $amount ='0' ?>
                @foreach ($orders as $order)
            <?php $amount += $order->final_total ?>
                <tr>
                    <td><div class="form-group"><input type="checkbox" height="30px" width="30px" name="selected_ids[]" value="{{ $order->id }}" @if($order->id==$payment_list->transaction_id) checked @endif></div></td>
                    <td>{{ $order->ref_no }}</td>
                    <td>{{ date('d/m/Y', strtotime($order->transaction_date)) }}</td>
                    <td>2{{ $order->final_total }}</td>
                    <td>0.00</td>
                    <td>{{ $order->final_total }}</td>
                </tr>  
                @endforeach   
                <tr>
                    <td colspan="5" style="text-align: right">Total</td>
                    <td>{{  $amount }}</td>
                </tr>              
            </tbody>
        </table>         
    </div>

<div class="setbtn">
    <button class="stbtn">Reset</button>
    <button class="stbtn">Save</button>
</div>
