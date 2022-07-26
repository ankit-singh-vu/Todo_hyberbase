<?php

namespace Model;
use ActiveRecord, System;

abstract class AttributeAbstract extends \ActiveRecord\Model
{
    /**
     * @param $tags
     * @return $this
     */
    public function set_tag($tags)
    {
        if(!is_array($tags)) {
            $tags = array($tags);
        }
        foreach($tags as $tag) {
            \Model\Tag::find_or_create_by_table_name_and_record_id_and_tag(static::$table_name, $this->id, $tag);
        }
        return $this;
    }

    /**
     * @param $tags
     * @return AttributeAbstract
     */
    public function set_tags($tags)
    {
        return $this->set_tag($tags);
    }

    /**
     * @param $tags
     * @return $this
     * @throws ActiveRecord\ActiveRecordException
     */
    public function rm_tag($tags)
    {
        if(!is_array($tags)) {
            $tags = array($tags);
        }
        foreach($tags as $tag) {
            $tagObject = \Model\Tag::find_by_table_name_and_record_id_and_tag(static::$table_name, $this->id, $tag);
            if($tagObject instanceof \Model\Tag) {
                $tagObject->delete();
            }
        }
        return $this;
    }

    /**
     * @param $tags
     * @return AttributeAbstract
     * @throws ActiveRecord\ActiveRecordException
     */
    public function rm_tags($tags)
    {
        return $this->rm_tag($tags);
    }

    /**
     * @param $tag
     * @return bool
     */
    public function is_tag_present($tag)
    {
        $tagObject = \Model\Tag::find_by_table_name_and_record_id_and_tag(static::$table_name, $this->id, $tag);
        if($tagObject instanceof \Model\Tag) {
            return true;
        }
        return false;
    }

    /**
     * @param $name
     * @param $value
     */
    public function set_variable($name, $value)
    {
        $variableObject = \Model\Variable::find_or_create_by_table_name_and_record_id_and_name(static::$table_name, $this->id, $name);
        if(is_array($value)) {
            $value = '__@@_HB_JSON_DATA=' . json_encode($value);
        }
        $variableObject->data = $value;
        $variableObject->save();
    }

    /**
     * @param $name
     * @return bool|mixed
     */
    public function get_variable($name, $default=null)
    {
        $variableObject = \Model\Variable::find_by_table_name_and_record_id_and_name(static::$table_name, $this->id, $name);
        if($variableObject instanceof \Model\Variable) {
            if(stripos($variableObject->data, '__@@_HB_JSON_DATA=') !== false) {
                return json_decode(str_replace('__@@_HB_JSON_DATA=', '', $variableObject->data), true);
            } else {
                return $variableObject->data;
            }
        }
        return $default;
    }

    /**
     * @param $name
     * @return $this
     * @throws ActiveRecord\ActiveRecordException
     */
    public function rm_variable($name)
    {
        $variableObject = \Model\Variable::find_by_table_name_and_record_id_and_name(static::$table_name, $this->id, $name);
        if($variableObject instanceof \Model\Variable) {
            $variableObject->delete();
        }
        return $this;
    }

    public static function generate_uuid()
    {
        while(true) {
            $uuid =str_replace('-', '', gen_uuid()).md5(date('U'));
            if(self::count_by_uuid($uuid)==0) {
                break;
            }
        }
        return $uuid;
    }

}