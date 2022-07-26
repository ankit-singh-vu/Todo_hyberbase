<!DOCTYPE html>
<html>
<head>
    <title>Trial Ended - WebNGIN.com</title>
    <link href="/assets/css-old/application.css" rel="stylesheet">
    <!-- as of IE9 cannot parse css files with more that 4K classes separating in two files -->
    <!--[if IE 9]>
    <link href="/assets/css-old/application-ie9-part2.css" rel="stylesheet">
    <![endif]-->
    <link rel="shortcut icon" href="/assets/img/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Advisor Learn">
    <meta name="author" content="Dyutiman Chakraborty">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web:600" rel="stylesheet">
    <script>
        /* yeah we need this empty stylesheet here. It's cool chrome & chromium fix
         chrome fix https://code.google.com/p/chromium/issues/detail?id=167083
         https://code.google.com/p/chromium/issues/detail?id=332189
         */
    </script>
</head>
<body class="login-page">

<div class="container">
    <main id="content" class="widget-login-container" role="main">
        <div class="row">
            <div style="width:70%;margin-left:15%;" class="col-xl-6 col-md-6 col-xs-10 col-xl-offset-3 col-md-offset-3 col-xs-offset-1">
                <h5 class="widget-login-logo animated fadeInUp" style="box-shadow:0 0 10px #999;background-color: #813ACF;padding: 10px;margin-bottom: -3px;border-radius: 3px;">
                    <i class="fa fa-circle text-white"></i>
                    <span style="font-family: 'Titillium Web', sans-serif;font-size:22px;">
                        <?=\Kernel()->config('app.plugin.system.bootstrap.logo.full')?>
                    </span>
                    <i class="fa fa-circle text-white"></i>
                </h5>
                <section class="widget widget-login animated fadeInUp" style="box-shadow: 0 0 10px #999">
                    <header>
                        <h4 style="padding-top:5px;float:right;font-size:18px;font-weight:600;">Please upgrade your account</h4>
                        <h3 style="color:#cc0000"><span class="fa fa-warning"></span> Trial Ended !!! </h3>
                    </header>
                    <div class="widget-body" style="margin-top:0">
                            <p class="widget-login-info" style="color:#000;margin-top:10px;">
                                The trial period for this account has ended. To continue using our services please upgrade
                                your account by adding your payment information. If no action is taken with in <?=$account_close_in?>, then this account
                                and all related data will be permanently deleted from our system. For any questions or suggestions please contact us at support@webngin.com
                            </p>

                            <div style="float:right;margin-top:20px;">
                                <a style="color: #813ACF;font-weight:bold;" class="btn" href="/system/logout">Logout</a>
                                <a style="background-color: #813ACF;color: #fff;font-weight:bold;" class="add-payment-method btn btn-primary" href="#">Upgrade Account</a>
                            </div>
                            <div style="clear:both"></div>

                    </div>
                </section>
            </div>
        </div>
    </main>
    <footer class="page-footer">
        2016-<?=date('Y')?> &copy; <a href="https://<?=$kernel->config('app.cookie_domain')?>" target="_blank"><?=$kernel->config('app.identity')?></a> All rights reserved.
    </footer>
</div>
<!-- The Loader. Is shown when pjax happens -->
<div class="loader-wrap hiding hide">
    <i class="fa fa-circle-o-notch fa-spin-fast"></i>
</div>

<!-- common libraries. required for every page-->
<script src="/assets/vendor/jquery/dist/jquery.min.js"></script>

<!-- page specific libs -->
<!-- page specific js -->
<?php foreach(\Kernel()->events('ui')->filter('load_footer_content', array()) as $footer_content) { ?>
    <?=$footer_content?>
<?php } ?>


</body>
</html>