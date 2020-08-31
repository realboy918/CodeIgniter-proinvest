<?php
$ppic = $userInfo->ppic == '' ? base_url('assets/dist/img/avatar.png') : $ppic;
$userId = $this->security->xss_clean($userInfo->userId);
$fname = set_value('fname') == false ? $this->security->xss_clean($userInfo->firstName) : set_value('fname');
$lname = set_value('lname') == false ? $this->security->xss_clean($userInfo->lastName) : set_value('lname');
$email = set_value('email') == false ? $this->security->xss_clean($userInfo->email) : set_value('email');
$mobile = set_value('mobile') == false ? $this->security->xss_clean($userInfo->mobile) : set_value('mobile');
$deactivate = set_value('deactivate') == false ? $this->security->xss_clean($userInfo->isActive) : set_value('deactivate');
$roleId = $this->security->xss_clean($userInfo->roleId);
?>


<!-- Site Content Wrapper -->
<div class="dt-content-wrapper">

    <!-- Site Content -->
    <div class="dt-content">
        <!-- Profile -->
        <div class="profile">

            <!-- Profile Banner -->
            <div class="profile__banner">
            <div class="profile__banner-detail">
                    <!-- Avatar Wrapper -->
                    <div class="dt-avatar-wrapper">
                        <!-- Avatar -->
                        <img class="dt-avatar dt-avatar__shadow size-90 mr-sm-4" src="<?php echo $ppic; ?>" alt="<?php echo $fname.' '.$lname ?>">
                        <!-- /avatar -->

                        <!-- Info -->
                        <div class="dt-avatar-info">
                            <span class="dt-avatar-name display-4 mb-2 font-weight-light"><?php echo $fname.' '.$lname ?></span>
                            <span class="f-16"><?php echo $email ?></span>
                        </div>
                        <!-- /info -->
                    </div>
                    <!-- /avatar wrapper -->

                    <div class="ml-sm-auto">
                        <!-- List -->
                        <div class="col-sm-12">
                        <a href="javascript:history.back()" class="btn btn-light btn-sm display-i ft-right"><?php echo lang('back') ?></a>
                        </div>
                        <!-- /list -->
                    </div>
                </div>
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
                                <form role="form" method="post" id="editUser" role="form">
                                    <?php $csrf = array(
                                        'name' => $this->security->get_csrf_token_name(),
                                        'hash' => $this->security->get_csrf_hash()
                                ); ?>
                                    <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
                                    <!-- Row -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <!-- Form Group -->
                                                    <div class="form-group">
                                                        <label for="fname"><?php echo lang('first_name') ?></label>
                                                        <input type="text" name="fname"
                                                            class="form-control <?php echo form_error('fname') == TRUE ? 'inputTxtError' : ''; ?>"
                                                            id="fname" aria-describedby="fname"
                                                            placeholder="<?php echo lang('first_name') ?>" value="<?php echo $fname ?>">
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
                                                            id="lname" aria-describedby="lname"
                                                            placeholder="<?php echo lang('last_name') ?>" value="<?php echo $lname ?>">
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
                                                    placeholder="<?php echo lang('email') ?>" value="<?php echo $email ?>">
                                                <label class="error"
                                                    for="fname"><?php echo form_error('email'); ?></label>
                                            </div>
                                            <!-- /form group -->
                                            <div class="form-row mb-4">
                                            <label class="col-md-4 col-sm-3 text-sm-left mb-4 mb-sm-0"><?php echo lang('deactivate_account') ?></label>
                                            <div class="col-md-8 col-sm-9">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" id="customcheckboxInline21" name="deactivate" class="custom-control-input" value="1" <?php echo $deactivate == 1 ? 'checked=true' : '' ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline21"></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            </div>
                                            <!-- /row -->
                                            <!-- Form Group -->
                                            <div class="form-group mb-0">
                                                <button type="submit"
                                                    class="btn btn-primary text-uppercase"><?php echo lang('save') ?></button>
                                            </div>
                                            <!-- /form group -->
                                        </div>
                                        <div class="col-md-6">
                                        <?php if($pageTitle == 'Edit Manager') {?>
                                            <div class="form-row display-4"><?php echo lang('permissions') ?></div>
                                            <div class="row">
                                            <div class="col-md-6">
                                            <h6 class="form-row display-5"><?php echo lang('deposits') ?></h6>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="deposits|view" id="customcheckboxInline1" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'deposits|view', $this->user_model->getPermissions('deposits', 'view', $userId)) ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline1"><?php echo lang('view') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="deposits|add" id="customcheckboxInline2" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'deposits|add', $this->user_model->getPermissions('deposits', 'add', $userId)) ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline2"><?php echo lang('add') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="deposits|edit" id="customcheckboxInline3" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'deposits|edit', $this->user_model->getPermissions('deposits', 'edit', $userId)) ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline3"><?php echo lang('edit') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="deposits|delete" id="customcheckboxInline4" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'deposits|delete', $this->user_model->getPermissions('deposits', 'delete', $userId)) ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline4"><?php echo lang('delete') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <h6 class="form-row display-5"><?php echo lang('withdrawals') ?></h6>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="withdrawals|view" id="customcheckboxInline5" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'withdrawals|view', $this->user_model->getPermissions('withdrawals', 'view', $userId)) ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline5"><?php echo lang('view') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="withdrawals|approve" id="customcheckboxInline6" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'withdrawals|approve', $this->user_model->getPermissions('withdrawals', 'approve', $userId)) ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline6"><?php echo lang('approve') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <h6 class="form-row display-5"><?php echo lang('settings') ?></h6>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="settings|email_templates" id="customcheckboxInline7" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'settings|email_templates', $this->user_model->getPermissions('settings', 'email_templates', $userId)) ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline7"><?php echo lang('email_templates') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="settings|general_settings" id="customcheckboxInline8" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'settings|general_settings', $this->user_model->getPermissions('settings', 'general_settings', $userId)) ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline8"><?php echo lang('general_settings') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="settings|API_settings" id="customcheckboxInline9" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'settings|API_settings', $this->user_model->getPermissions('settings', 'API_settings', $userId)) ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline9"><?php echo lang('plugins') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="settings|payment_methods" id="customcheckboxInline10" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'settings|payment_methods', $this->user_model->getPermissions('settings', 'payment_methods', $userId)) ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline10"><?php echo lang('payment_methods') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <h6 class="form-row display-5"><?php echo lang('login_history') ?></h6>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="loginHistory|view" id="customcheckboxInline22" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'loginHistory|view', $this->user_model->getPermissions('loginHistory', 'view', $userId)) ?>>
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
                                                    <input type="checkbox" value="clients|view" id="customcheckboxInline11" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'clients|view', $this->user_model->getPermissions('clients', 'view', $userId)) ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline11"><?php echo lang('view') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="clients|add" id="customcheckboxInline12" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'clients|add', $this->user_model->getPermissions('clients', 'add', $userId)) ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline12"><?php echo lang('add') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="clients|edit" id="customcheckboxInline13" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'clients|edit', $this->user_model->getPermissions('clients', 'edit', $userId)) ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline13"><?php echo lang('edit') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <h6 class="form-row display-5"><?php echo lang('team') ?></h6>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="teams|view" id="customcheckboxInline14" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'teams|view', $this->user_model->getPermissions('teams', 'view', $userId)) ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline14"><?php echo lang('view') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="teams|add" id="customcheckboxInline15" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'teams|add', $this->user_model->getPermissions('teams', 'add', $userId)) ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline15"><?php echo lang('add') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="teams|edit" id="customcheckboxInline16" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'teams|edit', $this->user_model->getPermissions('teams', 'edit', $userId)) ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline16"><?php echo lang('edit') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <h6 class="form-row display-5"><?php echo lang('investment_plans') ?></h6>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="plans|view" id="customcheckboxInline17" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'plans|view', $this->user_model->getPermissions('plans', 'view', $userId)) ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline17"><?php echo lang('view') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="plans|add" id="customcheckboxInline18" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'plans|add', $this->user_model->getPermissions('plans', 'add', $userId)) ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline18"><?php echo lang('add') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="plans|edit" id="customcheckboxInline19" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'plans|edit', $this->user_model->getPermissions('plans', 'edit', $userId)) ?>>
                                                    <label class="custom-control-label" for="customcheckboxInline19"><?php echo lang('edit') ?></label>
                                                </div>
                                                <!-- /checkbox -->
                                            </div>
                                            <h6 class="form-row display-5"><?php echo lang('payouts') ?></h6>
                                            <div class="form-row mb-2">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="payouts|view" id="customcheckboxInline20" name="permissions[]" class="custom-control-input" <?php echo set_checkbox('permissions[]', 'payouts|view', $this->user_model->getPermissions('payouts', 'view', $userId)) ?>>
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

    <script src='<?php echo base_url('/assets/dist/functions.js') ?>'></script>