$("#registerForm").submit(function(e) {
    e.preventDefault();
    
    //Remove error fields
    $('.form-control').removeClass('inputTxtError');
    $(".error").html('');

    //Button variables
    var loadermsg = $('#submit').attr('data-loading-text');
    var buttontitle = $('#submit').attr('data-title');
    $('#submit').attr('disabled', true);
    $("#submit").html(loadermsg);

    //Form Data
    var actionurl = e.currentTarget.action;
    $.ajax({
            url: actionurl,
            type: 'post',
            data: $("#registerForm").serialize(),
            success: function(data) {
                var content = JSON.parse(data);
                if(content.success == false)
                {
                    $.each(content.errors, function(key, value){
                        var msg = value;
                        $('input[name="' + key + '"], select[name="' + key + '"]').addClass('inputTxtError');
                        $('#' + key).html(msg);
                    });
                    
                    $("#terms").html(content.terms);
                    $('#submit').attr('disabled', false);
                    $("#submit").html(buttontitle);
                }else{
                    top.location.href=content.url;
                }
            },
            error: function(data) {
                swal(
                'Error!',
                'There is an issue in processing your signup request. Please try again later',
                'error'
                );
                $('#submit').attr('disabled', false);
                $("#submit").html(buttontitle);
            }
    });
    
});
$('.pay-ins').click(function(e){
    $('.fqins').addClass('hide');
    $('#faq' + e.currentTarget.id).toggleClass('hide');
})

$(function() {
    var $form = $(".require-validation");
    $('form.require-validation').bind('submit', function(e) {
        var $form = $(".require-validation"),
            inputSelector = ['input[type=email]', 'input[type=password]',
                'input[type=text]', 'input[type=file]',
                'textarea'
            ].join(', '),
            $inputs = $form.find('.required').find(inputSelector),
            $errorMessage = $form.find('div.error'),
            valid = true;
        $errorMessage.addClass('hide');

        $('.has-error').removeClass('has-error');
        $inputs.each(function(i, el) {
            var $input = $(el);
            if ($input.val() === '') {
                $input.parent().addClass('has-error');
                $errorMessage.removeClass('hide');
                e.preventDefault();
            }
        });

        if (!$form.data('cc-on-file')) {
            e.preventDefault();
            Stripe.setPublishableKey($form.data('stripe-publishable-key'));
            Stripe.createToken({
                number: $('.card-number').val(),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val()
            }, stripeResponseHandler);
        }

    });

    function stripeResponseHandler(status, response) {
        if (response.error) {
            $('.error')
                .removeClass('hide')
                .find('.alert')
                .text(response.error.message);
        } else {
            var token = response['id'];
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
        }
    }

});
$("form").submit(function(e) {
    var loadermsg = $(this).find(':input[type=submit]').attr('data-loading-text');
    $(this).find(':input[type=submit]').prop('disabled', true);
    $(this).find(':input[type=submit]').html(loadermsg);
    return true;
});

$('.deleteUser').click(function(e){
    $('#planIdVal').val(e.currentTarget.value);
    var action = 'plans/delete/';
    $('.delete-form').attr('action', action + e.currentTarget.value);
}) 
$('.delete-form').submit(function(e){
    e.preventDefault();
    var actionurl = e.currentTarget.action
    $.ajax({
        type: "POST",
        url: actionurl,
        data: $(this).serialize(),
        success: function(result) {
            var content = JSON.parse(result);
            $('#deleteModal').modal('toggle');
            var id = actionurl.substring(actionurl.lastIndexOf('/') + 1);
            $('#row' + id).remove();
            $("input[name="+content.csrfTokenName+"]").val(content.csrfHash);
            swal(
                content.success == true ? 'Success!' : 'Error!',
                content.msg,
                content.success == true ? 'success' : 'error'
            );
            if(content.success == false)
            {
                $.each(content.errors, function(key, value){
                    // here you can access all the properties just by typing either value.propertyName or value["propertyName"]
                    // example: value.ri_idx; value.ri_startDate; value.ri_endDate;
                    var msg = '<label class="error" for="'+key+'">'+value+'</label>';
                    $('input[name="' + key + '"], select[name="' + key + '"]').addClass('inputTxtError').after(msg);
                });
            }
        },
        error: function(result) {
            $value = 'error';
            //alert($value);
        }
    })
})
$('#next').click(function(e) {
    e.preventDefault();
    var plan = $('[name="plan"]:checked').val();
    var min = $('[name="plan"]:checked').attr('min');
    var max = $('[name="plan"]:checked').attr('max');
    if (plan > 0) {
        $('#amount').attr('min', min);
        $('#amount').attr('max', max);
        $('#step2').show();
        $('#step1').hide();
    } else {
        $('.error').html('Please select a plan')
    }
})
$("#joinForm").submit(function(e) {
    e.preventDefault();
    var actionurl = e.currentTarget.action;
    $.ajax({
        url: actionurl,
        type: 'post',
        data: $("#joinForm").serialize(),
        success: function(data) {
            var content = JSON.parse(data);
            $("input[name=" + content.csrfTokenName + "]").val(content.csrfHash);
            swal(
                content.success == true ? 'Success!' : 'Error!',
                content.msg,
                content.success == true ? 'success' : 'error'
            );
            $('#invite').attr('disabled', false);
            $('#invite').html('invite Friends');
        },
        error: function(data) {
            swal(
                'Error!',
                'There is an issue in sending out your invitation. Please reload the page and try again',
                'error'
            );
            $('#invite').attr('disabled', false);
            $('#invite').html('invite Friends');
        }
    });

});
