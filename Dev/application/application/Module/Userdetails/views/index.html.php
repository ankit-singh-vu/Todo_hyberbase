<style>
    .tx-black {
        color: #000;
    }

    .side-content {
        background: #ffffff;
        border-right: 1px solid #ECECEC;
        border-bottom: 1px solid #CDCDCD;
        width: 100%;
        min-height: 100vh;
    }

    .contentDiv {
        width: 92%;
    }

    .main-content {
        display: none;
    }

    @media screen and (min-width: 500px) {
        .main-content {
            display: block;
        }
    }

    @media screen and (min-width: 500px) {
        .side-content {
            background: #ffffff;
            border-right: 1px solid #ECECEC;
            width: 280px;
            min-height: 100vh;
        }
    }
</style>
<link rel="stylesheet" href="/v3/assets/css/dashforge.profile.css" rel="stylesheet">
<div class="container-fluid pd-x-0 pd-l-15 tx-13 pd-t-0 pd-y-0" style="max-width: 100vw; overflow-x: hidden">
    <div class="row">
        <div class="pd-x-0 pd-y-10 side-content">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 text-center">
                    <div class="text-center" id="profile_pic_display" style="cursor: pointer;" title="Upload Profile Picture">
                        <?php if ($profile_pic && !empty($profile_pic)) { ?>
                            <img src="<?= $profile_pic ?>" class="rounded-circle" id="main-profile-image" alt="" style="height: 100px; width: 100px">
                        <?php } else { ?>
                            <img src="https://via.placeholder.com/500" class="rounded-circle" alt="" style="height: 100px; width: 100px">
                        <?php } ?>
                    </div>
                    <h5 class="mg-b-20 mg-t-20 tx-spacing--1"><?= $userDetails->first_name . " " . $userDetails->last_name ?></h5>
                    <p class="tx-13 tx-color-02 mg-b-25">
                        Member Since: <span><?= $userDetails->created_at->format("d/m/Y") ?></span>
                        <br>
                        Last Login: N/A
                    </p>

                </div>
            </div>
            <ul class="nav flex-column nav-pills mg-t-25">
                <li class="nav-item">
                    <a class="nav-link pd-l-25 pd-y-10 tx-15 tx-black loadProfileLink" style="border-radius: 0;" load='/userdetails/<?= $userDetails->id ?>/activity' href="#"><i class="mg-r-15 fas fa-stopwatch"></i> User Activity</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link pd-l-25 pd-y-10 tx-15 tx-black loadProfileLink" style="border-radius: 0;" load='/userdetails/<?= $userDetails->id ?>/security' href="#"><i class="mg-r-15 fa fa-key"></i> Password Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link pd-l-25 pd-y-10 tx-15 tx-black loadProfileLink" style="border-radius: 0;" load='/userdetails/<?= $userDetails->id ?>/account' href="#"><i class="mg-r-15 fas fa-address-book"></i> Address Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link pd-l-25 pd-y-10 tx-15 tx-black loadProfileLink" style="border-radius: 0;" load='/userdetails/<?= $userDetails->id ?>/invoice' href="#"><i class="mg-r-15 fas fa-file-invoice"></i> Invoices</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link pd-l-25 pd-y-10 tx-15 tx-black loadProfileLink" style="border-radius: 0;" load='/userdetails/<?= $userDetails->id ?>/subscription' href="#"> <i class="mg-r-15 fas fa-box-open"></i> Subscription Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link pd-l-25 pd-y-10 tx-15 tx-black loadProfileLink" load='/userdetails/<?= $userDetails->id ?>/card' style="border-radius: 0;" href="#"><i class="mg-r-15 fas fa-credit-card"></i> Credit Card Management</a>
                </li>
            </ul>
        </div>
        <div class="col-lg-9 col-md-8 col-sm-12 text-left contentDiv pd-t-20 pd-b-30 pd-l-0 mx-auto main-content">
            <h5>Loading...</h5>
        </div>
    </div>
    <!-- media -->
</div>
<form id="profilePicForm">
    <input type="file" name="profile_pic" style="display: none;" id="profile_pic" accept="image/png, image/webp, image/jpeg, image/jpg">
</form>

<script>
    // function updateUi(){
    //     $.get('/userdetails/', function(data) {
    //         $('.contentDiv').html("");
    //         $('.contentDiv').html(data);
    //     });
    // }
    $(document).ready(function() {
        $(".menu-link").show();
        $(".support-link").hide();

        $('#profile_pic_display').click(function() {
            $('#profile_pic').trigger('click');
        });
        $('#profile_pic').change(function() {
            // Profile picture Update
            var formData = new FormData();
            formData.append('profile_pic', $('#profile_pic')[0].files[0]);
            $.ajax({
                type: 'POST',
                url: '/userdetails_profilepicture',
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
                    $.get('/userdetails', function(htmldata) {
                        console.log("helo");
                        $('#client-content').html(htmldata);
                        let imgSrc = $("#main-profile-image").attr("src");
                        // console.log(imgSrc);
                        console.log($("#side-bar-profile-image").src);
                        $(".side-bar-profile-image").attr("src", imgSrc);
                    });
                }
            });
        })
        $('.loadProfileLink').click(function() {
            if (window.matchMedia('(max-width: 991px)').matches) {
                $('.side-content').hide();
                $('.main-content').show();
                $(".menu-link").hide();
                $(".support-link").show();
            }
            $('.contentDiv').html("<h5>Loading...</h5>");
            $('.nav-pills .active').removeClass('active');
            $(this).addClass('active');
            $.get($(this).attr('load'), function(data) {
                $('.contentDiv').html("");
                $('.contentDiv').html(data);
            });
            return false;
        });
        if (window.matchMedia("(min-width: 992px)").matches) {
            var fElem = $('.loadProfileLink')[0];
            if (fElem) {
                fElem.classList.add('active');
                let lnk = fElem.getAttribute('load');
                $.get(lnk, function(data) {
                    $('.contentDiv').html("");
                    $('.contentDiv').html(data);
                });
            }

        }

    })
</script>