<?php

namespace Module\Form;

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
        return $this->view->helper('form')->process_submission($_POST);
    }

    public function getchildAction(System\Request $request)
    {
        $form_name = $request->get('name');
        $field = $request->get('field');
        $child_key = $request->get('ckey');
        $this->view->form = $this->view->helper('form')->load_child($form_name, $field, $child_key);
        return $this->view;
    }
}




















