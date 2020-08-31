$(".email-template-info").submit(function(e) {
    e.preventDefault();
    $('#loader').show();
    $('#emaileditform').hide();
    var actionurl = e.currentTarget.action;
    var formid = e.currentTarget.id;
    $.ajax({
        type: "POST",
        url: actionurl,
        data: $(this).serialize(),
        success: function(result) {
            var content = JSON.parse(result);
            $("input[name="+content.csrfTokenName+"]").val(content.csrfHash);
            var subject = content.subject;
            var body = content.body;
            var emailId = content.id;
            $(".dt-contact-active").addClass("dt-contact");
            $(".dt-contact-active").removeClass("dt-contact-active");
            $('#template' + emailId).addClass('dt-contact-active');
            $('#email_id').val(emailId);
            $('#normal-input-3').val(subject);
            $('#summernote').summernote('code', body);
            setTimeout(function () {
              $('#emaileditform').show();
              $('#loader').hide();
          }, 2000); 
        },
        error: function(result) {
            $value = 'error';
            //alert($value);
        }
    });
});
$("#emaileditform").submit(function(e) {
e.preventDefault();
var actionurl = e.currentTarget.action;
$.ajax({
        url: actionurl,
        type: 'post',
        data: $(this).serialize(),
        success: function(data) {
            var content = JSON.parse(data);
            $("input[name="+content.csrfTokenName+"]").val(content.csrfHash);
            swal(
            content.success == true ? 'Success!' : 'Error!',
            content.msg,
            content.success == true ? 'success' : 'error'
            );
        },
        error: function(data) {
            swal(
            'Error!',
            'There is an issue in saving your email template. Please reload the pgae and try again',
            'error'
            );
        }
});

});
