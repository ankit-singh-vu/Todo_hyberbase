<div id="invoice-section">
    <style>
        .sign-modal {
            width: 100%;
            height: 100%
        }

        @media screen and (min-width: 500px) {
            .sign-modal {
                width: 850px;
                height: 900px
            }
        }
    </style>
    <!-- Invoice Section -->
    <div class="card mg-b-20 mg-lg-b-25">
        <div class="card-header pd-y-15 pd-x-20 d-flex align-items-center justify-content-between">
            <span class="tx-13 tx-spacing-1 tx-uppercase tx-semibold mg-b-0">Invoice List</span>
        </div>
        <div class="card-body pd-x-10">
            <?php if (count($invoices) == 0) { ?>
                <div style="margin: auto; width:100%;">
                    <div style="color:#666;text-align:center;padding:25px;font-weight:500;background-color:#EEE">
                        Currently you do not have any invoice.
                    </div>
                </div>
            <?php } else { ?>
                <table class="table table-responsive w-100 d-block d-md-table" style="border-bottom: 1px solid #DBDCDB;">
                    <thead class="thead-secondary" style="background: #eee; color: #555">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Date</th>
                            <th scope="col">Package</th>
                            <th scope="col">Details</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $now = date('U');
                        foreach ($invoices as $invoice) {
                            $paid = false;
                            $upcoming = false;
                            $invCancelled = false;
                            if ($invoice->status == INVOICE_PAID) {
                                $paid = true;
                            } else if ($invoice->status == INVOICE_CANCELLED) {
                                $invCancelled = true;
                            }
                            if ($invoice->invdate > $now) {
                                $upcoming = true;
                                continue;
                            }
                        ?>
                            <tr>
                                <th scope="row">#<?= $invoice->id ?></th>
                                <td><?= date(\Kernel()->config('app.i18n.date_format'), $invoice->invdate) ?></td>
                                <td><?= \Model\Package::find($invoice->package_id)->name ?></td>
                                <td><?= $invoice->description ?></td>
                                <td>$<?= number_format($invoice->amount, 2) ?></td>
                                <td>
                                    <?php
                                    if ($invCancelled) { ?>
                                        <span class="badge badge-danger mg-t-0">Cancelled</span>
                                    <?php } else if ($paid) { ?>
                                        <span class="badge badge-success mg-t-0">Paid</span>
                                    <?php } else if ($upcoming && !$paid) { ?>
                                        <span class="badge badge-warning mg-t-0">Upcoming</span>
                                    <?php } else if (!$upcoming && !$paid) { ?>
                                        <span class="badge badge-danger mg-t-0">Due</span>
                                    <?php }
                                    ?>
                                </td>
                                <td class="text-right" style="width: auto;">
                                    <?php
                                    if ($paid) { ?>
                                        <a href="/admin_invoice/<?= $invoice->id ?>/download" class="badge badge-secondary text-uppercase pd-t-5" target="_blank" invid="<?= $invoice->id ?>" title="Download Invoice"><i class="fa fa-download tx-12"></i></a>
                                    <?php } else if ($upcoming && !$paid) { ?>
                                        <div class="row">
                                            <div class="col-6 mx-0 px-0 text-right">
                                                <a href="#" class="badge badge-secondary apply-payment-toinv pd-t-4 " invid="<?= $invoice->id ?>" title="Pay using Stripe"><i class="fa fa-credit-card tx-12"></i></a>
                                            </div>
                                            <div class="col-6 mx-0 px-0 text-left">
                                                <a href="/admin_invoice/<?= $invoice->id ?>/download" class="badge badge-secondary text-uppercase pd-t-5" target="_blank" invid="<?= $invoice->id ?>" title="Download Invoice"><i class="fa fa-download tx-12"></i></a>
                                            </div>
                                        </div>


                                    <?php } else if (!$upcoming && !$paid) { ?>
                                        <div class="row">
                                            <div class="col-6 mx-0 px-0 text-right">
                                                <a href="#" class="badge badge-secondary apply-payment-toinv pd-t-4 " invid="<?= $invoice->id ?>" title="Pay using Stripe"><i class="fa fa-credit-card tx-12"></i></a>
                                            </div>
                                            <div class="col-6 mx-0 px-0 text-center">
                                                <a href="/admin_invoice/<?= $invoice->id ?>/download" class="badge badge-secondary text-uppercase pd-t-5" target="_blank" invid="<?= $invoice->id ?>" title="Download Invoice"><i class="fa fa-download tx-12"></i></a>
                                            </div>
                                        </div>


                                    <?php }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>

    <!-- Doc Section -->
    <?php if (count($documents) > 0) : ?>
        <div class="card mg-b-20 mg-lg-b-25">
            <div class="card-header pd-y-15 pd-x-20 d-flex align-items-center justify-content-between">
                <span class="tx-13 tx-spacing-1 tx-uppercase tx-semibold mg-b-0">Documents</span>
            </div>
            <div class="card-body pd-x-10">
                <?php if (count($documents) == 0) { ?>
                    <div style="margin: auto;width:100%;">
                        <div style="color:#666;text-align:center;padding:25px;font-weight:500;background-color:#EEE">
                            Currently you do not have any invoice.
                        </div>
                    </div>
                <?php } else { ?>
                    <table class="table table-responsive w-100 d-block d-md-table" style="border-bottom: 1px solid #DBDCDB;">
                        <thead class="thead-secondary" style="background: #eee; color: #555">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Title</th>
                                <th scope="col">Date</th>
                                <th scope="col">Status</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($documents as $doc) {
                            ?>
                                <tr>
                                    <th scope="row">#<?= $doc->id ?></th>
                                    <td><?= $doc->template_name ?></td>
                                    <td><?= date(\Kernel()->config('app.i18n.date_format'), $doc->created_at->getTimestamp()) ?></td>
                                    <td>
                                        <?php
                                        if ($doc->status == 0) { ?>
                                            <span id="<?= $doc->id ?>" class="sent-doc-signed badge badge-info" docId="<?= $doc->id ?>" envId="<?= $doc->envelope_id ?>">Checking Status...</span>
                                        <?php  } else if ($doc->status == 1) { ?>
                                            <span class="badge badge-success">SIGNED</span>

                                        <?php }
                                        ?>
                                    </td>
                                    <td class="text-right">
                                        <?php
                                        if ($doc->status == 0) { ?>
                                            <span class="badge badge-secondary mg-l-20 sign-document" envId="<?= $doc->envelope_id ?>" docId="<?= $doc->id ?>" style="cursor: pointer;" title="Sign Document Digitally"><i class="fa fa-pen tx-12"></i></span>
                                        <?php  } else if ($doc->status == 1) { ?>

                                            <a target="_blank" href="/admin_docsign/download?envId=<?= $doc->envelope_id ?>" envId="<?= $doc->envelope_id ?>" download class="badge badge-secondary download-doc"><i class="fa fa-download tx-12"></i></a>
                                        <?php }
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Payment Modal Starts -->
<div class="modal" id="admin-apply-customer-payment-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="max-width:600px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-heading">Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="paymentForm">
                    <input type="hidden" name="invoiceId" id="invoiceId">
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <label>Full Name</label>
                            <input type="text" name="fullName" autocomplete="off" class="form-control cust-direct-name-first" style="width:100%;float:left" placeholder="Full Name">
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
                <button type="button" id="payment-submit" class="btn btn-primary add-new-customer-direct-submit">Make Payment</button>
            </div>
        </div>
    </div>
</div>
<!-- Payment Modal Ends -->

<!-- Docusign modal Starts -->
<div class="modal" id="docu-sign-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered sign-modal modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Docusign Signature</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center pd-b-50 pd-x-1" id="modal-b">
                <iframe src="" id="docusign-section" style="width: 100%; height: 600px; border: 0px" title="Iframe Example"></iframe>
            </div>
        </div>
    </div>
</div>
<!-- Docusign modal Ends -->

<script>
    $(document).ready(function() {
        var months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        var mySelect = $('#expMonth');

        $.each(months, function(val, text) {
            mySelect.append(
                $('<option></option>').val(text).html(text)
            );
        });

        $('.apply-payment-toinv').click(function() {
            var invid = $(this).attr('invid');
            $("#invoiceId").val(invid);
            $('#modal-heading').html(`Payment of Invoice #${invid}`);
            $('#admin-apply-customer-payment-modal').modal();
            return false;
        });

        $('.download-doc').click(function() {
            $(this).html('<i class="fa fa-spinner"></i>');
            let elem = $(this);
            setTimeout(function() {
                elem.html('<i class="fa fa-download tx-12"></i>');
            }, 4500);
        });

        $('#payment-submit').click(function() {
            $(this).prop('disabled', true);
            $(this).html('Processing...');

            var fields = {};
            let formError = false;
            $("#paymentForm").find(":input").each(function() {
                if (!$(this).val() || $(this).val() == '') {
                    $('#admin-apply-customer-payment-modal').modal('hide');
                    formError = true;
                }
                fields[this.name] = $(this).val();
            });
            if (formError) {
                window.FlashMessage.error('Please fill all the fields', {
                    progress: true,
                    timeout: 4000
                });
                return;
            }

            var obj = fields;

            $.ajax({
                method: "POST",
                url: "/admin_invoice",
                data: JSON.stringify(obj)
            }).done(function(msg) {
                $('#admin-apply-customer-payment-modal').modal('hide');
                if (msg.error) {
                    window.FlashMessage.error(msg.error, {
                        progress: true,
                        timeout: 6000
                    });
                } else {
                    window.FlashMessage.success("Invoice paid successfully", {
                        progress: true,
                        timeout: 6000
                    });
                }
                $.get('/userdetails/<?= $userDetails->id ?>/invoice', function(htmldata) {
                    $('#invoice-section').html(htmldata);
                });
            });

            return false;
        });

        // Sign Docusign
        $(".sign-document").click(function() {
            $(this).html('<i class="fa fa-spinner"></i>');
            $(this).prop('disabled', true)
            let docId = $(this).attr('docId');

            $.get(`/docsign/${docId}/checkdocwithid`, function(data) {
                $(this).html('<i class="fa fa-pen tx-12"></i>');
                if (data == '' || !data) {
                    window.FlashMessage.error("Sorry, something went wrong.", {
                        progress: true,
                        timeout: 4000
                    });
                    $.get('/userdetails/<?= $userDetails->id ?>/invoice', function(htmldata) {
                        $('#invoice-section').html(htmldata);
                    });
                    return false;
                } else {
                    $('#docu-sign-modal').modal();
                    var $iframe = $('#docusign-section');
                    if ($iframe.length) {
                        $iframe.attr('src', data);
                        return false;
                    }
                }
            });
        });

        $('#docu-sign-modal').on('hidden.bs.modal', function(e) {
            $.get('/userdetails/<?= $userDetails->id ?>/invoice', function(htmldata) {
                $('#invoice-section').html(htmldata);
            });
        })

        $(".sent-doc-signed").map(function() {
            let parentElem = $(this).parent();
            let envId = $(this).attr('envId');
            let docId = $(this).attr('docId');
            $.get('/admin_docsign/checkdoc?envId=' + envId + "&docId=" + docId, function(data) {
                data = JSON.parse(data);
                if (data.status == 'sent') {
                    $('#' + docId).html("SENT");
                    $('#' + docId).removeClass("badge-info");
                    $('#' + docId).addClass("badge-warning");
                } else {
                    $('#' + docId).html("SIGNED");
                    parentElem.find('.sign-document').hide();
                    $('#' + docId).removeClass("badge-info");
                    $('#' + docId).addClass("badge-success");
                }
            });
        }).get();

    });
</script>