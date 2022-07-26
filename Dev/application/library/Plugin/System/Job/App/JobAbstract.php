<?php

namespace App;

abstract class JobAbstract
{
    protected $steps = array();
    protected $job = null;
    protected $parameters = array();

    abstract protected function run();

    public function __construct(array $parameters = array())
    {
        $this->parameters = $parameters;
    }

    public function set_job_model(\Model\Job $job)
    {
        $this->job = $job;
    }

    public function exec($type=JOB_EXEC_TYPE_RUN)
    {
        if($type == JOB_EXEC_TYPE_ROLLBACK) {
            if(method_exists($this, 'rollback')) {
                $this->rollback();
            }
            return false;
        }
        return $this->run();
    }
}