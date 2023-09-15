@extends('layouts.app')
@section('title', __( 'States' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'States' )
        <small>@lang( 'Manage Your States' )</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'All Your States' )])
        @can('state.create')
            @slot('tool')
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                        data-href="{{action([\App\Http\Controllers\StateController::class, 'create'])}}" 
                        data-container=".state_modal">
                        <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                </div>
            @endslot
        @endcan
        @can('state.view')
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="state_table">
                    <thead>
                        <tr>
                            <th>@lang( 'State Name' )</th>
                            <th>@lang( 'Abbreviation' )</th>
                            <th>@lang( 'Status' )</th>
                            <th>@lang( 'Action' )</th>
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
<script src="{{ asset('js/state.js?v=' . $asset_v) }}"></script>
@endsection
