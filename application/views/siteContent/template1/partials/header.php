
<?php
header('Access-Control-Allow-Origin: *');
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE HTML>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="description" content="<?php echo $this->security->xss_clean($this->siteDescription) ?>">
    <meta name="keywords" content="<?php echo $this->security->xss_clean($this->siteKeywords) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $pageTitle ?></title>

    <!-- CSS -->
    <link rel="shortcut icon" href="<?php echo $this->favicon ?>"> 
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/home.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/responsive.css">

    <script src="<?php echo base_url(); ?>assets/dist/js/jquery.min.js"></script>
</head>
<body class="light-version js-focus-visible">
    <!-- Preloader -->


    <!-- ##### Header Area Start ##### -->
    <header class="header-area fadeInDown" data-wow-delay="0.2s">
        <div class="classy-nav-container light breakpoint-off dark left">
            <div class="container">
                <!-- Classy Menu -->
                <nav class="classy-navbar light justify-content-between" id="dreamNav">

                    <!-- Logo -->
                    <a class="nav-brand light" href="<?php echo base_url() ?>">
                        <img id="dark-logo" class="logo-img logo-small hidden"
                            src="<?php echo $this->security->xss_clean($this->logoDark); ?>" alt="logo">
                        <img id="white-logo" class="logo-img logo-small"
                            src="<?php echo $this->security->xss_clean($this->logoWhite); ?>" alt="logo">
                    </a>

                    <!-- Navbar Toggler -->
                    <div class="classy-navbar-toggler demo">
                        <span class="navbarToggler"></span>
                    </div>

                    <!-- Menu -->
                    <div class="classy-menu menu-on">

                        <!-- close btn -->
                        <div class="classycloseIcon">
                            <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                        </div>

                        <!-- Nav Start -->
                        <div class="classynav">
                            <ul id="nav">
                                <li class="active"><a href="<?php echo base_url() ?>signup">Get started</a></li>
                                <li class=""><a href="<?php echo base_url() ?>#about">About Us</a></li>
                                <li class=""><a href="<?php echo base_url() ?>#plans">Plans</a></li>
                                <li class=""><a href="<?php echo base_url() ?>faqs">FAQ</a></li>
                                <li class=""><a href="#contact">Contact Us</a></li>
                            </ul>
                            <ul class="dt-nav">
                                <li class="dt-nav__item dropdown">
    
                                    <!-- Dropdown Link -->
                                    <a href="#" class="dt-nav__link dropdown-toggle" id="currentLang" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img style="width:20px;" class="flag-icon flag-icon-rounded flag-icon-lg mr-1m" src="<?php echo base_url('uploads/'.$this->site_lang->logo) ?>">
                                    <span><?php echo $this->site_lang->code ?></span> </a>
                                    <!-- /dropdown link -->

                                    <!-- Dropdown Option -->
                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(8px, 72px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        <?php foreach($this->site_languages as $language) {?>
                                        <button class="dropdown-item sitelangChange" type="button" data-id="<?php echo base_url('switchlang/').$language->name ?>">
                                            <img class="flag-icon flag-icon-rounded flag-icon-lg mr-2" style="width: 20px;" src="<?php echo base_url('uploads/').$language->logo ?>">
                                            <span><?php echo $language->name ?></span> 
                                        </button>
                                        <?php }?>
                                    </div>
                                    <!-- /dropdown option -->

                                </li>
                            </ul>
                            <!-- Button -->
                            <a href="<?php echo base_url() ?>login"
                                class="btn more-btn btn-info blue-grad lh-40p p-0-35p"><?php echo lang("login") ?></a>
                        </div>
                        <!-- Nav End -->
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <!-- ##### Header Area End ##### -->