<?php 
$team = $this->ticket_model->team();
?>
<div class="dt-content-wrapper">
    <!-- Site Content -->
    <div class="dt-content">
        <!-- Profile -->
        <div class="profile">
            <!-- Profile Content -->
            <div class="profile-content">
                <!-- Grid -->
                <div class="row">
                    <!-- Grid Item -->
                    <div class="col-xl-12 col-12 order-xl-1">
                        <!-- Card -->
                        <div class="dt-card">
                            <!-- Card Body -->
                            <div class="dt-module__content-inner">
                                <div class="border-bottom border-w-2 mb-1 mt-n1 pb-4 px-1">
                                    <div class="d-flex flex-wrap">
                                        <div class="mt-2 mr-4 dropdown">
                                            <checkbox class="mr-3 dt-checkbox dt-checkbox-icon dt-checkbox-only">
                                                <input type="checkbox" id="gx-checkbox-181" class="ng-valid ng-dirty ng-touched">
                                                <label class="font-weight-light dt-checkbox-content" for="gx-checkbox-181">
                                                    <span class="unchecked">
                                                        <i name="box-o" size="xl" class="icon icon-box-o icon-xl icon-fw"></i>
                                                    </span><!---->
                                                    <span class="checked ng-star-inserted">
                                                        <i name="intermediate-o" size="xl" class="text-primary icon icon-intermediate-o icon-xl icon-fw"></i>
                                                    </span><!---->
                                                </label>
                                            </checkbox>
                                            <a aria-haspopup="true" href="javascript:void(0)" id="action-select" class="text-dark" aria-expanded="false"> <?php echo lang('none') ?> </a>
                                        </div>
                                        
                                        <div placement="bottom-right" class="mr-auto dropdown">
                                        <?php if($role != ROLE_CLIENT) {?>
                                            <a aria-haspopup="true" id="bulk-assign" href="javascript:void(0)" class="dropdown-toggle btn btn-outline-dark btn-sm" aria-expanded="false"> <?php echo lang('assign_to') ?> </a>
                                            <div x-placement="bottom-right" class="bulk-assign dropdown-menu">
                                                <?php foreach($team as $agent) {?>
                                                    <a href="javascript:void(0)" data-id="<?php echo $agent->userId ?>" class="assign-table-agent dropdown-item"><?php echo $agent->firstName ?></a>
                                                <?php }?>
                                            </div>
                                        <?php }?>
                                        </div><!---->
                                        
                                    <div role="group" class="btn-group btn-group-sm mr-4 mr-lg-7 mb-1 ng-star-inserted">
                                        <?php if($role != ROLE_CLIENT) {?>
                                        <button type="button" class="btn btn-outline-dark">
                                            <i name="star-fill" size="lg" class="icon icon-star-fill icon-lg icon-fw"></i>
                                        </button>
                                        <button type="button" id="bulk-priority" class="dropdown-btn btn btn-outline-dark">
                                            <i name="contacts" size="lg" class="icon icon-tag icon-lg icon-fw"></i>
                                        </button>
                                        <?php }?>
                                        
                                        <div x-placement="bottom-right" data-url="<?php echo base_url('bulk_prioritise_ticket') ?>" class="dropdown-menu bulk-priority" style="">
                                            <a href="javascript:void(0)" data-id="high" class="prioritise-table dropdown-item ng-star-inserted"><?php echo lang('high_priority') ?></a>
                                            <a href="javascript:void(0)" data-id="medium" class="prioritise-table dropdown-item ng-star-inserted"><?php echo lang('medium_priority') ?></a>
                                            <a href="javascript:void(0)" data-id="low" class="prioritise-table dropdown-item ng-star-inserted"><?php echo lang('low_priority') ?></a>
                                        </div>
                                    </div><!---->
                                    <div placement="bottom-right" class="dropdown">
                                        <a aria-haspopup="true" id="bulk-action" href="javascript:void(0)" class="dropdown-toggle btn btn-outline-dark btn-sm" aria-expanded="false"> <?php echo lang('status') ?> </a>
                                        <div class="bulk-action dropdown-menu" data-url="<?php echo base_url('bulk_resolve_ticket') ?>">
                                            <a href="javascript:void(0)" data-id="0" class="resolve-table dropdown-item"><?php echo lang('mark_as_pending') ?></a>
                                            <a href="javascript:void(0)" data-id="1" class="resolve-table dropdown-item"><?php echo lang('mark_as_resolved') ?></a>
                                        </div>
                                    </div><!---->
                                </div>
                            </div>
                            <!-- Tables -->
                            <div class="dt-module__content position-relative ps ps--active-y">
                                <div class=""><!---->
                                    <div id="hpdk" data-url="<?php echo base_url('bulk_assign_ticket') ?>" class="dt-module__list ng-star-inserted"><!---->
                                        <?php foreach($tickets as $ticket) {?>
                                            <a class="helpdesk dt-module__list-item ng-star-inserted" href="<?php echo base_url('ticket/').$ticket->id ?>">
                                                <div>
                                                    <div class="mr-5 dt-checkbox dt-checkbox-icon dt-checkbox-only">
                                                        <input type="checkbox" id="<?php echo $ticket->id; ?>" class="helpdesk-list checkbox ng-pristine ng-valid ng-touched">
                                                        <label class="font-weight-light dt-checkbox-content">
                                                            <span class="unchecked">
                                                                <i name="box-o" size="xl" class="icon icon-box-o icon-xl icon-fw"></i>
                                                            </span>
                                                            <span class="checked ng-star-inserted">
                                                                <i name="box-check-o" size="xl" class="text-primary icon icon-box-check-o icon-xl icon-fw"></i>
                                                            </span><!----><!---->
                                                        </label>
                                                    </div><!----><!---->
                                                    <?php if($role != ROLE_CLIENT) {?>
                                                    <gx-star class="mr-5 dt-checkbox dt-checkbox-icon dt-checkbox-only">
                                                        <input type="checkbox" id="gx-star-221" class="ng-untouched ng-pristine ng-valid">
                                                        <label class="font-weight-light dt-checkbox-content" for="gx-star-221">
                                                            <span class="unchecked">
                                                                <i name="star-o" size="xl" class="icon icon-star-o icon-xl icon-fw"></i>
                                                            </span>
                                                            <span class="checked">
                                                                <i name="star-fill" size="xl" class="text-warning icon icon-star-fill icon-xl icon-fw"></i>
                                                            </span>
                                                        </label>
                                                    </gx-star>
                                                    <?php }?>
                                                </div>
                                                <div class="dt-module__list-item-content">
                                                    <div class="user-detail">
                                                        <span class="user-name"><?php echo $ticket->userFirstName.' '.$ticket->userLastName ?></span>
                                                        <span class="dt-separator-v">&nbsp;</span>
                                                        <span class="designation"><?php echo $ticket->userEmail ?></span>
                                                    </div>
                                                    <div class="">
                                                        <span class="d-inline-block mr-3 text-light-gray"><?php echo lang('subject') ?>:</span>
                                                        <span class="d-inline-block"><?php echo $ticket->subject ?></span>
                                                    </div>
                                                </div>
                                                <div class="dt-module__list-item-info">
                                                    <task-badges>
                                                        <div class="badge-group ng-star-inserted">
                                                            <span class="resolve-badge<?php echo $ticket->id ?> badge <?php echo $ticket->resolved == 0 ? 'bg-dark-blue' : 'bg-dark-green'; ?> text-white ng-star-inserted"><?php echo $ticket->resolved == 0 ? lang('pending') : lang('resolved'); ?></span>
                                                            <?php if($ticket->resolved == 0) {?>
                                                            <span class="badge-pending<?php echo $ticket->id ?> priority-badge<?php echo $ticket->id ?> badge <?php if($ticket->priority == 'low'){ echo 'badge-primary'; } else if($ticket->priority == 'high'){ echo 'badge-danger'; } else if($ticket->priority == 'medium'){ echo 'badge-secondary'; } ?> ng-star-inserted text-capitalize"><?php echo $ticket->priority ?></span>
                                                            <?php }?>
                                                            <?php if(($role == ROLE_CLIENT && $ticket->supportReply == 1) || ($role != ROLE_CLIENT && $ticket->clientReply == 1)) {?>
                                                            <span class="badge-pending<?php echo $ticket->id ?> badge badge-success ng-star-inserted text-capitalize">New Message</span>
                                                            <?php }?>
                                                            <?php if($role != ROLE_CLIENT && $ticket->assignedTo == 0) {?>
                                                            <span class="assigned-badge badge badge-primary ng-star-inserted text-capitalize">Unassigned</span>
                                                            <?php } else if($role != ROLE_CLIENT && $ticket->assignedTo > 0) {?>
                                                            <span class="assigned-badge<?php echo $ticket->id ?> badge badge-primary ng-star-inserted text-capitalize"><?php echo lang('assigned_to').': '.$ticket->supportFirstName ?></span>
                                                            <?php }?>
                                                        </div>
                                                    </task-badges>
                                                    <span><?php echo date("d M y H:i",strtotime($ticket->createdDtm)) ?></span>
                                                </div>
                                            </a>
                                        <?php }?>
                                    </div>
                                </div>
                                <!-- /card body -->
                            </div>
                            <!-- /card -->
                        </div>
                        <!-- /grid item -->

                    </div>
                    <!-- /grid -->
                    <div style="float:right">
                        <?php echo $this->pagination->create_links(); ?>
                    </div>

                </div>
                <!-- /profile content -->

            </div>
            <!-- /Profile -->
            <?php $this->load->view('tickets/new'); ?>
        </div>
        </div>
    </div><!-- Footer --><!-- Footer --><!-- Footer --><!-- Footer -->
    <script src="<?php echo base_url('/assets/dist/js/tickets.js') ?>"></script>