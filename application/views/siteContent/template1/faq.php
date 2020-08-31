<section class="special bg8 section-padding-100-70 clearfix" id="about">

    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-lg-6 offset-lg-0">
                <div class="who-we-contant">
                    <div class="fadeInUp main-pg-txt a-delay-2" data-wow-delay="0.2s">
                        <span class="w-text"><?php echo $this->security->xss_clean($companyInfo['name']) ?> <span class="breadcrumb-arrow-right"></span> FAQs</span>
                    </div>
                    <h4 class="fadeInUp  w-text" data-wow-delay="0.3s">Frequently Asked Questions</h4>
                </div>
            </div>

            <div class="col-12 col-lg-6 offset-lg-0 col-md-12 no-padding-left">
                <div class="welcome-meter wow fadeInUp mb-30 main-pg-txt a-delay-7" data-wow-delay="0.7s">
                    <img src="<?php echo base_url('assets/dist/img/terms-of-service.png') ?>" alt="">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ##### FAQ & Timeline Area Start ##### -->
<div class="faq-timeline-area section-padding-100-85" id="faq">
        <div class="container">
            <div class="section-heading text-center">
                <!-- Dream Dots -->
                <div class="dream-dots justify-content-center fadeInUp" data-wow-delay="0.2s">
                    <span class="gradient-text blue"><?php echo $this->web_model->getTemplateContent('card_4_subtitle', $companyInfo['template'])->value; ?></span>
                </div>
                <h2 class="fadeInUp" data-wow-delay="0.3s"><?php echo $this->web_model->getTemplateContent('card_4_title', $companyInfo['template'])->value; ?></h2>
                <p class="fadeInUp" data-wow-delay="0.4s"><?php echo $this->web_model->getTemplateContent('card_4_content', $companyInfo['template'])->value; ?></p>
            </div>
            <div class="row align-items-center">
                <div class="col-12 col-lg-12 col-md-12">
                    <div class="dream-faq-area mt-s ">
                        <div class="row">
                            <dl class="col-lg-12 mb-0">
                                <!-- Single FAQ Area -->
                                <?php foreach($faqs as $faq) {?>
                                <dt class="v2 wave fadeInUp" data-wow-delay="0.2s"><?php echo $faq->question ?>
                                </dt>
                                <dd class="fadeInUp" data-wow-delay="0.3s">
                                    <p><?php echo $faq->answer ?></p>
                                </dd>
                                <?php }?>
                            </dl>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- ##### FAQ & Timeline Area End ##### -->
