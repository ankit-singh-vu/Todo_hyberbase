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
class UnderConstruction extends HelperAbstract
{
   public function set($label='Under Construction', $message='')
   {
       return '<div style="font-weight:bold;width:auto;text-align:center;margin-top:2%"><h3>'. $label .'</H3></div>'
           .  '';
   }

}