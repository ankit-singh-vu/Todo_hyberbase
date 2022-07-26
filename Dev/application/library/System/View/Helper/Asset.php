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
class Asset extends HelperAbstract
{
    /**
     * Stylesheet <link> tag input
     */
    public function stylesheet($file, array $options = array(), $if=false)
    {
        if(false === strpos($file, '//')) {
            $file = '/' . $file;
        }
        $tag = '';
        if($if != false) { $tag.='<!--[if '.$if.']>'; }
        $tag.= '<link type="text/css" href="' . $file . '" rel="stylesheet"' . $this->listExtra($options) . ' />';
        if($if != false) { $tag.='<![endif]-->'; }
        return $tag;
    }
    
    
    /**
     * Javascript <script> tag input
     */
    public function script($file, array $options = array())
    {
        //___debug($this->kernel->config('url.assets'));
        if(false === strpos($file, '//')) {
            $file = '/' . $file;
        }
        $tag = '<script type="text/javascript" src="' . $file . '"' . $this->listExtra($options) . '></script>';
        return $tag;
    }
    
    
    /**
     * Image <img> tag input
     *
     * @param string $file
     * @param array $options Array of Key => Value attributes for image tag
     */
    public function image($file, array $options = array())
    {
        if(false === strpos($file, '//')) {
            $file = '/' . $file;
        }
        $tag = '<img src="' . $file . '"' . $this->listExtra($options) . ' />';
        return $tag;
    }
    
    
    /**
     * List extra attributes passed in
     */
    protected function listExtra(array $options)
    {
        $output = '';
        foreach($options as $key => $val) {
            if(!empty($val)) {
                if(is_array($val)) {
                    $output .= ' ' . $key . '=\'' . json_encode($val) . '\'';
                } else {
                    $output .= ' ' . $key . '="' . $val . '"';
                }
            }
        }
        return $output;
    }
}