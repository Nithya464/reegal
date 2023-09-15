@extends('layouts.app')
@section('title', __( 'Customer Location' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'Customer Location' )
        <small>@lang( 'Manage Your Customer Location' )</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'All Your Customer Location' )])
       
        @can('customer_location.view')
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="state_table">
                    <thead>
                        <tr>
                            <th>@lang( 'Customer ID' )</th>
                            <th>@lang( 'Customer Name' )</th>
                            <th>@lang( 'Sales Person' )</th>
                            <th>@lang( 'Route Name' )</th>
                            <th>@lang( 'Last Order Date' )</th>
                            <th>@lang( 'Address' )</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcan
    @endcomponent

    <div class="modal fade state_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->

@endsection

@section('javascript')
<script src="{{ asset('js/customer_location.js?v=' . $asset_v) }}"></script>
@endsection
