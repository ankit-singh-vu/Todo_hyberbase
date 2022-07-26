<?php if(!\Kernel()->request()->isAjax()) { ?>

<!DOCTYPE html>
<html>
<head>
    <title>wpstack - ERROR</title>
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

<?php } ?>

            <div class="col-xl-10 col-md-6 col-xs-10 col-xl-offset-2 col-md-offset-3 col-xs-offset-10" <?=\Kernel()->request()->isAjax()?'style="max-width:700px;margin:auto;margin-top:5%"':''?>>

                <section class="widget widget-error animated fadeInUp" style="width:700px;box-shadow: 0 0 10px #ccc">
                    <header>
                        <h3 style="color:#cc0000;border-bottom: 2px dotted #cc0000;padding-bottom:5px;">
                            <i class="fa fa-warning"></i>
                            Oops, something went wrong !!!</h3>
                    </header>
                    <div class="widget-body" style="color:#333">

                        There was an error while serving your last request. Our technical team has been already informed
                        about the incident. Please try after sometime. If the issue persists then, please feel free to
                        get in touch with support, to solve the issue.


                    </div>
                </section>
            </div>

<?php if(!\Kernel()->request()->isAjax()) { ?>


        </div>
    </main>
</div>



<!-- The Loader. Is shown when pjax happens -->
<div class="loader-wrap hiding hide">
    <i class="fa fa-circle-o-notch fa-spin-fast"></i>
</div>

<!-- common libraries. required for every page-->
<script src="/assets/vendor/jquery/dist/jquery.min.js"></script>

<!-- page specific libs -->
<!-- page specific js -->
</body>
</html>

<?php } ?>