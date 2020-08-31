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
                    <?php if($pageTitle == "Clients"){
                        echo lang('clients');
                    } else if ($pageTitle == "Team") {
                        echo lang('team');
                    }?>
                    </h1>

                    <?php if($pageTitle == "Clients"){ ?>
                    <!-- Check the access for this component -->
                    <?php if($this->user_model->getPermissions('clients', 'add', $userId) OR $role == ROLE_ADMIN) {?>
                    <a href="<?php echo base_url(); ?>clients/newClient" class="btn btn-light btn-sm display-i ft-right"><?php echo lang('add_client') ?></a>
                    <?php }?>
                    <?php } else if ($pageTitle == "Team") { ?>
                    <!-- Check the access for this component -->
                    <?php if($this->user_model->getPermissions('teams', 'add', $userId) OR $role == ROLE_ADMIN) {?>
                    <a href="<?php echo base_url(); ?>team/newManager" class="btn btn-light btn-sm display-i ft-right"><?php echo lang('add_manager') ?></a>
                    <?php }?>
                    <?php } ?>

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
                                            <?php if($pageTitle == "Clients") {?>
                                                <?php echo lang('total_clients') ?>
                                            <?php } else if($pageTitle == "Team") { ?>
                                                <?php echo lang('team_size') ?>
                                            <?php } ?>
                                        </span>
                                        <!-- Media -->
                                        <div class="media">

                                            <i class="icon icon-users icon-6x mr-6 align-self-center"></i>

                                            <!-- Media Body -->
                                            <div class="media-body">
                                                <div class="display-3 font-weight-600 mb-1 init-counter">
                                                    <?php echo $allUsers; ?>
                                                </div>
                                                <span class="d-block">
                                                    <?php if($pageTitle == "Clients") {?>
                                                        <?php echo lang('registered_users') ?>
                                                    <?php } else if($pageTitle == "Team") { ?>
                                                        <?php echo lang('team_members') ?>
                                                    <?php } ?>
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
                                            <?php if($pageTitle == "Clients") {?>
                                                <?php echo lang('new_users_since_last_week') ?>
                                            <?php } else if($pageTitle == "Team") { ?>
                                                <?php echo lang('last_login') ?>
                                            <?php } ?>
                                        </span>
                                        <!-- Media -->
                                        <div class="media">

                                            <i class="icon icon-users icon-6x mr-6 align-self-center"></i>

                                            <!-- Media Body -->
                                            <div class="media-body">
                                                <div class="display-3 font-weight-600 mb-1 init-counter">
                                                    <?php if($pageTitle == "Clients") {
                                                     echo $clientsThisWeek;  }
                                                    else if($pageTitle == "Team") { 
                                                    echo date('Y-m-d', $lastLogin->timestamp) ;
                                                    } ?>
                                                </div>
                                                <span class="d-block">
                                                    <?php if($pageTitle == "Clients") {?>
                                                        <?php echo lang('new_users_since_last_week') ?>
                                                    <?php } else if($pageTitle == "Team") { ?>
                                                        <?php echo lang('last_login') ?>
                                                    <?php } ?>
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
                                        <?php if(!empty($userRecords))
                                            { ?>
                                        <table class="table table-striped mb-0">
                                            <thead class="thead-light">
                                                <tr role="row">
                                                    <th><?php echo lang('name') ?></th>
                                                    <th><?php echo lang('email') ?></th>
                                                    <?php if($pageTitle == "Clients"){ ?>
                                                    <th><?php echo lang('created_on') ?></th>
                                                    <?php } else if($pageTitle == "Team") {?>
                                                    <th><?php echo lang('role') ?></th>
                                                    <?php } ?>
                                                    <th class="text-center"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach($userRecords as $record)
                                                    {
                                                ?>
                                                <tr id="row<?php echo $record->userId ?>">
                                                    <td><?php echo $this->security->xss_clean($record->firstName).' '.$this->security->xss_clean($record->lastName) ?></td>
                                                    <td><?php echo $this->security->xss_clean($record->email) ?></td>
                                                    <?php if($pageTitle == "Clients"){ ?>
                                                    <td><?php echo date("d-m-Y", strtotime($record->createdDtm)) ?></td>
                                                    <?php } else if($pageTitle == "Team") {?>
                                                    <td><?php echo $record->role ?></td>
                                                    <?php } ?>

                                                    <td class="text-center">
                                                        <!-- Check the access for this component -->
                                                        <?php if($this->user_model->getPermissions('loginHistory', 'view', $userId) OR $role == ROLE_ADMIN) {?>
                                                        <a class="btn btn-sm btn-primary trans-btn"
                                                            href="<?= base_url().'login-history/'.$record->userId; ?>"
                                                            title="Login history"><?php echo lang('login_history') ?></a> |
                                                        <?php }?>
                                                        <?php if($pageTitle == "Clients") {?>
                                                        <!-- Check the access for this component -->
                                                        <?php if($this->user_model->getPermissions('clients', 'view', $userId) OR $role == ROLE_ADMIN) {?>
                                                        <a class="btn btn-sm btn-info trans-btn"
                                                            href="<?php echo base_url().'clients/viewClient/'.$record->userId; ?>"
                                                            title="Edit"><?php echo lang('view') ?></a> |
                                                        <?php }?>
                                                        <?php } else if($pageTitle == "Team") {?>
                                                        <!-- Check the access for this component -->
                                                        <?php if($this->user_model->getPermissions('teams', 'edit', $userId) OR $role == ROLE_ADMIN) {?>
                                                        <a class="btn btn-sm btn-info trans-btn"
                                                            href="<?php echo base_url('team/editManager/').$record->userId; ?>"
                                                            title="Edit"><?php echo lang('edit') ?></a> |
                                                        <?php }?>
                                                        <?php }?>
                                                        <button class="btn btn-sm btn-info trans-btn confirmButton"
                                                            data-url="<?php echo base_url('deleteUser').'/'.$record->userId ?>"
                                                            data-id="<?php echo $record->userId ?>"
                                                            data-toggle="modal" 
                                                            data-target="#confirmationModal"
                                                            title="Edit"><?php echo lang('delete') ?></button>
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
                            <div class="modal fade display-n" id="confirmationModal" tabindex="-1" role="dialog"
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

                                        <?php echo form_open(base_url() , array( 'class' => 'confirm-form' ));?>
                                        <!-- Modal Body -->
                                        <div class="modal-body">
                                            <input type="hidden" id="transID" name="id"/>
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
                        </div>
                    </div>
                    <!-- /grid -->

                </div>
                <!-- /profile content -->

            </div>
            <!-- /Profile -->

        </div>
    </div>
<script>
    $('.confirmButton').on('click', function(e){
        var url = $(this).attr('data-url')
        $('.confirm-form').attr('action', url)
    })
    $('.confirm-form').on('submit', function(e){
        e.preventDefault();
        var actionurl = e.currentTarget.action;
        $.ajax({
            url: actionurl,
            method:"POST",
            data: $(this).serialize(),
            success:function(data)
            {
                var content = JSON.parse(data);
                $("input[name="+content.csrfTokenName+"]").val(content.csrfHash);
                swal(
                    content.success == true ? 'Success!' : 'Error!',
                    content.msg,
                    content.success == true ? 'success' : 'error'
                );
                if(content.success == false)
                {
                    $.each(content.errors, function(key, value){
                        // here you can access all the properties just by typing either value.propertyName or value["propertyName"]
                        // example: value.ri_idx; value.ri_startDate; value.ri_endDate;
                        var msg = '<label class="error" for="'+key+'">'+value+'</label>';
                        $('input[name="' + key + '"], select[name="' + key + '"]').addClass('inputTxtError').after(msg);
                    });
                } else
                {
                    $('#row' + content.id).remove();
                    $('#confirmationModal').modal('toggle');
                }
            },
            error:function(data) {}
        })
    })
</script>