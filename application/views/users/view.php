<?php
$targetId =  $this->security->xss_clean($userInfo->userId);
$ppic = $userInfo->ppic == '' ? base_url('assets/dist/img/avatar.png') : $ppic;
$fname = $this->security->xss_clean($userInfo->firstName);
$lname =  $this->security->xss_clean($userInfo->lastName);
$email =  $this->security->xss_clean($userInfo->email);
$mobile =  $this->security->xss_clean($userInfo->mobile);
$roleId =  $this->security->xss_clean($userInfo->roleId);
$total =  $this->security->xss_clean($accountInfo);
$pmtType =  $this->security->xss_clean($userInfo->pmtType);
$pmtAccount = $this->security->xss_clean($userInfo->pmtAccount);
?>


<!-- Site Content Wrapper -->
<div class="dt-content-wrapper">

    <!-- Site Content -->
    <div class="dt-content">
        <!-- Profile -->
        <div class="profile">

            <!-- Profile Banner -->
            <div class="profile__banner">

                <!-- Profile Banner Top -->
                <div class="profile__banner-detail">
                    <!-- Avatar Wrapper -->
                    <div class="dt-avatar-wrapper">
                        <!-- Avatar -->
                        <img class="dt-avatar dt-avatar__shadow size-90 mr-sm-4"
                            src="<?php echo $ppic; ?>"
                            alt="<?php echo $fname.' '.$lname ?>">
                        <!-- /avatar -->

                        <!-- Info -->
                        <div class="dt-avatar-info">
                            <span
                                class="dt-avatar-name display-4 mb-2 font-weight-light"><?php echo $fname.' '.$lname ?></span>
                            <span class="f-16"><?php echo $email ?></span>
                            <span class="f-16"><?php echo $pmtType.' '.$pmtAccount; ?></span>
                            <div class="dropdown mt-2">

                                <!-- Check the access for this component -->
                                <?php if($this->user_model->getPermissions('clients', 'edit', $userId) OR $role == ROLE_ADMIN) {?>
                                <!-- Dropdown Button -->
                                <a href="<?php echo base_url().'clients/editClient/'.$targetId; ?>"
                                    class="dropdown-toggle no-arrow text-white">
                                    <i class="icon icon-settings icon-xl mr-2"></i><span
                                        class="d-none d-sm-inline-block"><?php echo lang('edit') ?></span>
                                </a>
                                <!-- /dropdown button -->
                                <?php }?>

                            </div>
                        </div>
                        <!-- /info -->
                    </div>
                    <!-- /avatar wrapper -->

                    <div class="ml-sm-auto">
                        <!-- List -->
                        <div class="col-sm-12">
                            <div class="d-flex align-items-baseline mb-1">
                                <span class="display-2 font-weight-500 text-white mr-2"><?php echo to_currency($total) ?></span>
                            </div>
                            <p class="mb-0"><?php echo lang('overall_balance') ?></p>
                        </div>
                        <!-- /list -->
                    </div>
                </div>
                <!-- /profile banner top -->

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
                                <h3 class="mb-2 mb-sm-n5"><?php echo lang('transactions') ?></h3>

                                <ul class="card-header-links nav nav-underline" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#tab-pane1" role="tab"
                                            aria-controls="tab-pane1" aria-selected="true"><?php echo lang('deposits') ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tab-pane2" role="tab"
                                            aria-controls="tab-pane2" aria-selected="true"><?php echo lang('withdrawals') ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tab-pane3" role="tab"
                                            aria-controls="tab-pane3" aria-selected="true"><?php echo lang('earnings') ?></a>
                                    </li>
                                </ul>

                            </div>
                            <!-- /card header -->

                            <!-- Card Body -->
                            <div class="card-body pb-2">

                                <!-- Tab Content-->
                                <div class="tab-content mt-5">

                                    <!-- Tab panel -->
                                    <div id="tab-pane1" class="tab-pane active">

                                        <div class="dt-card overflow-hidden">

                                            <!-- Card Body -->
                                            <div class="dt-card__body p-0">

                                                <!-- Tables -->
                                                <div class="table-responsive">
                                                    <table class="table table-striped mb-0">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th scope="col"><?php echo lang('transaction_code') ?></th>
                                                                <th scope="col"><?php echo lang('amount') ?></th>
                                                                <th scope="col"><?php echo lang('date') ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                if(!empty($deposits))
                                                                {
                                                                    foreach($deposits as $deposit)
                                                                    {
                                                                ?>
                                                            <tr>
                                                                <td><?php echo $deposit->txnCode ?></td>
                                                                <td><?php echo to_currency($deposit->amount) ?></td>
                                                                <td><?php echo date("d-m-Y H:i:s", strtotime($deposit->createdDtm)) ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                                    }
                                                                }
                                                                ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- /tables -->

                                            </div>
                                            <!-- /card body -->

                                        </div>

                                    </div>
                                    <!-- /tab panel -->

                                    <!-- Tab panel -->
                                    <div id="tab-pane2" class="tab-pane">
                                        <!-- Card Body -->
                                        <div class="dt-card__body p-0">

                                            <!-- Tables -->
                                            <div class="table-responsive">
                                                <table class="table table-striped mb-0">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th scope="col"><?php echo lang('transaction_code') ?></th>
                                                            <th scope="col"><?php echo lang('amount') ?></th>
                                                            <th scope="col"><?php echo lang('date') ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                                if(!empty($withdrawals))
                                                                {
                                                                    foreach($withdrawals as $withdrawal)
                                                                    {
                                                                ?>
                                                        <tr>
                                                            <td><?php echo $withdrawal->txnCode ?></td>
                                                            <td><?php echo to_currency($withdrawal->amount) ?></td>
                                                            <td><?php echo date("d-m-Y H:i:s", strtotime($withdrawal->createdDtm)) ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- /tables -->

                                        </div>
                                        <!-- /card body -->
                                    </div>
                                    <!-- /tab panel -->

                                    <!-- Tab panel -->
                                    <div id="tab-pane3" class="tab-pane">
                                        <!-- Card Body -->
                                        <div class="dt-card__body p-0">

                                            <!-- Tables -->
                                            <div class="table-responsive">
                                                <table class="table table-striped mb-0">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th scope="col"><?php echo lang('transaction_code') ?></th>
                                                            <th scope="col"><?php echo lang('amount') ?></th>
                                                            <th scope="col"><?php echo lang('date') ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                                if(!empty($earnings))
                                                                {
                                                                    foreach($earnings as $earning)
                                                                    {
                                                                ?>
                                                        <tr>
                                                            <td><?php echo $earning->txnCode ?></td>
                                                            <td><?php echo to_currency($earning->amount) ?></td>
                                                            <td><?php echo date("d-m-Y H:i:s", strtotime($earning->createdDtm)) ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- /tables -->

                                        </div>
                                        <!-- /card body -->
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


    </div>
    <!-- /site content -->