$("#submitButtonForm").click(function(e) {
    e.preventDefault();
    var wval = $("input[name=amount]").val();
    var api = $("input[name=withdrawalMethod]:checked").attr('data-api');
    var wmeth = $("input[name=withdrawalMethod]:checked").val();
    var wref = $("input[name=withdrawalMethod]:checked").attr('data-value');
    var account = $('input[name=withdrawalMethod]:checked').attr('data-name');
    var id = $('input[name=withdrawalMethod]:checked').attr('data-id');
    var actionurl = '../withdrawalInfo/' + id + '/' + wref + '/' + wval;

    if(wval == '' || typeof wmeth === 'undefined')
    {
       alert('input everything');
    } else
    {
        $.ajax({
            url: actionurl,
            method:"GET",
            success:function(data)
            {
                var content = JSON.parse(data);
                if(content.success == true)
                {
                    if(account == content.type)
                    {
                        $('#account-wda').val(content.account);
                    }
                    if(content.method == 1){
                        $('#bank-withdrawal').show();
                    }
                    else if(content.method == '2'){
                        $('#vsms-withdrawal').show();
                    }else if(content.method == '3'){
                        $('#rec-inp').show();
                    }
                    $('#transFee').html(content.transaction_fee);
                    $('#finAmount').html(content.final_amount);
                    
                    $('.cnt_min').hide();
                    $('#tr-fee-fi').show();
                    $('#tr-ref-sel').show();
                    $('#inputGroupPrepend2').html(account);
                    $('#tr-with-mth').html('Payment Method');
                    $("#account-wda").attr("placeholder", "Please enter Recipient's " + account + " account")
                    $('#row' + wref).show();
                    //$('#with-step-1').hide();
                    //$('#with-step-2').show();
                    $('#with-account').html(account);
                    $('#submitButtonForm').hide();
                    $('#withdrawSubmitButton').show()
                } else if(content.success == false)
                {
                    swal(
                        'Error!',
                        content.msg,
                        'error'
                    );
                }
            },
                error: function(data) {
                    swal(
                        'Error!',
                        'There is an issue in updating your account. Please refresh the page.',
                        'error'
                    );
                    $('#activate-submit').attr('disabled', false);
                    $("#activate-submit").html('Activate');
                }
            })
        
    }
});


$("#addWithdrawal").on('submit', function(e) {
    e.preventDefault();
    $('#acc-with-err').hide();
    var wval = $("input[name=amount]").val();
    var wtype = $('input[name=withdrawalMethod]:checked').val();
    var wmeth = $('input[name=withdrawalMethod]:checked').attr('data-name');
    var api = $('input[name=withdrawalMethod]:checked').attr('data-api');
    var wacc = $("#account-wda").val();
    var actionurl = '../withdraw';
    var crd = $("input[name=cardNumber]").val();

    //Run AJAX

    

    //Do a check to see if all variables needed have been entered
    if(api == '1' && crd == '')
    {
        if(crd == ""){
            $('#cardnumber12').after('<label class="error"> This field is required</label>');
        }  
    } else 
    {
        //if(wacc !== '')
        //{
            $.ajax({
                url: actionurl,
                data: $('#addWithdrawal').serialize(),
                method:"POST",
                success:function(data){
                    var content = JSON.parse(data);
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
                    $('#withdrawSubmitButton').prop('disabled', false);
                    $('#withdrawSubmitButton').html('Process Withdrawal');
                },
                error: function(data) {
                    $('#withdrawSubmitButton').prop('disabled', false);
                    $('#withdrawSubmitButton').html('Process Withdrawal');
                }
            })
        //} else {
            //$('#acc-with-err').show();
            //$('#withdrawSubmitButton').prop('disabled', false);
          //  $('#withdrawSubmitButton').html('Process Withdrawal');
        //}
    }
})

