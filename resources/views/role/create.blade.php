@extends('layouts.app')
@section('title', __('role.add_role'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('role.add_role')</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        @php
            $pos_settings = !empty(session('business.pos_settings')) ? json_decode(session('business.pos_settings'), true) : [];
        @endphp
        @component('components.widget', ['class' => 'box-primary'])
            {!! Form::open([
                'url' => action([\App\Http\Controllers\RoleController::class, 'store']),
                'method' => 'post',
                'id' => 'role_add_form',
            ]) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('name', __('user.role_name') . ':*') !!}
                        {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __('user.role_name')]) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label>@lang('user.permissions'):</label>
                </div>
            </div>

            {{-- <div class="row check_group">
          <div class="col-md-1">
            <h4>@lang( 'lang_v1.others' )</h4>
          </div>
          <div class="col-md-2">
            <div class="checkbox">
                <label>
                  <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
                </label>
              </div>
          </div>
          <div class="col-md-9">
              @if (in_array('service_staff', $enabled_modules))
                <div class="col-md-12">
                  <div class="checkbox">
                    <label>
                      {!! Form::checkbox('is_service_staff', 1, false, 
                      [ 'class' => 'input-icheck']); !!} {{ __( 'restaurant.service_staff' ) }}
                    </label>
                    @show_tooltip(__('restaurant.tooltip_service_staff'))
                  </div>
                </div>
              @endif

              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'view_export_buttons', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_export_buttons' ) }}
                  </label>
                </div>
              </div>
          </div>
        </div>
        <hr> --}}

            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('role.user')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'user.view', false, ['class' => 'input-icheck']) !!} {{ __('role.user.view') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'user.create', false, ['class' => 'input-icheck']) !!} {{ __('role.user.create') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'user.update', false, ['class' => 'input-icheck']) !!} {{ __('role.user.update') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'user.delete', false, ['class' => 'input-icheck']) !!} {{ __('role.user.delete') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('user.roles')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'roles.view', false, ['class' => 'input-icheck']) !!} {{ __('lang_v1.view_role') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'roles.create', false, ['class' => 'input-icheck']) !!} {{ __('role.add_role') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'roles.update', false, ['class' => 'input-icheck']) !!} {{ __('role.edit_role') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'roles.delete', false, ['class' => 'input-icheck']) !!} {{ __('lang_v1.delete_role') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            @if (in_array('stock_adjustment', $enabled_modules))
              <div class="row check_group">
                  <div class="col-md-1">
                      <h4>{{ __('Update Stock') }}</h4>
                  </div>
                  <div class="col-md-2">
                      <div class="checkbox">
                          <label>
                              <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                          </label>
                      </div>
                  </div>
                  <div class="col-md-9">
                      <div class="col-md-12">
                          <div class="checkbox">
                              <label>
                                  {!! Form::checkbox('permissions[]', 'stock.create',false, [
                                      'class' => 'input-icheck',
                                  ]) !!} {{ __('View & Add Update Stock') }}
                              </label>
                          </div>
                      </div>
                  </div>
              </div>
              <hr>
            @endif 
            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('role.supplier')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="radio-group">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::radio('radio_option[supplier_view]', 'supplier.view', false, ['class' => 'input-icheck']) !!} {{ __('lang_v1.view_all_supplier') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::radio('radio_option[supplier_view]', 'supplier.view_own', false, ['class' => 'input-icheck']) !!} {{ __('lang_v1.view_own_supplier') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'supplier.create', false, ['class' => 'input-icheck']) !!} {{ __('role.supplier.create') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'supplier.update', false, ['class' => 'input-icheck']) !!} {{ __('role.supplier.update') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'supplier.delete', false, ['class' => 'input-icheck']) !!} {{ __('role.supplier.delete') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('role.customer') @show_tooltip(__('lang_v1.customer_permissions_tooltip'))</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::radio('radio_option[customer_view]', 'customer.view', false, ['class' => 'input-icheck']) !!} {{ __('lang_v1.view_all_customer') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::radio('radio_option[customer_view]', 'customer.view_own', false, ['class' => 'input-icheck']) !!} {{ __('lang_v1.view_own_customer') }}
                            </label>
                        </div>
                        <hr>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::radio('radio_option[customer_view_by_sell]', 'customer_with_no_sell_one_month', false, [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('lang_v1.customer_with_no_sell_one_month') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::radio('radio_option[customer_view_by_sell]', 'customer_with_no_sell_three_month', false, [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('lang_v1.customer_with_no_sell_three_month') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::radio('radio_option[customer_view_by_sell]', 'customer_with_no_sell_six_month', false, [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('lang_v1.customer_with_no_sell_six_month') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::radio('radio_option[customer_view_by_sell]', 'customer_with_no_sell_one_year', false, [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('lang_v1.customer_with_no_sell_one_year') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::radio('radio_option[customer_view_by_sell]', 'customer_irrespective_of_sell', false, [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('lang_v1.customer_irrespective_of_sell') }}
                            </label>
                        </div>
                        <hr>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'customer.create', false, ['class' => 'input-icheck']) !!} {{ __('role.customer.create') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'customer.update', false, ['class' => 'input-icheck']) !!} {{ __('role.customer.update') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'customer.delete', false, ['class' => 'input-icheck']) !!} {{ __('role.customer.delete') }}
                            </label>
                        </div>
                        <hr>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'draft_customer.view', false, ['class' => 'input-icheck']) !!} {{ __(' View Draft-Customer') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'draft_customer.create', false, ['class' => 'input-icheck']) !!} {{ __(' Add Draft-Customer') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'draft_customer.update', false, ['class' => 'input-icheck']) !!} {{ __(' Edit Draft-Customer') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'draft_customer.delete', false, ['class' => 'input-icheck']) !!} {{ __(' Delete Draft-Customer') }}
                            </label>
                        </div>
                        <hr>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'customer_location.view', false, ['class' => 'input-icheck']) !!} {{ __(' View Customer Location') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('business.product')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'product.view', false, ['class' => 'input-icheck']) !!} {{ __('role.product.view') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'product.create', false, ['class' => 'input-icheck']) !!} {{ __('role.product.create') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'product.update', false, ['class' => 'input-icheck']) !!} {{ __('role.product.update') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'product.delete', false, ['class' => 'input-icheck']) !!} {{ __('role.product.delete') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'product.opening_stock', false, ['class' => 'input-icheck']) !!} {{ __('lang_v1.add_opening_stock') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'view_purchase_price', false, ['class' => 'input-icheck']) !!}
                                {{ __('lang_v1.view_purchase_price') }}
                            </label>
                            @show_tooltip(__('lang_v1.view_purchase_price_tooltip'))
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            {{-- Sanjay --}}
            {{-- @if (in_array('purchases', $enabled_modules) || in_array('stock_adjustment', $enabled_modules))
                <div class="row check_group">
                    <div class="col-md-1">
                        <h4>@lang('role.purchase')</h4>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::radio('radio_option[purchase_view]', 'purchase.view', false, ['class' => 'input-icheck']) !!} {{ __('lang_v1.view_all_purchase_n_stock_adjustment') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::radio('radio_option[purchase_view]', 'view_own_purchase', false, ['class' => 'input-icheck']) !!}
                                    {{ __('lang_v1.view_own_purchase_n_stock_adjustment') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'purchase.create', false, ['class' => 'input-icheck']) !!} {{ __('role.purchase.create') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'purchase.update', false, ['class' => 'input-icheck']) !!} {{ __('role.purchase.update') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'purchase.delete', false, ['class' => 'input-icheck']) !!} {{ __('role.purchase.delete') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'purchase.payments', false, ['class' => 'input-icheck']) !!}
                                    {{ __('lang_v1.add_purchase_payment') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'edit_purchase_payment', false, ['class' => 'input-icheck']) !!}
                                    {{ __('lang_v1.edit_purchase_payment') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'delete_purchase_payment', false, ['class' => 'input-icheck']) !!}
                                    {{ __('lang_v1.delete_purchase_payment') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'purchase.update_status', false, ['class' => 'input-icheck']) !!}
                                    {{ __('lang_v1.update_status') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            @endif --}}
            {{-- End Sanjay --}}

            {{-- @if (!empty($common_settings['enable_purchase_requisition']))
          <div class="row check_group">
            <div class="col-md-1">
              <h4>@lang( 'lang_v1.purchase_requisition' )</h4>
            </div>
            <div class="col-md-2">
              <div class="checkbox">
                  <label>
                    <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
                  </label>
                </div>
            </div>
            <div class="col-md-9">
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::radio('radio_option[purchase_requisition_view]', 'purchase_requisition.view_all', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_all_purchase_requisition' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::radio('radio_option[purchase_requisition_view]', 'purchase_requisition.view_own', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_own_purchase_requisition' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'purchase_requisition.create', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.create_purchase_requisition' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'purchase_requisition.delete', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.delete_purchase_requisition' ) }}
                  </label>
                </div>
              </div>

            </div>
          </div>
          <hr>
        @endif --}}

            {{-- @if (!empty($common_settings['enable_purchase_order']))
        <div class="row check_group">
          <div class="col-md-1">
            <h4>@lang( 'lang_v1.purchase_order' )</h4>
          </div>
          <div class="col-md-2">
            <div class="checkbox">
                <label>
                  <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
                </label>
              </div>
          </div>
          <div class="col-md-9">
            <div class="col-md-12">
              <div class="checkbox">
                <label>
                  {!! Form::radio('radio_option[purchase_order_view]', 'purchase_order.view_all', false, 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_all_purchase_order' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-12">
              <div class="checkbox">
                <label>
                  {!! Form::radio('radio_option[purchase_order_view]', 'purchase_order.view_own', false, 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_own_purchase_order' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-12">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'purchase_order.create', false, 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.create_purchase_order' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-12">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'purchase_order.update', false, 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.edit_purchase_order' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-12">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'purchase_order.delete', false, 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.delete_purchase_order' ) }}
                </label>
              </div>
            </div>

          </div>
        </div>
        <hr>
        @endif  --}}

            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('lang_v1.purchase_order')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::radio('radio_option[purchase_order_view]', 'purchase_order.view_all', false, [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('lang_v1.view_all_purchase_order') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::radio('radio_option[purchase_order_view]', 'purchase_order.view_own', false, [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('lang_v1.view_own_purchase_order') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'purchase_order.create', false, ['class' => 'input-icheck']) !!} {{ __('lang_v1.create_purchase_order') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'purchase_order.update', false, ['class' => 'input-icheck']) !!} {{ __('lang_v1.edit_purchase_order') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'purchase_order.delete', false, ['class' => 'input-icheck']) !!} {{ __('lang_v1.delete_purchase_order') }}
                            </label>
                        </div>
                    </div>

                </div>
            </div>
            <hr>
            <div class="row check_group">
            <div class="col-md-1">
              <h4>@lang( 'Orders (POS)' ) @show_tooltip(__('lang_v1.sell_permissions_tooltip'))</h4>
            </div>
            <div class="col-md-2">
              <div class="checkbox">
                  <label>
                    <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                  </label>
                </div>
            </div>
            <div class="col-md-9">
              @if (in_array('add_sale', $enabled_modules))
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'direct_sell_pos.view', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'View Order ( POS )' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'direct_sell_pos.create', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Create Order ( POS )' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'direct_sell_pos.update', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Update Order ( POS )' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'direct_sell_pos.delete', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'Delete Order ( POS )' ) }}
                  </label>
                </div>
              </div>
              @endif
            </div>
            </div>
            <hr>
            <div class="row check_group">
              <div class="col-md-1">
                <h4>@lang( 'Orders (SP)' ) @show_tooltip(__('lang_v1.sell_permissions_tooltip'))</h4>
              </div>
              <div class="col-md-2">
                <div class="checkbox">
                    <label>
                      <input type="checkbox" class="check_all input-icheck" > {{ __( 'Select All' ) }}
                    </label>
                  </div>
              </div>
              <div class="col-md-9">
                @if (in_array('add_sale', $enabled_modules))
                <div class="col-md-12">
                  <div class="checkbox">
                    <label>
                      {!! Form::checkbox('permissions[]', 'direct_sell_sp.view', false, 
                      [ 'class' => 'input-icheck']); !!} {{ __( 'View Order ( SP )' ) }}
                    </label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="checkbox">
                    <label>
                      {!! Form::checkbox('permissions[]', 'direct_sell_sp.create', false, 
                      [ 'class' => 'input-icheck']); !!} {{ __( 'Create Order ( SP )' ) }}
                    </label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="checkbox">
                    <label>
                      {!! Form::checkbox('permissions[]', 'direct_sell_sp.update', false, 
                      [ 'class' => 'input-icheck']); !!} {{ __( 'Update Order ( SP )' ) }}
                    </label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="checkbox">
                    <label>
                      {!! Form::checkbox('permissions[]', 'direct_sell_sp.delete', false, 
                      [ 'class' => 'input-icheck']); !!} {{ __( 'Delete Order ( SP )' ) }}
                    </label>
                  </div>
                </div>
                @endif
              </div>
              </div>
              <hr>
            {{-- <div class="row check_group">
            <div class="col-md-1">
                <h4>@lang( 'sale.pos_sale' )</h4>
            </div>
            <div class="col-md-2">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
                    </label>
                </div>
            </div>
            <div class="col-md-9">
            @if (in_array('pos_sale', $enabled_modules))
                <div class="col-md-12">
                    <div class="checkbox">
                      <label>
                        {!! Form::checkbox('permissions[]', 'sell.view', false, 
                        [ 'class' => 'input-icheck']); !!} {{ __( 'role.sell.view' ) }}
                      </label>
                    </div>
                </div>
                <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'sell.create', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'role.sell.create' ) }}
                  </label>
                </div>
              </div>
                @endif
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'sell.update', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'role.sell.update' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'sell.delete', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'role.sell.delete' ) }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'edit_product_price_from_pos_screen', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.edit_product_price_from_pos_screen') }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'edit_product_discount_from_pos_screen', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.edit_product_discount_from_pos_screen') }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'edit_pos_payment', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.add_edit_payment') }}
                  </label>
                </div>
              </div>
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'print_invoice', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.print_invoice') }}
                  </label>
                </div>
              </div>

            </div>
        </div> --}}
            {{-- <hr>
        <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'sale.sale' ) @show_tooltip(__('lang_v1.sell_permissions_tooltip'))</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
          @if (in_array('add_sale', $enabled_modules))
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[sell_view]', 'direct_sell.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_all_sale' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[sell_view]', 'view_own_sell_only', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_own_sell_only' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'view_paid_sells_only', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_paid_sells_only' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'view_due_sells_only', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_due_sells_only' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'view_partial_sells_only', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_partially_paid_sells_only' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'view_overdue_sells_only', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_overdue_sells_only' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'direct_sell.access', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.add_sell' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'direct_sell.update', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.update_sale' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'direct_sell.delete', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.delete_sell' ) }}
              </label>
            </div>
          </div>
          @endif
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'view_commission_agent_sell', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_commission_agent_sell' ) }}
              </label>
            </div>
          </div>

          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'sell.payments', false, ['class' => 'input-icheck']); !!}
                {{ __('lang_v1.add_sell_payment') }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'edit_sell_payment', false, ['class' => 'input-icheck']); !!}
                {{ __('lang_v1.edit_sell_payment') }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'delete_sell_payment', false, ['class' => 'input-icheck']); !!}
                {{ __('lang_v1.delete_sell_payment') }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'edit_product_price_from_sale_screen', false, ['class' => 'input-icheck']); !!}
                {{ __('lang_v1.edit_product_price_from_sale_screen') }}
              </label>
            </div>
          </div>
          
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'edit_product_discount_from_sale_screen', false, ['class' => 'input-icheck']); !!}
                {{ __('lang_v1.edit_product_discount_from_sale_screen') }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'discount.access', false, ['class' => 'input-icheck']); !!}
                {{ __('lang_v1.discount.access') }}
              </label>
            </div>
          </div>
          @if (in_array('types_of_service', $enabled_modules))
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'access_types_of_service', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.access_types_of_service' ) }}
              </label>
            </div>
          </div>
          @endif
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'access_sell_return', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.access_all_sell_return' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'access_own_sell_return', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.access_own_sell_return' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'edit_invoice_number', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.add_edit_invoice_number' ) }}
              </label>
            </div>
          </div>

        </div>
        </div>
        <hr> --}}
            {{-- @if (!empty($pos_settings['enable_sales_order']))
        <div class="row check_group">
          <div class="col-md-1">
            <h4>@lang( 'lang_v1.sales_order' )</h4>
          </div>
          <div class="col-md-2">
            <div class="checkbox">
                <label>
                  <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
                </label>
              </div>
          </div>
          <div class="col-md-9">
            <div class="col-md-12">
              <div class="checkbox">
                <label>
                  {!! Form::radio('radio_option[so_view]', 'so.view_all', false, 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_all_so' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-12">
              <div class="checkbox">
                <label>
                  {!! Form::radio('radio_option[so_view]', 'so.view_own', false, 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_own_so' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-12">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'so.create', false, 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.create_so' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-12">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'so.update', false, 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.edit_so' ) }}
                </label>
              </div>
            </div>
            <div class="col-md-12">
              <div class="checkbox">
                <label>
                  {!! Form::checkbox('permissions[]', 'so.delete', false, 
                  [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.delete_so' ) }}
                </label>
              </div>
            </div>

          </div>
        </div>
        <hr>
      @endif --}}
            {{-- <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'sale.draft' )</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
        <div class="checkbox">
          <label>
            {!! Form::radio('radio_option[draft_view]', 'draft.view_all', false, 
            [ 'class' => 'input-icheck']) !!} {{ __( 'lang_v1.view_all_drafts' ) }}
          </label>
        </div>
      </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[draft_view]', 'draft.view_own', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_own_drafts' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'draft.update', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.edit_draft' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'draft.delete', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.delete_draft' ) }}
              </label>
            </div>
          </div>

        </div>
      </div>
      <hr>
      <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'lang_v1.quotation' )</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
        <div class="checkbox">
          <label>
            {!! Form::radio('radio_option[quotation_view]', 'quotation.view_all', false, 
            [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_all_quotations' ) }}
          </label>
        </div>
      </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[quotation_view]', 'quotation.view_own', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_own_quotations' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'quotation.update', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.edit_quotation' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'quotation.delete', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.delete_quotation' ) }}
              </label>
            </div>
          </div>

        </div>
      </div>
      <hr>
      <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'lang_v1.shipments' )</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
            <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::radio('radio_option[shipping_view]', 'access_shipping', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.access_all_shipments') }}
                  </label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::radio('radio_option[shipping_view]', 'access_own_shipping', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.access_own_shipping') }}
                  </label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'access_pending_shipments_only', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.access_pending_shipments_only') }}
                  </label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'access_commission_agent_shipping', false, ['class' => 'input-icheck']); !!}
                    {{ __('lang_v1.access_commission_agent_shipping') }}
                  </label>
                </div>
            </div>
        </div>
    </div>
    <hr> --}}
            {{-- <div class="row check_group">
      <div class="col-md-1">
        <h4>@lang( 'cash_register.cash_register' )</h4>
      </div>
      <div class="col-md-2">
        <div class="checkbox">
            <label>
              <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
            </label>
          </div>
      </div>
      <div class="col-md-9">
        <div class="col-md-12">
          <div class="checkbox">
            <label>
              {!! Form::checkbox('permissions[]', 'view_cash_register', false, 
              [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_cash_register' ) }}
            </label>
          </div>
        </div>
        <div class="col-md-12">
          <div class="checkbox">
            <label>
              {!! Form::checkbox('permissions[]', 'close_cash_register', false, 
              [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.close_cash_register' ) }}
            </label>
          </div>
        </div>
      </div>
      </div>
        <hr> --}}

            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('role.brand')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'brand.view', false, ['class' => 'input-icheck']) !!} {{ __('role.brand.view') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'brand.create', false, ['class' => 'input-icheck']) !!} {{ __('role.brand.create') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'brand.update', false, ['class' => 'input-icheck']) !!} {{ __('role.brand.update') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'brand.delete', false, ['class' => 'input-icheck']) !!} {{ __('role.brand.delete') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>

            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('Orders')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'order.view', false, ['class' => 'input-icheck']) !!} {{ __('View Order') }}
                            </label>
                        </div>
                    </div>

                </div>
            </div>
            <hr>
            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('role.tax_rate')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'tax_rate.view', false, ['class' => 'input-icheck']) !!} {{ __('role.tax_rate.view') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'tax_rate.create', false, ['class' => 'input-icheck']) !!} {{ __('role.tax_rate.create') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'tax_rate.update', false, ['class' => 'input-icheck']) !!} {{ __('role.tax_rate.update') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'tax_rate.delete', false, ['class' => 'input-icheck']) !!} {{ __('role.tax_rate.delete') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('role.unit')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'unit.view', false, ['class' => 'input-icheck']) !!} {{ __('role.unit.view') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'unit.create', false, ['class' => 'input-icheck']) !!} {{ __('role.unit.create') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'unit.update', false, ['class' => 'input-icheck']) !!} {{ __('role.unit.update') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'unit.delete', false, ['class' => 'input-icheck']) !!} {{ __('role.unit.delete') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('category.category')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'category.view', false, ['class' => 'input-icheck']) !!} {{ __('role.category.view') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'category.create', false, ['class' => 'input-icheck']) !!} {{ __('role.category.create') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'category.update', false, ['class' => 'input-icheck']) !!} {{ __('role.category.update') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'category.delete', false, ['class' => 'input-icheck']) !!} {{ __('role.category.delete') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>

            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('Application')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('Select All') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'car.view', false, ['class' => 'input-icheck']) !!} {{ __('View Car') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'car.create', false, ['class' => 'input-icheck']) !!} {{ __('Add Car') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'car.update', false, ['class' => 'input-icheck']) !!} {{ __('Edit Car') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'car.delete', false, ['class' => 'input-icheck']) !!} {{ __('Delete Car') }}
                            </label>
                        </div>
                    </div>


                </div>
            </div>
            <hr>

            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('Application ( Manage Route )')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('Select All') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'route.view', false, ['class' => 'input-icheck']) !!} {{ __('View Route') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'route.create', false, ['class' => 'input-icheck']) !!} {{ __('Add Route') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'route.update', false, ['class' => 'input-icheck']) !!} {{ __('Edit Route') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'route.delete', false, ['class' => 'input-icheck']) !!} {{ __('Delete Route') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>

            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('Manage Price Level')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('Select All') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'price_level.view', false, ['class' => 'input-icheck']) !!} {{ __('View Price Level') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'price_level.create', false, ['class' => 'input-icheck']) !!} {{ __('Add Price Level') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'price_level.update', false, ['class' => 'input-icheck']) !!} {{ __('Edit Price Level') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'price_level.delete', false, ['class' => 'input-icheck']) !!} {{ __('Delete Price Level') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('Credit Memo List')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('Select All') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'access_sell_return', false, ['class' => 'input-icheck']) !!} {{ __('View Credit Memo') }}
                            </label>
                        </div>
                    </div>
                    {{-- <div class="col-md-12">
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('permissions[]', 'purchase_return.view', false, ['class' => 'input-icheck']) !!} {{ __('View Credit Memo') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('permissions[]', 'purchase_return.create', false, ['class' => 'input-icheck']) !!} {{ __('Add Credit Memo') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('permissions[]', 'purchase_return.update', false, ['class' => 'input-icheck']) !!} {{ __('Edit Credit Memo') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('permissions[]', 'purchase_return.delete', false, ['class' => 'input-icheck']) !!} {{ __('Delete Credit Memo') }}
                        </label>
                    </div>
                </div> --}}
                </div>
            </div>
            <hr>
            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('Parameter Setting')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('Select All') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'state.view', false, ['class' => 'input-icheck']) !!} {{ __('View State') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'state.create', false, ['class' => 'input-icheck']) !!} {{ __('Add State') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'state.update', false, ['class' => 'input-icheck']) !!} {{ __('Edit State') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'state.delete', false, ['class' => 'input-icheck']) !!} {{ __('Delete State') }}
                            </label>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'city.view', false, ['class' => 'input-icheck']) !!} {{ __('View City') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'city.create', false, ['class' => 'input-icheck']) !!} {{ __('Add City') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'city.update', false, ['class' => 'input-icheck']) !!} {{ __('Edit City') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'city.delete', false, ['class' => 'input-icheck']) !!} {{ __('Delete City') }}
                            </label>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'zipcode.view', false, ['class' => 'input-icheck']) !!} {{ __('View Zipcode') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'zipcode.create', false, ['class' => 'input-icheck']) !!} {{ __('Add Zipcode') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'zipcode.update', false, ['class' => 'input-icheck']) !!} {{ __('Edit Zipcode') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'zipcode.delete', false, ['class' => 'input-icheck']) !!} {{ __('Delete Zipcode') }}
                            </label>
                        </div>
                    </div>


                </div>
            </div>
            <hr>

            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('lang_v1.expense')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('Select All') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">


                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'expense_category.view', false, ['class' => 'input-icheck']) !!} {{ __('View Expense Category') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'expense_category.create', false, ['class' => 'input-icheck']) !!} {{ __('Add Expense Category') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'expense_category.update', false, ['class' => 'input-icheck']) !!} {{ __('Edit Expense Category') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'expense_category.delete', false, ['class' => 'input-icheck']) !!} {{ __('Delete Expense Category') }}
                            </label>
                        </div>
                        <hr>
                    </div>

                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'manage_expense.view', false, ['class' => 'input-icheck']) !!} {{ __('View Expense') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'manage_expense.create', false, ['class' => 'input-icheck']) !!} {{ __('Add Expense') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'manage_expense.update', false, ['class' => 'input-icheck']) !!} {{ __('Edit Expense') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'manage_expense.delete', false, ['class' => 'input-icheck']) !!} {{ __('Delete Expense') }}
                            </label>
                        </div>

                    </div>




                </div>
            </div>
            <hr>

            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('tax.tax')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('Select All') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'tax.view', false, ['class' => 'input-icheck']) !!} {{ __('View Tax') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'tax.create', false, ['class' => 'input-icheck']) !!} {{ __('Add Tax') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'tax.update', false, ['class' => 'input-icheck']) !!} {{ __('Edit Tax') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'tax.delete', false, ['class' => 'input-icheck']) !!} {{ __('Delete Tax') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>

            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('Driver')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('Select All') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'driver.view', false, ['class' => 'input-icheck']) !!} {{ __('View Driver') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'driver.create', false, ['class' => 'input-icheck']) !!} {{ __('Add Driver') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'driver.update', false, ['class' => 'input-icheck']) !!} {{ __('Edit Driver') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'driver.delete', false, ['class' => 'input-icheck']) !!} {{ __('Delete Driver') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>

            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('Vendor')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('Select All') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'vendor.view', false, ['class' => 'input-icheck']) !!} {{ __('View Vendor') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'vendor.create', false, ['class' => 'input-icheck']) !!} {{ __('Add Vendor') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'vendor.update', false, ['class' => 'input-icheck']) !!} {{ __('Edit Vendor') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'vendor.delete', false, ['class' => 'input-icheck']) !!} {{ __('Delete Vendor') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row check_group">
              <div class="col-md-1">
                  <h4>@lang('Write Cheque')</h4>
              </div>
              <div class="col-md-2">
                  <div class="checkbox">
                      <label>
                          <input type="checkbox" class="check_all input-icheck"> {{ __('Select All') }}
                      </label>
                  </div>
              </div>
              <div class="col-md-9">
                  <div class="col-md-12">
                      <div class="checkbox">
                          <label>
                              {!! Form::checkbox('permissions[]', 'write_cheque.view', false, ['class' => 'input-icheck']) !!} {{ __('View Write Cheque') }}
                          </label>
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="checkbox">
                          <label>
                              {!! Form::checkbox('permissions[]', 'write_cheque.create', false, ['class' => 'input-icheck']) !!} {{ __('Add Write Cheque') }}
                          </label>
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="checkbox">
                          <label>
                              {!! Form::checkbox('permissions[]', 'write_cheque.update', false, ['class' => 'input-icheck']) !!} {{ __('Edit Write Cheque') }}
                          </label>
                      </div>
                  </div>
                  <div class="col-md-12">
                      <div class="checkbox">
                          <label>
                              {!! Form::checkbox('permissions[]', 'write_cheque.delete', false, ['class' => 'input-icheck']) !!} {{ __('Delete Write Cheque') }}
                          </label>
                      </div>
                  </div>
              </div>
          </div>
          <hr>
            {{-- <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'role.report' )</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
            @if (in_array('purchases', $enabled_modules) || in_array('add_sale', $enabled_modules) || in_array('pos_sale', $enabled_modules))
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'purchase_n_sell_report.view', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __( 'role.purchase_n_sell_report.view' ) }}
                  </label>
                </div>
              </div>
            @endif
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'tax_report.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.tax_report.view' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'contacts_report.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.contacts_report.view' ) }}
              </label>
            </div>
          </div>
          @if (in_array('expenses', $enabled_modules))
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'expense_report.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.expense_report.view' ) }}
              </label>
            </div>
          </div>
          @endif
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'profit_loss_report.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.profit_loss_report.view' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'stock_report.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.stock_report.view' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'trending_product_report.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.trending_product_report.view' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'register_report.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.register_report.view' ) }}
              </label>
            </div>
          </div>

          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'sales_representative.view', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.sales_representative.view' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'view_product_stock_value', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.view_product_stock_value' ) }}
              </label>
            </div>
          </div> 

        </div>
        </div>
        <hr> --}}
            <div class="row check_group">
                <div class="col-md-1">
                    <h4>@lang('role.settings')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'business_settings.access', false, ['class' => 'input-icheck']) !!} {{ __('role.business_settings.access') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'barcode_settings.access', false, ['class' => 'input-icheck']) !!} {{ __('role.barcode_settings.access') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'invoice_settings.access', false, ['class' => 'input-icheck']) !!} {{ __('role.invoice_settings.access') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'access_printers', false, ['class' => 'input-icheck']) !!}
                                {{ __('lang_v1.access_printers') }}
                            </label>
                        </div>
                    </div>
                </div>
                {{-- </div>
        @if (in_array('expenses', $enabled_modules))
            <hr>
            <div class="row check_group">
                <div class="col-md-1">
                  <h4>@lang( 'lang_v1.expense' )</h4>
                </div>
                <div class="col-md-2">
                  <div class="checkbox">
                      <label>
                        <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
                      </label>
                    </div>
                </div>
                <div class="col-md-9">
                  <div class="col-md-12">
                        <div class="checkbox">
                          <label>
                            {!! Form::radio('radio_option[expense_view]', 'all_expense.access', false, 
                            [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.access_all_expense' ) }}
                          </label>
                        </div>
                      </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                      <label>
                        {!! Form::radio('radio_option[expense_view]', 'view_own_expense', false,['class' => 'input-icheck']); !!}
                        {{ __('lang_v1.view_own_expense') }}
                      </label>
                        </div>
                  </div>
                  <div class="col-md-12">
                    <div class="checkbox">
                      <label>
                        {!! Form::checkbox('permissions[]', 'expense.add', false, 
                        [ 'class' => 'input-icheck']); !!} {{ __( 'expense.add_expense' ) }}
                      </label>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="checkbox">
                      <label>
                        {!! Form::checkbox('permissions[]', 'expense.edit', false, 
                        [ 'class' => 'input-icheck']); !!} {{ __( 'expense.edit_expense' ) }}
                      </label>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="checkbox">
                      <label>
                        {!! Form::checkbox('permissions[]', 'expense.delete', false, 
                        [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.delete_expense' ) }}
                      </label>
                    </div>
                  </div>
                </div>
            </div>
        @endif
        <hr> --}}
                {{-- <div class="row check_group">
        <div class="col-md-3">
          <h4>@lang( 'role.dashboard' ) @show_tooltip(__('tooltip.dashboard_permission'))</h4>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'dashboard.data', true, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'role.dashboard.data' ) }}
              </label>
            </div>
          </div>
        </div>
        </div>
        <hr>
        <div class="row check_group">
        <div class="col-md-3">
          <h4>@lang( 'account.account' )</h4>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'account.access', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.access_accounts' ) }}
              </label>
            </div>
          </div>

          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'edit_account_transaction', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.edit_account_transaction' ) }}
              </label>
            </div>
          </div>

          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'delete_account_transaction', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'lang_v1.delete_account_transaction' ) }}
              </label>
            </div>
          </div>
        </div>
        </div>
        <hr> --}}
                {{-- @if (in_array('booking', $enabled_modules))
        <div class="row check_group">
        <div class="col-md-1">
          <h4>@lang( 'restaurant.bookings' )</h4>
        </div>
        <div class="col-md-2">
          <div class="checkbox">
              <label>
                <input type="checkbox" class="check_all input-icheck" > {{ __( 'role.select_all' ) }}
              </label>
            </div>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[bookings_view]', 'crud_all_bookings', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'restaurant.add_edit_view_all_booking' ) }}
              </label>
            </div>
          </div>
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::radio('radio_option[bookings_view]', 'crud_own_bookings', false, 
                [ 'class' => 'input-icheck']); !!} {{ __( 'restaurant.add_edit_view_own_booking' ) }}
              </label>
            </div>
          </div>
        </div>
        </div>
        <hr>
        @endif --}}
                {{-- <div class="row">
        <div class="col-md-3">
          <h4>@lang( 'lang_v1.access_selling_price_groups' )</h4>
        </div>
        <div class="col-md-9">
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('permissions[]', 'access_default_selling_price', true, 
                [ 'class' => 'input-icheck']); !!} {{ __('lang_v1.default_selling_price') }}
              </label>
            </div>
          </div>
          @if (count($selling_price_groups) > 0)
          @foreach ($selling_price_groups as $selling_price_group)
          <div class="col-md-12">
            <div class="checkbox">
              <label>
                {!! Form::checkbox('spg_permissions[]', 'selling_price_group.' . $selling_price_group->id, false, 
                [ 'class' => 'input-icheck']); !!} {{ $selling_price_group->name }}
              </label>
            </div>
          </div>
          @endforeach
          @endif
        </div>
        </div>
        @if (in_array('tables', $enabled_modules))
          <div class="row">
            <div class="col-md-3">
              <h4>@lang( 'restaurant.restaurant' )</h4>
            </div>
            <div class="col-md-9">
              <div class="col-md-12">
                <div class="checkbox">
                  <label>
                    {!! Form::checkbox('permissions[]', 'access_tables', false, 
                    [ 'class' => 'input-icheck']); !!} {{ __('lang_v1.access_tables') }}
                  </label>
                </div>
              </div>
            </div>
          </div>
        @endif --}}

                @include('role.partials.module_permissions')
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary pull-right">@lang('messages.save')</button>
                    </div>
                </div>

                {!! Form::close() !!}
            @endcomponent
    </section>
    <!-- /.content -->
@endsection
