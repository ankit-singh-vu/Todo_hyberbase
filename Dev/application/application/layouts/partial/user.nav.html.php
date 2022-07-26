
<?php $a=1; foreach($navigation as $menu) { ?>

<li <?=isset($menu['is_active']) && $menu['is_active']==true ?'class="active pl-0"':'class="pl-0"'?> >
    <a class="dropdown-item pl-2" href="<?=isset($menu['ajax_load']) && $menu['ajax_load']==false?'':'#'?><?=$menu['path']?>">
        <span class="uicon"><i class="<?=$menu['icon']?>"></i></span>
        <?=$menu['label']?>
    </a>
</li>

<?php $a++; } ?>


