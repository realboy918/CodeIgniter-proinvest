<div class="dt-content-wrapper">

    <!-- Site Content -->
    <div class="dt-content">
        <!-- Profile -->
        <div class="profile">

            <!-- Profile Banner -->
            <div class="profile__banner">

                <!-- Page Header -->
                <div class="dt-page__header">
                    <h1 class="dt-page__title text-light display-i"><?php echo lang('deposits') ?> / <?php echo lang('new') ?></h1>
                    <a href="" class="btn btn-light btn-sm display-i ft-right"><?php echo lang('back') ?></a>

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
                        <!-- Grid -->
                <div class="row">
                    <!-- Grid Item --> 
                    <div class="col-xl-6 col-md-6 col-12 order-xl-1">
                    </div>
                    <!-- Grid Item --> 
                    <div class="col-xl-6 col-md-6 col-12 order-xl-1">
                        <!-- Card -->
                        <div class="dt-card">
                            <!-- Card Body -->
                            <div class="dt-card__body p-xl-8 py-sm-8 py-6 px-4">
                                <span class="badge badge-secondary badge-top-right">
                                <?php echo lang('make_a_payment') ?></span>
                                <!-- Media -->
                                <div class="media">
                                    <!-- Media Body -->
                                    <div class="media-body" style="color: grey;">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <i class="icon icon-revenue-new icon-6x mr-6 align-self-center"></i>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="display-3 font-weight-600 mt-1 init-counter">
                                                <?php echo lang('pay') ?> <?php echo to_currency($payment); ?> 
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
                        <!-- /grid item -->
                    </div>
                    <!-- /grid -->
                </div>
                <!-- /profile content -->
                        
                    </div>
                    <!-- /avatar wrapper -->
                </div>

            </div>
            <!-- /profile banner -->

            <!-- Profile Content -->
            <div class="profile-content marg-t-17 marg-t-0 ">
            <div class="row">
                <!-- Grid Item -->
                <div class="col-sm-6 col-12">
                    <!-- Card -->
                    <div class="dt-card dt-card__full-height text-dark hide">

                        <!-- Card Body -->
                        <div class="dt-card__body p-xl-8 py-sm-8 py-6 px-4">
                            <span class="badge badge-secondary badge-top-right">
                            <?php echo lang('make_a_payment') ?></span>
                            <!-- Media -->
                            <div class="media">

                                <i class="icon icon-revenue-new icon-6x mr-6 align-self-center"></i>

                                <!-- Media Body -->
                                <div class="media-body">
                                    <div class="display-3 font-weight-600 mb-1 init-counter" style="font-size: 2.5rem;">
                                    <?php echo $account.' '.lang('account') ?> 
                                    </div>
                                    <div>
                                        <?php echo $note ?>
                                    </div>
                                    <br>
                                    <?php echo form_open(base_url('add_manual_transfer'), array('id' => 'submitForm', 'enctype' => 'multipart/form-data' ));?>
                                        <div class="form-group">
                                            <label>Enter account you've sent the deposit from</label>
                                            <input type="text" class="form-control" id="accountId" name="account">
                                        </div>
                                        <button type="submit" class="btn btn-primary text-uppercase">Confirm Deposit and Proceed</button>
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
            </div>
                
            </div>
            <!-- /Profile -->
        </div>
        <script>
            $('#submitForm').on('submit', function(e){
                e.preventDefault();
                var actionurl = e.currentTarget.action;
                $.ajax({
                url:actionurl,
                method:"POST",
                data:new FormData(this),
                contentType: false,
                cache: false,
                
                processData: false,
                success:function(data)
                {
                    var content = JSON.parse(data);
                    swal(
                        content.success == true ? 'Success!' : 'Error!',
                        content.msg,
                        content.success == true ? 'success' : 'error'
                    );

                    if(content.success == true){
                        window.setTimeout(function(){
                            window.location.replace("./deposits");
                        }, 2000)
                    }
                },
                error: function(data) {
                    swal(
                        'Error!',
                        'There is an issue in updating your account. Please refresh the page.',
                        'error'
                    );
                }
                })
            })
        </script>
