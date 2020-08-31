
<!-- Site Content Wrapper -->
<div class="dt-content-wrapper">

    <!-- Site Content -->
    <div class="dt-content">

    <!-- Page Header -->
    <div class="dt-page__header">
        <h1 class="dt-page__title" style="display: inline;">FAQ List</h1>
        <button id="newFaq" class="btn btn-primary btn-sm display-i ft-right"><?php echo lang('new') ?></button>
    </div>
    <!-- /page header -->

    <!-- Grid -->
    <div class="row">

        <!-- Grid Item -->
        <div class="col-xl-12">
            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">

                    <!-- Tables -->
                    <div class="table-responsive dataTables_wrapper dt-bootstrap4">

                        <div class="table-responsive">
                            <span class="d-block"></span>
                            <?php if(!empty($faqs)) { ?>
                            <table class="table table-striped mb-0" aria-describedby="data-table_info" role="grid">
                                <thead class="thead-light">
                                    <tr role="row">
                                        <th>Title</th>
                                        <th>Answer</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($faqs as $faq){
                                    ?>
                                    <tr>
                                        <td><?php echo $faq->question ?></td>
                                        <td><?php echo $faq->answer ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info trans-btn faqedit" title="Edit" data-id="<?php echo $faq->id ?>" data-q="<?php echo $faq->question ?>" data-a="<?php echo $faq->answer ?>"><?php echo lang('edit') ?></button> |
                                            <button class="btn btn-sm btn-danger trans-btn faqdelete" title="Edit" data-url="<?php echo base_url('delete_faq/').$faq->id ?>"><?php echo lang('delete') ?></button>
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php echo $this->pagination->create_links(); ?>
                            <?php } else { ?>
                            <div class="text-center mt-5">
                                <img src="<?php echo base_url('assets/dist/img/no-search-results.png') ?>" class="w-20rm">
                                <h1><?php echo lang('no_records_found') ?></h1>
                            </div>
                            <?php }?>
                        </div>
                    <!-- /tables -->

                </div>
                <!-- /card body -->

            </div>
            <!-- /card -->

        </div>
        <!-- /grid item -->

    </div>
    <!-- /grid -->
                                </div>
</div>
<!-- /site content -->
<?php $this->load->view('web/newfaq'); ?>
<?php $this->load->view('web/editfaq'); ?>
<script>
    $('#newFaq').on('click', function(e){
        $('.newdrawer').addClass('open');
    })
    $('.faqedit').on('click', function(e){
        $('.editdrawer').addClass('open');
        var ans = $(this).attr('data-a');
        var que = $(this).attr('data-q');
        var id = $(this).attr('data-id');

        $('#question').val(que);
        $('#ans').val(ans);
        $('#editFaq').attr('data-id', id);
    })
    $('#formFaq').on('submit', function(e){
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
                    location.reload()
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
    $('#editFaq').on('submit', function(e){
        e.preventDefault()
        var id = $('#editFaq').attr('data-id');
        var actionurl = e.currentTarget.action + '/' + id;

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
                    location.reload()
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
    $('.faqdelete').on('click', function(e){
        e.preventDefault();
        var actionurl = $(this).attr('data-url');

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
                    location.reload()
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
</script>

 