<?php

namespace Job;

class DeployServer extends \App\JobAbstract
{
    protected $steps = array(
        'building_instance'             => array(),
        'configuring_instance'          => array(),
        'installing_platform'           => array(),
        'adding_drives'                 => array(),
        'installing_services'           => array(),
        'validating_setup'              => array()
    );

    protected function run()
    {
        // TODO: Implement run() method.
    }
}