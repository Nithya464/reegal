$(document).ready(function () {


    // $(".is_default").on('change', function () {
    //     var $this = $(this);
    //     var id = $this.data('id');
    //     $('.is_default').prop('disabled', false);
    //     if ($this.is(':checked')) {
    //         $('#new_status' + id).val(1);
    //         $('#status' + id).prop('checked', true);
    //         $('#status' + id).prop('disabled', true);
    //         $(".is_default").prop('checked', false);
    //         $this.prop('checked', true).val(1);
    //     }
    //     $(".status").not("#status" + id).prop('disabled', false);
       
    // });





    $(".status").on('change', function () {
        var $this = $(this);
        var id = $(this).data('id');
        if ($(this).is(':checked')) {
            $(this).val(1);
            $('#new_status' + id).val(1);
        } else {
            $(this).val(2);
            $('#new_status' + id).val(2);
        }
        $(".status").not($this).not(':checked').val(2);

    });

    $(".is_free").on('change', function () {
        var $this = $(this);
        if ($(this).is(':checked')) {
            $(this).val(1);
        } else {
            $(this).val(2);
        }
        $(".is_free").not($this).not(':checked').val(2);

    });

    $(".is_free").each(function () {
        if ($(this).is(":checked")) {
            $(this).val(1);
        } else {
            $(this).val(2);
        }
    });





    $(".is_default").each(function () {
        var id = $(this).data('id');
      
        if ($(this).is(":checked")) {
            $('#status' + id).prop('checked', true);
            $('#status' + id).prop('disabled', true);
            $(this).val(1);
            $('#new_default' + id).val(1);
            $('#new_status' + id).val(1);
        } else {
            $('#new_default' + id).val(2);
            $(this).val(2);
        }
       
    });

    $('#cost_price1').on('keyup', function () {

        $cost_price1 = $(this).val();
        $qty2 = $('#qty2').val();
        $qty3 = $('#qty3').val();
        // if ($qty2 == 0) {
        //     $('#qty2').val(1);
        // }
        // if ($qty3 == 0) {
        //     $('#qty3').val(1);
        // }
        // console.log($qty2);
        // console.log($qty3);

        $cost_price2 = (parseFloat($cost_price1) * parseFloat($qty2));
        $cost_price3 = (parseFloat($cost_price1) * parseFloat($qty3));

        if (isNaN($cost_price2)) {
            $cost_price2 = 0;
        }
        if (isNaN($cost_price3)) {
            $cost_price3 = 0;
        }

        if (!($('#cost_price2').prop('readonly'))) {
            $('#cost_price2').val($cost_price2);
        }

        if (!($('#cost_price3').prop('readonly'))) {
            $('#cost_price3').val($cost_price3);
        }
    });

    $('#wh_min_price1').on('keyup', function () {
        $wh_min_price1 = $(this).val();
        console.log($wh_min_price1);
        $qty2 = $('#qty2').val();
        $qty3 = $('#qty3').val();
        // if ($qty2 == 0) {
        //     $('#qty2').val(1);
        // }
        // if ($qty3 == 0) {
        //     $('#qty3').val(1);
        // }
        // console.log($qty2);
        // console.log($qty3);

        $wh_min_price2 = (parseFloat($wh_min_price1) * parseFloat($qty2));
        $wh_min_price3 = (parseFloat($wh_min_price1) * parseFloat($qty3));

        if (isNaN($wh_min_price2)) {
            $wh_min_price2 = 0;
        }
        if (isNaN($wh_min_price3)) {
            $wh_min_price3 = 0;
        }


        if (!($('#wh_min_price2').prop('readonly'))) {
            $('#wh_min_price2').val($wh_min_price2);
        }

        if (!($('#wh_min_price3').prop('readonly'))) {
            $('#wh_min_price3').val($wh_min_price3);
        }
    });

    $('#min_retail_price1').on('keyup', function () {

        $min_retail_price1 = $(this).val();
        $qty2 = $('#qty2').val();
        $qty3 = $('#qty3').val();
        // if ($qty2 == 0) {
        //     $('#qty2').val(1);
        // }
        // if ($qty3 == 0) {
        //     $('#qty3').val(1);
        // }

        $min_retail_price2 = (parseFloat($min_retail_price1) * parseFloat($qty2));
        $min_retail_price3 = (parseFloat($min_retail_price1) * parseFloat($qty3));

        if (isNaN($min_retail_price2)) {
            $min_retail_price2 = 0;
        }
        if (isNaN($min_retail_price3)) {
            $min_retail_price3 = 0;
        }

        if (!($('#min_retail_price2').prop('readonly'))) {
            $('#min_retail_price2').val($min_retail_price2);
        }

        if (!($('#min_retail_price3').prop('readonly'))) {
            $('#min_retail_price3').val($min_retail_price3);
        }
    });

    $('#base_price1').on('keyup', function () {

        $base_price1 = $(this).val();
        $qty2 = $('#qty2').val();
        $qty3 = $('#qty3').val();
        // if ($qty2 == 0) {
        //     $('#qty2').val(1);
        // }
        // if ($qty3 == 0) {
        //     $('#qty3').val(1);
        // }

        $base_price2 = (parseFloat($base_price1) * parseFloat($qty2));
        $base_price3 = (parseFloat($base_price1) * parseFloat($qty3));

        if (isNaN($base_price2)) {
            $base_price2 = 0;
        }
        if (isNaN($base_price3)) {
            $base_price3 = 0;
        }

        if (!($('#base_price2').prop('readonly'))) {
            $('#base_price2').val($base_price2);
        }

        if (!($('#base_price3').prop('readonly'))) {
            $('#base_price3').val($base_price3);
        }
    });


    $('#qty2').on('keyup', function () {
        $qty2 = $(this).val();
        $cost_price1 = $('#cost_price1').val();
        $cost_price = (parseFloat($cost_price1) * parseFloat($qty2));
        $wh_min_price1 = $('#wh_min_price1').val();
        $wh_min_price = (parseFloat($wh_min_price1) * parseFloat($qty2));
        $min_retail_price1 = $('#min_retail_price1').val();
        $min_retail_price = (parseFloat($min_retail_price1) * parseFloat($qty2));
        $base_price1 = $('#base_price1').val();
        $base_price = (parseFloat($base_price1) * parseFloat($qty2));

        if (isNaN($cost_price)) {
            $cost_price = 0;
        }
        if (isNaN($wh_min_price)) {
            $wh_min_price = 0;
        }
        if (isNaN($min_retail_price)) {
            $min_retail_price = 0;
        }
        if (isNaN($base_price)) {
            $base_price = 0;
        }
        $('#cost_price2').val($cost_price);
        $('#wh_min_price2').val($wh_min_price);
        $('#min_retail_price2').val($min_retail_price);
        $('#base_price2').val($base_price);
        console.log($cost_price);
    });

    $('#qty3').on('keyup', function () {
        $qty3 = $(this).val();
        $cost_price1 = $('#cost_price1').val();
        $cost_price = (parseFloat($cost_price1) * parseFloat($qty3));
        $wh_min_price1 = $('#wh_min_price1').val();
        $wh_min_price = (parseFloat($wh_min_price1) * parseFloat($qty3));
        $min_retail_price1 = $('#min_retail_price1').val();
        $min_retail_price = (parseFloat($min_retail_price1) * parseFloat($qty3));
        $base_price1 = $('#base_price1').val();
        $base_price = (parseFloat($base_price1) * parseFloat($qty3));

        if (isNaN($cost_price)) {
            $cost_price = 0;
        }
        if (isNaN($wh_min_price)) {
            $wh_min_price = 0;
        }
        if (isNaN($min_retail_price)) {
            $min_retail_price = 0;
        }
        if (isNaN($base_price)) {
            $base_price = 0;
        }
        $('#cost_price3').val($cost_price);
        $('#wh_min_price3').val($wh_min_price);
        $('#min_retail_price3').val($min_retail_price);
        $('#base_price3').val($base_price);
    });

    // Attach event listener to checkboxes with class "create"
    $('.create').on('change', function () {
        var $this = $(this);
        var id = $this.data('id');
        if (id === 1) {
            $this.prop('checked', true);
        }
        var row = $(this).closest('tr'); // Get the closest row element to the checkbox
        var inputs = row.find(':input[type="text"]'); // Find all input elements within the row

        if ($(this).is(':checked')) {
            //inputs.prop('required', true); // Add the "required" attribute to inputs
            inputs.prop('readonly', false);
            inputs.prop('disabled', false);
            row.addClass('validated'); // Add a class to the row for styling
            $this.prop('checked', true);
            $cost_price = $('#cost_price1').val();
            $min_retail_price = $('#min_retail_price1').val();
            $wh_min_price = $('#wh_min_price1').val();
            $base_price = $('#base_price1').val();

            $('#status' + id).prop('disabled', false);
            $('#is_default' + id).prop('disabled', false);
            $('#is_free' + id).prop('disabled', false);
            $('#qty' + id).prop('disabled', false);

            $('#qty' + id).val(1);
            $('#cost_price' + id).val($cost_price);
            $('#wh_min_price' + id).val($wh_min_price);
            $('#min_retail_price' + id).val($min_retail_price);
            $('#base_price' + id).val($base_price);

        } else {
            //inputs.prop('required', false); // Remove the "required" attribute from inputs
            inputs.prop('readonly', true);
            row.removeClass('validated'); // Remove the class if checkbox is unchecked
            $this.prop('checked', false);
            $('#status' + id).prop('disabled', true);
            $('#is_default' + id).prop('disabled', true);
            $('#is_free' + id).prop('disabled', true);
            // $('#qty' + id).prop('disabled', true);

            $('#qty' + id).val(0);
            $('#cost_price' + id).val(0);
            $('#wh_min_price' + id).val(0);
            $('#min_retail_price' + id).val(0);
            $('#base_price' + id).val(0);

        }
    });

    $(".create").each(function () {
        var id = $(this).data('id');

        if (id === 1) {
            $(this).prop('checked', true);
            // $(this).prop('disabled',true);
            $('#status' + id).prop('checked', true);
        }
        var row = $(this).closest('tr'); // Get the closest row element to the checkbox
        var inputs = row.find(':input[type="text"]'); // Find all input elements within the row
        // var newInputs=row.find(':input');
        if ($(this).is(':checked')) {
            // inputs.prop('required', true); // Add the "required" attribute to inputs
            $(this).prop('checked', true);
            inputs.prop('readonly', false);
            row.addClass('validated'); // Add a class to the row for styling
            $('#qty1').prop('readonly',true);

        } else {
            $(this).prop('checked', false);
            // inputs.prop('required', false); // Remove the "required" attribute from inputs
            inputs.prop('readonly', true);
            row.removeClass('validated'); // Remove the class if checkbox is unchecked
            $('#qty' + id).val(0);
            $('#status' + id).prop('disabled', true);
            $('#is_default' + id).prop('disabled', true);
            $('#is_free' + id).prop('disabled', true);
            // $('#qty' + id).prop('disabled', true);
            $('#cost_price' + id).removeClass('document').val(0);
            $('#wh_min_price' + id).removeClass('document').val(0);
            $('#min_retail_price' + id).removeClass('document').val(0);
            $('#base_price' + id).removeClass('document').val(0);

        }
    });
    // Initialize form validation
    // $('#product_add_form').validate({
    //     errorPlacement: function (error, element) {
    //         // Display the error message in a custom manner
    //         if (element.hasClass("create")) {
    //             error.insertAfter(element.closest('tr'));
    //         } else {
    //             error.insertAfter(element);
    //         }
    //     },
    //     submitHandler: function (form) {
    //         // Form submission callback
    //         form.submit();
    //     }
    // });
   
    // $('.is_default').on('change', function () {
    //     var id = $(this).data('id');
    //     var newValue = $(this).is(':checked') ? 1 : 2;
    //     $('#new_default' + id).val(newValue);
       
    //     getValue(id);
    // });

    // function getValue(id) {
        
    //     $('input[name="new_default[]"]').each(function () {
    //         var newValue = $(this).val() == 1 ? 1 : 2;
    //         if ($(this).val() == id) {
    //             $('#new_default' + $(this).val()).val(1);
    //         } else {
    //             $('#new_default' + $(this).val()).val(2);
    //         }
    //         console.log(newValue);
    //     });
    // }

    $('.is_default').on('click', function () {
        var id = $(this).data('id');
        console.log(id);
        var newValue = $(this).is(':checked') ? 1 : 2;
        
        // Set the value of the selected hidden input to newValue
        $('#new_default' + id).val(newValue);
        
        
        // Set the value of other hidden inputs to 2
        $('.is_default').not(this).each(function () {
            console.log(1);
            var otherId = $(this).data('id');
            $('#new_default' + otherId).val(2).prop('checked', false);;
            
        });
    });


    // $('#unit_price input').prop('disabled', true);
    $(document).on('keyup', '#srp', function () {
        if ($(this).val() > 0) {
            var rowIndex = $(this).closest('tr').index();
            var row = $('#srp tr').eq(rowIndex);

            console.log(rowIndex);
            $('[name="cost_price[]"]:not([readonly])').attr('min', $(this).val());
            $('[name="wh_min_price[]"]:not([readonly])').attr('min', $(this).val());
            $('[name="min_retail_price[]"]:not([readonly])').attr('min', $(this).val());
            $('[name="base_price[]"]:not([readonly])').attr('min', $(this).val());
            $('[name="is_default[0]"]:not([disabled])').prop('checked', true);
            $('#new_default1').val(1);
            // $('#unit_price tr:first-child input').prop('disabled', false);
            // $('#unit_price tr input[name^="create"]').prop('disabled', false);
        }
    });

    $('#re_order_mark').on('input', function() {
        var sanitizedValue = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(sanitizedValue);
    });




});