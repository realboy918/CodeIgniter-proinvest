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
    <!-- Bootstrap CSS -->
    
    <link rel="shortcut icon" href="<?php echo $this->favicon ?>">
    
    <!-- Font Icon Styles -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/icons.css">
    <!-- /font icon Styles -->
    <!-- Load Styles -->
    <link rel="stylesheet" href="<?php echo base_url('assets/dist/css/bootstrap-formhelpers.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/flag-icon.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/chartist.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/style.min.css">
    <!-- /load styles -->
    <!-- include summernote css/js -->
    <link href="<?php echo base_url(); ?>assets/dist/summernote/summernote-bs4.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/intlTelInput.css">

    <script src="<?php echo base_url(); ?>assets/dist/js/jquery.min.js"></script>
    <?php if($this->uri->segment(1)=="signup" || $this->uri->segment(1)=="login") {?>
    <?php if($companyInfo['google_recaptcha'] == '1') {?>
        <?php if($companyInfo['recaptcha_version'] == 'v2') {?>
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <?php } else if($companyInfo['recaptcha_version'] == 'v3') {?>
            <script src='https://www.google.com/recaptcha/api.js?render=<?php echo $recaptchaInfo->public_key; ?>'></script>
            <script>
                grecaptcha.ready(function () {
                    grecaptcha.execute('<?php echo $recaptchaInfo->public_key; ?>').then(function (token) {
                        var recaptchaResponse = document.getElementById('recaptchaResponse');
                        recaptchaResponse.value = token;
                    });
                });
            </script>
        <?php }?>
    <?php }}?>
