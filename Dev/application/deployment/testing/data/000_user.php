<?php
try {
    \Model\User::create(array(
        "id"              => 1,
        "first_name"      => "Admin",
        "last_name"       => "Developer",
        "email"           => "admin@wpforever.com",
        "password"        => md5('wp562pri874'),
        "status"          => 1,
        "user_type"       => USER_TYPE_ADMIN
    ));
    \Model\User::create(array(
        "id"              => 2,
        "first_name"      => "Client1",
        "last_name"       => "Developer",
        "email"           => "client1@wpforever.com",
        "password"        => md5('wp562pri875'),
        "status"          => 1,
        "user_type"       => USER_TYPE_CLIENT,
        "plan"            => "basic"
    ));
    \Model\User::create(array(
        "id"              => 3,
        "first_name"      => "Client2",
        "last_name"       => "Developer",
        "email"           => "client2@wpforever.com",
        "password"        => md5('wp562pri876'),
        "status"          => 1,
        "user_type"       => USER_TYPE_CLIENT,
        "plan"            => "premium"
    ));
} catch(\Exception $e) { }