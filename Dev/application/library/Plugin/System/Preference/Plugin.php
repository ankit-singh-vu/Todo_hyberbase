<?php

/**
 * @package     :HyperBase
 * @subpackage  :System
 * @version     :7.0
 * @author      :Dyutiman Chakraborty <dc@mclogics.com>
 */

namespace Plugin\System\Preference;
use System, RuntimeException;

/**
 * Preference Plugin
 *
 * Allows to maintain user or tenant preference persistently
 */
class Plugin
{
    protected $kernel;

    /**
     * Initialize plguin
     */
    public function __construct(System\Kernel $kernel)
    {
        $kernel->loader()->registerNamespace('Model', __DIR__);

        $kernel->addMethod('set_preference', function($model, $key, $value) {
            if($model == 'system') {
                $model_name = 'system';
                $model_id = 1;
            } else {
                $model_name = $model::$table_name;
                $model_id = $model->id;
            }
            $object = \Model\Preference::find_or_create_by_key_name_and_object_name_and_object_id($key, $model_name, $model_id);
            if(is_array($value)) {
                $object->data_type = 'json';
                $value = json_encode($value);
            } else {
                $object->data_type = 'text';
            }
            $object->key_value = $value;
            $object->save();
            return true;
        });

        $kernel->addMethod('get_preference', function($model, $key, $default=false) {

            if($model == 'system') {
                $model_name = 'system';
                $model_id = 1;
            } else {
                $model_name = $model::$table_name;
                $model_id = $model->id;
            }
            $object = \Model\Preference::find_by_key_name_and_object_name_and_object_id($key, $model_name, $model_id);
            if($object instanceof \Model\Preference) {
                if($object->data_type == 'json') {
                    return json_decode($object->key_value, true);
                } else {
                    return $object->key_value;
                }
            }
            return $default;
        });

        return true;
    }

}