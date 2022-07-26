<?php

namespace Module\Todo;

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
    protected $authentication=false;
    public function indexAction(System\Request $request)
    {
        $this->disableLayout();
        $todos = \Model\Todo::find('all');
        $this->view->todos = $todos;
        return $this->view;
    }

    public function postMethod(System\Request $request)
    {
        $this->disableLayout();
        $data = $request->jsonPost();
        // Add record to db
        $todo = \Model\Todo::create(array(
            'description' => $data['description'],
        ));  
    }

    public function reloadAction(System\Request $request)
    {
        $this->disableLayout();
        $todos = \Model\Todo::find('all');
        // \Kernel()->redirect('/todo');//page redirect
        // ___debug($todos);
        $this->view->todos= $todos;
        return $this->view;

    }

    // public function deleteAction(System\Request $request)
    // {
    //     $this->disableLayout();
    //     echo 'deleteAction';
    //     // $todos = \Model\Todo::find('all');
    //     // \Kernel()->redirect('/todo');//page redirect
    //     // ___debug($todos);
    //     // $this->view->todos= $todos;
    //     // return $this->view;

    // }

    public function deleteAction(System\Request $request)
    {
        $todo = \Model\Todo::find($request->param('item'));
        $todo->delete();
    }    
}
