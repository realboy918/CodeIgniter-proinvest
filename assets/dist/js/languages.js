$(".lang").submit(function(e) {
    e.preventDefault();
    var actionurl = e.currentTarget.action;
    var formid = e.currentTarget.id;
    var form = document.getElementById(formid);
    var formData = new FormData(form);
    $('.error').html('');
    $('.form-control').removeClass('inputTxtError');
    if(formid == 'addLang')
    {
        $('#LangModulesLoader').show();
        $('#AllLangModules').hide(); 
    }
    $.ajax({
        type: "POST",
        url: actionurl,
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
            if(content.success ==true)
            {
                $('#langLogo').attr('src', '../uploads/' + content.logo);
                $('#langName').html(content.name);
                $('#langCode').html(content.code);
                if(formid == 'addLang')
                {
                    $('#LangModulesLoader').hide();
                    $('#AllLangModules').show();
                    $('#lid').val(content.id);
                    $('#lname').val(content.name);
                    $('#lcode').val(content.code);
                    $('.settingsLang').attr('data-id' , content.id);
                    $(".dt-contact-active").removeClass("dt-contact-active");
                    $(".contacts-list").append('<button type="submit" class="langSelect dt-contact bg-white w-100 text-left border-n dt-contact-active" id="template'+ content.id +'" data-id="'+ content.id +'"><div class="dt-contact__info"><h4 class="dt-module-side-nav__text mt-1m text-capitalize">'+ content.name +'</h4></div></button>');
                }
            }
            if(content.success == false)
            {
                $.each(content.errors, function(key, value){
                    // here you can access all the properties just by typing either value.propertyName or value["propertyName"]
                    // example: value.ri_idx; value.ri_startDate; value.ri_endDate;
                    var msg = '<label class="error" for="'+key+'">'+value+'</label>';
                    $('input[name="' + key + '"], select[name="' + key + '"]').addClass('inputTxtError').after(msg);
                });
                $('#LangModulesLoader').hide();
                $('#AllLangModules').show();
            }
        },
        error: function(data) {
            swal(
                'Error!',
                'There is an issue in saving the new language. Please reload the page and try again',
                'error'
                );
        }
    });
});
$(".langSelect").click(function(e){
    e.preventDefault();
    var landID = e.currentTarget.id;
    var value = $(this).attr("data-id");
    $('.settingsLang').attr('data-id' , value);
    $(".dt-contact-active").removeClass("dt-contact-active");
    $('#' + landID).addClass('dt-contact-active');
    $('#LangModulesLoader').show();
    $('#AllLangModules').hide();
    
    $.ajax({
        type: "GET",
        url: './getLang/' + value,
        data: $(this).serialize(),
        success: function(data) {
            var content = JSON.parse(data);
            $('#langName').html(content.name);
            $('#langCode').html(content.code);
            $('#transLangId').val(value);
            $('#lid').val(value);
            $('#lname').val(content.name);
            $('#lcode').val(content.code);
            $('.dt-drawer').removeClass('open');
            $('#langLogo').attr('src', '../uploads/' + content.logo);
            setTimeout(function () {
                $('#LangModulesLoader').hide();
                $('#AllLangModules').show();
            }, 2000); 
        },
        error: function(data) {
        }
    });
})
$("#langForm").submit(function(e){
    e.preventDefault();
    var actionurl = e.currentTarget.action;
    $.ajax({
        type: "POST",
        url: actionurl,
        data: $(this).serialize(),
        success: function (data) {
            var content = JSON.parse(data);
            swal(
                content.success == true ? 'Success!' : 'Error!',
                content.msg,
                content.success == true ? 'success' : 'error'
            );
        },
        error: function (data) {}
    })
})
$(".settingsLang").submit(function(e) {
    e.preventDefault();
    var actionurl = e.currentTarget.action;
    var formid = e.currentTarget.id;
    var dataValue = $(this).attr("data-id");
    $('#LangSettingsloader').show();
    $('#table').hide();
    $('#settingsSave').hide();
    $('#settingsList').html('');
    $('.dt-drawer').addClass('open');
    $.ajax({
        type: "GET",
        url: actionurl + '/' + dataValue + '/' + formid,
        data: $(this).serialize(),
        success: function(data) {
            var content = JSON.parse(data);
            var table = content.list;
            var table_html = '';
            for (var i = 0; i < table.length; i++){
                //create html table row
                table_html += '<tr>';
                //create html table cell, add class to cells to identify columns         
                table_html += '<td class="text-capitalize">' + table[i].key.replace(/_/g, " ") + '</td><td><input type="text" name="'+ table[i].key +'" value="'+ table[i].translation +'" class="form-control " id="'+ table[i].key +'" placeholder=""></td>'
                table_html += '</tr>'
            }
            $( "#settingsList" ).append(table_html);
            $("#lang-header").html(content.lang + ': ' + content.module_code);
            $('.dt-customizer__body').scrollTop(0);
            setTimeout(function () {
                $('#LangSettingsloader').hide();
                $('#settingsSave').show();
                $('#table').show();
            }, 2000); 
        },
        error: function(data) {
        }
    });
})