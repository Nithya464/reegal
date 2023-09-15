@extends('layouts.app')
@section('title', __( 'Manage Expense' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'Manage Expense' )
        <small>@lang( 'Manage Your Expense' )</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'All Your Expense' )])
        @can('manage_expense.create')
            @slot('tool')
                <div class="box-tools">
                    <a class="btn btn-block btn-primary" href="{{action([\App\Http\Controllers\ManageExpenseController::class, 'create'])}}"> <i class="fa fa-plus"></i>Add</a>
                </div>
            @endslot
        @endcan
        @can('manage_expense.view')
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="manage_expense_table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Payment Mode')</th>
                            <th>@lang('Payment Source')</th>
                            <th>@lang('Expense Description')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcan
    @endcomponent

   

</section>
<!-- /.content -->

@endsection

@section('javascript')
<script>
$(document).ready(function () {
    var manage_expense_table = $('#manage_expense_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/manage_expenses',
        columnDefs: [
            {   
                orderable: false,
                searchable: false,
            },
        ],
        columns: [
            { data: 'category', name: 'category' },
            { data: 'date', name: 'date' },
            { data: 'expense_amount', name: 'expense_amount' },
            { data: 'payment_method', name: 'payment_method' },
            { data: 'payment_source', name: 'payment_source' },
            { data: 'description', name: 'description' },
            { data: 'action', name: 'action' },
        ],
    });

    $(document).on('click', 'button.delete_expense_button', function () {
        swal({
            title: "Are You Sure",
            text: "For Deleting Expense",
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function (result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            manage_expense_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });
});
</script>

@endsection
