<?php
$session = \Model\Session::load_user();
if(!$session) {
    \Kernel()->redirect('/system/logout');
}
$tenant = $session->user->get_current_web_tenant();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?=\Kernel()->config('app.title')?></title>
    <?=$view->helper('head')?>
    <link rel="shortcut icon" href="/assets/img/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="wpstack application">
    <meta name="author" content="wpstack">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <script>
        /* yeah we need this empty stylesheet here. It's cool chrome & chromium fix
         chrome fix https://code.google.com/p/chromium/issues/detail?id=167083
         https://code.google.com/p/chromium/issues/detail?id=332189
         */
    </script>
</head>
<body>

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


<nav id="sidebar" class="sidebar" role="navigation">
    <!-- need this .js class to initiate slimscroll -->
    <header class="logo d-none d-md-block" style="margin:0;background-color:#813ACF">
        <a class="full-logo" href="/">
            <?=\Kernel()->config('app.plugin.system.bootstrap.logo.full')?>
            <!-- hyper<span style="color:#1E90FF;">Base</span> //-->
        </a>
        <a class="short-logo" href="/">
            <?=\Kernel()->config('app.plugin.system.bootstrap.logo.short')?>
            <!-- <span style="color:#1E90FF;">HB</span> //-->
        </a>
    </header>

    <div class="js-sidebar-content">
        <!-- seems like lots of recent admin template have this feature of user info in the sidebar.
             looks good, so adding it and enhancing with notifications -->
        <div class="sidebar-status d-md-none">
            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <span class="thumb-sm avatar float-left">
                            <img class="rounded-circle" src="/assets/demo/img/people/a5.jpg" alt="...">
                        </span> &nbsp;
                <?=$session->user->first_name?>                    &nbsp;
                <b class="caret"></b>

            </a>
            <!-- #notifications-dropdown-menu goes here when screen collapsed to xs or sm -->
        </div>
        <!-- main notification links are placed inside of .sidebar-nav -->

        <?=$view->helper('navigation')->render(__DIR__ . '/partial/app.left.nav.html.php', array(
            'type' => defined('SYSTEM_USER_TYPE_STAFF') && $session->user->is_tag_present(SYSTEM_USER_TYPE_STAFF)?'admin-left-navigation':'left-navigation'
        ))?>

        <?php /**/ ?>

        <h5 class="sidebar-nav-title">In Progress</h5>
        <!-- A place for sidebar notifications & alerts -->
        <div class="sidebar-alerts">

            <div class="alert">
                <a href="#" class="close" data-dismiss="alert" aria-hidden="true">&times;</a>
                <span class="text-white fw-semi-bold">Sales Report</span> <br>
                <div class="progress progress-xs mt-xs mb-0">
                    <div class="progress-bar progress-bar-gray-light" role="progressbar" style="width: 16%" aria-valuenow="16" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small>Calculating x-axis bias... 65%</small>
            </div>
            <div class="alert">
                <a href="#" class="close" data-dismiss="alert" aria-hidden="true">&times;</a>
                <span class="text-white fw-semi-bold">Personal Responsibility</span> <br>
                <div class="progress progress-xs mt-xs mb-0">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 23%" aria-valuenow="23" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small>Provide required notes</small>
            </div>


        </div>

        <?php /**/ ?>

    </div>

</nav>


<div id="headbar">

    <div style="float:left;margin-top:12px;margin-left:15px;">
        <?=\Kernel()->config('app.plugin.system.bootstrap.logo.full')?>
    </div>


    <div style="float:left;margin-left:10px;">
    <form class="navbar-form" role="search">
        <div class="form-group">
            <div class="input-group input-group-no-border float-left">
                <input class="form-control" type="text" style="background-color:#eee" placeholder="Search...">
                <span class="input-group-append">
                            <span class="input-group-text" style="background-color:#eee">
                                <i class="fa fa-search"></i>
                            </span>
                        </span>
            </div>
        </div>
    </form>
    </div>


    <div style="float:right;margin-right:15px;">
    <ul class="header-right-menu">

        <li class="dropdown nav-item float-left">
            <a href="#" class="nav-link btn btn-primary btn-sm" data-toggle="dropdown" style="margin-top:7px;color:#fff;margin-top:10px;background-color:#0747A6;border-radius:20px">
                ADD WP SITE&nbsp;
            </a>
        </li>

        <li class="dropdown nav-item float-left" style="margin-left:18px;">
            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" style="margin-top:7px;">
                <span class="thumb-sm avatar float-left">
                   <img class="rounded-circle" src="/assets/demo/img/people/a5.jpg" alt="..." style="margin-top: -6px;">
                </span> &nbsp;
                <?=$session->user->first_name?> <?=$session->user->middle_name?> <?=$session->user->last_name?> <b class="caret"></b>
            </a>
            <ul id="right-menu" class="dropdown-menu dropdown-menu-right">
                <?=$view->helper('navigation')->render(__DIR__ . '/partial/user.nav.html.php', array(
                    'type'      => defined('SYSTEM_USER_TYPE_STAFF') && $session->user->is_tag_present(SYSTEM_USER_TYPE_STAFF)?'admin-user-navigation':'user-navigation',
                    'segment'   => 'user'
                ))?>
                <li class="dropdown-divider"></li>
                <?=$view->helper('navigation')->render(__DIR__ . '/partial/user.nav.html.php', array(
                    'type'      => defined('SYSTEM_USER_TYPE_STAFF') && $session->user->is_tag_present(SYSTEM_USER_TYPE_STAFF)?'admin-user-navigation':'user-navigation',
                    'segment'   => 'tenant'
                ))?>
                <li class="dropdown-divider"></li>
                <?=$view->helper('navigation')->render(__DIR__ . '/partial/user.nav.html.php', array(
                    'type'      => defined('SYSTEM_USER_TYPE_STAFF') && $session->user->is_tag_present(SYSTEM_USER_TYPE_STAFF)?'admin-user-navigation':'user-navigation',
                    'segment'   => 'access'
                ))?>
            </ul>
        </li>

    </ul>
    </div>

    <div style="float:right;margin-right:15px;margin-top:14px;">
        <?php /*
        $notice = \Kernel()->events('ui')->filter('page_header_head_right_extend', array(
            'type'      => defined('SYSTEM_USER_TYPE_STAFF') && $session->user->is_tag_present(SYSTEM_USER_TYPE_STAFF)?'staff':'user',
            'user'      => $session->user,
            'html'      => ''
        ));
        echo $notice['html'];
        */ ?>
    </div>

    <div class="content-wrap">
        <!-- main page content. the place to put widgets in. usually consists of .row > .col-lg-* > .widget.  -->
        <main id="content" class="content" role="main">
            <!-- MAIN CONTENT STARTS HERE //-->
            <?=$content?>
            <!-- MAIN CONTENT ENDS HERE //-->
        </main>
    </div>



</div>



<?php foreach(\Kernel()->events('ui')->filter('load_footer_content', array()) as $footer_content) { ?>
<?=$footer_content?>
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




</body>
</html>