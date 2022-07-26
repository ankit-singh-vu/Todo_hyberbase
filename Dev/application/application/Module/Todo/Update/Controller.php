<?php

namespace Module\Todo\Update;

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
// class Controller extends \Module\Admin\Controller
{
    protected $authentication=false;
    public function postMethod(System\Request $request)
    {
        $data = $request->jsonPost();
        $todo = \Model\Todo::find($data['id']);
        $todo->description = $data['description'];
        $todo->save();
    }
}
