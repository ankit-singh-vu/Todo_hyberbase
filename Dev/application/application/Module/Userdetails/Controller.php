<?php

namespace Module\Userdetails;

use App,
    System,
    Model;


/**
 * Index Module
 *
 * Extends from base Application controller so custom functionality can be added easily
 * lib/App/Module/ControllerAbstract
 */
class Controller extends App\Module\ControllerAbstract
{
    public function indexAction(System\Request $request)
    {
        $user = \Model\Session::load_user();
        $this->view->userDetails = $user->user;

        if ($user->user->profile_pic) {
            $imageData = base64_encode(file_get_contents($user->user->profile_pic));

            $src = 'data: ' . mime_content_type($user->user->profile_pic) . ';base64,' . $imageData;
            $this->view->profile_pic = $src;
        }else{
            $this->view->profile_pic = NULL;
        }

        $this->view->tenant = \Model\Tenant::find($user->user->c_tenant);
        return $this->view;
    }

    public function accountAction(System\Request $request)
    {
        $user = \Model\Session::load_user();
        // ___debug($user->user);
        $this->view->user = $user->user;
        $tenant = \Model\Tenant::find($user->user->c_tenant);
        $this->view->tenant = $tenant;

        // Billing address 
        $this->view->billingAddress = \Model\Billingaddress::find_by_user_id_and_tenant_id($user->user->id, $user->user->c_tenant);

        $packCourseA = NULL;

        // Course List
        $userSub = \Model\Subscription::find_all_by_tenant_id_and_status($tenant->id, STATUS_ACTIVE);
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

        $this->view->courseList = $packCourseA;

        return $this->view;
    }

    public function securityAction(System\Request $request)
    {
        $user = \Model\Session::load_user();
        // ___debug($user->user);
        $this->view->userDetails = $user->user;
        return $this->view;
    }

    public function subscriptionAction(System\Request $request)
    {
        $user = \Model\User::find($request->param('item'));
        $tenant = \Model\Tenant::find($user->c_tenant);
        $this->view->subscriptions = \Model\Subscription::find_all_by_tenant_id($user->c_tenant);
        // ___debug($user->user);
        $this->view->userDetails = $user;
        $this->view->tenant = $tenant;

        return $this->view;
    }

    public function invoiceAction(System\Request $request)
    {
        $user = \Model\User::find($request->param('item'));
        $tenant = \Model\Tenant::find($user->c_tenant);
        $this->view->invoices = \Model\Invoice::find_all_by_tenant_id($tenant->id);

        $this->view->userDetails = $user;
        $this->view->tenant = $tenant;

        // Fetch Docs
        $docs = \Model\Sentdoc::find_all_by_tenant_id($tenant->id);
        $this->view->documents = $docs;

        return $this->view;
    }

    public function cardAction(System\Request $request)
    {
        $user = \Model\User::find($request->param('item'));
        $tenant = \Model\Tenant::find($user->c_tenant);
        // payment methods
        $paymentMethods = \Model\Paymentmethod::find_all_by_user_id_and_tenant_id($user->id, $tenant->id);

        $this->view->paymentMethods = $paymentMethods;
        // ___debug($user->user);
        $this->view->userDetails = $user;
        $this->view->tenant = $tenant;
        return $this->view;
    }

    public function activityAction(System\Request $request)
    {
        $user = \Model\User::find($request->param('item'));
        $tenant = \Model\Tenant::find($user->c_tenant);

        // ___debug($user->user);
        $this->view->userDetails = $user;
        $this->view->tenant = $tenant;
        return $this->view;
    }
}
