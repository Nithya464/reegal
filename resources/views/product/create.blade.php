@extends('layouts.app')
@section('title', __('product.add_new_product'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('product.add_new_product')</h1>
        <!-- <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                <li class="active">Here</li>
            </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        @php
            $form_class = empty($duplicate_product) ? 'create' : '';
            $is_image_required = !empty($common_settings['is_product_image_required']);
        @endphp
        {!! Form::open([
            'url' => action([\App\Http\Controllers\ProductController::class, 'store']),
            'method' => 'post',
            'id' => 'product_add_form',
            'class' => 'product_form ' . $form_class,
            'files' => true,
        ]) !!}
        @component('components.widget', ['class' => 'box-primary'])
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('category_id', __('product.category') . ':*') !!}
                                <input type="hidden" name="type" value="single">
                                <div class="input-group">
                                    {!! Form::select(
                                        'category_id',
                                        $categories,
                                        !empty($duplicate_product->category_id) ? $duplicate_product->category_id : null,
                                        ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2','required'],
                                    ) !!}
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
                                {!! Form::select(
                                    'sub_category_id',
                                    $sub_categories,
                                    !empty($duplicate_product->sub_category_id) ? $duplicate_product->sub_category_id : null,
                                    ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2'],
                                ) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('sku', __('product.sku') . ':*') !!} @show_tooltip(__('tooltip.sku'))
                                {!! Form::text('sku', null, ['class' => 'form-control', 'placeholder' => __('product.sku'),'required']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('name', __('product.product_name') . ':*') !!}
                                {!! Form::text('name', !empty($duplicate_product->name) ? $duplicate_product->name : null, [
                                    'class' => 'form-control',
                                    'required',
                                    'placeholder' => __('product.product_name'),
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('contact_id', __('product.preferred_vendor') . ':*') !!}
                                {!! Form::select('contact_id',$vendors,null,['placeholder' => __('messages.please_select'), 'class' => 'form-control select2','required'],) !!}
                            </div>
                        </div>

                        

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('brand_id', __('product.brand') . ':*') !!}
                                <div class="input-group">
                                    {!! Form::select(
                                        'brand_id',
                                        $brands,
                                        !empty($duplicate_product->brand_id) ? $duplicate_product->brand_id : null,
                                        ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2','required'],
                                    ) !!}
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
                                {!! Form::text('re_order_mark',null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('product.re_order_mark'),
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <br>
                                <label>
                                    {!! Form::checkbox('is_ml_qty', 1,null,[
                                        'class' => 'input-icheck',
                                        'id' => 'is_ml_qty',
                                    ]) !!} <strong>@lang('product.is_ml_qty')</strong>
                                </label>@show_tooltip(__('tooltip.is_ml_qty'))
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('ml_qty', __('product.ml_qty') . ':') !!}
                                {!! Form::text('ml_qty','0.00', [
                                    'class' => 'form-control',
                                 ]) !!}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <br>
                                <label>
                                    {!! Form::checkbox('is_weight', 1,null,[
                                        'class' => 'input-icheck',
                                        'id' => 'is_weight',
                                    ]) !!} <strong>@lang('product.is_weight')</strong>
                                </label>@show_tooltip(__('tooltip.is_weight'))
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('weight', __('product.weight') . ':') !!}
                                {!! Form::text('weight', "0.00", [
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('commission_per', __('product.commission_per') . ':*') !!}
                                {!! Form::select(
                                    'commission_per',
                                    config('global.commission_per'),null,
                                    ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2','required'],
                                ) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('stock', __('product.stock') . ':') !!}
                                {!! Form::text('stock','0', [
                                    'class' => 'form-control',
                                    'readonly'
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('srp', __('product.srp') . ':*') !!}
                                {!! Form::text('srp', "0.00", [
                                    'class' => 'form-control',
                                    'required'
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
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered table-hover myNewClass" id="unit_price">
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
                                <input type="checkbox" class="create" name="create[0]" id="create1" data-id="1">
                            </th> 
                            <td>
                                Piece
                                <input type="hidden" name="unit[]" value="piece">
                                <input type="hidden" id="new_status1" name="new_status[]">
                                <input type="hidden" id="new_default1" name="new_default[]" value="1">
                            </td>
                            <td>
                                <input type="checkbox" name="status[0]" id="status1"
                                    class="status" data-id="1">
                            </td>
                            <td>
                                <input class="is_default" type="radio" name="is_default[]" id="is_default1" data-id="1" checked>
                            </td>
                            <td>
                                <input type="checkbox" name="is_free[0]" id="is_free1" class="is_free">
                            </td>
                            <td>0</td>
                            <td>
                                <input type="text" style="width:50px;" name="qty[]" id="qty1"
                                    min="1" class="number" value="1" readonly>
                            </td>
                            <td>0</td>
                            <td>
                                <input type="text" style="width:50px;" name="cost_price[]" id="cost_price1"
                                    value="0" class="number">
                            </td>
                            <td>0</td>
                            <td>
                                <input type="text" style="width:50px;" name="wh_min_price[]" id="wh_min_price1"
                                    value="0" class="number">
                            </td>
                            <td>0</td>
                            <td>
                                <input type="text" style="width:50px;" name="min_retail_price[]"
                                    id="min_retail_price1"
                                    value="0" class="number">
                            </td>
                            <td>0</td>
                            <td>
                                <input type="text" style="width:50px;" name="base_price[]" id="base_price1"
                                    value="0" class="number">
                            </td>
                            <td>
                                <input type="text" style="width:50px;" name="rack[]" id="rack1" value="" maxlength="1">-
                                <input type="text" style="width:50px;" name="section[]" class="number" id="section1" value="" maxlength="2">-
                                <input type="text" style="width:50px;" name="row[]" class="number" id="row1" value="" maxlength="2">-
                                <input type="text" style="width:50px;" name="box_no[]" class="number" id="box_no1" value="" maxlength="3">
                            </td>
        
                        </tr>
                        <tr>
                            <th scope="row"><input type="checkbox" class="create" name="create[1]" id="create2" data-id="2"></th>
                            <td>
                                Box
                                <input type="hidden" name="unit[]" value="box">
                                <input type="hidden" id="new_status2" name="new_status[]">
                                <input type="hidden" id="new_default2" name="new_default[]">
                            </td>
                            <td>
                                <input type="checkbox" name="status[1]" id="status2" class="status" data-id="2">
                            </td>
                            <td>
                                <input type="radio" class="is_default" name="is_default[]" id="is_default2" data-id="2">
                            </td>
                            <td>
                                <input type="checkbox" name="is_free[1]" id="is_free2" class="is_free">
                            </td>
                            <td>0</td>
                            <td>
                                <input type="text" style="width:50px;" name="qty[]" id="qty2" value="" class="number">
                            </td>
                            <td>0</td>
                            <td><input type="text" style="width:50px;" name="cost_price[]" id="cost_price2" value="0" class="number"></td>
                            <td>0</td>
                            <td><input type="text" style="width:50px;" name="wh_min_price[]" id="wh_min_price2" value="0" class="number"></td>
                            <td>0</td>
                            <td><input type="text" style="width:50px;" name="min_retail_price[]" id="min_retail_price2" value="0" class="number"></td>
                            <td>0</td>
                            <td><input type="text" style="width:50px;" name="base_price[]" id="base_price2" value="0" class="number"></td>
                            <td>
                                <input type="text" style="width:50px;" name="rack[]" id="rack2" value="" maxlength="1">-
                                <input type="text" style="width:50px;" name="section[]" id="section2" class="number" maxlength="2">-
                                <input type="text" style="width:50px;" name="row[]" id="row2" class="number" maxlength="2">-
                                <input type="text" style="width:50px;" name="box_no[]" id="box_no2" class="number" value="" maxlength="3">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><input type="checkbox" class="create" name="create[2]" id="create3" data-id="3"></th>
                            <td>
                                Case
                                <input type="hidden" name="unit[]" value="case">
                                <input type="hidden" id="new_status3" name="new_status[]">
                                <input type="hidden" id="new_default3" name="new_default[]">
                            </td>
                            <td>
                                <input type="checkbox" class="status" name="status[2]" id="status3"  data-id="3">
                            </td>
                            <td>
                                <input type="radio" class="is_default" name="is_default[]" id="is_default3" data-id="3">
                            </td>
                            <td>
                                <input type="checkbox" name="is_free[2]" id="is_free3" class="is_free">
                            </td>
                            <td>0</td>
                            <td>
                                <input type="text" style="width:50px;" name="qty[]" id="qty3" value="" min="" class="number">
                            </td>
                            <td>0</td>
                            <td>
                                <input type="text" style="width:50px;" name="cost_price[]" id="cost_price3" value="0" class="number" />
                            </td>
                            <td>0</td>
                            <td>
                                <input type="text" style="width:50px;" name="wh_min_price[]" id="wh_min_price3"
                                    value="0" class="number">
                            </td>
                            <td>0</td>
                            <td>
                                <input type="text" style="width:50px;" name="min_retail_price[]" id="min_retail_price3" value="0" class="number">
                            </td>
                            <td>0</td>
                            <td>
                                <input type="text" style="width:50px;" name="base_price[]" id="base_price3" value="0" class="number">
                            </td>
                            <td>
                                <input type="text" style="width:50px;" name="rack[]" id="rack3" value="" maxlength="1">-
                                <input type="text" style="width:50px;" name="section[]" id="section3" class="number" value="" maxlength="2">-
                                <input type="text" style="width:50px;" name="row[]" id="row3" class="number" value="" maxlength="2">-
                                <input type="text" style="width:50px;" name="box_no[]" id="box_no3" class="number" value="" maxlength="3">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endcomponent
        <div class="row">
            <div class="col-sm-12">
                <input type="hidden" name="submit_type" id="submit_type">
                <div class="text-center">
                    <div class="btn-group">
                        <button type="submit" value="submit"
                            class="btn btn-primary submit_product_form">@lang('messages.save')</button>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}

    </section>
    <!-- /.content -->

@endsection

@section('javascript')
    @php $asset_v = env('APP_VERSION'); @endphp
    <script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/product_unit.js?v=' . $asset_v) }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            $("#sku,#ml_qty,#weight,#stock,#srp").on("keypress", function(event) {
                var charCode = event.which ? event.which : event.keyCode;
                if (charCode < 48 || charCode > 57) {
                    event.preventDefault();
                }
                
                if ($(this).val().length >= 8 && charCode !== 8 && charCode !== 46) {
                    event.preventDefault();
                }
            });

            $("#sku").on("paste input", function(event) {
                var pastedData = event.originalEvent.clipboardData.getData("text/plain");
                var validDigits = pastedData.replace(/[^\d]/g, "").substr(0, 8);
                document.execCommand("insertText", false, validDigits);
                event.preventDefault();
            });

            onScan.attachTo(document, {
                suffixKeyCodes: [13], // enter-key expected at the end of a scan
                reactToPaste: true, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)
                onScan: function(sCode, iQty) {
                    $('input#sku').val(sCode);
                },
                onScanError: function(oDebug) {
                    console.log(oDebug);
                },
                minLength: 2,
                ignoreIfFocusOn: ['input', '.form-control']
                // onKeyDetect: function(iKeyCode){ // output all potentially relevant key events - great for debugging!
                //     console.log('Pressed: ' + iKeyCode);
                // }
            });
        });
    </script>
@endsection
