<html lang="en">

<head>
    <!-- Meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?php echo $this->security->xss_clean($this->siteDescription) ?>">
    <meta name="keywords" content="<?php echo $this->security->xss_clean($this->siteKeywords) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- /meta tags -->
    <title>Error</title>

    <!-- Site favicon -->
    <link rel="shortcut icon" href="./images/favicon.png">
    <!-- /site favicon -->

    <!-- Load Styles -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/style.min.css">
    <!-- /load styles -->
</head>

<body class="dt-sidebar--fixed dt-header--fixed theme-semidark dt-layout--full-width">
    <!-- Root -->
    <div class="dt-root op-1">
        <div class="dt-root__inner">

            <!-- Login Container -->
            <div class="dt-page--container">

                <!-- 404 Page -->
                <div class="error-page text-center">

                    <!-- Title -->
                    <h1 class="title">404</h1>
                    <!-- /title -->

                    <p class="display-2 text-dark mb-7"><?php echo lang('page_not_found') ?></p>
                    <p class="mb-10"><?php echo lang('link_is_broken_or_page_removed') ?></p>
                    <a href="javascript:history.back()" class="btn btn-info btn-sm"><?php echo lang('back') ?></a>

                    <!---->

                </div>
                <!-- /404 page -->

            </div>
            <!-- /login container -->
        </div>

    </div>
    <!-- /root -->
</body>

</html>