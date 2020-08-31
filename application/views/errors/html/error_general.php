<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- /meta tags -->
    <title>Error</title>

    <!-- Site favicon -->
    <link rel="shortcut icon" href="./images/favicon.png">
    <!-- /site favicon -->

    <!-- Load Styles -->
    <link rel="stylesheet" href="../assets/dist/css/style.min.css">
    <!-- /load styles -->
</head>
<body class="dt-sidebar--fixed dt-header--fixed theme-semidark dt-layout--full-width" style="overflow: auto;">
    <style></style>

    <!-- Loader -->
    <div class="dt-loader-container" style="display: none;">
        <div class="dt-loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10">
                </circle>
            </svg>
        </div>
    </div>
    <!-- /loader -->
    <!-- Root -->
    <div class="dt-root" style="opacity: 1;">
        <div class="dt-root__inner">

            <!-- Login Container -->
            <div class="dt-page--container">

                <!-- 404 Page -->
                <div class="error-page text-center">

                    <!-- Title -->
                    <h1 class="title">404</h1>
                    <!-- /title -->

                    <p class="display-2 text-dark mb-7">Sorry! <?php echo $heading; ?></p>
                    <p class="mb-10"><?php echo $message; ?></p>
                    <a href="javascript:history.back()" class="btn btn-info btn-sm">Go Back</a>

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