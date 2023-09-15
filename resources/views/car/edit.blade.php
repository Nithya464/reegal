@extends('layouts.app')
@section('title', __('Car Edit'))

@section('content')
    <style type="text/css">



    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Car Edit</h1>
        <!-- <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                    <li class="active">Here</li>
                </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        {!! Form::open([
            'url' => action([\App\Http\Controllers\CarController::class, 'update'], [$car->id]),
            'method' => 'PUT',
            'id' => 'car_add_form',
            'files' => true,
        ]) !!}
        <div class="box box-solid">
            <div class="box-body">
                <br><br><br>
                <input type="hidden" name="sub_type">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('Car Nick Name', __('Car Nick Name') . ':*') !!}
                                {!! Form::text('car_nick_name', $car->car_nick_name, [
                                    'class' => 'form-control',
                                    'placeholder' => __('Enter Car Nic Name'),
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('year', __('Year') . ':*') !!}
                                {!! Form::text('year', $car->years, [
                                    'class' => 'form-control input_number',
                                    'placeholder' => __('Enter Year'),
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('exp_date', __('Inspect. Exp Date') . ':*') !!}
                                {!! Form::date('exp_date', date('Y-m-d',strtotime($car->exp_date)), [
                                    'class' => 'form-control',
                                    'required',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('make_by', __('Make') . ':*') !!}
                                {!! Form::text('make_by', $car->make_by, [
                                    'class' => 'form-control',
                                    'required',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('model', __('Model') . ':*') !!}
                                {!! Form::text('model', $car->model, [
                                    'class' => 'form-control',
                                    'required',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('vin_no', __('VIN Number') . ':*') !!}
                                {!! Form::text('vin_no', $car->vin_no, [
                                    'class' => 'form-control',
                                    'required',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('licence_plate', __('Licence Plate') . ':*') !!}
                                {!! Form::text('licence_plate', $car->licence_plate, [
                                    'class' => 'form-control',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('start_mileage', __('Start Mileage') . ':*') !!}
                                {!! Form::text('start_mileage', $car->start_mileage, [
                                    'class' => 'form-control input_number',
                                    'placeholder' => __('Enter Start Mileage'),
                                    'required',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('load_order_type', __('Load Order Type') . ':') !!}
                                {!! Form::select('load_order_type', ['1' => 'First In Last Out', '2' => 'First In First Out'], $car->load_order_type, [
                                    'class' => 'form-control',
                                    
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('status', __('Status') . ':*') !!}
                                {!! Form::select('status', ['1' => 'Active', '2' => 'De-Active'], $car->status, [
                                    'class' => 'form-control',
                                    
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('description', __('Description') . ':') !!}
                                {!! Form::textarea('description', $car->description, [
                                    'class' => 'form-control',
                                    'placeholder' => __('Enter Description'),
                                   
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-sm-8">
                            <button type="submit" class="btn btn-primary pull-right">Save</button>
                        </div>

                    </div>

                    <br><br><br>
                </div>
            </div>
            {!! Form::close() !!}
    </section>
    <!-- /.content -->
@endsection
@section('javascript')
<script>
      $('form#car_add_form').validate({
        rules: {
            car_nick_name:{required:true},
            year:{required:true},
            exp_date:{required:true},
            make_by:{required:true},
            model:{required:true},
            vin_no:{required:true},
            licence_plate:{required:true},
            start_mileage:{required:true},
        },
        messages:{
            car_nick_name:{required:"Please Enter Car Nick Name."} ,
            year:{required:"Please Enter Year."},
            exp_date:{required:"Please Select Inspect. Exp Date."},
            make_by:{required:"Please Enter Make By."},
            model:{required:"Please Enter Model."},
            vin_no:{required:"Please Enter VIN Number."},
            licence_plate:{required:"Please Enter Licence Plate."},
            start_mileage:{required:"Please Enter Start Mileage."},
        },
      });
</script>
@endsection