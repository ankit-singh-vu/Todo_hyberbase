<?php

namespace App;

use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;

class Quickbooklib
{
    private $dataService = '';

    public function __construct()
    {
        // Get Quick Book Creds
        $qbToken = \Model\Qbtoken::first();

        // oAuth
        $oauth2LoginHelper = new OAuth2LoginHelper(\Kernel()->config('app.QUICKBOOK_CLIENT_ID'), \Kernel()->config('app.QUICKBOOK_SECRET'));
        $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($qbToken->refresh_token);
        $accessTokenValue = $accessTokenObj->getAccessToken();
        $refreshTokenValue = $accessTokenObj->getRefreshToken();

        // Update DB
        $qbToken->access_token = $accessTokenValue;
        $qbToken->refresh_token = $refreshTokenValue;
        $qbToken->save();

        // Prep Data Services
        $this->dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => \Kernel()->config('app.QUICKBOOK_CLIENT_ID'),
            'ClientSecret' => \Kernel()->config('app.QUICKBOOK_SECRET'),
            'accessTokenKey' => $accessTokenValue,
            'refreshTokenKey' => $refreshTokenValue,
            'QBORealmID' => $qbToken->realm_id,
            'baseUrl' => "Development"
        ));
    }

    /* 
    *** Create Quick Book Invoice ***
    *** Param Invoice Id *** 
    *** Return QuickBook Invoice Object ***
    */
    public function createInvoice($invId)
    {
        try {
            // Fetch Invoice
            $invoice = \Model\Invoice::find($invId);

            // Fetch tenant
            $tenant = \Model\Tenant::find($invoice->tenant_id);
            $qbCustomer = NULL;

            if (empty($tenant->qb_customer_id) || !$tenant->qb_customer_id || $tenant->qb_customer_id == '') {
                // Call create customer function
                $qbCustomer = $this->createCustomer($tenant->id);
            } else {
                // Get customer data
                $qbCustomer = $this->dataService->FindbyId('customer', $tenant->qb_customer_id);
            }
            $theResourceObj = Invoice::create([
                "Line" => [
                    [
                        "Amount" => (float) $invoice->amount,
                        "DetailType" => "SalesItemLineDetail",
                        "SalesItemLineDetail" => array(
                            "Qty"   => 1,
                        ),
                        "Description" => \Model\Package::find($invoice->package_id)->name . " - " . \Model\Paymentplan::find($invoice->payment_plan)->invoice_line . " - " . $invoice->description,
                    ]
                ],
                "CustomerRef" => [
                    "value" => $qbCustomer->Id
                ],
                "DueDate" => date('Y-m-d', $invoice->invdate)
            ]);
            // Get Result
            $resultingObj = $this->dataService->Add($theResourceObj);

            // Get Error
            $error = $this->dataService->getLastError();

            // Update Invoice Record
            $invoice->qb_invoice_id = $resultingObj->Id;
            $invoice->save();
            return $resultingObj;
        } catch (\Throwable $th) {
            return array(
                'success' => false,
                'error'   => true,
                'data'    => $th->getMessage()
            );
        }
    }

    /* 
    *** Update Quick Book Invoice ***
    *** Param Invoice Id *** 
    *** Return QuickBook Invoice Object ***
    */
    public function updateInvoice($invId)
    {
        // Get data from DB
        $invoiceD = \Model\Invoice::find($invId);
        if ($invoiceD->qb_invoice_id && $invoiceD->qb_invoice_id != '') {
            $invoice = $this->dataService->FindbyId('invoice', $invoiceD->qb_invoice_id);

            $theResourceObj = Invoice::update($invoice, [
                "Line" => [
                    [
                        "Amount" => (float) $invoiceD->amount,
                        "DetailType" => "SalesItemLineDetail",
                        "SalesItemLineDetail" => array(
                            "Qty"   => 1
                        ),
                        "Description" => \Model\Package::find($invoiceD->package_id)->name . " - " . \Model\Paymentplan::find($invoiceD->payment_plan)->invoice_line . " - " . $invoiceD->description,
                    ]
                ],
            ]);

            $resultingObj = $this->dataService->Update($theResourceObj);
            $error = $this->dataService->getLastError();

            if ($error) {
                return array(
                    'success' => false,
                    'error'   => true,
                    'data'    => $error
                );
            } else {
                return array(
                    'success' => true,
                    'error'   => false,
                    'data'    => $resultingObj
                );
            }
        }
    }

    /* 
    *** Delete Quick Book Invoice ***
    *** param Invoice Id *** 
    *** return QuickBook Invoice Object ***
    */
    public function deleteInvoice($invId)
    {
        try {
            // Get data from DB
            $invoiceD = \Model\Invoice::find($invId);
            if ($invoiceD->qb_invoice_id && $invoiceD->qb_invoice_id != '') {
                $invoice = $this->dataService->FindbyId('invoice', $invoiceD->qb_invoice_id);
                $resultingObj = $this->dataService->Delete($invoice);
                $error = $this->dataService->getLastError();

                if ($error) {
                    return array(
                        'success' => false,
                        'error'   => true,
                        'data'    => $error
                    );
                } else {
                    return array(
                        'success' => true,
                        'error'   => false,
                        'data'    => $resultingObj
                    );
                }
            } else {
                return array(
                    'success' => false,
                    'error'   => true,
                    'data'    => 'No data found'
                );
            }
        } catch (\Throwable $th) {
            return array(
                'success' => false,
                'error'   => true,
                'data'    => $th->getMessage()
            );
        }
    }

    /* 
    *** Delete Quick Book Invoice ***
    *** param Invoice Id *** 
    *** return QuickBook Invoice Object ***
    */
    public function createPayment($invId)
    {
        try {
            // Get data from DB
            $invoiceD = \Model\Invoice::find($invId);
            // Get tenant information
            $tenant = \Model\Tenant::find($invoiceD->tenant_id);
            if ($invoiceD->qb_invoice_id && $invoiceD->qb_invoice_id != '') {
                $theResourceObj = Payment::create([
                    "CustomerRef" =>
                    [
                        "value" => $tenant->qb_customer_id
                    ],
                    "TotalAmt" => (float) $invoiceD->amount,
                    "Line" => [
                        [
                            "Amount" => (float) $invoiceD->amount,
                            "LinkedTxn" => [
                                [
                                    "TxnId" => $invoiceD->qb_invoice_id,
                                    "TxnType" => "Invoice"
                                ]
                            ]
                        ]
                    ]
                ]);
                $resultingObj = $this->dataService->Add($theResourceObj);
                $error = $this->dataService->getLastError();

                if ($error) {
                    return array(
                        'success' => false,
                        'error'   => true,
                        'data'    => $error
                    );
                } else {
                    return array(
                        'success' => true,
                        'error'   => false,
                        'data'    => $resultingObj
                    );
                }
            } else {
                return array(
                    'success' => false,
                    'error'   => true,
                    'data'    => 'No data found'
                );
            }
        } catch (\Throwable $th) {
            return array(
                'success' => false,
                'error'   => true,
                'data'    => $th->getMessage()
            );
        }
    }

    /* 
    *** Create Quick Book Customer ***
    *** Param Tenant Id *** 
    *** Return QuickBook Invoice Object ***
    */
    public function createCustomer($tenantId)
    {
        $tenant = \Model\Tenant::find($tenantId);

        $ndata = parse_name($tenant->name);
        // Fetch billing address
        $billingAddress = \Model\Billingaddress::find_by_tenant_id($tenant->id);

        $billAddressObj = NULL;

        if ($billingAddress && !empty($billingAddress)) {
            $billAddressObj = array(
                'Line1' => $billingAddress->address ? $billingAddress->address : '',
                'Country' => $billingAddress->country ?  $billingAddress->country : ($tenant->country ? $tenant->country : 'USA'),
                'PostalCode' => $billingAddress->zip_code ? $billingAddress->zip_code : '',
            );
        } else {
            $billAddressObj = array(
                'Country' => 'USA'
            );
        }

        $theResourceObj = Customer::create([
            "BillAddr" => $billAddressObj,
            "Notes" => "Advisor Learn Customer",
            "GivenName" => $ndata['first'],
            "FamilyName" => $ndata['last'],
            "FullyQualifiedName" => $tenant->name,
            "CompanyName" => "",
            "DisplayName" => "$tenant->name"
        ]);

        $resultingObj = $this->dataService->Add($theResourceObj);
        $error = $this->dataService->getLastError();

        $tenant->qb_customer_id = $resultingObj->Id;
        $tenant->save();

        return $resultingObj;
    }

    public function updateCustomer()
    {
        // :Todo
    }
    public function deleteCustomer()
    {
        // :Todo
    }
}
