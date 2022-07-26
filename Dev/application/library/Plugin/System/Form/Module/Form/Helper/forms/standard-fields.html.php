

<?php if($field['type'] == 'input') { ?>



    <div style="float:left;width:<?=isset($field['width'])?$field['width']:'100%'?>;margin-left:<?=isset($field['left-margin'])?$field['left-margin']:'0'?>;" class="<?=isset($field['class']['wrapper'])?$field['class']['wrapper']:''?>">

        <?php if(isset($field['append_text'])) {?>
            <div style="<?=isset($field['append_text']['margin-bottom'])?'margin-bottom:' . $field['append_text']['margin-bottom'].';':''?>;">
                <?=$field['append_text']['content']?>
            </div>
        <?php } ?>

        <?php if(isset($field['label']) && $field['label'] != null) {?>
            <?php if(isset($field['hide_label']) && $field['hide_label'] == true) { } else { ?>
                <label><?=$field['label']?></label>
            <?php } ?>
        <?php } ?>

        <?php if($field['input'] == 'text') { ?>
            <input
                    style="<?=isset($field['style']['input'])?$field['style']['input']:''?>"
                    class="form-control fld-<?=$keyname?> <?=isset($field['class']['input'])?$field['class']['input']:''?>"
                    name="<?=$keyname?>"
                    placeholder="<?=isset($field['placeholder'])?$field['placeholder']:''?>"
                    type="text"/>
        <?php } ?>

        <?php if($field['input'] == 'file') { ?>

            <div class="custom-file custom-file-<?=$keyname?>">
                <input
                        type="file"
                        style="<?=isset($field['style']['input'])?$field['style']['input']:''?>"
                        name="<?=$keyname?>"
                        class="custom-file-input form-control fld-<?=$keyname?> <?=isset($field['class']['input'])?$field['class']['input']:''?>" >
                <label class="custom-file-label"><?=isset($field['placeholder'])?$field['placeholder']:''?></label>
            </div>

            <script type="application/javascript">
            $(document).ready(function() {
                $(".custom-file-<?=$keyname?> input").on("change", function() {
                    var fileName = $(this).val().split("\\").pop();
                    $(this).siblings(".custom-file-label").css('color', '#333');
                    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                });
            });
            </script>
        <?php } ?>

        <?php if($field['input'] == 'select' || $field['input'] == 'subform') { ?>
            <select <?=isset($field['children']['target'])?'target="'.$field['children']['target'].'" ':' '?>
                    class="<?=$field['input']=='subform'?'form-subform-'.str_replace('.', '-', $form['params']['name']):''?> form-control redshift-input-select fld-<?=$keyname?> <?=isset($field['class']['input'])?$field['class']['input']:''?>"
                    style="<?=isset($field['style']['input'])?$field['style']['input']:''?>"
                    name="<?=$keyname?>"
                    title="<?=isset($field['placeholder'])?$field['placeholder']:''?>">
                <?php foreach($field['options'] as $okey => $ovalue) { ?>
                    <option value="<?=$okey?>"><?=$ovalue?></option>
                <?php } ?>
            </select>
        <?php } ?>

        <?php if(isset($field['description'])) { ?>
            <div style="margin-top:1px;font-size:11px;">
                <?=$field['description']?>
            </div>
        <?php } ?>

    </div>

<?php } ?>


<?php if($field['type'] == 'text') { ?>

    <div style="float:left;width:<?=isset($field['width'])?$field['width']:'100%'?>;margin-left:<?=isset($field['left-margin'])?$field['left-margin']:'0'?>;" class="<?=isset($field['class']['wrapper'])?$field['class']['wrapper']:''?>">
        <?php if(isset($field['text'])) {?>
            <?=$field['text']['content']?>
        <?php } ?>
    </div>

<?php } ?>