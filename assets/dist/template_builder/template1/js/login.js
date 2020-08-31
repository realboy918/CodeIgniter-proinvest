$("#confirm-user-pass").click(function(e) {
    e.preventDefault();
    //Remove error fields
    $('.form-control').removeClass('inputTxtError');
    $(".error").html('');

    //Disable button
    var loadermsg = $('#confirm-user-pass').attr('data-loading-text');
    $('#confirm-user-pass').attr('disabled', true);
    $("#confirm-user-pass").html(loadermsg);

    //Form Data
    var actionurl = './confirmpass';
    $.ajax({
        url: actionurl,
        type: 'post',
        data: $("#loginForm").serialize(),
        success: function(data) {
            var content = JSON.parse(data);
            $("input[name=" + content.csrfTokenName + "]").val(content.csrfHash);

            if(content.success == false)
            {
                $.each(content.errors, function(key, value){
                    var msg = '<label class="error mb-2" for="'+key+'">'+value+'</label>';
                    $('input[name="' + key + '"], select[name="' + key + '"]').addClass('inputTxtError').after(msg);
                });

                if(content.v == 'v2'){
                    grecaptcha.reset();
                } else if(content.v == 'v3'){
                    grecaptcha.execute(content.key).then(function (token) {
                        var recaptchaResponse = document.getElementById('recaptchaResponse');
                        recaptchaResponse.value = token;
                    });
                }

                if(content.type == 'pass'){
                    $('#overallError').html(content.msg);
                }
                var buttontitle = $('#confirm-user-pass').attr('data-title');
                $('#confirm-user-pass').attr('disabled', false);
                $("#confirm-user-pass").html(buttontitle);
            }

            if(content.twfa == false)
            {
                $("#authenticate").html('Redirecting you to dashboard');
                window.location.replace(content.url);
            } else if(content.twfa == true)
            {
                $('#login-title').html(content.msg);
                $('.email-pass').hide();
                $('#google-auth').show();
                $('#confirm-user-pass').hide();
                $("#authenticate").show();
                $('#new-reg').hide();
                $('#new-reg').removeClass('d-inline-block');
            }
        },
        error: function(data) {
        }
    });

});

$("#authenticate").click(function(e) {
    e.preventDefault();
    $('.form-control').removeClass('inputTxtError');
    $(".error").html('');
    $('#authenticate').attr('disabled', true);
    $("#authenticate").html('checking code...');
    var actionurl = './login_auth';
    $.ajax({
        url: actionurl,
        type: 'post',
        data: $("#loginForm").serialize(),
        success: function(data) {
            var content = JSON.parse(data);
            $("input[name=" + content.csrfTokenName + "]").val(content.csrfHash);

            if(content.success == false)
            {
                $.each(content.errors, function(key, value){
                    // here you can access all the properties just by typing either value.propertyName or value["propertyName"]
                    // example: value.ri_idx; value.ri_startDate; value.ri_endDate;
                    var msg = '<label class="error" for="'+key+'">'+value+'</label>';
                    $('input[name="' + key + '"], select[name="' + key + '"]').addClass('inputTxtError').after(msg);
                });
                $('#authenticate').attr('disabled', false);
                $("#authenticate").html('Authenticate & login');
            }

            $('.google-auth-err').html(content.errmsg);

            if(content.success == true)
            {
                $("#authenticate").html(content.msg);
                window.location.replace(content.url);
            }
        },
        error: function(data) {
        }
    });

});