@extends('layouts.app')
@section('title', 'Taxes')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('driver_log.driver_log')</h1>
        <!-- <ol class="breadcrumb">
                                                        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                                                        <li class="active">Here</li>
                                                    </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.filters', ['title' => __('report.filters')])
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('from_date', __('From Date') . ':*') !!}
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        {!! Form::text('from_date', @format_datetime('now'), ['class' => 'form-control', 'readonly', 'required']) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('to_date', __('To Date') . ':*') !!}
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                        {!! Form::text('to_date', @format_datetime('now'), ['class' => 'form-control', 'readonly', 'required']) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group" style="margin-top: 25px">
                    <button type="button" class="btn btn-block btn-primary btn-modal">
                        @lang('Search')</button>
                </div>
            </div>
        @endcomponent
        @component('components.widget', ['class' => 'box-primary', 'title' => __('tax.all_your_tax')])
            {{-- @can('brand.create') --}}
            @slot('tool')
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal"
                        data-href="{{ action([\App\Http\Controllers\TaxController::class, 'create']) }}"
                        data-container=".tax_modal">
                        <i class="fa fa-plus"></i> @lang('messages.add')</button>
                </div>
            @endslot
            {{-- @endcan --}}
            {{-- @can('tax.view') --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tax_table">
                    <thead>
                        <tr>
                            <th>@lang('driver_log.plan_by')</th>
                            <th>@lang('driver_log.planning_date')</th>
                            <th>@lang('driver_log.planning_id')</th>
                            <th>@lang('driver_log.drivers')</th>
                            <th>@lang('driver_log.stops')</th>
                            <th>@lang('driver_log.orders')</th>
                        </tr>
                    </thead>
                </table>
            </div>
            {{-- @endcan --}}
        @endcomponent

        <div class="modal fade tax_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>

    </section>
    <!-- /.content -->
    @include('driver_log.driver_log_details')

@endsection

@section('javascript')
    <script>
        $('#from_date').datetimepicker({
            format: moment_date_format,
            ignoreReadonly: true,
        });

        $('#to_date').datetimepicker({
            format: moment_date_format,
            ignoreReadonly: true,
        });

        $(document).ready(function() {
            var tax_table = $('#tax_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/driver/log',
                columnDefs: [{
                    targets: 2, // Column index for 'planning_id'
                    render: function(data, type, row, meta) {
                        return '<a href="#" class="view-driver" data-planning-id="' + data +
                            '">' + data + '</a>';
                    }
                }, {
                    targets: 5, // Column index for 'orders'
                    orderable: false,
                    searchable: false,
                }],
                columns: [{
                        data: 'plan_by',
                        name: 'plan_by'
                    },
                    {
                        data: 'planning_date',
                        name: 'planning_date'
                    },
                    {
                        data: 'planning_id',
                        name: 'planning_id'
                    },
                    {
                        data: 'drivers',
                        name: 'drivers'
                    },
                    {
                        data: 'stops',
                        name: 'stops'
                    },
                    {
                        data: 'orders',
                        name: 'orders'
                    }
                ],
            });

            $(document).on('click', '.view-driver', function() {
                var planningId = $(this).data('planning-id');
                // Here, you can use the planningId to fetch additional details from the server via AJAX
                // and populate the modal with the fetched data
                // For now, I'm just showing a sample to open the modal with the planningId
                $('#viewModal').modal('show');
            });
        });
    </script>
@endsection
