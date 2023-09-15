$(document).ready(function () {
    var table = $('#zipcode_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/zipcodes',
        columnDefs: [
            {   
                orderable: false,
                searchable: false,
            },
        ],
        columns: [
            { data: 'state_name', name: 'state_name' },
            { data: 'city_name', name: 'city_name' },
            { data: 'zipcode', name: 'zipcode' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action' },
        ],
    });

    $(document).on('submit', 'form#zipcode_add_form', function (e) {
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
                    $('div.zipcode_modal').modal('hide');
                    toastr.success(result.msg);
                    table.ajax.reload();
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

    $(document).on('click', 'button.edit_zipcode_button', function () {
      
        $('div.zipcode_modal').load($(this).data('href'), function () {
            $(this).modal('show');

            $('form#zipcode_edit_form').submit(function (e) {
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
                            $('div.zipcode_modal').modal('hide');
                            toastr.success(result.msg);
                            table.ajax.reload();
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

    
  
   

    $(document).on('click', 'button.delete_zipcode_button', function () {
        swal({
            title: "Are You Sure",
            text: "For Deleting Zipcode",
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
                            table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    $(document).on('change', '#state_id', function () {
        var stateId = $(this).val();
    
        $.ajax({
            url: '/get-cities/' + stateId,
            type: 'GET',
            success: function(response) {
                // Handle the response and update your city select element
                var citySelect = $('#city_id');
                citySelect.empty();
    
                $.each(response, function(key, value) {
                    citySelect.append($('<option></option>').attr('value', value.id).text(value.city_name));
                });
            },
            error: function(xhr, status, error) {
                // Handle the error if necessary
                console.log(error);
            }
        });
    });

});