<?php
$session = \Model\Session::load_user();
if(!$session) {
    \Kernel()->redirect('/system/logout');
}
$tenant = $session->user->get_current_web_tenant();

if(defined('SYSTEM_USER_TYPE_STAFF') && $session->user->is_tag_present(SYSTEM_USER_TYPE_STAFF) || (defined('SYSTEM_USER_TYPE_ADMIN') && $session->user->is_tag_present(SYSTEM_USER_TYPE_ADMIN))) {
    include_once __DIR__ . '/app.admin.html.php';
} else {
    include_once __DIR__ . '/app.client.html.php';
}