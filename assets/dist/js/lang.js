$(".sitelangChange").click(function(e){
    var actionurl = $(this).attr("data-id");
    $.get(actionurl, function(data){
        var content = JSON.parse(data);
        setTimeout(
            function() {
                location.reload();
            }, 1000);
    });
  });