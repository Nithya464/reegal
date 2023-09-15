$(document).ready(function () {
    var state_table = $('#state_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/states',
        columnDefs: [
            {   
                orderable: false,
                searchable: false,
            },
        ],
        columns: [
            { data: 'state_name', name: 'state_name' },
            { data: 'abbreviation', name: 'abbreviation' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action' },
        ],
    });

    $(document).on('submit', 'form#state_add_form', function (e) {
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
                    $('div.state_modal').modal('hide');
                    toastr.success(result.msg);
                    state_table.ajax.reload();
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

    $(document).on('click', 'button.edit_state_button', function () {
      
        $('div.state_modal').load($(this).data('href'), function () {
            $(this).modal('show');

            $('form#state_edit_form').submit(function (e) {
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
                            $('div.state_modal').modal('hide');
                            toastr.success(result.msg);
                            state_table.ajax.reload();
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
        });
    });

    $(document).on('click', 'button.delete_unit_button', function () {
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
                            state_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });
});