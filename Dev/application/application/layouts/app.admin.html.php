<!DOCTYPE html>
<html>

<head>
    <title><?= \Kernel()->config('app.title') ?></title>
    <?= $view->helper('head') ?>

    <link rel="shortcut icon" href="/assets/img/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Advisor Learn Admin Panel">
    <meta name="author" content="AdvisorLearn">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" href="/assets/flashjs/dist/flash.min.css">
    <link rel="icon" type="image/x-icon" href="/assets/img/fav_1.png">
    <script>
        /* yeah we need this empty stylesheet here. It's cool chrome & chromium fix
         chrome fix https://code.google.com/p/chromium/issues/detail?id=167083
         https://code.google.com/p/chromium/issues/detail?id=332189
         */
    </script>

    <script src="/assets/scroll/smooth-scrollbar.js"></script>
    <script src="/assets/flashjs/dist/flash.min.js"></script>

    <!--
    <script src="/assets/vendor/dropzone/dist/dropzone.js"></script>
    <link href="/assets/vendor/dropzone/dist/dropzone.css" rel="stylesheet" />
    <link href="https://vjs.zencdn.net/7.14.3/video-js.css" rel="stylesheet" />
    !-->


    <script src="/assets/contenttools/build/content-tools.js"></script>
    <link rel="stylesheet" href="/assets/contenttools/build/content-tools.min.css">
    <link href="/assets/vendor/select2/select2-bootstrap.css" rel="stylesheet" />


    <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
    <!-- <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script> -->

    <script src="/assets/switch/switch.js"></script>
    <link rel="stylesheet" href="/assets/switch/switch.css">
    <link rel="stylesheet" href="/assets/switchery/dist/switchery.css" />
    <script src="/assets/switchery/dist/switchery.js"></script>
    <script src="/assets/vendor/select2/select2.min.js"></script>

    <!-- <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css"> -->

    <style>
        .flash-container {
            height: 200px;
        }

        .ct-app {
            display: none;
        }
    </style>



</head>

<body>

    <?php

    $user = \Model\Session::load_user();

    if ($user->user->profile_pic) {
        $imageData = base64_encode(file_get_contents($user->user->profile_pic));

        $src = 'data: ' . mime_content_type($user->user->profile_pic) . ';base64,' . $imageData;
        $profile_pic = $src;
    } else {
        $profile_pic = NULL;
    }

    // ___debug($profile_pic);
    ?>

    <!--
  Main sidebar seen on the left. may be static or collapsing depending on selected state.

    * Collapsing - navigation automatically collapse when mouse leaves it and expand when enters.
    * Static - stays always open.
