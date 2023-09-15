@extends('layouts.app')
@section('title', __('lang_v1.edit_purchase_order'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('lang_v1.edit_purchase_order') <i class="fa fa-keyboard-o hover-q text-muted" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="bottom" data-content="@include('purchase.partials.keyboard_shortcuts_details')" data-html="true" data-trigger="hover" data-original-title="" title=""></i></h1>
</section>

<!-- Main content -->
<section class="content">

  <!-- Page level currency setting -->
  <input type="hidden" id="show_price">
  <input type="hidden" id="p_code" value="{{$currency_details->code}}">
  <input type="hidden" id="p_symbol" value="{{$currency_details->symbol}}">
  <input type="hidden" id="p_thousand" value="{{$currency_details->thousand_separator}}">
  <input type="hidden" id="p_decimal" value="{{$currency_details->decimal_separator}}">

  @include('layouts.partials.error')

  {!! Form::open(['url' =>  action([\App\Http\Controllers\PurchaseOrderByVendorImController::class, 'update'] , [$purchase->id] ), 'method' => 'PUT', 'id' => 'add_purchase_form', 'files' => true ]) !!}

  @php
    $currency_precision = session('business.currency_precision', 2);
  @endphp
  <input type="hidden" id="is_purchase_order">
  <input type="hidden" id="purchase_id" value="{{ $purchase->id }}">

    @component('components.widget', ['class' => 'box-primary'])
        <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                {!! Form::label('supplier_id', __('purchase.supplier') . ':*') !!}
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="fa fa-user"></i>
                  </span>
                  {!! Form::select('contact_id', [ $purchase->contact_id => $purchase->contact->name], $purchase->contact_id, ['class' => 'form-control', 'placeholder' => __('messages.please_select') , 'required', 'id' => 'supplier_id']); !!}
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-default bg-white btn-flat add_new_supplier" data-name=""><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                  </span>
                </div>
              </div>
            </div>

            <div class="col-sm-3">
              <div class="form-group">
                {!! Form::label('ref_no', __('Bill No') . '*') !!}
                {!! Form::text('ref_no', $purchase->ref_no, ['class' => 'form-control', 'required','readonly']); !!}
              </div>
            </div>
    
            <div class="col-sm-3">
				<div class="form-group">
					{!! Form::label('status', __('purchase.status').':') !!}
					{!! Form::text('status', ucfirst($purchase->status), ['class' => 'form-control', 'readonly']); !!}
				</div>
			</div>

            <div class="col-sm-3">
              <div class="form-group">
                {!! Form::label('transaction_date', __('lang_v1.order_date') . ':*') !!}
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </span>
                  {!! Form::text('transaction_date', @format_datetime($purchase->transaction_date), ['class' => 'form-control', 'readonly', 'required']); !!}
                </div>
              </div>
            </div>
        </div>
    @endcomponent

    @component('components.widget', ['class' => 'box-primary'])
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="fa fa-search"></i>
                  </span>
                  {!! Form::text('search_product', null, ['class' => 'form-control mousetrap', 'id' => 'search_product', 'placeholder' => __('lang_v1.search_product_placeholder'), 'autofocus']); !!}
                </div>
              </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
              @include('purchase.partials.edit_receive_stock_entry_row', ['is_purchase_order' => true])

              <hr/>
              <div class="pull-right col-md-5">
                <table class="pull-right col-md-12">
                  <tr>
                    <th class="col-md-7 text-right">@lang( 'purchase.net_total_amount' ):</th>
                    <td class="col-md-5 text-left">
                      <span id="total_subtotal" class="display_currency"></span>
                      <!-- This is total before purchase tax-->
                      <input type="hidden" id="total_subtotal_input" value="{{ $purchase->final_total }}"  name="total_before_tax">
			            <input type="hidden" id="row_count" value="{{ count($purchase->purchase_lines) }}">
                    </td>
                  </tr>
                </table>
              </div>

            </div>
        </div>
    @endcomponent

    @component('components.widget', ['class' => 'box-solid'])
        <div class="row">
            <div class="col-sm-12">
                <table class="table">
                  <tr>
                    <td colspan="4">
                      <div class="form-group">
                        {!! Form::label('additional_notes',__('purchase.additional_notes')) !!}
                        {!! Form::textarea('additional_notes', $purchase->additional_notes, ['class' => 'form-control', 'rows' => 3]); !!}
                      </div>
                    </td>
                  </tr>

                </table>
            </div>
        </div>
    @endcomponent
  
    <div class="row">
        <div class="col-sm-12">
            <button type="button" class="submit_purchase_IR_form btn btn-primary pull-right btn-flat" data-msg="Revert" data-is-confirm="true" data-status="revert">{{ __('Revert P.O.') }}</button>
            <button type="button" class="submit_purchase_IR_form btn btn-success pull-right btn-flat mr-8" data-msg="Generate" data-is-confirm="true" data-status="completed">{{ __('Generate P.O.') }}</button>
        </div>
    </div>
{!! Form::close() !!}
</section>
<!-- /.content -->
<!-- quick product modal -->
<div class="modal fade quick_add_product_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"></div>
<div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  @include('contact.create', ['quick_add' => true])
</div>

@endsection

@section('javascript')
    <script>
        jQuery('body').on('click','.submit_purchase_IR_form',function(){
            $("#status").val($(this).data('status'));
        });
    </script>
  <script src="{{ asset('js/purchase.js?v=' . $asset_v) }}"></script>
  <script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
  <script type="text/javascript">
    $(document).ready( function(){
      update_table_total();
      update_grand_total();
      __page_leave_confirmation('#add_purchase_form');
        $('#shipping_documents').fileinput({
            showUpload: false,
            showPreview: false,
            browseLabel: LANG.file_browse_label,
            removeLabel: LANG.remove,
        });
    });
  </script>
  @include('purchase.partials.keyboard_shortcuts')
@endsection
