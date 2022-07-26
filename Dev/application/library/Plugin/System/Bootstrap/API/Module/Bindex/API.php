<?php

namespace Module\Bindex;

use App,
    System,
    Model;


/**
 * Index Module
 *
 * Extends from base Application controller so custom functionality can be added easily
 * lib/App/Module/ControllerAbstract
 */
class API extends App\Module\ApiAbstract
{
    public function indexAction(System\Request $request)
    {
        //___debug($_SERVER);
        return array(
            'message' => 'Welcome to '. $request->env('BRAND_NAME', 'WPSHQ-Local') . " API version " . $request->env('STACK_VERSION', '0.01')
        );
    }
}




















