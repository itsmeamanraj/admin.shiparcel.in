$(document).ready(function () {
    $('#toggleReturnAddress').change(function () {
        if ($(this).is(':checked')) {
            $('#returnAddressDropdown').show();
        } else {
            $('#returnAddressDropdown').hide();
        }
    }).trigger('change'); // Ensure it runs on page load
});

var i = 0;
$('#btn_add_products').click(function () {
    i++;
    var append_text = '';

    append_text += '<hr>';
    append_text += '<div class="form-group mb-4" id="row' + i + '"><div class="row"><div class="col-md-4 col-lg-4 col-xl-4 form-group">';
    append_text += '<label for="product_name[]">Product Name<span class="text-danger">*</span></label>';
    append_text += '<input type="text" id="product_name[]" name="product_name[]" class="form-control" placeholder="Enter product name...">';

    append_text += '</div><div class="col-md-4 col-lg-4 col-xl-4 mb-4 form-group"><label for="product_quantity[]">Quantity<span class="text-danger">*</span></label>';
    append_text += '<input type="text" id="product_quantity" name="product_quantity[]" class="form-control" placeholder="Qty..." min="1">';

    append_text += '</div><div class="col-md-4 col-lg-4 col-xl-4 mb-4 form-group"><label for="product_value[]">Value<span class="text-danger">*</span></label>';
    append_text += '<input type="text" id="product_value" name="product_value[]" class="form-control valuesum" placeholder="Value..." value="0">';

    append_text += '</div><div class="col-md-4 col-lg-4 col-xl-4 form-group"><label for="product_category[]">Category</label>';
    append_text += '<input type="text" id="product_category" name="product_category[]" class="form-control" placeholder="Product category...">';

    append_text += '</div><div class="col-md-4 col-lg-4 col-xl-4 mb-4 form-group"><label for="product_sku[]">SKU</label>';
    append_text += '<input type="text" id="product_sku" name="product_sku[]" class="form-control" placeholder="SKU">';
    // append_text += '</div></div><div class="component-group"><div class="col-md-2"><label for="product_hsnsac[]">HSN/SAC</label>';
    // append_text += '<input type="text" id="product_hsnsac" name="product_hsnsac[]" class="form-control" placeholder="HSN/SAC...">';

    // append_text += '</div></div><div class="component-group"><div class="col-md-1"><label for="product_taxper[]">GST %<span class="text-danger">*</span></label>';
    // append_text += '<input type="text" id="product_taxper" name="product_taxper[]" class="form-control" placeholder="Tax %" value="0">';

    append_text += '</div><div class="col-md-3 col-lg-3 col-xl-3" style="">';
    append_text += '<label>&nbsp;</label><br><button type="button" class="btn   btn-danger btn_remove_product mt-2" data-toggle="tooltip" title="Remove Product" id="' + i + '" name="btn_remove_product"><i class="fa fa-trash-alt m-0"></i></button>';
    append_text += '</div></div></div>';

    // alert(i);
    $('#div_add_products').append(append_text);
});

$(document).on('click', '.btn_remove_product', function () {
    var button_id = $(this).attr("id");
    // alert(button_id);
    $('#row' + button_id + '').remove();
    calculateSum();
});


function calculateSum() {
    var sum = 0;
    $(".valuesum").each(function () {
        if (!isNaN(this.value) && this.value.length != 0)
            sum += parseFloat(this.value);
    });
    // alert(sum.toFixed(2));
    $("#order_amount").val(sum.toFixed(2));
    $("#total_amount").val((parseFloat(sum) + parseFloat($("#extra_charges").val())).toFixed(2));
    if (sum >= parseFloat(50000)) {
        $(".ewaybill").css("display", "block");
        $(".tipinfo").css("display", "block");
    } else {
        $(".ewaybill").css("display", "none");
        $(".tipinfo").css("display", "none");
    }
    calculatecod();
}

function calculatecod() {
    if ($("#payment_mode").val() == 'cod')
        $("#cod_amount").val($("#total_amount").val()).attr('readonly', false);
    else
        $("#cod_amount").val('0').attr('readonly', true);
}


