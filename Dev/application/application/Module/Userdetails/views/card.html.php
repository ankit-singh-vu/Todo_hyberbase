<style>
    .cardImg {
        height: 30px;
        width: 70px;
    }
</style>
<div id="card-section">
    <div class="card mg-b-20 mg-lg-b-25">
        <div class="card-header pd-y-10 pd-x-20 d-flex align-items-center justify-content-between">
            <span class="tx-13 tx-spacing-1 tx-uppercase tx-semibold mg-b-0">Payment Methods</span>
            <span class="tx-13 tx-spacing-1 tx-uppercase mg-b-0 btn btn-outline-primary" style="cursor: pointer;" data-toggle="modal" data-target="#addPaymentModal"><i class="fa fa-plus"></i> Add New</span>
        </div>
        <div class="card-body pd-25">

            <?php if (count($paymentMethods) == 0) { ?>
                <div style="margin: auto;width:100%;">
                    <div style="color:#666;text-align:center;padding:25px;font-weight:500;background-color:#EEE">
                        Currently you do not have any saved payment methods.
                    </div>
                </div>
            <?php } else { ?>

                <?php
                foreach ($paymentMethods as $method) {
                ?>
                    <div class="card mg-b-10">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                    <?php if (strtolower($method->card_type) == 'visa') { ?>
                                        <img src="/logos/visa.png" class="cardImg">
                                    <?php } else if (strtolower($method->card_type) == 'mastercard') { ?>
                                        <img src="/logos/mastercard.png" class="cardImg">
                                    <?php } else { ?>
                                        <img src="/logos/american-express.png" class="cardImg">
                                    <?php } ?>
                                    <p class="mg-b-0 pd-b-0 mg-t-10"><?= $method->name ?> </p>
                                    <p class="mg-b-0 pd-b-0 mg-t-5">**** **** **** <?= $method->card_number ?></p>

                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 pd-t-13 text-right">
                                    <button class="btn btn-outline-danger delete-payment-method" cardid='<?= $method->id ?>'>Delete</button>
                                    <p class="mg-t-15 mg-b-0 pd-b-0">Card expires at <?= $method->exp_month ?> / <?= substr($method->exp_year, -2) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

            <?php } ?>

        </div>
    </div>
</div>

<!-- Add payment method Modal Starts -->

<div class="modal" id="addPaymentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="max-width:600px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add new payment method</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="paymentForm">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label>Full Name</label>
                            <input type="text" name="name" autocomplete="off" class="form-control cust-direct-name-first" style="width:100%;float:left" placeholder="Full Name">
                            <div style="clear:both;"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label>Card Number</label>
                            <input type="number" autocomplete="off" maxlength="16" name="cardNumber" class="form-control cust-direct-email" placeholder="Credit / Debit Card Number">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label>Expiry Month</label>
                            <select name="expMonth" id="expMonth" class="form-control"></select>
                        </div>
                        <div class="col-sm-3">
                            <label>Expiry Year</label>
                            <input type="number" autocomplete="off" id="expYear" name="expYear" class="form-control" placeholder="Expiry Year" maxlength="4" min="<?php echo date("Y") ?>">
                        </div>
                        <div class="col-sm-4">
                            <label>CVC</label>
                            <input type="password" autocomplete="off" class="form-control cust-direct-repass" name="cvc" placeholder="CVC">
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary save-payment-method" id="save-payment-method">Save Payment Method</button>
            </div>
        </div>
    </div>
</div>

<!-- Add payment method Modal Ends -->

<script>
    $(document).ready(function() {
        var months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        var mySelect = $('#expMonth');

        $.each(months, function(val, text) {
            mySelect.append(
                $('<option></option>').val(text).html(text)
            );
        });

        $('#save-payment-method').click(function() {
            $(this).prop('disabled', true);
            $(this).html('Processing...');

            var fields = {};
            let formError = false;
            $("#paymentForm").find(":input").each(function() {
                if (!$(this).val() || $(this).val() == '') {
                    $('#addPaymentModal').modal('hide');
                    formError = true;
                }
                fields[this.name] = $(this).val();
            });
            if (formError) {
                window.FlashMessage.error('Please fill all the fields', {
                    progress: true,
                    timeout: 4000
                });
                $(this).prop('disabled', false);
                $(this).html('Save Payment Method');
                return;
            }

            var obj = fields;

            $.ajax({
                method: "POST",
                url: "/userdetails_paymentmethod",
                data: JSON.stringify(obj)
            }).done(function(msg) {
                console.log(msg);
                $('#addPaymentModal').modal('hide');
                if (msg.error) {
                    window.FlashMessage.error(msg.error, {
                        progress: true,
                        timeout: 6000
                    });
                } else {
                    window.FlashMessage.success("Payment method added successfully", {
                        progress: true,
                        timeout: 6000
                    });
                }
                $.get('/userdetails/<?= $userDetails->id ?>/card', function(htmldata) {
                    $('#card-section').html(htmldata);
                });
            });

            return false;
        });

        $('.delete-payment-method').click(function() {

            var conff = confirm('Are you sure you want to delete the payment method?');
            if (conff) {
                var cardid = $(this).attr('cardid');
                $(this).html('Processing...');
                $.get('/userdetails_paymentmethod/' + cardid + '/deletemethod', function() {
                    window.FlashMessage.success("Payment method deleted successfully", {
                        progress: true,
                        timeout: 6000
                    });
                    $.get('/userdetails/<?= $userDetails->id ?>/card', function(htmldata) {
                        $('#card-section').html(htmldata);
                    });
                });
            }
            return false;
        });
    });
</script>