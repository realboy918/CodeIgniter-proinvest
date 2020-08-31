<!-- Site Content Wrapper -->
<div class="dt-content-wrapper">
    <!-- Site Content -->
    <div class="dt-content">
        <!-- Grid -->
        <!-- Profile Content -->
        <div class="profile-content">
            <!-- Grid -->
            <div class="row">
                <div class="col-md-12">
                <?php echo form_open(base_url( 'verificationupload' ) , array( 'id' => 'veriForm', 'enctype=' => 'multipart/form-data' ));?>
                    <!-- Grid Item -->
                    <div class="col-xl-12 col-12">
                        <div class="dt-card <?php if($verificationInfo->verification_status == 'submitted'){ echo 'disabled';} ?>">
                            <div class="dt-card__body">
                                <div class="row">
                                    <div class="col-md-7">
                                        <h1 class="text-capitalize">Please make a Payment for account activation <b class="text-capitalize ver2 <?php echo $verificationInfo->verification_status == 'approved' ? 'green' : 'red' ?> f-12"><?php if($verificationInfo->verification_status == 'pending'){ echo '[pending approval]';} else if($verificationInfo->verification_status == 'approved'){ echo '[approved]'; } else if($verificationInfo->verification_status == 'resubmit'){echo '[pending resubmission]';} else if($verificationInfo->verification_status == 'submitted'){ echo '[Pending Approval]'; } else if($verificationInfo->verification_status == 'resubmit'){ echo '[Pending Resubmission]'; } ?></b></h1>
                                        <p class="f-12">Please make a payment to the following account for your account to be activated</p>
                                        <p>Bank Account: Demo Account Name</p>
                                        <p>Bank Account: Demo Account Number</p>
                                        <div class="form-group">
                                            <label for="source">Upload payment receipt</label>
                                            <input class="dropify" id="img" type="file" name="img">
                                        </div>
                                    </div>
                                    <?php if($verificationInfo->verification_status == 'resubmit'){ ?>
                                    <div class="col-md-5 disabled <?php echo $verificationInfo->rejection_reason != null ? '' : 'hide' ; ?>">
                                    <br>
                                        <div class="form-group">
                                            <label for="rejection">Rejection reason</label>
                                            <textarea type="text" id="reason2" class="form-control" name="reason" rows="8"><?php echo $verificationInfo->rejection_reason ?></textarea>
                                        </div>
                                    </div>
                                    <?php }?>
                                </div>
                                <button type="submit" id="submit" class="btn btn-primary center-align">Confirm payment</button>
                            </div>
                        </div>
                    </div>
                <?php echo form_close();?>
                </div>
                <div class="col-md-2">
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo base_url(); ?>assets/dist/js/dropify.js"></script>
    <script src="<?php echo base_url(); ?>assets/dist/js/dropify.min.js"></script>
    <script>
    $(document).ready(function(){
        // Basic
        $('.dropify').dropify();

        // Translated
        $('.dropify-fr').dropify({
            messages: {
                default: 'Glissez-déposez un fichier ici ou cliquez',
                replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                remove:  'Supprimer',
                error:   'Désolé, le fichier trop volumineux'
            }
        });

        // Used events
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function(event, element){
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element){
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element){
            console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e){
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })
    });
    $('#document-select').on('change', function() {
        var val = this.value;
        $('.guideimg').addClass('hide');
        $('img1').addClass('hide');
        $('img2').addClass('hide');
        $('#img-upload-2').addClass('hide');
        if(val == 'International passport')
        {
            $('#passportimg').removeClass('hide');
            $('#img1-label').html('Upload Document');
            $('#img-upload-1').removeClass('hide');
        } else {
            $('#idimg').removeClass('hide');
            $('#img1-label').html('Frontside of ID');
            $('#img-upload-1').removeClass('hide');
            $('#img-upload-2').removeClass('hide');
        }
    });
    $('#proof-select').on('change', function() {
        $('#img-upload-3').removeClass('hide');
    });
    $('#veriForm').submit(function(e){
        e.preventDefault();
        var actionurl = e.currentTarget.action;
        var form = document.getElementById("veriForm");
        var formData = new FormData(form)
        $('#submit').attr('disabled', true);
        $("#submit").html('Processing');
        $.ajax({
            url:actionurl,
            method:"POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(data){
                var content = JSON.parse(data);
                swal(
                    content.success == true ? 'Success!' : 'Error!',
                    content.msg,
                    content.success == true ? 'success' : 'error'
                );

                if(content.success == true){
                    setTimeout(function(){
                        location.reload();
                        }, 3000);
                }
                $('#submit').attr('disabled', false);
                $("#submit").html('Submit');
            },
            error: function(data){
                swal(
                'Error!',
                'There is an issue in uploading your data. Please try again later',
                'error'
                );
                $('#submit').attr('disabled', false);
                $("#submit").html('Submit');
            }
        })
    })
    </script>