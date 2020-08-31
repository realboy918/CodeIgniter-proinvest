<!-- ##### Footer Area Start ##### -->
<footer class="footer-area bg-img">

<!-- ##### team Area Start ##### -->
<div class="striples-bg">
    <!-- ##### Team Area Start ##### -->
    <section class="our_team_area section-padding-100-70 clearfix" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading text-center">
                        <!-- Dream Dots -->
                        <div class="dream-dots justify-content-center fadeInUp" data-wow-delay="0.2s">
                            <span><?php echo $this->web_model->getTemplateContent('card_5_subtitle', $companyInfo['template'])->value; ?></span>
                        </div>
                        <h2 class="fadeInUp" data-wow-delay="0.3s"><?php echo $this->web_model->getTemplateContent('card_5_title', $companyInfo['template'])->value; ?></h2>
                        <p class="fadeInUp" data-wow-delay="0.4s"><?php echo $this->web_model->getTemplateContent('card_5_content', $companyInfo['template'])->value; ?></p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-3"></div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                <div id='msg'></div>
                <?php echo validation_errors(); ?>
                    <?php echo form_open( base_url( 'contactus' ), array( 'id' => 'contactForm', 'class' => 'contact_form' ));?>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="name" placeholder="Your Name"
                                        class="form-control font_color <?php echo form_error('name') == TRUE ? 'inputTxtError' : ''; ?>">
                                        <label class="error" for="name"><?php echo form_error('name'); ?></label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="email" name="email" placeholder="Email Address"
                                        class="form-control font_color <?php echo form_error('email') == TRUE ? 'inputTxtError' : ''; ?>">
                                    <label class="error" for="email"><?php echo form_error('email'); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" name="subject" placeholder="Subject" class="form-control font_color <?php echo form_error('subject') == TRUE ? 'inputTxtError' : ''; ?>">
                            <label class="error" for="subject"><?php echo form_error('subject'); ?></label>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control font_color <?php echo form_error('comment') == TRUE ? 'inputTxtError' : ''; ?>" name="comment" placeholder="Your Comment..."
                                rows="5"></textarea>
                            <label class="error" for="comment"><?php echo form_error('comment'); ?></label>
                        </div>
                        <div class="form-btn">
                            <button type="submit" class="btn more-btn blue-grad w-100">Send
                                message</button>
                        </div>
                    <?php echo form_close();?>
                </div>
                ` <div class="col-3"></div>
            </div>
        </div>
    </section>
    <!-- ##### Team Area End ##### -->

    <div class="footer-content-area mt-0">
        <div class="container">
            <div class="row ">
                <div class="col-12 col-lg-4 col-md-6">
                    <div class="footer-copywrite-info">
                        <!-- Copywrite -->
                        <div class="copywrite_text fadeInUp" data-wow-delay="0.2s">
                            <div class="footer-logo">
                                <a href="#"><img class="logo-img" src="<?php echo $this->security->xss_clean($this->logoWhite) ?>"
                                        alt="logo"></a>
                            </div>
                            <p><?php echo $this->web_model->getTemplateContent('footer', $companyInfo['template'])->value; ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4 col-md-6">
                    <div class="contact_info_area d-sm-flex justify-content-between">
                        <!-- Content Info -->
                        <div class="contact_info mt-x text-center fadeInUp" data-wow-delay="0.3s">
                            <h5>PRIVACY &amp; T&Cs</h5>
                            <a href="<?php echo base_url().'privacy' ?>">
                                <p>Privacy Policy</p>
                            </a>
                            <a href="<?php echo base_url().'terms' ?>">
                                <p>T&C's</p>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4 col-md-6 ">
                    <div class="contact_info_area d-sm-flex justify-content-between">
                        <!-- Content Info -->
                        <div class="contact_info mt-s text-center fadeInUp" data-wow-delay="0.4s">
                            <h5>CONTACT US</h5>
                            <p><?php echo $this->security->xss_clean($companyInfo['name']) ?></p>
                            <p><?php echo $this->security->xss_clean($companyInfo['address']) ?></p>
                            <p><?php echo $this->security->xss_clean($companyInfo['phone1']) ?></p>
                            <p><?php echo $this->security->xss_clean($companyInfo['email']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</footer>
<!-- ##### Footer Area End ##### -->
<script src="<?php echo base_url(); ?>assets/dist/js/bootstrap/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/contact.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/perfect-scrollbar.min.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/masonry.pkgd.min.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/sweetalert2.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/customizer.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/Chart.min.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/chartist.min.js"></script>
<script src="<?php echo base_url(); ?>assets/dist/js/script.js"></script>
</body>
</html>

    