<?php /*
<ol class="breadcrumb">
    <li class="breadcrumb-item">YOU ARE HERE</li>
    <li class="breadcrumb-item active">Email</li>
</ol> */ ?>

<h2 class="page-title">My <span class="fw-semi-bold">Profile</span></h2>
<div class="row">
    <div class="col-lg-3 col-xl-2 text-center">

        <?php /*
        <a class="btn btn-danger btn-block create-new-ticket-action" id="compose-btn" href="compose.html">Create New Ticket</a>
        */ ?>

        <div class="text-center" id="profile_pic_display" style="cursor: pointer;" title="Upload Profile Picture">
            <?php if ($profile_pic && !empty($profile_pic)) { ?>
                <img src="<?= $profile_pic ?>" class="rounded-circle" id="main-profile-image" alt="" style="height: 100px; width: 100px">
            <?php } else { ?>
                <img src="https://via.placeholder.com/500" class="rounded-circle" alt="" style="height: 100px; width: 100px">
            <?php } ?>
        </div>

        <ul class="nav wps-in-content-side-nav flex-column nav-pills nav-stacked nav-email-folders mt" id="folders-list">
            <?= $view->helper('navigation')->render(__DIR__ . '/partial/profile.nav.html.php', array(
                'type' => 'profile-navigation'
            )) ?>
            <?php
            $userData = \Model\Session::load_user()->user;
            /*
            <li class="nav-item active first">
                <a class="nav-link" href="#">

                    All Tickets
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <!-- <span class="badge badge-pill bg-danger float-right">2</span> //-->
                    Open
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    Pending
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    In Progress
                </a>
            </li><li class="nav-item">
                <a class="nav-link" href="#">

                    Closed
                </a>
            </li> */ ?>
        </ul>


        <?php /*
        <h6 class="mt">By Department</h6>
        <ul class="nav wps-in-content-side-nav flex-column nav-pills nav-stacked nav-email-folders mb-lg fs-mini">
            <?=$view->helper('navigation')->render(__DIR__ . '/partial/support.nav.html.php', array(
                'type' => 'support-ticket-department-navigation'
            ))?>
        </ul>
        */ ?>


    </div>
    <div class="col-lg-9 col-xl-10">

        <section class="widget" style="min-height:400px;">
            <div class="widget-body" id="mailbox-content">

                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3">Basic Details</h5>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" readonly name="email" class="form-control" id="email" value="<?= $userData->email ?>">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control" id="first_name" value="<?= $userData->first_name ?>">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control" id="last_name" value="<?= $userData->last_name ?>">
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary update-profile-btn">Update Details</button>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3">Security (Change Password)</h5>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="new_password" class="form-control" id="new_password">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>Re enter Password</label>
                            <input type="password" name="re_password" class="form-control" id="re_password">
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary change-password-btn">Change Password</button>
                    </div>
                </div>

            </div>
        </section>

    </div>
</div>

<form id="profilePicForm">
    <input type="file" name="profile_pic" style="display: none;" id="profile_pic" accept="image/png, image/webp, image/jpeg, image/jpg">
</form>

<script>
    $(document).ready(function() {

        $('#profile_pic_display').click(function() {
            $('#profile_pic').trigger('click');
        });
        $('#profile_pic').change(function() {
            // Profile picture Update
            var formData = new FormData();
            formData.append('profile_pic', $('#profile_pic')[0].files[0]);
            $.ajax({
                type: 'POST',
                url: '/profile_profilepicture',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                error: function(res) {
                    console.log("error", res);
                },
                success: function(res) {
                    console.log(res);
                    if (res.error != '') {
                        window.FlashMessage.error(res.error, {
                            progress: true,
                            timeout: 6000
                        });
                    } else {
                        window.FlashMessage.success(res.success, {
                            progress: true,
                            timeout: 6000
                        });
                    }
                    $.get('/profile', function(htmldata) {
                        $('#content').html(htmldata);
                    });
                }
            });
        })

        $('.update-profile-btn').click(function() {
            $(this).prop('disabled', true);
            $(this).html('Processing...');

            var fields = {};
            let formError = false;
            fields['first_name'] = $('#first_name').val();
            fields['last_name'] = $('#last_name').val();
            if (!fields['first_name'] || !fields['last_name']) {
                window.FlashMessage.error('Please fill all the fields', {
                    progress: true,
                    timeout: 4000
                });
                $(this).prop('disabled', false);
                $(this).html('Update Details');
                return;
            }

            var obj = fields;

            $.ajax({
                method: "POST",
                url: "/profile",
                data: JSON.stringify(obj)
            }).done(function(msg) {
                console.log(msg);
                if (msg.error) {
                    window.FlashMessage.error(msg.error, {
                        progress: true,
                        timeout: 6000
                    });
                } else {
                    window.FlashMessage.success("Profile updated successfully", {
                        progress: true,
                        timeout: 6000
                    });
                }
                $.get('/profile', function(htmldata) {
                    $('#content').html(htmldata);
                });
            });

            return false;
        });

        $('.change-password-btn').click(function() {
            $(this).prop('disabled', true);
            $(this).html('Processing...');

            var fields = {};
            let formError = false;
            fields['new_password'] = $('#new_password').val();
            fields['re_password'] = $('#re_password').val();
            if (!fields['new_password']) {
                window.FlashMessage.error('Please fill all the fields', {
                    progress: true,
                    timeout: 4000
                });
                $(this).prop('disabled', false);
                $(this).html('Change Password');
                return;
            }

            var obj = fields;

            $.ajax({
                method: "POST",
                url: "/profile_password",
                data: JSON.stringify(obj)
            }).done(function(msg) {
                console.log(msg);
                if (msg.error) {
                    window.FlashMessage.error(msg.error, {
                        progress: true,
                        timeout: 6000
                    });
                } else {
                    window.FlashMessage.success("Password changed successfully", {
                        progress: true,
                        timeout: 6000
                    });
                }
                $.get('/profile', function(htmldata) {
                    $('#content').html(htmldata);
                });
            });

            return false;
        });
    });
</script>