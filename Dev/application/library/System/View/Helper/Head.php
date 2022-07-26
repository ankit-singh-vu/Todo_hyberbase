<?php
namespace System\View\Helper;

/**
 * HTML Head Helper
 * Collects HTML elements that need to be added within the <head></head> tags of an HTML document
 * Used so any view can add items to the final HTML layout that gets produced
 * 
 * @package system
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @link http://systemframework.com/
 */
class Head extends HelperAbstract
{
    protected $_assetHelper;
    protected $_title;
    protected $_styles = array();
    protected $_styleUrls = array();
    protected $_scripts = array();
    protected $_scriptUrls = array();
    protected $_contentPrepend = array();
    protected $_contentAppend = array();
    
    
    /**
     * Load required Asset helper
     */
    public function init()
    {
        $this->_assetHelper = $this->_view->helper('Asset');
    }


    /**
     *	Stylesheet <link> tag input
     */
    public function stylesheet()
    {
        $this->_styles[] = call_user_func_array(array($this->_assetHelper, __FUNCTION__), func_get_args());
        $this->_styleUrls = array_merge($this->_styleUrls, func_get_args());
    }

    public function getStylesheetUrls()
    {
        return $this->_styleUrls;
    }
    
    
    /**
     *	Javascript <script> tag input
     */
    public function script()
    {
        $this->_scripts[] = call_user_func_array(array($this->_assetHelper, __FUNCTION__), func_get_args());
        $args = func_get_args();
        if(isset($args[0]))
            $this->_scriptUrls[] = $args[0];
    }

    public function getScriptUrls()
    {
        return $this->_scriptUrls;
    }


    /**
     * Get/Set title, usually to pass along to layout
     */
    public function title($title = null)
    {
        if(null === $title) {
            return $this->_title;
        } else {
            $this->_title = $title;
            return $this; // Fluent interface
        }
    }
    
    
    /**
     * Prepend content
     */
    public function prepend($content)
    {
        $this->_contentPrepend[] = $content;
    }
    
    
    /**
     * Append content
     */
    public function append($content)
    {
        $this->_contentAppend[] = $content;
    }
    
    
    /**
     * Return HTML content string
     *
     * @return string
     */
    public function content()
    {
        $content = "";
        
        // Numeric keys with array_merge just appends items in order
        $contentItems = array_merge(
            $this->_contentPrepend,
            $this->_styles,
            $this->_scripts,
            $this->_contentAppend
            );
        
        // Format content items
        foreach($contentItems as $item) {
            $content .= "\t" . (string) $item . "\n";
        }
        
        return $content;
    }
    
    
    /**
     * Return HTML content string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->content();
    }
}