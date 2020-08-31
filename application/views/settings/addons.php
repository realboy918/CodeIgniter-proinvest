<div class="dt-content-wrapper">
    <!-- Site Content -->
    <div class="dt-content">
        <!-- Profile -->
        <div class="profile">
            <!-- Profile Banner -->
            <div class="profile__banner">
                <!-- Page Header -->
                <div class="dt-page__header">
                    <h1 class="dt-page__title text-white display-i"><?php echo lang('plugins') ?></h1>
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
                    <div class="col-xl-12 col-12 order-xl-1">
                        <!-- Card -->
                        <div class="col-12">
                            <div class="row">

                                <?php if(!empty($paymentMethods)) {
                                foreach($paymentMethods as $method) {?>
                                <!-- Grid Item -->
                                <div class="col-sm-4 col-12">

                                    <!-- Card -->
                                    <div class="dt-card dt-card__full-height text-dark">

                                        <!-- Card Body -->
                                        <div class="dt-card__body p-xl-8 py-sm-8 py-6 px-4">
                                            <!-- Media -->
                                            <div class="media">
                                                <img class="icon mr-6 align-self-center mt--20p mx-w-100p mh-100p"
                                                    src="<?php echo base_url(); ?>assets/dist/img/<?php echo $method->logo; ?>"
                                                    alt="<?php echo $method->name.' Img'; ?>">

                                                <!-- Media Body -->
                                                <div class="media-body">
                                                    <div class="display-5 font-weight-600 mb-1 init-counter">
                                                        <?php echo $method->name ?></div>
                                                        <?php if($pageTitle == 'Payment Methods') {?>
                                                            <h2 id="methcol<?php echo $method->id ?>" class="<?php echo $method->status == "0" ? 'red' : 'green'  ?>"><?php echo $method->status == '0' ? lang('inactive') : lang('active'); ?></h2>
                                                        <?php } ?>
                                                        <?php echo form_open($pageTitle == 'Add-ons Settings' ? base_url( 'paymentAPIInfo' ) : base_url( 'paymentmethodInfo' ), array('class' => 'methodFormButton'));?>
                                                        <input name="method" value="<?php echo $method->name ?>" hidden/>
                                                        <button type="submit" class="btn btn-info method-button"><?php echo lang('update') ?></button>
                                                        <?php echo form_close();?>
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
                                <?php } ?>
                                <div class="col-xl-12 col-12">
                                    <div class="row">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4"><?php echo $this->pagination->create_links(); ?></div>
                                        <div class="col-md-4"></div>
                                    </div>
                                </div>
                                <?php } else { ?>
                                <div class="col-xl-12 col-12">
                                    <div class="text-center mt-5">
                                        <img src="<?php echo base_url('assets/dist/img/no-search-results.png') ?>" class="w-20rm">
                                        <h1><?php echo lang('no_records_found') ?></h1>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
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
        <aside class="dt-customizer dt-drawer right-drawer position-right">
            <div class="dt-customizer__inner">

                <!-- Customizer Header -->
                <div class="dt-customizer__header">

                    <!-- Customizer Title -->
                    <div class="dt-customizer__title">
                        <h3 class="mb-0" id="method-header"></h3>
                    </div>
                    <!-- /customizer title -->

                    <!-- Close Button -->
                    <button type="button" class="close" data-toggle="customizer">
                        <span aria-hidden="true">×</span>
                    </button>
                    <!-- /close button -->

                </div>
                <!-- /customizer header -->

                <!-- Customizer Body -->
                <div class="dt-customizer__body ps-custom-scrollbar ps ps--active-y">
                <div class="loader" id="loader"></div>
                    <!-- Customizer Body Inner  -->
                    <div class="dt-customizer__body-inner" id="sideContent">
                        <!-- Section -->
                        <section>
                            <img id="methImg" src="" alt="Logo" class="methImg mt-mb-25">
                        </section>
                        <!-- /section -->
                        <?php echo form_open(base_url( 'settings/addonAPIUpdate' ) , array( 'id' => 'methodForm', 'class' => 'form-group' ));?>
                            <?php if($pageTitle == 'Add-ons Settings') { ?>
                            <!-- Section -->
                            <section id="publicKey">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4 id="public_key_title"><?php echo lang('public_key') ?></h4>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="reveal" id="reveal_pKey"><?php echo lang('show') ?></p>
                                    </div>
                                </div>
                                <input class="form-control" type="password" id="pKey" name="pKey" value=""
                                    placeholder="Public Key" />
                            </section>
                            <!-- /section -->

                            <!-- Section -->
                            <section id="secret-key">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4 id="secret_key_title"><?php echo lang('secret_key') ?></h4>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="reveal" id="reveal_sKey"><?php echo lang('show') ?></p>
                                    </div>
                                </div>
                                <input class="form-control" id="sKey" type="password" name="sKey" value=""
                                    placeholder="Secret Key" />

                            </section>
                            <!-- /section -->

                            <!-- Section -->
                            <section id="merchName" class="hide">
                                <h4>Merchant Name</h4>
                                <input class="form-control" type="text" id="merchantName" name="merchantName" value=""
                                    placeholder="Merchant Name" />
                            </section>
                            <!-- /section -->
                            <!-- Section -->
                            <section id="merchID" class="hide">
                                <h4 id="merchIDTitle">Merchant ID</h4>
                                <input class="form-control" type="text" id="merchantID1" name="merchantID1" value=""
                                    placeholder="Merchant ID" />
                            </section>
                            <!-- /section -->
                            <!-- Section -->
                            <section id="urlID" class="hide">
                                <h4>URL</h4>
                                <input class="form-control" type="text" id="btcpayurlid" name="btcpayurl" value=""
                                    placeholder="https://mainnet.demo.btcpayserver.org/" />
                            </section>
                            <!-- /section -->

                            <!-- Section -->
                            <section id="payee_account" class="hide">
                                <h4 id="payee_account_header">Payee Account</h4>
                                <input class="form-control" type="text" id="payee_account_inp" name="payee_account" value=""
                                    placeholder="Payee Account" />
                            </section>
                            <!-- /section -->

                            <!-- Section -->
                            <section id="payee_name" class="hide">
                                <h4 id="payee_name_header">Payee Name</h4>
                                <input class="form-control" type="text" id="payee_name_inp" name="payee_name" value=""
                                    placeholder="Payee Name" />
                            </section>
                            <!-- /section -->

                            <section class="hide" id="g-version">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4 id="secret_key_title">Recaptcha Version</h4>
                                    </div>
                                </div>
                                <select class="form-control" value="" name="version" id="version-select">
                                    <option id="v2" value="v2">v2 Checkbox</option>
                                    <option id="v3" value="v3">v3</option>
                                </select>
                            </section>
                            <!-- Section -->
                            <section id="contractCodeID" class="hide">
                                <h4 id="contract-code-title">Contract Code</h4>
                                <input class="form-control" type="text" id="contractCodeID1" name="contractcode" value=""
                                    placeholder="Contract Code" />
                            </section>
                            <!-- /section -->
                            <section class="hide" id="mode">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4 id="secret_key_title">Mode</h4>
                                    </div>
                                </div>
                                <select class="form-control" value="" name="mode" id="mode-select">
                                    <option id="sandbox" value="sandbox">Sandbox</option>
                                    <option id="live" value="live">Live</option>
                                </select>
                            </section>
                            <!-- Section -->
                            <div class="hide" id="IPN_fields">
                            <section class="d-lg-block" id="IPNKey_field">
                                <h4>Merchant ID</h4>
                                <input class="form-control" id="merchantID" type="text" name="merchantID" value=""
                                    placeholder="Merchant ID" />

                            </section>
                            <section class="d-lg-block" id="IPNKey_field">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4>IPN Key</h4>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="reveal" id="reveal_iKey"><?php echo lang('show') ?></p>
                                    </div>
                                </div>
                                <input class="form-control" id="IPNKey" type="password" name="IPNKey" value=""
                                    placeholder="IPN Key" />

                            </section>
                            <!-- /section -->
                            <!-- Section -->
                            <section class="d-lg-block" id="IPNURL_field">
                                <h4>IPN URL</h4>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="padding: 10px;">MyURL/ipncp/</span>
                                    </div>
                                    <input type="text" id="IPNURL" class="form-control " name="IPNURL" placeholder="IPN URL" aria-label="IPNURL" onkeyup="getURL()">
                                </div>
                                <small id="checkHelp1" class="form-text"><?php echo base_url() ?>ipncp/<d id="typedURL"></d></small>
                            </section>
                            </div>
                            <!-- /section -->
                            <?php } ?>
                            
                            <!-- Section -->
                            <section class="d-lg-block" id="layout-chooser">
                                <h4 id="statusTitle"><?php echo lang('status') ?></h4>

                                <div class="col-md-10 col-sm-9 ml--15p">

                                    <!-- Radio Button -->
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="active" name="status" value="1" class="custom-control-input">
                                        <label class="custom-control-label" for="active"><?php echo lang('active') ?>
                                        </label>
                                    </div>
                                    <!-- /radio button -->

                                    <!-- Radio Button -->
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="inactive" name="status" value="0" class="custom-control-input">
                                        <label class="custom-control-label" for="inactive"><?php echo lang('inactive') ?>
                                        </label>
                                    </div>
                                    <!-- /radio button -->

                                </div>

                            </section>
                            <!-- /section -->
                            <input class="hide" name="method" id="methID" hidden/>
                            <button class="btn btn-info w-100" name="save" id="methSave"><?php echo lang('save') ?></button>
                        <?php echo form_close();?>
                    </div>
                    <!-- /customizer body inner -->
                </div>
                <!-- /customizer body -->

            </div>
        </aside>

    </div>
    <script src="<?php echo base_url('/assets/dist/js/payments.js') ?>"></script>