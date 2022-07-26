<?php
/**
 * DB ActiveRecord Plugin.
 * 
 * @package    Plugin
 * @subpackage DB/ActiveRecord
 * @author     Dyutiman Chakraborty <dc@mclogics.com>
 * @copyright  (c) 2014 - 2015, Sky
 * @license    https://licenses.domain.com/psl-1.0.txt Proprietary Service Licence ver. 1.0
 */

namespace Plugin\System\DB;

use ActiveRecord;
use RuntimeException;

/**
 *  DB Plugin
 *
 *  Plugin Wrapper for DB ActiveRecord.
 */
class Plugin
{
    /**
     * Class Constructor.
     * 
     * @param \System\Kernel $kernel Instance of the Kernel Object.
     */
    public function __construct(\System\Kernel $kernel)
    {
        $this->loadActiveRecordLibrary();
        $kernel->loader()->registerNamespace('ActiveRecord', __DIR__);
        $kernel->loader()->registerNamespace('Model', $kernel->config('app.path.root'));
        ActiveRecord\Config::initialize(
            function ($cfg) use ($kernel) {
                $cfg->set_connections($kernel->config('app.plugin.system.db.connections'));
                $cfg->set_default_connection($kernel->config('app.plugin.system.db.default_connection'));
            }
        );
    }//end __construct()

    /**
     * Leads the ActiveRecord Library.
     * 
     * @return boolean
     */
    public function loadActiveRecordLibrary()
    {
        include __DIR__ . '/ActiveRecord/Singleton.php';
        include __DIR__ . '/ActiveRecord/Config.php';
        include __DIR__ . '/ActiveRecord/Utils.php';
        include __DIR__ . '/ActiveRecord/DateTime.php';
        //include __DIR__ . '/ActiveRecord/Model.php';
        include __DIR__ . '/ActiveRecord/Table.php';
        include __DIR__ . '/ActiveRecord/ConnectionManager.php';
        include __DIR__ . '/ActiveRecord/Connection.php';
        include __DIR__ . '/ActiveRecord/SQLBuilder.php';
        include __DIR__ . '/ActiveRecord/Reflections.php';
        include __DIR__ . '/ActiveRecord/Inflector.php';
        include __DIR__ . '/ActiveRecord/CallBack.php';
        include __DIR__ . '/ActiveRecord/Exceptions.php';
        return true;
    }//end loadActiveRecordLibrary()
}//end class
