<div class="dt-content-wrapper">

    <!-- Site Content -->
    <div class="dt-content">
        <!-- Profile -->
        <div class="profile">

            <!-- Profile Banner -->
            <div class="profile__banner">

                <!-- Page Header -->
                <div class="dt-page__header">
                    <h1 class="dt-page__title text-light display-i"><?php echo lang('deposits') ?> / <?php echo lang('new') ?></h1>
                    <a href="" class="btn btn-light btn-sm display-i ft-right"><?php echo lang('back') ?></a>

                    <div class="dt-entry__header mt-1m">
                        <!-- Entry Heading -->
                        <div class="dt-entry__heading">
                        </div>
                        <!-- /entry heading -->
                    </div>
                </div>
                <!-- /page header -->

                <div class="profile__banner-detail">
                    <!-- Avatar Wrapper -->
                    <div class="col-12">
                        <div class="row">
                            <!-- Grid Item -->
                            <div class="col-sm-6 col-12"></div>
                            <!-- Grid Item -->

                            <!-- Grid Item -->
                            <div class="col-sm-6 col-12">

                                <!-- Card -->
                                <div class="dt-card dt-card__full-height text-dark">

                                    <!-- Card Body -->
                                    <div class="dt-card__body p-xl-8 py-sm-8 py-6 px-4">
                                        <span class="badge badge-secondary badge-top-right">
                                        <?php echo lang('make_a_payment') ?></span>
                                        <!-- Media -->
                                        <div class="media">

                                            <i class="icon icon-revenue-new icon-6x mr-6 align-self-center"></i>

                                            <!-- Media Body -->
                                            <div class="media-body">
                                                <div class="display-3 font-weight-600 mb-1 init-counter">
                                                <?php echo lang('pay') ?> <?php echo to_currency($payment); ?> </div>
                                                    <span class="d-block">
                                                    <?php echo lang('please_make_a_payment_within_5_minutes') ?></span>
                                            </div>
                                            <!-- /media body -->

                                        </div>
                                        <!-- /media -->
                                    </div>
                                    <!-- /card body -->

                                </div>
                                <!-- /card -->

                            </div>
                            <!-- Grid Item -->
                        </div>
                    </div>
                    <!-- /avatar wrapper -->
                </div>

            </div>
            <!-- /profile banner -->

            <!-- Profile Content -->
            <div class="profile-content marg-t-17 marg-t-0 ">

                <!-- Grid -->
                <div class="row">

                    <!-- Grid Item -->
                    <div class="col-xl-6 col-md-6 col-12 order-xl-1 z20">
                        <!-- Card -->
                        <div class="dt-card">
                        <div class="dt-card__body">
                                <?php
                                    $this->load->helper('form');
                                    $error = $this->session->flashdata('error');
                                    if($error)
                                    {
                                ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">×</button>
                                    <?php echo $this->session->flashdata('error'); ?>
                                </div>
                                <?php } 
                                    $success = $this->session->flashdata('success');
                                    if($success)
                                    {
                                ?>
                                <div class="alert alert-success alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">×</button>
                                    <?php echo $this->session->flashdata('success'); ?>
                                </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Card Body -->
                            <!-- Card Body -->
                            <div class="dt-card__body">
                                <!-- Form -->
                                    <div class="row">
                                        <div class="col-md-12">
                                        <div class="text-center display-3"><?php echo lang('pay') ?> <?php echo $amount2.' '.$currency2 ;?></div>
                                        <div class="text-center"><?php echo $QR; ?></div>
                                        <div class="text-center"><?php echo lang('payment_address') ?>: <span class="dashed-line p-0-3m"><?php echo $address ?></span></div>
                                        <div id="hidden"></div>
                                        <div class="mt-5">
                                        <p class="text center"><?php echo lang('please_send') ?> <?php echo $amount2.' '.$currency2 ;?> <?php echo lang('to_address') ?> <?php echo $address ?>.</p> 
                                        <div class="display-6 crypto-note">
                                        <button class="btn pay-ins crypto-note-button" id="ins1"><i class="icon icon-circle-add-o icon-xl mr-2"></i><?php echo lang('how_to_pay') ?></button>
                                        <p id="faqins1" class="fqins hide font-14p"><?php echo lang('coinpayments_instruction_1') ?></p>
                                        </div>
                                        <div class="display-6 crypto-note" id="pay-ins2">
                                        <button class="btn pay-ins crypto-note-button" id="ins2"><i class="icon icon-circle-add-o icon-xl mr-2"></i><?php echo lang('what_next_after_payment') ?></button>
                                        <p id="faqins2" class="fqins hide font-14p"><?php echo lang('coinpayments_instruction_2') ?></p>
                                        </div>
                                        <div class="display-6 crypto-note" id="ins3">
                                        <button class="btn pay-ins font-in p-0p w-100 txt-left" id="ins3"><i class="icon icon-circle-add-o icon-xl mr-2"></i><?php echo lang('what_if_i_dont_send_enough') ?></button>
                                        <p id="faqins3" class="fqins hide font-14p"><?php echo lang('coinpayments_instruction_3') ?></p>
                                        </div>
                                        </div>
                                        </div>
                                        <!-- Form Group -->

                                        <!-- /form group -->

                                        <!-- /form -->
                                    </div>
                                <!-- /card body -->
                            </div>
                            <!-- /card -->
                        </div>
                        <!-- /grid item -->
                    </div>
                    <!-- /grid -->
                </div>
                <!-- /profile content -->
            </div>
            <!-- /Profile -->
        </div>
    </div>
    <script src="<?php echo base_url("/assets/dist/js/functions.js") ?>"></script>
    <script>
    var evtSource = new EventSource("<?php echo base_url().'checkpayment/'.$invoice ?>");
    evtSource.onmessage = function(e) {
        var content = JSON.parse(e.data);
        if(content.success == true)
        {
            swal(
                'Success!',
                content.msg,
                'success'
            );
            e.target.close();
            var timer = setTimeout(function() {
                  window.location.replace('<?php echo base_url('deposits') ?>')
                }, 3000);
        }
    }
    </script>