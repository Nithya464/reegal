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
$(document).ready(function () {
    var tax_table = $('#tax_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/vendors',
        columnDefs: [{
            // targets: 3,
            orderable: false,
            searchable: false,
        },],
        columns: [{
            data: 'status',
            name: 'status'
        },
        {
            data: 'vendor_id',
            name: 'vendor_id'
        },
        {
            data: 'vendor_name',
            name: 'vendor_name'
        },
        {
            data: 'sub_type',
            name: 'sub_type'
        },
        {
            data: 'contact_person',
            name: 'contact_person'
        },
        {
            data: 'cell',
            name: 'cell'
        },
        {
            data: 'office_1',
            name: 'office_1'
        },
        {
            data: 'email',
            name: 'email'
        },
        {
            data: 'action',
            name: 'action'
        }
        ],
    });
    // Handle the click event of the delete button
    $(document).on('click', 'button.delete_vendor_button', function () {
        swal({
            title: "Are You Sure",
            text: "For Deleting Vendor",
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
                            tax_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });
    $('#tax_table').on('click', '.view-driver-btn', function () {
        var data = tax_table.row($(this).parents('tr')).data();
        // Assuming your data has the 'plan_by', 'planning_date', and 'drivers' fields
        $('#driverName').text(data.plan_by);
        $('#assignedDate').text(data.planning_date);
        // Add more lines to display other driver details as needed

        // Show the modal
        $('#viewModal').modal('show');
    });
    $('.btn-modal').on('click', function () {
        var url = $(this).data('href'); // Get the URL from data-href attribute

        // Open the "create" page using the URL
        window.location.href = url;
    });

    
});
