<?php $a=1; foreach($navigation as $menu) { ?>
    <li class="nav-item <?=isset($menu['is_active']) && $menu['is_active']==true ?'active':''?> <?=$a==1?'first':''?>">
        <a class="nav-link" href="<?=isset($menu['ajax_load']) && $menu['ajax_load']==false?'':'#'?><?=$menu['path']?>">
            <?php if(isset($menu['circle'])) { ?>
                <i class="fa fa-circle <?=$menu['circle']?> float-right"></i>
            <?php } ?>
            <?=$menu['label']?>
        </a>
    </li>
<?php $a++; } ?>
