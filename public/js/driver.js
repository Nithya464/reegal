$(document).ready(function () {
    var driver_table = $('#driver_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/drivers',
        columnDefs: [
            {
                // targets: 3,
                orderable: false,
                searchable: false,
            },
        ],
        columns: [
            { data: 'full_name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone_number', name: 'phone_number' },
            { data: 'state_name', name: 'state_name' },
            { data: 'city_name', name: 'city_name' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action' },
        ],
    });

    $(document).on('submit', 'form#driver_add_form', function (e) {
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
                    $('div.driver_modal').modal('hide');
                    toastr.success(result.msg);
                    driver_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status === 422) {
                   
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, error) {
                        $('#' + field).after("<label class='error'>"+error[0]+"</label>");
                    });
                } 
            }
        });
    });

    $(document).on('click', 'button.edit_driver_button', function () {
      
        $('div.driver_modal').load($(this).data('href'), function () {
            $(this).modal('show');

            $('form#driver_edit_form').submit(function (e) {
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
                            $('div.driver_modal').modal('hide');
                            toastr.success(result.msg);
                            driver_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                           
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, error) {
                                $('#' + field).after("<label class='error'>"+error[0]+"</label>");
                            });
                        } 
                    }
                });
            });
        });
    });

    $(document).on('click', 'button.delete_driver_button', function () {
        swal({
            title: LANG.sure,
            text: "Are want to delete this driver?",
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
                            driver_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    $(document).on('click', 'button.delete_state_button', function () {
        swal({
            title: "Are You Sure",
            text: "For Deleting State",
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
});