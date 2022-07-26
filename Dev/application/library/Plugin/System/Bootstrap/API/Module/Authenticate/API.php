<?php

namespace Module\Authenticate;

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
    protected $authentication = false;

    public function indexAction(System\Request $request)
    {
        return array(
            'error' => 'expected_post_request'
        );
    }


    public function postMethod(System\Request $request)
    {
        //___debug($_SERVER);
    }
}




















