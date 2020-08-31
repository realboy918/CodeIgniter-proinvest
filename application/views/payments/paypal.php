<div class="dt-content-wrapper">

    <!-- Site Content -->
    <div class="dt-content">
        <!-- Profile -->
        <div class="profile">

            <!-- Profile Banner -->
            <div class="profile__banner">

                <!-- Page Header -->
                <div class="dt-page__header">
                    <h1 class="dt-page__title text-light display-i"><?php echo lang('deposits') ?>/ <?php echo lang('new') ?></h1>
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
                                                <?php echo lang('pay') ?>  </div>
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
                                <!-- Enter paypal payment info here -->
                                    <div class="row">
                                    <div class="container">
                                        <div class="starter-template">
                                            <h1><?php echo lang('paypal_payment') ?></h1>
                                            <p class="lead"><?php echo lang('pay_now') ?></p>
                                        </div>
                                        <div class="contact-form">
                                            <p class="notice error"><?= $this->session->flashdata('error_msg') ?></p><br/>
                                            <p class="notice error"><?= $this->session->flashdata('success_msg') ?></p><br/>
                                            <form method="post" class="form-horizontal" role="form" action="<?= base_url() ?>paypal/create_payment_with_paypal">
                                                <fieldset>
                                                    <input title="item_name" name="item_name" type="hidden" value="ahmed fakhr">
                                                    <input title="item_number" name="item_number" type="hidden" value="12345">
                                                    <input title="item_description" name="item_description" type="hidden" value="to buy samsung smart tv">
                                                    <input title="item_tax" name="item_tax" type="hidden" value="1">
                                                    <input title="item_price" name="item_price" type="hidden" value="7">
                                                    <input title="details_tax" name="details_tax" type="hidden" value="7">
                                                    <input title="details_subtotal" name="details_subtotal" type="hidden" value="7">

                                                    <div class="form-group">
                                                        <div class="col-sm-offset-5">
                                                            <button  type="submit"  class="btn btn-success"><?php echo lang('pay_now') ?></button>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </form>
                                        </div>
                                    </div><!-- /.container -->
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