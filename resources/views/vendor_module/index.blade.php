@extends('layouts.app')
@section('title', 'Vendor')

@section('content')
    <!-- Content Header (Page header) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <section class="content-header">
        <h1>@lang('Vendor List')</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => __('All Vendor List')])
            @can('vendor.create')
                @slot('tool')
                    <div class="box-tools">
                        <button type="button" class="btn btn-block btn-primary btn-modal"
                            data-href="{{ action([\App\Http\Controllers\VendorController::class, 'create']) }}">
                            <i class="fa fa-plus"></i> @lang('messages.add')</button>
                    </div>
                @endslot
            @endcan
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tax_table">
                    <thead>
                        <tr>
                            <th>@lang('Status')</th>
                            <th>@lang('Vendor ID')</th>
                            <th>@lang('Vendor Name')</th>
                            <th>@lang('Vendor Type')</th>
                            <th>@lang('Contact Person')</th>
                            <th>@lang('Phone No')</th>
                            <th>@lang('Office Contact')</th>
                            <th>@lang('Email ID')</th>
                            <th>@lang('action')</th>
                        </tr>
                    </thead>
                </table>
            </div>
            {{-- @endcan --}}
        @endcomponent

        {{-- @include('assign_driver.assigned_orders_details') --}}

    </section>
    <!-- /.content -->

@endsection
@section('javascript')
    <script src="{{ asset('js/vendor_module.js?v=' . $asset_v) }}"></script>
    <script>
        $(document).ready(function() {
            // Function to show the success message as a toaster notification
            function showSuccessMessage(message) {
                toastr.success(message);
            }

            // Check if the success message exists in the session and display it
            @if (session('success'))
                showSuccessMessage("{{ session('success') }}");
            @endif
        });
    </script>
@endsection
