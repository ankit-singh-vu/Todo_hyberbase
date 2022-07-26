<ul class="sidebar-nav">

    <?php $a=1; foreach($navigation as $menu) { ?>

    <li <?=isset($menu['is_active']) && $menu['is_active']==true ?'class="active"':''?> >
        <a href="<?=isset($menu['ajax_load']) && $menu['ajax_load']==false?'':'#'?><?=$menu['path']?>" <?=count($navigation)==$a?'style="border-bottom:1px solid #333"':''?> >
            <span class="icon"><i class="<?=$menu['icon']?>"></i></span>
            <?=$menu['label']?>
        </a>
    </li>

    <?php $a++; } ?>

</ul>


