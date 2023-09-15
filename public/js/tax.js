$(document).ready(function () {
    var tax_table = $('#tax_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/taxes',
        columnDefs: [
            {
                // targets: 3,
                orderable: false,
                searchable: false,
            },
        ],
        columns: [
            { data: 'state_name', name: 'state_name' },
            { data: 'name', name: 'name' },
            { data: 'tax', name: 'tax' },
            { data: 'label_text', name: 'label_text' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action' },
        ],
    });

    $(document).on('submit', 'form#tax_add_form', function (e) {
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
                    $('div.tax_modal').modal('hide');
                    toastr.success(result.msg);
                    tax_table.ajax.reload();
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

    $(document).on('click', 'button.edit_tax_button', function () {
      
        $('div.tax_modal').load($(this).data('href'), function () {
            $(this).modal('show');

            $('form#tax_edit_form').submit(function (e) {
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
                            $('div.tax_modal').modal('hide');
                            toastr.success(result.msg);
                            tax_table.ajax.reload();
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

    $(document).on('click', 'button.delete_tax_button', function () {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_unit,
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
                            units_table.ajax.reload();
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