// =============================== Wizard Step Js Start ================================
$(document).ready(function () {
    // click on next button
    $('.form-wizard-next-btn').on("click", function () {
        var parentFieldset = $(this).parents('.wizard-fieldset');
        var currentActiveStep = $(this).parents('.form-wizard').find('.form-wizard-list .active');
        var next = $(this);
        var nextWizardStep = true;
        // parentFieldset.find('.wizard-required').each(function () {
        //     var thisValue = $(this).val();

        //     if (thisValue == "") {
        //         $(this).siblings(".wizard-form-error").show();
        //         nextWizardStep = false;
        //     }
        //     else {
        //         $(this).siblings(".wizard-form-error").hide();
        //     }
        // });
        if (nextWizardStep) {
            next.parents('.wizard-fieldset').removeClass("show", "400");
            currentActiveStep.removeClass('active').addClass('activated').next().addClass('active', "400");
            next.parents('.wizard-fieldset').next('.wizard-fieldset').addClass("show", "400");
            $(document).find('.wizard-fieldset').each(function () {
                if ($(this).hasClass('show')) {
                    var formAtrr = $(this).attr('data-tab-content');
                    $(document).find('.form-wizard-list .form-wizard-step-item').each(function () {
                        if ($(this).attr('data-attr') == formAtrr) {
                            $(this).addClass('active');
                            var innerWidth = $(this).innerWidth();
                            var position = $(this).position();
                            $(document).find('.form-wizard-step-move').css({ "left": position.left, "width": innerWidth });
                        } else {
                            $(this).removeClass('active');
                        }
                    });
                }
            });
        }
    });
    //click on previous button
    $('.form-wizard-previous-btn').on("click", function () {
        var counter = parseInt($(".wizard-counter").text());;
        var prev = $(this);
        var currentActiveStep = $(this).parents('.form-wizard').find('.form-wizard-list .active');
        prev.parents('.wizard-fieldset').removeClass("show", "400");
        prev.parents('.wizard-fieldset').prev('.wizard-fieldset').addClass("show", "400");
        currentActiveStep.removeClass('active').prev().removeClass('activated').addClass('active', "400");
        $(document).find('.wizard-fieldset').each(function () {
            if ($(this).hasClass('show')) {
                var formAtrr = $(this).attr('data-tab-content');
                $(document).find('.form-wizard-list .form-wizard-step-item').each(function () {
                    if ($(this).attr('data-attr') == formAtrr) {
                        $(this).addClass('active');
                        var innerWidth = $(this).innerWidth();
                        var position = $(this).position();
                        $(document).find('.form-wizard-step-move').css({ "left": position.left, "width": innerWidth });
                    } else {
                        $(this).removeClass('active');
                    }
                });
            }
        });
    });
    //click on form submit button
    $(document).on("click", ".form-wizard .form-wizard-submit", function () {
        var parentFieldset = $(this).parents('.wizard-fieldset');
        var currentActiveStep = $(this).parents('.form-wizard').find('.form-wizard-list .active');
        parentFieldset.find('.wizard-required').each(function () {
            var thisValue = $(this).val();
            if (thisValue == "") {
                $(this).siblings(".wizard-form-error").show();
            }
            else {
                $(this).siblings(".wizard-form-error").hide();
            }
        });
    });
    // focus on input field check empty or not
    $(".form-control").on('focus', function () {
        var tmpThis = $(this).val();
        if (tmpThis == '') {
            $(this).parent().addClass("focus-input");
        }
        else if (tmpThis != '') {
            $(this).parent().addClass("focus-input");
        }
    }).on('blur', function () {
        var tmpThis = $(this).val();
        if (tmpThis == '') {
            $(this).parent().removeClass("focus-input");
            $(this).siblings(".wizard-form-error").show();
        }
        else if (tmpThis != '') {
            $(this).parent().addClass("focus-input");
            $(this).siblings(".wizard-form-error").hide();
        }
    });
});
// =============================== Wizard Step Js End ================================