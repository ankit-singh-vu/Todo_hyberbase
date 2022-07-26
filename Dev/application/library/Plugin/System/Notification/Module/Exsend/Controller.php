<?php

namespace Module\Exsend;

class Controller extends \System\Module\ControllerAbstract
{
    public function processAction(\System\Request $request)
    {
        if(!$request->isCli()) {
            return false;
        }
        \Model\Notification::find($request->param('item'))->push();
        return array();
    }
}




















