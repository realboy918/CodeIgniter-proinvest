$('#newTicket').on('click', function(e){
    $('.dt-drawer').addClass('open');
})

$('#ticketFilterOff').on('click', function(e){
    e.preventDefault();
    $.ajax({
        url: './remove_ticket_filter',
        type: 'get',
        success: function(data) {
            var content = JSON.parse(data);
            if(content.success == true){
                location.reload();
            }
        },
        error: function(data) {}
    })
})
$('#gx-checkbox-226').change(function() {
    if($(this).is(":checked")){
        $('#gx-checkbox-227').prop('checked', false);
        $('#gx-checkbox-228').prop('checked', false);
    }    
});

$('#gx-checkbox-227').change(function() {
    if($(this).is(":checked")){
        $('#gx-checkbox-226').prop('checked', false);
        $('#gx-checkbox-228').prop('checked', false);
    }    
});

$('#gx-checkbox-228').change(function() {
    if($(this).is(":checked")){
        $('#gx-checkbox-226').prop('checked', false);
        $('#gx-checkbox-227').prop('checked', false);
    }    
});

$('.checkbox-check').change(function() {
    var high = $('#gx-checkbox-226').prop('checked');
    var medium = $('#gx-checkbox-227').prop('checked');
    var low = $('#gx-checkbox-228').prop('checked');

    if(high == true){
        var data = 'high';
    } else if(medium == true){
        var data = 'medium';
    } else if(low == true){
        var data = 'low';
    }

    $.ajax({
        url: './priority_filter',
        type: 'POST',
        data: {priority: data},
        success: function(data) {
            var content = JSON.parse(data);
            if(content.success == true){
                location.reload();
            }
        },
        error: function(data) {}
    })
})

$('.dropdown-toggle, .dropdown-btn').on('click', function(e){
    var obj = $(this).attr('id');
    $('.dropdown-menu').removeClass('show');
    $('.' + obj).toggleClass('show').focus(); 
})

$('body').on('click', function(e){
    if($(e.target).closest('.dropdown-toggle, .dropdown-btn').length == 0) {
        /* Hide dropdown here */
        $('.dropdown-menu').removeClass('show');
     }
})

$('#comment').on('submit', function(e){
    e.preventDefault();
    var actionurl = e.currentTarget.action;

    $.ajax({
        url: actionurl,
        type: 'POST',
        data: $(this).serialize(),
        success: function(data) {
            var content = JSON.parse(data);
            if(content.success == true){
                location.reload();
            }
        },
        error: function(data) {}
    })
})
$('#previousmessages').on('click', function(e){
    var actionurl = $(this).attr('data-id');
    $.ajax({
        url: actionurl,
        type: 'GET',
        success: function(data) {
            var content = JSON.parse(data);
            if(content.success == true){
                $('#messageList').html('')
                for (var i = 0; i < content.data.length; i++) {
                    if(content.data[i].ppic === null){
                        var pic = '../assets/dist/img/avatar.png';
                    } else {
                        var pic = '../' + content.data[i].ppic
                    }
                    var divopen = '<div class="mb-6 media ng-star-inserted">'
                    var img = '<img alt="'+ content.data[i].firstName +'" src="'+ pic +'" class="dt-avatar size-25 mr-4 ng-star-inserted">'
                    var body = '<div class="media-body">'
                    var name = '<h5 class="text-light-gray mb-1">'+ content.data[i].firstName +''
                    var time = '<span class="d-inline-block f-12 ml-2">'+ content.data[i].createdDtm +''
                    var timeclose = '</span>'
                    var nameclose = '</h5>'
                    var msg = '<p class="mb-0 text-dark">'+ content.data[i].message +'</p>'
                    var bodyclose = '</div>'
                    var divclose = '</div>'

                    document.getElementById('messageList').innerHTML += divopen + img + body + name + time + timeclose + nameclose + msg + bodyclose + divclose
                }
            }
        },
        error: function(data) {}
    })
})
$('#ticket-state').on('click', function(e){
    var actionurl = $(this).attr('data-url');

    $.ajax({
        url: actionurl,
        type: 'GET',
        success: function(data) {
            var content = JSON.parse(data);
            if(content.success == true){
                location.reload();
            }
        },
        error: function(data) {}
    })
})
$('#formTicket').on('submit', function(e){
    e.preventDefault()
    var actionurl = e.currentTarget.action;

    $.ajax({
        url: actionurl,
        type: 'POST',
        data: $(this).serialize(),
        success: function(data) {
            var content = JSON.parse(data);
            
            if(content.success == true){
                swal(
                    content.success == true ? 'Success!' : 'Error!',
                    content.msg,
                    content.success == true ? 'success' : 'error'
                );
                $('.dt-drawer').removeClass('open');
            } else if(content.success == false)
            {
                swal({
                    title: "Error!",
                    text: content.msg,
                    type: "error"
                }, function() {
                    window.location = "redirectURL";
                });
                $.each(content.errors, function(key, value){
                    // here you can access all the properties just by typing either value.propertyName or value["propertyName"]
                    // example: value.ri_idx; value.ri_startDate; value.ri_endDate;
                    var msg = '<label class="error" for="'+key+'">'+value+'</label>';
                    $('input[name="' + key + '"], select[name="' + key + '"], textarea[name="' + key + '"]').addClass('inputTxtError').after(msg);
                });
            }
        },
        error: function(data) {}
    })
})

