<div id="account-section">
    <style>
        .CustomHeading {
            font-size: 1.1em;
            cursor: pointer;
            font-weight: normal;
        }

        .CustomHeading2 {
            font-size: 1.1em;
        }

        .edit-icon {
            display: none;
        }
    </style>
    <!-- Billing address -->
    <div class="card mg-b-20 mg-lg-b-25">

        <div id="showBillingAddress">
            <div class="card-header pd-y-15 pd-x-20 d-flex align-items-center justify-content-between">
                <h6 class="tx-13 tx-spacing-1 tx-uppercase tx-semibold mg-b-0">Billing Address</h6>
            </div><!-- card-header -->
            <div class="card-body pd-25">
                <form id="billing-form">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 mg-b-10">
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <h5 class="CustomHeading2">Name: </h5>
                                </div>
                                <div class="col-md-9 col-sm-12 text-left">
                                    <h5 class="CustomHeading" title="Click to Edit"><?= $user->first_name . " " . $user->last_name ?> &nbsp; <i class="fa fa-edit edit-icon"></i></h5>
                                    <div class="row edit-section" style="display: none;">
                                        <div class="col-10">
                                            <input type="text" class="form-control edit-input" placeholder="Enter Name" name="name" id="name" value="<?= $user->first_name . " " . $user->last_name ?>">
                                        </div>
                                        <div class="col-2 text-left pd-r-0 pd-l-2">
                                            <span field-name="name" class="save-btn" style="cursor: pointer"><i class="fa fa-check-circle tx-20 mg-t-8"></i></span>
                                            <span class="close-input-button mg-l-5" style="cursor: pointer"><i class="fa fa-times-circle tx-20 mg-t-8"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 mg-b-10">
                            <div class="row">
                                <div class="col-md-4 col-sm-3">
                                    <h5 class="CustomHeading2">Company Name: </h5>
                                </div>
                                <div class="col-md-8 col-sm-9 text-left">
                                    <h5 class="CustomHeading" title="Click to Edit"><?= $billingAddress && !empty($billingAddress->company) ? $billingAddress->company : 'NA' ?> &nbsp; <i class="fa fa-edit edit-icon"></i></h5>
                                    <div class="row edit-section" style="display: none;">
                                        <div class="col-10">
                                            <input type="text" class="form-control edit-input" placeholder="Enter Company Name" name="company" id="company" value="<?= $billingAddress && !empty($billingAddress->company) ? $billingAddress->company : '' ?>">
                                        </div>
                                        <div class="col-2 text-left pd-r-0 pd-l-1">
                                            <span class="save-btn" style="cursor: pointer" field-name="company"><i class="fa fa-check-circle tx-20 mg-t-8"></i></span>
                                            <span class="close-input-button mg-l-5" style="cursor: pointer"><i class="fa fa-times-circle tx-20 mg-t-8"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 mg-b-10">
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <h5 class="CustomHeading2">Contact: </h5>
                                </div>
                                <div class="col-md-9 col-sm-12 text-left">
                                    <h5 class="CustomHeading" title="Click to Edit"><?= $billingAddress && !empty($billingAddress->contact) ? '+1 ' . $billingAddress->contact : 'NA' ?> &nbsp; <i class="fa fa-edit edit-icon"></i></h5>
                                    <div class="row edit-section" style="display: none;">
                                        <div class="col-10">
                                            <input type="text" class="form-control edit-input" placeholder="Enter Contact Number" name="contact" id="contact" value="<?= $billingAddress && !empty($billingAddress->contact) ? $billingAddress->contact : '' ?>">
                                        </div>
                                        <div class="col-2 text-left pd-r-0 pd-l-2">
                                            <span class="save-btn" style="cursor: pointer" field-name="contact"><i class="fa fa-check-circle tx-20 mg-t-8"></i></span>
                                            <span class="close-input-button mg-l-5" style="cursor: pointer"><i class="fa fa-times-circle tx-20 mg-t-8"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 mg-b-10">
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <h5 class="CustomHeading2">Billing Email: </h5>
                                </div>
                                <div class="col-md-8 col-sm-12 text-left">
                                    <h5 class="CustomHeading" title="Click to Edit"><?= $billingAddress && !empty($billingAddress->email) ? $billingAddress->email : 'NA' ?> &nbsp; <i class="fa fa-edit edit-icon"></i></h5>
                                    <div class="row edit-section" style="display: none;">
                                        <div class="col-10">
                                            <input type="email" class="form-control edit-input" placeholder="Enter Email" name="email" id="email" value="<?= $billingAddress && !empty($billingAddress->email) ? $billingAddress->email : '' ?>">
                                        </div>
                                        <div class="col-2 text-left pd-r-0 pd-l-2">
                                            <span class="save-btn" style="cursor: pointer" field-name="email"><i class="fa fa-check-circle tx-20 mg-t-8"></i></span>
                                            <span class="close-input-button mg-l-5" style="cursor: pointer"><i class="fa fa-times-circle tx-20 mg-t-8"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 mg-b-10">
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <h5 class="CustomHeading2">Country: </h5>
                                </div>
                                <div class="col-md-9 col-sm-12 text-left">
                                    <h5 class="CustomHeading" title="Click to Edit"><?= $billingAddress && !empty($billingAddress->country) ? $billingAddress->country : 'NA' ?> &nbsp; <i class="fa fa-edit edit-icon"></i></h5>
                                    <div class="row edit-section" style="display: none;">
                                        <div class="col-10">
                                            <input type="text" class="form-control edit-input" placeholder="Enter Country (Eg. USA)" name="country" id="country" value="<?= $billingAddress && !empty($billingAddress->country) ? $billingAddress->country : '' ?>">
                                        </div>
                                        <div class="col-2 text-left pd-r-0 pd-l-2">
                                            <span class="save-btn" style="cursor: pointer" field-name="country"><i class="fa fa-check-circle tx-20 mg-t-8"></i></span>
                                            <span class="close-input-button mg-l-5" style="cursor: pointer"><i class="fa fa-times-circle tx-20 mg-t-8"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 mg-b-10">
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <h5 class="CustomHeading2">Tax ID: </h5>
                                </div>
                                <div class="col-md-8 col-sm-12 text-left">
                                    <h5 class="CustomHeading" title="Click to Edit"><?= $billingAddress && !empty($billingAddress->tax_id) ? $billingAddress->tax_id : 'NA' ?> &nbsp; <i class="fa fa-edit edit-icon"></i></h5>
                                    <div class="row edit-section" style="display: none;">
                                        <div class="col-10">
                                            <input type="text" class="form-control edit-input" placeholder="Enter tax Id" name="tax_id" id="tax_id" value="<?= $billingAddress && !empty($billingAddress->tax_id) ? $billingAddress->tax_id : '' ?>">
                                        </div>
                                        <div class="col-2 text-left pd-r-0 pd-l-2">
                                            <span class="save-btn" style="cursor: pointer" field-name="tax_id"><i class="fa fa-check-circle tx-20 mg-t-8"></i></span>
                                            <span class="close-input-button mg-l-5" style="cursor: pointer"><i class="fa fa-times-circle tx-20 mg-t-8"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 mg-b-10">
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <h5 class="CustomHeading2">State: </h5>
                                </div>
                                <div class="col-md-9 col-sm-12 text-left">
                                    <h5 class="CustomHeading" title="Click to Edit"><?= $billingAddress && !empty($billingAddress->state) ? $billingAddress->state : 'NA' ?> &nbsp; <i class="fa fa-edit edit-icon"></i></h5>
                                    <div class="row edit-section" style="display: none;">
                                        <div class="col-10">
                                            <input type="text" class="form-control edit-input" placeholder="Enter State" name="state" id="state" value="<?= $billingAddress && !empty($billingAddress->state) ? $billingAddress->state : '' ?>">
                                        </div>
                                        <div class="col-2 text-left pd-r-0 pd-l-2">
                                            <span class="save-btn" style="cursor: pointer" field-name="state"><i class="fa fa-check-circle tx-20 mg-t-8"></i></span>
                                            <span class="close-input-button mg-l-5" style="cursor: pointer"><i class="fa fa-times-circle tx-20 mg-t-8"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 mg-b-10">
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <h5 class="CustomHeading2">Vat Number: </h5>
                                </div>
                                <div class="col-md-8 col-sm-12 text-left">
                                    <h5 class="CustomHeading" title="Click to Edit"><?= $billingAddress && !empty($billingAddress->vat_no) ? $billingAddress->vat_no : 'NA' ?> &nbsp; <i class="fa fa-edit edit-icon"></i></h5>
                                    <div class="row edit-section" style="display: none;">
                                        <div class="col-10">
                                            <input type="text" class="form-control edit-input" placeholder="Enter Vat Number" name="vat_no" id="vat_no" value="<?= $billingAddress && !empty($billingAddress->vat_no) ? $billingAddress->vat_no : '' ?>">
                                        </div>
                                        <div class="col-2 text-left pd-r-0 pd-l-2">
                                            <span class="save-btn" style="cursor: pointer" field-name="vat_no"><i class="fa fa-check-circle tx-20 mg-t-8"></i></span>
                                            <span class="close-input-button mg-l-5" style="cursor: pointer"><i class="fa fa-times-circle tx-20 mg-t-8"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 mg-b-10">
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <h5 class="CustomHeading2">Zip Code: </h5>
                                </div>
                                <div class="col-md-9 col-sm-12 text-left">
                                    <h5 class="CustomHeading" title="Click to Edit"><?= $billingAddress && !empty($billingAddress->zip_code) ? $billingAddress->zip_code : 'NA' ?> &nbsp; <i class="fa fa-edit edit-icon"></i></h5>
                                    <div class="row edit-section" style="display: none;">
                                        <div class="col-10">
                                            <input type="text" class="form-control edit-input" placeholder="Enter ZIP Code" name="zip_code" id="zip_code" value="<?= $billingAddress && !empty($billingAddress->zip_code) ? $billingAddress->zip_code : '' ?>">
                                        </div>
                                        <div class="col-2 text-left pd-r-0 pd-l-2">
                                            <span class="save-btn" style="cursor: pointer" field-name="zip_code"><i class="fa fa-check-circle tx-20 mg-t-8"></i></span>
                                            <span class="close-input-button mg-l-5" style="cursor: pointer"><i class="fa fa-times-circle tx-20 mg-t-8"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 mg-b-10">
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <h5 class="CustomHeading2">Billing Address: </h5>
                                </div>
                                <div class="col-md-8 col-sm-12 text-left">
                                    <h5 class="CustomHeading" title="Click to Edit"><?= $billingAddress && !empty($billingAddress->address) ? $billingAddress->address : 'NA' ?> &nbsp; <i class="fa fa-edit edit-icon"></i></h5>
                                    <div class="row edit-section" style="display: none;">
                                        <div class="col-10">
                                            <input type="text" class="form-control edit-input" placeholder="Enter Billing Address" name="address" id="address" value="<?= $billingAddress && !empty($billingAddress->address) ? $billingAddress->address : '' ?>">
                                        </div>
                                        <div class="col-2 text-left pd-r-0 pd-l-2">
                                            <span class="save-btn" style="cursor: pointer" field-name="address"><i class="fa fa-check-circle tx-20 mg-t-8"></i></span>
                                            <span class="close-input-button mg-l-5" style="cursor: pointer"><i class="fa fa-times-circle tx-20 mg-t-8"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div><!-- card -->
