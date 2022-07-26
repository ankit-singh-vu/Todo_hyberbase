<div class="modal-header">
    <h5 class="modal-title">Plan: <?=$plan->pg_id?> / <?=$plan->pg_name?> </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="api-access-create-main-area">
        <label>Plan Name</label>
        <input class="form-control api-account-name-field" name="plan-name" value="<?=$plan->name?>" type="text" style="margin-bottom:5px;">
    </div>
    <div class="clearfix"></div>
    <div class="api-access-create-main-area">
        <label>Plan Description</label>
        <textarea class="form-control api-account-name-field" name="plane-description" style="margin-bottom:5px;"><?=$plan->get_variable('description', '')?></textarea>
    </div>
    <div class="clearfix"></div>

    <div class="api-access-create-main-area">
        <label>Resource Allotments</label>
        <table class="table table-bordered">
            <tbody>
                <?php foreach(\Kernel()->events('app')->filter('load_plan_metric_types', array()) as $mkey => $mvalue) { ?>
                    <tr>
                        <td style="width: 100px;text-align: right;padding-top: 13px;font-weight: bold;color: #999;"><?=$mvalue['label']?></td>
                        <td><input class="form-control api-account-name-field" name="metrics[<?=$mkey?>]" type="number" value="<?=$plan->get_variable($mkey, $mvalue['default'])?>"></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="clearfix"></div>

    <div class="api-access-create-main-area" style="width:200px;float:left">
        <label>Plan Type</label>
        <select name="plan-type">
            <option <?=$plan->plan_type==PLAN_TYPE_ACCOUNT_SUBSCRIPTION?'selected':''?> value="<?=PLAN_TYPE_ACCOUNT_SUBSCRIPTION?>">Account Subscription</option>
            <option <?=$plan->plan_type==PLAN_TYPE_ADDON?'selected':''?> value="<?=PLAN_TYPE_ADDON?>">Addon</option>
        </select>
    </div>
    <div class="api-access-create-main-area" style="width:260px;float:left;padding-top:15px;text-align:right;">
        This is a <b><?=$plan->private==PLAN_ACCESS_PRIVATE?'private':'public'?></b> plan <br/>
        renewable every <b><?=$plan->billing_period?> <?=$plan->billing_type?></b>
    </div>
    <div class="clearfix"></div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary add-api-access-cancel" data-dismiss="modal">Cancel</button>
    <button type="button" class="btn btn-primary plan-update-submit">Submit</button>
</div>

<script>
    $(document).ready(function() {

        $('.plan-update-submit').click(function() {


            return false;
        });

    });
</script>











