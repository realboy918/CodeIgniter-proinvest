$(function() { // Makes sure the code contained doesn't run until
    //     all the DOM elements have loaded
    $('#typeselector').change(function() {
        $('#simple').hide();
        $('#multiple').hide();
        $('#' + $(this).val()).show();
    });

});

$(document).ready(function(){
    var input = document.querySelector("#phonenumber");
            window.intlTelInput(input,
            {
                separateDialCode: false,
                hiddenInput: "phone"
            });
    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var x = 1; //Initial field counter is 1

    //Once add button is clicked
    $(addButton).click(function(e) {
        var v = $(this).data('value')
        //Check maximum number of input fields
        if (x < maxField) {
            x++; //Increment field counter
            console.log(x)
            var level = x;
            $(wrapper).append("<div class='form-group'><label>Level " + (x + v) +
                " Interest</label><input class='form-control' type='text' name='multipleInt[]' value='' placeholder='interest' /><a href='javascript:void(0);' class='remove_button'><img class='add-delete-icon' src='./assets/dist/img/remove.svg'/></div>"
                ); //Add field html
        }
    });

    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e) {
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });
    $(document).find(".bfh-currencies input[type=hidden]").attr("name","currency");
    $('.bfh-selectbox').on('change.bfhselectbox', function () {
        var data = $(this).val();
        $('#local_currency').html(data);
        if(data !== 'USD')
        {
            $('#currency_exchange').show();
        }else{
            $('#currency_exchange').hide();
        }
    });
    $(".form").submit(function(e) {
        e.preventDefault();
        var actionurl = e.currentTarget.action;
        var formid = e.currentTarget.id;
        $.ajax({
            url: actionurl,
            type: 'post',
            data: $('#' + formid).serialize(),
            success: function(data) {
                $('#form-modal' + formid).modal('toggle');
                var content = JSON.parse(data);
                $("input[name="+content.csrfTokenName+"]").val(content.csrfHash);
                $activesms = $("input[name='smsactive']:checked").val();
                $activeemail = $("input[name='emailactive']:checked").val();
                if(formid == 'SMSProfile')
                {
                    if($activesms == 1)
                    $('#testsms-email').removeClass('hide');
                    else if($activesms == 0)
                    $('#testsms-email').addClass('hide');
                }
                if(formid == 'emailProfile')
                {
                    if($activeemail == 1)
                    $('#testemail-sms').removeClass('hide');
                    else if($activeemail == 0)
                    $('#testemail-sms').addClass('hide');
                }
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
            error: function(data) {
                $('#form-modal' + formid).modal('toggle');
                swal(
                    'Error!',
                    'There is an issue in updating your settings. Please reload the page and try again',
                    'error'
                );
            }
        });
    
    });
    $('#companyProfile').on('submit', function(e){
      e.preventDefault();
      var actionurl = e.currentTarget.action;
      var formid = e.currentTarget.id;
      var form = document.getElementById("companyProfile");
      var formData = new FormData(form)
      formData.append('currency', $(".bfh-currencies input[type=hidden]").val());
        $.ajax({
         url:actionurl,
         method:"POST",
         data: formData,
         contentType: false,
         cache: false,
         processData: false,
         success: function(data) {
               $('#form-modal' + formid).modal('toggle');
               var content = JSON.parse(data);
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
               if (content.darkLogo !== "") 
               {
                   $('#uploaded_image_dark').html(content.darkLogo);
               }
               if (content.whiteLogo !== "") 
               {
                   $('#uploaded_image_white').html(content.whiteLogo);
               }
               if (content.favicon !== "") 
               {
                   $('#uploaded_image_favicon').html(content.favicon);
               }
           },
           error: function(data) {
               $('#form-modal' + formid).modal('toggle');
               swal(
                   'Error!',
                   'There is an issue in saving your settings. Please reload the page and try again',
                   'error'
               );
           }
        })
    })
  })