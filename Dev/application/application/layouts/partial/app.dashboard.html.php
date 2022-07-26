<?php
// Get user Details
$user = \Model\Session::load_user()->user;
$tenant = \Model\Tenant::find($user->c_tenant);

// get enrolled courses
$userSub = \Model\Subscription::find_all_by_tenant_id_and_status($tenant->id, STATUS_ACTIVE);
$packCourseA = array();
if (count($userSub) > 0) {
    foreach ($userSub as $subc) {
        $packcourses = \Model\Packagecourse::find_all_by_package_id($subc->plan_id);
        if (!empty($packCourseA)) {
            $packCourseA = array_merge($packCourseA, $packcourses);
        } else {
            $packCourseA = $packcourses;
        }
    }
}

// Portfolio Details
$aumTarget = 'NA';
$incomeTarget = 'NA';
$newClientTarget = 'NA';
$portfolio = \Model\Portfolio::find_by_user_id_and_tenant_id($user->id, $tenant->id);
if ($portfolio) {
    $desiredState = $portfolio && $portfolio->desired_state ? json_decode($portfolio->desired_state) : NULL;
    $aumTarget = $desiredState && !empty($desiredState->aum) ? $desiredState->aum : 'NA';
    $newClientTarget = $desiredState && !empty($desiredState->new_client) ? $desiredState->new_client : 'NA';
    $incomeTarget = $desiredState && !empty($desiredState->target_income) ? $desiredState->target_income : 'NA';
}

