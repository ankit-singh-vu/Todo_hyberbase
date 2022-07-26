<?php

namespace System;

abstract class Manifest
{
    protected $kernel;
    
    abstract public function navigation($params);    
    abstract public function routes(\System\Router $router);
    
    public function getRouteWeight()
    {
        return 0;
    }
}