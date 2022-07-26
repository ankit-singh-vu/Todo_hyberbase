
<?php $a=1; foreach($navigation as $menu) { ?>

<li <?=isset($menu['is_active']) && $menu['is_active']==true ?'class="active"':''?> >
    <a class="dropdown-item" href="<?=isset($menu['ajax_load']) && $menu['ajax_load']==false?'':'#'?><?=$menu['path']?>">
        <span class="uicon"><i class="<?=$menu['icon']?>"></i></span>
        <?=$menu['label']?>
    </a>
</li>

<?php $a++; } ?>


