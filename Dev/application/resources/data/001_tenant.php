<?php

try {

    /*
    \Kernel()->events('system')->bind('db_up', 'model_tenant', function ($version) {

        $table = new \App\DB(\Model\User::$table_name);
        $table->addColumn('date', array());
        $table->exec(function($status) {

        });

    });

    \Kernel()->events('system')->bind('db_down', 'model_tenant', function ($version) {

        $table = new \App\DB(\Model\User::$table_name);
        $table->rmColumn('date');
        $table->exec(function($status) {

        });

    });
    */

} catch(\Exception $e) {

}
