        <!-- Login Container -->
        <div class="dt-login--container">

            <!-- Login Content -->
            <div class="dt-login__content-wrapper">

                <!-- Login Background Section -->
                <div class="dt-login__bg-section">

                    <div class="dt-login__bg-content">
                        <!-- Login Title -->
                        <h1 class="dt-login__title text-capitalize"><?php echo lang('forgot_password') ?></h1>
                        <!-- /login title -->

                        <p class="f-16 text-capitalize"><?php echo lang('please_enter_email_to_reset_password') ?></p>
                    </div>


                    <!-- Brand logo -->
                    <div class="dt-login__logo">
                        <a class="dt-brand__logo-link" href="<?php echo base_url() ?>">
                            <img class="dt-brand__logo-img" src="<?php echo $this->security->xss_clean($this->logoWhite) ?>" alt="Logo">
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
                        <h2 class="f-20 text-capitalize"><?php echo lang('forgot_pass_title') ?></h2>
                        <!-- Form -->
                        <?php echo form_open(base_url( 'resetPasswordUser' ), array( 'id' => 'loginForm' ));?>

                            <!-- Form Group -->
                            <div class="form-group">
                                <label class="sr-only" for="email-1"><?php echo lang('email_address') ?></label>
                                <input type="email" class="form-control" name="login_email" id="email-1"
                                    aria-describedby="email-1" placeholder="<?php echo lang('email') ?>" value="<?=set_value('email')?>">
                            </div>
                            <!-- /form group -->

                            <!-- Form Group -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary text-uppercase"><?php echo lang('continue') ?></button>
                                <span class="d-inline-block ml-4 text-uppercase"><?php echo lang('or') ?>
                                    <a class="d-inline-block font-weight-500 ml-3 text-uppercase"
                                        href="<?php echo base_url("signup") ?>"><?php echo lang('create_account') ?></a>
                                </span>
                            </div>
                            <!-- /form group -->
                        <?php echo form_close();?>
                        <!-- /form -->

                    </div>
                    <!-- /login content inner -->

                    <!-- Login Content Footer -->
                    <div class="dt-login__content-footer">
                        <a href="<?php echo base_url('login'); ?>"><?php echo lang('remember_pass') ?></a>
                    </div>
                    <!-- /login content footer -->

                </div>
                <!-- /login content section -->

            </div>
            <!-- /login content -->

        </div>
        <!-- /login container -->