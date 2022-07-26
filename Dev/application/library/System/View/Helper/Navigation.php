<?php
namespace System\View\Helper;

/**
 * Asset Helper
 * Functions useful for building HTML forms with less code
 * 
 * @package system
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @link http://systemframework.com/
 */
class Navigation extends HelperAbstract
{

   protected $loaded_navigations = array();

   public function load(array $params=array())
   {
       $nkey = md5(json_encode($params));
       if(!isset($this->loaded_navigations[$nkey])) {
           $navData = $this->kernel->events('ui')->filter('load_navigation', array(
               'params' => $params,
               'data' => array()
           ));
           $navigation = $navData['data'];
           foreach ($navigation as $segment => $navblock) {
               if(isset($GLOBALS['__EXT']['POINTERS'])
                   && in_array($segment, $GLOBALS['__EXT']['POINTERS'])) {
                       $navigation[$segment]['is_active'] = true;
               }

               if(isset($params['variables'])) {
                   foreach($params['variables'] as $key => $value) {
                       $navigation[$segment]['path'] = str_replace(':'.$key, $value, $navigation[$segment]['path']);
                   }
               }

               if (isset($navigation[$segment]['child_menu'])) {
                   foreach($navigation[$segment]['child_menu'] as $cseg =>$cnav) {
                       if(isset($GLOBALS['__EXT']['POINTERS'])
                           && in_array($cseg, $GLOBALS['__EXT']['POINTERS'])) {
                               $navigation[$segment]['child_menu'][$cseg]['is_active'] = true;
                       }
                   }
                   uasort($navigation[$segment]['child_menu'], function($a, $b) {
                       if($a['weight'] == $b['weight']) {
                           return 0;
                       } else {
                           return ($a['weight'] < $b['weight']) ? -1 : +1;
                       }
                   });
               }
           }
           uasort($navigation, function($a, $b) {
               if($a['weight'] == $b['weight']) {
                   return 0;
               } else {
                   return ($a['weight'] < $b['weight']) ? -1 : +1;
               }
           });

           $this->loaded_navigations[$nkey] = $navigation;
       }
       return $this->loaded_navigations[$nkey];
   }

   public function hasChild($params=array())
   {
       if(isset($params['_type'])) {
           if(isset($GLOBALS['__EXT']['NAGIVATION_CHILD'][$params['_type']]['HIDE'])) {
               return false;
           }
       }
       $has = false;
       foreach($this->load($params) as $key => $nav) {
           if( isset($nav['is_active']) && $nav['is_active'] == true
                && isset($nav['child_menu'])) {
                $has = true;
               break;
           }

       }
       return $has;
   }

   public function isHeaderTabEnabled()
   {
       if(!isset($GLOBALS['__EXT']['HEADER_TAB']['ENABLED'])
        || $GLOBALS['__EXT']['HEADER_TAB']['ENABLED']==false) {
           return false;
       }
       //___debug('TRUE');
       return true;
   }
   
   public function render($template, array $params=array())
   {
       $navigation = $this->load($params);
       include $template;
   }
   
   public function pagination($params, $url, $object='records', $join="?")
   {
       if($params['current_page'] > 5) {
           $loop_start = $params['current_page'] - 5;
           $loop_end = $params['current_page'] + 5;
       } else {
           $loop_start = 0;
           $loop_end = 10;
       }
       $loop = $loop_start;
       
       if($params['total_pages'] > 1) {
       
            $responce['nav'] = '<ul>';
            if($params['previous'] !== false) {
                $responce['nav'].= '<li><a href="'.$url.$join.'offset='.$params['previous'].'">&laquo;</a></li>';
            } else {
                //$responce['nav'].= '<li class="disabled"><a href="'.$url.'?offset=0">&laquo;</a></li>';
            }
            while($loop != $loop_end) {
                if($loop >= $params['total_pages']) {
                    break;
                }
                if(($loop+1) == $params['current_page']) {
                     $responce['nav'].= '<li class="disabled"><a href="'.$url.$join.'offset='.($loop * $params['record_per_page']).'">'.($loop+1).'</a></li>';
                } else {
                     $responce['nav'].= '<li><a href="'.$url.$join.'offset='.($loop * $params['record_per_page']).'">'.($loop+1).'</a></li>';
                }
                $loop++;
            }
            if($params['next'] !== false) {
                $responce['nav'].= '<li><a href="'.$url.$join.'offset='.$params['next'].'">&raquo;</a></li>';
            } else {
                //$responce['nav'].= '<li class="disabled" ><a href="'.$url.'?offset='.$params['offset'].'">&raquo;</a></li>';
            }
            $responce['nav'].= '</ul>';
       
       } else {
           $responce['nav'] = '';
       }
       
       $responce['showing'] = 'Showing '.$params['start'].' to '.$params['end'].' out of '.$params['total_rows'].' ' . $object . '';
       return $responce;
       
   }
}