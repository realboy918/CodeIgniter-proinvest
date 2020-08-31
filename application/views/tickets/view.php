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
                                <app-task-details class="dt-module__container ng-star-inserted" style="max-width: none;">
                                    <div class="dt-module__content position-relative ps ps--active-y">
                                        <div class="dt-module__content-inner">
                                            <task-header class="align-items-center d-flex flex-wrap mb-5">
                                                <a href="<?php echo base_url('tickets') ?>" class="text-dark ng-star-inserted mr-5">
                                                    <i name="arrow-left" size="1x" class="icon icon-arrow-left icon-1x icon-fw"></i>
                                                    <span class="display-6 align-middle ml-1"><?php echo lang('back') ?></span>
                                                </a>
                                                <?php if($ticket->resolved == 0) {?>
                                                <task-labels>
                                                    <div class="mb-2 mr-6 dropdown">
                                                        <button aria-haspopup="true" type="button" id="priority-select" class="dropdown-toggle btn btn-outline-dark btn-sm" aria-expanded="false">
                                                            <gx-icon name="check" class="mr-1 icon icon-check icon-fw"></gx-icon> <?php echo lang('priority') ?> 
                                                        </button>
                                                        <div x-placement="bottom-left" class="dropdown-menu priority-select">
                                                            <a href="javascript:void(0)" class="dropdown-item ng-star-inserted">
                                                                <?php echo lang('high_priority') ?>
                                                            </a>
                                                            <a href="javascript:void(0)" class="dropdown-item ng-star-inserted">
                                                                <?php echo lang('medium_priority') ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </task-labels>
                                                <?php }?>
                                                <task-date>
                                                    <a href="javascript:void(0)" data-target-input="nearest" class="text-dark d-inline-block mb-2 mr-6">
                                                        <div data-target="#date-time-picker-1" data-toggle="datetimepicker" class="dt-avatar-wrapper">
                                                            <input name="dueDate" hidden="" aria-haspopup="true" min="2018-08-15T07:30:00.000Z" class="ng-untouched ng-pristine ng-valid">
                                                            <owl-date-time pickertype="calendar"></owl-date-time><!---->
                                                            <span class="dt-avatar dt-avatar__outline size-35">
                                                                <gx-icon name="calendar" class="icon icon-calendar icon-fw"></gx-icon>
                                                            </span>
                                                            <span class="dt-avatar-info"><span class="dt-avatar-name"><?php echo $ticket->createdDtm ?></span>
                                                        </div>
                                                    </a>
                                                </task-date>
                                                <?php if(($role != ROLE_CLIENT && $ticket->assignedTo > 0) || $role == ROLE_ADMIN) {?>
                                                <task-assignees>
                                                    <div class="mb-2 dropdown">
                                                        <a aria-haspopup="true" href="javascript:void(0)" id="team-select" class="dropdown-toggle no-arrow dt-avatar-wrapper text-dark" aria-expanded="false">
                                                            <div class="size-35 dt-avatar text-white ng-star-inserted">
                                                                <img class="img-fluid ng-star-inserted" alt="<?php echo $ticket->supportFirstName.' '.$ticket->supportLastName ?>" src="<?php echo $ticket->supportppic == NULL ? base_url('assets/dist/img/avatar.png') : base_url('uploads/').$ticket->supportppic ?>"><!----><!----><!---->
                                                            </div>
                                                            <span class="dt-avatar-info ng-star-inserted">
                                                                <?php if($ticket->assignedTo > 0) {?>
                                                                    <span id="support_name" class="dt-avatar-name"><?php echo $ticket->supportFirstName ?></span>
                                                                <?php } else {?>
                                                                    <span id="support_name" class="dt-avatar-name">Assign Ticket</span>
                                                                <?php }?>
                                                            </span><!----><!----><!----><!---->
                                                        </a>
                                                        <?php if($role != ROLE_CLIENT) {?>
                                                        <div class="dropdown-menu-right dropdown-menu team-select" x-placement="bottom-left">
                                                            <?php foreach($team as $agent) {?>
                                                            <a href="javascript:void(0)" id="assignee<?php echo $agent->userId ?>" data-url="<?php echo base_url('assign_ticket/' ).$ticket->id.'/'.$agent->userId ?>" class="assign dropdown-item dt-avatar-wrapper <?php echo $agent->userId == $ticket->assignedTo ? 'active' : ''; ?> ng-star-inserted">
                                                                <gx-avatar class="size-30 dt-avatar text-white bg-white">
                                                                    <img class="img-fluid ng-star-inserted" alt="<?php echo $agent->firstName.' '.$agent->lastName; ?>" src="<?php echo $agent->ppic == NULL ? base_url('assets/dist/img/avatar.png') : base_url('uploads/').$agent->ppic ?>"><!----><!----><!---->
                                                                </gx-avatar>
                                                                <span class="dt-avatar-info">
                                                                    <span class="dt-avatar-name"><?php echo $agent->firstName.' '.$agent->lastName; ?></span>
                                                                </span>
                                                            </a>
                                                            <?php }?>
                                                        </div>
                                                        <?php }?>
                                                    </div>
                                                </task-assignees>
                                                <?php }?>
                        
                                                <div placement="bottom-right" id="ticket-state" class="ml-auto dropdown" data-url="<?php echo $ticket->resolved == 0 ? base_url('ticket/closed/'.$ticket->id) : base_url('ticket/opened/'.$ticket->id) ?>">
                                                    <btn-complete display="button">
                                                        <button type="button" data-toggle="button" aria-pressed="false" class="btn btn-outline-dark btn-sm mb-2 mr-5 ng-star-inserted">
                                                            <i name="check" class="mr-1 icon icon-check icon-fw"></i> <?php echo $ticket->resolved == 0 ? lang('mark_as_resolved') : lang('open_ticket') ?> 
                                                        </button>
                                                    </btn-complete>
                                                </div>
                                            </task-header>
                                            <form novalidate="" class="ng-pristine ng-valid ng-touched">
                                                <h2 class="display-4"><?php echo lang('subject') ?>: <?php echo $ticket->subject ?></h2>
                                                <task-badges class="d-block mb-5">
                                                    <div class="ng-star-inserted">
                                                        <div class="badge-group">
                                                            <span class="badge <?php echo $ticket->resolved == 0 ? 'bg-dark-blue' : 'bg-dark-green'; ?> text-white ng-star-inserted"><?php echo $ticket->resolved == 0 ? 'Pending' : 'Resolved'; ?></span>
                                                            <?php if($ticket->resolved == 0) {?>
                                                            <span class="badge badge-danger ng-star-inserted text-capitalize"><?php echo $ticket->priority ?></span>
                                                            <?php }?>
                                                        </div>
                                                    </div><!----><!---->
                                                </task-badges>
                                                <div class="form-group d-flex">
                                                    <p><?php echo $ticket->message ?></p>
                                                </div>
                                            </form>
                                            <hr class="my-7">
                                            <div id="messageList">
                                                <?php if($count > 2) {?>
                                                <a href="javascript:void(0)" id="previousmessages" data-id="<?php echo base_url('previous_messages/'.$ticket->id) ?>" class="d-inline-block mb-6 text-decoration-underline ng-star-inserted">View all messages</a><!---->
                                                <?php }?>
                                                <?php foreach($replies as $reply) {?>
                                                <div class="mb-6 media ng-star-inserted">
                                                    <img alt="<?php echo $reply->firstName ?>" src="<?php echo $reply->ppic == NULL ? base_url('assets/dist/img/avatar.png') : base_url('uploads/').$reply->ppic ?>" class="dt-avatar size-25 mr-4 ng-star-inserted"><!---->
                                                    <div class="media-body"><!---->
                                                        <h5 class="text-light-gray mb-1"> <?php echo $reply->firstName ?> 
                                                            <span class="d-inline-block f-12 ml-2">
                                                            <?php
                                                                echo $timeago
                                                            ?>
                                                            </span>
                                                        </h5>
                                                        <p class="mb-0 text-dark"><?php echo $reply->message ?></p>
                                                    </div><!---->
                                                </div><!---->
                                                <?php }?>
                                            </div>
                                    </div>
                                </div>
                                <?php if($ticket->resolved == 0) {?>
                                <app-comment-box class="add-comment-box">
                                    <?php echo form_open(base_url( 'ticket/comment/'.$ticket->id ), array( 'id' => 'comment', 'class'=>'ng-pristine ng-invalid ng-touched' ));?>
                                        <div class="action-tools">
                                            <button type="submit" class="btn btn-primary dt-fab-btn">
                                                <i name="send-new" size="xl" class="icon icon-send-new icon-xl"></i>
                                            </button>
                                            <!--
                                            <div class="dt-fab-btn dt-attachment-btn size-30">
                                                <input type="file">
                                                <gx-icon name="attach-v" class="icon icon-attach-v"></gx-icon>
                                            </div>
                                            -->
                                        </div>
                                        <bs-media-object class="media">
                                            <img alt="<?php echo $firstName.' '.$lastName; ?>" src="<?php echo $ppic; ?>" class="dt-avatar mr-2 ng-star-inserted"><!---->
                                            <div class="media-body"><!---->
                                                <textarea rows="1" name="comment" formcontrolname="notes" required="" placeholder="Write your comment..." class="form-control border-0 shadow-none bg-focus ng-pristine ng-invalid ng-touched"></textarea>
                                            </div><!---->
                                        </bs-media-object>
                                    <?php echo form_close();?>
                                </app-comment-box>
                                <?php }?>
                            </app-task-details>
                        </div>
                        <!-- /grid item -->
                    </div>
                    <!-- /grid -->
                </div>
                <!-- /profile content -->
            </div>
            <!-- /Profile -->
            <?php $this->load->view('tickets/new'); ?>
        </div>
    </div>
</div>
<script src="<?php echo base_url('/assets/dist/js/tickets.js') ?>"></script>