<?php
namespace App;

abstract class Plugin
{
    protected \System\Kernel $kernel;

    public function __construct(\System\Kernel $kernel)
    {
        $this->kernel = $kernel;

        $kernel->events('form')->addFilter('validate_data', get_called_class(), function($params) {
            $validation = $this->validate_data($params['validation'], $params['data']);
            if($validation && is_array($validation)) {
                $params['errors'][$params['field_key']] = $validation[0];
            }
            return $params;
        });

        if(method_exists($this, 'init')) {
            $this->init();
        }
    }

    protected function validate_data($validation_type, $data)
    {
        return true;
    }

}