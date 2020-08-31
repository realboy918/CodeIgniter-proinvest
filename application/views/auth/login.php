        <!-- Login Container -->
        <div class="dt-login--container">

            <!-- Login Content -->
            <div class="dt-login__content-wrapper">

                <!-- Login Background Section -->
                <div class="dt-login__bg-section">

                    <div class="dt-login__bg-content">
                        <!-- Login Title -->
                        <h1 class="dt-login__title text-capitalize"><?php echo lang("login") ?></h1>
                        <!-- /login title -->

                        <p class="f-16 text-capitalize"><?php echo lang("sign_in_and_explore") ?> <?php echo $this->security->xss_clean($this->siteTitle) ?>.</p>
                    </div>


                    <!-- Brand logo -->
                    <div class="dt-login__logo">
                        <a class="dt-brand__logo-link" href="<?php echo base_url() ?>">
                            <img class="dt-brand__logo-img" src="<?php echo $this->security->xss_clean($this->logoWhite) ?>" alt="logo">
                        </a>
                    </div>
                    <!-- /brand logo -->

                </div>
                <!-- /login background section -->

                <!-- Login Content Section -->
                <div class="dt-login__content">
                    <ul style="float: right;background-color: #f4f4f4;padding: 5px;">
                        <li class="dt-nav__item dropdown">

                            <!-- Dropdown Link -->
                            <a href="#" class="dt-nav__link dropdown-toggle" id="currentLang" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img style="width:20px;" class="flag-icon flag-icon-rounded flag-icon-lg mr-1m" src="<?php echo base_url('uploads/'.$this->site_lang->logo) ?>">
                            <span><?php echo $this->site_lang->code ?></span> </a>
                            <!-- /dropdown link -->

                            <!-- Dropdown Option -->
                            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(8px, 72px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <?php foreach($this->site_languages as $language) {?>
                                <button class="dropdown-item sitelangChange" type="button" data-id="<?php echo base_url('switchlang/').$language->name ?>">
                                    <img class="flag-icon flag-icon-rounded flag-icon-lg mr-2" style="width: 20px;" src="<?php echo base_url('uploads/').$language->logo ?>">
                                    <span><?php echo $language->name ?></span> 
                                </button>
                                <?php }?>
                            </div>
                            <!-- /dropdown option -->

                        </li>
                    </ul>

                    <!-- Login Content Inner -->
                    <div class="dt-login__content-inner">
                        <?php
                        $this->load->helper('form');
                        $error = $this->session->flashdata('error');
                        if($error)
                        { ?>
                        <div class="alert border-0 alert-primary bg-gradient m-b-30 alert-dismissible fade show border-radius-none"
                            role="alert">
                            <?php echo $this->session->flashdata('error'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <?php } ?>
                        <?php  
                      $success = $this->session->flashdata('success');
                      if($success)
                      {
                  ?>
                        <div class="alert border-0 alert-primary bg-gradient m-b-30 alert-dismissible fade show border-radius-none"
                            role="alert">
                            <?php echo $this->session->flashdata('success'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <?php } ?>
                        <?php echo validation_errors('<div class="alert border-0 alert-primary bg-gradient m-b-30 alert-dismissible fade show border-radius-none" role="alert">', ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>'); ?>
                        <h2 id="login-title" class="f-20"><?php echo lang("enter_email_and_password_below") ?></h2>
                        <!-- Form -->
                        <?php echo form_open(base_url( 'login' ), array( 'id' => 'loginForm' ));?>
                        <div class="email-pass">
                            <div class="errorClass">
                                <label class="error" id="overallError"></label>
                            </div>
                            <!-- Form Group -->
                            <div class="form-group">
                                <label class="sr-only" for="email-1"><?php echo lang("email_address") ?></label>
                                <input type="email" class="form-control" name="email" id="email-1"
                                    aria-describedby="email-1" placeholder="<?php echo lang("email") ?>" value="<?=set_value('email')?>">
                            </div>
                            <!-- /form group -->

                            <!-- Form Group -->
                            <div class="form-group">
                                <label class="sr-only" for="password-1"><?php echo lang("password") ?></label>
                                <input type="password" class="form-control" name="password" id="password-1"
                                    placeholder="<?php echo lang("password") ?>" value="<?=set_value('password')?>">
                            </div>
                            <!-- /form group -->

                            <!-- Form Group -->
                            <div class="mb-2">
                                <checkbox class="dt-checkbox dt-checkbox-icon dt-checkbox-only">
                                    <input type="checkbox" name="stay_logged_in" id="checkbox-1" value="agree" class="checkbox-check ng-pristine ng-valid ng-touched">
                                    <label class="font-weight-light dt-checkbox-content" for="checkbox-1">
                                        <span class="unchecked">
                                            <i name="box-o" size="xl" class="icon icon-box-o icon-xl icon-fw"></i>
                                        </span>
                                        <span class="checked ng-star-inserted">
                                            <i name="box-check-o" size="xl" class="text-primary icon icon-box-check-o icon-xl icon-fw"></i>
                                        </span>
                                    </label>
                                    <?php echo lang("keep_me_logged_in") ?>
                                </checkbox>
                            </div>
                            <!-- /form group -->
                            
                            <!-- /form group -->
                            <?php if($companyInfo['google_recaptcha'] != 0) {?>
                                <?php if($companyInfo['recaptcha_version'] == 'v2') {?>
                                    <input type="hidden" name="g-recaptcha-response">
                                    <div class="g-recaptcha" style="margin-bottom: 15px;" data-sitekey="<?php echo $recaptchaInfo->public_key; ?>"></div>
                                <?php } else if($companyInfo['recaptcha_version'] == 'v3') {?>
                                    <input type="hidden" class="g-recaptcha" name="recaptcha_response" id="recaptchaResponse">
                                <?php }?>
                            <?php }?>
                        </div>
                        <div class="hide" id="google-auth">
                            <!-- Form Group -->
                            <div class="form-group">
                                <div id="divOuter" class="<?php echo $companyInfo['two_factor_auth'] == 'Authy' ? 'authydivOuter' : ''; ?>">
                                    <div id="divInner">
                                        <input id="partitioned" class="<?php echo $companyInfo['two_factor_auth'] == 'Authy' ? 'authypartitioned' : ''; ?>" name="token" type="text" maxlength="<?php echo $companyInfo['two_factor_auth'] == 'Authy' ? '7' : '6'; ?>" />
                                        <label class="error google-auth-err"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                        <!-- Form Group -->
                        <div class="form-group">
                            <button type="button" id="confirm-user-pass" class="btn btn-info text-uppercase" data-loading-text="<?php echo lang('processing_data') ?>" data-title="<?php echo lang("login") ?>"><?php echo lang("login") ?></button>
                            <button type="button" id="authenticate" class="hide btn btn-info text-uppercase"><?php echo lang("login") ?></button>
                            <span class="d-inline-block ml-4 text-uppercase"><?php echo lang("or") ?>
                                <a class="d-inline-block font-weight-500 ml-3 text-capitalize"
                                    href="<?php echo base_url("signup") ?>"><?php echo lang("create_account") ?></a>
                            </span>
                        </div>
                        <!-- /form group -->
                        <?php echo form_close();?>
                        <!-- /form -->

                    </div>
                    <!-- /login content inner -->

                    <!-- Login Content Footer -->
                    <div class="dt-login__content-footer">
                        <a href="<?php echo base_url('forgotPassword'); ?>"><?php echo lang("cant_access_your_account") ?></a>
                    </div>
                    <!-- /login content footer -->

                </div>
                <!-- /login content section -->

            </div>
            <!-- /login content -->

        </div>
        <!-- /login container -->
        <script src="<?php echo base_url('/assets/dist/js/login.js') ?>"></script>