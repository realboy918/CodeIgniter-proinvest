<!-- Site Content Wrapper -->
<div class="dt-content-wrapper">
    <!-- Site Content -->
    <div class="dt-content">
        <!-- Profile -->
        <div class="profile">
            <!-- Profile Banner -->
            <div class="profile__banner">
                <!-- Page Header -->
                <div class="dt-page__header">
                    <h1 class="dt-page__title text-white display-i text-capitalize"><?php echo lang('email_templates') ?></h1>
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
                    <div class="col-xl-12">

                        <!-- Module -->
                        <div class="dt-module">

                            <!-- Module Sidebar -->
                            <div class="dt-module__sidebar">

                                <!-- Sidebar Header -->
                                <div class="dt-module__sidebar-header border-bottom">
                                    <div class="d-none d-md-flex align-items-center">
                                        <i class="icon icon-open-mail icon-1x mr-3 text-dark"></i>
                                        <span class="h3 mb-0 text-capitalize"><?php echo lang('templates') ?></span>
                                    </div>

                                </div>
                                <!-- /sidebar header -->

                                <!-- Sidebar Menu -->
                                <div class="dt-module__sidebar-content ps-custom-scrollbar ps">
                                    <!-- Contacts -->
                                    <div class="dt-contacts contacts-list">

                                        <?php
                                            if(!empty($emailTemplates))
                                            {
                                                foreach($emailTemplates as $email)
                                                {
                                            ?>
                                        <!-- Contact -->
                                        <?php echo form_open( base_url( 'emailTemplate' ) , array( 'class' => 'email-template-info', 'id' => 'email-temp'.$emailID == $email->id ));?>
                                        <input name="id" value="<?php echo $email->id ?>" hidden/>
                                        <button type="submit" class="dt-contact bg-white w-100 text-left border-n <?php echo $emailID == $email->id ? 'dt-contact-active' : '' ; ?>" id="template<?php echo $email->id ?>" value="<?php echo $email->id ?>">
                                            <!-- Contact Info -->
                                            <div class="dt-contact__info">
                                                <h4 class="dt-module-side-nav__text mt-1m"><?php echo $email->type ?></h4>
                                            </div>
                                            <!-- /contact info -->
                                        </button>
                                        <?php echo form_close();?>
                                        <!-- /contact -->
                                        <?php
                                                }
                                            }
                                            ?>
                                    </div>
                                    <!-- contacts -->
                                </div>
                                <!-- /sidebar Menu -->

                            </div>
                            <!-- /module sidebar -->

                            <!-- Module Container -->
                            <div class="dt-module__container">
                                <div class="loader mt-20 hide" id="loader"></div>
                                <?php echo form_open( base_url( 'settings/edit_email' ) , array( 'id' => 'emaileditform' ));?>
                                <input id="email_id" name="email_id" value="<?php echo $emailID; ?>" class="hide"/>
                                <!-- Module Header -->
                                <div class="dt-module__header d-none d-md-flex">
                                    <span for="h3 mb-0 mr-1m font-1-2m text-capitalize"><?php echo lang('subject') ?>:</span>
                                        <input type="text" class="form-control form-control-sm" name="email_subject"
                                            id="normal-input-3" placeholder="Email Subject" value="<?php echo $emailSubject; ?>">
                                    <!-- /search box -->

                                </div>
                                <!-- /module header -->

                                <!-- Module Content -->
                                <div class="dt-module__content ps-custom-scrollbar ps">

                                    <!-- Module Content Inner -->
                                    <div class="">
                                        <!-- Card Body -->
                                        <div class="dt-card__body">

                                            <textarea id="summernote" name="email_body"><?php echo $emailBody; ?></textarea>
                                            <button type="submit"
                                                class="btn btn-info text-uppercase w-100" id="edit_email"><?php echo lang('save') ?></button>
                                        </div>
                                        <!-- /card body -->
                                    </div>
                                    <!-- /module content inner -->
                                </div>
                                <!-- /module content -->
                                <?php echo form_close();?>

                            </div>
                            <!-- /module container -->
                        </div>
                        <!-- /module -->

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
<script src="<?php echo base_url('/assets/dist/js/emails.js') ?>"></script>