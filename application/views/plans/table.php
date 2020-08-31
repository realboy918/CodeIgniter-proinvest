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
                    <h1 class="dt-page__title text-white display-i"><?php echo lang('investment_plans') ?></h1>
                    <!-- Check the access for this component -->
                    <?php if($this->user_model->getPermissions('plans', 'add', $userId) OR $role == ROLE_ADMIN) {?>
                    <a href="<?php echo base_url(); ?>plans/new" class="btn btn-light btn-sm display-i ft-right"><?php echo lang('create_plan') ?></a>
                    <?php }?>

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
                            <div class="col-sm-6 col-12">

                                <!-- Card -->
                                <div class="dt-card dt-card__full-height text-dark">

                                    <!-- Card Body -->
                                    <div class="dt-card__body p-xl-8 py-sm-8 py-6 px-4">
                                        <span class="badge badge-secondary badge-top-right">
                                        <?php echo lang('minimum_investment') ?>
                                        </span>
                                        <!-- Media -->
                                        <div class="media">

                                            <i class="icon icon-revenue-new icon-6x mr-6 align-self-center"></i>

                                            <!-- Media Body -->
                                            <div class="media-body">
                                                <div class="display-3 font-weight-600 mb-1 init-counter">
                                                <?php echo $minInvest == null ? to_currency('0.00') : to_currency($this->security->xss_clean($minInvest->minInvestment)); ?>
                                                </div>
                                                <span class="d-block">
                                                <?php echo lang('based_on_current_plans') ?>
                                                </span>
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

                            <!-- Grid Item -->
                            <div class="col-sm-6 col-12">

                                <!-- Card -->
                                <div class="dt-card dt-card__full-height text-dark">

                                    <!-- Card Body -->
                                    <div class="dt-card__body p-xl-8 py-sm-8 py-6 px-4">
                                        <span class="badge badge-secondary badge-top-right">
                                        <?php echo lang('current_plans') ?>
                                        </span>
                                        <!-- Media -->
                                        <div class="media">

                                            <i class="icon icon-list icon-6x mr-6 align-self-center"></i>

                                            <!-- Media Body -->
                                            <div class="media-body">
                                                <div class="display-3 font-weight-600 mb-1 init-counter">
                                                <?php echo $this->security->xss_clean($allPlans); ?>
                                                </div>
                                                <span class="d-block">
                                                <?php echo lang('investment_plans') ?>
                                                </span>
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
            <div class="profile-content">

                <!-- Grid -->
                <div class="row">

                    <!-- Grid Item -->
                    <div class="col-xl-12 col-12 order-xl-1">
                        <!-- Card -->
                        <div class="dt-card">

                            <!-- Card Body -->
                            <div class="dt-card__body">

                                <!-- Tables -->
                                <div class="table-responsive dataTables_wrapper dt-bootstrap4">
                                    <div class="table-responsive">
                                        <span class="d-block">
                                        </span>
                                        <?php if(!empty($plans))
                                            { ?>
                                        <table class="table table-striped mb-0">
                                            <thead class="thead-light">
                                                <tr role="row">
                                                    <th><?php echo lang('investment_plan') ?></th>
                                                    <th><?php echo lang('minimum_investment') ?></th>
                                                    <th><?php echo lang('maximum_investment') ?></th>
                                                    <th><?php echo lang('principal_return') ?></th>
                                                    <th><?php echo lang('maturity') ?></th>
                                                    <th class="text-center"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                        foreach($plans as $plan)
                        {
                    ?>
                                                <tr id="row<?php echo $plan->id ?>">
                                                    <td><?php echo $this->security->xss_clean($plan->name).' at '.$this->security->xss_clean($plan->profit).'% '.$this->security->xss_clean($plan->periodName) ?></td>
                                                    <td><?php echo to_currency($this->security->xss_clean($plan->minInvestment)) ?></td>
                                                    <td><?php echo to_currency($this->security->xss_clean($plan->maxInvestment)) ?></td>
                                                    <td><?php $val = $plan->principalReturn == 1 ? lang('yes') : lang('no'); echo $val ?>
                                                    </td>
                                                    <td><?php echo $this->security->xss_clean($plan->maturity_desc) ?></td>
                                                    <td class="text-center">
                                                    <!-- Check the access for this component -->
                                                    <?php if($this->user_model->getPermissions('plans', 'edit', $userId) OR $role == ROLE_ADMIN) {?>
                                                        <a class="btn btn-sm btn-info"
                                                            href="<?php echo base_url().'plans/edit/'.$plan->id; ?>"
                                                            title="Edit"><?php echo lang('edit') ?></a>
                                                        <button class="btn btn-sm btn-danger deleteUser" id="deletebutton<?php echo $plan->id ?>" value="<?php echo $plan->id ?>" data-toggle="modal" data-target="#deleteModal"><?php echo lang('delete') ?></button>
                                                    <?php }?>
                                                    </td>
                                                </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>
                                        <?php echo $this->pagination->create_links(); ?>
                                        <?php } else { ?>
                                        <div class="text-center mt-5">
                                            <img src="<?php echo base_url('assets/dist/img/no-search-results.png') ?>" class="w-20rm">
                                            <h1><?php echo lang('no_records_found') ?></h1>
                                        </div>
                                        <?php }?>
                                    </div>
                                    <!-- /tables -->

                                </div>
                                <!-- /card body -->

                            </div>
                            <!-- /card -->
                        </div>
                        <!-- /grid item -->

                        <div class="dt-card__body">

                            <!-- Modal -->
                            <div class="modal fade display-n" id="deleteModal" tabindex="-1" role="dialog"
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

                                        <?php echo form_open(base_url() , array( 'class' => 'delete-form' ));?>
                                        <!-- Modal Body -->
                                        <div class="modal-body">
                                            <input type="hidden" id="planIdVal" name="id"/>
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
                                        <?php echo form_close();?>

                                    </div>
                                    <!-- /modal content -->

                                </div>
                            </div>
                            <!-- /modal -->
                        </div>

                    </div>
                    <!-- /grid -->

                </div>
                <!-- /profile content -->

            </div>
            <!-- /Profile -->

        </div>
    </div>
<script src="<?php echo base_url('/assets/dist/js/functions.js') ?>"></script>