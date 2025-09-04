/*
    File: Main Js File
*/

$.ajaxSetup({
    "error": function (XMLHttpRequest, textStatus, errorThrown) {
        if(XMLHttpRequest.status == 404){
            if(XMLHttpRequest.responseJSON.message){
               showNotify('error',XMLHttpRequest.responseJSON.message);
            }else{
                showNotify('error','Something went wrong.');
            }
        }
    }
});

$('input, textarea').on('blur', function() {
    var trimmedValue = $(this).val().trim();
    $(this).val(trimmedValue);
});

/** validations */
$.validator.setDefaults({
    highlight: function(element) {
        $(element).closest('.form-group').addClass('has-error');
    },
    unhighlight: function(element) {
        $(element).closest('.form-group').removeClass('has-error');
        $(element).closest('.input-group').removeClass('has-error');
        $(element).parent('div').removeClass('has-error');
    },
    errorElement: 'span',
    errorClass: 'help-block',
    errorPlacement: function(error, element) {
        if (element.parent('.input-group').length) {
            error.insertAfter($(element).parent()).css('color', 'red');
        } else if ($(element).hasClass("custom-select")) {
            error.insertAfter($(element)).css('color', 'red');
        } else if ($(element).hasClass("form-select")) {
            if($(element).next().hasClass('select2')){
                error.insertAfter($(element).next()).css('color', 'red');
            }else{
                error.insertAfter($(element)).css('color', 'red');
            }
        }else if ($(element).attr("id") == "client_image") {
            error.insertAfter($(element).parent().parent()).css('color', 'red');
        } else if ($(element).hasClass('tom-select') || $(element).hasClass('tomselected')) {
            error.insertAfter($(element).next()).css('color', 'red');
        } else {
            error.insertAfter($(element)).css('color', 'red');
        }
    },
    invalidHandler: function(form, validator) {}
});

