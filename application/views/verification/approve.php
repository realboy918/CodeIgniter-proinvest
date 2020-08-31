<!-- Site Content Wrapper -->
<div class="dt-content-wrapper">
    <!-- Site Content -->
    <div class="dt-content">
        <!-- Profile -->
        <div class="profile">
            <!-- Profile Banner -->
            <div class="profile__banner">
                <!-- Profile Banner Top -->
                <div class="profile__banner-detail">
                    <!-- Avatar Wrapper -->
                    <div class="dt-avatar-wrapper">
                        <!-- Avatar -->
                        <img class="dt-avatar dt-avatar__shadow size-90 mr-sm-4" src="<?php echo base_url('assets/dist/img/avatar.png') ?>" alt="<?php echo $verificationInfo->firstName.' '.$verificationInfo->lastName ?>">
                        <!-- /avatar -->
                        <!-- Info -->
                        <div class="dt-avatar-info">
                            <span class="dt-avatar-name display-4 mb-2 font-weight-light"><?php echo $verificationInfo->firstName.' '.$verificationInfo->lastName ?></span>
                            <span class="f-16"><?php echo $verificationInfo->email; ?></span>
                        </div>
                        <!-- /info -->
                    </div>
                    <!-- /avatar wrapper -->

                    <div class="ml-sm-auto">
                        <!-- List -->
                        <div class="col-sm-12">
                            <div class="d-flex align-items-baseline mb-1">
                                <span class="display-4 font-weight-500 text-white mr-2 text-capitalize">
                                    <?php echo $verificationInfo->verification_status ?>
                                </span>
                            </div>
                        </div>
                        <!-- /list -->
                    </div>
                </div>
                <!-- /profile banner top -->

            </div>
            <!-- /profile banner -->

            <!-- Profile Content -->
            <div class="profile-content">
            <!-- Grid -->
            <div class="row">
                <!-- Grid Item -->
                <div class="col-xl-12 col-12 order-xl-1">
                    <div class="dt-card">
                        <div class="dt-card__body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h1>Uploaded payment info <b class="text-capitalize ver2 <?php echo $verificationInfo->verification_status == 'approved' ? 'green' : 'red' ?> f-12"><?php if($verificationInfo->verification_status == 'pending'){ echo '[pending approval]';} else if($verificationInfo->verification_status == 'approved'){ echo '[approved]'; } else if($verificationInfo->verification_status == 'resubmit'){echo '[pending resubmission]';} else if($verificationInfo->verification_status == 'rejected'){ echo '[rejected]'; } else if($verificationInfo->verification_status == 'resubmit'){ echo '[Resubmitted]'; } ?></b></h1>
                                    <a href="<?php echo base_url('uploads/').$verificationInfo->payment_proof_img; ?>" target="_blank" class="btn btn-primary">Click to view or download image</a>
                                    <br>
                                    <br>
                                    <div class='apreas2'>
                                        <?php echo form_open(base_url( 'ver_approve/' ).$verificationInfo->userId , array( 'class' => 'veriForm', 'data-id'=>'appreas2', 'data-value'=>'ver2', 'enctype=' => 'multipart/form-data' ));?>
                                            <button type="submit" class="btn btn-primary appreas2">Approve</button>
                                            <button type="button" class="btn btn-danger reject" id="reas2">Reject</button>
                                        <?php echo form_close();?>
                                    </div>
                                </div>
                                <div class="col-md-6 <?php echo $verificationInfo->rejection_reason != null ? '' : 'hide' ; ?> reas2">
                                    <?php echo form_open(base_url( 'ver_reject/' ).$verificationInfo->userId , array( 'class' => 'veriForm', 'data-id'=>'appreas2', 'data-value'=>'ver2', 'enctype=' => 'multipart/form-data' ));?>
                                    <div class="form-group">
                                        <label for="rejection">Rejection reason</label>
                                        <textarea type="text" id="reason2" class="form-control" name="reason" rows="8"><?php echo $verificationInfo->rejection_reason ?></textarea>
                                    </div>
                                    <button class="btn btn-primary">Submit</button>
                                    <button class="btn btn-danger reject-cancel" data-id="reas2">Cancel</button>
                                    <?php echo form_close();?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <!-- /profile content -->

        </div>
        <!-- /Profile -->


    </div>
    <script>
        $('.reject').click(function(e){
            e.preventDefault();
            var id = $(this).attr('id');
            $('.' + id).removeClass('hide');
            $('#' + id).addClass('hide');
            $('.app' + id).addClass('hide');
        })
        $('.reject-cancel').click(function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            $('.' + id).addClass('hide');
            $('.app' + id).removeClass('hide');
            $('#' + id).removeClass('hide');
        })
        $('.veriForm').submit(function(e){
            e.preventDefault();
            var actionurl = e.currentTarget.action;
            var ver = $(this).attr('data-value');
            var val = $(this).attr('data-id');
            $.ajax({
                method: 'POST',
                url: actionurl,
                data: $(this).serialize(),
                success: function(data){
                    var content = JSON.parse(data);
                    swal(
                        content.success == true ? 'Success!' : 'Error!',
                        content.msg,
                        content.success == true ? 'success' : 'error'
                    )
                    if(content.success){
                        $('.' + ver).html(content.status_value);
                        if(content.status == 'approve')
                        {
                            $('.' + ver).addClass('green');
                            $('.' + ver).removeClass('red');
                            $('.' + val).addClass('hide');
                            $('.reas' + content.level).addClass('hide');
                            $('#reas' + content.level).removeClass('hide');
                        } else {
                            $('.appreas' + content.level).removeClass('hide');
                            $('#reason' + content.level).attr("disabled", true);
                        }
                    }

                },
                error: function(data){} 
            })
        })
    </script>