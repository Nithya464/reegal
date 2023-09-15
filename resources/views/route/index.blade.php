@extends('layouts.app')
@section('title', 'Manage Routes')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'Manage Routes' )
        <small>@lang( 'Manage your routes' )</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'All your routes' )])
        @can('route.create')
            @slot('tool')
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                        data-href="{{action([\App\Http\Controllers\RouteController::class, 'create'])}}" 
                        data-container=".routes_modal">
                        <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                </div>
            @endslot
        @endcan
        @can('route.view')
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="routes_table">
                    <thead>
                        <tr>
                            <th>@lang( 'Route ID' )</th>
                            <th>@lang( 'Route Name' )</th>
                            <th>@lang( 'Sales Person' )</th>
                            <th>@lang( 'Status' )</th>
                            <th>@lang( 'Customer' )</th>
                            <th>@lang( 'messages.action' )</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcan
    @endcomponent

    <div class="modal fade routes_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->

@endsection
