    <!-- ##### Welcome Area Start ##### -->
    <section class="hero-section blue-bg relative section-padding" id="home">

        <div class="hero-section-content">

            <div class="container h-100">
                <div class="row h-100 mb-50 align-items-center">

                    <!-- Welcome Content -->
                    <div class="col-12 col-lg-6 col-md-12">
                        <div class="welcome-content">
                            <div class="promo-section">
                                <h3 class="special-head gradient-text cyan"><?php echo $this->web_model->getTemplateContent('header_sub_title', $companyInfo['template'])->value; ?></h3>
                            </div>
                            <h1 class="w-text wow fadeInUp main-pg-txt a-delay-2" data-wow-delay="0.2s">
                            <?php echo $this->web_model->getTemplateContent('header_title', $companyInfo['template'])->value; ?> </h1>
                            <p class="g-text wow fadeInUp main-pg-txt a-delay-3" data-wow-delay="0.3s">
                            <?php echo $this->web_model->getTemplateContent('header_description', $companyInfo['template'])->value; ?></p>
                            <div class="dream-btn-group wow fadeInUp main-pg-txt a-delay-4" data-wow-delay="0.4s">
                                <a href="<?php echo base_url() ?>signup" class="btn more-btn btn-primary pink mr-3"><?php echo lang("create_account") ?></a>
                                <a href="<?php echo base_url() ?>login" class="btn more-btn btn-info blue-grad"><?php echo lang("login") ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="main-ilustration main-ilustration-5"></div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- ##### Welcome Area End ##### -->


    <div class="clearfix"></div>

    <div class="clearfix"></div>

    <!-- ##### About Us Area Start ##### -->
    <section class="special section-padding-100-70 clearfix" id="about">

        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-lg-6 offset-lg-0 col-md-12 no-padding-left">
                    <div class="welcome-meter wow fadeInUp mb-30 main-pg-txt a-delay-7" data-wow-delay="0.7s">
                        <img src="<?php echo base_url() ?>assets/dist/img/About-us-banner-img.png" alt="">
                    </div>
                </div>

                <div class="col-12 col-lg-6 offset-lg-0">
                    <div class="who-we-contant">
                        <div class="dream-dots text-left fadeInUp main-pg-txt a-delay-2" data-wow-delay="0.2s">
                            <span class="gradient-text blue"><?php echo $this->web_model->getTemplateContent('card_1_subtitle', $companyInfo['template'])->value; ?></span>
                        </div>
                        <h4 class="fadeInUp" data-wow-delay="0.3s"><?php echo $this->web_model->getTemplateContent('card_1_title', $companyInfo['template'])->value; ?></h4>
                        <p class="fadeInUp" data-wow-delay="0.4s">
                        <?php echo $this->web_model->getTemplateContent('card_1_content', $companyInfo['template'])->value; ?>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- ##### About Us Area End ##### -->

    <section class=" fuel-features features section-padding-100 clearfix" id="plans">

        <div class="container has-shadow">
            <div class="section-heading text-center">
                <!-- Dream Dots -->
                <div class="dream-dots justify-content-center fadeInUp main-pg-txt a-delay-2" data-wow-delay="0.2s">
                    <span class="gradient-text blue"><?php echo $this->web_model->getTemplateContent('card_2_subtitle', $companyInfo['template'])->value; ?></span>
                </div>
                <h2 class="wow fadeInUp main-pg-txt a-delay-2" data-wow-delay="0.3s"><?php echo $this->web_model->getTemplateContent('card_2_title', $companyInfo['template'])->value; ?>
                </h2>
            </div>
            <div class="row align-items-center">
                <?php foreach($plans as $plan) {?>
                <div class="col-lg-4 col-md-6 col-sm-12 mt-md-30">
                    <div class="services-block-four v2 txt-center">
                            <h3><a href="#"><?php echo $this->security->xss_clean($plan->name) ?></a></h3>
                            <h2 class="black"><?php echo $this->security->xss_clean($plan->profit).'% '.$this->security->xss_clean($plan->periodName) ?></h2>
                            <h5><?php echo to_currency($this->security->xss_clean($plan->minInvestment)).' - '.to_currency($this->security->xss_clean($plan->maxInvestment)) ?></h5>
                        <a href="#" class="icon_foot">
                            <i class="fa fa-long-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <?php } ?>
            </div>

        </div>
    </section>
    <section class="special section-padding-100-70 clearfix" id="about">

        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-lg-6 offset-lg-0">
                    <div class="who-we-contant">
                        <div class="dream-dots text-left fadeInUp main-pg-txt a-delay-2" data-wow-delay="0.2s">
                            <span class="gradient-text blue"><?php echo $this->web_model->getTemplateContent('card_3_subtitle', $companyInfo['template'])->value; ?></span>
                        </div>
                        <h4 class="fadeInUp" data-wow-delay="0.3s"><?php echo $this->web_model->getTemplateContent('card_3_title', $companyInfo['template'])->value; ?></h4>
                        <p class="fadeInUp" data-wow-delay="0.4s"><?php echo $this->web_model->getTemplateContent('card_3_content', $companyInfo['template'])->value; ?></p>
                    </div>
                </div>
                <div class="col-12 col-lg-6 offset-lg-0 col-md-12 no-padding-left">
                    <div class="welcome-meter wow fadeInUp mb-30 main-pg-txt a-delay-7" data-wow-delay="0.7s">
                        <img src="<?php echo base_url(); ?>assets/dist/img/affiliate-program.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    