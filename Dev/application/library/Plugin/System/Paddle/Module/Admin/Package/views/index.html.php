<?php /*
<ol class="breadcrumb">
    <li class="breadcrumb-item">YOU ARE HERE</li>
    <li class="breadcrumb-item active">Email</li>
</ol> */ ?>

<div style="float:right;">
    <a style="color: #813ACF;font-weight: bold;background-color: #d5d5d5;" class="btn sync-with-paddle" href="#">Sync with PG</a>
</div>

<h2 class="page-title">Plans & <span class="fw-semi-bold">Addons</span></h2>


<?php /*\Kernel()->events('ui')->filter('payment_link', array(
    'label' => 'Subscribe',
    'data'  => array(
        'product'           => 568152,
        'email'             => 'dyutiman@mclogics.com',
        'message'           => 'Includes 10 sites renewed monthly',
        'disable-logout'    => 'true'
    )
)); */ ?>

<div class="col-12 mb-lg" style="padding-left: 0;padding-right: 0;">
    <section class="widget">
        <div class="widget-body p-0 support table-wrapper" style="padding:10px !important;">
            <table class="table mb-0">
                <thead>
                <tr class="text-muted">
                    <th scope="col"><span class=" pl-3">PLAN</span></th>
                    <th scope="col">TYPE</th>
                    <th scope="col">PG ID</th>
                    <th scope="col">PG NAME</th>
                    <th scope="col">BILLING INTERVAL</th>
                    <th style="text-align:right" scope="col">PRICE</th>
                    <th scope="col">PUBLIC</th>
                    <th style="text-align:center;" scope="col">STATUS</th>
                    <th style="text-align:right" scope="col">SUBSCRIBERS</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="text-dark">

                <?php foreach(\Model\Plan::all() as $plan) { ?>

                    <tr>
                        <th class="pl-4"><?=$plan->name?></th>
                        <td><?=$plan->plan_type==PLAN_TYPE_ACCOUNT_SUBSCRIPTION?'subscription':'addon'?></td>
                        <td><?=$plan->pg_id?></td>
                        <td><?=$plan->pg_name?></td>
                        <td><?=$plan->billing_period?> <?=$plan->billing_type?></td>
                        <td style="text-align:right"><?=money_format('%i', round($plan->cost/100, 2))?> USD</td>
                        <td>
                            <input pid="<?=$plan->id?>" data-on="YES" data-off="NO" class="orion-plan-type" data-onstyle="primary" type="checkbox" <?=$plan->private == PLAN_ACCESS_PUBLIC?'checked':''?> data-toggle="toggle" data-size="mini">
                        </td>
                        <td style="text-align:center;"><?=$plan->status==STATUS_ACTIVE?'<span style="color:green">ACTIVE</span>':'<span style="color:#cc0000">SUSPENDED</span>'?></td>
                        <td style="text-align:right"><?=\Model\Subscription::count_by_plan_id($plan->id)?></td>
                        <td style="text-align:right">
                            <a class="plan-open-details" pid="<?=$plan->id?>" style="font-size:15px;" href="#">
                                <span class="fa fa-edit"></span> Edit
                            </a>
                        </td>
                    </tr>


                <?php } ?>




                </tbody>
            </table>
        </div>
    </section>
</div>


<div id="manage-plan-widget" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php /*
            <div class="modal-header">
                <h5 class="modal-title">Creating New API Access</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Enter the unique name for your new API account below. The credentials for the API will be auto generated.
                <div class="api-access-create-main-area" style="margin-top:15px;">
                    <input class="form-control api-account-name-field" placeholder="API Account Name" type="text" style="margin-bottom:5px;">
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary add-api-access-cancel" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary add-api-access-submit">Submit</button>
            </div>
            */ ?>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

        $('.sync-with-paddle').click(function() {
            var elem = $(this);
            elem.html('Syncing...');
            $.get('/admin_package/syncpaddle', function(d) {
                elem.html('Synced');
                $.get('/admin_package', function(t) {
                    $('#content').html(t);
                })
                //alert('Synced');
            });
            return false;
        });

        $('.orion-plan-type').bootstrapToggle();

        $('.orion-plan-type').change(function() {
            var pid = $(this).attr('pid');
            var url = '/admin_package/' + pid + '/upaccess/?a=';
            var state = '';
            if($(this).is(':checked')) {
                url += '<?=PLAN_ACCESS_PUBLIC?>';
                state = 'set to public';
            } else {
                url += '<?=PLAN_ACCESS_PRIVATE?>';
                state = 'set to private';
            }
            $.get(url, function(data) {
                if(typeof data.error === 'undefined') {
                    client_notify('Access Policy of Plan with id ' + pid + ' has been successfully ' + state, 'success');
                } else {
                    client_notify('ERROR:' + data.error, 'error');
                }
            })
        });

        $('.plan-open-details').click(function() {
            $.get('/admin_package/' + $(this).attr('pid'), function(data) {
                $('#manage-plan-widget .modal-content').html(data);
                $('#manage-plan-widget').modal();
            });
            return false;
        });

    })
</script>