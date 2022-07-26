<?php

namespace Module\Job;

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
    protected $authentication = false;
    //protected $register_callbacks = false;
    //protected $skip_authentication = array('initexec');

    public function runAction(System\Request $request)
    {
        if(!$request->isCli()) {
            return false;
        }
        $job = Model\Job::find($request->param('item'));
        $jobClass = '\\Job\\' . $job->job;
        $jobObject = new $jobClass(json_decode($job->data, true));
        $jobObject->set_job_model($job);
        try {
            $jobObject->exec(JOB_EXEC_TYPE_RUN);
            $job->complete();
        } catch (\Exception $e) {
            $jobObject->exec(JOB_EXEC_TYPE_ROLLBACK);
            $job->fail($e->getMessage());
        }
        return array();
    }

}




















