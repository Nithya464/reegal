@php
    $hide_tax = '';
    if( session()->get('business.enable_inline_tax') == 0){
        $hide_tax = 'hide';
    }
    $currency_precision = session('business.currency_precision', 2);
    $quantity_precision = session('business.quantity_precision', 2);
@endphp
<div class="table-responsive">
    <table class="table table-condensed table-bordered table-th-green text-center table-striped" 
    id="purchase_entry_table">
        <thead>
              <tr>
                <th>#</th>
                <th>@lang( 'product.product_name' )</th>
                <th>@lang( 'PO Unit' )</th>
                <th>@lang( 'Current Stock' )</th>
                <th>@lang( 'PO Qty' )</th>
                <th>@lang( 'PO Pieces' )</th>
                <th>@lang( 'Price' )</th>
                <th>@lang( 'Total Price' )</th>
                <th><i class="fa fa-trash" aria-hidden="true"></i></th>
              </tr>
        </thead>
        <tbody>
    <?php $row_count = 0; ?>
    @foreach($purchase->purchase_lines as $purchase_line)
        <tr @if(!empty($purchase_line->purchase_order_line) && !empty($common_settings['enable_purchase_order'])) data-purchase_order_id="{{$purchase_line->purchase_order_line->transaction_id}}" @endif  @if(!empty($purchase_line->purchase_requisition_line) && !empty($common_settings['enable_purchase_requisition'])) data-purchase_requisition_id="{{$purchase_line->purchase_requisition_line->transaction_id}}" @endif>
            <td><span class="sr_number"></span></td>
            <td>
                <input type="hidden" id="productId{{ $purchase_line->product_id }}">
                {!! Form::hidden('purchases[' . $row_count . '][purchase_line_id]', $purchase_line->id ); !!}
                {!! Form::hidden('purchases[' . $row_count . '][product_id]', $purchase_line->product->id ); !!}
                {{ $purchase_line->product->name }}
            </td>
            <td>
                <select name="purchases[{{$row_count}}][sub_unit_id]" data-count="{{ $row_count }}" class="unitIdClass form-control input-sm sub_unit">
                    @foreach($purchase_line->product->product_unit_price as $key => $value)
                        @if($purchase_line->product_unit_price_id == $value->id)
                            @php $poPieces = $value->qty * $purchase_line->quantity; @endphp
                        @endif
                        <option @if($purchase_line->product_unit_price_id == $value->id) selected @endif value="{{$value->id}}" data-multiplier="{{$value->qty}}" data-base-price="{{ $value->base_price }}">
                            {{$value->unit}} ({{ $value->qty }}) pcs
                        </option>
                    @endforeach
                </select>
            </td>
            <td>{{ (($purchase_line->product->product_default_unit->unit)) ?? null }} - {{ (($purchase_line->product->product_stock->current_stock)) ?? 0; }}</td>

            <td>
                @php
                    $check_decimal = 'false';
                    if($purchase_line->product->unit->allow_decimal == 0){
                        $check_decimal = 'true';
                    }
                    $max_quantity = 0;

                    if(!empty($purchase_line->purchase_order_line_id) && !empty($common_settings['enable_purchase_order'])){
                        $max_quantity = $purchase_line->purchase_order_line->quantity - $purchase_line->purchase_order_line->po_quantity_purchased + $purchase_line->quantity;
                    }
                @endphp

                <input type="text" 
                name="purchases[{{$loop->index}}][quantity]" 
                value="{{@format_quantity($purchase_line->quantity)}}"
                class="form-control input-sm purchase_quantity input_number mousetrap"
                required
                data-rule-abs_digit={{$check_decimal}}
                data-msg-abs_digit="{{__('lang_v1.decimal_value_not_allowed')}}"
                @if(!empty($max_quantity))
                    data-rule-max-value="{{$max_quantity}}"
                    data-msg-max-value="{{__('lang_v1.max_quantity_quantity_allowed', ['quantity' => $max_quantity])}}" 
                @endif
                >
            </td>
            <td>
                {!! Form::hidden('purchases[' . $row_count . '][sub_total_quantity]', (($poPieces)) ?? 0, ['class' => 'sub_total_quantity']); !!}
                <span class="sub_total_quantity">{{ (($poPieces)) ?? 0; }}</span>
            </td>
            <td><input type="text" name="purchases[{{$row_count}}][price]" value="{{ $purchase_line->purchase_price }}" class="form-control input-sm purchase_product_price input_number mousetrap" required></td>

            <td><input type="text" name="purchases[{{$row_count}}][total_price]" value="{{ $purchase_line->purchase_price_total }}" class="form-control input-sm purchase_product_total_price input_number mousetrap" readonly></td>

            <td><i class="fa fa-times remove_purchase_entry_row text-danger" data-id="{{ $purchase_line->product->id }}" title="Remove" style="cursor:pointer;"></i></td>
        </tr>
        <?php $row_count = $loop->index + 1 ; ?>
    @endforeach
        </tbody>
    </table>
</div>
<input type="hidden" id="row_count" value="{{ $row_count }}">