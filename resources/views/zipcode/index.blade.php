@extends('layouts.app')
@section('title', __( 'Zipcodes' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'Zipcodes' )
        <small>@lang( 'Manage Your Zipcodes' )</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'All Your Zipcodes' )])
        @can('zipcode.create')
            @slot('tool')
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                        data-href="{{action([\App\Http\Controllers\ZipcodeController::class, 'create'])}}" 
                        data-container=".zipcode_modal">
                        <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                </div>
            @endslot
        @endcan
        @can('zipcode.view')
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="zipcode_table">
                    <thead>
                        <tr>
                            <th>@lang( 'State Name' )</th>
                            <th>@lang( 'City Name' )</th>
                            <th>@lang( 'Zipcode' )</th>
                            <th>@lang( 'Status' )</th>
                            <th>@lang( 'Action' )</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcan
    @endcomponent

    <div class="modal fade zipcode_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->

@endsection

@section('javascript')
<script src="{{ asset('js/zipcode.js?v=' . $asset_v) }}"></script>
@endsection