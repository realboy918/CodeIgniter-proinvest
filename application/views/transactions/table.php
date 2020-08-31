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
                    <h1 class="dt-page__title text-white display-i">
                    <?php if($pageTitle == 'Withdrawals'){
                        echo lang('withdrawals');
                    } else if($pageTitle == 'Deposits'){
                        echo lang('deposits');
                    } else if($pageTitle == 'Earnings'){
                        echo lang('earnings');
                    }?>
                    </h1>
                    <?php if($pageTitle == 'Withdrawals'){?>
                        <?php if($role == ROLE_CLIENT) { ?>
                        <a href="<?php echo base_url(); ?>withdrawals/new" class="btn btn-light btn-sm display-i ft-right"><?php echo lang('make_withdrawal') ?></a>
                        <?php } ?>
                    <?php } else if($pageTitle == 'Deposits'){?>
                        <?php if($role == ROLE_ADMIN) { ?>
                    <a href="<?php echo base_url(); ?>deposits/new" class="btn btn-light btn-sm display-i ft-right"><?php echo lang('make_deposit') ?></a>
                    <?php } } ?>
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
                            <?php if($pageTitle == 'Earnings') {?>
                            <div class="col-sm-4 col-12">
                                <!-- Card -->
                                <div class="dt-card dt-card__full-height text-dark">
                                    <!-- Card Body -->
                                    <div class="dt-card__body p-xl-8 py-sm-8 py-6 px-4">
                                        <span class="badge badge-secondary badge-top-right"><?php echo lang('principal_repayments') ?></span>
                                        <!-- Media -->
                                        <div class="media">
                                            <i class="icon icon-revenue icon-6x mr-6 align-self-center"></i>
                                            <!-- Media Body -->
                                            <div class="media-body">
                                                <div class="display-3 font-weight-600 mb-1 init-counter">
                                                    <?php echo $principalEarnings > 0 ? to_currency($principalEarnings) : to_currency('0.00'); ?></div>
                                                <span class="d-block"><?php echo lang('principal_repayments') ?></span>
                                            </div>
                                            <!-- /media body -->
                                        </div>
                                        <!-- /media -->
                                    </div>
                                    <!-- /card body -->
                                </div>
                                <!-- /card -->
                            </div>
                            <?php }?>
                            <!-- Grid Item -->
                            <div class="col-sm-<?php echo $pageTitle == 'Earnings' ? '4': '6'; ?> col-12">

                                <!-- Card -->
                                <div class="dt-card dt-card__full-height text-dark">

                                    <!-- Card Body -->
                                    <div class="dt-card__body p-xl-8 py-sm-8 py-6 px-4">
                                        <span class="badge badge-secondary badge-top-right">
                                        <?php if($role == ROLE_CLIENT) { ?>
                                            <?php if($pageTitle == 'Withdrawals'){
                                                echo lang('pending_withdrawals');
                                            } else if($pageTitle == 'Deposits'){
                                                echo lang('active_deposits');
                                            } else if($pageTitle == 'Earnings'){
                                                echo lang('interest_earnings');
                                            } 
                                        } else if($role == ROLE_ADMIN OR $role == ROLE_MANAGER) {?>
                                        <?php if($pageTitle == 'Withdrawals'){
                                                echo lang('pending_withdrawals');
                                            } else if($pageTitle == 'Deposits'){
                                                echo lang('active_deposits');
                                            } else if($pageTitle == 'Earnings'){
                                                echo lang('interest_earnings');
                                            } 
                                        }?>
                                        </span>
                                        <!-- Media -->
                                        <div class="media">

                                            <i class="icon icon-revenue icon-6x mr-6 align-self-center"></i>

                                            <!-- Media Body -->
                                            <div class="media-body">
                                                <div class="display-3 font-weight-600 mb-1 init-counter">
                                                    <?php if($pageTitle == 'Withdrawals'){
                                                    echo $withdrawalsInfo > 0 ? to_currency($withdrawalsInfo) : to_currency('0.00');
                                                    } else if($pageTitle == 'Deposits'){
                                                        echo $activeDeposits > 0 ? to_currency($activeDeposits) : to_currency('0.00');
                                                    } else if($pageTitle == 'Earnings'){
                                                        echo $interestEarnings > 0 ? to_currency($interestEarnings): to_currency('0.00');
                                                    } ?>
                                                </div>
                                                <span class="d-block">
                                                    <?php if($pageTitle == 'Withdrawals'){
                                                    echo lang('pending_withdrawals');
                                                    } else if($pageTitle == 'Deposits'){
                                                        echo lang('locked_deposits');
                                                    } else if($pageTitle == 'Earnings'){
                                                        echo lang('interest_earnings');
                                                    } ?>
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
                            <div class="col-sm-<?php echo $pageTitle == 'Earnings' ? '4': '6'; ?> col-12">

                                <!-- Card -->
                                <div class="dt-card dt-card__full-height text-dark">

                                    <!-- Card Body -->
                                    <div class="dt-card__body p-xl-8 py-sm-8 py-6 px-4">
                                        <span class="badge badge-secondary badge-top-right">
                                            <?php if($pageTitle == 'Withdrawals'){
                                                echo $role == ROLE_ADMIN ? lang('completed_withdrawals') : lang('total_earnings');
                                            } else if($pageTitle == 'Deposits'){
                                                echo lang('inactive_deposits');
                                            } else if($pageTitle == 'Earnings'){
                                                echo lang('referral_earnings');
                                            } ?>
                                        </span>
                                        <!-- Media -->
                                        <div class="media">

                                            <i class="icon icon-revenue-new icon-6x mr-6 align-self-center"></i>

                                            <!-- Media Body -->
                                            <div class="media-body">
                                                <div class="display-3 font-weight-600 mb-1 init-counter">
                                                    <?php if($pageTitle == 'Withdrawals'){
                                                        if($role == ROLE_CLIENT){
                                                            echo $earningsInfo > 0 ? to_currency($earningsInfo) : to_currency('0.00');
                                                        } else {
                                                            echo $completedWithdrawals > 0 ? to_currency($completedWithdrawals) : to_currency('0.00');
                                                        }
                                                } else if($pageTitle == 'Deposits'){
                                                    echo $inActiveDeposits > 0 ? to_currency($inActiveDeposits) : to_currency('0.00');
                                                } else if($pageTitle == 'Earnings'){
                                                    echo $referralEarnings > 0 ? to_currency($referralEarnings) : to_currency('0.00');
                                                } ?>
                                                </div>
                                                <span class="d-block">
                                                    <?php if($pageTitle == 'Withdrawals'){
                                                    echo $role == ROLE_ADMIN ? lang('completed_withdrawals') : lang('total_earnings');
                                                    } else if($pageTitle == 'Deposits'){
                                                        echo lang('inactive_deposits');
                                                    } else if($pageTitle == 'Earnings'){
                                                        echo lang('referral_earnings');
                                                    }?>
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
                        <?php if(!empty($userInfo->pmtAccount) OR $role == ROLE_ADMIN OR $role == ROLE_MANAGER) {?>
                            <div class="dt-card__body">
                            <!-- Card Body -->
                                <!-- Tables -->
                                <div class="table-responsive dataTables_wrapper dt-bootstrap4">
                                    <div class="table-responsive">
                                    <?php if(!empty($transactions))
                                            { ?>
                                        <table class="table table-striped mb-0">
                                            <thead class="thead-light">
                                                <tr role="row">
                                                    <th><?php echo lang('transaction_id') ?></th>
                                                    <?php if($role == ROLE_ADMIN OR $role == ROLE_MANAGER) { ?>
                                                    <th><?php echo lang('client') ?></th>
                                                    <?php } ?>
                                                    <th><?php echo lang('amount') ?></th>
                                                    <?php if($pageTitle == 'Withdrawals'){?>
                                                    <th><?php echo lang('type') ?></th>
                                                    <th><?php echo lang('status') ?></th>
                                                    <?php }?>
                                                    <?php if($pageTitle == 'Earnings'){?>
                                                    <th><?php echo lang('type') ?></th>
                                                    <?php }?>
                                                    <th><?php echo lang('created_on') ?></th>
                                                    <?php if($pageTitle == 'Deposits'){?>
                                                        <th><?php echo lang('maturity_date') ?></th>
                                                    <?php }?>
                                                    <?php if($pageTitle == 'Deposits' && ($role == ROLE_ADMIN OR $role == ROLE_MANAGER)){?>
                                                        <th><?php echo lang('status') ?></th>
                                                    <?php }?>
                                                    <?php if(($pageTitle == 'Deposits' || $pageTitle == 'Withdrawals') && ($role == ROLE_ADMIN OR $role == ROLE_MANAGER)){?>
                                                    <th class="text-center"></th>
                                                    <?php } else if($pageTitle == 'Deposits') {?>
                                                    <th><?php echo lang('status') ?></th>
                                                    <?php } else if($pageTitle == 'Withdrawals') {?>
                                                    <th class="text-center"></th>
                                                    <?php }?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach($transactions as $transaction)
                                                { 
                                                ?>
                                                <tr id="row<?php echo $transaction->id ?>">
                                                    <td><?php echo $transaction->txnCode ?></td>
                                                    <?php if($role == ROLE_ADMIN OR $role == ROLE_MANAGER) { ?>
                                                    <td><?php echo $transaction->firstName.' '.$transaction->lastName ?>
                                                    </td>
                                                    <?php } ?>
                                                    <td><?php echo to_currency($transaction->amount) ?></td>
                                                    <?php if($pageTitle == 'Withdrawals'){?>
                                                    <td><?php echo $transaction->withdrawal_method ?></td>
                                                    <td class="text-capitalize">
                                                        <?php if($transaction->status == 0) {
                                                            echo lang('pending');
                                                         } else if($transaction->status == 1) {
                                                             echo lang('completed');
                                                          } else if($transaction->status == 2) {
                                                              echo lang('not_approved');
                                                           }?>
                                                    </td>
                                                    <?php }?>
                                                    <?php if($pageTitle == 'Earnings'){?>
                                                    <td><?php echo $transaction->type; ?></td>
                                                    <?php }?>
                                                    <td><?php echo date("d M Y H:i", strtotime($transaction->createdDtm)) ?>
                                                    </td>
                                                    <?php if($pageTitle == 'Deposits'){?>
                                                    <td><?php echo $transaction->maturityDtm; ?></td>
                                                    <!-- Check the access for this component -->
                                                    <?php if($this->user_model->getPermissions('deposits', 'edit', $userId) OR $role == ROLE_ADMIN) {?>
                                                    <?php if($pageTitle == 'Deposits' && ($role == ROLE_ADMIN OR $role == ROLE_MANAGER)){?>
                                                    <td>
                                                    <?php if(($transaction->maturityDtm < date('Y-m-d H:i:s')) && $transaction->status == 1) {?>
                                                        <?php echo lang('withdrawn') ?>
                                                    <?php } else if(($transaction->maturityDtm < date('Y-m-d H:i:s')) && ($transaction->status == 0 || $transaction->status == 3)) {?>
                                                        <?php echo lang('deposit_matured') ?>
                                                    <?php } else if($transaction->status == 5){?>
                                                        <?php echo lang('not_approved') ?>
                                                    <?php } else if($transaction->status == 4){?>
                                                        <?php echo lang('pending_approval') ?>
                                                    <?php } else {?>
                                                        <?php echo lang('active') ?>
                                                    <?php }?>
                                                    </td>
                                                    <?php }?>
                                                    <td>
                                                    <?php if($transaction->status == 4){?>
                                                        <a class="btn btn-sm btn-info trans-btn" href="<?php echo base_url().'deposits/editTrans/'.$transaction->id; ?>" title="Delete"><?php echo lang('approve') ?></a> |
                                                        <button class="btn btn-sm btn-danger cancelAction trans-btn" title="Cancel" id="cancelButton<?php echo $transaction->id ?>" value="<?php echo base_url('deposits/cancelTrans/').$transaction->id ?>" data-toggle="modal" data-target="#confirmationModal"><?php echo lang('cancel') ?></a>
                                                    <?php } else {?>
                                                        <a class="btn btn-sm btn-info trans-btn" href="<?php echo base_url().'deposits/editTrans/'.$transaction->id; ?>" title="Delete"><?php echo lang('edit') ?></a> |
                                                        <button class="btn btn-sm btn-danger confirmAction trans-btn" title="Delete" id="confirmButton<?php echo $transaction->id ?>" value="<?php echo base_url('deposits/deleteTrans/').$transaction->id ?>" data-toggle="modal" data-target="#confirmationModal"><?php echo lang('delete') ?></a>
                                                    <?php }?>
                                                    </td>
                                                    <?php }?>
                                                    <?php if($role == ROLE_CLIENT) {?>
                                                    <td class="collastcl" id="col<?php echo $transaction->txnCode ?>">
                                                    <?php if(($transaction->maturityDtm < date('Y-m-d H:i:s')) && $transaction->status == 0) {?>
                                                        <button class="btn btn-sm btn-info reinvest trans-btn" id="reinvest<?php echo $transaction->txnCode ?>" data-toggle="modal" value="<?php echo $transaction->txnCode ?>" data-target="#modal"><?php echo lang('reinvest') ?></button>
                                                        <button data-toggle="modal" id="<?php echo $transaction->txnCode ?>" data-target="#modal" value="<?php echo to_currency($transaction->amount) ?>" class="btn btn-sm btn-info withdraw trans-btn"><?php echo lang('withdraw') ?></button>
                                                    <?php } else if(($transaction->maturityDtm < date('Y-m-d H:i:s')) && $transaction->status == 1) {?>
                                                    <?php echo lang('withdrawn') ?>
                                                    <?php } else if(($transaction->maturityDtm > date('Y-m-d H:i:s')) && $transaction->status == 0) {?>
                                                        <?php echo lang('pending_maturity') ?>
                                                    <?php } else if(($transaction->maturityDtm < date('Y-m-d H:i:s')) && $transaction->status == 3) {?>
                                                        <?php echo lang('maturity_reached') ?>
                                                    <?php } else if(($transaction->maturityDtm > date('Y-m-d H:i:s')) && $transaction->status == 3) {?>
                                                        <?php echo lang('pending_maturity') ?>
                                                    <?php } else if($transaction->status == 4){?>
                                                        <?php echo lang('pending_approval') ?>
                                                    <?php } else if($transaction->status == 5){?>
                                                        <?php echo lang('not_approved') ?>
                                                    <?php }?>
                                                    </td>
                                                    <?php } ?>
                                                    <?php } ?>
                                                    <?php if($pageTitle == 'Withdrawals') {?>
                                                    <td class="text-center p-0-5m">
                                                        <?php if($role == ROLE_CLIENT) {?>
                                                        <button class="btn btn-sm btn-info accountsButton trans-btn"
                                                            data-userid="<?php echo $transaction->id ?>" 
                                                            data-url="<?php echo base_url('user_payment_accounts/').$transaction->id ?>"
                                                            title="Payment Accounts" 
                                                            data-toggle="modal" 
                                                            data-target="#accountsModal">Payment Details</button>
                                                        <?php } if($role == ROLE_ADMIN OR $role == ROLE_MANAGER) {?>
                                                        <?php if($transaction->status == 0){?>
                                                        <!-- Check the access for this component -->
                                                        <?php if($this->user_model->getPermissions('withdrawals', 'approve', $userId) OR $role == ROLE_ADMIN) {?>
                                                        <button class="btn btn-sm btn-info confirmAction trans-btn"
                                                            data-userid="" title="accept" 
                                                            data-url="<?php echo base_url('user_payment_accounts/').$transaction->id ?>"
                                                            id="confirmButton<?php echo $transaction->id ?>" 
                                                            value="<?php echo base_url('approveWithdrawal/').$transaction->id ?>" 
                                                            data-toggle="modal" 
                                                            data-target="#confirmationModal"><?php echo lang('approve') ?></button>
                                                        <button class="btn btn-sm btn-info declineAction trans-btn"
                                                            data-userid="" title="accept" 
                                                            id="declineButton<?php echo $transaction->id ?>" 
                                                            value="<?php echo base_url('declineWithdrawal/').$transaction->id ?>" 
                                                            data-toggle="modal" 
                                                            data-target="#declineModal">Decline</button>
                                                        <?php } else { ?>
                                                            <?php echo lang('pending_payment') ?>
                                                        <?php } } else {?>
                                                            <button class="btn btn-sm btn-info accountsButton trans-btn"
                                                            data-userid="<?php echo $transaction->id ?>" 
                                                            data-url="<?php echo base_url('user_payment_accounts/').$transaction->id ?>"
                                                            title="Payment Accounts" 
                                                            data-toggle="modal" 
                                                            data-target="#accountsModal">Payment Details</button>
                                                        <?php } } ?>  
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <?php echo $this->pagination->create_links(); ?>
                                        <?php } else { ?>
                                        <div class="text-center mt-5">
                                            <img src="<?php echo base_url('assets/dist/img/no-search-results.png') ?>" class="w-20rm">
                                            <h1><?php echo lang('no_transactions_found') ?></h1>
                                        </div>
                                        <?php }?>
                                    </div>
                                    <!-- /tables -->

                                </div>
                                <!-- /card body -->
                                <?php } else {?>
                                <div class="card-body">

                                    <!-- Card Title-->
                                    <h2 class="card-title"><?php echo lang('no_payment_method_on_record') ?></h2>
                                    <!-- Card Title-->
                                    <!-- Card Text-->
                                    <p class="card-text">
                                    <?php echo lang('please_setup_payment_account') ?>
                                    </p>
                                    <!-- /card text-->

                                    <!-- Card Link-->
                                    <a href="<?php echo base_url() ?>paymentInfo"
                                        class="btn btn-info text-uppercase"><?php echo lang('setup_payment_account') ?></a>
                                    <!-- /card link-->

                                </div>
                                <?php } ?>

                            </div>
                            <!-- /card -->
                        </div>
                        <!-- /grid item -->
                        
                        <div class="dt-card__body">
                            <!-- Modal -->
                            <div class="modal fade display-n" id="accountsModal" tabindex="-1" role="dialog" aria-labelledby="model-8" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">

                                    <!-- Modal Content -->
                                    <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h3 class="modal-title" id="model-8">Payment Account</h3>
                                            <button type="button" class="close"
                                                data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <!-- /modal header -->

                                        <!-- Modal Body -->
                                        <div class="modal-body">
                                            <div class="row" id="paymentList">
                                            </div>
                                        </div>
                                        <!-- /modal body -->

                                        <!-- Modal Footer -->
                                        <div class="modal-footer">
                                            <button type="button"
                                                class="btn btn-secondary btn-sm"
                                                data-dismiss="modal">Cancel
                                            </button>
                                        </div>
                                        <!-- /modal footer -->
                                        <?php echo form_close();?>

                                    </div>
                                    <!-- /modal content -->

                                </div>
                            </div>
                            <!-- /modal -->
                            <?php if($role == ROLE_ADMIN) {?>
                                <!-- Modal -->
                            <div class="modal fade display-n" id="declineModal" tabindex="-1" role="dialog"
                                aria-labelledby="model-8" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">

                                    <!-- Modal Content -->
                                    <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h3 class="modal-title" id="model-8">Decline Withdrawal</h3>
                                            <button type="button" class="close"
                                                data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <!-- /modal header -->

                                        <?php echo form_open(base_url() , array( 'id'=>'decline-form', 'class' => 'confirm-form decline-form' ));?>
                                        <!-- Modal Body -->
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Reason</label>
                                                <textarea type="text" class="form-control" name="reason" rows="3" placeholder="Decline Reason"></textarea>
                                            </div>
                                            <input type="hidden" id="transDecID" name="id"/>
                                            <div class="form-group">
                                                <label>Enter password To Decline</label>
                                                <input class="form-control" name="password"
                                                    id="passwordDec" type="password">
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
                                                class="btn btn-primary btn-sm" id="buttonSubmitDec"><?php echo lang('save') ?>
                                            </button>
                                        </div>
                                        <!-- /modal footer -->
                                        <?php echo form_close();?>

                                    </div>
                                    <!-- /modal content -->

                                </div>
                            </div>
                            <!-- /modal -->
                            <!-- Modal -->
                            <div class="modal fade display-n" id="confirmationModal" tabindex="-1" role="dialog"
                                aria-labelledby="model-8" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">

                                    <!-- Modal Content -->
                                    <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <?php if($pageTitle == 'Withdrawals') {?>
                                            <h3 class="modal-title" id="model-8">Make payment to</h3>
                                            <?php } else if($pageTitle == 'Deposits') {?>
                                            <h3 class="modal-title" id="model-8">Enter Password</h3>
                                            <?php }?>
                                            <button type="button" class="close"
                                                data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <!-- /modal header -->

                                        <?php echo form_open(base_url() , array( 'id'=>'confirm-form', 'class' => 'confirm-form' ));?>
                                        <!-- Modal Body -->
                                        <div class="modal-body">
                                            <?php if($pageTitle == 'Withdrawals') {?>
                                            <table class="table table-hover table-dark mb-0">
                                                <tbody id="paymentData">
                                                </tbody>
                                            </table>
                                            <small class="form-text mb-10">NB: Make payment to this account manually and then approve the withdrawal by entering your password below.</small>
                                            <?php }?>
                                            <input type="hidden" id="transID" name="id"/>
                                            <div class="form-group">
                                                <?php if($pageTitle == 'Withdrawals') {?>
                                                <label>Enter password To confirm payment</label>
                                                <?php }?>
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
                                                class="btn btn-primary btn-sm" id="buttonSubmit"><?php echo lang('save') ?>
                                            </button>
                                        </div>
                                        <!-- /modal footer -->
                                        <?php echo form_close();?>

                                    </div>
                                    <!-- /modal content -->

                                </div>
                            </div>
                            <!-- /modal -->
                            <?php }?>
                        </div>

                    </div>
                    <!-- /grid -->

                </div>
                <!-- /profile content -->

            </div>
            <!-- /Profile -->

        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade display-n" id="modal" tabindex="-1" role="dialog"
        aria-labelledby="model-8" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- Modal Content -->
            <div class="modal-content">
                <?php echo form_open(base_url() , array( 'id' => 'modalForm' ));?>
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h3 class="modal-title" id="model-8"></h3>
                        <button type="button" class="close"
                            data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <!-- /modal header -->
                    <!-- Modal Body -->
                    <!-- Form Group -->
                    <div class="modal-body mb--10 hide" id="reinvestPlans">
                        <div class="form-group">
                            <label for="fname"><?php echo lang('select_investment_plan') ?></label>
                            <select class="form-control" name="plan" id="simple-select">
                                <option value="" selected disabled hidden><?php echo lang('select_investment_plan') ?></option>
                                <?php foreach($plans as $plan) { ?>
                                <option value="<?php echo $plan->id ?>">
                                    <?php echo $plan->name.' at '.$plan->profit.'% '.$plan->periodName.' investment: '.to_currency($plan->minInvestment).' - '.to_currency($plan->maxInvestment)  ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <!-- /form group -->
                    <div class="modal-body" id="modalBody">
                    </div>
                    <!-- /modal body -->
                    <!-- Modal Footer -->
                    <div class="modal-footer" id="modalFooter">
                        <button type="button"
                            class="btn btn-secondary btn-sm"
                            data-dismiss="modal"><?php echo lang('cancel') ?>
                        </button>
                        <div id="continue"></div>
                    </div>
                    <!-- /modal footer -->
                <?php echo form_close();?>

            </div>
            <!-- /modal content -->
        </div>
    </div>
    <!-- /modal -->
    <script src="<?php echo base_url('/assets/dist/js/trans.js') ?>"></script>
    <script>
        $('.accountsButton').click(function(e){
            e.preventDefault();
            var user = $(this).attr('data-id');
            var actionurl = $(this).attr('data-url');

            //Clear list
            $('#paymentList').html('');

            $.ajax({
                url: actionurl,
                type: 'get',
                success: function(res) {
                    var content = JSON.parse(res);
                    console.log(content);
                    if(content.success == true)
                    {
                            var div = document.getElementById('paymentList');

                            if(content.method == 'Bank Transfer'){
                                var divOpen = '<div class="row col-md-12">'
                                var div1 = '<div class="col-md-6">Bank Name: </div>'
                                var div2 = '<div class="col-md-6 text-uppercase">'+content.data.bank_name+'</div>'
                                var div3 = '<div class="col-md-6">Account Name: </div>'
                                var div4 = '<div class="col-md-6 text-uppercase">'+content.data.account_name+'</div>'
                                var div5 = '<div class="col-md-6">Account Number: </div>'
                                var div6 = '<div class="col-md-6">'+content.data.account_number+'</div>'
                                var div7 = '<div class="col-md-6">Swift Code: </div>'
                                var div8 = '<div class="col-md-6">'+content.data.swift_code+'</div>'
                                var div9 = '<div class="col-md-6">Status: </div>'
                                var div10 = '<div class="col-md-6">'+content.status+'</div>'
                                var div11 = '<div class="col-md-6">Reason: </div>'
                                var div12 = '<div class="col-md-6">'+content.data.reason+'</div>'
                                var divClose = '</div>'

                                if(content.data.status == '0' || content.data.status == '1'){
                                    div.innerHTML += divOpen + div1 + div2 + div3 + div4 + div5 + div6 + div7 + div8 + div9 + div10 + divClose;
                                } else {
                                    div.innerHTML += divOpen + div1 + div2 + div3 + div4 + div5 + div6 + div7 + div8 + div9 + div10 + div11 + div12 + divClose;
                                }
                            } else {
                                var divOpen = '<div class="row col-md-12">'
                                var div1 = '<div class="col-md-6">'+content.data.withdrawal_method+': </div>'
                                var div2 = '<div class="col-md-6">'+content.data.withdrawal_account+'</div>'
                                var div3 = '<div class="col-md-6">Status: </div>'
                                var div4 = '<div class="col-md-6">'+content.status+'</div>'
                                var div5 = '<div class="col-md-6">Reason: </div>'
                                var div6 = '<div class="col-md-6">'+content.data.reason+'</div>'
                                var divClose = '</div>'

                                if(content.data.status === 0 || content.data.status === 1){
                                    div.innerHTML += divOpen + div1 + div2 + div3 + div4 +  divClose;
                                } else {
                                    div.innerHTML += divOpen + div1 + div2 + div3 + div4 + divClose;
                                }
                            }

                        $("#terms").html(content.terms);
                        $('#submit').attr('disabled', false);
                        $("#submit").html('Create Account');
                    }
                }
            })

        })
    </script>