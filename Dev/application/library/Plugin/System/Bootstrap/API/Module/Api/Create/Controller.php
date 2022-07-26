<?php

namespace Module\Api\Create;

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
    public function postMethod(System\Request $request)
    {
        //sleep(10);
        $name = $request->post('name');
        if($this->validateUniqueApiName($name) == false) {
            return array(
                'error' => 'api_name_not_unique'
            );
        }
        if($this->hasReachedMax() >= $this->kernel->config('app.plugin.system.bootstrap.api.max_api_account_per_user_per_tenant', 10)) {
            return array(
                'error' => 'max_api_account_limit_reached'
            );
        }
        while(true) {
            $uuid = gen_uuid();
            if(\Model\Access::count_by_access_key($uuid) == 0) {
                break;
            }
        }
        \Model\Access::create(array(
            'user_id'       => $this->user->id,
            'access_type'   => ACCESS_TYPE_API,
            'tenant'        => $this->access->tenant,
            'access_key'    => $uuid,
            'access_secret' => md5($uuid . date('U')) . md5(date('U')),
            'status'        => STATUS_ACTIVE,
            'last_access'   => 0
        ))->set_variable('name', $name);
        return array(
            'message'   => 'api_account_created'
        );
    }

    protected function hasReachedMax()
    {
        return \Model\Access::count_by_user_id_and_access_type_and_tenant($this->user->id, ACCESS_TYPE_API, $this->access->tenant);

    }

    protected function validateUniqueApiName($name)
    {
        $apiAccess = \Model\Access::find_all_by_user_id_and_access_type_and_tenant($this->user->id, ACCESS_TYPE_API, $this->access->tenant);
        foreach($apiAccess as $api) {
            if($api->get_variable('name') == $name) {
                return false;
            }
        }
        return true;
    }
}




















