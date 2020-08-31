
<!-- Site Content Wrapper -->
<div class="dt-content-wrapper">

    <!-- Site Content -->
    <div class="dt-content">

    <!-- Page Header -->
    <div class="dt-page__header">
        <h1 class="dt-page__title" style="display: inline;"><?php echo lang('privacy_policy') ?></h1>
        <button id="editContent" class="btn btn-primary btn-sm display-i ft-right"><?php echo lang('save') ?></button>
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
                <?php echo form_open(base_url( 'edit_policy' ), array( 'id' => 'policyForm' ));?>
                    <textarea id="summernote" name="policycontent"><?php echo $policy; ?></textarea>
                <?php echo form_close();?>   
                </div>
                <!-- /card body -->

            </div>
            <!-- /card -->

        </div>
        <!-- /grid item -->

    </div>
    <!-- /grid -->
</div>
<!-- /site content -->
<script>
    $('#editContent').on('click', function(e){
        e.preventDefault();
        var actionurl = $('#policyForm').attr('action');

        $.ajax({
            url: actionurl,
            type: 'POST',
            data: $('#policyForm').serialize(),
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