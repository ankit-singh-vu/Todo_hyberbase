<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title><?= \Kernel()->config('app.title') ?></title>
    <?= $view->helper('head') ?>

    <!-- Meta -->
    <meta name="description" content="Advisor Learn Website" />
    <meta name="author" content="Triophase" />
    <link rel="icon" type="image/x-icon" href="/assets/img/fav_1.png">

    <link rel="stylesheet" href="/assets/flashjs/dist/flash.min.css">
    <script src="/assets/flashjs/dist/flash.min.js"></script>

    <!-- Favicon -->
    <!-- <link rel="shortcut icon" type="image/x-icon" href="../../assets/img/favicon.png"> -->
    <style>
        .menu-link {
            display: block;
        }

        .support-link {
            display: none;
        }
    </style>

</head>

<body>
    <?php
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $segments = explode('/', rtrim($uri, '/'));

    $user = \Model\Session::load_user();

    if ($user->user->profile_pic) {
        $imageData = base64_encode(file_get_contents($user->user->profile_pic));
        $src = 'data: ' . mime_content_type($user->user->profile_pic) . ';base64,' . $imageData;
    } else {
        $src = NULL;
    }
    ?>
    <aside class="aside aside-fixed" id="side-bar-client">
        <div class="aside-header pd-l-8" style="background: #000;">
            <a href="/" class="aside-logo"><img src="/assets/img/alogo.png" style="width: 92.5%; height: auto;" alt=""></a>
            <a href="#" class="aside-menu-link menu-link">
                <i data-feather="menu"></i>
                <i data-feather="x"></i>
            </a>
            <a href="#" class="aside-menu-link support-link">
                <i class="fa fa-long-arrow-alt-left" style="font-size:1.6em;"></i>
                <i data-feather="x"></i>
            </a>
        </div>
        <div class="aside-body" style="background: #111;">
            <div class="aside-loggedin">
                <div class="d-flex align-items-center justify-content-start">
                    <?php if ($src && !empty($src)) { ?>
                        <a href="" class="avatar"><img src="<?= $src ?>" class="rounded-circle side-bar-profile-image" alt="" /></a>
                    <?php } else { ?>
                        <a href="" class="avatar"><img id="" src="https://via.placeholder.com/500" class="rounded-circle side-bar-profile-image" alt="" /></a>
                    <?php } ?>
                    <div class="aside-alert-link">
                        <a href="" class="new" data-toggle="tooltip" title="You have 2 unread messages"><i data-feather="message-square"></i></a>
                        <a href="" class="new" data-toggle="tooltip" title="You have 4 new notifications"><i data-feather="bell"></i></a>
                        <a href="/system/logout" data-toggle="tooltip" title="Sign out"><i data-feather="log-out"></i></a>
                    </div>
                </div>
                <div class="aside-loggedin-user">
                    <a href="#" class="d-flex align-items-center justify-content-between mg-b-2">
                        <h6 class="tx-semibold mg-b-0"><?= \Model\Session::load_user()->user->first_name . " " . \Model\Session::load_user()->user->last_name ?></h6>
                    </a>
                    <p class="tx-color-03 tx-12 mg-b-0">User since 1 month</p>
                </div>
                <!-- <div class="collapse" id="loggedinMenu">
                    <ul class="nav nav-aside mg-b-0">
                        <li class="nav-item">
                            <a href="/userdetails/" class="nav-link"><i data-feather="user"></i> <span>View Profile</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link"><i data-feather="settings"></i>
                                <span>Account Settings</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link"><i data-feather="help-circle"></i>
                                <span>Help Center</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link"><i data-feather="log-out"></i> <span>Sign Out</span></a>
                        </li>
                    </ul>
                </div> -->
            </div>
            <!-- aside-loggedin -->
            <ul class="nav nav-aside">
                <li class="nav-item pd-b-10 <?php if ($segments[1] == 'dashboard') echo 'active'; ?>">
                    <a href="/" class="nav-link tx-15"><i class="fa fa-chart-line mg-r-15"></i> <span>Dashboard</span></a>
                </li>
                <li class="nav-item pd-b-10 <?php if ($segments[1] == 'userdetails') echo 'active'; ?>">
                    <a href="/userdetails" class="nav-link tx-15"><i data-feather="settings"></i> <span>Account Setting</span></a>
                </li>
                <li class="nav-item pd-b-10 <?php if ($segments[1] == 'userprofile') echo 'active'; ?>">
                    <a href="/userprofile" class="nav-link tx-15"><i data-feather="user"></i> <span>Profile Management</span></a>
                </li>
                <li class="nav-item pd-b-10 <?php if ($segments[1] == 'course') echo 'active'; ?>">
                    <a href="/course" class="nav-link tx-15"><i class="fa fa-graduation-cap mg-r-15"></i> <span>Course Management</span></a>
                </li>
                <li class="nav-item pd-b-10 <?php if ($segments[1] == 'support') echo 'active'; ?>">
                    <a href="/support" class="nav-link tx-15"><i data-feather="help-circle"></i> <span>Help & Support</span></a>
                </li>
            </ul>
        </div>
    </aside>

    <div class="content ht-100v pd-0">
        <div class="content-header">
            <div class="content-search">
                <i data-feather="search"></i>
                <input type="search" class="form-control" placeholder="Search..." />
            </div>
            <nav class="nav">
                <a href="/support" class="nav-link"><i data-feather="help-circle"></i></a>
            </nav>
        </div>
        <!-- content-header -->

        <div id="client-content" class="content-body pd-l-0 pd-y-0 pd-r-0" style="overflow-x: hidden;">
            <?= $content ?>
            <!-- <div class="container pd-x-0">
                <div class="
              d-sm-flex
              align-items-center
              justify-content-between
              mg-b-20 mg-lg-b-25 mg-xl-b-30 mx-auto
            ">
                    <div class="card shadow col-md-8 col-sm-12 mx-auto">
                        <div class="card-body">
                            <h2 class="text-center">Dashboard Coming Soon!</h2>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- container -->
        </div>
    </div>
    
    <!-- Docusign modal -->
    <div class="modal" id="docu-sign-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="width:850px; height: 900px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">AdvisorLearn Contract Sign</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center pd-b-50">
                    <iframe src="" id="docusign-section" height="600px" width="100%" title="Iframe Example"></iframe>
                </div>
            </div>
        </div>
    </div>
    <script>
        if (window.addEventListener) {
            window.addEventListener("message", onMessage, false);
        } else if (window.attachEvent) {
            window.attachEvent("onmessage", onMessage, false);
        }

        function onMessage(event) {
            // Check sender origin to be trusted
            let origin = "https://<?= getenv('APPLICATION_DOMAIN') ?>";
            if (event.origin !== "https://<?= getenv('APPLICATION_DOMAIN') ?>") return;

            var data = event.data;

            if (typeof window[data.func] == "function") {
                window[data.func].call(null, data.message);
            }
        }

        // Function to be called from iframe
        function closeDocusignModal(message) {
            $("#docu-sign-modal").modal("hide");
        }
    </script>
</body>

</html>