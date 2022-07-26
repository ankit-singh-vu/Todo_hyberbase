<?php

namespace Module\Login\Signup;

use App,
    System,
    Model;


/**
 * Index Module
 *
 * Extends from base Application controller so custom functionality can be added easily
 * lib/App/Module/ControllerAbstract
 */
class Controller extends \System\Module\ControllerAbstract
{
    /**
     * Forget password
     *
     * @param System\Request $request
     * @return System\View\Template
     */
    public function postMethod(System\Request $request)
    {
        $email = $request->post('email');
        // Check if already exist
        $tenantC = \Model\Tenant::count_by_email($email);
        $userC = \Model\User::count_by_email($email);
        $accessC = \Model\Access::count_by_access_key($email);

        if ($tenantC > 0 || $userC > 0 || $accessC > 0) {
            $this->kernel->redirect('/login/signup?alreadyExist=1');
        }

        $userName = $request->post('firstname') . " " . $request->post('lastname');

        $session = \App\Account::create(array(
            'email' => $request->post('email'),
            'uname' => $userName,
            'password' => $request->post('password'),
            'sent_mail'=> 1
        ));
        $user = \Model\User::find($session->user_id);
        $tenant = \Model\Tenant::find($user->c_tenant);

        $user->set_tag(SYSTEM_USER_TYPE_CUSTOMER);
        $user->user_type = SYSTEM_USER_TYPE_CUSTOMER;
        $tenant->tenant_type = SYSTEM_TENANT_TYPE_CUSTOMER;

        $tenant->save();
        $user->save();

        $this->kernel->redirect('/login?signupsuccess=1');
    }
}