$(function(param) {
    $('form').validate({
        rules: {
            confirm_password: {
                equalTo: '#password',
            }
        }
    });

    $('#passwordForm').validate({
        rules: {
            confirm_password: {
                equalTo: '#password',
            }
        }
    });

    $.validator.addMethod("email", function(e, t) {
        return this.optional(t) || /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(e)
    }, "Please enter a valid email address.");

    $.validator.addMethod("passcheck", function(e, t) {
        return this.optional(t) || /^(?=.*\d)(?=.)(?=.*[a-zA-Z]).{8,30}$/.test(e)
        //return this.optional(t) || /^(?=.*\d)(?=.*[a-zA-Z])[\w~@#$%^&*+=`|{}:;!'".?<>\"()\[\]-]{8,30}$/.test(e)
    }, "Password must contain at least 8 and max 30 characters including one letter and one number.");

    $.validator.addMethod("notempty", function(value, element) {
        return /^(?!\s*$).+/.test(value);
    }, "Please enter value, is required.");

    jQuery.validator.addMethod("nospecialChar", function(value, element) {
        var isValid = false;
        var regex = /^[a-zA-Z0-9 ' ’]*$/;
        isValid = regex.test($(element).val());
        return isValid;
        //return this.optional(element) || /^[A-Za-z0-9 ']+$/.test(value);
    }, "Please do not include special characters.");

    jQuery.validator.addMethod("noSpace", function(value, element) {
        var isValid = true;
        var firstChr = value.substring(0, 1);
        if ($.trim(firstChr) == "") {
            isValid = false;
        }
        return isValid;
    }, "No space please and don't leave it empty");

    jQuery.validator.addMethod("url", function(val, elem) {
        if (val.length == 0) {
            return true;
        }

        if (!/^(https?|ftp):\/\//i.test(val)) {
            val = 'https://' + val;
            $(elem).val(val);
        }
        return /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(val);
    });

    //^(\+\d{1,2}\s?)?1?\-?\.?\s?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$
    $.validator.addMethod("phoneNumber", function(e, t) {
        return this.optional(t) || /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/.test(e)
    }, "Please enter a valid phone number");

    $.validator.addMethod("alfaname", function(e, t) {
        return this.optional(t) || /^[\w'\-,.][^0-9_!¡?÷?¿/\\+=@#$%ˆ&*(){}|~<>;:[\]]{2,}$/.test(e)
    }, "Please enter a valid name");

    /**Tool Tips */
    updateToopTip();
});

var elementsNameStr = "[name=name]";
$(document).on("keydown", elementsNameStr, function(e) {
    /* // Allow: backspace, delete, tab, escape, enter and . */
    var key = e.keyCode;
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 || (e.keyCode === 65) ||
        (e.keyCode >= 35 && e.keyCode <= 40)) {
        return;
    }
    if ((key >= 48 && key <= 57) || (key == 187 || key == 219 || key == 221 || key == 189) || (key >= 96 && key <= 105)) {
        e.preventDefault();
    }
    if ((e.ctrlKey && (e.keyCode == 86)) || (e.ctrlKey && (e.keyCode == 219)) || (e.ctrlKey && (e.keyCode == 221))) { // block ctrl+v
        e.preventDefault();
    }
});

var specialKeys = new Array();
specialKeys.push(8);
specialKeys.push(46);
$(".onlyNumbers").bind("keypress", function(e) {
    var keyCode = e.which ? e.which : e.keyCode
    var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
    return ret;
});

$(".onlyNumbers").bind("paste", function(e) {
    return false;
});

$(".onlyNumbers").bind("drop", function(e) {
    return false;
});

$(document).on('keyup', '.money_charm', function(e) {
    var inputVal = $(this).val();
    var inputVal = $(this).val();
    var inputLen = $(this).val().length;
    var dott = inputVal.toString().indexOf(".");
    var allowDi = parseFloat(inputLen) - parseFloat(dott);
    if (dott != -1) { var allowDi = parseFloat(inputLen) - parseFloat(dott); } else { var allowDi = '0'; }
    if ((inputLen > 5 && allowDi == 0) || (inputLen > 8 || allowDi > 3)) {
        $(this).val(tempInput);
    } else {
        tempInput = inputVal;
    }
});

$(document).on('keyup', '.money_charm_tax', function(e) {
    var inputVal = $(this).val();
    var inputVal = $(this).val();
    var inputLen = $(this).val().length;
    var dott = inputVal.toString().indexOf(".");
    var allowDi = parseFloat(inputLen) - parseFloat(dott);
    if (dott != -1) { var allowDi = parseFloat(inputLen) - parseFloat(dott); } else { var allowDi = '0'; }
    if ((inputLen > 2 && allowDi == 0) || (inputLen > 5 || allowDi > 3)) {
        $(this).val(tempInput);
    } else {
        tempInput = inputVal;
    }
});

$(document).on('keyup', '.numbersOnly', function() {
    this.value = this.value.replace(/[^0-9\.]/g, '');
});

$.validator.addMethod("maxleng", function(value, element) {
    var getleng = $(this).attr('maxlength');
    if (getleng == '' || getleng === undefined) {
        getleng = '25';
    }

    var checkleng = value.length
    var isValid = true;
    if (checkleng > getleng) {
        isValid = false;
    }

    $.validator.messages.maxleng = 'Please enter no more than ' + getleng + ' characters';
    return isValid;
}, $.validator.messages.maxleng);

/** Clicks */
$(".email").keyup(function() {
    var str = $(this).val();
    var string = str.replace(/\s+/g, "");
    $(this).val(string);
});

$('#deletebcchk').click(function(e) {
    var current_checked_status = $(this).prop('checked');
    if (current_checked_status == false) {
        $('.checkboxes').prop('checked', false);
    } else {
        $('.checkboxes').prop('checked', true);
    }
});

$(document).on('click', '.paginate_button', function() {
    updateToopTip();
});

/* $(document).on('click', '#mode-setting-btn', function() {
    var mode = $('body').attr('data-layout-mode');
    alert(mode);
    Cookies.set("siteMode", mode, {
        path: '/',
        secure: true,
        sameSite: 'strict'
    });
}); */
//sameSite: 'Strict',
/* $(document).on('click', '#vertical-menu-btn', function() {
    var navMode = $('body').attr('data-sidebar-size');
    $.ajax({
        url: ADMIN_APPURL+"/ajax-navrequest",
        dataType: "json",
        data: {navMode:navMode},
        success: function(data) {
            if(data.success){

            }
        }
    });

    Cookies.set("navMode", navMode, {
        path: '/',
        secure: true,
        sameSite: 'strict',
    });
}); */

// sameSite: 'Strict',
function freezeButton(t, e, o) {
    switch (o) {
        case "active":
            $(t).attr("disabled", !1), $(t).html(e);
            break;
        case "disabled":
            $(t).attr("disabled", "disabled"), $(t).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + e)
    }
}

function updateToopTip() {
    setTimeout(function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }, 1000);
}

/** Form Submit Checker */
$("form").submit(function() {
    if ($(this).valid()) {
        ///OnBeginRequest();
    }
    // "this" is a reference to the submitted form
});

function OnBeginRequest(sender, args) {
    blockUI();
}

//Center the element
$.fn.center = function() {
    this.css("position", "absolute");
    this.css("top", ($(window).height() - this.height()) / 2 + $(window).scrollTop() + "px");
    this.css("left", ($(window).width() - this.width()) / 2 + $(window).scrollLeft() + "px");
    return this;
}

