@extends('layouts.app')
@section('title', __( 'Write Cheque' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'Write Cheque' )
        <small>@lang( 'Write Cheque' )</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'All Your Write Cheque' )])
        @can('write_cheque.create')
            @slot('tool')
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                        data-href="{{action([\App\Http\Controllers\WriteChequeController::class, 'create'])}}" 
                        data-container=".write_check_modal">
                        <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                </div>
            @endslot
        @endcan
        {{-- <div class="row">
            <label> Cheque List</label>
            <div class="input-group col-sm-12">
                <input type="text" class="form-control" name="searchcheque" id="searchcheque"
                    placeholder="Search Cheque number"> <span class="input-group-btn">
                        </span>
                <input type="date" class="form-control " name="searchcheque" id="searchcheque"
                    placeholder="Search Cheque number"> <span class="input-group-btn">
                        </span>
                        <input type="date" class="form-control" name="searchcheque" id="searchcheque"
                    placeholder="Search Cheque number"> <span class="input-group-btn">
                        </span>
                    <select  name="type" class="form-control" id="selecttype">
                        <option value="">Select type</option>
                        <option value="employee">Employee</option>
                        <option value="vendor">Vendor</option>
                    </select><span class="input-group-btn">
                    </span>
                  
            </div>
        </div> --}}
        @php
            //$name = App\WriteCheque::with('employee','vendor')->get();
        @endphp
        {{-- <div class="row" style="margin-top: 5px">
            <div class="input-group col-sm-4">
            <select  name="type" class="form-control">
                <option>All name</option>
                @foreach($name as $nm)
                @php
                $data = '';
                $ids = '';
                if(isset($nm->employee_id)) {
                    $data =  $nm->employee->username;
                    $ids = $nm->employee_id;

                } else {
                    $data =  $nm->vendor->vendor_name;
                    $ids = $nm->vendor_id;
                }
                @endphp
                <option value="{{$nm->type}}-{{$ids}}" >{{$data}}</option>
                @endforeach
            </select>
            <span class="input-group-btn">
            </span>
            <button class="btn btn-primary" style="margin: 0 0 25px 5px;">Search</button>
            </div>
        </div> --}}
        @can('write_cheque.view')
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="write_check_table">
                    <thead>
                        <tr>
                            <th>@lang( 'Cheque Number' )</th>
                            <th>@lang( 'Cheque Date' )</th>
                            <th>@lang( 'Type' )</th>
                            <th>@lang( 'Name' )</th>
                            <th>@lang( 'Amount' )</th>
                            <th>@lang( 'Created By' )</th>
                            <th>@lang( 'Memo' )</th>
                            <th>@lang( 'Address' )</th>
                            <th>@lang( 'action' )</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcan
    @endcomponent

    <div class="modal fade write_check_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->

@endsection

@section('javascript')
<script src="{{ asset('js/write_check.js?v=' . $asset_v) }}"></script>

@endsection