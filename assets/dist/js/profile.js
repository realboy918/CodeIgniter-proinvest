function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      
      reader.onload = function(e) {
        $('#ppic').attr('src', e.target.result);
        document.getElementById('ppic-save').style.display = 'block';
      }
      
      reader.readAsDataURL(input.files[0]);
    }
  }
  $("#imgInp").change(function() {
    readURL(this);
  });
  $('#upload_form').on('submit', function(e){

  e.preventDefault();
  var actionurl = e.currentTarget.action;
  $.ajax({
      url:actionurl,
      method:"POST",
      data:new FormData(this),
      contentType: false,
      cache: false,
      
      processData: false,
      success:function(data)
      {
        var content = JSON.parse(data);
        swal(
            content.success == true ? 'Success!' : 'Error!',
            content.msg,
            content.success == true ? 'success' : 'error'
        );
      },
      error: function(data) {
          swal(
              'Error!',
              'There is an issue in updating your account. Please refresh the page.',
              'error'
          );
      }
    })
})
$('#google2FAForm').on('submit', function(e){
  e.preventDefault();

  //Remove error fields
  $('.form-control').removeClass('inputTxtError');
  $(".error").html('');

  //Button variables
  var loadermsg = $('#activate-submit').attr('data-loading-text');
  var buttontitle = $('#activate-submit').attr('data-title');
  $('#activate-submit').attr('disabled', true);
  $("#activate-submit").html(loadermsg);

  //Form Data
  var actionurl = e.currentTarget.action;
  $.ajax({
      url: actionurl,
      method:"POST",
      data: $(this).serialize(),
      success:function(data)
      {
        var content = JSON.parse(data);
        $("input[name="+content.csrfTokenName+"]").val(content.csrfHash);
        swal(
            content.success == true ? 'Success!' : 'Error!',
            content.msg,
            content.success == true ? 'success' : 'error'
        );
        $('#activate-submit').attr('disabled', false);
        $("#activate-submit").html(buttontitle);
        if(content.success == false)
        {
            $.each(content.errors, function(key, value){
                // here you can access all the properties just by typing either value.propertyName or value["propertyName"]
                // example: value.ri_idx; value.ri_startDate; value.ri_endDate;
                var msg = '<label class="error" for="'+key+'">'+value+'</label>';
                $('input[name="' + key + '"], select[name="' + key + '"]').addClass('inputTxtError').after(msg);
            });
        } else
        {
          $('#authenticate-modal').modal('toggle');
        }
      },
      error: function(data) {
        swal(
            'Error!',
            'There is an issue in updating your account. Please refresh the page.',
            'error'
        );
        $('#activate-submit').attr('disabled', false);
        $("#activate-submit").html(buttontitle);
      }
    })
})