@extends('layouts.app')

@section('title', __('lang_v1.add_stock_transfer'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>{{ __('lang_v1.add_stock_transfer') }}</h1>
    </section>

    <!-- Main content -->
    <section class="content no-print">
        {!! Form::open([
            'url' => action([\App\Http\Controllers\ProductStockController::class, 'store']),
            'method' => 'post',
            'id' => 'product_stock_form',
        ]) !!}
        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title">{{ __('stock_adjustment.stock_details') }}</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="col-sm-12">
                            <div class="form-group">
                                {!! Form::label('barcode', 'Barcode:') !!}
                                {!! Form::text('barcode', null, ['class' => 'form-control input-sm', 'placeholder' => 'Enter Barcode']) !!}
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                {!! Form::label('product_id', 'Products:*') !!}
                                {!! Form::select('product_id', $products, null, [
                                    'placeholder' => __('messages.please_select'),
                                    'class' => 'form-control input-sm select2',
                                ]) !!}
                            </div>
                        </div>
    
                        <div class="col-sm-12">
                            <div class="form-group">
                                {!! Form::label('available_in_stock', 'Available in Stock:') !!}
                                {!! Form::text('available_in_stock', null, [
                                    'class' => 'form-control input-sm',
                                    'placeholder' => 'Available in Stock',
                                    'id' => 'availableInStockId',
                                    'readonly',
                                ]) !!}
                            </div>
                        </div>
    
                        <div class="col-sm-12">
                            <div class="form-group">
                                {!! Form::label('remark', 'Remark:*') !!}
                                {!! Form::textarea('remark', null, ['class' => 'form-control input-sm', 'placeholder' => 'Enter Remark', 'id'=> 'remarkId', 'rows' => 4]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered table-striped table-condensed table table-responsive text-center">
                            <thead>
                                <tr>
                                    <th scope="col" class="col-sm-3">Unit Type</th>
                                    <th scope="col" class="col-sm-3">Quantity Per Unit</th>
                                    <th class="col-sm-1"></th>
                                    <th scope="col" class="col-sm-2">Quantity</th>
                                    <th scope="col" class="col-sm-3">Total</th>
                                </tr>
                            </thead>
                            <tbody id="manageStockId">
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="row" colspan="4" class='text-right'>Total New Stock in Pieces</th>
                                    <td>
                                        <div class="col-sm-12">
                                            {!! Form::text('current_stock', 0, [
                                                'class' => 'form-control input-sm text-center',
                                                'id' => 'current_stock',
                                                'readonly',
                                            ]) !!}
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <button type="reset" id="reset_btn" class="btn btn-danger pull-right">@lang('messages.cancel')</button>
                        <button type="submit" id="save_stock_transfer" class="btn btn-primary pull-right mr-8">@lang('messages.save')</button>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
        <div class="box box-solid" id="stockUpdateLogId" style="display: none">
            <div class="box-header">
                <h3 class="box-title">{{ __('stock_adjustment.stock_update_log_list') }} <span id="productName" class="text-bold"></span></h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered" id="stockUpdateLog">
                    <thead>
                        <tr>
                            <th>{{ __('stock_adjustment.date_time') }}</th>
                            <th>{{ __('stock_adjustment.before_stock') }}</th>
                            <th>{{ __('stock_adjustment.affected_stock') }}</th>
                            <th>{{ __('stock_adjustment.default_affected_stock') }}</th>
                            <th>{{ __('stock_adjustment.current_stock') }}</th>
                            <th>{{ __('stock_adjustment.current_stock_in_default_unit') }}</th>
                            <th>{{ __('stock_adjustment.reference_id') }}</th>
                            <th>{{ __('stock_adjustment.updated_by') }}</th>
                            <th>{{ __('stock_adjustment.remark') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!--box end-->
    </section>
@stop
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            jQuery('body').on('change','#product_id',function(){
                let product_id = $(this).val();
                let option = $(this).find("option:selected").text();
                $("#productName").html('('+option+')');

                // get update stock log table datas
                funUpdateStockTable(product_id);

                $.ajax({
                    url: "{{ route('stock.get_unit') }}",
                    type: 'POST',
                    data: { product_id: product_id }, 
                    success: function(response) {
                        $("#availableInStockId").val(response.data.current_stock);
                        $("#manageStockId").html('');
                        
                        $.each(response.data.product_units, function(index, value) {
                            let isDefault = '';
                            if(value.is_default == 1){
                                isDefault = 'style="font-weight: bold;background: #8cc896;"';
                            }
                            $("#manageStockId").append('<tr '+isDefault+'>\
                                                            <td scope="row">'+value.unit+'</td>\
                                                            <td>'+value.qty+'</td>\
                                                            <td>*</td>\
                                                            <td>\
                                                                <div class="col-sm-12">\
                                                                    <input type="text" name="qty[]" value="0" data-id="'+value.id+'" data-qty="'+value.qty+'" class="form-control quantity-class text-center">\
                                                                </div>\
                                                            </td>\
                                                            <td><span id="sub-total'+value.id+'" class="sub_total">0</span></td>\
                                                        </tr>');
                        });
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                    }
                });
            });

            jQuery('body').on('keyup','.quantity-class',function(){
                this.value = this.value.replace(/[^0-9.]/g, '');
                var decimalCount = (this.value.match(/\./g) || []).length;
                if (decimalCount > 1) {
                    this.value = this.value.replace(/\.+$/, '');
                }
                let qtyVal = $(this).data('qty');

                let subTotal = parseFloat(this.value) * parseFloat(qtyVal);
                $("#sub-total"+$(this).data('id')).html(subTotal);

                let total = 0;
                $('.sub_total').each(function() {
                    var val = parseInt($(this).html());
                    if (!isNaN(val)) {
                        total += val;
                    }
                });

                $("#current_stock").val(total);
            });
            
            $("#product_stock_form").validate({
                debug: true,
                rules: {
                    product_id: {
                        required: true,
                    },
                    remark:{
                        required: true,
                        minlength: 10, 
                    }
                },
                messages: {
                    product_id: {
                        required: "Please Select Prodct",
                    },
                    remark:{
                        required: "Please Enter Remark", 
                        minlength: "Minimum of 10 characters required.",
                    }
                },
                submitHandler: function(form) {
                    // This function will be called when the form is valid and submitted
                    // Serialize the form data
                    var productId = $('#product_id').val();

                    var formData = $(form).serialize();

                    // Send an Ajax request
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: formData,
                        success: function(response) {
                            $('#product_id').val(productId).trigger('change');
                            $("#current_stock").val(0);
                            $("#remarkId").val('');
                            toastr.success('Stock updated successfully.');
                        },
                        error: function(xhr, status, error) {
                            // Handle the error
                            console.log(error);
                        }
                    });
                    //form.submit();
                }
            });

        });
        function funUpdateStockTable(productId){
            $("#stockUpdateLogId").show();
            $('#stockUpdateLog').DataTable().destroy();
            $('#stockUpdateLog').DataTable({
                bPaginate: false,
                bLengthChange: false,
                searching:false,
                processing: true,
                serverSide: true,
                ajax: "{{ route('stock.update-log') }}?product_id="+productId,
                buttons:[],
                "columns":[
                    {"data":"created_at"},
                    {"data":"before_stock"},
                    {"data":"affected_stock"},
                    {"data":"defaul_affected_stock"},
                    {"data":"current_stock"},
                    {"data":"current_stock_in_defaul_unit"},
                    {"data":"reference_id"},
                    {"data":"updated_by"},
                    {"data":"remark"}
                ]

            });
        }
    </script>
@endsection
