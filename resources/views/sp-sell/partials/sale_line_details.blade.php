<table class="table @if(!empty($for_ledger)) table-slim mb-0 bg-light-gray @else bg-gray @endif" @if(!empty($for_pdf)) style="width: 100%;" @endif>
    <tr @if(empty($for_ledger)) class="bg-green" @endif>
    <th>ID</th>
    <th>{{ __('sale.product') }}</th>
    <th>Unit</th>
    <th>{{ __('sale.qty') }}</th>
    <th>Total Pieces</th>
    <th>{{ __('sale.unit_price') }}</th>
    <th>SRP</th>
    <th>GP (%)</th>
    <th>Item Total</th>
    <th>{{ __('sale.discount') }}</th>
    {{-- <th>{{ __('sale.tax') }}</th> --}}
    {{-- <th>{{ __('sale.price_inc_tax') }}</th> --}}
    <th>{{ __('sale.subtotal') }}</th>
</tr>

@foreach($sell->sell_lines as $sell_line)
    <tr>
        <td>{{ $sell_line->product->sku }}</td>
        <td>
            {{ $sell_line->product->name }}
        </td>
        <td>
            {{ $sell_line->sub_unit_price->unit }} ({{ $sell_line->sub_unit_price->qty }} pcs)
        </td>
        <td>{{@format_quantity($sell_line->quantity)}}</td>
        <td>{{@format_quantity(($sell_line->quantity * $sell_line->sub_unit_price->qty))}}</td>
        <td><span class="display_currency" data-currency_symbol="true">{{ $sell_line->unit_price }}</span></td>
        <td>{{ $sell_line->product->srp }}</td>
        <td>00.00</td>
        <td>
            <span class="display_currency" data-currency_symbol="true">{{ $sell_line->unit_price_before_discount }}</span>
        </td>
        <td>
            @if(!empty($for_ledger))
                @format_currency($sell_line->get_discount_amount())
            @else
                <span class="display_currency" data-currency_symbol="true">{{ $sell_line->get_discount_amount() }}</span>
            @endif
            @if($sell_line->line_discount_type == 'percentage') ({{$sell_line->line_discount_amount}}%) @endif
        </td>
        <td>
            @if(!empty($for_ledger))
                @format_currency($sell_line->unit_price_inc_tax)
            @else
                <span class="display_currency" data-currency_symbol="true">{{ $sell_line->unit_price_inc_tax }}</span>
            @endif
        </td>
    </tr>
@endforeach
</table>