</div>

<!-- Courses -->
<!-- <div class="card mg-b-20 mg-lg-b-25">
    <div class="card-header d-flex align-items-start justify-content-between">
        <h6 class="tx-13 tx-spacing-1 tx-uppercase tx-semibold mg-b-0">Courses</h6>
        <a href="" class="tx-13 link-03">Last 30 days <i class="icon ion-ios-arrow-down"></i></a>
    </div>
    <div class="card-body pd-y-15 pd-x-10">
        <?php
        if (count($courseList) > 0) {
            $courseArr = array();
            $coursesArr = array();
            foreach ($courseList as $packCou) {
                $course = \Model\Course::find($packCou->course_id);
                $cAccess = \Model\Courseaccess::find_by_course_id_and_tenant_id($course->id, $tenant->id);
                if ($cAccess && !empty($cAccess) && $cAccess != null) {
                    $permissions = json_decode($cAccess->permissions);
                } else {
                    $cAccess = \Model\Packagecourse::find_by_course_id_and_package_id($course->id, $packCou->package_id);
                    $permissions = json_decode($cAccess->permissions);
                }
                if (isset($permissions->perm_access_course_after)) {
                    $avlSpan = $permissions->prem_access_for_day ? $permissions->prem_access_for_day : 0;
                    $avlUnit = $permissions->prem_access_for_span ? $permissions->prem_access_for_span : 'day';
                    $subscription = \Model\Subscription::find_by_tenant_id_and_plan_id($tenant->id, $packCou->package_id);
                    $subDate = $subscription->created_at->format('Y-m-d');
                    if ($avlUnit == 'day') $avlUnit = 'days';
                    if ($avlUnit == 'month') $avlUnit = 'months';
                    if ($avlUnit == 'year') $avlUnit = 'years';
                    $toAdd = " + {$avlSpan} {$avlUnit}";
                    $validUpto = date('Y-m-d', strtotime($subDate . $toAdd));
                    $today = date('Y-m-d');

                    if ($today > $validUpto) {
                        break;
                    }
                }

                if (in_array($course->id, $courseArr)) {
                    continue;
                }
                array_push($courseArr, $course->id);
                array_push($coursesArr, $packCou);
        ?>
            <?php }
            if (count($courseArr) == 0) { ?>
                <h5 class="CustomHeading2">No course is available right now!</h5>
            <?php } else { ?>
                <div class="table-responsive">
                    <table class="table table-borderless table-sm tx-13 tx-nowrap mg-b-0">
                        <thead>
                            <tr class="tx-15 tx-spacing-1 tx-color-03 tx-uppercase">
                                <th class="wd-5p">Link</th>
                                <th>Course Title</th>
                                <th>Details</th>
                                <th class="text-right">Percentage (%)</th>
                                <th class="text-right">Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($coursesArr as $cou) {
                                $course = \Model\Course::find($packCou->course_id); ?>
                                <tr>
                                    <td class="align-middle text-center"><a href="/course/<?= $cou->id ?>/details"><i class="fa fa-link" class="wd-12 ht-12 "></i></a></td>
                                    <td class="align-middle tx-medium"><?= $course->name ?></td>
                                    <td class="align-middle tx-medium"><?= $course->description ?></td>
                                    <td class="align-middle text-right">
                                        <div class="wd-150 d-inline-block">
                                            <div class="progress ht-4 mg-b-0">
                                                <div class="progress-bar bg-teal wd-65p" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-right"><span class="tx-medium">65.35%</span></td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            <?php }
        } else { ?>
            <h5 class="CustomHeading2">No course is available right now!</h5>
        <?php }

        ?>
    </div>
</div> -->
<!-- card -->

<!-- <div id="account-section">
    <h4 class="mg-b-10">Edit Profile</h4>
    <form id="profile-form">
        <input type="hidden" name="user_id" value="<?= $user->id ?>">
        <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" placeholder="Enter Your First Name" id="first_name" name="first_name" class="form-control" value="<?= $user->first_name ?>">
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" placeholder="Enter Your Last Name" id="last_name" name="last_name" class="form-control" value="<?= $user->last_name ?>">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                    <label>Country</label>
                    <input type="text" placeholder="Eg. USA" id="country" name="country" class="form-control" value="<?= $tenant->country ?>">
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                    <div class="form-group">
                        <label>Language</label>
                        <input type="text" placeholder="Eg. English" id="language" name="language" class="form-control" value="<?= $tenant->language ?>">
                    </div>
                </div>
            </div>
        </div>
    </form>
    <button type="submit" class="text-right btn btn-success float-right mg-t-20" id="update-profile-btn">Update Profile</button>
</div> -->
<script>
    $(document).ready(function() {

        $(".CustomHeading").hover(function() {
            $(this).find('.edit-icon').show();
        }, function() {
            $(this).find('.edit-icon').hide();
        });

        $('.CustomHeading').click(function() {
            $(this).hide()
            $(this).next().show();
        })

        $('.close-input-button').click(function() {
            // $(this).next().show();
            $(this).parent().parent().hide();
            $(this).parent().parent().parent().find('.CustomHeading').show();
        })

        $('.save-btn').click(function() {
            // $(this).next().show();
            let fieldName = $(this).attr('field-name');
            let fieldValue = $(this).parent().parent().find('.edit-input').val();
            let fields = {};
            fields[fieldName] = fieldValue;

            let formError = false;

            let requiredFields = ['contact', 'email', 'address', 'zip_code', 'country', 'state', 'first_name', 'last_name', 'name'];

            if (requiredFields.includes(fieldName) && (!fieldValue || fieldValue == '')) {
                formError = true;
            }

            if (formError == true) {
                window.FlashMessage.error('Please fill all the fields', {
                    progress: true,
                    timeout: 4000
                });
                return;
            }

            if (fieldName == 'name') {
                console.log("iam name");
                let firstName = fieldValue.split(" ")[0];
                let lastName = fieldValue.split(" ")[1];
                fields['first_name'] = firstName;
                fields['last_name'] = lastName;
                updateUserDetails(fields);
            } else if (fieldName == 'country') {
                updateUserDetails(fields);
                updateBillingAddress(fields);
            } else if (fieldName == 'email') {
                if (!validateEmail(fieldValue)) {
                    window.FlashMessage.error('Please give a proper email address', {
                        progress: true,
                        timeout: 4000
                    });
                    return;
                } else {
                    updateBillingAddress(fields);
                }
            } else {
                updateBillingAddress(fields);
            }
            return false;
        });

        function updateBillingAddress(billingObj) {
            $.ajax({
                method: "POST",
                url: "/userdetails_billingaddress",
                data: JSON.stringify(billingObj)
            }).done(function(msg) {
                console.log(msg);
                if (msg.error && msg.error != '') {
                    window.FlashMessage.error(msg.error, {
                        progress: true,
                        timeout: 4000
                    });
                } else {
                    window.FlashMessage.success(msg.success, {
                        progress: true,
                        timeout: 4000
                    });
                }
                $.get('/userdetails/<?= $user->id ?>/account', function(htmldata) {
                    $('#account-section').html(htmldata);
                });
            });
            return false;
        }

        function updateUserDetails(userObj) {
            console.log("hey man");
            $.ajax({
                method: "POST",
                url: "/userdetails_update",
                data: JSON.stringify(userObj)
            }).done(function(msg) {
                console.log("hello world");
                console.log(msg);
                $.get('/userdetails/<?= $user->id ?>/account', function(htmldata) {
                    $('#account-section').html(htmldata);
                });
            });
            return false;
        }

        function validateEmail(email) {
            const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }

        // $('#update-profile-btn').click(function() {
        //     $(this).prop('disabled', true);
        //     $(this).html('Processing...');
        //     var fields = {};
        //     let formError = false;

        //     $("#profile-form").find(":input").each(function() {
        //         console.log(this.name);
        //         console.log($(this).val());
        //         if (!$(this).val() || $(this).val() == '') {
        //             formError = true;
        //         }
        //         fields[this.name] = $(this).val();
        //     });
        //     if (formError) {
        //         window.FlashMessage.error('Please fill all the fields', {
        //             progress: true,
        //             timeout: 4000
        //         });
        //         $(this).prop('disabled', false);
        //         $(this).html('Update Profile');
        //         return;
        //     }
        //     var obj = fields;
        //     $.ajax({
        //         method: "POST",
        //         url: "/userdetails_update",
        //         data: JSON.stringify(obj)
        //     }).done(function(msg) {
        //         $('#admin-apply-customer-payment-modal').modal('hide');
        //         if (msg.error) {
        //             window.FlashMessage.error(msg.error, {
        //                 progress: true,
        //                 timeout: 6000
        //             });
        //         } else {
        //             window.FlashMessage.success(msg.success, {
        //                 progress: true,
        //                 timeout: 6000
        //             });
        //         }
        //         $.get('/userdetails/<?= $user->id ?>/account', function(htmldata) {
        //             $('#account-section').html(htmldata);
        //         });
        //         // location.reload();
        //         // updateUi();
        //     });

        //     return false;
        // });
    });
</script>