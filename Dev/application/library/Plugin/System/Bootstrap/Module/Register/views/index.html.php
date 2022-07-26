<!DOCTYPE html>
<html>
<head>
    <title><?=\Kernel()->config('app.title')?> - Registration</title>
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
    <main id="content" class="widget-login-container" role="main" style="padding-top: 2%">

        <h2 style="text-align:center;font-family: Times New Roman, Times, serif"><span style="font-weight:bold;color:#000;font-size:40px;">Advisor</span><span style="font-family:monospace;color:#007100">Algorithm<span style="font-size:17px;color:#000">.com</span></span></h2>
        <p style="font-size:20px;font-family: Arial;text-align:center;margin-bottom:20px;">
            How To Grow Your Financial Advisory Practice (Investment & Insurance) Using Social Media <br/> By
            Generating 5-25+ Appointments per Month & Get Clients
        </p>

        <h3 style="text-align: center;font-family: Arial;margin-bottom:20px;">Trusted by <span style="color:#3C5894;font-weight:bold">300</span> <span style="color:#cc0000">(dynamic counter)</span> Advisors</h3>

        <div class="row">
            <div class="col-xl-4 col-md-6 col-xs-10 col-xl-offset-4 col-md-offset-3 col-xs-offset-1">



                <section class="widget widget-login animated fadeInUp" style="box-shadow: 0 0 10px #999">

                    <?php /*
                    <div style="width: auto;text-align: center;margin-bottom:10px;">
                    <span style="font-family: 'Titillium Web', sans-serif;font-size:22px;">
                        <?=\Kernel()->config('app.plugin.system.bootstrap.logo.full')?>
                    </span>
                    </div>
                    */ ?>


                    <header>
                        <h4 style="font-family: Arial;text-align: center">Get 7 Day FREE Program Trial</h4>
                    </header>
                    <div class="widget-body" style="margin-top:0">
                        <p class="widget-login-info" style="color:#444;font-family: Arial;text-align: center;">
                            Sign up in 30 second, no credit card required
                        </p>
                        <?php if(isset($error)) { ?>
                        <p class="widget-login-info" style="color:#CC0000;font-family: Arial;text-align: center;font-size:15px;">
                           <?=$error?>
                        </p>
                        <?php } ?>
                        <form action="/register" method="post" class="login-form mt-lg">
                            <input type="hidden" value="<?=$registration_key?>" name="key"/>
                            <input type="hidden" value="<?=$registration_signature?>" name="signature"/>
                            <div class="form-group">
                                <input type="text" name="firstname" value="<?=isset($_POST['firstname'])?$_POST['firstname']:''?>" class="form-control" id="inputFullname" placeholder="Your First Name">
                            </div>
                            <div class="form-group">
                                <input type="text" name="lastname" value="<?=isset($_POST['lastname'])?$_POST['lastname']:''?>" class="form-control" id="inputLastname" placeholder="Your Last Name">
                            </div>
                            <div class="form-group">
                                <input type="text" name="email" value="<?=isset($_POST['email'])?$_POST['email']:''?>" class="form-control" id="inputEmail" placeholder="Your Email">
                            </div>
                            <div class="form-group" style="width:140px;float:left;margin-right:10px;">
                                <input class="form-control" name="password" value="<?=isset($_POST['password'])?$_POST['password']:''?>" id="pswd" type="password" placeholder="Password">
                            </div>
                            <div class="form-group" style="width:143px;float:left;">
                                <input class="form-control" name="repassword" value="<?=isset($_POST['repassword'])?$_POST['repassword']:''?>" id="repswd" type="password" placeholder="Retype Password">
                            </div>
                            <div style="clear: both"></div>

                            <?php /*
                            <div class="form-group" style="background-color: #FFFFAC;padding: 10px;border: 1px solid #ccc;border-radius: 5px;">
                                <label>Select a Plan</label>
                                <select name="plan" class="form-control">
                                    <option value="">--</option>
                                    <?php foreach(\Model\Plan::find_all_by_private(0) as $plan) { ?>
                                    <option <?=isset($_POST['plan'])?'selected':''?> value="<?=$plan->uuid?>"><?=$plan->name?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            */ ?>

                            <div class="clearfix">
                                <div class="btn-toolbar pull-xs-right">
                                    <!--<button type="button" class="btn btn-secondary btn-sm">Create an Account</button> !-->
                                    <button class="btn btn-inverse btn-sm">Continue</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>


        <div class="row" style="margin-top:15px;">
            <div class="col-xl-4 col-md-6 col-xs-10 col-xl-offset-4 col-md-offset-3 col-xs-offset-1">



                <section class="widget widget-login animated fadeInUp" style="box-shadow: 0 0 10px #999">

                    <a href="#" class="btn btn-sm btn-primary" style="width:100%;font-weight:bold;margin-bottom:15px;font-size:18px;"><span class="fa fa-facebook-square"></span> Facebook</a>

                    <a href="#" class="btn btn-sm btn-primary" style="width:100%;font-weight:bold;background-color:#0E72A3;font-size:18px;"><span class="fa fa-linkedin-square"></span>  LinkedIN</a>

                </section>
            </div>
        </div>


    </main>
    <footer class="page-footer">
        2017-<?=date('Y')?> &copy; <a href="<?=\Kernel()->config('app.protocol')?>://<?=\Kernel()->config('app.brand_domain')?>" target="_blank"><?=\Kernel()->config('app.brand_domain')?></a> All rights reserved.
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
</body>
</html>