?>
<div class="container pd-x-0 pd-t-20 mg-x-auto">
    <div class="col-md-10 col-sm-12 mx-auto" style="width: 94%;">
        <h3 class="mg-b-20">Your Personalized Dashboard <?= $user->first_name . " " . $user->last_name ?></h3>
        <!-- Course details -->
        <div class="card shadow mx-auto">
            <div class="card-header pd-b-10">
                <span class="tx-16" style="font-weight: 500">Courses Enrolled</span>
            </div>
            <div class="card-body">
                <?php
                if (count($packCourseA) > 0) {
                    $courseArr = array();
                    $i = 1;
                    foreach ($packCourseA as $packCou) {
                        $course = \Model\Course::find($packCou->course_id);
                        $courseTrack = \Model\Coursetrack::find_by_course_id_and_tenant_id($course->id, $tenant->id);
                        if (in_array($course->id, $courseArr)) {
                            continue;
                        }
                        array_push($courseArr, $course->id);

                        if ($courseTrack) {
                            $completedPercentage = (int) ceil(($courseTrack->completed_chapter / $courseTrack->total_chapter) * 100);
                            $pendingPercentage = (int) (100 - $completedPercentage);
                            if ($completedPercentage > 0 && $completedPercentage < 5) {
                                $toConvert = 6;
                            } else {
                                $toConvert = $completedPercentage;
                            }
                            $roundComP = round($toConvert, -1);
                            $roundPenP = 100 - $roundComP;
                        } else {
                            $completedPercentage = 0;
                            $pendingPercentage = 100 - $completedPercentage;
                            $roundComP = 0;
                            $roundPenP = 100 - $roundComP;
                        }
                        // echo $completedPercentage;
                ?>
                        <div class="row">
                            <div class="col-5 pd-t-10">
                                <h6 class="
                                    tx-sans
                                    tx-uppercase
                                    tx-11
                                    tx-spacing-1
                                    tx-color-01
                                    tx-semibold
                                    mg-b-5 mg-md-b-8">
                                    <?= $i ?>. <?= $course->name ?>
                                </h6>
                            </div>
                            <div class="col-7 pd-t-0 mg-t-0">
                                <div class="d-flex align-items-end justify-content-between mg-b-5">
                                    <!-- <h5 class="tx-normal tx-rubik lh-2 mg-b-0"><?= $completedPercentage ?>%</h5>
                                    <h5 class="tx-normal tx-rubik lh-2 mg-b-0"><?= $pendingPercentage ?>%</h5> -->
                                </div>
                                <div class="progress ht-15 mg-b-0" style="margin-top: 7px;">
                                    <div class="progress-bar wd-<?= $roundComP ?>p pd-t-1" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="background: #4FCA04; color: #000000;">
                                        <?= $completedPercentage > 0 ? $completedPercentage . "%" : ''
                                        ?>
                                    </div>
                                    <div class="progress-bar wd-<?= $roundPenP ?>p pd-t-1" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="background: #CFCFCF">

                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <hr>
                            </div>
                        </div>

                    <?php
                        $i++;
                    }
                } else { ?>
                    <p class="tx-16">You are not enrolled to any course yet.</p>
                <?php }
                ?>
            </div>
        </div>
    </div>

    <!-- Portfolio Details -->
    <div class="row col-md-10 col-sm-12 mg-t-40 mg-b-10 pd-l-1 mx-auto" style="width: 94%;">
        <div class="col-8 col-md-8 col-sm-6 mg-l-0">
            <h4 class="mg-l-0 pd-l-0">Your 12 Months Goals</h4>
        </div>
        <div class="col-4 col-md-4 col-sm-6 text-right pd-r-0"><a href="/userprofile" class="btn btn-outline-primary btn-sm mg-r-0">Edit Targets</a></div>
    </div>
    <div class="col-md-10 col-sm-12 mx-auto" style="width: 94%;">
        <div class="card pd-b-20 shadow mx-auto pd-l-0">
            <div class="card-header pd-b-10 pl-l-0">
                <span class="tx-16" style="font-weight: 500">Your goals based on the advisor formula data</span>
            </div>
            <div class="card-body pd-t-20">
                <div class="d-sm-flex mg-t-10">
                    <div class="media">
                        <div class="
                          wd-40 wd-md-50
                          ht-40 ht-md-50
                          bg-teal
                          tx-white
                          mg-r-10 mg-md-r-10
                          d-flex
                          align-items-center
                          justify-content-center
                          rounded
                          op-6
                        ">
                            <i data-feather="bar-chart-2"></i>
                        </div>
                        <div class="media-body">
                            <h6 class="
                            tx-sans
                            tx-uppercase
                            tx-10
                            tx-spacing-1
                            tx-color-03
                            tx-semibold
                            tx-nowrap
                            mg-b-5 mg-md-b-8
                          ">
                                AUM / Insurance Targets
                            </h6>
                            <h4 class="
                            tx-20 tx-sm-18 tx-md-20 tx-normal tx-rubik
                            mg-b-0
                          ">
                                <?= $aumTarget != 'NA' && $aumTarget != '' ? '$' . $aumTarget : 'NA' ?>
                            </h4>
                        </div>
                    </div>
                    <div class="media mg-t-20 mg-sm-t-0 mg-sm-l-15 mg-md-l-70">
                        <div class="
                          wd-40 wd-md-50
                          ht-40 ht-md-50
                          bg-pink
                          tx-white
                          mg-r-10 mg-md-r-10
                          d-flex
                          align-items-center
                          justify-content-center
                          rounded
                          op-5
                        ">
                            <i data-feather="bar-chart-2"></i>
                        </div>
                        <div class="media-body">
                            <h6 class="
                            tx-sans
                            tx-uppercase
                            tx-10
                            tx-spacing-1
                            tx-color-03
                            tx-semibold
                            mg-b-5 mg-md-b-8
                          ">
                                Annual Income Targets
                            </h6>
                            <h4 class="
                            tx-20 tx-sm-18 tx-md-20 tx-normal tx-rubik
                            mg-b-0
                          ">
                                <?= $incomeTarget != 'NA' && $incomeTarget != '' ? '$' . $incomeTarget : 'NA' ?>
                            </h4>
                        </div>
                    </div>
                    <div class="media mg-t-20 mg-sm-t-0 mg-sm-l-15 mg-md-l-70">
                        <div class="
                          wd-40 wd-md-50
                          ht-40 ht-md-50
                          bg-primary
                          tx-white
                          mg-r-10 mg-md-r-10
                          d-flex
                          align-items-center
                          justify-content-center
                          rounded
                          op-4
                        ">
                            <i data-feather="bar-chart-2"></i>
                        </div>
                        <div class="media-body">
                            <h6 class="
                            tx-sans
                            tx-uppercase
                            tx-10
                            tx-spacing-1
                            tx-color-03
                            tx-semibold
                            mg-b-5 mg-md-b-8
                          ">
                                New Client Targets
                            </h6>
                            <h4 class="
                            tx-20 tx-sm-18 tx-md-20 tx-normal tx-rubik
                            mg-b-0
                          ">
                                <?= $newClientTarget ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Docusign modal -->
<div class="modal" id="docu-sign-modal-course" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="width:850px; height: 900px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Docusign Signature</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center pd-b-50">
                <iframe src="" id="docusign-section" height="600px" width="100%" title="Iframe Example"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // $.get('/docsign/checkdoc', function(data) {
        //     if (data == '' || !data) {
        //         return false;
        //     } else {
        //         $('#docu-sign-modal-course').modal();
        //         var $iframe = $('#docusign-section');
        //         if ($iframe.length) {
        //             $iframe.attr('src', data);
        //             return false;
        //         }
        //     }
        //     // $('#ticket-details-section').html(htmldata);
        // });
    });
</script>