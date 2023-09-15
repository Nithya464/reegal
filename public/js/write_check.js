$('#date').datetimepicker({
    format: moment_date_format,
    ignoreReadonly: true,
});

$(document).ready(function () {
    var write_check_table = $('#write_check_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            "url":'/cheques',
            "data": function(data) {
                var type = $('#selecttype').val();
                data.type = type;
            },
        },
       
        columnDefs: [
            {   
                orderable: false,
                searchable: false,
            },
        ],      
        columns: [
            { data: 'cheque_number', name: 'cheque_number' },
            { data: 'date', name: 'date' },
            { data: 'type', name: 'type' },
            { data: 'name', name: 'name' },
            { data: 'cheque_amount', name: 'cheque_amount' },
            { data: 'created_by', name: 'created_by' },
            { data: 'memo', name: 'memo' },
            { data: 'address', name: 'address' },
            { data: 'action', name: 'action' },
        ],
    });
    $('#selecttype').on('change', function () {
        write_check_table.ajax.reload();
    })
    $(document).on('submit', 'form#write_check_add_form', function (e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            beforeSend: function (xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            },
            success: function (result) {
                if (result.success == true) {
                    $('div.write_check_modal').modal('hide');
                    toastr.success(result.msg);
                    write_check_table.ajax.reload();
                    __enable_submit_button(form.find('button[type="submit"]'));
                } else {
                    toastr.error(result.msg);
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status === 422) {
                    __enable_submit_button(form.find('button[type="submit"]'));
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, error) {
                        console.log(field);
                        $('#' + field).after("<label class='error'>"+error[0]+"</label>");
                    });
                } 
            }
        });
    });

    $(document).on('click', 'button.edit_write_check_button', function () {
      
        $('div.write_check_modal').load($(this).data('href'), function () {
            $(this).modal('show');
            $('form#writecheck_edit_form').submit(function (e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();

                $.ajax({
                    method: 'POST',
                    url: $(this).attr('action'),
                    dataType: 'json',
                    data: data,
                    beforeSend: function (xhr) {
                        __disable_submit_button(form.find('button[type="submit"]'));
                    },
                    success: function (result) {
                        if (result.success == true) {
                            $('div.write_check_modal').modal('hide');
                            toastr.success(result.msg);
                            write_check_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                           
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, error) {
                                console.log(field);
                                $('#' + field).after("<label class='error'>"+error[0]+"</label>");
                            });
                        } 
                    }
                });
            });
        });
    });
    $(document).on('click', 'button.delete_write_check_button', function () {
        swal({
            title: "Are You Sure",
            text: "For Deleting Write Cheque",
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
                            write_check_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });
});