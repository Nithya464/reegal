@extends('layouts.app')
@section('title', __( 'Cities' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'Cities' )
        <small>@lang( 'Manage Your Cities' )</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'All Your Cities' )])
        @can('city.create')
            @slot('tool')
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                        data-href="{{action([\App\Http\Controllers\CityController::class, 'create'])}}" 
                        data-container=".city_modal">
                        <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                </div>
            @endslot
        @endcan
        @can('city.view')
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="city_table">
                    <thead>
                        <tr>
                            <th>@lang( 'State Name' )</th>
                            <th>@lang( 'City Name' )</th>
                            <th>@lang( 'Status' )</th>
                            <th>@lang( 'Action' )</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcan
    @endcomponent

    <div class="modal fade city_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->

@endsection

@section('javascript')
<script src="{{ asset('js/city.js?v=' . $asset_v) }}"></script>
@endsection