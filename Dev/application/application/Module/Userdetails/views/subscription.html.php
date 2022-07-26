<div id="subscription-section">
    <div class="card mg-b-20 mg-lg-b-25">
        <div class="card-header pd-y-15 pd-x-20 d-flex align-items-center justify-content-between">
            <span class="tx-13 tx-spacing-1 tx-uppercase tx-semibold mg-b-0">Subscription List</span>
        </div>
        <div class="card-body pd-25">
            <?php if (count($subscriptions) == 0) { ?>
                <div style="margin: auto;width:100%;">
                    <div style="color:#666;text-align:center;padding:25px;font-weight:500;background-color:#EEE">
                        Currently you do not have any subscription.
                    </div>
                </div>
            <?php } else { ?>
                <table class="table table-responsive w-100 d-block d-md-table" style="border-bottom: 1px solid #DBDCDB;">
                    <thead class="thead-secondary" style="background: #eee; color: #555">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Plan</th>
                            <th scope="col" class="text-center">Invoice(s)</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $now = date('U');
                        foreach ($subscriptions as $subc) {
                            $plan = \Model\Package::find($subc->plan_id);
                            $payment = \Model\Paymentplan::find($subc->payment_plan);
                            $invoices = \Model\Invoice::find_all_by_tenant_id_and_package_id_and_payment_plan($tenant->id, $plan->id, $payment->id);
                        ?>
                            <tr>
                                <th scope="row">#<?= $subc->id ?></th>
                                <td><?= $plan->name ?></td>
                                <td><?= $payment->name ?></td>
                                <td class="text-center"><?= count($invoices) ?> </td>
                                <td><?php
                                    if ($subc->status == STATUS_ACTIVE) { ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php } else { ?>
                                        <span class="badge badge-danger">Suspended</span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
</div>