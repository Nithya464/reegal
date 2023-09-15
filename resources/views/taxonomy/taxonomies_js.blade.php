<script type="text/javascript">
    $(document).ready(function() {

        function getTaxonomiesIndexPage() {
            var data = {
                category_type: $('#category_type').val()
            };
            $.ajax({
                method: "GET",
                dataType: "html",
                url: '/taxonomies-ajax-index-page',
                data: data,
                async: false,
                success: function(result) {
                    $('.taxonomy_body').html(result);
                }
            });
        }

        function initializeTaxonomyDataTable() {
            //Category table
            if ($('#category_table').length) {
                var category_type = $('#category_type').val();
                category_table = $('#category_table').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollCollapse: true,
                    aaSorting: [
                        [1, 'asc']
                    ],
                    ajax: '/taxonomies?type=' + category_type,
                    columns: [{
                            data: 'short_code',
                            name: 'short_code'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'website_sequence_no',
                            name: 'website_sequence_no'
                        },
                        {
                            data: 'show_on_website',
                            name: 'show_on_website'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    createdRow: function(row, data, dataIndex) {
                        if (data.is_parent > 0) {
                            var cell = $(row).find('td:eq(0)');
                            var existingContent = cell.html();
                            cell.html(
                                '<i style="margin:auto;" class="fa fa-plus-circle text-success cursor-pointer no-print taxonomy-rack-details" title="' +
                                LANG.details + '"></i>&nbsp;&nbsp;' + existingContent);
                        }
                    },
                });
            }
        }

        var detailRows = [];

        $('#category_table').on('click', 'tr td i.taxonomy-rack-details', function() {

            var i = $(this);
            var tr = $(this).closest('tr');
            var row = category_table.row(tr);
            console.log(row.data());
            var idx = $.inArray(tr.attr('id'), detailRows);
            if (row.child.isShown()) {
                i.addClass('fa-plus-circle text-success');
                i.removeClass('fa-minus-circle text-danger');
                row.child.hide();
                detailRows.splice(idx, 1);
            } else {
                i.removeClass('fa-plus-circle text-success');
                i.addClass('fa-minus-circle text-danger');
                row.child(get_taxonomy_details(row.data())).show();
                if (idx === -1) {
                    detailRows.push(tr.attr('id'));
                }
            }
        })

        function get_taxonomy_details(rowData) {
            var div = $('<div/>')
                .addClass('loading')
                .text('Loading...');

            $.ajax({
                url: '/taxonomies/' + rowData.id,
                dataType: 'html',
                success: function(data) {
                    div.html(data).removeClass('loading');
                },
            });

            return div;
        }

        // $('#category_table tbody').on( 'click', 'tr td i.taxonomy-rack-details', function () {
        //     alert('Ad');
        //     var i = $(this);
        //     var tr = $(this).closest('tr');
        //     var row = product_table.row( tr );
        //     var idx = $.inArray( tr.attr('id'), detailRows );

        //     if ( row.child.isShown() ) {
        //         i.addClass( 'fa-plus-circle text-success' );
        //         i.removeClass( 'fa-minus-circle text-danger' );

        //         row.child.hide();

        //         // Remove from the 'open' array
        //         detailRows.splice( idx, 1 );
        //     } else {
        //         i.removeClass( 'fa-plus-circle text-success' );
        //         i.addClass( 'fa-minus-circle text-danger' );

        //         row.child( get_taxonomy_details( row.data() ) ).show();

        //         // Add to the 'open' array
        //         if ( idx === -1 ) {
        //             detailRows.push( tr.attr('id') );
        //         }
        //     }
        // });

        @if (empty(request()->get('type')))
            getTaxonomiesIndexPage();
        @endif

        initializeTaxonomyDataTable();
    });
    $(document).on('submit', 'form#category_add_form', function(e) {
        e.preventDefault();
        var form = $(this);
        var data = form.serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            beforeSend: function(xhr) {
                __disable_submit_button(form.find('button[type="submit"]'));
            },
            success: function(result) {
                if (result.success === true) {
                    $('div.category_modal').modal('hide');
                    toastr.success(result.msg);
                    if (typeof category_table !== 'undefined') {
                        category_table.ajax.reload();
                    }

                    var evt = new CustomEvent("categoryAdded", {
                        detail: result.data
                    });
                    window.dispatchEvent(evt);

                    //event can be listened as
                    //window.addEventListener("categoryAdded", function(evt) {}
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });
    $(document).on('click', 'button.edit_category_button', function() {
        $('div.category_modal').load($(this).data('href'), function() {
            $(this).modal('show');

            $('form#category_edit_form').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var data = form.serialize();

                $.ajax({
                    method: 'POST',
                    url: $(this).attr('action'),
                    dataType: 'json',
                    data: data,
                    beforeSend: function(xhr) {
                        __disable_submit_button(form.find('button[type="submit"]'));
                    },
                    success: function(result) {
                        if (result.success === true) {
                            $('div.category_modal').modal('hide');
                            toastr.success(result.msg);
                            category_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });
        });
    });

    $(document).on('click', 'button.delete_category_button', function() {
        swal({
            title: LANG.sure,
            text: "This Category will be deleted.",
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
                    success: function(result) {
                        if (result.success === true) {
                            toastr.success(result.msg);
                            category_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });
</script>
