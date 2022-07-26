<?php

namespace Module\Api;

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
        $this->setPointers('profile_api');

        $accounts = \Model\Access::find_all_by_user_id_and_access_type_and_tenant($this->user->id, ACCESS_TYPE_API, $this->access->tenant);
        $this->view->api_accounts = $accounts;
        //___debug($accounts);
        return $this->view;
    }

    public function deleteAction(System\Request $request)
    {
        $this->setPointers('profile_api');
        $account = \Model\Access::find($request->param('item'));
        if($account->user_id != $this->user->id) {
            return array(
                'error' => 'permission_denied'
            );
        }
        $account->delete();
        return array(
            'message'   => 'api_account_deleted'
        );
    }

    public function statusAction(System\Request $request)
    {
        $this->setPointers('profile_api');
        $account = \Model\Access::find($request->param('item'));
        if($account->user_id != $this->user->id) {
            return array(
                'error' => 'permission_denied'
            );
        }
        $account->status = $request->get('a');
        $account->save();
        return array(
            'message'   => 'api_account_status_updated'
        );
    }


}




















