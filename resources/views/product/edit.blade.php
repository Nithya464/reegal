@extends('layouts.app')
@section('title', __('product.edit_product'))

@section('content')
    {{-- <style>
    .myNewClass tr td{padding:10px 8px 20px!important;position: relative;}
    .myNewClass tr td label.error {
    position: absolute;
    bottom: -2px;
    left: 7px;
    font-size: 13px;
}
</style> --}}

<style>
    thead.thead-dark {
        font-size: 14px;
        background-color: #24394D !important;
        color: #FFFFFF !important;
        font-weight: 400;
    }
    #Table1 th, .table td {
        padding: 3px !important;
    }
    #Table1 tr th {
     vertical-align: middle;
    }
    .table>thead>tr>th{
        vertical-align: middle;
    }
    td.UnitType{
        width: 5%;
        text-align: center;
        font-size: 14px;
        color: #000 !important;
        font-weight: 400;
    }

    .btn:not(:disabled):not(.disabled) {
        cursor: pointer;
    }
    .modal-footer > :not(:last-child) {
        margin-right: 0.25rem;
    }
    .modal-footer > :not(:first-child) {
        margin-left: 0.25rem;
    }
    .btn-primary {
        border-color: #535BE2 !important;
        background-color: #666EE8 !important;
        color: #FFFFFF;
    }
    .round {
        border-radius: 1.5rem;
    }
    .pull-right {
        float: right;
    }
    .btn-danger {
        border-color: #FF394F !important;
        background-color: #FF4961 !important;
        color: #FFFFFF;
    }
    .btn-sm, .btn-group-sm > .btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1;
        border-radius: 0.21rem;
    }
    .btn {
        display: inline-block;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        user-select: none;
        border: 1px solid transparent;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        line-height: 1.25;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    /* .Qty .text-center{

    } */
</style>
    @php
        $is_image_required = !empty($common_settings['is_product_image_required']) && empty($product->image);
    @endphp

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('product.edit_product')</h1>
        <!-- <ol class="breadcrumb">
                                                                        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                                                                        <li class="active">Here</li>
                                                                    </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        {!! Form::open([
            'url' => action([\App\Http\Controllers\ProductController::class, 'update'], [$product->id]),
            'method' => 'PUT',
            'id' => 'product_add_form',
            'class' => 'product_form',
            'files' => true,
        ]) !!}
        <input type="hidden" id="product_id" value="{{ $product->id }}">

        @component('components.widget', ['class' => 'box-primary'])
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('category_id', __('product.category') . ':*') !!}
                                <div class="input-group">
                                    {!! Form::select('category_id', $categories, $product->category_id, [
                                        'placeholder' => __('messages.please_select'),
                                        'class' => 'form-control select2',
                                        'required',
                                    ]) !!}
                                    <span class="input-group-btn">
                                        <button type="button" @if (!auth()->user()->can('category.create')) disabled @endif
                                            class="btn btn-default bg-white btn-flat btn-modal"
                                            data-href="{{ action([\App\Http\Controllers\TaxonomyController::class, 'create'], ['type' => 'product', 'quick_add' => true]) }}"
                                            title="Create Category" data-container=".view_modal"><i
                                                class="fa fa-plus-circle text-primary fa-lg"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('sub_category_id', __('product.sub_category') . ':*') !!}
                                {!! Form::select('sub_category_id', $sub_categories, $product->sub_category_id, [
                                    'placeholder' => __('messages.please_select'),
                                    'class' => 'form-control select2',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('sku', __('product.sku') . ':*') !!} @show_tooltip(__('tooltip.sku'))
                                {!! Form::text('sku', $product->sku, [
                                    'class' => 'form-control',
                                    'placeholder' => __('product.sku'),
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('name', __('product.product_name') . ':*') !!}
                                {!! Form::text('name', $product->name, [
                                    'class' => 'form-control',
                                    'required',
                                    'placeholder' => __('product.product_name'),
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('contact_id', __('product.preferred_vendor') . ':*') !!}
                                {!! Form::select('contact_id', $vendors, $product->contact_id, [
                                    'placeholder' => __('messages.please_select'),
                                    'class' => 'form-control select2',
                                    'required',
                                ]) !!}
                            </div>
                        </div>



                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('brand_id', __('product.brand') . ':*') !!}
                                <div class="input-group">
                                    {!! Form::select('brand_id', $brands, $product->brand_id, [
                                        'placeholder' => __('messages.please_select'),
                                        'class' => 'form-control select2',
                                        'required',
                                    ]) !!}
                                    <span class="input-group-btn">
                                        <button type="button" @if (!auth()->user()->can('brand.create')) disabled @endif
                                            class="btn btn-default bg-white btn-flat btn-modal"
                                            data-href="{{ action([\App\Http\Controllers\BrandController::class, 'create'], ['quick_add' => true]) }}"
                                            title="@lang('brand.add_brand')" data-container=".view_modal"><i
                                                class="fa fa-plus-circle text-primary fa-lg"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('re_order_mark', __('product.re_order_mark') . ':') !!}
                                {!! Form::text('re_order_mark', $product->re_order_mark, [
                                    'class' => 'form-control',
                                    'placeholder' => __('product.re_order_mark'),
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <br>
                                <label>
                                    {!! Form::checkbox('is_ml_qty', 1, ($product->is_ml_qty==1)?true:false, [
                                        'class' => 'input-icheck',
                                        'id' => 'is_ml_qty',
                                    ]) !!} <strong>@lang('product.is_ml_qty')</strong>
                                </label>@show_tooltip(__('tooltip.is_ml_qty'))
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('ml_qty', __('product.ml_qty') . ':') !!}
                                {!! Form::text('ml_qty', $product->ml_qty, [
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <br>
                                <label>
                                    {!! Form::checkbox('is_weight', 1, ($product->is_weight==1)?true:false, [
                                        'class' => 'input-icheck',
                                        'id' => 'is_weight',
                                    ]) !!} <strong>@lang('product.is_weight')</strong>
                                </label>@show_tooltip(__('tooltip.is_weight'))
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('weight', __('product.weight') . ':') !!}
                                {!! Form::text('weight', $product->weight, [
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('commission_per', __('product.commission_per') . ':*') !!}
                                {!! Form::select(
                                    'commission_per',
                                    config('global.commission_per'),
                                    number_format($product->commission_per, 2, '.', ''),
                                    ['placeholder' => __('messages.please_select'), 'class' => 'form-control', 'required'],
                                ) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('stock', __('product.stock') . ':') !!}
                                {!! Form::text('stock', $product->stock, [
                                    'class' => 'form-control',
                                    'readonly',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('srp', __('product.srp') . ':*') !!}
                                {!! Form::text('srp', $product->srp, [
                                    'class' => 'form-control',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                        @php
                            $default_location = null;
                            if (count($business_locations) == 1) {
                                $default_location = array_key_first($business_locations->toArray());
                            }
                        @endphp

                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('product_locations', 'Location Wise Status:') !!} @show_tooltip(__('lang_v1.product_location_help'))
                                {!! Form::select('product_locations[]', $business_locations, $default_location, [
                                    'class' => 'form-control select2',
                                    'multiple',
                                    'id' => 'product_locations',
                                ]) !!}
                            </div>
                        </div>

                    </div>
                </div>


                <div class="col-md-4">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                {!! Form::label('image', __('lang_v1.product_image') . ':') !!}
                                {!! Form::file('image', [
                                    'id' => 'upload_image',
                                    'accept' => 'image/*',
                                    'required' => $is_image_required,
                                    'class' => 'upload-element',
                                ]) !!}
                                <small>
                                    <p class="help-block">@lang('purchase.max_file_size', ['size' => config('constants.document_size_limit') / 1000000]) <br> @lang('lang_v1.aspect_ratio_should_be_1_1')</p>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        @endcomponent



        @component('components.widget', ['class' => 'box-primary'])

        <div class="card-header">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <h4 class="card-title" style="margin-top: 5px">Packing Details</h4>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-6 text-right">
                        
                <div class="col-lg-9 col-md-9 col-sm-6 text-right">
                    <button type="button" class="btn btn-success buttonAnimation pull-right round box-shadow-1 btn-sm" data-animation="pulse" id="btnManagePrince" style="margin-left: 10px;" onclick="showManagePriceModal()">
                        Manage Price
                    </button>
                </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="managePriceModal" tabindex="-1" aria-labelledby="managePriceModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="min-width: 1250px !important;">
                <div class="modal-content">
                <div class="modal-header">
                    <h4 style="float: left" class="modal-title">Manage Price <span id="ProductIDNName" style="font-size: 14px; font-weight: bold">[ 87654321 - Test123 ]</span></h4>
                </div>
                <div class="modal-body">

                    <div class="row">

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover myNewClass">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col" class="text-center" rowspan="2">Create</th>
                                        <th scope="col" class="text-center" rowspan="2">Unit Type</th>
                                        <th scope="col" class="text-center" rowspan="2">Status Active?</th>
                                        <th scope="col" class="text-center" rowspan="2">Default</th>
                                        <th scope="col" class="text-center" rowspan="2">Free ?</th>
                                        <th scope="col" class="text-center" colspan="2">Qty</th>
                                        <th scope="col" class="text-center" colspan="2">Cost Price</th>
                                        <th scope="col" class="text-center" colspan="2">WH. Min Price</th>
                                        <th scope="col" class="text-center" colspan="2">Min Retail Price</th>
                                        <th scope="col" class="text-center" colspan="2">Base Price</th>
                                        <th scope="col" class="text-center" rowspan="2">Location [Rack-Section-Row-Box NO]
                                            [A-01-01-101]</th>
                                    </tr>
                                    <tr>
                                        <th>Old</th>
                                        <th>New</th>
                                        <th>Old</th>
                                        <th>New</th>
                                        <th>Old</th>
                                        <th>New</th>
                                        <th>Old</th>
                                        <th>New</th>
                                        <th>Old</th>
                                        <th>New</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">
                                            <input type="checkbox" class="create" name="create[0]" id="create1"
                                                {{ $piece && $piece->create == 1 ? 'Checked' : '' }} data-id="1">
                                        </th>
                                        <td class="UnitType">
                                            Piece
                                            <input type="hidden" name="unit[]" value="piece">
                                            <input type="hidden" id="new_status1" name="new_status[]">
                                            <input type="hidden" id="new_default1" name="new_default[]" value="2">
                                        </td>
                                        <td>
                                            <input type="checkbox" name="status[0]" id="status1"
                                                class="status"{{ $piece && $piece->status == 1 ? 'Checked' : '' }} data-id="1">
                                        </td>
                                        <td>
                                            {{-- <input class="is_default" type="radio" name="is_default[]" id="is_default1"
                                                {{ $piece && $piece->is_default == 1 ? 'checked' : '' }} data-id="1"> --}}
                                            <input class="is_default" type="radio" name="is_default" id="is_default1"
                                                {{ $piece && $piece->is_default == 1 ? 'checked' : '' }} data-id="1">
                                        </td>
                                        <td>
                                            <input type="checkbox" name="is_free[0]" id="is_free1" class="is_free"
                                                {{ $piece && $piece->is_free == 1 ? 'Checked' : '' }}>
                                        </td>
                                        <td class="Qty text-center">{{ $piece && $piece->qty ? $piece->qty : 0 }}</td>
                                        <td>
                                            <input type="text" style="width:50px;" name="qty[]" id="qty1" min="1"
                                                class="number" value="1" readonly>
                                        </td>
                                        <td>{{ $piece && $piece->cost_price ? $piece->cost_price : 0 }}</td>
                                        <td>
                                            <input type="text" style="width:50px;" name="cost_price[]" id="cost_price1"
                                                value="{{ $piece && $piece->cost_price ? $piece->cost_price : '0' }}" class="number"
                                                min="{{ $product->srp }}">
                                        </td>
                                        <td>{{ $piece && $piece->wh_min_price ? $piece->wh_min_price : 0 }}</td>
                                        <td>
                                            <input type="text" style="width:50px;" name="wh_min_price[]" id="wh_min_price1"
                                                value="{{ $piece && $piece->wh_min_price ? $piece->wh_min_price : 0 }}"
                                                class="number" min="{{ $product->srp }}">
                                        </td>
                                        <td>{{ $piece && $piece->min_retail_price ? $piece->min_retail_price : 0 }}</td>
                                        <td>
                                            <input type="text" style="width:50px;" name="min_retail_price[]"
                                                id="min_retail_price1"
                                                value="{{ $piece && $piece->min_retail_price ? $piece->min_retail_price : 0 }}"
                                                class="number" min="{{ $product->srp }}">
                                        </td>
                                        <td>{{ $piece && $piece->base_price ? $piece->base_price : 0 }}</td>
                                        <td>
                                            <input type="text" style="width:50px;" name="base_price[]" id="base_price1"
                                                value="{{ $piece && $piece->base_price ? $piece->base_price : 0 }}" class="number"
                                                min="{{ $product->srp }}">
                                        </td>
                                        <td>
                                            <input type="text" style="width:50px;" name="rack[]" id="rack1"
                                                value="{{ $piece && $piece->rack ? $piece->rack : '' }}" maxlength="1">-
                                            <input type="text" style="width:50px;" name="section[]" class="number"
                                                id="section1" value="{{ $piece && $piece->section ? $piece->section : '' }}"
                                                maxlength="2">-
                                            <input type="text" style="width:50px;" name="row[]" class="number" id="row1"
                                                value="{{ $piece && $piece->row ? $piece->row : '' }}" maxlength="2">-
                                            <input type="text" style="width:50px;" name="box_no[]" class="number" id="box_no1"
                                                value="{{ $piece && $piece->box_no ? $piece->box_no : '' }}" maxlength="3">
                                        </td>

                                    </tr>
                                    <tr>
                                        <th scope="row"><input type="checkbox" class="create" name="create[1]" id="create2"
                                                {{ $box && $box->create == 1 ? 'Checked' : '' }} data-id="2"></th>
                                        <td class="UnitType">
                                            Box
                                            <input type="hidden" name="unit[]" value="box">
                                            <input type="hidden" id="new_status2" name="new_status[]">
                                            <input type="hidden" id="new_default2" name="new_default[]" value="2">

                                        </td>
                                        <td>
                                            <input type="checkbox" name="status[1]" id="status2" class="status"
                                                {{ $box && $box->status == 1 ? 'Checked' : '' }} data-id="2">
                                        </td>
                                        <td>
                                            {{-- <input type="radio" class="is_default" name="is_default[]" id="is_default2"
                                                {{ $box && $box->is_default == 1 ? 'checked' : '' }} data-id="2"> --}}
                                            <input type="radio" class="is_default" name="is_default" id="is_default2"
                                                {{ $box && $box->is_default == 1 ? 'checked' : '' }} data-id="2">
                                        </td>
                                        <td>
                                            <input type="checkbox" name="is_free[1]" id="is_free2" class="is_free"
                                                {{ $box && $box->is_free == 1 ? 'checked' : '' }}>
                                        </td>
                                        <td>{{ $box && $box->qty ? $box->qty : 0 }}</td>
                                        <td>
                                            <input type="text" style="width:50px;" name="qty[]" class="Qty text-center" id="qty2"
                                                value="{{ $box && $box->qty ? $box->qty : 0 }}"
                                                min="{{ $box && $box->qty ? $box->qty : 0 }}" class="number">
                                        </td>
                                        <td>{{ $box && $box->cost_price ? $box->cost_price : 0 }}</td>
                                        <td><input type="text" style="width:50px;" name="cost_price[]" id="cost_price2"
                                                value="{{ $box && $box->cost_price ? $box->cost_price : '0' }}" class="number"
                                                min="{{ $product->srp }}"></td>
                                        <td>{{ $box && $box->wh_min_price ? $box->wh_min_price : 0 }}</td>
                                        <td><input type="text" style="width:50px;" name="wh_min_price[]" id="wh_min_price2"
                                                value="{{ $box && $box->wh_min_price ? $box->wh_min_price : 0 }}" class="number"
                                                min="{{ $product->srp }}"></td>
                                        <td>{{ $box && $box->min_retail_price ? $box->min_retail_price : 0 }}</td>
                                        <td><input type="text" style="width:50px;" name="min_retail_price[]"
                                                id="min_retail_price2"
                                                value="{{ $box && $box->min_retail_price ? $box->min_retail_price : 0 }}"
                                                class="number" min="{{ $product->srp }}"></td>
                                        <td>{{ $box && $box->base_price ? $box->base_price : 0 }}</td>
                                        <td><input type="text" style="width:50px;" name="base_price[]" id="base_price2"
                                                value="{{ $box && $box->base_price ? $box->base_price : 0 }}" class="number"
                                                min="{{ $product->srp }}"></td>
                                        <td>
                                            <input type="text" style="width:50px;" name="rack[]" id="rack2"
                                                value="{{ $box && $box->rack ? $box->rack : '' }}" maxlength="1">-
                                            <input type="text" style="width:50px;" name="section[]" id="section2"
                                                class="number" value="{{ $box && $box->section ? $box->section : '' }}"
                                                maxlength="2">-
                                            <input type="text" style="width:50px;" name="row[]" id="row2" class="number"
                                                value="{{ $box && $box->row ? $box->row : '' }}" maxlength="2">-
                                            <input type="text" style="width:50px;" name="box_no[]" id="box_no2" class="number"
                                                value="{{ $box && $box->box_no ? $box->box_no : '' }}" maxlength="3">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><input type="checkbox" class="create" name="create[2]" id="create3"
                                                {{ $case && $case->create == 1 ? 'Checked' : '' }} data-id="3"></th>
                                        <td class="UnitType">
                                            Case
                                            <input type="hidden" name="unit[]" value="case">
                                            <input type="hidden" id="new_status3" name="new_status[]">
                                            <input type="hidden" id="new_default3" name="new_default[]" value="2">
                                        </td>
                                        <td>
                                            <input type="checkbox" class="status" name="status[2]" id="status3"
                                                {{ $case && $case->status == 1 ? 'Checked' : '' }} data-id="3">
                                        </td>
                                        <td>
                                            {{-- <input type="radio" class="is_default" name="is_default[]" id="is_default3"
                                                {{ $case && $case->is_default == 1 ? 'checked' : '' }} data-id="3"> --}}

                                            <input type="radio" class="is_default" name="is_default" id="is_default3"
                                                {{ $case && $case->is_default == 1 ? 'checked' : '' }} data-id="3">
                                        </td>
                                        <td>
                                            <input type="checkbox" name="is_free[2]" id="is_free3" class="is_free Qty text-center"
                                                {{ $case && $case->is_free == 1 ? 'checked' : '' }}>
                                        </td>
                                        <td>{{ $case && $case->qty ? $case->qty : 0 }}</td>
                                        <td>
                                            <input type="text" style="width:50px;" name="qty[]" id="qty3"
                                                value="{{ $case && $case->qty ? $case->qty : 0 }}"
                                                min="{{ $case && $case->qty ? $case->qty : 0 }}" class="number">
                                        </td>
                                        <td>{{ $case && $case->cost_price ? $case->cost_price : 0 }}</td>
                                        <td>
                                            <input type="text" style="width:50px;" name="cost_price[]" id="cost_price3"
                                                value="{{ $case && $case->cost_price ? $case->cost_price : '0' }}" class="number"
                                                min="{{ $product->srp }}" />
                                        </td>
                                        <td>{{ $case && $case->wh_min_price ? $case->wh_min_price : 0 }}</td>
                                        <td>
                                            <input type="text" style="width:50px;" name="wh_min_price[]" id="wh_min_price3"
                                                value="{{ $case && $case->wh_min_price ? $case->wh_min_price : 0 }}" class="number"
                                                min="{{ $product->srp }}">
                                        </td>
                                        <td>{{ $case && $case->min_retail_price ? $case->min_retail_price : 0 }}</td>
                                        <td>
                                            <input type="text" style="width:50px;" name="min_retail_price[]"
                                                id="min_retail_price3"
                                                value="{{ $case && $case->min_retail_price ? $case->min_retail_price : 0 }}"
                                                class="number" min="{{ $product->srp }}">
                                        </td>
                                        <td>
                                            {{ $case && $case->min_retail_price ? $case->min_retail_price : 0 }}
                                        </td>
                                        <td>
                                            <input type="text" style="width:50px;" name="base_price[]" id="base_price3"
                                                value="{{ $case && $case->base_price ? $case->base_price : 0 }}" class="number"
                                                min="{{ $product->srp }}">
                                        </td>
                                        <td>
                                            <input type="text" style="width:50px;" name="rack[]" id="rack3"
                                                value="{{ $case && $case->rack ? $case->rack : '' }}" maxlength="1">-
                                            <input type="text" style="width:50px;" name="section[]" id="section3"
                                                class="number" value="{{ $case && $case->section ? $case->section : '' }}"
                                                maxlength="2">-
                                            <input type="text" style="width:50px;" name="row[]" id="row3" class="number"
                                                value="{{ $case && $case->row ? $case->row : '' }}" maxlength="2">-
                                            <input type="text" style="width:50px;" name="box_no[]" id="box_no3" class="number"
                                                value="{{ $case && $case->box_no ? $case->box_no : '' }}" maxlength="3">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <div class="alert alert-danger alert-dismissable fade in" id="Div1" style="width: 33%; text-align: left; display: none;">

                        <span><b style="color: red">Note - </b>Please check the Enable section check box to manage price.</span>
                    </div>
                    
                    <button type="button" class="btn btn-primary buttonAnimation pull-right round box-shadow-1 btn-sm" onclick="checkDefault()">&nbsp;&nbsp;&nbsp;Update&nbsp;&nbsp;&nbsp;</button>
                    <button type="button" class="btn btn-danger buttonAnimation pull-right round box-shadow-1 btn-sm" data-dismiss="modal">&nbsp;&nbsp;&nbsp;Close&nbsp;&nbsp;&nbsp;</button>
                </div>
            </div>
        </div>
        </div>
        @endcomponent

        <div class="row">
            <input type="hidden" name="type" value="single">
            <input type="hidden" name="single_variation_id" value="{{ $product_variation->id }}">
            <input type="hidden" name="single_dpp"
                value="{{ @num_format($product_variation->default_purchase_price) }}">
            <input type="hidden" name="single_dpp_inc_tax" value="@num_format($product_variation->dpp_inc_tax)">
            <input type="hidden" name="profit_percent" value="@num_format($product_variation->profit_percent)">
            <input type="hidden" name="single_dsp" value="@num_format($product_variation->default_sell_price)">
            <input type="hidden" name="single_dsp_inc_tax" value="@num_format($product_variation->sell_price_inc_tax)">
            <input type="hidden" name="submit_type" id="submit_type">
            <div class="col-sm-12">
                <div class="text-center">
                    <div class="btn-group">
                        @if ($selling_price_group_count)
                            <button type="submit" value="submit_n_add_selling_prices"
                                class="btn btn-warning submit_product_form">@lang('lang_v1.save_n_add_selling_price_group_prices')</button>
                        @endif

                        {{-- @can('product.opening_stock')
                            <button type="submit" @if (empty($product->enable_stock)) disabled="true" @endif
                                id="opening_stock_button" value="update_n_edit_opening_stock"
                                class="btn bg-purple submit_product_form">@lang('lang_v1.update_n_edit_opening_stock')</button>
                            @endif

                            <button type="submit" value="save_n_add_another"
                                class="btn bg-maroon submit_product_form">@lang('lang_v1.update_n_add_another')</button> --}}



                        <button type="submit" value="submit"
                            class="btn btn-primary submit_product_form">@lang('messages.update')</button>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </section>
    <!-- /.content -->

@endsection

@section('javascript')
    <script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/product_unit.js?v=' . $asset_v) }}"></script>

    <script>
        // $('#product_add_form').on('submit',function(e){

        //     e.preventDefault();
        //     $('[name="qty[]"]').rules( "add", {
        //         required: true,
        //     })


        // });

        $(document).ready(function() {
            $('.number').keyup(function(e) {
                // Remove all non-digit and non-decimal point characters
                this.value = this.value.replace(/[^0-9.]/g, '');

                // Remove extra decimal points
                var decimalCount = (this.value.match(/\./g) || []).length;
                if (decimalCount > 1) {
                    this.value = this.value.replace(/\.+$/, '');
                }
            });

            $(".status").each(function() {
                var id = $(this).data('id');

                if ($(this).is(":checked")) {
                    $(this).val(1);
                    $('#new_status' + id).val(1);
                } else {
                    $(this).val(2);
                    $('#new_status' + id).val(2);
                }
            });
        });
        // __page_leave_confirmation('#product_add_form');
    </script>
    <script>
        function showManagePriceModal() {
            $('#managePriceModal').modal('show');
        }

        //check default

        function checkDefault() {
            var chk = false, prc = false;
            var tqty = 0, checkAuto = 0, autId = 0, autId1 = 0, autId2 = 0, OrderAttachement = 0, OrderAttachement1 = 0, OrderAttachement2 = 0, Setdefault = 0, checkd = 0, loc = 0, chkloc = 0;
            $("#Table1 tbody tr").each(function () {
                if ($(this).find('.Action input').prop('checked') == true) {
                    var basePrice = Number($(this).find('.NewCosePrice input').val());
                    if (basePrice == 0 || basePrice == null) {
                        prc = true;
                        $(this).find('.NewCosePrice input').addClass('border-warning');
                        $(this).find('.NewBasePrice input').addClass('border-warning');
                        $(this).find('.NewRetailPrice input').addClass('border-warning');
                        $(this).find('.NWHPrice input').addClass('border-warning');
                    }
                    checkAuto = Number($(this).find('.Action input').attr("autoid"));
                    if (checkAuto == 0) {
                        autId = Number($(this).find('.NewQty input').val());;
                        OrderAttachement = Number($(this).find('.Action span').attr("OrderAttachment"));
                    }
                    else if (checkAuto == 1) {
                        autId1 = Number($(this).find('.NewQty input').val());;
                        OrderAttachement1 = Number($(this).find('.Action span').attr("OrderAttachment"));
                    }
                    else if (checkAuto == 2) {
                        autId2 = Number($(this).find('.NewQty input').val());;
                        OrderAttachement2 = Number($(this).find('.Action span').attr("OrderAttachment"))
                    }
                }
                var totalQty = Number($(this).find('.NewQty input').val());
                if (totalQty == 0 || totalQty == null) {
                    chk = true;
                    $(this).find('.NewQty input').addClass('border-warning');
                }
                //if ($(this).find('.Status input').prop('checked') == true) {
                if ($(this).find('.Default input').prop("checked") == true) {
                    Setdefault += Setdefault + 1;
                }
                //}
                loc = 0;
                if ($(this).find('.Location input.first').val().trim() != '') {
                    loc = 1;
                }
                if ($(this).find('.Location input.second').val().trim() != '') {
                    loc = Number(loc) + 1;
                }
                if ($(this).find('.Location input.third').val().trim() != '') {
                    loc = Number(loc) + 1;
                }
                if ($(this).find('.Location input.fourth').val().trim() != '') {
                    loc = Number(loc) + 1;
                }
                if (Number(loc) != 0) {
                    if (Number(loc) < 4) {
                        $(this).find('.Location input.first').addClass('border-warning');
                        $(this).find('.Location input.second').addClass('border-warning');
                        $(this).find('.Location input.third').addClass('border-warning');
                        $(this).find('.Location input.fourth').addClass('border-warning');
                        chkloc = Number(chkloc) + 1;
                    }
                    else {
                        $(this).find('.Location input.first').removeClass('border-warning');
                        $(this).find('.Location input.second').removeClass('border-warning');
                        $(this).find('.Location input.third').removeClass('border-warning');
                        $(this).find('.Location input.fourth').removeClass('border-warning');
                    }
                }
                //if (Number($(this).find('.NewQty input.first').val()) == Number($(this).find('.NewQty input.second').val())) {
                //    $(this).find('.NewQty input.second').addClass('border-warning');
                //}
                //else if (Number($(this).find('.NewQty input.second').val()) == Number($(this).find('.NewQty input.third').val())) {
                //    $(this).find('.NewQty input.third').addClass('border-warning');
                //}
            })
            $('#Table1 tr:nth-child(3) .NewQty input').removeClass('border-warning');
            $('#Table1 tr:nth-child(2) .NewQty input').removeClass('border-warning');
            $('#Table1 tr:nth-child(1) .NewQty input').removeClass('border-warning');

            if ($('#Table1 tr:nth-child(1) .NewQty input').val() == $('#Table1 tr:nth-child(2) .NewQty input').val() == $('#Table1 tr:nth-child(3) .NewQty input').val()) {
                $('#Table1 tr:nth-child(2) .NewQty input').addClass('border-warning');
                $('#Table1 tr:nth-child(3) .NewQty input').addClass('border-warning');
                $('#Table1 tr:nth-child(1) .NewQty input').addClass('border-warning');
            }
            else if ($('#Table1 tr:nth-child(1) .NewQty input').val() == $('#Table1 tr:nth-child(2) .NewQty input').val()) {
                $('#Table1 tr:nth-child(2) .NewQty input').addClass('border-warning');
                $('#Table1 tr:nth-child(1) .NewQty input').addClass('border-warning');
            }
            else if ($('#Table1 tr:nth-child(2) .NewQty input').val() == $('#Table1 tr:nth-child(3) .NewQty input').val()) {
                $('#Table1 tr:nth-child(2) .NewQty input').addClass('border-warning');
                $('#Table1 tr:nth-child(3) .NewQty input').addClass('border-warning');
            }
            else if ($('#Table1 tr:nth-child(1) .NewQty input').val() == $('#Table1 tr:nth-child(3) .NewQty input').val()) {
                $('#Table1 tr:nth-child(1) .NewQty input').addClass('border-warning');
                $('#Table1 tr:nth-child(3) .NewQty input').addClass('border-warning');
            }

            if (prc) {
                toastr.error('Please manage price before update.', 'Warning', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                return;
            }
            if (chk) {
                toastr.error('Please enter new quantity.', 'Warning', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                return;
            }

            if (autId === autId1 === autId2) {
                toastr.error('Please manage quantity.Its cannot be same.', 'Warning', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                return;
            }
            else if (autId == autId1 && (autId > 0 || autId1 > 0)) {
                toastr.error('Please manage quantity.Its cannot be same.', 'Warning', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                return;
            }
            else if (autId == autId2 && (autId > 0 || autId2 > 0)) {
                toastr.error('Please manage quantity.Its cannot be same.', 'Warning', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                return;
            }
            else if (autId1 == autId2 && (autId1 > 0 || autId2 > 0)) {
                toastr.error('Please manage new quantity.Its cannot be same.', 'Warning', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                return;
            }

            if ((autId == autId1 == autId2) && OrderAttachement != 0 && OrderAttachement1 != 0 && OrderAttachement2 != 0) {
                toastr.error('Please manage new quantity.Its cannot be same.', 'Warning', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                return;
            }
            else if ((autId == autId1) && OrderAttachement != 0 && OrderAttachement1 != 0) {
                toastr.error('Please manage new quantity.Its cannot be same.', 'Warning', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                return;
            }
            else if ((autId == autId2) && OrderAttachement != 0 && OrderAttachement2 != 0) {
                toastr.error('Please manage new quantity.Its cannot be same.', 'Warning', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                return;
            }
            else if ((autId1 == autId2) && OrderAttachement1 != 0 && OrderAttachement2 != 0) {
                toastr.error('Please manage new quantity.Its cannot be same.', 'Warning', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                return;
            }
            else if (Setdefault == 0) {
                toastr.error('Please set default before update.', 'Warning', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                return;
            }
            else if (chkloc != 0) {
                toastr.error('Please manage location. All fields required.', 'Warning', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                return;
            }
            var check = 0;
            $("#Table1 tbody tr").each(function () {
                if ($(this).find('.Action input').prop('checked') == true) {
                    {
                        check += check + 1;
                        chk = true;
                    }
                }
            });
            if (check == 0) {
                toastr.error('Please check the checkbox to manage price.', 'Warning', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                return;
            }
            //Validation end
            var checkd = 0, currentDefault = 0;
            var Default = localStorage.getItem("Default");
            $("#Table1 tbody tr").each(function () {
                if ($(this).find('.Action input').prop('checked') == true) {
                    if ($(this).find('.Default input').prop('checked') == true && $(this).find('.Qty span').attr("defaultpack") == '0'
                        && $(this).find('.Qty span').attr("PackingId") != '0') {
                        checkd = 1;
                    }
                }
            })
            if ($("#Hd_Domain").val().toUpperCase() != 'PSMNJ') {
                checkd = 0;
            }
            if (checkd == 1) {
                swal({
                    title: "Are you sure?",
                    text: "You want to change default packing.",
                    icon: "warning",
                    showCancelButton: true,
                    allowOutsideClick: false,
                    closeOnClickOutside: false,
                    buttons: {
                        cancel: {
                            text: "No, Cancel.",
                            value: null,
                            visible: true,
                            className: "btn-warning",
                            closeModal: true,
                        },
                        confirm: {
                            text: "Yes, Change it.",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: false
                        }
                    }
                }).then(function (isConfirm) {
                    if (isConfirm) {
                        updatebulk();
                    }
                })
            }
            else {
                updatebulk();
            }
        }
    </script>
@endsection
