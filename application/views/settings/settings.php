<?php
$refType = $this->security->xss_clean($companyInfo['refType']);
$refInt = $this->security->xss_clean($companyInfo['refInterest']);
if($refType == 'multiple') {
    $int_array = explode(",", $refInt);
    $intCount = count($int_array);
    $first = $int_array[0];
} else 
{
    $int_array = explode(",", $refInt);
    $intCount = count($int_array);
    $first = $int_array[0];
}
?>
<div class="dt-content-wrapper">
    <!-- Site Content -->
    <div class="dt-content">
        <!-- Profile -->
        <div class="profile">
            <!-- Profile Banner -->
            <div class="profile__banner">
                <!-- Page Header -->
                <div class="dt-page__header">
                    <h1 class="dt-page__title text-white display-i text-capitalize"><?php echo lang('settings') ?></h1>
                    <div class="dt-entry__header mt-1m">
                        <!-- Entry Heading -->
                        <div class="dt-entry__heading">
                        </div>
                        <!-- /entry heading -->
                    </div>
                </div>
                <!-- /page header -->
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
                                <ul class="card-header-links nav nav-underline" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#tab-pane1" role="tab"
                                            aria-controls="tab-pane1" aria-selected="true"><?php echo lang('company_information') ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tab-pane4" role="tab"
                                            aria-controls="tab-pane4" aria-selected="true"><?php echo lang('site_settings') ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tab-pane2" role="tab"
                                            aria-controls="tab-pane2" aria-selected="true"><?php echo lang('email_sms_settings') ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tab-pane3" role="tab"
                                            aria-controls="tab-pane3" aria-selected="true"><?php echo lang('earnings_settings') ?></a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /card header -->
                            <!-- Card Body -->
                            <div class="card-body pb-2">
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
                                <?php } ?>

                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                                    </div>
                                </div>

                                <!-- Tab Content-->
                                <div class="tab-content mt-5">
                                    <!-- Tab panel -->
                                    <div id="tab-pane1" class="tab-pane active">
                                        <!-- Grid -->
                                        <div class="row">
                                            <!-- Grid Item -->
                                            <div class="col-xl-6">
                                                <!-- Form -->
                                                <?php echo form_open(base_url( 'settings/companyInfo' ) , array( 'id' => 'companyProfile', 'enctype=' => 'multipart/form-data' ));?>
                                                    <!-- Row -->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <!-- Form Group -->
                                                            <div class="form-group">
                                                                <label for="companyName"><?php echo lang('company_name') ?></label>
                                                                <input type="text"
                                                                    value="<?php echo $this->security->xss_clean($companyInfo['name']) ?>"
                                                                    class="form-control" name="companyName"
                                                                    id="companyName" aria-describedby="companyName"
                                                                    placeholder="<?php echo lang('company_name') ?>">
                                                                <?php echo form_error('companyName'); ?>
                                                            </div>
                                                            <!-- /form group -->
                                                        </div>
                                                    </div>
                                                    <!-- /row -->
                                                    <!-- Row -->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <!-- Form Group -->
                                                            <div class="form-group">
                                                                <label for="phone1"><?php echo lang('company_phone') ?></label>
                                                                <input type="text"
                                                                    value="<?php echo $this->security->xss_clean($companyInfo['phone1'])?>"
                                                                    name="phone1" class="form-control" id="phone1"
                                                                    aria-describedby="phone1" placeholder="Phone 1">
                                                            </div>
                                                            <!-- /form group -->
                                                        </div>
                                                        <div class="col-md-6">
                                                            <!-- Form Group -->
                                                            <div class="form-group">
                                                                <label for="phone2"><?php echo lang('company_tel') ?></label>
                                                                <input type="text"
                                                                    value="<?php echo $this->security->xss_clean($companyInfo['phone2']) ?>"
                                                                    name="phone2" class="form-control" id="phone2"
                                                                    aria-describedby="phone2" placeholder="phone 2">
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
                                                                <label for="email"><?php echo lang('company_email') ?></label>
                                                                <input type="email"
                                                                    value="<?php echo $this->security->xss_clean($companyInfo['email']) ?>"
                                                                    class="form-control" name="email" id="email"
                                                                    aria-describedby="email" placeholder="<?php echo lang('email') ?>">
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
                                                                <label for="url"><?php echo lang('company_url') ?></label>
                                                                <input type="url"
                                                                    value="<?php echo $this->security->xss_clean($companyInfo['url']) ?>"
                                                                    class="form-control" name="url" id="url"
                                                                    aria-describedby="url" placeholder="Url">
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
                                                            <label for="address"><?php echo lang('company_address') ?></label>
                                                            <textarea name="address" class="form-control"
                                                                id="text-area-1" rows="5"
                                                                placeholder="<?php echo lang('company_address') ?>"><?php echo $this->security->xss_clean($companyInfo['address']) ?></textarea>
                                                        </div>
                                                        <!-- /form group -->
                                                    </div>
                                                </div>
                                                <!-- /row -->
                                            </div>
                                            <div class="col-md-6">
                                                <!-- Row -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <!-- Form Group -->
                                                        <div class="form-group">
                                                            <label for="currency"><?php echo lang('currency') ?></label>
                                                            <div class="bfh-selectbox bfh-currencies" data-blank="false" data-name="currency" data-currency="<?php echo $this->security->xss_clean($companyInfo['currency']) ?>" data-flags="true"></div>
                                                        </div>
                                                        <!-- /form group -->
                                                    </div>
                                                    <div class="col-md-6">
                                                        <!-- Form Group -->
                                                        <div class="form-group">
                                                            <label for="position"><?php echo lang('currency_position') ?></label>
                                                                <select class="form-control " name="position" id="simple-select">
                                                                    <option value="" selected="" disabled="" hidden="">Select</option>
                                                                    <option value="before" <?php echo $companyInfo['currency_position'] == 'before' ? 'selected="selected"' : '' ?>><?php echo lang('before_amount') ?></option>
                                                                    <option value="after" <?php echo $companyInfo['currency_position'] == 'after' ? 'selected="selected"' : '' ?>><?php echo lang('after_amount') ?></option>
                                                                </select>
                                                        </div>
                                                        <!-- /form group -->
                                                    </div>
                                                </div>
                                                <!-- /row -->
                                                <!-- Row -->
                                                <div class="row <?php echo $companyInfo['currency'] != 'USD' ? '' : 'hide' ?>" id="currency_exchange">
                                                    <div class="col-md-12">
                                                        <!-- Form Group -->
                                                        <div class="form-group">
                                                            <label for="excurrency"> 1 <b>USD</b> To <b id="local_currency"><?php echo $this->security->xss_clean($companyInfo['currency']) ?></b> <?php echo lang('exchange_rate') ?></label>
                                                            <input type="excurrency"
                                                                value="<?php echo $this->security->xss_clean($companyInfo['currency_exchange_rate']) ?>"
                                                                class="form-control" name="excurrency" id="excurrency"
                                                                aria-describedby="excurrency" placeholder="Exchange Rate">
                                                        </div>
                                                        <!-- /form group -->
                                                    </div>
                                                </div>
                                                <!-- Form Group -->
                                                <div class="form-group">
                                                    <label for="minwithdrawal"><?php echo lang('minimum_withdrawal') ?></label>
                                                    <div class="input-group">
                                                        <input class="form-control" type="number" step="any" name="minwithdrawal" value="<?php echo $companyInfo['min_withdrawal'] ?>">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><?php echo $companyInfo['currency'] ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /form group -->
                                                <!-- /row -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <!-- Form Group -->
                                                        <div class="form-group">
                                                            <label for="dark-logo"><?php echo lang('dark_logo') ?></label>
                                                            <input  type="file" aria-describedby="dark-logo" name="dark-logo" id="dark_logo_upload" />
                                                        </div>
                                                        <!-- /form group -->
                                                    </div> 
                                                    <div class="col-md-6">
                                                        <div id="uploaded_image_dark">
                                                            <img class="logo-showcase-dark" src="<?php echo $this->security->xss_clean($this->logoDark) ?>">
                                                        </div>
                                                    </div> 
                                                </div>   
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <!-- Form Group -->
                                                        <div class="form-group">
                                                            <label for="white-logo"><?php echo lang('white_logo') ?></label>
                                                            <input  type="file" aria-describedby="white-logo" name="white-logo" id="white-logo_upload" />
                                                        </div>
                                                        <!-- /form group -->
                                                    </div> 
                                                    <div class="col-md-6">
                                                        <div id="uploaded_image_white">
                                                            <img class="logo-showcase-white" src="<?php echo $this->security->xss_clean($this->logoWhite) ?>">
                                                        </div>
                                                    </div> 
                                                </div>      
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <!-- Form Group -->
                                                        <div class="form-group">
                                                            <label for="favicon-logo"><?php echo lang('favicon') ?></label>
                                                            <input  type="file" aria-describedby="favicon-logo" name="favicon-logo" id="favicon-logo_upload" />
                                                        </div>
                                                        <!-- /form group -->
                                                    </div> 
                                                    <div class="col-md-6">
                                                        <div id="uploaded_image_favicon">
                                                            <img class="logo-favicon" src="<?php echo $this->security->xss_clean($this->favicon) ?>">
                                                        </div>
                                                    </div> 
                                                </div>                                       
                                                <!-- Form Group -->
                                                <div class="form-group mb-0">
                                                    <button type="button" class="btn btn-primary text-uppercase"
                                                        data-toggle="modal" data-target="#form-modalcompanyProfile"><?php echo lang('save') ?></button>
                                                </div>
                                                <!-- /form group -->
                                                <div class="dt-card__body">

                                                    <!-- Modal -->
                                                    <div class="modal fade display-n" id="form-modalcompanyProfile" tabindex="-1" role="dialog"
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

                                                                    <div class="form-group">
                                                                        <input class="form-control" name="password"
                                                                            id="password" type="password">
                                                                    </div>

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

                                    <!-- Tab panel -->
                                    <div id="tab-pane4" class="tab-pane">
                                        <!-- Grid -->
                                        <div class="row">
                                            <!-- Grid Item -->
                                            <div class="col-xl-6">
                                                <!-- Form -->
                                                <?php echo form_open(base_url( 'settings/seo' ) , array( 'id' => 'seoUpdate', 'class' => 'form' ));?>
                                                    <!-- Row -->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <!-- Form Group -->
                                                            <div class="form-group">
                                                                <label for="companyName"><?php echo lang('site_title') ?></label>
                                                                <textarea type="text"
                                                                    class="form-control" name="title" rows="3"
                                                                    placeholder="<?php echo lang('site_title') ?>"><?php echo $this->security->xss_clean($companyInfo['title']) ?>
                                                                </textarea>
                                                                <?php echo form_error('title'); ?>
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
                                                                <label for="phone1"><?php echo lang('site_description') ?></label>
                                                                <textarea type="text"
                                                                    class="form-control" name="description" rows="3"
                                                                    placeholder="<?php echo lang('site_description') ?>"><?php echo $this->security->xss_clean($companyInfo['description']) ?>
                                                                </textarea>
                                                                <?php echo form_error('description'); ?>
                                                            </div>
                                                            <!-- /form group -->
                                                        </div>
                                                    </div> 
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <!-- Form Group -->
                                                            <div class="form-group">
                                                                <label for="phone2"><?php echo lang('keywords') ?></label>
                                                                <textarea type="text"
                                                                    class="form-control" name="keywords" rows="3"
                                                                    placeholder="<?php echo lang('keywords') ?>"><?php echo $this->security->xss_clean($companyInfo['keywords']) ?>
                                                                </textarea>
                                                                <?php echo form_error('keywords'); ?>
                                                            </div>
                                                            <!-- /form group -->
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="col-md-6">
                                                <!-- Row -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <!-- Form Group -->
                                                        <div class="form-group">
                                                            <label for="chatplugin"><?php echo lang('chat_plugin') ?></label>
                                                                <select class="form-control " name="chatplugin" id="chat-select">
                                                                    <option value="" selected="" disabled="" hidden="">Select</option>
                                                                    <option value="Tawk" <?php echo $companyInfo['chat_plugin'] == 'Tawk' ? 'selected' : '' ?>>Tawk.To</option>
                                                                </select>
                                                        </div>
                                                        <!-- /form group -->
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mt-2">
                                                            <!-- Checkbox -->
                                                            <div class="custom-control custom-checkbox custom-control-inline">
                                                                <input type="checkbox" value="1" id="customcheckboxInline1" name="actchatplugin" class="custom-control-input" <?php echo $companyInfo['chat_plugin_active'] == 1 ? 'checked=true' : '' ?>>
                                                                <label class="custom-control-label" for="customcheckboxInline1"><?php echo lang('activate_chat_plugin') ?></label>
                                                            </div>
                                                            <br>
                                                            <small class="form-text"><?php echo lang('if_checked_the_chat_plugin_selected_will_be_activated') ?></small>
                                                            <!-- /checkbox -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <!-- Form Group -->
                                                        <div class="form-group">
                                                            <label for="auth"><?php echo lang('two_factor_authenticator') ?></label>
                                                                <select class="form-control" name="auth" id="auth-select">
                                                                    <option value="" selected="" disabled="" hidden="">Select</option>
                                                                    <option value="Google Authenticator" <?php echo $companyInfo['two_factor_auth'] == 'Google Authenticator' ? 'selected' : '' ?>>Google Authenticator</option>
                                                                    <option value="Authy" <?php echo $companyInfo['two_factor_auth'] == 'Authy' ? 'selected' : '' ?>>Authy</option>
                                                                </select>
                                                        </div>
                                                        <!-- /form group -->
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mt-2">
                                                            <!-- Checkbox -->
                                                            <div class="custom-control custom-checkbox custom-control-inline">
                                                                <input type="checkbox" value="1" id="customcheckboxInline2" name="acttfa" class="custom-control-input" <?php echo $companyInfo['two_factor_auth_active'] == 1 ? 'checked=true' : '' ?>>
                                                                <label class="custom-control-label" for="customcheckboxInline2"><?php echo lang('activate_two_factor_auth') ?></label>
                                                            </div>
                                                            <br>
                                                            <small class="form-text"><?php echo lang('if_checked_the_selected_two_factor_authenticator_will_be_used_in_2FA_requests') ?></small>
                                                            <!-- /checkbox -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="mb-4">
                                                            <!-- Checkbox -->
                                                            <div class="custom-control custom-checkbox custom-control-inline">
                                                                <input type="checkbox" value="1" id="customcheckboxInline3" name="actrecaptcha" class="custom-control-input" <?php echo $companyInfo['google_recaptcha'] == 1 ? 'checked=true' : '' ?>>
                                                                <label class="custom-control-label" for="customcheckboxInline3"><?php echo lang('activate_google_recaptcha') ?></label>
                                                            </div>
                                                            <br>
                                                            <small class="form-text"><?php echo lang('if_checked_users_will_be_subjected_to_recaptcha_verification_during_login_and_signup') ?></small>
                                                            <!-- /checkbox -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /row -->

                                                <!-- Form Group -->
                                                <div class="form-group mb-0">
                                                    <button type="button" class="btn btn-primary text-uppercase"
                                                        data-toggle="modal" data-target="#form-modalseoUpdate"><?php echo lang('save') ?></button>
                                                </div>
                                                <!-- /form group -->
                                                <div class="dt-card__body">

                                                    <!-- Modal -->
                                                    <div class="modal fade display-n" id="form-modalseoUpdate" tabindex="-1" role="dialog"
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

                                                                    <div class="form-group">
                                                                        <input class="form-control" name="password"
                                                                            id="password" type="password">
                                                                    </div>

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

                                    <!-- Tab panel -->
                                    <div id="tab-pane3" class="tab-pane">
                                        <!-- Grid -->
                                        <div class="row">
                                            <!-- Grid Item -->
                                            <div class="col-xl-12">
                                                <!-- Form -->
                                                <?php echo form_open(base_url( 'settings/referral' ) , array( 'id' => 'referralForm', 'class' => 'form' ));?>
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <!-- Form Group -->
                                                                    <div class="form-group">
                                                                    <label for="payouts"><?php echo lang('disable_referral_payouts_to_accounts_without_deposits') ?></label>
                                                                    <br>
                                                                        <!-- Radio Button -->
                                                                        <div class="custom-control custom-radio custom-control-inline">
                                                                            <input type="radio" id="refactive" name="refpayouts" value="1" class="custom-control-input" <?php echo $companyInfo['disableRefPayouts']==1 ? 'checked' : '' ?>>
                                                                            <label class="custom-control-label" for="refactive"><?php echo lang('yes') ?>
                                                                            </label>
                                                                        </div>
                                                                        <!-- /radio button -->

                                                                        <!-- Radio Button -->
                                                                        <div class="custom-control custom-radio custom-control-inline">
                                                                            <input type="radio" id="refinactive" name="refpayouts" value="0" class="custom-control-input" <?php echo $companyInfo['disableRefPayouts']==0 ? 'checked' : '' ?>>
                                                                            <label class="custom-control-label" for="refinactive"><?php echo lang('no') ?>
                                                                            </label>
                                                                        </div>
                                                                        <!-- /radio button -->

                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="referral1"><?php echo lang('referral_type') ?></label>
                                                                        <select class="form-control" name="refType"
                                                                            id="typeselector">
                                                                            <option selected><?php echo lang('choose_here') ?></option>
                                                                            <option value="simple"
                                                                                <?php echo $refType == 'simple' ? 'selected' : '' ?>>
                                                                                Simple</option>
                                                                            <option value="multiple"
                                                                                <?php echo $refType == 'multiple' ? 'selected' : '' ?>>
                                                                                Multiple</option>
                                                                        </select>
                                                                    </div>
                                                                    <!-- /form group -->
                                                                    <!-- Simple Form Group -->
                                                                    <div class="form-group <?php echo $refType == 'simple' ? 'display-b' : 'display-n' ?>" id="simple">
                                                                        <label for="referralInt"><?php echo lang('referral_interest') ?></label>
                                                                        <input class="form-control" name="simpleInt"
                                                                            placeholder="Enter interest"
                                                                            value="<?php echo $first ?>">
                                                                    </div>
                                                                    <!-- /siple form group -->
                                                                    <!-- Multiple Form Group -->
                                                                    <div class="field_wrapper <?php echo $refType == 'multiple' ? 'display-b' : 'display-n' ?>" id="multiple">
                                                                        <div class="form-group">
                                                                            <label for="level1">Level 1 Interest</label>
                                                                            <input class="form-control" type="text"
                                                                                name="multipleInt[]"
                                                                                value="<?php echo $first; ?>" />
                                                                            <a href="javascript:void(0);"
                                                                                class="add_button"
                                                                                data-value="<?php echo $intCount > 0 ? $intCount-1 : '2' ?>"
                                                                                title="Add field"><img
                                                                                    class="add-delete-icon"
                                                                                    src="<?php echo base_url(); ?>assets/dist/img/plus.svg" /></a>
                                                                        </div>
                                                                        <?php if($refType == 'multiple') {
                                                                            foreach($int_array as $index => $val) { 
                                                                                if ($index == 0) {
                                                                                    continue;
                                                                                }?>
                                                                        <div class="form-group">
                                                                            <label>Level
                                                                                <?php echo $index + 1 ?>
                                                                                Interest</label>
                                                                            <input class="form-control" type="text"
                                                                                name="multipleInt[]"
                                                                                value="<?php echo $val; ?>" />
                                                                            <a href="javascript:void(0);"
                                                                                class="remove_button">
                                                                                <img class="add-delete-icon"
                                                                                    src="<?php echo base_url(); ?>assets/dist/img/remove.svg" />
                                                                            </a>
                                                                        </div>
                                                                        <?php } } ?>
                                                                    </div>
                                                                    <!-- /multiple form group -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                    </div>

                                                    <!-- Form Group -->
                                                    <div class="form-group mb-0">
                                                        <button type="button" class="btn btn-primary text-uppercase"
                                                            data-toggle="modal" data-target="#form-modalreferralForm"><?php echo lang('save') ?></button>
                                                    </div>
                                                    <!-- /form group -->
                                                    <div class="dt-card__body">

                                                        <!-- Modal -->
                                                        <div class="modal fade display-n" id="form-modalreferralForm" tabindex="-1"
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

                                                                        <div class="form-group">
                                                                            <input class="form-control" name="password"
                                                                                id="password" type="password">
                                                                        </div>

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

                                    <!-- Tab panel -->
                                    <div id="tab-pane2" class="tab-pane">
                                        <div class="row">
                                            <!-- Grid Item -->
                                            <div class="col-xl-6">
                                                <!-- Form -->
                                                <?php echo form_open(base_url( 'settings/emailInfo' ) , array( 'id' => 'emailProfile', 'class' => 'form' ));?>
                                                    <!-- Row -->
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <!-- Form Group -->
                                                            <div class="form-group">
                                                                <label for="SMTPHost"><?php echo lang('smtp_host') ?></label>
                                                                <input type="text"
                                                                    value="<?php echo $this->security->xss_clean($companyInfo['SMTPHost']) ?>"
                                                                    name="SMTPHost" class="form-control" id="SMTPHost"
                                                                    aria-describedby="SMTPHost" placeholder="SMTP Host">
                                                            </div>
                                                            <!-- /form group -->
                                                        </div>
                                                        <div class="col-md-4">
                                                            <!-- Form Group -->
                                                            <div class="form-group">
                                                                <label for="SMTPPort"><?php echo lang('port') ?></label>
                                                                <input type="text"
                                                                    value="<?php echo $this->security->xss_clean($companyInfo['SMTPPort']) ?>"
                                                                    name="SMTPPort" class="form-control" id="SMTPPort"
                                                                    aria-describedby="SMTPPort" placeholder="SMTP Port">
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
                                                                <label for="SMTPProtocol"><?php echo lang('smtp_protocol') ?></label>
                                                                    <select class="form-control"
                                                                        name="SMTPProtocol" id="SMTPProtocol">
                                                                        <option value="" selected disabled hidden><?php echo lang('choose_here') ?></option>
                                                                        <option value="smtp" <?php echo $this->security->xss_clean($companyInfo['SMTPProtocol']) == 'smtp' ? 'selected' : ''; ?>>SMTP</option>
                                                                    </select>
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
                                                                <label for="SMTPEmail">SMTP User (Email)</label>
                                                                <input type="email"
                                                                    value="<?php echo $this->security->xss_clean($companyInfo['SMTPUser']) ?>"
                                                                    name="SMTPEmail" class="form-control" id="SMTPEmail"
                                                                    aria-describedby="SMTPEmail"
                                                                    placeholder="SMTP Email">
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
                                                                <label for="SMTPPass"><?php echo lang('password') ?></label>
                                                                <input type="password"
                                                                    value="<?php echo $this->security->xss_clean($companyInfo['SMTPPass']) ?>"
                                                                    class="form-control" name="SMTPPass" id="SMTPPass"
                                                                    aria-describedby="SMTPPass"
                                                                    placeholder="SMTP Password">
                                                            </div>
                                                            <!-- /form group -->
                                                        </div>
                                                    </div>
                                                    <!-- /row -->

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                            <label for="email_Active"><?php echo lang('activate_email') ?></label>
                                                            <br>
                                                                <!-- Radio Button -->
                                                                <div class="custom-control custom-radio custom-control-inline">
                                                                    <input type="radio" id="emailactive" name="emailactive" value="1" class="custom-control-input" <?php echo $companyInfo['email_active']==1 ? 'checked' : '' ?>>
                                                                    <label class="custom-control-label" for="emailactive"><?php echo lang('yes') ?>
                                                                    </label>
                                                                </div>
                                                                <!-- /radio button -->

                                                                <!-- Radio Button -->
                                                                <div class="custom-control custom-radio custom-control-inline">
                                                                    <input type="radio" id="emailinactive" name="emailactive" value="0" class="custom-control-input" <?php echo $companyInfo['email_active']==0 ? 'checked' : '' ?>>
                                                                    <label class="custom-control-label" for="emailinactive"><?php echo lang('no') ?>
                                                                    </label>
                                                                </div>
                                                                <!-- /radio button -->

                                                            </div>
                                                        </div>
                                                        <div class="col-md-3"></div>
                                                        <div class="col-md-5">
                                                            <!-- Form Group -->
                                                            <p class="testsms-email <?php echo $companyInfo['email_active']==0 ? 'hide' : '' ?>" id="testemail-sms" data-toggle="modal" data-target="#testEmail"><?php echo lang('send_test_email') ?></p>
                                                            <!-- /form group -->
                                                        </div>
                                                    </div>


                                                    <!-- Form Group -->
                                                    <div class="form-group mb-0">
                                                        <button type="button" class="btn btn-primary text-uppercase"
                                                            data-toggle="modal" data-target="#form-modalemailProfile"><?php echo lang('save') ?></button>
                                                    </div>
                                                    <!-- /form group -->
                                                    <div class="dt-card__body">

                                                        <!-- Modal -->
                                                        <div class="modal fade display-n" id="form-modalemailProfile" tabindex="-1"
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

                                                                        <div class="form-group">
                                                                            <input class="form-control" name="password"
                                                                                id="password" type="password">
                                                                        </div>

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
                                            <div class="col-xl-6">
                                            <?php echo form_open(base_url( 'settings/smsInfoupdate' ) , array( 'id' => 'SMSProfile', 'class' => 'form' ));?>
                                            <!-- Row -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <!-- Form Group -->
                                                    <div class="form-group">
                                                        <label for="SMSPhone"><?php echo lang('sms_phone') ?></label>
                                                        <input type="text"
                                                            value="<?php echo $this->security->xss_clean($companyInfo['sms_phone']) ?>"
                                                            name="sms_phone" class="form-control" id="sms_phone"
                                                            aria-describedby="sms_phone"
                                                            placeholder="+1222222222">
                                                    </div>
                                                    <!-- /form group -->
                                                </div>
                                            </div>
                                            <!-- /row -->
                                            <div class="row">
                                                <div class="col-md-4">
                                                <!-- Form Group -->
                                                <div class="form-group">
                                                <label for="sms_Active"><?php echo lang('activate_sms') ?></label>
                                                <br>
                                                    <!-- Radio Button -->
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="smsactive" name="smsactive" value="1" class="custom-control-input" <?php echo $companyInfo['sms_active']==1 ? 'checked' : '' ?>>
                                                        <label class="custom-control-label" for="smsactive"><?php echo lang('yes') ?>
                                                        </label>
                                                    </div>
                                                    <!-- /radio button -->

                                                    <!-- Radio Button -->
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="smsinactive" name="smsactive" value="0" class="custom-control-input" <?php echo $companyInfo['sms_active']==0 ? 'checked' : '' ?>>
                                                        <label class="custom-control-label" for="smsinactive"><?php echo lang('no') ?>
                                                        </label>
                                                    </div>
                                                    <!-- /radio button -->

                                                </div>
                                                <!-- /form group -->
                                                </div>
                                                <div class="col-md-3"></div>
                                                    <div class="col-md-5">
                                                    <!-- Form Group -->
                                                    <p class="testsms-email <?php echo $companyInfo['sms_active']==0 ? 'hide' : '' ?>" id="testsms-email" data-toggle="modal" data-target="#testSMSEmail"><?php echo lang('send_test_sms') ?></p>
                                                    <!-- /form group -->
                                                    </div>
                                                </div>
                                                <!-- Form Group -->
                                                <div class="form-group mb-0">
                                                    <button type="button" class="btn btn-primary text-uppercase"
                                                        data-toggle="modal" data-target="#form-modalSMS"><?php echo lang('save') ?></button>
                                                </div>
                                                <!-- /form group -->

                                                <div class="dt-card__body">

                                                    <!-- Modal -->
                                                    <div class="modal fade display-n" id="form-modalSMS" tabindex="-1"
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

                                                                    <div class="form-group">
                                                                        <input class="form-control" name="password"
                                                                            id="password" type="password">
                                                                    </div>

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
                                            <!-- Modal -->
                                            <?php echo form_open(base_url( 'settings/testSMS' ) , array( 'id' => 'testSend', 'class' => 'form' ));?>
                                            <div class="modal fade display-n" id="testSMSEmail" tabindex="-1"
                                                        role="dialog" aria-labelledby="model-8" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered"
                                                            role="document">

                                                            <!-- Modal Content -->
                                                            <div class="modal-content">

                                                                <!-- Modal Header -->
                                                                <div class="modal-header">
                                                                    <h3 class="modal-title" id="model-8"><?php echo lang('enter_phone_number') ?></h3>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <!-- /modal header -->

                                                                <!-- Modal Body -->
                                                                <div class="modal-body">

                                                                    <div class="form-group">
                                                                        <input class="form-control width-phone" name="phone1"
                                                                            id="phonenumber" type="text">
                                                                    </div>

                                                                </div>
                                                                <!-- /modal body -->

                                                                <!-- Modal Footer -->
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                        class="btn btn-secondary btn-sm"
                                                                        data-dismiss="modal"><?php echo lang('cancel') ?>
                                                                    </button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary btn-sm"><?php echo lang('send_test_sms') ?>
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
                                            <?php echo form_open(base_url( 'settings/testEmail' ) , array( 'id' => 'testSendEmail', 'class' => 'form' ));?>
                                            <div class="modal fade display-n" id="testEmail" tabindex="-1"
                                                        role="dialog" aria-labelledby="model-8" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered"
                                                    role="document">

                                                    <!-- Modal Content -->
                                                    <div class="modal-content">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h3 class="modal-title" id="model-8"><?php echo lang('enter_email') ?></h3>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <!-- /modal header -->

                                                        <!-- Modal Body -->
                                                        <div class="modal-body">

                                                            <div class="form-group">
                                                                <input class="form-control" name="email"
                                                                    id="email_address" type="text">
                                                            </div>

                                                        </div>
                                                        <!-- /modal body -->

                                                        <!-- Modal Footer -->
                                                        <div class="modal-footer">
                                                            <button type="button"
                                                                class="btn btn-secondary btn-sm"
                                                                data-dismiss="modal">Cancel
                                                            </button>
                                                            <button type="submit"
                                                                class="btn btn-primary btn-sm"><?php echo lang('send_test_email') ?>
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
                                        </div>
                                        <!-- /grid -->
                                    </div>
                                    <!-- /tab panel -->

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

    <!-- /site content -->
    <script src="<?php echo base_url('/assets/dist/js/bootstrap-formhelpers.min.js') ?>"></script>
    <script src="<?php echo base_url('/assets/dist/js/settings.js') ?>"></script>
    <script src="<?php echo base_url('/assets/dist/js/intlTelInput.js') ?>"></script>
    <script src="<?php echo base_url('/assets/dist/js/utils.js') ?>"></script>
