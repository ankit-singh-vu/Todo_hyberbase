<!DOCTYPE html>
<html>

<head>
    <title><?= \Kernel()->config('app.title') ?> - Signup</title>
    <!-- <link href="/assets/css-old/application.css" rel="stylesheet"> -->
    <link href="/v3/assets/css/dashforge.css" rel="stylesheet">
    <link href="/v3/assets/css/dashforge.auth.css" rel="stylesheet">
    <!-- as of IE9 cannot parse css files with more that 4K classes separating in two files -->
    <!--[if IE 9]>
    <link href="/assets/css-old/application-ie9-part2.css" rel="stylesheet">
    <![endif]-->
    <link rel="shortcut icon" href="/assets/img/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Advisor Learn">
    <meta name="author" content="Dyutiman Chakraborty">
    <link rel="icon" type="image/x-icon" href="/assets/img/fav_1.png">
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

<body style="background: #000;">

    <div class="pd-x-0" style="min-height: 100%">
        <div class="container-fluid pd-x-0 d-flex align-items-center justify-content-center" style="min-height: 100vh">
        <h2 style="position: fixed; top: 10px; left: 30px;" class="font-weight-bold"><img src="/assets/img/alogo.png" style="height: 60px; width: 350px" alt=""></h2>
            <div class="row col-12 mx-auto ht-100p mg-r-0 pd-r-0" style="min-height: 100vh;">
                <div class="col-lg-8 col-md-8 d-none d-lg-flex align-items-center justify-content-center">
                    <div class="mx-wd-700">
                        <img src="/v3/assets/img/login-v2.svg" class="img-fluid" alt="">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 d-flex align-items-center justify-content-center" style="background: #d5d5d5;">
                    <div class="sign-wrapper pd-l-0">
                        <div class="wd-100p mg-l-0">
                            <h3 class="tx-color-01 mg-b-5">Sign Up</h3>
                            <p class="tx-color-01 tx-16 mg-b-20">Create your account</p>

                            <?php if (isset($_GET['alreadyExist']) && $_GET['alreadyExist'] == 1) { ?>
                                <p class="tx-20 mg-b-20" style="color: red; font-weight:bold">This email is already registered.</p>
                            <?php } ?>


                            <form action="/login_signup" method="post" class="login-form mt-lg">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" name="firstname" class="form-control" placeholder="Enter your firstname" required>
                                </div>
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" name="lastname" class="form-control" placeholder="Enter your lastname" required>
                                </div>
                                <div class="form-group">
                                    <label>Email address</label>
                                    <input type="email" class="form-control" name="email" id="inputEmail1" placeholder="yourname@yourmail.com" required>
                                </div>
                                <div class="form-group">
                                    <div class="d-flex justify-content-between mg-b-5">
                                        <label class="mg-b-0-f">Password</label>
                                    </div>
                                    <input type="password" name="password" id="pswd" class="form-control" placeholder="Enter your password" required>
                                </div>
                                <button class="btn btn-brand-02 btn-block">Sign Up</button>
                            </form>
                            <div class="divider-text">or</div>
                            <!-- <button class="btn btn-outline-facebook btn-block">Sign Up With Facebook</button>
                            <button class="btn btn-outline-twitter btn-block">Sign Up With Twitter</button> -->
                            <div class="tx-13 mg-t-20 tx-center">Already have an account? <a href="/login">Sign In</a></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- media -->
        </div><!-- container -->
    </div>
    <!-- The Loader. Is shown when pjax happens -->
    <div class="loader-wrap hiding hide">
        <i class="fa fa-circle-o-notch fa-spin-fast"></i>
    </div>

    <!-- common libraries. required for every page-->
    <script src="v3/lib/jquery/jquery.min.js"></script>
    <script src="v3/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="v3/lib/feather-icons/feather.min.js"></script>
    <script src="v3/lib/perfect-scrollbar/perfect-scrollbar.min.js"></script>

    <script src="v3/assets/js/dashforge.js"></script>

    <!-- page specific libs -->
    <!-- page specific js -->
</body>

</html>