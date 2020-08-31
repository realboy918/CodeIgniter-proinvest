<?php 
$categories = $this->ticket_model->allCategories();
$team = $this->ticket_model->team();
?>
<aside class="dt-customizer dt-drawer w-65 position-right" style="width: 55em;">
            <div class="dt-customizer__inner">
                <!-- Customizer Header -->
                <div class="dt-customizer__header">
                    <!-- Customizer Title -->
                    <div class="dt-customizer__title">
                        <h3 class="mb-0 text-capitalize" id="lang-header"><?php echo lang('create_ticket') ?></h3>
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
                <div class="dt-customizer__body ps-custom-scrollbar ps">
                <div class="loader" id="LangSettingsloader" style="display: none;"></div>
                    <!-- Customizer Body Inner  -->
                    <div class="dt-customizer__body-inner" id="sideContent">
                    <?php echo form_open(base_url( 'create_ticket' ), array( 'id' => 'formTicket' ));?>
                        <form>        
                            <div class="form-group">
                                <label for="subject"><?php echo lang('subject') ?></label>
                                <input type="text" placeholder="Subject" id="subject" name="subject" formcontrolname="subject" class="form-control ng-pristine ng-valid ng-touched">
                            </div>
                            <?php if($role != ROLE_CLIENT){?>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="subject"><?php echo lang('email') ?></label>
                                        <input type="email" placeholder="<?php echo lang('email') ?>" id="email" name="email" formcontrolname="" class="form-control ng-pristine ng-valid ng-touched">
                                    </div> 
                                    <div class="form-group col-md-6">
                                        <label for="subject"><?php echo lang('assign_to') ?></label>
                                        <select name="assignee" class="form-control">
                                            <option value="" selected disabled hidden><?php echo lang('choose_here') ?></option>
                                            <?php foreach($team as $agent) {?>
                                            <option value="<?php echo $agent->userId ?>"><?php echo $agent->firstName ?></option>
                                            <?php }?>
                                        </select>
                                    </div>   
                                </div> 
                            <?php }?>   
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="subject"><?php echo lang('category') ?></label>
                                    <select name="category" class="form-control">
                                        <option value="" selected disabled hidden><?php echo lang('choose_here') ?></option>
                                        <?php foreach($categories as $category) {?>
                                        <option value="<?php echo $category->categoryId ?>"><?php echo $category->categoryName; ?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="priority"><?php echo lang('priority') ?></label>
                                    <select name="priority" class="form-control">
                                        <option value="" selected disabled hidden><?php echo lang('choose_here') ?></option>
                                        <option value="high"><?php echo lang('high') ?></option>
                                        <option value="medium"><?php echo lang('medium') ?></option>
                                        <option value="low"><?php echo lang('low') ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="text-area-1"><?php echo lang('message') ?></label>
                                <textarea name="message" id="text-area-1" rows="10" placeholder="Detail your issue here" class="form-control"></textarea>
                            </div>
                            <button class="btn btn-info w-100 hide text-capitalize" name="save" style="display: inline-block;"><?php echo lang('save') ?></button>
                        <?php echo form_close();?>                 
                    </div>
                    <!-- /customizer body inner -->
                </div>
                <!-- /customizer body -->
            </div>
        </aside>