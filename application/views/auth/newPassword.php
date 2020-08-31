        <!-- Login Container -->
        <div class="dt-login--container">

            <!-- Login Content -->
            <div class="dt-login__content-wrapper">

                <!-- Login Background Section -->
                <div class="dt-login__bg-section">

                    <div class="dt-login__bg-content">
                        <!-- Login Title -->
                        <h1 class="dt-login__title"><?php echo lang('change_password') ?></h1>
                        <!-- /login title -->

                        <p class="f-16"><?php echo lang('change_your_password') ?></p>
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
                        <h2 class="f-20"><?php echo lang('enter_your_new_password') ?></h2>
                        <!-- Form -->
                        <?php echo form_open(base_url( 'createPasswordUser' ));?>
                            <input type="email" class="form-control" placeholder="<?php echo lang('email') ?>" name="email"
                                value="<?php echo $email; ?>" readonly required hidden />
                            <input type="text" class="form-control" placeholder="Email" name="activation_code"
                                value="<?php echo $activation_code; ?>" readonly required hidden />
                            <!-- Form Group -->
                            <div class="form-group">
                                <label class="sr-only" for="password-1"><?php echo lang('new_password') ?></label>
                                <input type="password" class="form-control" name="password" id="password-1"
                                    aria-describedby="password-1" placeholder="<?php echo lang('new_password') ?>">
                            </div>
                            <!-- /form group -->

                            <!-- Form Group -->
                            <div class="form-group">
                                <label class="sr-only" for="password-2"><?php echo lang('confirm_password') ?></label>
                                <input type="password" class="form-control" name="cpassword" id="password-2"
                                    placeholder="<?php echo lang('confirm_password') ?>">
                            </div>
                            <!-- /form group -->

                            <!-- Form Group -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary text-uppercase"><?php echo lang('change_password') ?></button>
                            </div>
                            <!-- /form group -->

                        <?php echo form_close();?>
                        <!-- /form -->

                    </div>
                    <!-- /login content inner -->
                </div>
                <!-- /login content section -->

            </div>
            <!-- /login content -->

        </div>
        <!-- /login container -->