</head>
    <body class="dt-header--fixed theme-dark dt-layout--full-width dt-sidebar--fixed o-auto">
        <!-- Root -->
        <div class="dt-root op-1">
            <div class="dt-root__inner">
                <?php if($pageTitle=="Login" OR $this->uri->segment(1)=="signup" OR $pageTitle=="Forgot Password" OR $pageTitle=="Reset Password" ) {?>
                <?php } else { ?>
                <!-- Header -->
                <header class="dt-header">
                    <!-- Header container -->
                    <div class="dt-header__container">
                        <!-- Brand -->
                        <div class="dt-brand">
                            <!-- Brand tool -->
                            <div class="dt-brand__tool" data-toggle="main-sidebar">
                                <div class="hamburger-inner"></div>
                            </div>
                            <!-- /brand tool -->

                            <!-- Brand logo -->
                            <span class="dt-brand__logo">
                                <a class="dt-brand__logo-link" href="<?php echo base_url() ?>">
                                <img class="d-none d-sm-inline-block w-100" src="<?php echo $this->security->xss_clean($this->logoDark); ?>" alt="logo">
                                <img class="dt-brand__logo-symbol d-sm-none" src="<?php echo $this->security->xss_clean($this->logoDark); ?>" alt="logo">
                                </a>
                            </span>
                            <!-- /brand logo -->

                        </div>
                        <!-- /brand -->

                        <!-- Header toolbar-->
                        <div class="dt-header__toolbar">
                        

                            <div class="search-box d-none d-lg-block">
                            <?php if($displayBreadcrumbs == true) {?>
                                <h1 class="dt-page__title mt-4"><?php echo $breadcrumbs ?></h1>
                            <?php } else {?>
                                <form method="post">
                                <?php $csrf = array(
                                            'name' => $this->security->get_csrf_token_name(),
                                            'hash' => $this->security->get_csrf_hash()
                                    ); ?>
                                    <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
                                    <div class="input-group">
                                        <input class="form-control" placeholder="Search <?php echo $pageTitle; ?>"
                                            name="searchText" value="<?php echo html_purify(set_value('searchText')); ?>" type="search">
                                        <span class="search-icon"><i class="icon icon-search icon-lg"></i></span>
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><?php echo lang("search") ?>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <?php }?>
                            </div>

                            <!-- Header Menu Wrapper -->
                            <div class="dt-nav-wrapper">
                                <!-- Header Menu -->
                                <ul class="dt-nav d-lg-none">
                                    <li class="dt-nav__item dt-notification-search dropdown">

                                        <!-- Dropdown Link -->
                                        <a href="#" class="dt-nav__link dropdown-toggle no-arrow" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false"> <i
                                                class="icon icon-search icon-fw icon-lg"></i> </a>
                                        <!-- /dropdown link -->

                                        <!-- Dropdown Option -->
                                        <div class="dropdown-menu">

                                            <!-- Search Box -->
                                            <form class="search-box right-side-icon">
                                                <input class="form-control form-control-lg" type="search"
                                                    placeholder="Search in app...">
                                                <button type="submit" class="search-icon"><i
                                                        class="icon icon-search icon-lg"></i></button>
                                            </form>
                                            <!-- /search box -->

                                        </div>
                                        <!-- /dropdown option -->

                                    </li>
                                </ul>
                                <!-- /header menu -->
                                <!-- Header Menu -->
                                <ul class="dt-nav">
                                    <li class="dt-nav__item dropdown">
      
                                        <!-- Dropdown Link -->
                                        <a href="#" class="dt-nav__link dropdown-toggle" id='currentLang' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <img class="flag-icon flag-icon-rounded flag-icon-lg mr-1m" src="<?php echo base_url('uploads/').$userLang->logo ?>">
                                        <span><?php echo $userLang->code; ?></span> </a>
                                        <!-- /dropdown link -->

                                        <!-- Dropdown Option -->
                                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(8px, 72px, 0px); top: 0px; left: 0px; will-change: transform;">
                                            <?php foreach($languages as $lang) {?>
                                            <button class="dropdown-item sitelangChange" type="button" data-id="<?php echo base_url('switchlang/').$lang->name ?>">
                                            <img class="flag-icon flag-icon-rounded flag-icon-lg mr-2" src="<?php echo base_url('uploads/').$lang->logo ?>"><span><?php echo $lang->name; ?></span> </button>
                                            <?php }?>
                                        </div>
                                        <!-- /dropdown option -->

                                    </li>
                                </ul>
                                <!-- /header menu -->

                                <!-- Header Menu -->
                                <ul class="dt-nav">
                                    <li class="dt-nav__item dropdown">

                                        <!-- Dropdown Link -->
                                        <a href="#" class="dt-nav__link dropdown-toggle no-arrow dt-avatar-wrapper"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <img class="dt-avatar size-30"
                                                src="<?php echo $ppic; ?>"
                                                alt="<?php echo $firstName.' '.$lastName; ?>">
                                            <span class="dt-avatar-info d-none d-sm-block">
                                                <span
                                                    class="dt-avatar-name"><?php echo $firstName.' '.$lastName; ?></span>
                                            </span> </a>
                                        <!-- /dropdown link -->

                                        <!-- Dropdown Option -->
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="<?php echo base_url(); ?>profile"> <i
                                                    class="icon icon-user icon-fw mr-2 mr-sm-1"></i><?php echo lang("account") ?>
                                            </a>
                                            <a class="dropdown-item" href="<?php echo base_url(); ?>logout"> <i
                                                    class="icon icon-editors icon-fw mr-2 mr-sm-1"></i><?php echo lang("logout") ?>
                                            </a>
                                        </div>
                                        <!-- /dropdown option -->

                                    </li>
                                </ul>
                                <!-- /header menu -->
                            </div>
                            <!-- Header Menu Wrapper -->

                        </div>
                        <!-- /header toolbar -->

                    </div>
                    <!-- /header container -->

                </header>
                <!-- /header -->

                <!-- Site Main -->
                <main class="dt-main">
                    <!-- Sidebar -->
                    <aside id="main-sidebar" class="dt-sidebar ps ps--active-y">
                        <?php if($pageTitle == 'Tickets') {?>
                            <app-task-sidebar class="ng-tns-c266-22 dt-module__sidebar active" style="max-width: none;border-right: none;">
                                <div class="dt-module__sidebar-header ng-tns-c266-22">
                                    <div class="quick-menu-list ng-tns-c266-22">
                                        <!---->
                                        <form novalidate="" class="search-box d-md-none ng-tns-c266-22 ng-untouched ng-pristine ng-valid ng-star-inserted">
                                            <input type="search" id="address" name="address" placeholder="Search in app..." class="form-control ng-tns-c266-22">
                                            <button type="submit" class="search-icon ng-tns-c266-22">
                                                <gx-icon name="search" size="lg" class="ng-tns-c266-22 icon icon-search icon-lg"></gx-icon>
                                            </button>
                                        </form>
                                        <!---->
                                        <a href="<?php echo base_url('dashboard') ?>" class="text-dark ng-star-inserted">
                                            <i name="arrow-left" size="1x" class="icon icon-arrow-left icon-1x icon-fw"></i>
                                            <span class="display-6 align-middle ml-1"><?php echo lang('main_dashboard') ?></span>
                                        </a>
                                    </div>
                                </div>
                                <div _ngcontent-ovt-c266="" perfectscrollbar="" class="dt-module__sidebar-content position-relative ng-tns-c266-22 ng-trigger ng-trigger-collapseAppSidebar ps ps--active-y" style="display: block; opacity: 1;">
                                    <div _ngcontent-ovt-c266="" class="dt-module__sidebar-content-inner pt-md-7 ng-tns-c266-22">
                                        <ul _ngcontent-ovt-c266="" class="dt-module-side-nav ng-tns-c266-22">
                                            <li _ngcontent-ovt-c266="" class="dt-module-side-nav__header ng-tns-c266-22">
                                                <span _ngcontent-ovt-c266="" class="dt-module-side-nav__text ng-tns-c266-22"><?php echo lang('tickets') ?></span>
                                                <?php if(isset($_SESSION['helpdesk_priority']) || isset($_SESSION['helpdesk_team'])) {?>
                                                <span _ngcontent-ovt-c266="" class="dt-module-side-nav__text ng-tns-c266-22" style="float: right;">
                                                    <a href="#" id="ticketFilterOff">
                                                        <span class="dt-module-side-nav__text ng-tns-c266-22"><?php echo lang('remove_filters') ?></span>
                                                    </a>
                                                </span>
                                                <?php }?>
                                            </li>
                                            <li class="dt-module-side-nav__item ng-tns-c266-22">
                                                <a class="dt-module-side-nav__link ng-tns-c266-22" id="newTicket" href="#" style="width: 100%;">
                                                    <i name="dashboard" size="lg" class="ng-tns-c266-22 icon icon-description icon-lg icon-fw"></i>
                                                    <span class="dt-module-side-nav__text ng-tns-c266-22"><?php echo lang('create_ticket') ?></span>
                                                </a>
                                            </li>
                                            <li class="dt-module-side-nav__item ng-tns-c266-22 <?php if($this->uri->segment(1)=="tickets" && $this->uri->segment(2)==""){echo "active";}?>">
                                                <a class="dt-module-side-nav__link ng-tns-c266-22" href="<?php echo base_url('tickets') ?>" style="width: 100%;">
                                                    <i name="dashboard" size="lg" class="ng-tns-c266-22 icon icon-forms-basic icon-lg icon-fw"></i>
                                                    <span class="dt-module-side-nav__text ng-tns-c266-22"><?php echo lang('all_tickets') ?></span>
                                                </a>
                                            </li>
                                            <li class="dt-module-side-nav__item ng-tns-c266-22 <?php if($this->uri->segment(1)=="tickets" && $this->uri->segment(2)=="open"){echo "active";}?>">
                                                <a class="dt-module-side-nav__link ng-tns-c266-22" href="<?php echo base_url('tickets/open') ?>">
                                                    <i name="users" size="lg" class="ng-tns-c266-22 icon icon-open-mail icon-lg icon-fw"></i>
                                                    <span class="dt-module-side-nav__text ng-tns-c266-22"><?php echo lang('pending_tickets') ?></span>
                                                </a>
                                            </li>
                                            <li class="dt-module-side-nav__item ng-tns-c266-22 <?php if($this->uri->segment(1)=="tickets" && $this->uri->segment(2)=="resolved"){echo "active";}?>">
                                                <a class="dt-module-side-nav__link ng-tns-c266-22" href="<?php echo base_url('tickets/resolved') ?>">
                                                    <i name="users" size="lg" class="ng-tns-c266-22 icon icon-task-manager icon-lg icon-fw"></i>
                                                    <span class="dt-module-side-nav__text ng-tns-c266-22"><?php echo lang('resolved_tickets') ?></span>
                                                </a>
                                            </li>
                                            <?php if($role != ROLE_CLIENT) {?>
                                            <li class="dt-module-side-nav__header ng-tns-c266-22">
                                                <span class="dt-module-side-nav__text ng-tns-c266-22"><?php echo lang('status') ?></span>
                                            </li>
                                            <li class="dt-module-side-nav__item ng-tns-c266-22 ng-star-inserted" style="">
                                                <a class="dt-module-side-nav__link ng-tns-c266-22" href="#">
                                                    <gx-checkbox class="dt-checkbox dt-checkbox-icon dt-checkbox-only">
                                                        <input type="checkbox" name="high-priority" id="gx-checkbox-226" class="checkbox-check ng-pristine ng-valid ng-touched" <?php echo isset($_SESSION['helpdesk_priority']) && $_SESSION['helpdesk_priority'] == 'high' ? 'checked' : '' ?>>
                                                        <label class="font-weight-light dt-checkbox-content" for="gx-checkbox-226">
                                                            <span class="unchecked">
                                                                <i name="box-o" size="xl" class="icon icon-box-o icon-xl icon-fw"></i>
                                                            </span>
                                                            <span class="checked ng-star-inserted">
                                                                <i name="box-check-o" size="xl" class="text-primary icon icon-box-check-o icon-xl icon-fw"></i>
                                                            </span>
                                                        </label>
                                                    </gx-checkbox>
                                                    <span class="dt-module-side-nav__text ng-tns-c266-22"><?php echo lang('high_priority') ?></span>
                                                </a>
                                            </li>
                                            <li class="dt-module-side-nav__item ng-tns-c266-22 ng-star-inserted" style="">
                                                <a class="dt-module-side-nav__link ng-tns-c266-22" href="#">
                                                <gx-checkbox class="dt-checkbox dt-checkbox-icon dt-checkbox-only">
                                                        <input type="checkbox" name="medium-priority" id="gx-checkbox-227" class="checkbox-check ng-pristine ng-valid ng-touched" <?php echo isset($_SESSION['helpdesk_priority']) && $_SESSION['helpdesk_priority'] == 'medium' ? 'checked' : '' ?>>
                                                        <label class="font-weight-light dt-checkbox-content" for="gx-checkbox-227">
                                                            <span class="unchecked">
                                                                <i name="box-o" size="xl" class="icon icon-box-o icon-xl icon-fw"></i>
                                                            </span>
                                                            <span class="checked ng-star-inserted">
                                                                <i name="box-check-o" size="xl" class="text-primary icon icon-box-check-o icon-xl icon-fw"></i>
                                                            </span><!----><!---->
                                                        </label>
                                                    </gx-checkbox>
                                                    <span class="dt-module-side-nav__text ng-tns-c266-22"><?php echo lang('medium_priority') ?></span
                                                ></a>
                                            </li><!---->
                                            <li class="dt-module-side-nav__item ng-tns-c266-22 ng-star-inserted" style="">
                                                <a class="dt-module-side-nav__link ng-tns-c266-22" href="#">
                                                <gx-checkbox class="dt-checkbox dt-checkbox-icon dt-checkbox-only">
                                                        <input type="checkbox" name="medium-priority" id="gx-checkbox-228" class="checkbox-check ng-pristine ng-valid ng-touched" <?php echo isset($_SESSION['helpdesk_priority']) && $_SESSION['helpdesk_priority'] == 'low' ? 'checked' : '' ?>>
                                                        <label class="font-weight-light dt-checkbox-content" for="gx-checkbox-228">
                                                            <span class="unchecked">
                                                                <i name="box-o" size="xl" class="icon icon-box-o icon-xl icon-fw"></i>
                                                            </span>
                                                            <span class="checked ng-star-inserted">
                                                                <i name="box-check-o" size="xl" class="text-primary icon icon-box-check-o icon-xl icon-fw"></i>
                                                            </span><!----><!---->
                                                        </label>
                                                    </gx-checkbox>
                                                    <span class="dt-module-side-nav__text ng-tns-c266-22"><?php echo lang('low_priority') ?></span
                                                ></a>
                                            </li><!---->
                                            <?php }?>
                                        </ul>
                                    </div>
                                </div>
                            </app-task-sidebar>
                            <?php } else if($this->uri->segment(1)=="webcontrol") {?>
                            <app-task-sidebar class="ng-tns-c266-22 dt-module__sidebar active" style="max-width: none;border-right: none;">
                                <div class="dt-module__sidebar-header ng-tns-c266-22">
                                    <div class="quick-menu-list ng-tns-c266-22">
                                        <!---->
                                        <form novalidate="" class="search-box d-md-none ng-tns-c266-22 ng-untouched ng-pristine ng-valid ng-star-inserted">
                                            <input type="search" id="address" name="address" placeholder="Search in app..." class="form-control ng-tns-c266-22">
                                            <button type="submit" class="search-icon ng-tns-c266-22">
                                                <gx-icon name="search" size="lg" class="ng-tns-c266-22 icon icon-search icon-lg"></gx-icon>
                                            </button>
                                        </form>
                                        <!---->
                                        <a href="<?php echo base_url('dashboard') ?>" class="text-dark ng-star-inserted">
                                            <i name="arrow-left" size="1x" class="icon icon-arrow-left icon-1x icon-fw"></i>
                                            <span class="display-6 align-middle ml-1"><?php echo lang('main_dashboard') ?></span>
                                        </a>
                                    </div>
                                </div>
                                <div class="dt-module__sidebar-content position-relative ng-tns-c266-22 ng-trigger ng-trigger-collapseAppSidebar ps ps--active-y" style="display: block; opacity: 1;">
                                    <div class="dt-module__sidebar-content-inner pt-md-7 ng-tns-c266-22">
                                        <ul class="dt-module-side-nav ng-tns-c266-22">
                                            <li class="dt-module-side-nav__item ng-tns-c266-22 <?php if($this->uri->segment(1)=="webcontrol" && $this->uri->segment(2)=="templates"){echo "active";}?>">
                                                <a class="dt-module-side-nav__link ng-tns-c266-22" id="newTicket" href="<?php echo base_url('webcontrol/templates') ?>" style="width: 100%;">
                                                    <i name="dashboard" size="lg" class="ng-tns-c266-22 icon icon-layout icon-lg icon-fw"></i>
                                                    <span class="dt-module-side-nav__text ng-tns-c266-22"><?php echo lang('templates') ?></span>
                                                </a>
                                            </li>
                                            <li class="dt-module-side-nav__item ng-tns-c266-22 <?php if($this->uri->segment(1)=="webcontrol" && $this->uri->segment(2)=="terms"){echo "active";}?>">
                                                <a class="dt-module-side-nav__link ng-tns-c266-22" href="<?php echo base_url('webcontrol/terms') ?>" style="width: 100%;">
                                                    <i name="dashboard" size="lg" class="ng-tns-c266-22 icon icon-forms-basic icon-lg icon-fw"></i>
                                                    <span class="dt-module-side-nav__text ng-tns-c266-22"><?php echo lang('terms_and_conditions') ?></span>
                                                </a>
                                            </li>
                                            <li class="dt-module-side-nav__item ng-tns-c266-22 <?php if($this->uri->segment(1)=="webcontrol" && $this->uri->segment(2)=="policy"){echo "active";}?>">
                                                <a class="dt-module-side-nav__link ng-tns-c266-22" href="<?php echo base_url('webcontrol/policy') ?>" style="width: 100%;">
                                                    <i name="users" size="lg" class="ng-tns-c266-22 icon icon-description icon-lg icon-fw"></i>
                                                    <span class="dt-module-side-nav__text ng-tns-c266-22"><?php echo lang('privacy_policy') ?></span>
                                                </a>
                                            </li>
                                            <li class="dt-module-side-nav__item ng-tns-c266-22 <?php if($this->uri->segment(1)=="webcontrol" && $this->uri->segment(2)=="faqs"){echo "active";}?>">
                                                <a class="dt-module-side-nav__link ng-tns-c266-22" href="<?php echo base_url('webcontrol/faqs') ?>" style="width: 100%;">
                                                    <i name="users" size="lg" class="ng-tns-c266-22 icon icon-question icon-lg icon-fw"></i>
                                                    <span class="dt-module-side-nav__text ng-tns-c266-22"><?php echo lang('faqs') ?></span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </app-task-sidebar>
                        <?php } else {?>
                        <div class="dt-sidebar__container mt-10">

                            <!-- Sidebar Navigation -->
                            <ul class="dt-side-nav">
                                <?php if($role == ROLE_CLIENT) { ?>
                                <li
                                    class="dt-side-nav__item <?php if($this->uri->segment(1)=="dashboard"){echo "selected";}?>">
                                    <a href="<?php echo base_url(); ?>dashboard"
                                        class="dt-side-nav__link <?php if($this->uri->segment(1)=="dashboard"){echo "dt-active";}?>"
                                        title="Dashboard">
                                        <i class="icon icon-dashboard icon-fw icon-lg"></i>
                                        <span class="dt-side-nav__text"><?php echo lang("dashboard") ?></span>
                                    </a>
                                </li>
                                <li
                                    class="dt-side-nav__item <?php if($this->uri->segment(1)=="deposits"){echo "open selected";}?>">
                                    <a href="javascript:void(0)" class="dt-side-nav__link dt-side-nav__arrow"
                                        title="Dashboard">
                                        <i class="icon icon-revenue2 icon-fw icon-lg"></i> <span
                                            class="dt-side-nav__text"><?php echo lang("deposits") ?></span> </a>

                                    <!-- Sub-menu -->
                                    <ul class="dt-side-nav__sub-menu <?php echo $this->uri->segment(1)=="deposits" ? "display-b" : "display-n"?>">
                                        <li
                                            class="dt-side-nav__item open <?php if($this->uri->segment(1)=="deposits" && $this->uri->segment(2)=="new"){echo "selected";}?>">
                                            <a href="<?php echo base_url(); ?>deposits/new"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="deposits" && $this->uri->segment(2)=="new"){echo "active";}?>"
                                                title="Traffic">
                                                <span class="dt-side-nav__text"><?php echo lang("deposit_funds") ?></span> </a>
                                        </li>

                                        <li class="dt-side-nav__item">
                                            <a href="<?php echo base_url(); ?>deposits"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="deposits" && $this->uri->segment(2)==""){echo "active";}?>"
                                                title="Revenue">
                                                <span class="dt-side-nav__text"><?php echo lang("view_deposits") ?></span> </a>
                                        </li>
                                    </ul>
                                    <!-- /sub-menu -->

                                </li>
                                <li
                                    class="dt-side-nav__item <?php if($this->uri->segment(1)=="withdrawals"){echo "selected open";}?>">
                                    <a href="javascript:void(0)" class="dt-side-nav__link dt-side-nav__arrow"
                                        title="Dashboard">
                                        <i class="icon icon-revenue-new icon-fw icon-lg"></i><span
                                            class="dt-side-nav__text"><?php echo lang("withdrawals") ?></span> </a>

                                    <!-- Sub-menu -->
                                    <ul class="dt-side-nav__sub-menu <?php echo $this->uri->segment(1)=="withdrawals" ? "display-b" : "display-n"?>">
                                        <li
                                            class="dt-side-nav__item open <?php if($this->uri->segment(1)=="withdrawals"){echo "selected";}?>">
                                            <a href="<?php echo base_url(); ?>withdrawals/new"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="withdrawals" && $this->uri->segment(2)=="new"){echo "active";}?>"
                                                title="Traffic">
                                                <span class="dt-side-nav__text"><?php echo lang("withdraw_funds") ?></span> </a>
                                        </li>

                                        <li class="dt-side-nav__item">
                                            <a href="<?php echo base_url(); ?>withdrawals"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="withdrawals" && $this->uri->segment(2)==""){echo "active";}?>"
                                                title="Revenue">
                                                <span class="dt-side-nav__text"><?php echo lang("view_withdrawals") ?></span> </a>
                                        </li>
                                    </ul>
                                    <!-- /sub-menu -->

                                </li>
                                <li
                                    class="dt-side-nav__item <?php if($this->uri->segment(1)=="earnings"){echo "selected";}?>">
                                    <a href="<?php echo base_url(); ?>earnings"
                                        class="dt-side-nav__link <?php if($this->uri->segment(1)=="earnings"){echo "dt-active";}?>"
                                        title="Dashboard">
                                        <i class="icon icon-dashboard icon-fw icon-lg"></i>
                                        <span class="dt-side-nav__text"><?php echo lang("earnings") ?></span>
                                    </a>
                                </li>
                                <li
                                    class="dt-side-nav__item <?php if($this->uri->segment(1)=="tickets"){echo "selected";}?>">
                                    <a href="<?php echo base_url(); ?>tickets" class="dt-side-nav__link"
                                        title="Support">
                                        <i class="icon icon-mail icon-fw icon-lg"></i>
                                        <span class="dt-side-nav__text">Help Desk</span>
                                    </a>
                                </li>
                                <!-- Menu Header -->
                                <?php } else {?>

                                <li
                                    class="dt-side-nav__item <?php if($this->uri->segment(1)=="dashboard"){echo "selected";}?>">
                                    <a href="<?php echo base_url(); ?>dashboard"
                                        class="dt-side-nav__link <?php if($this->uri->segment(1)=="dashboard"){echo "dt-active";}?>"
                                        title="Dashboard">
                                        <i class="icon icon-dashboard icon-fw icon-lg"></i>
                                        <span class="dt-side-nav__text"><?php echo lang("dashboard") ?></span>
                                    </a>
                                </li>
                                <?php if($this->user_model->getPermissions('deposits', 'view', $userId) OR
                                $this->user_model->getPermissions('withdrawals', 'view', $userId) OR
                                $this->user_model->getPermissions('payouts', 'view', $userId) OR
                                $role == ROLE_ADMIN) {?>
                                <li
                                    class="dt-side-nav__item <?php if($this->uri->segment(1)=="withdrawals" || $this->uri->segment(1)=="deposits" || $this->uri->segment(1)=="earnings"){echo "selected open";}?>">
                                    <a href="javascript:void(0)" class="dt-side-nav__link dt-side-nav__arrow"
                                        title="Dashboard">
                                        <i class="icon icon-revenue-new icon-fw icon-lg"></i><span
                                            class="dt-side-nav__text"><?php echo lang("transactions") ?></span> </a>

                                    <!-- Sub-menu -->
                                    <ul class="dt-side-nav__sub-menu <?php echo $this->uri->segment(1)=="withdrawals" || $this->uri->segment(1)=="deposits" || $this->uri->segment(1)=="earnings" ? "display-b" : "display-n"?>">
                                        <?php if($this->user_model->getPermissions('deposits', 'view', $userId) OR $role == ROLE_ADMIN) {?>
                                        <li
                                            class="dt-side-nav__item open <?php if($this->uri->segment(1)=="deposits"){echo "selected";}?>">
                                            <a href="<?php echo base_url(); ?>deposits"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="deposits"){echo "active";}?>"
                                                title="Traffic">
                                                <span class="dt-side-nav__text"><?php echo lang("deposits") ?></span> </a>
                                        </li>
                                        <?php }?>
                                        <?php if($this->user_model->getPermissions('withdrawals', 'view', $userId) OR $role == ROLE_ADMIN) {?>
                                        <li class="dt-side-nav__item">
                                            <a href="<?php echo base_url(); ?>withdrawals"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="withdrawals"){echo "active";}?>"
                                                title="Revenue">
                                                <span class="dt-side-nav__text"><?php echo lang("withdrawals") ?></span> </a>
                                        </li>
                                        <?php }?>
                                        <?php if($this->user_model->getPermissions('payouts', 'view', $userId) OR $role == ROLE_ADMIN) {?>
                                        <li class="dt-side-nav__item">
                                            <a href="<?php echo base_url(); ?>earnings"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="earnings"){echo "active";}?>"
                                                title="Revenue">
                                                <span class="dt-side-nav__text"><?php echo lang("payouts") ?></span> </a>
                                        </li>
                                        <?php }?>
                                    </ul>
                                    <!-- /sub-menu -->

                                </li>
                                <?php }?>

                                <?php if($this->user_model->getPermissions('teams', 'view', $userId) OR
                                $this->user_model->getPermissions('clients', 'view', $userId) OR
                                $role == ROLE_ADMIN) {?>
                                <li
                                    class="dt-side-nav__item <?php if($this->uri->segment(1)=="clients" || $this->uri->segment(1)=="team"){echo "selected open";}?>">
                                    <a href="javascript:void(0)" class="dt-side-nav__link dt-side-nav__arrow"
                                        title="Dashboard">
                                        <i class="icon icon-contacts-app icon-fw icon-lg"></i><span
                                            class="dt-side-nav__text"><?php echo lang("users") ?></span> </a>

                                    <!-- Sub-menu -->
                                    <ul class="dt-side-nav__sub-menu <?php echo $this->uri->segment(1)=="clients" || $this->uri->segment(1)=="team" ? "display-b" : "display-n"?>">
                                        <?php if($this->user_model->getPermissions('clients', 'view', $userId) OR $role == ROLE_ADMIN) {?>
                                        <li
                                            class="dt-side-nav__item open <?php if($this->uri->segment(1)=="clients"){echo "selected";}?>">
                                            <a href="<?php echo base_url(); ?>clients"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="clients"){echo "active";}?>"
                                                title="Traffic">
                                                <span class="dt-side-nav__text"><?php echo lang("clients") ?></span> </a>
                                        </li>
                                        <?php }?>
                                        <?php if($this->user_model->getPermissions('teams', 'view', $userId) OR $role == ROLE_ADMIN) {?>
                                        <li class="dt-side-nav__item">
                                            <a href="<?php echo base_url(); ?>team"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="team"){echo "active";}?>"
                                                title="Revenue">
                                                <span class="dt-side-nav__text"><?php echo lang("team") ?></span> </a>
                                        </li>
                                        <?php }?>
                                    </ul>
                                    <!-- /sub-menu -->

                                </li>
                                <?php }?>
                                <?php if($this->user_model->getPermissions('plans', 'view', $userId) OR $role == ROLE_ADMIN) {?>
                                <li
                                    class="dt-side-nav__item <?php if($this->uri->segment(1)=="plans"){echo "selected";}?>">
                                    <a href="<?php echo base_url(); ?>plans" class="dt-side-nav__link"
                                        title="Contacts App">
                                        <i class="icon icon-list icon-fw icon-lg"></i>
                                        <span class="dt-side-nav__text"><?php echo lang("investment_plans") ?></span>
                                    </a>
                                </li>
                                <?php }?>
                                <?php if($this->user_model->getPermissions('settings', 'API_settings', $userId) OR $role == ROLE_ADMIN) {?>
                                    <li class="dt-side-nav__item">
                                        <a href="<?php echo base_url(); ?>settings/addons"
                                            class="dt-side-nav__link <?php if($this->uri->segment(1)=="settings" && $this->uri->segment(2)=="paymentsAPIs"){echo "active";}?>"
                                            title="Plugins">
                                            <i class="icon icon-customizer icon-fw icon-lg"></i>
                                            <span class="dt-side-nav__text"><?php echo lang("plugins") ?></span> </a>
                                    </li>
                                <?php }?>
                                <li class="dt-side-nav__item <?php if($this->uri->segment(1)=="tickets"){echo "selected";}?>">
                                    <a href="<?php echo base_url(); ?>tickets" class="dt-side-nav__link"
                                        title="Support">
                                        <i class="icon icon-mail icon-fw icon-lg"></i>
                                        <span class="dt-side-nav__text"><?php echo lang('tickets') ?></span>
                                    </a>
                                </li>
                                <li class="dt-side-nav__item" <?php if($this->uri->segment(1)=="webcontrol"){echo "selected";}?>">
                                    <a href="<?php echo base_url(); ?>webcontrol/templates"
                                        class="dt-side-nav__link <?php if($this->uri->segment(1)=="webcontrol"){echo "active";}?>"
                                        title="Web Control">
                                        <i class="icon icon-layout icon-fw icon-lg"></i>
                                        <span class="dt-side-nav__text"><?php echo lang('web_control') ?></span> </a>
                                </li>
                                <?php if($this->user_model->getPermissions('settings', 'email_templates', $userId) OR
                                    $this->user_model->getPermissions('settings', 'general_settings', $userId) OR
                                    $this->user_model->getPermissions('settings', 'API_settings', $userId) OR
                                    $this->user_model->getPermissions('settings', 'payment_methods', $userId) OR
                                    $role == ROLE_ADMIN) {?>
                                <li
                                    class="dt-side-nav__item <?php if($this->uri->segment(1)=="settings"){echo "selected open";}?>">
                                    <a href="javascript:void(0)" class="dt-side-nav__link dt-side-nav__arrow"
                                        title="Dashboard">
                                        <i class="icon icon-settings icon-fw icon-lg"></i><span
                                            class="dt-side-nav__text"><?php echo lang("settings") ?></span> </a>
                                    <!-- Sub-menu -->
                                    <ul class="dt-side-nav__sub-menu <?php echo $this->uri->segment(1)=="settings" ? "display-b" : "display-n"?>">
                                        <?php if($this->user_model->getPermissions('settings', 'email_templates', $userId) OR $role == ROLE_ADMIN) {?>
                                        <li
                                            class="dt-side-nav__item open <?php if($this->uri->segment(1)=="settings" && $this->uri->segment(2)=="email_templates"){echo "selected";}?>">
                                            <a href="<?php echo base_url(); ?>settings/email_templates"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="settings" && $this->uri->segment(2)=="email_templates"){echo "active";}?>"
                                                title="Traffic">
                                                <span class="dt-side-nav__text"><?php echo lang("email_templates") ?></span> </a>
                                        </li>
                                        <?php }?>

                                        <?php if($this->user_model->getPermissions('settings', 'general_settings', $userId) OR $role == ROLE_ADMIN) {?>
                                        <li class="dt-side-nav__item">
                                            <a href="<?php echo base_url(); ?>settings"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="settings" && $this->uri->segment(2)==""){echo "active";}?>"
                                                title="Revenue">
                                                <span class="dt-side-nav__text"><?php echo lang("general_settings") ?></span> </a>
                                        </li>
                                        <?php }?>

                                        <?php if($this->user_model->getPermissions('settings', 'payment_methods', $userId) OR $role == ROLE_ADMIN) {?>
                                        <li class="dt-side-nav__item">
                                            <a href="<?php echo base_url(); ?>settings/paymentMethods"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="settings" && $this->uri->segment(2)=="paymentMethods"){echo "active";}?>"
                                                title="Revenue">
                                                <span class="dt-side-nav__text"><?php echo lang("payment_methods") ?></span> </a>
                                        </li>
                                        <?php }?>

                                        <?php if($this->user_model->getPermissions('settings', 'languages', $userId) OR $role == ROLE_ADMIN) {?>
                                        <li class="dt-side-nav__item">
                                            <a href="<?php echo base_url(); ?>settings/languages"
                                                class="dt-side-nav__link <?php if($this->uri->segment(1)=="settings" && $this->uri->segment(2)=="languages"){echo "active";}?>"
                                                title="Language settings">
                                                <span class="dt-side-nav__text"><?php echo lang("languages") ?></span> </a>
                                        </li>
                                        <?php }?>
                                    </ul>
                                    <!-- /sub-menu -->
                                </li>
                                <?php }?>
                                <?php }?>

                                <!-- /menu item -->

                            </ul>
                            <!-- /sidebar navigation -->

                        </div>
                        <?php }?>
                    </aside>
                    <!-- /sidebar -->
                    <?php } ?>