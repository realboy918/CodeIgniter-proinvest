<?php
$userId = $this->security->xss_clean($userInfo->userId);
$fname = set_value('fname') == false ? $this->security->xss_clean($userInfo->firstName) : set_value('fname');
$lname = set_value('lname') == false ? $this->security->xss_clean($userInfo->lastName) : set_value('lname');
$email = set_value('email') == false ? $this->security->xss_clean($userInfo->email) : set_value('email');
$mobile = $this->security->xss_clean($userInfo->mobile);
$code = $this->security->xss_clean($userInfo->refCode);
$roleId = $this->security->xss_clean($userInfo->roleId);
$total = $this->security->xss_clean($accountInfo);
if($this->uri->segment(1) == 'changepass'){
    $tab = '2';
} else if($this->uri->segment(1) == 'profileUpdate' || $this->uri->segment(1) == 'profile'){
    $tab = '1';
} else if($this->uri->segment(1) == 'paymentInfo') {
    $tab = '3';
}
?>
<!-- Site Content Wrapper -->
<div class="dt-content-wrapper">
    <div class="hide" id="active-tab" data-id="#tab-pane<?php echo $tab ?>" ></div>
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
                        <img class="dt-avatar dt-avatar__shadow size-90 mr-sm-4"
                            src="<?php echo $ppic ?>" id="ppic" alt="Logo">
                        <!-- /avatar -->

                        <!-- Info -->
                        <div class="dt-avatar-info">
                            <span
                                class="dt-avatar-name display-4 mb-2 font-weight-light"><?php echo $fname.' '.$lname ?></span>
                            <span class="f-16"><?php echo $email ?></span>
                            <div class="dropdown mt-2">

                                <!-- Profile Pic Uploader -->
                                <?php echo form_open(base_url( 'user/logo_update' ) , array( 'id' => 'upload_form', 'enctype' => 'multipart/form-data' ));?>
                                    <div class="upload-btn-wrapper">
                                    <button class="dropdown-toggle no-arrow text-white bg-transparent border-n">
                                        <i class="icon icon-settings icon-xl mr-2"></i>
                                        <span class="d-sm-inline-block"><?php echo lang('change_profile_pic') ?></span>
                                    </button>
                                    <input type="file" name="profile-pic" id="imgInp"/>
                                    </div>
                                    <button id="ppic-save" type="submit" class="btn btn-info display-n bg-transparent border-n">
                                        <i class="icon icon-circle-add-o icon-xl mr-2"></i>
                                        <span class="d-sm-inline-block"><?php echo lang('save') ?></span>
                                    </button>
                                <?php echo form_close();?>
                            </div>
                        </div>
                        <!-- /info -->
                    </div>
                    <!-- /avatar wrapper -->

                    <div class="ml-sm-auto">
                        <!-- List -->
                        <div class="col-sm-12">
                            <?php if($roleId == ROLE_CLIENT) { ?>
                            <!--
                            <div class="d-flex align-items-baseline mb-1">
                                <span
                                    class="display-2 font-weight-500 text-light mr-2"><?php //echo $accountInfo>0 ? to_currency($accountInfo) : to_currency('0.00') ?></span>
                            </div>
                            <p class="mb-0">Overall balance</p>
                            -->
                            <?php } else if ($roleId == ROLE_ADMIN) { ?>
                            <div class="d-flex align-items-baseline mb-1">
                                <span class="display-2 font-weight-500 text-white mr-2"><?php echo lang('admin') ?></span>
                            </div>
                            <?php } else if ($roleId == ROLE_MANAGER) { ?>
                            <div class="d-flex align-items-baseline mb-1">
                                <span class="display-2 font-weight-500 text-white mr-2"><?php echo lang('manager') ?></span>
                            </div>
                            <?php } ?>
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
                    <div class="col-xl-12 order-xl-1">

                        <!-- Card -->
                        <div class="card">

                            <!-- Card Header -->
                            <div
                                class="card-header card-nav bg-transparent border-bottom d-sm-flex justify-content-sm-between">
                                <h3 class="mb-2 mb-sm-n5"><?php echo lang('account_details') ?></h3>

                                <ul class="card-header-links nav nav-underline" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo $tab == 1 ? 'active' : '' ?>" href="<?php echo base_url('profile') ?>"><?php echo lang('my_profile') ?></a>
                                    </li>
                                    <?php if ($roleId == ROLE_CLIENT) { ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo $tab == 3 ? 'active' : '' ?>" href="<?php echo base_url('paymentInfo') ?>"><?php echo lang('payment_account') ?></a>
                                    </li>
                                    <?php } ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo $tab == 2 ? 'active' : '' ?>" href="<?php echo base_url('changepass') ?>"><?php echo lang('security') ?></a>
                                    </li>
                                </ul>

                            </div>
                            <!-- /card header -->

                            <!-- Card Body -->
                            <div class="card-body pb-2">

                                <!-- Tab Content-->
                                <div class="tab-content">

                                    <!-- Tab panel -->
                                    <div id="tab-pane1" class="tab-pane <?php echo $tab == 1 ? 'active' : '' ?>">
                                        <!-- Grid -->
                                        <div class="row">
                                            <!-- Grid Item -->
                                            <div class="col-xl-12">
                                                <?php
                                                if($tab == 1){
                                                    $this->load->helper('form');
                                                    $error = $this->session->flashdata('error');
                                                    if(validation_errors() != false) { ?>
                                                    <div class="alert alert-danger alert-dismissable">
                                                        <button type="button" class="close" data-dismiss="alert"
                                                            aria-hidden="true">×</button>
                                                        <?php echo lang('please_correct_errors_and_try_again'); ?>
                                                    </div>
                                                <?php } ?>
                                                <?php $success = $this->session->flashdata('success');
                                                    if($success) { ?>
                                                    <div class="alert alert-success alert-dismissable">
                                                        <button type="button" class="close" data-dismiss="alert"
                                                            aria-hidden="true">×</button>
                                                        <?php echo $this->session->flashdata('success'); ?>
                                                    </div>
                                                <?php } }?>

                                                <!-- Form -->
                                                <?php echo form_open(base_url( 'profileUpdate' ) , array( 'id' => 'editProfile' ));?>
                                                    <!-- Row -->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <!-- Form Group -->
                                                                    <div class="form-group">
                                                                        <label for="fname"><?php echo lang('first_name') ?></label>
                                                                        <input type="text" value="<?php echo $fname; ?>"
                                                                            name="fname" class="form-control <?php echo form_error('fname') == TRUE ? 'inputTxtError' : ''; ?>" id="fname"
                                                                            aria-describedby="fname" placeholder="<?php echo lang('first_name') ?>">
                                                                        <label class="error" for="fname"><?php echo form_error('fname'); ?></label>
                                                                    </div>
                                                                    <!-- /form group -->
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <!-- Form Group -->
                                                                    <div class="form-group">
                                                                        <label for="lname"><?php echo lang('last_name') ?></label>
                                                                        <input type="text" value="<?php echo $lname; ?>"
                                                                            name="lname" class="form-control <?php echo form_error('lname') == TRUE ? 'inputTxtError' : ''; ?>" id="lname"
                                                                            aria-describedby="lname" placeholder="<?php echo lang('last_name') ?>">
                                                                        <label class="error" for="fname"><?php echo form_error('lname'); ?></label>
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
                                                                        <label for="email"><?php echo lang('email') ?></label>
                                                                        <input type="email" value="<?php echo $email; ?>"
                                                                            class="form-control <?php echo form_error('email') == TRUE ? 'inputTxtError' : ''; ?>" name="email" id="email"
                                                                            aria-describedby="email" placeholder="<?php echo lang('email') ?>">
                                                                        <label class="error" for="fname"><?php echo form_error('email'); ?></label>
                                                                    </div>
                                                                    <!-- /form group -->
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <!-- Form Group -->
                                                                    <div class="form-group">
                                                                        <label for="phone"><?php echo lang('phone') ?></label>
                                                                        <input type="tel" value="<?php echo $mobile; ?>"
                                                                            class="form-control <?php echo form_error('phone') == TRUE ? 'inputTxtError' : ''; ?>" name="phone" id="phone"
                                                                            aria-describedby="phone">
                                                                        <input type="hidden" name="phonefull" id="phonefull" />
                                                                        <label class="error" for="phone"><?php echo form_error('phone'); ?></label>
                                                                    </div>
                                                                    <!-- /form group -->
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <!-- Form Group -->
                                                                    <div class="form-group mb-10">
                                                                        <button type="button" class="btn btn-info text-uppercase w-100"
                                                                            data-toggle="modal" data-target="#form-modal"><?php echo lang('save') ?></button>
                                                                    </div>
                                                                    <!-- /form group -->
                                                                </div>
                                                            </div>
                                                            <!-- /row -->
                                                        </div>
                                                        <div class="col-md-6">
                                                            <?php if ($roleId == ROLE_CLIENT) { ?>
                                                            <!-- Row -->
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <!-- Form Group -->
                                                                    <div class="form-group">
                                                                        <label for="ref"><?php echo lang('referral_code') ?></label>
                                                                        <p class="display-4"><?php echo $code ?>
                                                                            <p>
                                                                    </div>
                                                                    <!-- /form group -->
                                                                </div>
                                                            </div>
                                                            <!-- /row -->
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="dt-card__body">
                                                        <!-- Modal -->
                                                        <div class="modal fade display-n" id="form-modal" tabindex="-1" role="dialog"
                                                            aria-labelledby="model-8" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">

                                                                <!-- Modal Content -->
                                                                <div class="modal-content">

                                                                    <!-- Modal Header -->
                                                                    <div class="modal-header">
                                                                        <h3 class="modal-title" id="model-8"><?php echo lang('enter_password_to_proceed') ?></h3>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <!-- /modal header -->

                                                                    <!-- Modal Body -->
                                                                    <div class="modal-body">
                                                                        <form>
                                                                            <div class="form-group">
                                                                                <input class="form-control <?php echo form_error('password') == TRUE ? 'inputTxtError' : ''; ?>" name="password"
                                                                                    id="password" type="password">
                                                                                <label class="error" for="fname"><?php echo form_error('password'); ?></label>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <!-- /modal body -->

                                                                    <!-- Modal Footer -->
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-secondary btn-sm"
                                                                            data-dismiss="modal"><?php echo lang('cancel') ?>
                                                                        </button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary btn-sm"><?php echo lang('save') ?>
                                                                        </button>
                                                                    </div>
                                                                    <!-- /modal footer -->

                                                                </div>
                                                                <!-- /modal content -->

                                                            </div>
                                                        </div>
                                                        <!-- /modal -->
                                                    </div>
                                                <?php echo form_close();?>
                                                <!-- /form -->
                                            </div>
                                            <!-- /grid item -->
                                        </div>
                                        <!-- /grid -->
                                    </div>
                                    <!-- /tab panel -->
                                    <?php if ($roleId == ROLE_CLIENT) { ?>
                                    <!-- Tab panel -->
                                    <div id="tab-pane" class="tab-pane <?php echo $tab == 3 ? 'active' : '' ?>">
                                        <!-- Grid -->
                                        <div class="row">
                                            <!-- Grid Item -->
                                            <div class="col-xl-6">
                                                <?php
                                                if($tab == 3){
                                                    $this->load->helper('form');
                                                    $error = $this->session->flashdata('error');
                                                    if(validation_errors() != false) { ?>
                                                    <div class="alert alert-danger alert-dismissable">
                                                        <button type="button" class="close" data-dismiss="alert"
                                                            aria-hidden="true">×</button>
                                                        <?php echo lang('please_correct_errors_and_try_again'); ?>
                                                    </div>
                                                <?php } ?>
                                                <?php  
                                                    $success = $this->session->flashdata('success');
                                                    if($success)
                                                    {
                                                    ?>
                                                <div class="alert alert-success alert-dismissable">
                                                    <button type="button" class="close" data-dismiss="alert"
                                                        aria-hidden="true">×</button>
                                                    <?php echo $this->session->flashdata('success'); ?>
                                                </div>
                                                <?php } }?>
                                                <!-- Form -->
                                                <?php echo form_open(base_url( 'paymentInfo' ));?>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <!-- Form Group -->
                                                                    <div class="form-group">
                                                                        <label for="paymentType"><?php echo lang('type') ?></label>
                                                                        <select class="form-control" name="paymentType" id="simple-select">
                                                                            <option value="" selected disabled hidden><?php echo lang('choose_here') ?></option>
                                                                            <?php foreach($withdrawalMethods as $method) {?>
                                                                                <option value="<?= $this->security->xss_clean($method->name) ?>" <?php echo $method->name == $userInfo->pmtType ? 'selected' : ''; ?>><?php echo $this->security->xss_clean($method->name) ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                        <label class="error" for="paymentType"><?php echo form_error('paymentType'); ?></label>
                                                                    </div>
                                                                    <!-- /form group -->
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <!-- Form Group -->
                                                                    <div class="form-group">
                                                                        <label for="bitcoinAd"><?php echo lang('account') ?></label>
                                                                        <input type="text"
                                                                            value="<?php echo $this->security->xss_clean($userInfo->pmtAccount) ?>"
                                                                            class="form-control" name="paymentAccount"
                                                                            aria-describedby="bitcoinAd"
                                                                            placeholder="Payment Account">
                                                                        <label class="error" for="paymentAccount"><?php echo form_error('paymentAccount'); ?></label>
                                                                    </div>
                                                                    <!-- /form group -->
                                                                </div>
                                                            </div>
                                                            <!-- /row -->
                                                        </div>
                                                    </div>

                                                    <!-- Form Group -->
                                                    <div class="form-group mb-0">
                                                        <button type="button" class="btn btn-primary text-uppercase"
                                                            data-toggle="modal" data-target="#form1-modal"><?php echo lang('save') ?></button>
                                                    </div>
                                                    <!-- /form group -->
                                                    <div class="dt-card__body">

                                                        <!-- Modal -->
                                                        <div class="modal fade display-n" id="form1-modal" tabindex="-1"
                                                            role="dialog" aria-labelledby="model-8" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered"
                                                                role="document">

                                                                <!-- Modal Content -->
                                                                <div class="modal-content">

                                                                    <!-- Modal Header -->
                                                                    <div class="modal-header">
                                                                        <h3 class="modal-title" id="model-8"><?php echo lang('enter_password_to_proceed') ?></h3>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <!-- /modal header -->

                                                                    <!-- Modal Body -->
                                                                    <div class="modal-body">
                                                                        <form>
                                                                            <div class="form-group">
                                                                            <input class="form-control <?php echo form_error('password') == TRUE ? 'inputTxtError' : ''; ?>" name="password"
                                                                                    id="password" type="password">
                                                                                <label class="error" for="fname"><?php echo form_error('password'); ?></label>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <!-- /modal body -->

                                                                    <!-- Modal Footer -->
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-secondary btn-sm"
                                                                            data-dismiss="modal"><?php echo lang('cancel') ?>
                                                                        </button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary btn-sm"><?php echo lang('save') ?>
                                                                        </button>
                                                                    </div>
                                                                    <!-- /modal footer -->
                                                                </div>
                                                                <!-- /modal content -->
                                                            </div>
                                                        </div>
                                                        <!-- /modal -->
                                                    </div>
                                                <?php echo form_close();?>
                                                <!-- /form -->

                                            </div>
                                            <!-- /grid item -->

                                        </div>
                                        <!-- /grid -->

                                    </div>
                                    <!-- /tab panel -->
                                    <?php } ?>

                                    <!-- Tab panel -->
                                    <div id="tab-pane2" class="tab-pane <?php echo $tab == 2 ? 'active' : '' ?>">
                                        <div class="row">
                                            <div class="col-12">
                                                <?php if($tab == 2){
                                                    $this->load->helper('form');
                                                    $error = $this->session->flashdata('error');
                                                    if(validation_errors() != false) { ?>
                                                    <div class="alert alert-danger alert-dismissable">
                                                        <button type="button" class="close" data-dismiss="alert"
                                                            aria-hidden="true">×</button>
                                                        <?php echo lang('please_correct_errors_and_try_again'); ?>
                                                    </div>
                                                    <?php } ?>
                                                    <?php $success = $this->session->flashdata('success');
                                                    if($success) {?>
                                                    <div class="alert alert-success alert-dismissable">
                                                        <button type="button" class="close" data-dismiss="alert"
                                                            aria-hidden="true">×</button>
                                                        <?php echo $this->session->flashdata('success'); ?>
                                                    </div>
                                                <?php } } ?>
                                            </div>
                                            <!-- Grid Item -->
                                            <div class="col-xl-6">
                                                <!-- Form -->
                                                <?php echo form_open(base_url( 'changepass' ) , array( 'id' => 'resetForm' ));?>
                                                <!-- Form Group -->
                                                <div class="form-group">
                                                    <label for="currentpass"><?php echo lang('current_password') ?></label>
                                                    <input class="form-control <?php echo form_error('oldPassword') == TRUE ? 'inputTxtError' : ''; ?>" name="oldPassword" type="password">
                                                    <label class="error" for="fname"><?php echo form_error('oldPassword'); ?></label>
                                                </div>
                                                <!-- /form group -->
                                                <!-- Form Group -->
                                                <div class="form-group">
                                                    <label for="newpass"><?php echo lang('new_password') ?></label>
                                                    <input class="form-control <?php echo form_error('newPassword') == TRUE ? 'inputTxtError' : ''; ?>" name="newPassword" type="password">
                                                    <label class="error" for="fname"><?php echo form_error('newPassword'); ?></label>
                                                </div>
                                                <!-- /form group -->
                                                <!-- Form Group -->
                                                <div class="form-group">
                                                    <label for="cpass"><?php echo lang('confirm_password') ?></label>
                                                    <input class="form-control <?php echo form_error('cNewPassword') == TRUE ? 'inputTxtError' : ''; ?>" name="cNewPassword" type="password">
                                                    <label class="error" for="fname"><?php echo form_error('cNewPassword'); ?></label>
                                                </div>
                                                <!-- /form group -->
                                                <button type="submit" class="btn btn-primary text-uppercase"><?php echo lang('change_password') ?></button>
                                                <?php echo form_close();?>
                                                <!-- /form -->
                                            </div>
                                            <!-- /grid item -->
                                            <?php if($companyInfo['two_factor_auth_active'] == 1) {?>
                                            <div class="col-xl-6">
                                                <label><?php echo lang('two_factor_authentication') ?></label>
                                                <div class="row">
                                                    <div class="<?php echo $companyInfo['two_factor_auth'] == 'Authy' ? 'col-md-12' : 'col-md-6' ?>">
                                                        <?php if($twfa == 0) {?>
                                                        <button type="button" class="btn btn-primary text-uppercase" data-toggle="modal" data-target="#authenticate-modal"><?php echo lang('please_activate') ?></button>
                                                        <?php } else {?>
                                                            <button type="button" class="btn btn-success text-uppercase"><?php echo lang('activated') ?></button>
                                                        <?php }?>
                                                        <br>
                                                        <?php if($companyInfo['two_factor_auth'] == 'Google Authenticator') {?>
                                                        <small><?php echo lang('google_2fa_instructions') ?></small>
                                                        <?php } else if($companyInfo['two_factor_auth'] == 'Authy') {?>
                                                        <small><?php echo lang('authy_2fa_instructions') ?></small>
                                                        <?php }?>
                                                    </div>
                                                    <?php if($companyInfo['two_factor_auth'] == 'Google Authenticator') {?>
                                                    <div class="col-md-6">
                                                        <img src="<?php echo $token; ?>" style="height: 9em">
                                                    </div>
                                                    <?php }?>
                                                </div>
                                            </div>
                                            <?php }?>
                                            <!-- /grid item -->
                                        </div>
                                        <!-- /grid -->
                                    </div>
                                    <!-- /tab panel -->

                                    <!-- Modal -->
                                    <div class="modal fade" id="authenticate-modal" tabindex="-1"
                                        role="dialog" aria-labelledby="model-8" aria-hidden="true">
                                        <?php echo form_open(base_url('activate2fa') , array( 'id' => 'google2FAForm' ));?>
                                        <div class="modal-dialog modal-dialog-centered"
                                            role="document">

                                            <!-- Modal Content -->
                                            <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <?php if($companyInfo['two_factor_auth'] == 'Google Authenticator') {?>
                                                    <h3 class="modal-title" id="model-8">Enter 6 Digit Code</h3>
                                                    <?php } else if($companyInfo['two_factor_auth'] == 'Authy') { ?>
                                                    <h3 class="modal-title" id="model-8"><?php echo lang('enter_phone_number') ?></h3>
                                                    <?php }?>
                                                    <button type="button" class="close"
                                                        data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <!-- /modal header -->

                                                <!-- Modal Body -->
                                                <div class="modal-body">
                                                    <?php if($companyInfo['two_factor_auth'] == 'Google Authenticator') {?>
                                                    <form>
                                                        <div class="form-group">
                                                        <div id="divOuter">
                                                            <div id="divInner">
                                                                <input id="partitioned" name="token" type="text" maxlength="6" />
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </form>
                                                    <?php } else if($companyInfo['two_factor_auth'] == 'Authy') { ?>
                                                    <div class="col-md-12">
                                                        <!-- Form Group -->
                                                        <div class="form-group">
                                                            <label for="phone"><?php echo lang('phone') ?></label>
                                                            <input type="tel" value="<?php echo $mobile; ?>"
                                                                class="form-control width-phone <?php echo form_error('phone') == TRUE ? 'inputTxtError' : ''; ?>" name="phone" id="authyphone"
                                                                aria-describedby="phone">
                                                            <input type="hidden" name="countrycode" id="countrycode" />
                                                            <label class="error" for="phone"><?php echo form_error('phone'); ?></label>
                                                        </div>
                                                        <!-- /form group -->
                                                    </div>
                                                    <?php }?>
                                                </div>
                                                <!-- /modal body -->

                                                <!-- Modal Footer -->
                                                <div class="modal-footer">
                                                    <button type="button"
                                                        class="btn btn-secondary btn-sm"
                                                        data-dismiss="modal"><?php echo lang('cancel') ?>
                                                    </button>
                                                    <button type="submit" id="activate-submit"
                                                        class="btn btn-primary btn-sm" data-loading-text="<?php echo lang('processing_data') ?>" data-title="<?php echo lang("activate") ?>"><?php echo lang('activate') ?>
                                                    </button>
                                                </div>
                                                <!-- /modal footer -->
                                            </div>
                                            <!-- /modal content -->
                                        </div>
                                        <?php echo form_close();?>
                                    </div>
                                    <!-- /modal -->

                                </div>
                                <!-- /tab content-->

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
    <!-- /site content -->

<script src="<?php echo base_url('/assets/dist/js/profile.js') ?>"></script>
<script src="<?php echo base_url('/assets/dist/js/intlTelInput.js') ?>"></script>
<script src="<?php echo base_url('/assets/dist/js/utils.js') ?>"></script>
<script>
    $(document).ready(function () {
        var active = $('#active-tab').attr('data-id');
        $('[href="'+ active +'"]').tab('show')

        var input = document.querySelector("#phone");
        var input2 = document.querySelector("#authyphone");
        window.intlTelInput(input,
        {
            separateDialCode: true,
            hiddenInput: "fullMobile"
        });
        var input = $('#authyphone');
        var country = $('#countrycode');
        var iti = intlTelInput(input.get(0))
        
        // set it's initial value
        country.val(iti.getSelectedCountryData().dialCode);

        // listen to the telephone input for changes
        input.on('countrychange', function(e) {
        // change the hidden input value to the selected country code
        country.val(iti.getSelectedCountryData().dialCode);
        });

        $("form").submit(function() {
        });var obj = document.getElementById('partitioned');
        obj.addEventListener('keydown', stopCarret); 
        obj.addEventListener('keyup', stopCarret); 

        function stopCarret() {
            if (obj.value.length > 5){
                setCaretPosition(obj, 5);
            }
        }

        function setCaretPosition(elem, caretPos) {
            if(elem != null) {
                if(elem.createTextRange) {
                    var range = elem.createTextRange();
                    range.move('character', caretPos);
                    range.select();
                }
                else {
                    if(elem.selectionStart) {
                        elem.focus();
                        elem.setSelectionRange(caretPos, caretPos);
                    }
                    else
                        elem.focus();
                }
            }
        }
    });
</script>