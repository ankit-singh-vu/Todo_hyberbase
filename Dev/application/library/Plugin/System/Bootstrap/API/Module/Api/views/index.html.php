<?php /*
<ol class="breadcrumb">
    <li class="breadcrumb-item">YOU ARE HERE</li>
    <li class="breadcrumb-item active">Email</li>
</ol> */ ?>

<h2 class="page-title">My <span class="fw-semi-bold">Profile</span></h2>
<div class="row">
    <div class="col-lg-3 col-xl-2">

        <div style="width:137px;margin:auto;background-color:#ccc;height:137px;border-radius:80px;margin-bottom:25px;"></div>

        <ul class="nav wps-in-content-side-nav flex-column nav-pills nav-stacked nav-email-folders mt" id="folders-list">
            <?=$view->helper('navigation')->render(\Kernel()->config('system.path.lib') . '/Plugin/System/Bootstrap/Module/Profile/views/partial/profile.nav.html.php', array(
                'type' => 'profile-navigation'
            ))?>

        </ul>

    </div>
    <div class="col-lg-9 col-xl-10">

        <section class="widget">
            <div class="widget-body" id="mailbox-content">



                <!-- <h5 style="color:#777;border-bottom: 1px dotted #ccc;padding-bottom:15px;padding-top:5px;font-weight:bold;">API Settings</h5> -->

                <div class="alert alert-primary-light">

                    You can add one or more API accounts attached to your profile, which can be used by third party applications, to access the
                    services of <?=$kernel->config('app.identity')?>, with the same privilege as your profile, for this tenant. API accounts can be
                    enabled or disabled ar per your requirement. Please note that, an API account can not be used by multiple application simultaneously.
                    If you intend to use more than one application, accessing the API, then please create separate API accounts for each application.
                    Incase of any question please feel free to contact support.

                </div>

                <?php if(count($api_accounts)==0) { ?>

                <div style="width:auto;text-align:center;margin-top:50px;margin-bottom:50px;font-size:16px;">
                    Currently there are no API accounts attached to your profile. <br/>
                    <a href="#" class="create-api-access-action" style="font-weight:bold;">Click Here</a> to add an API account / access
                </div>

                <?php } else { ?>

                    <table style="border-bottom:1px solid #ddd" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Access Key</th>
                                <th>Access Secret</th>
                                <th>Status</th>
                                <th>Last Access</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($api_accounts as $api) { $name = $api->get_variable('name'); $now = date('U') ?>
                                <tr>
                                    <td><?=$api->id?></td>
                                    <td><?=$name?></td>
                                    <td style="font-family:monospace"><?=$api->access_key?></td>
                                    <td>*********** &nbsp; <a secret="<?=$api->access_secret?>" key="<?=$api->access_key?>" aname="<?=$name?>" class="show-api-access-details" href="#">show</a></td>
                                    <td>

                                        <input aid="<?=$api->id?>" data-on="Enabled" data-off="Disabled" class="api-access-status-toggle" data-onstyle="primary" type="checkbox" <?=$api->status == STATUS_ACTIVE?'checked':''?> data-toggle="toggle" data-size="mini">

                                    </td>
                                    <td>
                                        <?php
                                        if($api->last_access == 0) {
                                            echo 'Never';
                                        } else {

                                            $gap = $now - $api->last_access;
                                            echo '<a style="cursor:pointer" title="'. date('r', $api->last_access) .'">' . $kernel->secondsToTime($gap) . ' ago </a>';


                                        }
                                        ?>


                                    </td>
                                    <td><a apid="<?=$api->id?>" title="Delete API Access" class="delete-api-access" style="font-weight:bold" href="#">
                                            <span class="fa fa-trash"></span>
                                        </a></td>
                                </tr>

                            <?php } ?>
                        </tbody>
                    </table>

                    <div style="float:right;">
                        <a href="#" class="btn btn-primary btn-sm create-api-access-action">Add API Access</a>
                    </div>

                <?php } ?>


            </div>
        </section>

    </div>
</div>



<div id="create-api-access-form" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
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
        </div>
    </div>
