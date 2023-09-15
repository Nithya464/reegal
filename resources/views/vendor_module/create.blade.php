@extends('layouts.app')
@section('title', __('Vendor'))
@section('content')
    <style type="text/css">
        .form-group {
            margin-bottom: 30px;
        }

        .invalid-feedback.text-danger {
            padding: 0px;
            margin-bottom: -20px;
        }
    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Manage Vendor</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        {!! Form::open([
            'url' => action([\App\Http\Controllers\VendorController::class, 'store']),
            'method' => 'post',
            'id' => 'vandor_add_form',
        ]) !!}
        <div class="box box-solid">
            <div class="box-body">
                <h3>Vendor Details</h3>
                <br><br>
                <input type="hidden" name="sub_type">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('vendor_id', __('Vendor ID')) !!}
                                {!! Form::text('vendor_id', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('Vendor ID'),
                                    'readonly' => 'readonly',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('vendor_name', __('Vendor Name') . ':') !!}
                                <span style="color: red">*</span>
                                {!! Form::text('vendor_name', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('Vendor Name'),
                                ]) !!}
                                @error('vendor_name')
                                    <div class="invalid-feedback text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('status', __('Status') . ':') !!}
                                {!! Form::select('status', config('global.status_dropdown'), null, [
                                    'class' => 'form-control',
                                    'required',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('sub_type', __('Sub Type') . ':') !!}
                                <span style="color: red">*</span>
                                {!! Form::select('sub_type', config('global.subtype_options'), null, [
                                    'class' => 'form-control required-label',
                                    'placeholder' => __('Select Sub Type'),
                                ]) !!}
                                @error('sub_type')
                                    <div class="invalid-feedback text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('company_name', __('Company Name') . ':') !!}
                                <span style="color: red">*</span>
                                {!! Form::text('company_name', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('Company Name'),
                                ]) !!}
                                @error('company_name')
                                    <div class="invalid-feedback text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('contact_person', __('Contact Person') . ':') !!}
                                <span style="color: red">*</span>
                                {!! Form::text('contact_person', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('Contact Person'),
                                ]) !!}
                                @error('contact_person')
                                    <div class="invalid-feedback text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('designation', __('Designation') . ':') !!}
                                {!! Form::text('designation', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('Designation'),
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('cell', __('Cell') . ':') !!}
                                <span style="color: red">*</span>
                                {!! Form::text('cell', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('Cell'),
                                ]) !!}
                                @error('cell')
                                    <div class="invalid-feedback text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('fax', __('Fax') . ':') !!}
                                {!! Form::text('fax', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('Fax'),
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('email', __('Email') . ':') !!}
                                {!! Form::email('email', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('Email'),
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('office_1', __('Office 1') . ':') !!}
                                <span style="color: red">*</span>
                                {!! Form::text('office_1', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('Office 1'),
                                ]) !!}
                                @error('office_1')
                                    <div class="invalid-feedback text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('other_1', __('Other 1') . ':') !!}
                                {!! Form::text('other_1', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('Other 1'),
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('cc_email', __('CC Email') . ':') !!}
                                {!! Form::text('cc_email', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('CC Email'),
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('office_2', __('Office 2') . ':') !!}
                                {!! Form::text('office_2', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('Office 2'),
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('other_2', __('Other 2') . ':') !!}
                                {!! Form::text('other_2', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('Other 2'),
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('website', __('Website') . ':') !!}
                                {!! Form::text('website', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('Website'),
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                {!! Form::label('notes', __('Notes') . ':') !!}
                                {!! Form::textarea('notes', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('Notes'),
                                    'style' => 'width: 100%; height: 50px; resize: vertical;',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <h3>Address Details</h3>
                <br><br>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('address_1', __('Address 1')) !!}
                                <span style="color: red">*</span>
                                {!! Form::text('address_1', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('Address 1'),
                                ]) !!}
                                @error('address_1')
                                    <div class="invalid-feedback text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('address_2', __('Address 2') . ':') !!}
                                {!! Form::text('address_2', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('Address 2'),
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('zip_code', __('Zip Code') . ':') !!}
                                <span style="color: red">*</span>
                                {!! Form::text('zip_code', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('Zip Code'),
                                ]) !!}
                                @error('zip_code')
                                    <div class="invalid-feedback text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('city', __('City') . ':') !!}
                                <span style="color: red">*</span>
                                {!! Form::text('city', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('City'),
                                ]) !!}
                                @error('city')
                                    <div class="invalid-feedback text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('state', __('State') . ':') !!}
                                <span style="color: red">*</span>
                                {!! Form::text('state', null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('State'),
                                ]) !!}
                                @error('state')
                                    <div class="invalid-feedback text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('country', __('Country') . ':') !!}
                                <span style="color: red">*</span>
                                {!! Form::select('country', config('global.country_options'), null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('Country'),
                                ]) !!}
                                @error('country')
                                    <div class="invalid-feedback text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 text-center">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('vendors.index') }}" class="btn btn-primary">Back</a>
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
@endsection
