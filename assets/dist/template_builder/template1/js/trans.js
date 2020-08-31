$('.declineAction').click(function(e){
    e.preventDefault();
    var id = $(this).attr('id').replace(/confirmButton/, '');
    $('#transDecID').val(id);
    $('.decline-form').attr('action', e.currentTarget.value);
})

$('.confirmAction').click(function(e){
    e.preventDefault();
    var id = $(this).attr('id').replace(/confirmButton/, '');
    $('#transID').val(id);
    $('.confirm-form').attr('action', e.currentTarget.value);

    var actionurl = $(this).attr('data-url');

    //Clear list
    $('#paymentData').html('');

    $.ajax({
        url: actionurl,
        type: 'get',
        success: function(res) {
            var content = JSON.parse(res);
            console.log(content);
            if(content.success == true)
            {
                    var div = document.getElementById('paymentData');

                    if(content.method == 'Bank Transfer'){
                        var div1 = '<tr><td>Bank Name: </td>'
                        var div2 = '<td>'+content.data.bank_name+'</td></tr>'
                        var div3 = '<tr><td>Account Name: </td>'
                        var div4 = '<td>'+content.data.account_name+'</td></tr>'
                        var div5 = '<tr><td>Account Number: </td>'
                        var div6 = '<td>'+content.data.account_number+'</td></tr>'
                        var div7 = '<tr><td>Swift Code: </td>'
                        var div8 = '<td>'+content.data.swift_code+'</td></tr>'

                        div.innerHTML += div1 + div2 + div3 + div4 + div5 + div6 + div7 + div8;
                    } else {
                        var divOpen = '<tr>'
                        var div1 = '<td>'+content.data.withdrawal_method+': </td>'
                        var div2 = '<td>'+content.data.witdhrawal_account+'</td>'
                        var divClose = '</tr>'

                        div.innerHTML += divOpen + div1 + div2 + divClose;
                    }

                $("#terms").html(content.terms);
                $('#submit').attr('disabled', false);
                $("#submit").html('Create Account');
            }
        }
    })
})
$('.cancelAction').click(function(e){
    var id = $(this).attr('id').replace(/cancelButton/, '');
    $('#transID').val(id);
    $('.confirm-form').attr('action', e.currentTarget.value);
})
$('.confirm-form').submit(function(e){
e.preventDefault();
var actionurl = e.currentTarget.action
var form = $(this).attr('id');
$(this).find(':input[type=submit]').prop('disabled', true);
$(this).find(':input[type=submit]').html('Processing …');
$.ajax({
    type: "POST",
    url: actionurl,
    data: $(this).serialize(),
    success: function(result) {
        if(form === 'confirm-form'){
            $('#confirmationModal').modal('toggle');
        } else {
            $('#declineModal').modal('toggle');
        }
        var content = JSON.parse(result);
        $("input[name="+content.csrfTokenName+"]").val(content.csrfHash);
        if(form === 'confirm-form'){
            $('#buttonSubmit').prop('disabled', false);
            $('#buttonSubmit').html('Save Changes');
        } else {
            $('#buttonSubmitDec').prop('disabled', false);
            $('#buttonSubmitDec').html('Save Changes');
        }
        swal(
            content.success == true ? 'Success!' : 'Error!',
            content.msg,
            content.success == true ? 'success' : 'error'
        );
        
        if(content.success == true)
        {
            var id = actionurl.substring(actionurl.lastIndexOf('/') + 1);
            if(document.title == 'Withdrawals') {
                $('#confirmButton' + id).remove();
                $('#declineButton' + id).remove();
            } else if(document.title == 'Deposits') {
                $('#row' + id).remove();
            }
        }
    },
    error: function(result) {
        $value = 'error';
        //alert($value);
        $('#buttonSubmit').prop('disabled', false);
        $('#buttonSubmit').html('Save Changes');
        }
    })
})
$('.reinvest').click(function(e){
    e.preventDefault();
    var val = e.currentTarget.value;
    var msg = '<p>Please confirm that you want to reinvest by entering your password below</p><div class="form-group"><input class="form-control" name="password" id="password" type="password"/></div>';
    var button = '<input name="code" value="'+val+'" hidden/><button id="withdrawalID" type="submit" class="btn btn-primary btn-sm">Proceed and re-invest</button>';
    $('#modalBody').html(msg);
    $('#reinvestPlans').show();
    $('#model-8').html('Reinvest Funds');
    $('#continue').html(button);
    $('#modalForm').attr('action', './reinvest');
})
$('.withdraw').click(function(e){
    e.preventDefault();
    var id = e.currentTarget.id;
    var val = e.currentTarget.value;
    var msg = '<h1 class="text-center">Withdraw '+val+'</h1><p class="text-center">Deposit ID:'+id+'</p>';
    var button = '<input name="code" value="'+id+'" hidden/><button id="withdrawalID" type="submit" value="'+id+'" class="btn btn-primary btn-sm">Proceed and withdraw</button>';
    $('#modalBody').html(msg);
    $('#reinvestPlans').hide();
    $('#model-8').html('Withdraw Funds');
    $('#continue').html(button);
    $('#modalForm').attr('action', './withdrawDeposit');
})
$('#modalForm').on('submit', function(e){
    e.preventDefault();
    var url = e.currentTarget.action;
    $(this).find(':input[type=submit]').prop('disabled', true);
    $(this).find(':input[type=submit]').html('Processing …');
    $.ajax({
        url: url,
        method:"POST",
        data:new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success:function(data)
        {
            $('#modal').modal('toggle');
            var content = JSON.parse(data);
            $("input[name="+content.csrfTokenName+"]").val(content.csrfHash);
            alert(content.csrfTokenName)
            $('#modalForm').find(':input[type=submit]').prop('disabled', false);
            $('#modalForm').find(':input[type=submit]').html('Save Changes');
            if(content.success == true)
            {
                var code =document.querySelector('[name="code"]').value;
                $('#' + code).remove();
                $('#reinvest' + code).remove();
                $('#col' + code).html('Withdrawn');
            }
            swal(
                content.success == true ? 'Success!' : 'Error!',
                content.msg,
                content.success == true ? 'success' : 'error'
            );
        },
            error: function(data) {
                $('#modalForm').find(':input[type=submit]').prop('disabled', false);
                $('#modalForm').find(':input[type=submit]').html('Save Changes');
                swal(
                    'Error!',
                    'There is an issue in processing your transaction. Please try again later',
                    'error'
                );
            }
        })
    })