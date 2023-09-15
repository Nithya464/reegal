@extends('layouts.app')
@section('title', __( 'Cars' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'Cars' )
        <small>@lang( 'Manage Your Cars' )</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'All Your Cars' )])
        @can('car.create')
            @slot('tool')
                <div class="box-tools">
                    {{-- <button type="button" class="btn btn-block btn-primary btn-modal" 
                        data-href="{{action([\App\Http\Controllers\UnitController::class, 'create'])}}" 
                        data-container=".unit_modal">
                        <i class="fa fa-plus"></i> @lang( 'messages.add' )</button> --}}

                        <a class="btn btn-block btn-primary" 
                           href="{{action([\App\Http\Controllers\CarController::class, 'create'])}}"> <i class="fa fa-plus"></i>Add</a>
                </div>
            @endslot
        @endcan
        @can('car.view')
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="car_table">
                    <thead>
                        <tr>
                            <th>@lang( 'Car ID' )</th>
                            <th>@lang( 'Car Nic Name' )</th>
                            <th>@lang( 'Years' )</th>
                            <th>@lang( 'Make' )</th>
                            <th>@lang( 'Model' )</th>
                            <th>@lang( 'VIN No' )</th>
                            <th>@lang( 'Lisense Plate' )</th>
                            <th>@lang( 'Exp Date' )</th>
                            <th>@lang( 'Start Mileage' )</th>
                            <th>@lang( 'Load Order Type' )</th>
                            <th>@lang( 'status' )</th>
                            <th>@lang( 'Action' )</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcan
    @endcomponent

    <div class="modal fade unit_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->

@endsection
