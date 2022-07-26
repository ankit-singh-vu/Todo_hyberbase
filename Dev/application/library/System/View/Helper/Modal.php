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
class Modal extends HelperAbstract
{
   public function header()
   {
       return '<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" style="font-weight:bold;color:#999;">'.$GLOBALS['__EXT']['PAGE_HEADING'].'</h4>
            </div>
            <div class="modal-body">';
   }

    public function footer($button='')
    {
        return '</div>
            <div class="modal-footer">
                '.$button.'
            </div>';
    }

}