//blockUI
function blockUI() {
    $.blockUI({
        css: {
            backgroundColor: 'transparent',
            border: 'none'
        },
        message: '<div class="spinner"><div class="spinner-border m-5" role="status"><span class="sr-only">Loading...</span></div></div>',
        baseZ: 1500,
        overlayCSS: {
            backgroundColor: '#FFFFFF',
            opacity: 0.7,
            cursor: 'wait'
        }
    });
    $('.blockUI.blockMsg').center();
} //end Blockui

function checkSelects(id) {
    var checkedRecords = false;
    $(".elem_ids").each(function(index, element) {
        if ($(this).prop("checked") == true) { checkedRecords = true; }
    });
    if (checkedRecords == false) {
        showNotify('error', `No Records Selected for Delete,\nPlease Select Records to delete`)
        return false;
    } else {
        Swal.fire({
            title: "Are you sure?",
            text: "Do you want to delete selected records?. You won't be able to revert this!",
            icon: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#2ab57d",
            cancelButtonColor: "#fd625e",
            confirmButtonText: "Yes, delete it!",
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((function(isConfirm) {
            isConfirm.value && document.getElementById(id).submit();
        }));
        return false;
    }
}

function globalStatus(chkAll) {
    if ($(chkAll).hasClass("status-1")) {
        var status = 0
        $(chkAll).addClass("status-0");
        $(chkAll).removeClass("status-1");
    } else {
        var status = 1
        $(chkAll).addClass("status-1");
        $(chkAll).removeClass("status-0");
    }

    vars = $(chkAll).attr("id").split("-");
    title = $(chkAll).attr("title");
    scriptUrl = ADMIN_APPURL + "/ajaxsetstatus/" + vars[0] + "/" + vars[1] + "/" + status;
    if (title !== undefined && title != "") { usermessage = title + " has been changed"; } else {
        field_title = vars[0].replace(/_/g, " ");
        field_title = field_title;

        usermessage = '<b>' + field_title + '</b>  status has been changed';
    }
    $.ajax({
        url: scriptUrl,
        dataType: "json",
        success: function(data) {
            if (data.success) {
                showNotify('success', data.message)
            } else {
                showNotify('error', data.message)
            }
        },
        error: function(data) {
            showNotify('error', 'Internal Server Error')
        }
    });
}

function unAuthenticated(msg = false, redirect = false) {
    var msg = msg == "" ? "It seems your session has expired, please login again." : msg;
    Swal.fire({
        title: "Oops!",
        text: msg,
        icon: "error",
        confirmButtonColor: "#2ab57d",
        cancelButtonColor: "#fd625e",
        confirmButtonText: "Login",
        showCancelButton: false,
        closeOnConfirm: false,
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((function(isConfirm) {
        if (isConfirm.value) {
            var url = window.location.href;
            window.location = ADMIN_APPURL + '/login?url=' + url;
        }
    }));
}

if ($.fn.dataTable !== undefined) {
    $.fn.dataTable.ext.errMode = function(settings, helpPage, message) {

        if (settings.jqXHR.status === 401) {
            unAuthenticated();
        } else {
            alert(message);
        }

    };
}

function showNotify(status, msg) {
    alertify.set('notifier', 'position', 'top-right');
    var html = '';
    if (status != '' && msg != '') {
        if (status == 'success') {
            html = `<i class="mdi mdi-check-all label-icon"></i> <strong>Success</strong> - ` + msg;
            alertify.success(html);
        } else if (status == 'error') {
            html = `<i class="mdi mdi-block-helper label-icon" ></i> <strong>Error </strong> - ` + msg;
            alertify.error(html);
        } else if (status == 'warning') {
            html = `<i class="mdi mdi-alert-outline label-icon" ></i> <strong>Warning </strong> - ` + msg;
            alertify.warning(html);
        } else {
            html = `<i class="mdi mdi-alert-circle-outline label-icon" ></i> <strong>Info </strong> -` + msg;
            alertify.message(html);
        }
    }
}

function showAlert(status, title, msg) {
    Swal.fire({
        title: title,
        text: msg,
        icon: status,
        allowOutsideClick: false,
        allowEscapeKey: false
    });
}

function showAlertConfirm(status, title, msg, confirm, redirect) {
    Swal.fire({
        title: title,
        text: msg,
        icon: status,
        showCancelButton: !0,
        confirmButtonColor: "#2ab57d",
        cancelButtonColor: "#fd625e",
        confirmButtonText: "Ok",
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((function(isConfirm) {
        if (isConfirm.value) {}
    }));
}
