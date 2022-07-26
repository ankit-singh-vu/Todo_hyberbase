<div id="security-section">
    <div class="card mg-b-20 mg-lg-b-25">
        <div class="card-header pd-y-15 pd-x-20 d-flex align-items-center justify-content-between">
            <h6 class="tx-13 tx-spacing-1 tx-uppercase tx-semibold mg-b-0">Change Password</h6>
        </div>
        <div class="card-body pd-25">
            <form id="security-form">
                <input type="hidden" id="user_id" name="user_id" value="<?= $userDetails->id ?>">
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-12">
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" placeholder="Enter Your password" id="password" name="password" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12">
                        <div class="form-group">
                            <div class="form-group">
                                <label>Re enter password</label>
                                <input type="password" placeholder="Enter Your password again" id="repassword" name="repassword" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <button type="submit" class="text-right btn btn-outline-primary float-right mg-t-20" id="change-pass-btn">Change Password</button>
        </div>
    </div>

    <!-- two step verification is not for MVP -->

    <!-- <div class="card mg-b-20 mg-lg-b-25">
        <div class="card-header pd-y-15 pd-x-20 d-flex align-items-center justify-content-between">
            <span class="tx-13 tx-spacing-1 tx-uppercase tx-semibold mg-b-0">Two Step Verification <br> <span class="tx-10 pd-t-20">Keep your account secured with authentication step</span></span>
        </div>
        <div class="card-body pd-25">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <h6 class="text-left">SMS</h6>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12">
                    <span>+1(789) 789-4567</span>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12 text-right">
                    <span><i class="fa fa-edit mg-r-10 tx-15"></i></span>
                    <span><i class="fa fa-trash tx-15"></i></span>
                </div>
                <div class="col-12">
                    <hr>
                    <p>Two factor authentication adds an extra layer of security to your account by requiring mor ethan just a password to login.</p>
                </div>
            </div>
        </div>
    </div> -->

    <div class="card mg-b-20 mg-lg-b-25">
        <div class="card-header pd-y-15 pd-x-20 d-flex align-items-center justify-content-between">
            <span class="tx-13 tx-spacing-1 tx-uppercase tx-semibold mg-b-0">Recent Devices</span>
        </div>
        <div class="card-body pd-25">
            <table class="table table-responsive w-100 d-block d-md-table" style="border-bottom: 1px solid #DBDCDB;">
                <thead class="thead-secondary" style="background: #eee; color: #555">
                    <tr>
                        <th scope="col">Browser</th>
                        <th scope="col">Device</th>
                        <th scope="col">Location</th>
                        <th scope="col">Recent Activity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="row"><i class="fab fa-chrome" class="wd-12 ht-12 "></i> Chrome on windows</td>
                        <td>Dell XPS 13</td>
                        <td>United States</td>
                        <td>10 Jan, 2021 20:07</td>
                    </tr>
                    <tr>
                        <td scope="row"><i class="fab fa-chrome" class="wd-12 ht-12 "></i> Chrome on windows</td>
                        <td>Dell XPS 13</td>
                        <td>United States</td>
                        <td>10 Jan, 2021 20:07</td>
                    </tr>
                    <tr>
                        <td scope="row"><i class="fab fa-chrome" class="wd-12 ht-12 "></i> Chrome on windows</td>
                        <td>Dell XPS 13</td>
                        <td>United States</td>
                        <td>10 Jan, 2021 20:07</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#change-pass-btn').click(function() {
            $(this).prop('disabled', true);
            $(this).html('Processing...');
            var fields = {};
            let formError = false;
            let errorMsg = "";

            fields['password'] = $('#password').val();
            fields['repassword'] = $('#repassword').val();
            fields['user_id'] = $('#user_id').val();

            if (fields['password'].length < 6) {
                formError = true;
                errorMsg = "Password must be 6 or more characters";
            }

            if (fields['password'] != fields['repassword']) {
                formError = true;
                errorMsg = "Both the passwords must be same";
            }

            if (formError && errorMsg != '') {
                window.FlashMessage.error(errorMsg, {
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
                url: "/userdetails_password",
                data: JSON.stringify(obj)
            }).done(function(msg) {
                console.log(msg);
                if (msg.error) {
                    window.FlashMessage.error(msg.error, {
                        progress: true,
                        timeout: 6000
                    });
                } else {
                    window.FlashMessage.success(msg.success, {
                        progress: true,
                        timeout: 6000
                    });
                }
                $.get('/userdetails/<?= $userDetails->id ?>/security', function(htmldata) {
                    $('#security-section').html(htmldata);
                });
            });

            return false;
        })
    });
</script>