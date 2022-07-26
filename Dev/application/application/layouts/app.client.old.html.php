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


<div class="adv-main-trial-notice" style="float: right;padding-top: 10px;padding-right: 33px;font-size: 18px;font-family: Arial !important;">
    Your 7 days FREE Trial Ends in <span style="color:#cc0000">3 Days (3 days 11 hrs 27 mins left)</span> on <span style="color:#cc0000">29th June 2021</span>.
    <a href="" style="font-weight:bold;">Click Here to Join</a>
</div>

<div class="adv-page-header" style="width:auto;height:50px">
    <div class="adv-logo" style="float:left;width:200px;padding-top:8px;padding-left:20px;">
        <h3 style="text-align:center;font-family: Times New Roman, Times, serif"><span style="font-weight:bold;color:#000;font-size:30px;">Advisor</span><span style="font-family:monospace;color:#007100">Algorithm<span style="font-size:17px;color:#000">.com</span></span></h3>
    </div>
</div>


<div class="adv-main-nav" style="background-color:#333;min-height:40px;width:auto;">

    <ul class="adv-main-navigation" style="float:right;">
        <li><a href="#">Hi Deepak</a></li>
        <li><a href="/system/logout">Logout</a></li>
    </ul>

    <ul class="adv-main-navigation">
        <li><a href="#">Dashboard</a></li>
        <li><a href="#">Products</a></li>
        <li><a href="#">Support</a></li>
        <li><a href="#">Community</a></li>
        <li><a href="#">Accountability Partners</a></li>
        <li><a href="#">Refer a friend</a></li>
    </ul>
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