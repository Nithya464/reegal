@extends('layouts.app')
@section('title', 'Driver')

@section('content')

    <!-- Content Header (Page header) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <section class="content-header">
        <h1>@lang('assigned_driver.assigned_driver')</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.filters', ['title' => __('report.filters')])
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('all_driver', __('All driver') . ':*') !!}
                    {!! Form::select(
                        'all_driver',
                        [
                            'option1' => __('Option 1'),
                            'option2' => __('Option 2'),
                        ],
                        null,
                        ['class' => 'form-control'],
                    ) !!}
                </div>
            </div>
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
        @component('components.widget', ['class' => 'box-primary', 'title' => __('assigned_driver.all_driver_Assigned')])
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tax_table">
                    <thead>
                        <tr>
                            <th>@lang('assigned_driver.assigned_date')</th>
                            <th>@lang('assigned_driver.driver_name')</th>
                            <th>@lang('assigned_driver.assigned_orders')</th>
                            <th>@lang('assigned_driver.action')</th>
                        </tr>
                    </thead>
                </table>
            </div>
            {{-- @endcan --}}
        @endcomponent

        @include('assign_driver.assigned_orders_details')

    </section>
    <!-- /.content -->

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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            var tax_table = $('#tax_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/assigned-driver',
                columnDefs: [{
                    // targets: 3,
                    orderable: false,
                    searchable: false,
                }, ],
                columns: [{
                        data: 'assigned_date',
                        name: 'assigned_date'
                    },
                    {
                        data: 'driver_name',
                        name: 'driver_name'
                    },
                    {
                        data: 'assigned_orders',
                        name: 'assigned_orders'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
            });

            $('#tax_table').on('click', '.view-driver-btn', function() {
                var data = tax_table.row($(this).parents('tr')).data();
                // Assuming your data has the 'plan_by', 'planning_date', and 'drivers' fields
                $('#driverName').text(data.plan_by);
                $('#assignedDate').text(data.planning_date);
                // Add more lines to display other driver details as needed

                // Show the modal
                $('#viewModal').modal('show');
            });
        });
    </script>
@endsection