-->
    <nav id="sidebar" class="sidebar" role="navigation">
        <!-- need this .js class to initiate slimscroll -->
        <header class="logo d-none d-md-block" style="margin:0;background-color:#000000;">
            <a class="full-logo" style="padding-top: 0px;" href="/">
                <img src="/assets/img/alogo.png" style="width: 80%; height: auto;" alt="">
                <!-- hyper<span style="color:#1E90FF;">Base</span> //-->
            </a>
            <a class="short-logo" href="/">
                <img src="/assets/img/alogo.png" style="width: 80%; height: auto;" alt="">
                <!-- <span style="color:#1E90FF;">HB</span> //-->
            </a>

        </header>

        <div class="js-sidebar-content">
            <!-- seems like lots of recent admin template have this feature of user info in the sidebar.
             looks good, so adding it and enhancing with notifications -->
            <div class="sidebar-status d-md-none">
                <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                    <span class="thumb-sm avatar float-left">
                        <?php if ($profile_pic && !empty($profile_pic)) { ?>
                            <img src="<?= $profile_pic ?>" class="rounded-circle">
                        <?php } else { ?>
                            <img class="rounded-circle" src="/assets/demo/img/people/a3.jpg" alt="...">
                        <?php } ?>
                    </span> &nbsp;
                    <?= $session->user->first_name ?> &nbsp;
                    <b class="caret"></b>

                </a>
                <!-- #notifications-dropdown-menu goes here when screen collapsed to xs or sm -->
            </div>
            <!-- main notification links are placed inside of .sidebar-nav -->

            <?= $view->helper('navigation')->render(__DIR__ . '/partial/app.left.nav.html.php', array(
                'type' => 'admin-left-navigation', //defined('SYSTEM_USER_TYPE_STAFF') && $session->user->is_tag_present(SYSTEM_USER_TYPE_STAFF)?'admin-left-navigation':'left-navigation'
            )) ?>

            <?php /**/ ?>

            <?php if ($session->user->is_tag_present(SYSTEM_USER_TYPE_CUSTOMER)) { ?>

                <h5 class="sidebar-nav-title">My Course Progress</h5>
                <!-- A place for sidebar notifications & alerts -->
                <div class="sidebar-alerts">

                    <div class="alert">
                        <?php /*
                <a href="#" class="close" data-dismiss="alert" aria-hidden="true">&times;</a>
                */ ?>
                        <span class="text-white fw-semi-bold">Insurance Master Class</span> <br>
                        <div class="progress progress-xs mt-xs mb-0">
                            <div class="progress-bar progress-bar-gray-light" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small>Chapter 9 running... 65%</small>
                    </div>
                    <div class="alert">
                        <span class="text-white fw-semi-bold">Leads from LinkedIN</span> <br>
                        <div class="progress progress-xs mt-xs mb-0">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 23%" aria-valuenow="23" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small>Chapter 2 running... 23%</small>
                    </div>


                </div>

            <?php } ?>

            <?php /**/ ?>

        </div>

    </nav>


    <!-- This is the white navigation bar seen on the top. A bit enhanced BS navbar. See .page-controls in _base.scss. -->
    <nav class="page-controls page-top-navigation navbar navbar-dashboard">
        <div class="container-fluid">
            <!-- .navbar-header contains links seen on xs & sm screens -->
            <div class="navbar-header mr-md-3">
                <ul class="nav navbar-nav">
                    <li class="nav-item">
                        <!-- whether to automatically collapse sidebar on mouseleave. If activated acts more like usual admin templates -->
                        <a class="d-none d-lg-block nav-link" id="nav-state-toggle" href="#">
                            <i class="fa fa-bars fa-lg"></i>
                        </a>
                        <!-- shown on xs & sm screen. collapses and expands navigation -->
                        <a class="d-lg-none nav-link" id="nav-collapse-toggle" href="#" data-toggle="tooltip" data-html="true" title="Show/hide<br>sidebar" data-placement="bottom">
                            <span class="rounded rounded-lg bg-transparent text-white d-md-none"><i class="fa fa-bars fa-lg"></i></span>
                            <i class="fa fa-bars fa-lg d-none d-md-block"></i>
                        </a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right d-md-none">
                    <li>
                        <!-- toggles chat -->
                        <a href="#">
                            <span class="rounded rounded-lg bg-transparent text-white">
                                <i class="glyphicon glyphicon-menu-down"></i>
                            </span>
                        </a>
                    </li>
                </ul>
                <!-- xs & sm screen logo -->
                <span class="navbar-brand d-md-none">
                    <?= \Kernel()->config('app.plugin.system.bootstrap.logo.full') ?>
                </span>
            </div>

            <!-- this part is hidden for xs screens -->
            <div class="navbar-header mobile-hidden">

                <?php if (!$session->user->is_tag_present(SYSTEM_USER_TYPE_STAFF)) { ?>
                    <!-- search form! link it to your search server -->
                    <form class="navbar-form" role="search">
                        <div class="form-group">
                            <div class="input-group input-group-no-border">
                                <input class="form-control" type="text" style="background-color:#eee" placeholder="Search...">
                                <span class="input-group-append">
                                    <span class="input-group-text" style="background-color:#eee">
                                        <i class="fa fa-search"></i>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </form>
                <?php } else { ?>
                    <div class="form-group">
                        <div style="margin-top:11px;">ADMINISTRATION</div>
                    </div>
                <?php } ?>

                <ul class="nav navbar-nav float-right">

                    <?php /**/
                    $notice = \Kernel()->events('ui')->filter('page_header_head_right_extend', array(
                        'type'      => 'staff', //defined('SYSTEM_USER_TYPE_STAFF') && $session->user->is_tag_present(SYSTEM_USER_TYPE_STAFF)?'staff':'user',
                        'user'      => $session->user,
                        'html'      => ''
                    ));
                    echo $notice['html'];
                    /**/ ?>

                    <li class="nav-item add-site-link">

                        <?= $view->helper('navigation')->render(__DIR__ . '/partial/calltoaction.nav.html.php', array(
                            'type'      => defined('SYSTEM_USER_TYPE_STAFF') && $session->user->is_tag_present(SYSTEM_USER_TYPE_STAFF) ? 'admin-call-to-action' : 'user-call-to-action',
                        )) ?>

                    </li>
                    <li class="dropdown nav-item">
                        <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                            <span class="thumb-sm avatar float-left">
                                <?php if ($profile_pic && !empty($profile_pic)) { ?>
                                    <img src="<?= $profile_pic ?>" class="rounded-circle">
                                <?php } else { ?>
                                    <img class="rounded-circle" src="/assets/demo/img/people/a3.jpg" alt="...">
                                <?php } ?>
                            </span> &nbsp;
                            <?= $session->user->first_name ?> <?= $session->user->middle_name ?> <?= $session->user->last_name ?>
                            &nbsp;
                            <b class="caret"></b>
                        </a>
                        <ul id="right-menu" class="dropdown-menu dropdown-menu-right">
                            <?= $view->helper('navigation')->render(__DIR__ . '/partial/user.nav.html.php', array(
                                'type'      => defined('SYSTEM_USER_TYPE_STAFF') && $session->user->is_tag_present(SYSTEM_USER_TYPE_STAFF) ? 'admin-user-navigation' : 'user-navigation',
                                'segment'   => 'user'
                            )) ?>
                            <li class="dropdown-divider"></li>
                            <?= $view->helper('navigation')->render(__DIR__ . '/partial/user.nav.html.php', array(
                                'type'      => defined('SYSTEM_USER_TYPE_STAFF') && $session->user->is_tag_present(SYSTEM_USER_TYPE_STAFF) ? 'admin-user-navigation' : 'user-navigation',
                                'segment'   => 'tenant'
                            )) ?>
                            <li class="dropdown-divider"></li>
                            <?= $view->helper('navigation')->render(__DIR__ . '/partial/user.nav.html.php', array(
                                'type'      => defined('SYSTEM_USER_TYPE_STAFF') && $session->user->is_tag_present(SYSTEM_USER_TYPE_STAFF) ? 'admin-user-navigation' : 'user-navigation',
                                'segment'   => 'access'
                            )) ?>
                        </ul>
                    </li>

                    <?php /**/ ?>

                    <?php if (!$session->user->is_tag_present(SYSTEM_USER_TYPE_STAFF)) { 
                        /*
                        <li class="dropdown nav-item">
                            <a href="#" role="button" class="dropdown-toggle dropdown-toggle-notifications nav-link" id="notifications-dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                &nbsp; <i class="fa fa-bell" style="font-size:15px;"></i>

                                <!--
                        <span class="circle bg-warning fw-bold">
                            13
                        </span> //-->
                            </a>
                            <div class="dropdown-menu dropdown-menu-right animated fadeInUp py-0" aria-labelledby="notifications-dropdown-toggle" id="notifications-dropdown-menu">
                                <section class="card notifications">
                                    <header class="card-header">
                                        <div class="text-center mb-sm">
                                            <strong>You have 4 notifications</strong>
                                        </div>
                                        <div class="btn-group btn-group-sm btn-group-toggle" id="notifications-toggle" data-toggle="buttons">
                                            <label class="btn btn-default active">
                                                <!-- ajax-load plugin in action. setting data-ajax-load & data-ajax-target is the
                                             only requirement for async reloading -->
                                                <input type="radio" checked data-ajax-trigger="change" data-ajax-load="demo/ajax/notifications.html" data-ajax-target="#notifications-list"> Notifications
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="radio" data-ajax-trigger="change" data-ajax-load="demo/ajax/messages.html" data-ajax-target="#notifications-list"> Messages
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="radio" data-ajax-trigger="change" data-ajax-load="demo/ajax/progress.html" data-ajax-target="#notifications-list"> Progress
                                            </label>
                                        </div>

                                    </header>
                                    <!-- notification list with .thin-scroll which styles scrollbar for webkit -->
                                    <div id="notifications-list" class="list-group thin-scroll">
                                        <div class="list-group-item">
                                            <span class="thumb-sm float-left mr clearfix">
                                                <?php if ($profile_pic && !empty($profile_pic)) { ?>
                                                    <img src="<?= $profile_pic ?>" class="rounded-circle">
                                                <?php } else { ?>
                                                    <img class="rounded-circle" src="/assets/demo/img/people/a3.jpg" alt="...">
                                                <?php } ?>
                                            </span>
                                            <p class="no-margin overflow-hidden">
                                                1 new user just signed up! Check out
                                                <a href="#">Monica Smith</a>'s account.
                                                <time class="help-block no-margin">
                                                    2 mins ago
                                                </time>
                                            </p>
                                        </div>
                                        <a class="list-group-item" href="#">
                                            <span class="thumb-sm float-left mr">
                                                <i class="glyphicon glyphicon-upload fa-lg"></i>
                                            </span>
                                            <p class="text-ellipsis no-margin">
                                                2.1.0-pre-alpha just released. </p>
                                            <time class="help-block no-margin">
                                                5h ago
                                            </time>
                                        </a>
                                        <a class="list-group-item" href="#">
                                            <span class="thumb-sm float-left mr">
                                                <i class="fa fa-bolt fa-lg"></i>
                                            </span>
                                            <p class="text-ellipsis no-margin">
                                                Server load limited. </p>
                                            <time class="help-block no-margin">
                                                7h ago
                                            </time>
                                        </a>
                                        <div class="list-group-item">
                                            <span class="thumb-sm float-left mr clearfix">
                                                <img class="rounded-circle" src="/assets/demo/img/people/a5.jpg" alt="...">
                                            </span>
                                            <p class="no-margin overflow-hidden">
                                                User <a href="#">Jeff</a> registered
                                                &nbsp;&nbsp;
                                                <button class="btn btn-xs btn-success">Allow</button>
                                                <button class="btn btn-xs btn-danger">Deny</button>
                                                <time class="help-block no-margin">
                                                    12:18 AM
                                                </time>
                                            </p>
                                        </div>
                                        <div class="list-group-item">
                                            <span class="thumb-sm float-left mr">
                                                <i class="fa fa-shield fa-lg"></i>
                                            </span>
                                            <p class="no-margin overflow-hidden">
                                                Instructions for changing your Envato Account password. Please
                                                check your account <a href="#">security page</a>.
                                                <time class="help-block no-margin">
                                                    12:18 AM
                                                </time>
                                            </p>
                                        </div>
                                        <a class="list-group-item" href="#">
                                            <span class="thumb-sm float-left mr">
                                                <span class="rounded bg-primary rounded-lg">
                                                    <i class="fa fa-facebook text-white"></i>
                                                </span>
                                            </span>
                                            <p class="text-ellipsis no-margin">
                                                New <strong>76</strong> facebook likes received.</p>
                                            <time class="help-block no-margin">
                                                15 Apr 2014
                                            </time>
                                        </a>
                                        <a class="list-group-item" href="#">
                                            <span class="thumb-sm float-left mr">
                                                <span class="circle circle-lg bg-gray-dark">
                                                    <i class="fa fa-circle-o text-white"></i>
                                                </span>
                                            </span>
                                            <p class="text-ellipsis no-margin">
                                                Dark matter detected.</p>
                                            <time class="help-block no-margin">
                                                15 Apr 2014
                                            </time>
                                        </a>
                                    </div>
                                    <footer class="card-footer text-sm">
                                        <!-- ajax-load button. loads demo/ajax/notifications.php to #notifications-list
                                     when clicked -->
                                    </footer>
                                </section>
                            </div>
                        </li> */

                     } ?>

                </ul>
            </div>
        </div>
    </nav>

    <div class="content-wrap">
        <!-- main page content. the place to put widgets in. usually consists of .row > .col-lg-* > .widget.  -->
        <main id="content" class="content" source="/" role="main">
            <!-- MAIN CONTENT STARTS HERE //-->
            <?= $content ?>
            <!-- MAIN CONTENT ENDS HERE //-->
        </main>
    </div>
    <!-- The Loader. Is shown when pjax happens -->
    <div class="loader-wrap hiding hide">
        <i class="fa fa-circle-o-notch fa-spin-fast"></i>
    </div>

    <?php /* if(defined('SYSTEM_USER_TYPE_STAFF') && $session->user->is_tag_present(SYSTEM_USER_TYPE_STAFF)) { } else { ?>
<!-- livezilla.net PLACE SOMEWHERE IN BODY -->
<!-- PASS THRU DATA OBJECT -->
<script type="text/javascript">
    var lz_data = {
        overwrite:false,
        0:  '<?=$tenant->uuid?>',
        1:  '<?=$session->user->uuid?>',
        111:'<?=$session->user->first_name?> <?=$session->user->middle_name?> <?=$session->user->last_name?>',
        112:'<?=$session->user->email?>',
        113:'<?=$tenant->name?>'
    };
</script>
<div id="lvztr_81b" style="display:none"></div>
<script id="lz_r_scr_1c796ac872fa1404636787fd9ba7d01f"
        type="text/javascript">
    lz_ovlel = [
        {type:"wm",icon:"commenting"},
        {type:"chat",icon:"comments",counter:true},
        {type:"ticket",icon:"envelope"}];
    lz_ovlec = null;
    lz_code_id="1c796ac872fa1404636787fd9ba7d01f";
    var script = document.createElement("script");
    script.async=true;
    script.type="text/javascript";
    var src = "https://panel.wpstack.io:2400/server.php?rqst=track&output=jcrpt&group=support&ovlv=djI_&ovlc=MQ__&esc=IzQ0NA__&epc=IzE2NWM4Mg__&ovlts=MA__&nse="+Math.random();
    script.src=src;document.getElementById('lvztr_81b').appendChild(script);
</script>
<?php } */ ?>

    <?php foreach (\Kernel()->events('ui')->filter('load_footer_content', array()) as $footer_content) { ?>
        <?= $footer_content ?>
    <?php } ?>



    <!-- Live Support JS -->
    <!-- PASS THRU DATA OBJECT -->
    <?php /**  / ?>
<?php if(defined('SYSTEM_USER_TYPE_STAFF') && $session->user->is_tag_present(SYSTEM_USER_TYPE_STAFF)) { } else { ?>
<script type="text/javascript">
    var lz_data = {
        overwrite:false,
        0:  '<?=$tenant->uuid?>',
        1:  '<?=$session->user->uuid?>',
        111:'<?=$session->user->first_name?> <?=$session->user->middle_name?> <?=$session->user->last_name?>',
        112:'<?=$session->user->email?>',
        113:'<?=$tenant->name?>'
    };
</script>
<script type="text/javascript"
        id="1c796ac872fa1404636787fd9ba7d01f"
        src="https://panel.wpstack.io:2400/script.php?id=1c796ac872fa1404636787fd9ba7d01f">
</script>
<?php } ?>
<?php /**/ ?>



    <div id="gateway-add-new-site" class="modal fade bd-example-modal-lg" role="dialog">
        <div class="modal-dialog wp-modal-lg" style="margin-top:3%;max-width:530px;">
            <div class="modal-content">

                <div class="modal-body site-create-options" style="background-color: #7A7A7A;">
                    <ul>
                        <li class="action-site-create-trigger">
                            <div class="left">
                                <span style="color:#008000" class="fa fa-check-circle"></span>
                            </div>
                            <div class="right">
                                <h3>Create New WordPress Site</h3>
                                <p>If you are creating a new site, which is not hosted or not even built yet, then select this option</p>
                            </div>
                            <div style="clear:both;"></div>
                        </li>
                        <li class="last">
                            <div class="left">
                                <span style="color:#4169E1" class="fa fa-cubes"></span>
                            </div>
                            <div class="right">
                                <h3>Add Existing WordPress Site</h3>
                                <p>If the site you want to add is already hosted somewhere, and is live, then select this option</p>
                            </div>
                            <div style="clear:both;"></div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <script type="application/javascript">
        $(document).ready(function() {
            $('.action-site-create-trigger').click(function() {
                $('#gateway-add-new-site').modal('hide');
            });
        });
    </script>

    <script src="https://vjs.zencdn.net/7.14.3/video.min.js"></script>
    <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script> -->

</body>

</html>