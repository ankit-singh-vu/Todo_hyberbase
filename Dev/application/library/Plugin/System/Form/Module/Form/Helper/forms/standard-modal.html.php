<div id="modal-<?=str_replace('.', '-', $form['params']['name'])?>" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?=$form['params']['heading']?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body redshift-modal-form">

                <div class="form-error-holder-<?=str_replace('.', '-', $form['params']['name'])?> alert alert-danger" style="display:none;color:#cc0000;font-weight:500;font-size:12px;padding:10px;">
                    This is a demo error
                </div>

                <?php foreach($form['fields'] as $keyname => $field) {

                    include __DIR__ . '/standard-fields.html.php';

                } ?>

                <div class="clearfix"></div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-<?=$form['params']['buttons']['cancel']['class']?> form-<?=str_replace('.', '-', $form['params']['name'])?>-cancel" data-dismiss="modal"><?=$form['params']['buttons']['cancel']['label']?></button>
                <button type="button" class="btn btn-<?=$form['params']['buttons']['submit']['class']?> form-<?=str_replace('.', '-', $form['params']['name'])?>-submit"><?=$form['params']['buttons']['submit']['label']?></button>
            </div>
        </div>
    </div>
</div>

<script type="application/javascript">
    $(document).ready(function() {

        //$('.form-subform').unbind('selectpicker');
        $('.redshift-input-select').selectpicker();

        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        //$('.form-subform').unbind('change');
        $('.form-subform-<?=str_replace('.', '-', $form['params']['name'])?>').change(function() {

                var field = $(this).attr('name');
                var ckey = $(this).val();
                var target = $('.' + $(this).attr('target'));
                var subform_path = '/form/getchild?name=<?=urlencode($form['params']['name'])?>&field=' + encodeURI(field) + '&ckey=' + encodeURI(ckey);


            $('.form-error-holder-<?=str_replace('.', '-', $form['params']['name'])?>').html('');
            $('.form-error-holder-<?=str_replace('.', '-', $form['params']['name'])?>').hide();

                if(ckey === 'none') {
                    target.addClass('blank');
                    target.html('<img style="width:435px;" src="/assets/img/plug.png"/>');
                } else {
                    target.block({
                        message: '<img src="assets/img/ajax-loader-1.svg"/>'
                    });
                    Pace.ignore(function () {
                        $.get(subform_path, function (data) {
                            target.removeClass('blank');
                            target.html(data);
                        });
                    });
                }
        });

        $('<?=$form['params']['trigger']?>').click(function() {
            $('.form-error-holder').remove();
            $('#modal-<?=str_replace('.', '-', $form['params']['name'])?>').modal(<?=isset($form['modal'])?json_encode($form['modal']):null?>);
            return false;
        });

        $('.form-<?=str_replace('.', '-', $form['params']['name'])?>-submit').click(function() {
            $('.form-<?=str_replace('.', '-', $form['params']['name'])?>-cancel').attr('disabled', 'disabled');
            $('.form-<?=str_replace('.', '-', $form['params']['name'])?>-submit').attr('disabled', 'disabled');
            $('#modal-<?=str_replace('.', '-', $form['params']['name'])?> .modal-body').block({
                message: '<img src="assets/img/ajax-loader-1.svg"/>'
            });

            $('.form-error-holder').remove();

            var data = {
                "__form_name": "<?=$form['params']['name']?>"
            };
            <?php foreach($form['fields'] as $keyname => $field) { ?>
                if($('.fld-<?=$keyname?>').length > 0) {
                    data.<?=$keyname?> = $('.fld-<?=$keyname?>').val();
                }
                <?php if(isset($field['children']['source'])) { foreach($field['children']['source'] as $ckey => $cfield) { ?>
                    <?php foreach($cfield as $cckey => $ccfield) { ?>
                        if($('.fld-<?=$cckey?>').length > 0) {
                            data.<?=$cckey?> = $('.fld-<?=$cckey?>').val();
                        }
                    <?php } ?>
                <?php }} ?>
            <?php } ?>

            $('.form-error-holder-<?=str_replace('.', '-', $form['params']['name'])?>').html('');
            $('.form-error-holder-<?=str_replace('.', '-', $form['params']['name'])?>').hide();

            $.ajax({
                type: 'POST',
                url: '/form',
                data: data,
                dataType: 'json',
                success: function(data) {
                    //console.log(data);
                    if ( typeof data.errors !== 'undefined') {
                        for ( var index in data.errors) {
                            if (!data.errors.hasOwnProperty(index)) continue;
                            if($('.fld-' + index).length > 0) {
                                $('.fld-' + index).parent().append('<span class="form-error-holder">' + data.errors[index] + '</span>');
                            } else {
                                $('.form-error-holder-<?=str_replace('.', '-', $form['params']['name'])?>').show();
                                $('.form-error-holder-<?=str_replace('.', '-', $form['params']['name'])?>').append(data.errors[index] + '<br/>');
                            }
                        }
                        $('#modal-<?=str_replace('.', '-', $form['params']['name'])?> .modal-body').unblock();
                        $('.form-<?=str_replace('.', '-', $form['params']['name'])?>-cancel').removeAttr('disabled');
                        $('.form-<?=str_replace('.', '-', $form['params']['name'])?>-submit').removeAttr('disabled');
                    } else {

                        $('#modal-<?=str_replace('.', '-', $form['params']['name'])?> .modal-body').unblock();
                        $('.form-<?=str_replace('.', '-', $form['params']['name'])?>-cancel').removeAttr('disabled');
                        $('.form-<?=str_replace('.', '-', $form['params']['name'])?>-submit').removeAttr('disabled');
                        $('#modal-<?=str_replace('.', '-', $form['params']['name'])?>').modal('hide');

                        if ( typeof data.notify !== 'undefined') {
                            client_notify(data.notify.content, data.notify.type);
                        }

                    }
                }
            });

        });

    })
</script>