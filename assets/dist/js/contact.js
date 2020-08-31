$("#contactForm").submit(function(e) {
    $('label[class="error"]').text('');
    $('.form-control').removeClass('inputTxtError');
        e.preventDefault();
        var actionurl = e.currentTarget.action;
        $.ajax({
            url: actionurl,
            type: 'post',
            data: $(this).serialize(),
            success: function(data) {
                var content = JSON.parse(data);
                $("input[name="+content.csrfTokenName+"]").val(content.csrfHash);
                var col = content.success == true ? 'green' : 'red';
                $('#msg').html('<div class="text-center mb-2m" style="color: '+ col +'">'+content.msg +'</div>')
                if(content.success == false)
                {
                    $.each(content.errors, function(key, value){
                        // here you can access all the properties just by typing either value.propertyName or value["propertyName"]
                        // example: value.ri_idx; value.ri_startDate; value.ri_endDate;
                        var msg = '<label class="error" for="'+key+'">'+value+'</label>';
                        $('input[name="' + key + '"], select[name="' + key + '"]').addClass('inputTxtError').after(msg);
                        $('textarea[name="' + key + '"], select[name="' + key + '"]').addClass('inputTxtError').after(msg);
                    });
                }
            },
            error: function(data) {

            }
        });

    });