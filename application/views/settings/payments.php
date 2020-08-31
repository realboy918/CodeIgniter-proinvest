<div class="dt-content-wrapper">
    <!-- Site Content -->
    <div class="dt-content">
        <!-- Profile -->
        <div class="profile">
            <!-- Profile Banner -->
            <div class="profile__banner">
                <!-- Page Header -->
                <div class="dt-page__header">
                    <h1 class="dt-page__title text-white display-i"><?php echo lang('payment_methods') ?></h1>
                    <button class="btn btn-light btn-sm display-i ft-right" data-toggle="modal" data-target="#paymentModalForm"><?php echo lang('add_payment_method') ?></button>
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
                                                    src="<?php echo base_url(); ?>uploads/<?php echo $method->logo; ?>"
                                                    alt="Stripe Img">

                                                <!-- Media Body -->
                                                <div class="media-body">
                                                    <div class="display-5 font-weight-600 mb-1 init-counter">
                                                        <?php echo $method->name ?></div>
                                                        <?php if($pageTitle == 'Payment Methods') {?>
                                                            <h2 id="methcol<?php echo $method->id ?>" class="<?php echo $method->status == "0" ? 'red' : 'green'  ?>"><?php echo $method->status == '0' ? lang('inactive') : lang('active'); ?></h2>
                                                        <?php } ?>
                                                        <?php echo form_open($pageTitle == 'Add-ons Settings' ? base_url( 'paymentAPIInfo' ) : base_url( 'paymentmethodInfo' ), array('class' => 'methodInfo'));?>
                                                        <input name="method" value="<?php echo $method->id ?>" hidden/>
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
        <!-- Modal -->
        <div class="modal fade display-n" id="paymentModalForm" tabindex="-1" role="dialog"
            aria-labelledby="model-8" aria-hidden="true">
            <?php echo form_open( base_url( 'addpaymentmethod' ) , array( 'id' => 'createMethod', 'class' => 'form-group', 'enctype=' => 'multipart/form-data' ));?>
            <div class="modal-dialog modal-dialog-centered" role="document">

                <!-- Modal Content -->
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h3 class="modal-title" id="model-8"><?php echo lang('add_payment_method') ?></h3>
                        <button type="button" class="close"
                            data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <!-- /modal header -->

                    <!-- Modal Body -->
                    <div class="modal-body">
                    <div class="form-group">
                        <label for="methodname"><?php echo lang('payment_type') ?></label>
                        <select class="form-control" name="ptype" id="ptype">
                            <option value="" selected disabled hidden><?php echo lang('choose_here') ?></option>
                            <option value="bank"><?php echo lang('bank') ?></option>
                            <option value="manual"><?php echo lang('manual') ?></option>
                            <option value="auto"><?php echo lang('automated') ?></option>
                        </select>
                    </div>
                    <div class="hide" id="baMeth">
                        <div class="form-group">
                            <label for="methodname"><?php echo lang('bank_name') ?></label>
                            <input type="text" value="" class="form-control" name="bankname" aria-describedby="methodname" placeholder="Bank Name">
                        </div>
                        <div class="form-group">
                            <label for="methodname"><?php echo lang('account_name') ?></label>
                            <input type="text" value="" class="form-control" name="acname" aria-describedby="methodname" placeholder="Account Name">
                        </div>
                        <div class="form-group">
                            <label for="methodname"><?php echo lang('account_number') ?></label>
                            <input type="text" value="" class="form-control" name="acnumber" aria-describedby="methodname" placeholder="1234567890">
                        </div>
                        <div class="form-group">
                            <label for="methodname"><?php echo lang('swift_code') ?></label>
                            <input type="text" value="" class="form-control" name="swiftcode" aria-describedby="methodname" placeholder="00000">
                        </div>

                        <div class="form-group">
                            <label for="logo"><?php echo lang('use_this_method_for_client_withdrawals') ?>?</label>

                            <div class="col-md-10 col-sm-9 ml--15p">

                                <!-- Radio Button -->
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="isbankwithdrawable" name="isnewwithdrawable" value="1" class="custom-control-input">
                                    <label class="custom-control-label" for="isbankwithdrawable"><?php echo lang('yes') ?></label>
                                </div>
                                <!-- /radio button -->

                                <!-- Radio Button -->
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="notbankwithdrawable" name="isnewwithdrawable" value="0" class="custom-control-input">
                                    <label class="custom-control-label" for="notbankwithdrawable"><?php echo lang('no') ?></label>
                                </div>
                                <!-- /radio button -->

                            </div>
                            <small id="checkHelp12" class="form-text"><?php echo lang('clients_will_be_able_to_see_this_method_as_a_withdrawal_method') ?></small>
                        </div>
                    </div>
                    <div class="hide" id="nbaMeth">
                        <div class="form-group hide" id="pAPI">
                            <label for="methodname"><?php echo lang('payment_API') ?></label>
                            <select class="form-control" name="api" id="pAPISelect">
                                <option value="" selected disabled><?php echo lang('choose_here') ?></option>
                                <?php foreach($paymentAPIs as $api){?>
                                    <option value="<?php echo $api->id ?>"><?php echo $api->name; ?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="form-group hide" id="pmName">
                            <label for="methodname" id="mthName"><?php echo lang('method_name') ?></label>
                            <input type="text" class="form-control" name="methodname" id="methodname" aria-describedby="methodname" placeholder="Payment method">
                            <select class="form-control hide" name="methodname1" id="methselect">
                                <optionvalue="" selected="" disabled="" hidden=""><?php echo lang('choose_here') ?></option>
                                <option value="Bitcoin">Bitcoin</option>
                                <option value="Dogecoin">Dogecoin</option>
                                <option value="Litecoin">Litecoin</option>
                            </select>
                        </div>

                        <div class="form-group hide" id="pmSym">
                            <label for="methodname"><?php echo lang('code') ?> </label>
                            <input type="text" value="" class="form-control" name="code" id="methodname" aria-describedby="methodname" placeholder="e.g. BTC">
                        </div>

                        <div class="form-group hide" id="pNote">
                            <label for="note"><?php echo lang('note_to_investor') ?></label>
                            <textarea name="note" class="form-control" id="note-1" rows="5" placeholder="Note to investor"></textarea>
                            <small id="checkHelp1" class="form-text">Use <code>&lt;br&gt;</code> to create a new line.</small>
                        </div>

                        <div class="form-group">
                            <label for="logo"><?php echo lang('use_this_method_for_client_withdrawals') ?>?</label>
                            
                            <div class="col-md-10 col-sm-9 ml--15p">

                                <!-- Radio Button -->
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="isnewwithdrawable" name="isnewwithdrawable" value="1" class="custom-control-input">
                                    <label class="custom-control-label" for="isnewwithdrawable"><?php echo lang('yes') ?>                                    </label>
                                </div>
                                <!-- /radio button -->

                                <!-- Radio Button -->
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="notnewwithdrawable" name="isnewwithdrawable" value="0" class="custom-control-input">
                                    <label class="custom-control-label" for="notnewwithdrawable"><?php echo lang('no') ?>                                    </label>
                                </div>
                                <!-- /radio button -->

                            </div>
                            <small id="checkHelp12" class="form-text"><?php echo lang('clients_will_be_able_to_see_this_method_as_a_withdrawal_method') ?></small>
                        </div>

                        <div class="form-group">
                            <label for="logo"><?php echo lang('logo') ?></label>
                            <input type="file" aria-describedby="logo" name="img" id="logo_upload" />
                            <input name="logo" hidden />
                        </div>
                    </div>
                    </div>
                    <!-- /modal body -->

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button"
                            class="btn btn-secondary btn-sm"
                            data-dismiss="modal"><?php echo lang('cancel') ?>
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm"><?php echo lang('save') ?>
                        </button>
                    </div>
                    <!-- /modal footer -->

                </div>
                <!-- /modal content -->

            </div>
            <?php echo form_close();?>
        </div>
        <!-- /modal -->
        <!-- /Profile -->
        <aside class="dt-customizer dt-drawer position-right">
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
                        <?php echo form_open( base_url( 'settings/paymentMethodUpdate' ) , array( 'id' => 'methodForm', 'class' => 'form-group', 'enctype' => 'multipart/form-data' ));?>  
                        <!-- Section -->
                        <section>
                            <img id="methImg" src="" alt="Logo" class="methImg mt-mb-25">
                            <div class="upload-btn-wrapper" style="margin-top: 3rem;margin-left: -1rem;">
                                <button class="dropdown-toggle no-arrow bg-transparent border-n">
                                    <i class="icon icon-file-upload icon-xl mr-2"></i>
                                    <span class="d-sm-inline-block"><?php echo lang('change_logo') ?></span>
                                </button>
                                <input type="file" name="img" id="imgInp">
                            </div>
                        </section>
                        <!-- /section -->          
                        <!-- Section -->
                        <section class="hide" id="paymenttypekey">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4><?php echo lang('payment_type') ?></h4>
                                </div>
                            </div>
                            <select class="form-control" name="ptype" id="edmethtype">
                                <option value="" selected disabled hidden><?php echo lang('choose_here') ?></option>
                                <option value="bank"><?php echo lang('bank') ?></option>
                                <option value="manual"><?php echo lang('manual') ?></option>
                                <option value="auto"><?php echo lang('automated') ?></option>
                            </select>
                        </section>
                        <!-- /section -->  
                        <!-- Section -->
                        <section class="hide" id="methnamekey">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4><?php echo lang('method_name') ?></h4>
                                </div>
                            </div>
                            <input class="form-control" type="text" id="edmethname" name="methodname" value="" placeholder="Name">
                        </section>
                        <!-- /section -->     
                        <!-- Section -->
                        <section class="hide" id="paymentAPIkey">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4><?php echo lang('payment_API') ?></h4>
                                </div>
                            </div>
                            <select class="form-control" name="api" id="edAPISelect">
                                <?php foreach($paymentAPIs as $api){?>
                                    <option value="<?php echo $api->id ?>"><?php echo $api->name; ?></option>
                                <?php }?>
                            </select>
                        </section>
                        <!-- /section --> 
                        <!-- Section -->
                        <section class="hide" id="notekey">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4><?php echo lang('note_to_investor') ?></h4>
                                </div>
                            </div>
                            <textarea name="note" class="form-control" id="edmethnote" rows="5" placeholder="Note to investor"></textarea>
                            <small id="checkHelp1" class="form-text">Use <code>&lt;br&gt;</code> to create a new line.</small>
                        </section>
                        <!-- /section --> 
                        <!-- Section -->
                        <section class="hide" id="codekey">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4><?php echo lang('code') ?></h4>
                                </div>
                            </div>
                            <input type="text" name="code" class="form-control" id="codename" aria-describedby="bname" placeholder="Code">
                        </section>
                        <!-- /section -->    
                        <!-- Section -->
                        <section class="hide" id="banknamekey">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4><?php echo lang('bank_name') ?></h4>
                                </div>
                            </div>
                            <input type="text" name="bname" class="form-control" id="bname" aria-describedby="bname" placeholder="Bank Name">
                        </section>
                        <!-- /section -->    
                        <!-- Section -->
                        <section class="hide" id="acnamekey">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4><?php echo lang('account_name') ?></h4>
                                </div>
                            </div>
                            <input type="text" name="acname" class="form-control" id="acname" aria-describedby="acname" placeholder="Account Name">
                        </section>
                        <!-- /section -->     
                        <!-- Section -->
                        <section class="hide" id="acnumberkey">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4><?php echo lang('account_number') ?></h4>
                                </div>
                            </div>
                            <input type="text" name="acnumber" class="form-control" id="acnumber" aria-describedby="acnumber" placeholder="Account Number">
                        </section>
                        <!-- /section -->   
                        <!-- Section -->
                        <section class="hide" id="swiftcodekey">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4><?php echo lang('swift_code') ?></h4>
                                </div>
                            </div>
                            <input type="text" name="swcode" class="form-control" id="swcode" aria-describedby="swcode" placeholder="Swift Code">
                        </section>
                        <!-- /section -->   
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
                        <!-- Section -->
                        <section class="d-lg-block">
                            <h4 id="statusTitle"><?php echo lang('allow_withdrawals') ?>?</h4>

                            <div class="col-md-10 col-sm-9 ml--15p">

                                <!-- Radio Button -->
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="iswithdrawable" name="iswithdrawable" value="1" class="custom-control-input">
                                    <label class="custom-control-label" for="iswithdrawable"><?php echo lang('yes') ?>
                                    </label>
                                </div>
                                <!-- /radio button -->

                                <!-- Radio Button -->
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="notwithdrawable" name="iswithdrawable" value="0" class="custom-control-input">
                                    <label class="custom-control-label" for="notwithdrawable"><?php echo lang('no') ?>
                                    </label>
                                </div>
                                <!-- /radio button -->

                            </div>

                        </section>
                        <!-- /section -->
                        <input class="hide" name="method" id="methID" hidden/>
                        <div class="row">
                            <div class="col-md-6">
                                <button data-url="" class="btn btn-danger w-100" name="delete" id="methDelete"><?php echo lang('delete') ?></button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-info w-100" name="save" id="methSave"><?php echo lang('save') ?></button>
                            </div>
                        </div>
                        <?php echo form_close();?>
                    </div>
                    <!-- /customizer body inner -->
                </div>
                <!-- /customizer body -->

            </div>
        </aside>

    </div>
    <script src="<?php echo base_url('/assets/dist/js/payments.js') ?>"></script>
    <script>  
    function readURL(input) {
        if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            $('#methImg').attr('src', e.target.result);
        }
        
        reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imgInp").change(function() {
        readURL(this);
    });   
    </script>
    <script>
        $('#methDelete').click(function(e){
            e.preventDefault();
            var actionurl = $(this).attr('data-url');
            $.ajax({
                url: actionurl,
                type: 'get',
                success: function(result){
                    var content = JSON.parse(result);
                    $("input[name="+content.csrfTokenName+"]").val(content.csrfHash);

                    swal(
                        content.success == true ? 'Success!' : 'Error!',
                        content.msg,
                        content.success == true ? 'success' : 'error'
                    );
                },
                error: function(result){}
            })
        })
        $('#ptype').on('change', function() {
            var val = $('#ptype option').filter(":selected").val();
            if(val == 'auto')
            {
                $('#pAPI').removeClass('hide');
                $('#pNote').addClass('hide');
                $('#pmName').removeClass('hide');
                $('#baMeth').addClass('hide');
                $('#nbaMeth').removeClass('hide');
            } else if(val == 'manual')
            {
                $('#pAPI').addClass('hide');
                $('#pNote').removeClass('hide');
                $('#pmName').removeClass('hide');
                $('#methodname').attr('disabled', false);
                $('#pAPISelect').val([]);
                $('#methodname').val('');
                $('#pmSym').addClass('hide');
                $('#baMeth').addClass('hide');
                $('#nbaMeth').removeClass('hide');
                $('#mthName').html('Method Name');
                $('#methselect').addClass('hide');
            } else if(val == 'bank')
            {
                $('#baMeth').removeClass('hide');
                $('#nbaMeth').addClass('hide');
            }
        });
        $('#pAPISelect').on('change', function(){
            var val = $('#pAPISelect').find(":selected").text();
            if(val == 'CoinPayments')
            {
                $('#pmSym').removeClass('hide');
                $('#methodname').attr('disabled', false).val('').removeClass('hide');
                $('#pmName').removeClass('hide');
                $('#methselect').addClass('hide');
                $('#mthName').html('Coin Name');
                $('#methodname').attr('name', 'methodname');
                $('#methselect').attr('name', 'methodname1');
            } else if(val == 'Payeer')
            {
                $('#methodname').attr('name', 'methodname');
                $('#methodname').val('Payeer').attr('disabled', true).removeClass('hide');
                $('#pmSym').addClass('hide');
                $('#pmName').removeClass('hide');
                $('#methselect').addClass('hide');
                $('#mthName').html('Method Name');
                $('#methselect').attr('name', 'methodname1');
            } else if(val == 'PayPal')
            {
                $('#methodname').val('PayPal').attr('disabled', true).removeClass('hide');
                $('#pmSym').addClass('hide');
                $('#pmName').removeClass('hide');
                $('#methselect').addClass('hide');
                $('#mthName').html('Method Name');
                $('#methodname').attr('name', 'methodname');
                $('#methselect').attr('name', 'methodname1');
            } else if(val == 'Paystack')
            {
                $('#methodname').val('Paystack').attr('disabled', true).removeClass('hide');
                $('#pmSym').addClass('hide');
                $('#pmName').removeClass('hide');
                $('#methselect').addClass('hide');
                $('#mthName').html('Method Name');
                $('#methodname').attr('name', 'methodname');
                $('#methselect').attr('name', 'methodname1');
            } else if(val == 'Monnify')
            {
                $('#methodname').val('Monnify').attr('disabled', true).removeClass('hide');
                $('#pmSym').addClass('hide');
                $('#pmName').removeClass('hide');
                $('#methselect').addClass('hide');
                $('#mthName').html('Method Name');
                $('#methodname').attr('name', 'methodname');
                $('#methselect').attr('name', 'methodname1');
            } else if(val == 'Perfect Money')
            {
                $('#methodname').val('Perfect Money').attr('disabled', true).removeClass('hide');
                $('#pmSym').addClass('hide');
                $('#pmName').removeClass('hide');
                $('#methselect').addClass('hide');
                $('#mthName').html('Method Name');
                $('#methodname').attr('name', 'methodname');
                $('#methselect').attr('name', 'methodname1');
            } else if(val == 'Flutterwave')
            {
                $('#methodname').val('Flutterwave').attr('disabled', true).removeClass('hide');
                $('#pmSym').addClass('hide');
                $('#pmName').removeClass('hide');
                $('#methselect').addClass('hide');
                $('#mthName').html('Method Name');
                $('#methodname').attr('name', 'methodname');
                $('#methselect').attr('name', 'methodname1');
            } else if(val == 'Stripe')
            {
                $('#methodname').val('').attr('disabled', false).removeClass('hide');
                $('#pmSym').addClass('hide');
                $('#methselect').addClass('hide');
                $('#mthName').html('Method Name');
                $('#methodname').attr('name', 'methodname');
                $('#methselect').attr('name', 'methodname1');
            } else if(val == 'Block.io')
            {
                $('#methodname').val('');
                $('#methodname').attr('disabled', false);
                $('#methodname').addClass('hide');
                $('#pmSym').addClass('hide');
                $('#methselect').removeClass('hide');
                $('#mthName').html('Method Name');
                $('#methodname').attr('name', 'methodname1');
                $('#methselect').attr('name', 'methodname');
            }
        })
        $("#createMethod").submit(function(e) {
            e.preventDefault();
            var actionurl = e.currentTarget.action;
            var form = document.getElementById("createMethod");
            var formData = new FormData(form)
            $('.error').hide();
            $('.form-control').removeClass('inputTxtError');
            $.ajax({
                url:actionurl,
                type:"POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(result) {
                    var content = JSON.parse(result);
                    $("input[name="+content.csrfTokenName+"]").val(content.csrfHash);

                    if (content.success == false) {
                        $.each(content.errors, function(key, value) {
                            var msg = '<label class="error" for="' + key + '">' + value +
                                '</label>';
                            $('input[name="' + key + '"], select[name="' + key + '"]').addClass(
                                'inputTxtError').after(msg);
                        });
                    }

                    swal(
                        content.success == true ? 'Success!' : 'Error!',
                        content.msg,
                        content.success == true ? 'success' : 'error'
                    );

                    if(content.success == true)
                    {
                        $('#paymentModalForm').modal('toggle');
                        $('#createMethod').trigger("reset");
                    }
                },
                error: function(result) {
                    $('#paymentModalForm').modal('toggle');
                    swal(
                        'Error!',
                        'There is an issue in processing your request. Please try again later',
                        'error'
                    );
                }
            })
        })
        $(".methodInfo").submit(function(e) {
            e.preventDefault();
            $('.dt-drawer').addClass('open');
            $('#sideContent').hide();
            $('#loader').show();
            $('.dt-customizer__body').scrollTop(0);
            var actionurl = e.currentTarget.action;
            $.ajax({
                type: "POST",
                url: actionurl,
                data: $(this).serialize(),
                success: function(result) {
                    var content = JSON.parse(result);
                    $("input[name="+content.csrfTokenName+"]").val(content.csrfHash);
                    $("#methImg").attr('src', content.logo);
                    $("#methDelete").attr("data-url", '../deletepaymentmethod/' + content.id);

                    $('#edmethname').val(content.name);
                    $('#edmethtype').val(content.type);
                    $('#edmethnote').val(content.note);
                    $('#edAPISelect').val(content.API);
                    $('#codename').val(content.ref);
                    $('#bname').val(content.bname);
                    $('#acname').val(content.acname);
                    $('#acnumber').val(content.acnumber);
                    $('#swcode').val(content.swcode);
                    $('#methID').val(content.id);
                    $("#method-header").text(content.name + " Settings");

                    if(content.type == 'bank')
                    {
                        $('#paymenttypekey').removeClass('hide');
                        $('#banknamekey').removeClass('hide');
                        $('#acnamekey').removeClass('hide');
                        $('#acnumberkey').removeClass('hide');
                        $('#swiftcodekey').removeClass('hide');
                        $('#methnamekey').addClass('hide');
                        $('#notekey').addClass('hide');
                        $('#codekey').addClass('hide');
                        $('#paymentAPIkey').addClass('hide');
                    } else if(content.type == 'manual')
                    {
                        $('#paymenttypekey').addClass('hide');
                        $('#banknamekey').addClass('hide');
                        $('#acnamekey').addClass('hide');
                        $('#acnumberkey').addClass('hide');
                        $('#swiftcodekey').addClass('hide');
                        $('#paymenttypekey').removeClass('hide');
                        $('#methnamekey').removeClass('hide');
                        $('#notekey').removeClass('hide');
                        $('#codekey').addClass('hide');
                        $('#paymentAPIkey').addClass('hide');
                    } else if(content.type == 'auto')
                    {
                        $('#paymenttypekey').removeClass('hide');
                        $('#paymentAPIkey').removeClass('hide');
                        $('#methnamekey').removeClass('hide');
                        $('#banknamekey').addClass('hide');
                        $('#acnamekey').addClass('hide');
                        $('#acnumberkey').addClass('hide');
                        $('#swiftcodekey').addClass('hide');
                        $('#notekey').addClass('hide');

                        if(content.APIname == 'CoinPayments')
                        {
                            $('#codekey').removeClass('hide');
                        } else
                        {
                            $('#codekey').addClass('hide');
                        }
                    }
                    content.iswithdrawable == 1 ? $("#iswithdrawable").prop("checked", true) : $("#notwithdrawable").prop(
                "checked", true);
                    content.status == 1 ? $("#active").prop("checked", true) : $("#inactive").prop("checked", true);
                    setTimeout(function () {
                        $('#sideContent').show();
                        $('#loader').hide();
                    }, 2000); 
                },
                error: function(result) {}
            })
        })
        $('#edAPISelect').on('change', function(){
            var val = $('#edAPISelect').find(":selected").text();

            if(val == 'CoinPayments')
            {
                $('#codekey').removeClass('hide');
            } else
            {
                $('#codekey').addClass('hide');
            } 
        })
        $('#edmethtype').on('change', function(){
            var val = $('#edmethtype option').filter(":selected").val();
            var API = $('#edAPISelect').find(":selected").text();

            if(val == 'auto')
            {
                $('#paymentAPIkey').removeClass('hide');
                $('#methnamekey').removeClass('hide');  
                $('#notekey').addClass('hide');

                if(API == 'CoinPayments')
                {
                    $('#codekey').removeClass('hide');
                }

                $('#acnamekey').addClass('hide');
                $('#acnumberkey').addClass('hide');
                $('#banknamekey').addClass('hide');
                $('#swiftcodekey').addClass('hide');
            } else if(val == 'manual')
            {
                $('#notekey').removeClass('hide');
                $('#methnamekey').removeClass('hide');
                $('#paymentAPIkey').addClass('hide');
                $('#codekey').addClass('hide');

                $('#acnamekey').addClass('hide');
                $('#acnumberkey').addClass('hide');
                $('#banknamekey').addClass('hide');
                $('#swiftcodekey').addClass('hide');
            } else if(val == 'bank')
            {
                $('#paymentAPIkey').addClass('hide');
                $('#methnamekey').addClass('hide');
                $('#codekey').addClass('hide');
                $('#notekey').addClass('hide');

                $('#acnamekey').removeClass('hide');
                $('#acnumberkey').removeClass('hide');
                $('#banknamekey').removeClass('hide');
                $('#swiftcodekey').removeClass('hide');
            }
        })
    </script>