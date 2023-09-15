<div class="col-md-3">
        <div class="form-group">
            <label for="all">Payment ID</label>
            <input type="text" placeholder="Payment ID" id="paymentid" name="paymentid" class="form-control" readonly>
        </div>
    </div>
   
        <div class="col-md-3">
            <div class="form-group">
                <label for="select"> Customer Name <span class="error">*</span></label>
                <select class="form-control" id="customer_id" value="" name="customer_id">
                    <option value="">Select Customer</option>
                    @foreach ($customers as $key=>$customer)
                    <option value="{{ $key }}">{{ $customer }}</option>
                    @endforeach                    
                </select>
            </div>
        </div>
    



    <div class="col-md-3">
        <div class="form-group">
            <label for="bod">Due Amount</label>
            <div id="dueamount" class="input-group date input-color" value required>
                <span class="input-group-addon"><i class="fa fa-eye" data-toggle="modal" data-target="#exampleModal111" aria-hidden="true"></i></span>
                <input class="form-control align-right" id="due_amount" name="due_amount" type="text" value="0.00" readonly>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="bod">Receive Date <span class="error">*</span></label>
            <input class="form-control" type="date" data-date-format="dd/mm/yyyy" value="20/02/2023" name="receive_date" id="receive_date">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="rcamount">Receive Amount <span class="error">*</span></label>
            <div id="receiveamount" class="input-group date input-color" required>
                <span class="input-group-addon">&#36;</span>
                <input class="form-control align-right" type="text" id="receive_amount" name="receive_amount" value="0.00">
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
                <option value="Cash">Cash</option>                   
                <option value="Check">Check</option>                   
                <option value="Credit Card">Credit Card</option>                   
                <option value="Electronic Transfer">Electronic Transfer</option>                   
                <option value="Money Order">Money Order</option>                   
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="rcamount">Reference ID</label>
            <input class="form-control" type="text" id="referenceid" name="reference_id" value="">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="rcamount">Remark</label>
            <textarea class="form-control" type="textarea" id="remark" name="remark" min-height="150px" width="100%"></textarea>
        </div>
    </div>

    <!-- table -->
           
        <div class="order_data">            
        </div>
    
    <div class="setbtn">
        <button class="stbtn">Reset</button>
        <button class="stbtn">Save</button>
    </div>
