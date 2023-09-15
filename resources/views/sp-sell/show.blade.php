<div class="modal-dialog modal-xl no-print" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title" id="modalTitle"> @lang('View order') (<b>@if($sell->type == 'sales_order') @lang('restaurant.order_no') @else @lang('Order No') @endif :</b> {{ $sell->ref_no }})
      </h4>
  </div>
  <div class="modal-body">
      <div class="row">
        <div class="col-xs-12">
            <p class="pull-right"><b>@lang('Order Date'):</b> {{ @format_date($sell->transaction_date) }}</p>
        </div>
      </div>
      <div class="row">
        @php
          $custom_labels = json_decode(session('business.custom_labels'), true);
          $export_custom_fields = [];
          if (!empty($sell->is_export) && !empty($sell->export_custom_fields_info)) {
              $export_custom_fields = $sell->export_custom_fields_info;
          }
        @endphp
        <div class="@if(!empty($export_custom_fields)) col-sm-3 @else col-sm-4 @endif">
          <b>@if($sell->type == 'sales_order') {{ __('restaurant.order_no') }} @else {{ __('sale.invoice_no') }} @endif:</b> #{{ $sell->invoice_no }}<br>
          <b>{{ __('sale.status') }}:</b> 
            @if($sell->status == 'draft' && $sell->is_quotation == 1)
              {{ __('lang_v1.quotation') }}
            @else
              {{ $statuses[$sell->status]['label'] ?? __($sell->status) }}
            @endif
          <br>
          <b>{{ __('Shipping Type') }}:</b> 
            {{ config('global.shipping_type')[$sell->sub_type] ?? __($sell->sub_type) }}
          <br>
        </div>
        <div class="@if(!empty($export_custom_fields)) col-sm-3 @else col-sm-4 @endif">
          @if(!empty($sell->contact->supplier_business_name))
            {{ $sell->contact->supplier_business_name }}<br>
          @endif
          <b>{{ __('sale.customer_name') }}:</b> {{ $sell->contact->name }}<br>
          <b>{{ __('business.address') }}:</b><br>
          @if(!empty($sell->billing_address()))
            {{$sell->billing_address()}}
          @else
            {!! $sell->contact->contact_address !!}
            @if($sell->contact->mobile)
            <br>
                {{__('contact.mobile')}}: {{ $sell->contact->mobile }}
            @endif
            @if($sell->contact->alternate_number)
            <br>
                {{__('contact.alternate_contact_number')}}: {{ $sell->contact->alternate_number }}
            @endif
            @if($sell->contact->landline)
              <br>
                {{__('contact.landline')}}: {{ $sell->contact->landline }}
            @endif
            @if($sell->contact->email)
              <br>
                {{__('business.email')}}: {{ $sell->contact->email }}
            @endif
          @endif
          
        </div>
        <div class="@if(!empty($export_custom_fields)) col-sm-3 @else col-sm-4 @endif">
        <strong>@lang('sale.shipping'):</strong>
        <span class="label @if(!empty($shipping_status_colors[$sell->shipping_status])) {{$shipping_status_colors[$sell->shipping_status]}} @else {{'bg-gray'}} @endif">{{$shipping_statuses[$sell->shipping_status] ?? '' }}</span><br>
        @if(!empty($sell->shipping_address()))
          {{$sell->shipping_address()}}
        @else
          {{$sell->shipping_address ?? '--'}}
        @endif
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-sm-12 col-xs-12">
          <h4>{{ __('sale.products') }}:</h4>
        </div>
  
        <div class="col-sm-12 col-xs-12">
          <div class="table-responsive">
            @php
                $mlQuantity = 0.00;
                $weightQuantity = 0.00;
            @endphp
            @foreach($sell->sell_lines as $sell_line)
              @php
                  $mlQuantity += $sell_line->ml_qty;
                  $weightQuantity += $sell_line->weight;
              @endphp
            @endforeach
            @include('pos-sell.partials.sale_line_details')
          </div>
        </div>
      </div>
      <div class="row">
        @php
          $total_paid = 0;
        @endphp
        
        <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-6">
          <div class="table-responsive">
            <table class="table bg-gray">
              <tr>
                <th>{{ __('Sub Total') }}: </th>
                <td></td>
                <td><span class="display_currency pull-right" data-currency_symbol="true">{{ $sell->final_total }}</span></td>
                <th>{{ __('Order Total') }}: </th>
                <td></td>
                <td><span class="display_currency pull-right" data-currency_symbol="true">{{ $sell->final_total }}</span></td>
              </tr>
              <tr>
                <th>{{ __('Overall Discount') }}:</th>
                <td><b>(-)</b></td>
                <td><div class="pull-right"><span class="display_currency" @if( $sell->discount_type == 'fixed') data-currency_symbol="true" @endif>{{ $sell->discount_amount }}</span> @if( $sell->discount_type == 'percentage') {{ '%'}} @endif</span></div></td>
                <th>{{ __('Credit Memo Amount') }}:</th>
                <td></td>
                <td><span class="display_currency pull-right" data-currency_symbol="true">0.00</span></td>
              </tr>
              <tr>
                <th>{{ __('Shipping Charges') }}:</th>
                <td><b>(+)</b></td>
                <td><span class="display_currency pull-right" data-currency_symbol="true">{{ $sell->shipping_charges }}</span></td>
                <th>{{ __('Store Credit Amount') }}:</th>
                <td></td>
                <td><span class="display_currency pull-right" data-currency_symbol="true">0.00</span></td>
              </tr>
              <tr>
                <th>{{ __('Tax Type') }}:</th>
                <td></td>
                <td class="text-right">
                  @if(!empty($order_taxes))
                    @foreach($order_taxes as $k => $v)
                      <strong><small>{{$k}}</small></strong> - <span class="display_currency pull-right" data-currency_symbol="true">{{ $v }}</span><br>
                    @endforeach
                  @else
                  0.00
                  @endif
                </td>
                <th>{{ __('Payable Amount') }}:</th>
                <td></td>
                <td><span class="display_currency pull-right" data-currency_symbol="true">{{ $sell->final_total }}</span></td>
              </tr>
              <tr>
                <th>{{ __('Total Tax') }}:</th>
                <td></td>
                <td><span class="display_currency pull-right" data-currency_symbol="true">0.00</span></td>
                <th>{{ __('Past Due Amount') }}:</th>
                <td></td>
                <td><span class="display_currency pull-right" data-currency_symbol="true">0.00</span></td>
              </tr>
              <tr>
                <th>{{ __('ML Quantity') }}:</th>
                <td></td>
                <td><span class="display_currency pull-right" data-currency_symbol="true">{{ $mlQuantity }}</span></td>
                <th>{{ __('Open Balance') }}:</th>
                <td></td>
                <td><span class="display_currency pull-right" data-currency_symbol="true">0.00</span></td>
              </tr>
              <tr>
                <th>{{ __('ML Tax') }}:</th>
                <td></td>
                <td><span class="display_currency pull-right" data-currency_symbol="true">0.00</span></td>
                <th>{{ __('Paid Amount') }}:</th>
                <td></td>
                <td><span class="display_currency pull-right" data-currency_symbol="true">0.00</span></td>
              </tr>
              <tr>
                <th>{{ __('Weight Quantity') }}:</th>
                <td></td>
                <td><span class="display_currency pull-right" data-currency_symbol="true">{{ $weightQuantity }}</span></td>
                <th>{{ __('Balance Amount') }}:</th>
                <td></td>
                <td><span class="display_currency pull-right" data-currency_symbol="true">0.00</span></td>
              </tr>
              <tr>
                <th>{{ __('Weight Tax') }}:</th>
                <td></td>
                <td><span class="display_currency pull-right" data-currency_symbol="true">0.00</span></td>
                <th>{{ __('Payment Method') }}:</th>
                <td></td>
                <td><span class="display_currency pull-right" data-currency_symbol="true">0.00</span></td>
              </tr>
              <tr>
                <th>{{ __('Adjustment') }}:</th>
                <td></td>
                <td><span class="display_currency pull-right" data-currency_symbol="true">0.00</span></td>
                <th>{{ __('Reference No') }}:</th>
                <td></td>
                <td><span class="display_currency pull-right" data-currency_symbol="true">0.00</span></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <strong>{{ __( 'Remark')}}:</strong><br>
          <p class="well well-sm no-shadow bg-gray">
            @if($sell->additional_notes)
              {!! nl2br($sell->additional_notes) !!}
            @else
              --
            @endif
          </p>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
              <strong>{{ __('lang_v1.activities') }}:</strong><br>
              @includeIf('activity_log.activities', ['activity_type' => 'sell'])
          </div>
      </div>
    </div>
    <div class="modal-footer">
      @if($sell->type != 'sales_order')
      <a href="#" class="print-invoice btn btn-success" data-href="{{route('sell.printInvoice', [$sell->id])}}?package_slip=true"><i class="fas fa-file-alt" aria-hidden="true"></i> @lang("lang_v1.packing_slip")</a>
      @endif
      @can('print_invoice')
        <a href="#" class="print-invoice btn btn-primary" data-href="{{route('sell.printInvoice', [$sell->id])}}"><i class="fa fa-print" aria-hidden="true"></i> @lang("lang_v1.print_invoice")</a>
      @endcan
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
  