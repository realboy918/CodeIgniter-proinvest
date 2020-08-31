<div class="dt-content-wrapper">
    <!-- Site Content -->
    <div class="dt-content">
        <!-- Profile -->
        <div class="profile">
            <!-- Profile Banner -->
            <div class="profile__banner">
                <!-- Page Header -->
                <div class="dt-page__header">
                    <h1 class="dt-page__title text-white display-i"><?php echo $pageTitle ?></h1>
                    <div class="dt-entry__header mt-1m">
                        <!-- Entry Heading -->
                        <div class="dt-entry__heading">
                        </div>
                        <!-- /entry heading -->
                    </div>
                </div>
                <!-- /page header -->
                <div class="profile__banner-detail">
                    <div class="col-12 m-n3">
                    <?php echo form_open(base_url( 'wallet/transactions' ) , array( 'id' => 'filter' ));?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-2">
                                        <p class="pt-sm-3">From</p>
                                    </div>
                                    <div class="col-md-10">
                                        <input class="form-control" name="start-date" placeholder="01-01-1970">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row">
                                    <div class="col-md-2">
                                        <p class="pt-sm-3">To</p>
                                    </div>
                                    <div class="col-md-10">
                                        <input class="form-control" name="end-date" placeholder="01-01-1970">
                                    </div>
                                </div>
                            </div>
                            <div class="cold-md-3">
                                <button class="btn btn-light btn-sm display-i ft-right">Filter</button>
                            </div>
                        </div>
                    <?php echo form_close();?>
                    </div>
                </div>
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
                            <div class="dt-card__body">
                            <!-- Card Body -->
                                <!-- Tables -->
                                <div class="table-responsive dataTables_wrapper dt-bootstrap4">
                                    <div class="table-responsive">
                                         <table class="table table-striped mb-0">
                                            <thead class="thead-light">
                                                <tr role="row">
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Created On</th>
                                                    <th>Status</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($verifications as $verification) { ?>
                                                    <tr>
                                                        <td><?php echo $verification->firstName.' '.$verification->lastName; ?></td>
                                                        <td><?php echo $verification->email ?></td>
                                                        <td><?php echo $verification->modifiedDtm ?></td>
                                                        <td class="text-capitalize">
                                                            <?php echo $verification->verification_status?>
                                                        </td>
                                                        <td>
                                                            <?php if($verification->verification_status == 'pending') {?>
                                                                <p class="text-capitalize">Pending submission</p>
                                                            <?php } else if($verification->verification_status == 'resubmit') {?>
                                                                <p class="text-capitalize">Pending resubmission</p>
                                                            <?php } else if($verification->verification_status == 'submitted'){?>
                                                                <a href="<?php echo base_url('verification/approval/').$verification->id ?>" class="btn btn-sm btn-info trans-btn approve-btn" title="Approve">Approve</a>
                                                            <?php } else if($verification->verification_status == 'rejected' || $verification->verification_status == 'approved') {?>
                                                                <a href="<?php echo base_url('verification/approval/').$verification->id ?>" class="btn btn-sm btn-info trans-btn approve-btn" title="view">View</a>
                                                            <?php }?>
                                                        </td>
                                                    </tr>
                                                <?php }?>
                                            </tbody>
                                        </table>
                                        <?php echo $this->pagination->create_links(); ?>
                                    </div>
                                    <!-- /tables -->

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