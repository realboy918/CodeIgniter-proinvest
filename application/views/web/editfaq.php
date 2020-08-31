<aside class="dt-customizer dt-drawer w-65 position-right editdrawer" style="width: 55em;">
            <div class="dt-customizer__inner">
                <!-- Customizer Header -->
                <div class="dt-customizer__header">
                    <!-- Customizer Title -->
                    <div class="dt-customizer__title">
                        <h3 class="mb-0 text-capitalize" id="lang-header">Edit FAQ</h3>
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
                    <?php echo form_open(base_url( 'edit_faq' ), array( 'id' => 'editFaq' ));?>
                        <form>        
                            <div class="form-group">
                                <label for="subject">Question</label>
                                <textarea name="question" id="question" rows="2" placeholder="Enter question here" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="text-area-1">Answer</label>
                                <textarea name="answer" id="ans" rows="10" placeholder="Enter answer here" class="form-control"></textarea>
                            </div>
                            <button class="btn btn-info w-100 hide text-capitalize" name="save" style="display: inline-block;"><?php echo lang('save') ?></button>
                        <?php echo form_close();?>                 
                    </div>
                    <!-- /customizer body inner -->
                </div>
                <!-- /customizer body -->
            </div>
        </aside>