$('.assign').on('click', function(e){
    e.preventDefault();
    var actionurl = $(this).attr('data-url');
    var id = $(this).attr('id');

    $.ajax({
        url: actionurl,
        type: 'POST',
        data: $(this).serialize(),
        success: function(data) {
            var content = JSON.parse(data);
            if(content.success == true){
                $('#support_name').html(content.name);
                $('.assign').removeClass('active');
                $('#' + id).addClass('active');
            }
        },
        error: function(data) {}
    })
})

$("#gx-checkbox-181").click(function () {
    $(".checkbox").prop('checked', $(this).prop('checked'));

    if($(this).prop('checked') == true){
        $('#action-select').html('Select All');
        $('.helpdesk-list').addClass('selected-row');
    } else {
        $('#action-select').html('None')
        $('.helpdesk-list').removeClass('selected-row');
    }
});

$("#gx-checkbox-181").click(function () {
    $(".checkbox").prop('checked', $(this).prop('checked'));

    if($(this).prop('checked') == true){
        $('#action-select').html('Select All');
        $('.helpdesk-list').addClass('selected-row');
    } else {
        $('#action-select').html('None')
        $('.helpdesk-list').removeClass('selected-row');
    }
});

$(".checkbox").click(function(){
    if($(this).prop('checked') == true){
        $(this).addClass('selected-row');
    } else {
        $(this).removeClass('selected-row');
    }
})

$(".assign-table-agent").on('click', function(e){
    e.preventDefault()
    var actionurl = $('#hpdk').attr('data-url');
    var elements = document.getElementsByClassName("selected-row");

    var dataID = [];
    for (var i = 0; i<elements.length; i++) {
        dataID.push(elements[i].id);
    }

    var owner = $(this).attr('data-id');
    var postData = { ticketId: dataID, assignee: owner };

    $.ajax({
        url: actionurl,
        type: 'POST',
        data: postData,
        success: function(data) {
            var content = JSON.parse(data);
            if(content.success == true){
                $.each(dataID, function(key, value){
                    var msg = 'Assigned To: ' + content.assignee;
                    $('.assigned-badge' + value).html(msg);
                });
            }
        },
        error: function(data) {}
    })
})

$(".prioritise-table").on('click', function(e){
    e.preventDefault()
    var actionurl = $('.bulk-priority').attr('data-url');
    var elements = document.getElementsByClassName("selected-row");

    var dataID = [];
    for (var i = 0; i<elements.length; i++) {
        dataID.push(elements[i].id);
    }

    var priority = $(this).attr('data-id');
    var postData = { ticketId: dataID, priority: priority };

    $.ajax({
        url: actionurl,
        type: 'POST',
        data: postData,
        success: function(data) {
            var content = JSON.parse(data);
            if(content.success == true){
                $.each(dataID, function(key, value){
                    $('.priority-badge' + value).html(content.priority);
                });
            }
        },
        error: function(data) {}
    })
})

$(".resolve-table").on('click', function(e){
    e.preventDefault()
    var actionurl = $('.bulk-action').attr('data-url');
    var elements = document.getElementsByClassName("selected-row");

    var dataID = [];
    for (var i = 0; i<elements.length; i++) {
        dataID.push(elements[i].id);
    }

    var resolve = $(this).attr('data-id');
    var postData = { ticketId: dataID, resolve: resolve };

    $.ajax({
        url: actionurl,
        type: 'POST',
        data: postData,
        success: function(data) {
            var content = JSON.parse(data);
            if(content.success == true){
                $.each(dataID, function(key, value){
                    if(content.resolve == 'resolved'){
                        $('.badge-pending' + value).hide();
                    } else if(content.resolve == 'pending'){
                        $('.badge-pending' + value).show();
                    }
                    $('.resolve-badge' + value).html(content.resolve);
                });
            }
        },
        error: function(data) {}
    })
})