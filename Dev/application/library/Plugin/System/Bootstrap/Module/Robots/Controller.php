<?php
namespace Module\Robots;
use System, System\Exception;

/**
 * Assets Module
 * 
 */
class Controller extends System\Module\ControllerAbstract
{
    /**
     * Index
     * @method GET
     */
    public function indexAction(System\Request $request)
    {
        echo "User-agent: * \n";
        echo "Disallow:";
        exit; 
    }
}