@extends('layouts.app')
@section('title', 'Taxes')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'tax.taxes' )
        <small>@lang( 'tax.manage_your_tax' )</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'tax.all_your_tax' )])
        @can('tax.create')
            @slot('tool')
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                        data-href="{{action([\App\Http\Controllers\TaxController::class, 'create'])}}" 
                        data-container=".tax_modal">
                        <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                </div>
            @endslot
        @endcan
        @can('tax.view')
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tax_table">
                    <thead>
                        <tr>
                            <th>@lang( 'tax.state_name' )</th>
                            <th>@lang( 'tax.tax_name' )</th>
                            <th>@lang( 'tax.tax' )</th>
                            <th>@lang( 'tax.print_label_text' )</th>
                            <th>@lang( 'tax.status' )</th>
                            <th>@lang( 'messages.action' )</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcan
    @endcomponent

    <div class="modal fade tax_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->

@endsection
@section('javascript')
<script src="{{ asset('js/tax.js?v=' . $asset_v) }}"></script>
@endsection