</div>

<div id="api-access-details" class="modal fade lg-model" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">API Access - <span class="access-name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-noborder" style="margin-bottom:0;">
                    <tbody>
                        <tr>
                            <th>Access Key:</th>
                            <td style="font-family: monospace" class="access-key"> -- </td>
                        </tr>
                        <tr>
                            <th>Access Secret:</th>
                            <td style="font-family: monospace" class="access-secret"> -- </td>
                        </tr>
                    </tbody>
                </table>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        $('.create-api-access-action').click(function() {
            $('#create-api-access-form').modal({
                backdrop: 'static',
                keyboard: false
            });
            return false;
        });
        $('.add-api-access-submit').click(function() {

            var apiName = $('.api-account-name-field').val().trim();
            if(apiName === '') {
                alert('Please enter a unique name for the new API access');
                return false;
            }

            $('#create-api-access-form .modal-body').block({
                message: '<img src="/assets/img/spinner.png" style="width:100px;"/>',
                css: { border: '0px solid #a00', 'background-color': 'rgba(255, 0, 0, 0)', 'margin-top': '-25px' }
            });
            $('.add-api-access-submit').attr('disabled', 'disbaled');
            $('.add-api-access-cancel').attr('disabled', 'disbaled');

            $.ajax({
                type: 'POST',
                url: "/api_create",
                data: { name: apiName },
                dataType: "json",
                success: function(resultData) {
                    //console.log(resultData);
                    $('.add-api-access-submit').removeAttr('disabled');
                    $('.add-api-access-cancel').removeAttr('disabled');
                    $('#create-api-access-form .modal-body').unblock();
                    $('#create-api-access-form').modal('hide');

                    if(typeof resultData.error === 'undefined') {
                        client_notify('The API account, ' + apiName + ', has been successfully created.', 'success', 5);
                        client_load('/api');
                    } else {
                        if(resultData.error === 'api_name_not_unique') {
                            client_notify('Unable to create API account, ' + apiName + ', due to duplicate naming.', 'error', 5);
                        } else if(resultData.error === 'max_api_account_limit_reached') {
                            client_notify('Unable to create API account, ' + apiName + ', due to max limit reached', 'error');
                        } else {
                            client_notify('Unable to create API account, ' + apiName + ', due to internal error.', 'error');
                        }
                        client_load('/api');
                    }



                }
            });

            return false;
        });

        $('.delete-api-access').click(function() {
            var api_id = $(this).attr('apid');
            var conff = confirm('You are about to delete API access, with ID ' + api_id + '. ' +
                'Are you sure you want to continue?');
            if(conff === true) {
                $.get('/api/' + api_id + '/delete', function (data) {
                    if (typeof data.error === 'undefined') {
                        client_notify('API account with ID ' + api_id + ', has been successfully deleted', 'success');
                    } else {
                        client_notify('ERROR: ' + data.error, 'error');
                    }
                    client_load('/api');
                });
            }
            return false;
        });

        $('.show-api-access-details').click(function() {

            var name = $(this).attr('aname');
            var key = $(this).attr('key');
            var secret = $(this).attr('secret');

            $('#api-access-details .access-name').html(name);
            $('#api-access-details .access-key').html(key);
            $('#api-access-details .access-secret').html(secret);

            $('#api-access-details').modal();
            return false;
        });

        $('.api-access-status-toggle').bootstrapToggle();

        $('.api-access-status-toggle').change(function() {
            var aid = $(this).attr('aid');
            var url = '/api/' + aid + '/status/?a=';
            var state = '';
            if($(this).is(':checked')) {
                url += '<?=STATUS_ACTIVE?>';
                state = 'enabled';
            } else {
                url += '<?=STATUS_SUSPENDED?>';
                state = 'disabled';
            }
            $.get(url, function(data) {
                if(typeof data.error === 'undefined') {
                    client_notify('API account with id ' + aid + ' has been successfully ' + state, 'success');
                } else {
                    client_notify('ERROR:' + data.error, 'error');
                }
            })
        });

    });
</script>


















