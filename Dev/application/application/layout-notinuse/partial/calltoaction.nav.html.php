
<?php $a=1; foreach($navigation as $menu) { ?>

    <a style="background-color: #813ACF;color: #fff;" class="launch-newsite-model cta-btn btn btn-<?=isset($menu['btn-type'])?$menu['btn-type']:'default'?> btn-sm mb-xs" href="<?=isset($menu['ajax_load']) && $menu['ajax_load']==false?'':'#'?><?=$menu['path']?>">
        <span class="uicon"><i class="<?=$menu['icon']?>"></i></span>
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