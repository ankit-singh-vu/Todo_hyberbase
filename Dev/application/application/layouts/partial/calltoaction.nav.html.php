
<?php $a=1; foreach($navigation as $menu) { ?>

    <a style="background-color:#228B22;border-radius:50px;color: #fff;padding:5px 15px !important;" class="<?php /*launch-newsite-model*/ ?> action-site-create-trigger cta-btn btn btn-<?=isset($menu['btn-type'])?$menu['btn-type']:'default'?> btn-sm mb-xs" href="<?=isset($menu['ajax_load']) && $menu['ajax_load']==false?'':'#'?><?=$menu['path']?>">
        <!-- <span class="uicon"><i class="<?=$menu['icon']?>"></i></span> //-->
        <?=$menu['label']?>
    </a>


<?php $a++; } ?>





<script>
    $(document).ready(function() {

        $('.launch-newsite-model').click(function() {
            $('#gateway-add-new-site').modal();
            return false;
        });


    });
</script>