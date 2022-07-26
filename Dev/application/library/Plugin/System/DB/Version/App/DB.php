<?php
/**
 * @package ActiveRecord
 */
namespace App;

final class DB
{
    protected $table = null;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function addColumn($field, $params)
    {

    }

    public function rmColumn($field, $params)
    {

    }

    public function editColumn($field, $params)
    {

    }

    public function exec($callback=null)
    {

    }



};
?>