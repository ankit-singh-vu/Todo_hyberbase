<?php
namespace Module\Admin\Helper;

/**
 * Asset Helper
 * Functions useful for building HTML forms with less code
 * 
 * @package system
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @link http://systemframework.com/
 */
class Navigation extends \System\View\Helper\Navigation
{
    public function render($template, array $params = array())
    {
        $navigation = $this->load($params);
        foreach($navigation as $nkey => $ndata) {
            if(!isset($ndata['ajax_load'])) {
                $navigation[$nkey]['ajax_load'] = true;
            }
            if(isset($ndata['path'])) {
                if(stripos($ndata['path'], '/admin_') !== false && $navigation[$nkey]['ajax_load']==true) {
                    $navigation[$nkey]['path'] = str_replace('/admin_', '/', $navigation[$nkey]['path']);
                }
            }
        }
        include $template;
    }
}