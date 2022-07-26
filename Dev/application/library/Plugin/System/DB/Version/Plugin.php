<?php

/**
 * @package     :HyperBase
 * @subpackage  :System
 * @version     :7.0
 * @author      :Dyutiman Chakraborty <dc@mclogics.com>
 */

namespace Plugin\System\DB\Version;
use System, RuntimeException;

/**
 * DB/Version Plugin
 *
 * Takes care of DB schema migration and manages the DB schema from
 * parameters and migrations defined in the respective Models.
 */
class Plugin
{
    /**
     * @var
     */
    protected $kernel;

    /**
     * @param bool $table
     * @return \ActiveRecord\Model|bool|string
     */
    public function loadRegistry($table=false)
    {
        if($table==false) {
            $table="__@#application";
        }
        $tableDB = \Model\SystemDbVersion::find_by_table_name($table);
        if($tableDB instanceof \ActiveRecord\Model) {
            return $tableDB;
        }
        return \Model\SystemDbVersion::create(array(
            'table_name'    => $table,
            'version'       => 0
        ));
    }



    protected function setupDB()
    {
        foreach(\Kernel()->loader()->getNamespacePaths('Model') as $mpath) {
            if(file_exists($mpath . '/Model')) {
                foreach (scandir($mpath . '/Model') as $modelFile) {
                    //echo $modelFile."\n";
                    if (!in_array($modelFile, array('.', '..', '.DS_Store', 'README.md'))) {
                        if (stripos($modelFile, 'Abstract') === false
                            && stripos($modelFile, '.php') !== false) {
                            $modelClass = '\\Model\\' . str_replace('.php', '', $modelFile);
                            if($modelClass != '\\Model\\SystemDbVersion') {
                                echo 'Creating table for '.$modelClass.", if not present...\n" ;
                                $modelClass::build_table_from_schema(true);
                            }
                        }
                    }
                }
            }
        }
        return true;
    }

    protected function createDB()
    {
        $connection = \Model\SystemDbVersion::get_connection_info();
        $conn       = mysqli_connect(
            $connection['dbhost'], $connection['dbuser'], $connection['dbpass']
        );
        if(!$conn) {
            throw new \Exception('Not connected : ' . mysqli_error($conn));
        }
        $db_selected = mysqli_select_db($conn, $connection['dbname']);
        if (!$db_selected) {
            if (!mysqli_query($conn, 'CREATE DATABASE '. $connection['dbname'])) {
                throw new \Exception(mysqli_error($conn));
            }
        }
        \Model\SystemDbVersion::build_table_from_schema();
        return true;
    }

    /**
     * Initialize plguin
     */
    public function __construct(System\Kernel $kernel)
    {
        $kernel->loader()->registerNamespace('Model', __DIR__);
        $kernel->loader()->registerNamespace('App', __DIR__);
        $kernel->addMethod('loadDBVersionRegistry', function($table=false) {
            return $this->loadRegistry($table);
        });

        $kernel->addMethod('getAppVersion', function() use($kernel) {
            /*
            $version = exec('env | grep WPFOREVER_APP_VERSION');
            if($version) {
                return str_replace('WPFOREVER_APP_VERSION=', '', $version);
            }
            $gv = exec('cd '.$kernel->config('system.path.root').' && git rev-list --count HEAD');
            $main_version = $kernel->config('app.version');
            return 'dev-'.$main_version.'.'.$gv;
            */
            return getenv('STACK_VERSION');
        });

        $kernel->events('system')->bind('install', 'system_plugin_db_version', function() use($kernel)
        {
            $this->createDB();
            $deployed = $this->loadRegistry();
            $deployed->version(date('U'));
            $this->setupDB();
            return true;
        });

        $kernel->events('system')->bind('post_install', 'system_plugin_db_version', function() use($kernel)
        {
            $datapath = $kernel->config('system.path.config').'/data';
            if(file_exists($datapath)) {
                foreach(scandir($datapath) as $dbfile) {
                    echo "Processing " . $dbfile . "...\n";
                    if(!in_array($dbfile, array('.', '..'))) {
                        $sdpath = $datapath . '/' .$dbfile;
                        include $sdpath;
                    }
                }
            }
            return true;
        });

    }

}