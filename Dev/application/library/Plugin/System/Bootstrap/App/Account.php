<?php

namespace App;

use App,
    System;

/**
 * Class Account
 * @package App
 */
final class Account
{
    /**
     * @var
     */
    protected $tenant;

    /**
     * Account constructor.
     */
    public function __construct(\Model\Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * @param $attributes = array(
     *      'uname'         => 'Full name of the user'
     *      'organization'  => 'Name of organization' (optional)
     *      'org_email'     => 'Email of organization' (optional)
     *      'email'         => 'user@emailid.com'
     *      'password'      => 'secret',
     *      'plan'          => 'plan_id'
     * );
     * @return \ActiveRecord\Model
     * @throws \Exception
     */
    public static function create($attributes)
    {
        /*
        if(empty($attributes['plan'])) {
            throw new \Exception('Plan is required', 800);
        }*/

        if (empty($attributes['uname'])) {
            throw new \Exception('User\'s name is required', 801);
        }
        if (empty($attributes['email'])) {
            throw new \Exception('Email is required', 802);
        }
        if (empty($attributes['password'])) {
            throw new \Exception('Password is required', 803);
        }


        if (\Model\User::count_by_email($attributes['email']) > 0) {
            throw new \Exception('Email address is not unique', 805);
        }
        if (\Model\Access::count_by_access_key($attributes['email']) > 0) {
            throw new \Exception('Email address is not unique', 805);
        }

        /*
        try {
            $plan = \Model\Plan::find($attributes['plan']);
        } catch(\Exception $e) {
            throw new \Exception('Plan not found', 806);
        }*/


        $nameArray = parse_name($attributes['uname']);

        $user = \Model\User::create(array(
            "uuid"        => gen_uuid(),
            "email"       => $attributes['email'],
            "first_name"  => isset($nameArray['first']) ? $nameArray['first'] : '',
            "middle_name" => isset($nameArray['middle']) ? $nameArray['middle'] : '',
            "last_name"   => isset($nameArray['last']) ? $nameArray['last'] : '',
            "status"      => STATUS_ACTIVE
        ));

        if (isset($attributes['user_type'])) {
            if ($attributes['user_type'] == 'sub_user') {
                $user->c_tenant = $attributes['tenant_id'];
                $user->save();
            }
        } else {
            $tenant = \Model\Tenant::create(array(
                "uuid"        => gen_uuid(),
                "email"       => isset($attributes['org_email']) && trim($attributes['org_email']) != '' ? $attributes['org_email'] : $attributes['email'],
                "name"        => isset($attributes['organization']) && trim($attributes['organization']) != '' ? $attributes['organization'] : $attributes['uname'],
                "user_id"     => $user->id,
                "create_ts"   => date('U'),
                "status"      => STATUS_ACTIVE
            ));
            $tenant->setUser($user, $tenant->addRole('owner'));
            $tenant->addRole('admin');
            $tenant->addRole('staff');
            $user->c_tenant = $tenant->id;
            $user->save();

            // Qb Account Create
            $qb = new \App\Quickbooklib();
            $qbRes = $qb->createCustomer($tenant->id);
            $tenant->qb_customer_id = $qbRes->Id;
            $tenant->save();
        }

        /*
        \Model\Subscription::create(array(
            "uuid"         => gen_uuid(),
            "tenant_id"    => $tenant->id,
            "plan_id"      => $plan->id,
            "trial_end"    => date('U') + $plan->trial_span,
            "status"       => STATUS_ACTIVE
        ))->set_tag(array(SUBSCRIPTION_TRIAL, SUBSCRIPTION_DEMO, SUBSCRIPTION_NOT_ATTACHED_TO_PG));
        */

        $access = \Model\Access::create(array(
            "user_id"       => $user->id,
            "access_type"   => ACCESS_TYPE_WEB,
            "access_key"    => $user->email,
            "access_secret" => md5($attributes['password']),
            "status"        => STATUS_ACTIVE
        ));

        if (isset($attributes['sent_mail'])) {
            \Kernel()->events('app')->trigger('account_created', array($tenant, $user));
        }

        return \Model\Session::register($access);
    }
}