$("#activate-select").click(function(e) {
    e.preventDefault();
    var twfa = $("input[name=twfa]:checked").val();
    var actionurl = '../emailwithdrawal2FA';
    $("#activate-select").prop('disabled', true);
    $("#activate-select").html('Processing …');

    if(typeof twfa !== 'undefined'){
        if(twfa == 'email_2FA')
        {
            $.ajax({
                url: actionurl,
                method:"GET",
                success:function(data){
                    var content = JSON.parse(data);
                    $('#withauth-msg').html(content.msg);
                    $('#wdauth-step1').hide();
                    $('#wdauth-step2').show();
                    $('#activate-submit').show();
                    $('#activate-select').hide();
                },
                error: function(data) {
                    $('#wdauth-step1').hide();
                    $('#wdauth-step2').show();
                    $('#activate-submit').show();
                    $('#activate-select').hide();
                }
            })
        } else if(twfa == 'google_2FA'){
            $('#withauth-msg').html('Please input 6 digit code from your Google Authenticator App');
            $('#wdauth-step1').hide();
            $('#wdauth-step2').show();
            $('#activate-submit').show();
            $('#activate-select').hide();
        }
    }
})
$("#google2FAForm").submit(function(e) {
    e.preventDefault();
    var actionurl = e.currentTarget.action;

    $.ajax({
        type: "POST",
        url: actionurl,
        data: $(this).serialize(),
        success:function(data)
        {
            var content = JSON.parse(data);
            if(content.success == true)
            {
                swal(
                    'Success!',
                    content.msg,
                    'success'
                );
                $('#authenticate-modal').modal('toggle');
                window.location.replace('../withdrawals');

            } else if(content.success == false)
            {
                swal(
                    'Error!',
                    content.msg,
                    'error'
                );
                $('#activate-submit').prop('disabled', false);
                $('#activate-submit').html('Withdraw');
            }
        },
        error: function(data) {
            swal(
                'Error!',
                'There is an issue in making your withdrawal. Please refresh the page.',
                'error'
            );
            $('#activate-submit').prop('disabled', false);
            $('#activate-submit').html('Withdraw');
        }
    })
})
$("input[name=withdrawalMethod]").change(function(e){
    $("#amountWithError").html(''); 
})
$('.myElements').each(function() {
    var elem = $(this);

    // Save current value of element
    elem.data('oldVal', elem.val());
 
    // Look for changes in the value
    elem.bind("propertychange change click keyup input paste", function(event){
 
       // If value has changed...
       if (elem.data('oldVal') != elem.val()) {
 
            // Updated stored value
            elem.data('oldVal', elem.val());

            var wval = $("input[name=amount]").val();
            var wmeth = $("input[name=withdrawalMethod]:checked").val();
            var account = $('input[name=withdrawalMethod]:checked').attr('data-name');
            var id = $('input[name=withdrawalMethod]:checked').attr('data-id');
            var actionurl = '../withdrawalInfo/' + id + '/' + wmeth + '/' + wval;
            if(typeof wmeth !== 'undefined')
            {
                // Do action
                $.ajax({
                    url: actionurl,
                    method:"GET",
                    success:function(data)
                    {
                        var content = JSON.parse(data);
                        if(content.success == true)
                        {
                            if(account == content.type)
                            {
                                $('#account-wda').val(content.account);
                            }
                            $('#transFee').html(content.transaction_fee);
                            $('#finAmount').html(content.final_amount);
                            $('#withdrawSubmitButton').prop('disabled', false);
                            $("#amountWithError").html('');
                        } else if(content.success == false)
                        {
                            $('#transFee').html('0.00');
                            $('#finAmount').html('0.00');
                            $("#withdrawSubmitButton").prop('disabled', true);
                            $("#amountWithError").html(content.msg);
                        }
                    },
                    error: function(data) {
                        swal(
                            'Error!',
                            'There is an issue in updating your account. Please refresh the page.',
                            'error'
                        );
                    }
                })
            }
        }
 
    });
 
  });