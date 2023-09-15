<div class="modal-dialog modal-xl no-print" role="document">
  <div class="modal-content">
    <div class="modal-header">
    <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="modalTitle"> @lang('Credit Memo') (<b>@lang('Credit Memo No'):</b> {{ $sell->return_parent->ref_no }})
    </h4>
</div>
<div class="modal-body">
   <div class="row">
      <div class="col-sm-6 col-xs-6">
        <h4>@lang('Credit Memo Details'):</h4>
        <strong>@lang('Date'):</strong> {{@format_date($sell->return_parent->transaction_date)}}<br>
        <strong>@lang('Status'):</strong> {{$sell->return_parent->status}}<br>
        <strong>@lang('Credit Type'):</strong> {{$sell->return_parent->sub_type}}<br>
        <strong>@lang('contact.customer'):</strong> {{ $sell->contact->name }} <br>
        <strong>@lang('Sales Person Name'):</strong> {{ $sell->contact->sales_person->first_name .' '.$sell->contact->sales_person->last_name }} <br>
      </div>
      <div class="col-sm-6 col-xs-6">
        <h4>@lang('Order Details'):</h4>
        <strong>@lang('Order Id'):</strong> {{ $sell->ref_no }} <br>
        <strong>@lang('messages.date'):</strong> {{@format_date($sell->transaction_date)}}
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-sm-12">
        <br>
        <table class="table bg-gray">
          <thead>
            <tr class="bg-green">
                <th>#</th>
                <th>@lang('product.product_name')</th>
                <th>@lang('sale.unit_price')</th>
                <th>@lang('lang_v1.return_quantity')</th>
                <th>@lang('lang_v1.return_subtotal')</th>
            </tr>
        </thead>
        <tbody>
            @php
              $total_before_tax = 0;
            @endphp
            @foreach($sell->sell_lines as $sell_line)

            @if($sell_line->quantity_returned == 0)
                @continue
            @endif

            @php
              $unit_name = $sell_line->product->unit->short_name;

              if(!empty($sell_line->sub_unit)) {
                $unit_name = $sell_line->sub_unit->short_name;
              }
            @endphp

            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                  {{ $sell_line->product->name }}
                  @if( $sell_line->product->type == 'variable')
                    - {{ $sell_line->variations->product_variation->name}}
                    - {{ $sell_line->variations->name}}
                  @endif
                </td>
                <td><span class="display_currency" data-currency_symbol="true">{{ $sell_line->unit_price_inc_tax }}</span></td>
                <td>{{@format_quantity($sell_line->quantity_returned)}} {{$unit_name}}</td>
                <td>
                  @php
                    $line_total = $sell_line->unit_price_inc_tax * $sell_line->quantity_returned;
                    $total_before_tax += $line_total ;
                  @endphp
                  <span class="display_currency" data-currency_symbol="true">{{$line_total}}</span>
                </td>
            </tr>
            @endforeach
          </tbody>
      </table>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-6 col-sm-offset-6 col-xs-6 col-xs-offset-6">
      <table class="table">
        <th>@lang('Total Amount'): </th>
          <td></td>
          <td><span class="display_currency pull-right" data-currency_symbol="true">{{ $total_before_tax }}</span></td>
        </tr>
        <th>@lang('Overall Discount'): </th>
          <td></td>
          <td><span class="display_currency pull-right" data-currency_symbol="true">0.00</span></td>
        </tr>
        <th>@lang('Tax Type'): </th>
          <td></td>
          <td><span class="display_currency pull-right" data-currency_symbol="true">0.00</span></td>
        </tr>
        <th>@lang('Total Tax'): </th>
          <td></td>
          <td><span class="display_currency pull-right" data-currency_symbol="true">0.00</span></td>
        </tr>
        <th>@lang('ML Quantity'): </th>
          <td></td>
          <td><span class="display_currency pull-right" >0.00</span></td>
        </tr>
        <th>@lang('ML Tax'): </th>
          <td></td>
          <td><span class="display_currency pull-right" data-currency_symbol="true">0.00</span></td>
        </tr>
        <th>@lang('Weight Quantity'): </th>
          <td></td>
          <td><span class="display_currency pull-right">0.00</span></td>
        </tr>
        <th>@lang('Weight Tax'): </th>
          <td></td>
          <td><span class="display_currency pull-right" data-currency_symbol="true">0.00</span></td>
        </tr>
        <th>@lang('Adjustment'): </th>
          <td></td>
          <td><span class="display_currency pull-right" data-currency_symbol="true">0.00</span></td>
        </tr>
        <tr>
          <th>@lang('Grand Total'):</th>
          <td></td>
          <td><span class="display_currency pull-right" data-currency_symbol="true" >{{ $sell->return_parent->final_total }}</span></td>
        </tr>
        {{-- <tr>
          <th>@lang('purchase.net_total_amount'): </th>
          <td></td>
          <td><span class="display_currency pull-right" data-currency_symbol="true">{{ $total_before_tax }}</span></td>
        </tr>

        <tr>
          <th>@lang('lang_v1.return_discount'): </th>
          <td><b>(-)</b></td>
          <td class="text-right">@if($sell->return_parent->discount_type == 'percentage')
              @<strong><small>{{$sell->return_parent->discount_amount}}%</small></strong> -
              @endif
          <span class="display_currency pull-right" data-currency_symbol="true">{{ $total_discount }}</span></td>
        </tr>
        
        <tr>
          <th>@lang('lang_v1.total_return_tax'):</th>
          <td><b>(+)</b></td>
          <td class="text-right">
              @if(!empty($sell_taxes))
                @foreach($sell_taxes as $k => $v)
                  <strong><small>{{$k}}</small></strong> - <span class="display_currency pull-right" data-currency_symbol="true">{{ $v }}</span><br>
                @endforeach
              @else
              0.00
              @endif
            </td>
        </tr>
        <tr>
          <th>@lang('lang_v1.return_total'):</th>
          <td></td>
          <td><span class="display_currency pull-right" data-currency_symbol="true" >{{ $sell->return_parent->final_total }}</span></td>
        </tr> --}}
      </table>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
          <strong>{{ __('Credit Memo Activities') }}:</strong><br>
          @includeIf('activity_log.activities', ['activity_type' => 'sell'])
      </div>
  </div>
</div>
<div class="modal-footer">
    <a href="#" class="print-invoice btn btn-primary" data-href="{{action([\App\Http\Controllers\SellReturnController::class, 'printInvoice'], [$sell->return_parent->id])}}"><i class="fa fa-print" aria-hidden="true"></i> @lang("messages.print")</a>
      <button type="button" class="btn btn-default no-print" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    var element = $('div.modal-xl');
    __currency_convert_recursively(element);
  });
</script>