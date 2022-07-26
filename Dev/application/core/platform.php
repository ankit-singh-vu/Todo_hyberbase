<?php

$system = array();

// Directories.
$system['dir']['root']   = '/';
$system['dir']['config'] = $system['dir']['root'];
$system['dir']['lib']    = $system['dir']['root'] . 'library';

// Full root paths.
$system['path']['root']    = dirname(__DIR__);
if (DEPLOYMENT_MODE == SAAS || DEPLOYMENT_MODE == CONTAINER) {
    $system['path']['config']  = dirname(__DIR__) . '/resources/' . strtolower(APP_DOMAIN) ;
} else {
    $system['path']['config']  = dirname(__DIR__) . '/resources';
}
$system['path']['lib']     = $system['path']['root'] . $system['dir']['lib'];

return array('system' => $system);
