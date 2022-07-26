<?php

namespace Module\Admin\Package;

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
        return $this->view;
    }

    public function syncpaddleAction(System\Request $request)
    {
        //sleep(3);

        $api = new \Breadhead\Paddle\Api(getenv('PADDLE_VENDOR_ID'), getenv('PADDLE_API_KEY'), 60);
        $plans = $api->getPlans(100);

        //___debug($plans);

        foreach($plans as $plan) {
            $p = \Model\Plan::find_or_create_by_pg_id($plan['id']);
            if($p->uuid=='') { $p->uuid = gen_uuid(); }
            if($p->name =='') { $p->name = $plan['name']; }
            $p->cost = $plan['recurring_price']['USD']*100;
            $p->currency = 'USD';
            $p->pg_data = json_encode($plan);

            if($p->plan_type == 0) { $p->plan_type = PLAN_TYPE_ADDON; }
            $p->pg_name = $plan['name'];
            if($p->private == 0) { $p->private = PLAN_ACCESS_PRIVATE; }

            $p->billing_type = $plan['billing_type'];
            $p->billing_period = $plan['billing_period'];

            $p->status = STATUS_ACTIVE;
            $p->save();
        }

        return array();
    }

    public function upaccessAction(System\Request $request)
    {
        //sleep(3);

        $plan = \Model\Plan::find($request->param('item'));
        $plan->private = $request->get('a');
        $plan->save();

        return array();
    }

    public function viewAction(System\Request $request)
    {
        $plan = \Model\Plan::find($request->param('item'));
        $this->view->plan = $plan;
        //__debug($plan);
        return $this->view;
    }

    public function postMethod(System\Request $request)
    {

    }

}




















