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
                            <div class="col-sm-6 col-12 z0">

                            </div>
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
                                <?php echo form_open(base_url( 'stripePost' ), array( 'id' => 'payment-form', 'class' => 'require-validation', 'data-cc-on-file' => 'false', 'data-stripe-publishable-key' => $publishable_key));?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Row -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- Form Group -->
                                                <div class='col-xs-12 form-group required'>
                                                    <label class='control-label'><?php echo lang('name_on_card') ?></label>
                                                    <input class='form-control' size='4' type='text'
                                                        placeholder="John Doe">
                                                </div>
                                                <!-- /form group -->
                                            </div>
                                        </div>
                                        <!-- /row -->
                                        <!-- Row -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- Form Group -->
                                                <div class="form-group">
                                                    <label for="card number"><?php echo lang('card_number') ?></label>
                                                    <input type="number" class="form-control card-number"
                                                        aria-describedby="cardnumber" placeholder="4242 4242 4242 4242">
                                                </div>
                                                <!-- /form group -->
                                            </div>
                                        </div>
                                        <!-- /row -->
                                        <!-- Row -->
                                        <div class='form-row row'>
                                            <div class='col-xs-12 col-md-4 form-group cvc required'>
                                                <label class='control-label'><?php echo lang('cvc') ?></label> <input autocomplete='off'
                                                    class='form-control card-cvc' placeholder='ex. 311' size='4'
                                                    type='text'>
                                            </div>
                                            <div class='col-xs-12 col-md-4 form-group expiration required'>
                                                <label class='control-label'><?php echo lang('expiration_month') ?></label> <input
                                                    class='form-control card-expiry-month' placeholder='MM' size='2'
                                                    type='text'>
                                            </div>
                                            <div class='col-xs-12 col-md-4 form-group expiration required'>
                                                <label class='control-label'><?php echo lang('expiration_year') ?></label> <input
                                                    class='form-control card-expiry-year' placeholder='YYYY' size='4'
                                                    type='text'>
                                            </div>
                                        </div>
                                        <div class='form-row row'>
                                            <div class='col-md-12 error form-group hide'>
                                                <div class='alert-danger alert'><?php echo lang('please_correct_errors_and_try_again') ?></div>
                                            </div>
                                        </div>
                                        <!-- /row -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mb-0">
                                                    <button type="submit" class="btn btn-info text-uppercase w-100"><?php echo lang('pay') ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Form Group -->

                                    <!-- /form group -->

                                    <!-- /form -->
                                </div>
                                <?php echo form_close();?>
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
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script type="text/javascript" src="<?php echo base_url("/assets/dist/js/functions.js") ?>"></script>