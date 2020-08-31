<?php
$id = $this->security->xss_clean($planInfo->id);
$name = $this->security->xss_clean($planInfo->name);
$minInv = $this->security->xss_clean($planInfo->minInvestment);
$maxInv = $this->security->xss_clean($planInfo->maxInvestment);
$int = $this->security->xss_clean($planInfo->intAfterMaturity);
$principalReturn = $this->security->xss_clean($planInfo->principalReturn);
$profit = $this->security->xss_clean($planInfo->profit);
$period = $this->security->xss_clean($planInfo->period);
?>
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
                    <h1 class="dt-page__title text-white display-i"><?php echo lang('edit_plan') ?></h1>
                    <button class="btn btn-light btn-sm display-i ft-right"
                        onclick="history.back();"><?php echo lang('back') ?></button>
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
                                <form role="form" id="addPlan" method="post" role="form">
                                    <?php $csrf = array(
                                        'name' => $this->security->get_csrf_token_name(),
                                        'hash' => $this->security->get_csrf_hash()
                                ); ?>
                                    <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- Row -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <!-- Form Group -->
                                                    <div class="form-group">
                                                        <label for="pname"><?php echo lang('plan_name') ?></label>
                                                        <input type="text" name="pname" value="<?php echo $name; ?>"
                                                            class="form-control <?php echo form_error('pname') == TRUE ? 'inputTxtError' : ''; ?>"
                                                            id="pname" aria-describedby="pname"
                                                            placeholder="Enter the plan name">
                                                        <label class="error"
                                                            for="pname"><?php echo form_error('pname'); ?></label>
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
                                                        <label for="minInv"><?php echo lang('minimum_investment') ?></label>
                                                        <input type="number"
                                                            class="form-control <?php echo form_error('minInv') == TRUE ? 'inputTxtError' : ''; ?>"
                                                            name="minInv" value="<?php echo $minInv; ?>" id="minInv"
                                                            aria-describedby="minInv" placeholder="1000.00">
                                                        <label class="error"
                                                            for="minInv"><?php echo form_error('minInv'); ?></label>
                                                    </div>
                                                    <!-- /form group -->
                                                </div>
                                                <div class="col-md-6">
                                                    <!-- Form Group -->
                                                    <div class="form-group">
                                                        <label for="maxInv"><?php echo lang('maximum_investment') ?></label>
                                                        <input type="number"
                                                            class="form-control <?php echo form_error('maxInv') == TRUE ? 'inputTxtError' : ''; ?>"
                                                            id="maxInv" name="maxInv" value="<?php echo $maxInv; ?>"
                                                            aria-describedby="maxInv" placeholder="1000001.00">
                                                        <label class="error"
                                                            for="maxInv"><?php echo form_error('maxInv'); ?></label>
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
                                                        <label for="profit"><?php echo lang('profit') ?></label>
                                                        <div class="input-group">
                                                            <input type="number" step=".01"
                                                                class="form-control <?php echo form_error('profit') == TRUE ? 'inputTxtError' : ''; ?>"
                                                                value="<?php echo $profit; ?>" name="profit"
                                                                placeholder="1.00" aria-label="profit">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">%</span>
                                                            </div>
                                                        </div>
                                                        <label class="error"
                                                            for="profit"><?php echo form_error('profit'); ?></label>
                                                    </div>
                                                    <!-- /form group -->
                                                </div>
                                                <div class="col-md-6">
                                                    <!-- Form Group -->
                                                    <div class="form-group">
                                                        <label for="password-1"><?php echo lang('interest_period') ?></label>
                                                        <select
                                                            class="form-control <?php echo form_error('period') == TRUE ? 'inputTxtError' : ''; ?>"
                                                            value="" name="period" id="simple-select">
                                                            <option value="" selected disabled hidden><?php echo lang('choose_here') ?>
                                                            </option>
                                                            <?php foreach($periods as $periodrecord) {?>
                                                            <option
                                                                value="<?php echo $this->security->xss_clean($periodrecord->id) ?>"
                                                                <?php echo $period == $periodrecord->id ? 'selected' : ''; ?>>
                                                                <?php echo $this->security->xss_clean($periodrecord->periodName) ?>
                                                            </option>
                                                            <?php }?>
                                                        </select>
                                                        <label class="error"
                                                            for="period"><?php echo form_error('period'); ?></label>
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
                                                        <label for="maturityDate"><?php echo lang('maturity') ?></label>
                                                        <select
                                                            class="form-control <?php echo form_error('maturityDate') == TRUE ? 'inputTxtError' : ''; ?>"
                                                            name="maturityDate" id="simple-select">
                                                            <option value="" selected disabled hidden><?php echo lang('choose_here') ?>
                                                            </option>
                                                            <?php foreach($periods as $periodrecord) {?>
                                                            <option
                                                                value="<?php echo $this->security->xss_clean($periodrecord->id) ?>"
                                                                <?php echo $this->security->xss_clean($planInfo->maturity == $periodrecord->id) ? 'selected' : ''; ?>>
                                                                <?php echo lang('maturity_payment_after') ?>
                                                                <?php echo $periodrecord->maturity_desc ?></option>
                                                            <?php } ;?>
                                                        </select>
                                                        <label class="error"
                                                            for="maturityDate"><?php echo form_error('maturityDate'); ?></label>
                                                    </div>
                                                    <!-- /form group -->
                                                </div>
                                            </div>
                                            <!-- /row -->
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="1"
                                                        <?php echo $principalReturn == 1 ? 'checked=true' : '' ?>
                                                        id="customcheckboxInline1" name="principalReturn"
                                                        class="custom-control-input">
                                                    <label class="custom-control-label"
                                                        for="customcheckboxInline1"><?php echo lang('principal_return_after_the_end_of_period') ?></label>
                                                </div>
                                                <br>
                                                <small id="checkHelp1" class="form-text"><?php echo lang('principal_return_help_text') ?></small>
                                                <!-- /checkbox -->
                                            </div>
                                            <!--
                                            <div class="form-row mb-4">
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="1"
                                                        <?php //echo $int == 1 ? 'checked=true' : '' ?>
                                                        id="customcheckboxInline2" name="int"
                                                        class="custom-control-input">
                                                    <label class="custom-control-label" for="customcheckboxInline2">Pays
                                                        interest after maturity</label>
                                                </div>
                                            </div>
                                            -->
                                            <div class="mb-4">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="1"
                                                        <?php echo $planInfo->clientDisplay == 1 ? 'checked=true' : '' ?>
                                                        id="customcheckboxInline3" name="clientdisp"
                                                        class="custom-control-input">
                                                    <label class="custom-control-label"
                                                        for="customcheckboxInline3"><?php echo lang('display_to_client') ?></label>
                                                </div>
                                                <br>
                                                <small id="checkHelp2" class="form-text"><?php echo lang('display_to_client_help_text') ?></small>
                                                <!-- /checkbox -->
                                            </div>
                                            <div class="mb-4">
                                                <!-- Checkbox -->
                                                <div class="custom-control custom-checkbox custom-control-inline">
                                                    <input type="checkbox" value="1" 
                                                    <?php echo $planInfo->businessDays == 1 ? 'checked=true' : '' ?>
                                                    id="customcheckboxInline4"
                                                    name="businessDays" class="custom-control-input">
                                                    <label class="custom-control-label" for="customcheckboxInline4">Payout on business days only</label>
                                                </div>
                                                <br>
                                                <small id="checkHelp2" class="form-text">If checked this plan will only generate earnings on weekdays (Mon - Friday).</small>
                                                <!-- /checkbox -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Form Group -->
                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-primary text-uppercase"><?php echo lang('save') ?></button>
                                    </div>
                                    <!-- /form group -->
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