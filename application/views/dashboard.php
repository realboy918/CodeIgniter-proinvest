<?php
$refType = $companyInfo['refType'];
$refInt = $companyInfo['refInterest'];
if($refType == 'multiple') {
    $int_array = explode(",", $refInt);
    $intCount = count($int_array);
    $first = $int_array[0];
} else 
{
    $int_array = explode(",", $refInt);
    $intCount = count($int_array);
    $first = $int_array[0];
}
?>
<!-- Site Content Wrapper -->
<div class="dt-content-wrapper">
    <!-- Site Content -->
    <div class="dt-content">
        <!-- Page Header -->
        <div class="dt-page__header">
            <h1 class="dt-page__title text-capitalize"><?php echo lang("dashboard") ?></h1>
            <div class="dt-entry__header">
                <!-- Entry Heading -->
                <div class="dt-entry__heading">
                </div>
                <!-- /entry heading -->
            </div>
        </div>
        <!-- /page header -->

        <!-- Grid -->
        <div class="row <?php echo $role==ROLE_CLIENT ? 'mt-3-2m' : '' ?>">
            <!-- Grid Item -->
            <div class="col-xl-12 col-12 col-md-12">
                <?php if($role == ROLE_ADMIN || $role == ROLE_MANAGER) {?>
                <div class="row">
                    <div class="col-md-8 col-12">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Card -->
                                <div class="dt-card">
                                    <!-- Card Body -->
                                    <div class="dt-card__body p-xl-8 py-sm-8 py-6 px-4 dt-card bg-secondary text-white">
                                        <span class="badge badge-secondary badge-top-right"><?php echo lang("clients") ?></span>
                                        <!-- Media -->
                                        <div class="media">
                                            <i
                                                class="icon icon-users text-white icon-5x mr-xl-5 mr-3 align-self-center"></i>
                                            <!-- Media Body -->
                                            <div class="flex-1">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span
                                                        class="h2 mb-0 font-weight-500 mr-2 text-white"><?php echo $allClients ?></span>
                                                    <span class="d-inline-flex text-white">
                                                        <i
                                                            class="icon icon-profit icon-fw text-white"></i>+<?php echo $clientsthisweek ?>
                                                            <?php echo lang("past_7_days") ?>
                                                    </span>
                                                </div>
                                                <div class="h5 mb-2 text-white"><?php echo lang("registered_users") ?></div>
                                            </div>
                                            <!-- /media body -->
                                        </div>
                                        <!-- /media -->
                                    </div>
                                    <!-- /card body -->
                                </div>
                                <!-- /card -->
                            </div>
                            <div class="col-md-6">
                                <!-- Card -->
                                <div class="dt-card">
                                    <!-- Card Body -->
                                    <div class="dt-card__body p-xl-8 py-sm-8 py-6 px-4 dt-card bg-primary text-white">
                                        <span class="badge badge-secondary badge-top-right"><?php echo lang("withdrawals") ?></span>
                                        <!-- Media -->
                                        <div class="media">
                                            <i
                                                class="icon icon-revenue bg-primary text-white icon-5x mr-xl-5 mr-3 align-self-center"></i>
                                            <!-- Media Body -->
                                            <div class="flex-1">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span
                                                        class="h2 mb-0 font-weight-500 mr-2 text-white"><?php echo $pendingWithdrawals > 0 ? to_currency($pendingWithdrawals) : to_currency('0.00') ?></span>
                                                </div>
                                                <div class="h5 mb-2 text-white"><?php echo lang("pending_withdrawals") ?></div>
                                            </div>
                                            <!-- /media body -->
                                        </div>
                                        <!-- /media -->
                                    </div>
                                    <!-- /card body -->
                                </div>
                                <!-- /card -->
                            </div>
                            <div class="col-md-12">
                                <!-- Card -->
                                <div class="dt-card">

                                    <!-- Card Header -->
                                    <div class="dt-card__header mb-4">

                                        <!-- Card Heading -->
                                        <div class="dt-card__heading">
                                            <h3 class="dt-card__title"><?php echo lang("todays_payouts") ?></h3>
                                        </div>
                                        <!-- /card heading -->

                                        <!-- Card Tools -->
                                        <div class="dt-card__tools">
                                            <a href="<?php echo base_url("earnings") ?>" class="dt-card__more"><?php echo lang("view_all") ?></a>
                                        </div>
                                        <!-- /card tools -->

                                    </div>
                                    <!-- /card header -->

                                    <!-- Card Body -->
                                    <div class="dt-card__body pb-5">

                                        <!-- Tables -->
                                        <div class="table-responsive ps-custom-scrollbar ps">
                                            <table class="table table-ordered table-bordered-0 mb-0">
                                                <thead>
                                                    <tr>
                                                        <th class="text-uppercase"><?php echo lang("transaction_code") ?></th>
                                                        <th><?php echo lang("type") ?></th>
                                                        <th><?php echo lang("amount") ?> (<?php echo $companyInfo['currency']; ?>)
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                if(!empty($earningTransactions))
                                                {
                                                foreach($earningTransactions as $transaction)
                                                { 
                                                ?>
                                                    <tr>
                                                        <td><?php echo $transaction->txnCode ?></td>
                                                        <td><?php echo $transaction->type ?></td>
                                                        <td><?php echo $transaction->amount ?></td>

                                                    </tr>
                                                    <?php } } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /tables -->

                                    </div>
                                    <!-- /card body -->

                                </div>
                                <!-- /card -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <!-- Card -->
                        <div class="dt-card">
                            <!-- Card Body -->
                            <div class="dt-card__body p-xl-8 py-sm-8 py-6 px-4 dt-card bg-white text-dark">
                                <span class="badge badge-secondary badge-top-right"><?php echo lang("deposits") ?></span>
                                <!-- Media -->
                                <div class="media">
                                    <!-- Media Body -->
                                    <div class="w-100">
                                        <div class="dt-zone-stats__content">
                                            <div class="h1 display-4 font-weight-600 mb-1">
                                                <?php echo $activeDeposits + $inActiveDeposits > 0 ? to_currency($activeDeposits + $inActiveDeposits) : to_currency('0.00') ?>
                                            </div>
                                            <span><?php echo lang("total_deposits") ?></span>
                                        </div>
                                        <div class="pt-10p">
                                            <div class="row w-100">
                                                <div class="col-6 text-center border-right">
                                                    <div class="h3 mb-1 font-weight-500">
                                                        <?php echo $activeDeposits > 0 ? to_currency($activeDeposits) : to_currency('0.00') ?>
                                                    </div>
                                                    <span><?php echo lang("locked") ?></span>
                                                </div>
                                                <div class="col-6 text-center">
                                                    <div class="h3 mb-1 font-weight-500">
                                                        <?php echo $inActiveDeposits > 0 ? to_currency($inActiveDeposits) : to_currency('0.00') ?>
                                                    </div>
                                                    <span><?php echo lang("inactive") ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /media body -->
                                </div>
                                <!-- /media -->
                            </div>
                            <!-- /card body -->
                        </div>
                        <!-- /card -->
                    </div>
                </div>
                <?php } else if($role == ROLE_CLIENT) {?>
                <!-- Grid Item -->
                <!-- TradingView Widget BEGIN -->
                <div class="tradingview-widget-container mb-1-5m">
                    <div class="tradingview-widget-container__widget"></div>
                    <script type="text/javascript"
                        src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
                    {
                        "symbols": [{
                                "proName": "OANDA:SPX500USD",
                                "title": "S&P 500"
                            },
                            {
                                "proName": "OANDA:NAS100USD",
                                "title": "Nasdaq 100"
                            },
                            {
                                "proName": "FX_IDC:EURUSD",
                                "title": "EUR/USD"
                            },
                            {
                                "proName": "BITSTAMP:BTCUSD",
                                "title": "BTC/USD"
                            },
                            {
                                "proName": "BITSTAMP:ETHUSD",
                                "title": "ETH/USD"
                            }
                        ],
                        "colorTheme": "light",
                        "isTransparent": false,
                        "displayMode": "regular",
                        "locale": "en"
                    }
                    </script>
                </div>
                <!-- TradingView Widget END -->
            </div>
            <div class="col-md-6 col-sm-12 col-12">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Card -->
                        <div class="dt-card">
                            <!-- Card Body -->
                            <div class="dt-card__body p-xl-8 py-sm-8 py-6 px-4 dt-card bg-secondary text-white">
                                <span class="badge badge-secondary badge-top-right"><?php echo lang("earnings") ?></span>
                                <!-- Media -->
                                <div class="media">
                                    <i class="icon icon-revenue-new icon-5x mr-xl-5 mr-3 align-self-center"></i>
                                    <!-- Media Body -->
                                    <div class="media-body">
                                        <p class="mb-1 h1 text-white">
                                            <?php if($withdrawals>$earningsInfo)
                                                    { 
                                                        echo to_currency($accountInfo);
                                                    } else 
                                                    {
                                                        echo to_currency($accountInfo);
                                                    } ?>
                                        </p>
                                        <span class="d-block"><?php echo lang("withdrawable") ?></span>
                                    </div>
                                    <!-- /media body -->
                                </div>
                                <!-- /media -->
                            </div>
                            <!-- /card body -->
                        </div>
                        <!-- /card -->
                    </div>
                    <div class="col-md-6">
                        <!-- Card -->
                        <div class="dt-card">
                            <!-- Card Body -->
                            <div class="dt-card__body p-xl-8 py-sm-8 py-6 px-4 dt-card bg-primary text-white">
                                <span class="badge badge-secondary badge-top-right"><?php echo lang("active_deposits") ?></span>
                                <!-- Media -->
                                <div class="media">
                                    <i class="icon icon-revenue-new icon-5x mr-xl-5 mr-3 align-self-center"></i>
                                    <!-- Media Body -->
                                    <div class="media-body">
                                        <p class="mb-1 h1 text-white">
                                            <?php echo $activeDeposits>0 ? to_currency($activeDeposits) : to_currency('0.00') ?>
                                        </p>
                                        <span class="d-block"><?php echo lang("locked") ?></span>
                                    </div>
                                    <!-- /media body -->
                                </div>
                                <!-- /media -->
                            </div>
                            <!-- /card body -->
                        </div>
                        <!-- /card -->
                    </div>
                    <div class="col-md-12">
                        <!-- Card -->
                        <div class="dt-card dt-card__full-height">
                            <!-- Card Header -->
                            <div class="dt-card__header">
                                <!-- Card Heading -->
                                <div class="dt-card__heading">
                                    <h3 class="dt-card__title"><?php echo lang("overall_portfolio") ?></h3>
                                </div>
                                <!-- /card heading -->
                            </div>
                            <!-- /card header -->
                            <!-- Card Body -->
                            <div class="dt-card__body d-flex justify-content-center align-items-center">
                                <!-- Chart -->
                                <canvas id="pie-chart" class="m--30p display-b" data-fill="27" height="130" width="130"></canvas>
                                <!-- /chart -->
                            </div>
                            <!-- /card body -->
                        </div>
                        <script src="<?php echo base_url('/assets/dist/js/Chart.min.js') ?>"></script>
                        <script>
                        var activedeposits = <?php echo $activeDeposits > 0 ? $activeDeposits : '0' ?>;
                        var inactivedeposits = <?php echo $inActiveDeposits > 0 ? $inActiveDeposits : '0' ?>;
                        var withdrawals = <?php
                        if ($withdrawals > $earningsInfo) {
                            echo $accountInfo;
                        } else {
                            echo $accountInfo;
                        } ?>;
                        new Chart(document.getElementById("pie-chart"), {
                            type: 'pie',
                            data: {
                                labels: ["Active", "Inactive", "Earnings"],
                                datasets: [{
                                    label: "Transactions",
                                    backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f"],
                                    data: [activedeposits, inactivedeposits, withdrawals]
                                }]
                            }
                        });
                        </script>
                        <!-- /card -->
                    </div>
                </div>


            </div>
            <div class="col-md-5 col-sm-6 col-12">
                <div class="row">
                    <div class="col-md-12">
                        <?php if(empty($userInfo->pmtAccount) OR $role == ROLE_ADMIN OR $role == ROLE_MANAGER) {?>
                        <div class="card bg-gradient-orange text-white">
                            <!-- Card Body -->
                            <div class="card-body p-6">
                                <p class="display-4 mb-1"><?php echo lang("setup_your_payment") ?></p>
                                <p class="f-14"><?php echo lang("please_add_mode_of_payment") ?>
                                </p>
                                <a class="btn btn-default btn-sm text-dark"
                                    href="<?php echo base_url('profile') ?>"><?php echo lang("setup") ?></a>
                            </div>
                            <!-- /card body -->
                        </div>
                        <?php } ?>
                        <?php if($companyInfo['sms_active'] == 1) {?>
                        <?php if(empty($userInfo->mobile) OR $role == ROLE_ADMIN OR $role == ROLE_MANAGER) {?>
                        <div class="card bg-gradient-orange text-white">
                            <!-- Card Body -->
                            <div class="card-body p-6">
                                <p class="display-4 mb-1"><?php echo lang("setup_your_phone") ?></p>
                                <p class="f-14"><?php echo lang("please_add_a_mobile_to_receive_sms") ?>
                                </p>
                                <a class="btn btn-default btn-sm text-dark"
                                    href="<?php echo base_url('profile') ?>"><?php echo lang("setup") ?></a>
                            </div>
                            <!-- /card body -->
                        </div>
                        <?php }} ?>
                        <div
                            class="dt-card dt-card bg-image-v6 bg-overlay overlay-opacity-0_8 bg-gradient-blue--after text-white">

                            <!-- Overlay Content -->
                            <div class="bg-overlay__inner mt-auto">

                                <!-- Card Body -->
                                <div class="dt-card__body">

                                    <h3 class="text-white mb-2"><?php echo lang("refer_earn") ?>
                                        <?php if($refType == 'multiple') { foreach($int_array as $int){ echo $int.'%'; } ?>
                                        <?php echo $intCount; } else { echo $first.'%'; ?> <?php echo lang("from_your_referrals_deposit") ?><?php }?></h3>

                                    <p><?php echo lang("referral_link") ?>: <?php echo base_url(); ?>signup/<?php echo $userInfo->refCode ?>
                                    </p>
                                    <form action="<?php echo base_url(); ?>invite" method="post" id="joinForm"
                                        name="joinForm">
                                        <?php echo form_open( base_url( 'invite' ) , array( 'id' => 'joinForm' ));?>
                                        <input class="btn btn-outline-light btn-block" name="email"
                                            placeholder="Enter Email Address">
                                        <button type="submit" id="invite" class="btn btn-info btn-block"><?php echo lang("invite_friends") ?></button>
                                        <?php echo form_close();?>

                                </div>
                                <!-- /card body -->

                            </div>
                            <!-- /overlay content -->

                        </div>
                        <!-- /card -->
                    </div>
                    <div class="col-md-12">
                        <div class="dt-card">

                            <!-- Card Header -->
                            <div class="dt-card__header mb-4">

                                <!-- Card Heading -->
                                <div class="dt-card__heading">
                                    <h3 class="dt-card__title text-capitalize"><?php echo lang("earnings_history") ?></h3>
                                </div>
                                <!-- /card heading -->

                                <!-- Card Tools -->
                                <div class="dt-card__tools">
                                    <a href="<?php echo base_url(); ?>earnings" class="dt-card__more"><?php echo lang("detailed_history") ?></a>
                                </div>
                                <!-- /card tools -->

                            </div>
                            <!-- /card header -->

                            <!-- Card Body -->
                            <div class="dt-card__body pb-5">

                                <!-- Tables -->
                                <div class="table-responsive ps-custom-scrollbar ps">
                                    <table class="table table-ordered table-bordered-0 mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase"><?php echo lang("transaction_code") ?></th>
                                                <th class="text-uppercase text-right" scope="col"><?php echo lang("amount") ?> (<?php echo $companyInfo['currency'] ?>)
                                                </th>
                                                <th class="text-uppercase text-center" scope="col"><?php echo lang("date") ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if(!empty($transactions))
                                            {
                                                foreach($transactions as $transaction){
                                            ?>
                                            <tr>
                                                <td><?php echo $transaction->txnCode ?></td>
                                                <td><?php echo to_currency($transaction->amount) ?></td>
                                                <td><?php echo date("d-m-Y", strtotime($transaction->createdDtm)) ?>
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
                </div>

                <?php } ?>

            </div>
            <!-- /grid item -->

        </div>
        <!-- /grid -->
    </div>
    <!-- /site content -->
    <script src="<?php echo base_url('/assets/dist/js/functions.js') ?>"></script>