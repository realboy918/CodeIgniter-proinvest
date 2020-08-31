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
                    <h1 class="dt-page__title text-white display-i"><?php echo $breadcrumbs; ?></h1>
                    <?php if($pageTitle == 'Add New Client') { ?>
                    <a href="<?php echo base_url(); ?>clients" class="btn btn-light btn-sm display-i ft-right"><?php echo lang('view_all_clients') ?></a>
                    <?php } else if ($pageTitle == 'Add New User') { ?>
                    <a href="<?php echo base_url(); ?>team" class="btn btn-light btn-sm display-i ft-right"><?php echo lang('view_team') ?></a>
                    <?php } ?>
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
                    <div class="col-xl-12 col-md-12 col-12 order-xl-1">
                        <!-- Card -->
                        <div class="dt-card">

                            <!-- Card Body -->
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
                                <!-- Form -->
                                <!-- Form -->
                                <form role="form" id="" method="post" role="form">
                                    <?php $csrf = array(
                                        'name' => $this->security->get_csrf_token_name(),
                                        'hash' => $this->security->get_csrf_hash()
                                ); ?>
                                    <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- Row -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <!-- Form Group -->
                                                    <div class="form-group">
                                                        <label for="fname"><?php echo lang('first_name') ?></label>
                                                        <input type="text" name="fname"
                                                            class="form-control <?php echo form_error('fname') == TRUE ? 'inputTxtError' : ''; ?>"
                                                            id="fname" aria-describedby="fname" placeholder="<?php echo lang('first_name') ?>"
                                                            value="<?php echo set_value('fname') ?>">
                                                        <label class="error"
                                                            for="fname"><?php echo form_error('fname'); ?></label>
                                                    </div>
                                                    <!-- /form group -->
                                                </div>
                                                <div class="col-md-6">
                                                    <!-- Form Group -->
                                                    <div class="form-group">
                                                        <label for="lname"><?php echo lang('last_name') ?></label>
                                                        <input type="text" name="lname"
                                                            class="form-control <?php echo form_error('lname') == TRUE ? 'inputTxtError' : ''; ?>"
                                                            id="lname" aria-describedby="lname" placeholder="<?php echo lang('last_name') ?>"
                                                            value="<?php echo set_value('lname') ?>">
                                                        <label class="error"
                                                            for="fname"><?php echo form_error('lname'); ?></label>
                                                    </div>
                                                    <!-- /form group -->
                                                </div>
                                            </div>
                                            <!-- /row -->
                                            <!-- Form Group -->
                                            <div class="form-group">
                                                <label for="email"><?php echo lang('email') ?></label>
                                                <input type="email"
                                                    class="form-control <?php echo form_error('email') == TRUE ? 'inputTxtError' : ''; ?>"
                                                    name="email" id="email" aria-describedby="email"
                                                    placeholder="<?php echo lang('email') ?>" value="<?php echo set_value('email') ?>">
                                                <label class="error"
                                                    for="fname"><?php echo form_error('email'); ?></label>
                                            </div>
                                            <!-- /form group -->
                                            <?php if($pageTitle == 'Add New Manager') {?>
                                            <!-- Form Group -->
                                            <div class="form-group">
                                                <label for="phone"><?php echo lang('phone') ?></label>
                                                <input type="text"
                                                    class="form-control <?php echo form_error('phone') == TRUE ? 'inputTxtError' : ''; ?>"
                                                    name="phone1" id="phone" aria-describedby="phone"
                                                    placeholder="<?php echo lang('phone') ?>" value="<?php echo set_value('phone') ?>">
                                                <label class="error"
                                                    for="phone"><?php echo form_error('phone'); ?></label>
                                            </div>
                                            <!-- /form group -->
                                            <?php }?>
                                            <small id="emailHelp1" class="form-text mb-2m"><?php echo lang('an_email_will_be_sent_with_instructions_on_how_user_can_login') ?></small>
                                            <!-- Form Group -->
                                            <div class="form-group mb-0">
                                                <button type="submit" class="btn btn-primary text-uppercase"><?php echo lang('save') ?></button>
                                            </div>
                                            <!-- /form group -->
                                        </div>
                                        <div class="col-md-6">
                                        <?php if($pageTitle == 'Add New Manager') {?>
                                            <div class="form-row display-4"><?php echo lang('permissions') ?></div>
                                            <div class="row">
                                            <div class="col-md-6">
                                            <h6 class="form-row display-5"><?php echo lang('deposits') ?></h6>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="deposits|view" id="customcheckboxInline1" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'deposits|view', true); ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline1"><?php echo lang('view') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="deposits|add" id="customcheckboxInline2" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'deposits|add', true); ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline2"><?php echo lang('add') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="deposits|edit" id="customcheckboxInline3" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'deposits|edit', true); ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline3"><?php echo lang('edit') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="deposits|delete" id="customcheckboxInline4" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'deposits|delete', true); ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline4"><?php echo lang('delete') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <h6 class="form-row display-5"><?php echo lang('withdrawals') ?></h6>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="withdrawals|view" id="customcheckboxInline5" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'withdrawals|view', true); ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline5"><?php echo lang('view') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="withdrawals|approve" id="customcheckboxInline6" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'withdrawals|approve', true); ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline6"><?php echo lang('approve') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <h6 class="form-row display-5"><?php echo lang('settings') ?></h6>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="settings|email_templates" id="customcheckboxInline7" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'settings|email_templates', true); ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline7"><?php echo lang('email_templates') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="settings|general_settings" id="customcheckboxInline8" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'settings|general_settings', true); ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline8"><?php echo lang('general_settings') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="settings|API_settings" id="customcheckboxInline9" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'settings|API_settings', true); ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline9"><?php echo lang('plugins') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="settings|payment_methods" id="customcheckboxInline10" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'settings|payment_methods', true); ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline10"><?php echo lang('payment_methods') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <h6 class="form-row display-5"><?php echo lang('login_history') ?></h6>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="loginHistory|view" id="customcheckboxInline22" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'loginHistory|view', true); ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline22"><?php echo lang('view') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            </div>
                                            <div class="col-md-6">
                                            <h6 class="form-row display-5"><?php echo lang('clients') ?></h6>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="clients|view" id="customcheckboxInline11" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'clients|view', true); ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline11"><?php echo lang('view') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="clients|add" id="customcheckboxInline12" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'clients|add', true); ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline12"><?php echo lang('add') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="clients|edit" id="customcheckboxInline13" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'clients|edit', true); ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline13"><?php echo lang('edit') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <h6 class="form-row display-5"><?php echo lang('team') ?></h6>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="teams|view" id="customcheckboxInline14" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'teams|view', true); ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline14"><?php echo lang('view') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="teams|add" id="customcheckboxInline15" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'teams|add', true); ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline15"><?php echo lang('add') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="teams|edit" id="customcheckboxInline16" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'teams|edit', true); ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline16"><?php echo lang('edit') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <h6 class="form-row display-5"><?php echo lang('investment_plans') ?></h6>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="plans|view" id="customcheckboxInline17" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'plans|view', true); ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline17"><?php echo lang('view') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="plans|add" id="customcheckboxInline18" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'plans|add', true); ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline18"><?php echo lang('add') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="plans|edit" id="customcheckboxInline19" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'plans|edit', true); ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline19"><?php echo lang('edit') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <h6 class="form-row display-5"><?php echo lang('payouts') ?></h6>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="payouts|view" id="customcheckboxInline20" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'payouts|view', true); ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline20"><?php echo lang('view') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            </div>
                                            </div>
                                        <?php }?>
                                        </div>
                                    </div>
                                </form>
                                <!-- /form -->
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
    </div>

    <!-- /site content -->
    <script src="<?php echo base_url('/assets/dist/js/functions.js') ?>"></script>
    <script src="<?php echo base_url('/assets/dist/js/intlTelInput.js') ?>"></script>
    <script src="<?php echo base_url('/assets/dist/js/utils.js') ?>"></script>
    <script>
        $(document).ready(function () {
            var input = document.querySelector("#phone");
            window.intlTelInput(input,
            {
                separateDialCode: true,
                hiddenInput: "phone"
            });
            $("form").submit(function() {
            });
        });
    </script>