@extends('layouts.app')
@section('title', 'Manage Price Level')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'Manage Price Level' )
        <small>@lang( 'Manage your Price Level' )</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'All your Price Level' )])
        @can('price_level.create')
            @slot('tool')
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                        data-href="{{action([\App\Http\Controllers\PriceLevelController::class, 'create'])}}" 
                        data-container=".price_levels_modal">
                        <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                </div>
            @endslot
        @endcan
        @can('price_level.view')
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="price_levels_table">
                    <thead>
                        <tr>
                            <th>@lang( 'Price Level ID' )</th>
                            <th>@lang( 'Price Level Name' )</th>
                            <th>@lang( 'Customer Type' )</th>
                            <th>@lang( 'Status' )</th>
                            <th>@lang( 'Created By' )</th>
                            <th>@lang( 'messages.action' )</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcan
    @endcomponent

    <div class="modal fade price_levels_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->

@endsection
