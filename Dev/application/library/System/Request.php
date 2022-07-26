<?php
namespace System;

/**
 * Request Class
 *
 * Wraps around request variables for now; Will also wrap XML, JSON, and CLI variables in the future
 *
 * @package system
 * @todo Handle XML, JSON, CLI, etc. requests also (maybe)
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @link http://systemframework.com/
 */
class Request
{
    // Request parameters
    protected $_params = array();


    protected $_putParams = false; 
    
    /**
     * Ensure magic quotes are not mucking up request data
     */
    public function __construct()
    {
        // Die magic_quotes, just die...
        $stripslashes_gpc = function(&$value, $key) {
            $value = stripslashes($value);
        };
        array_walk_recursive($_GET, $stripslashes_gpc);
        array_walk_recursive($_POST, $stripslashes_gpc);
        array_walk_recursive($_COOKIE, $stripslashes_gpc);
        array_walk_recursive($_REQUEST, $stripslashes_gpc);

    }
    
    
    /**
    * Access values contained in the superglobals as public members
    * Order of precedence: 1. GET, 2. POST, 3. COOKIE, 4. SERVER, 5. ENV
    *
    * @see http://msdn.microsoft.com/en-us/library/system.web.httprequest.item.aspx
    * @param string $key
    * @return mixed
    */
    public function get($key, $default = null)
    {
        switch (true) {
            case isset($this->_params[$key]):
                $value = $this->_params[$key];
                break;
            
            case isset($_GET[$key]):
                $value = $_GET[$key];
                break;
            
            case isset($_POST[$key]):
                $value = $_POST[$key];
                break;
            
            case isset($_COOKIE[$key]):
                $value = $_COOKIE[$key];
                break;
            
            case isset($_SERVER[$key]):
                $value = $_SERVER[$key];
                break;
                
            case isset($_ENV[$key]):
                $value = $_ENV[$key];
                break;
                
            default:
                $value = $default;
        }

        // Key not found, default is being used
        if($value === $default) {
            // Check for dot-separator (convenience access for nested array values)
            if(strpos($key, '.') !== false) {
                // Get all dot-separated key parts
                $keyParts = explode('.', $key);

                // Remove first key because we're going to start with it
                $keyFirst = array_shift($keyParts);

                // Get root value array to begin
                $value = $this->get($keyFirst);

                // Loop over remaining key parts to see if value can be found in resulting array
                foreach($keyParts as $keyPart) {
                    if(is_array($value)) {
                        if(isset($value[$keyPart])) {
                            $value = $value[$keyPart];
                        } else {
                            $value = $default;
                        }   
                    }
                }
            }
        }
        
        return $value;
    }
    // Automagic companion function
    public function __get($key)
    {
        return $this->get($key);
    }
    
    
    
    /**
     *	Override request parameter value
     *
     *	@param string $key
     *	@param string $value
     */
    public function set($key, $value)
    {
        $this->_params[$key] = $value;
    }
    // Automagic companion function
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }
    
    
    /**
    * Check to see if a property is set
    *
    * @param string $key
    * @return boolean
    */
    public function __isset($key)
    {
        switch (true) {
            case isset($this->_params[$key]):
            return true;
            case isset($_GET[$key]):
            return true;
            case isset($_POST[$key]):
            return true;
            case isset($_COOKIE[$key]):
            return true;
            case isset($_SERVER[$key]):
            return true;
            case isset($_ENV[$key]):
            return true;
            default:
            return false;
        }
    }
    
    
    /**
    * Retrieve request parameters
    *
    * @return array Returns array of all GET, POST, and set params
    */
    public function params(array $params = array())
    {
        // Setting
        if(count($params) > 0) {
            foreach($params as $pKey => $pValue) {
                $this->set($pKey, $pValue);
            }
            
        // Getting
        } else {
            $params = array_merge($_GET, $_POST, $this->_params);
        }
        return $params;
    }
    
    public function putParam($key=false, $default=null)
    {
        if($this->isPut()==false) {
            return $default;
        }
        if($this->_putParams == false) {
            $this->_putParams = json_decode(file_get_contents("php://input", "r"), true);
        }
        if($key==false) {
            return $this->_putParams;
        }
        if(isset($this->_putParams[$key])) {
            return $this->_putParams[$key];
        }
        return $default;
    }
    
    public function putParams()
    {
        return $this->putParam();
    }


    
    /**
    * Set additional request parameters
    */
    public function setParams($params)
    {
        if($params && is_array($params)) {
            foreach($params as $pKey => $pValue) {
                $this->set($pKey, $pValue);
            }
        }
    }
    
    
    /**
    * Retrieve a member of the $params set variables
    *
    * If no $key is passed, returns the entire $params array.
    *
    * @todo How to retrieve from nested arrays
    * @param string $key
    * @param mixed $default Default value to use if key not found
    * @return mixed Returns null if key does not exist
    */
    public function param($key = null, $default = null)
    {
        if (null === $key) {
            return $this->_params;
        }
        
        return (isset($this->_params[$key])) ? $this->_params[$key] : $default;
    }
    
    
    /**
    * Retrieve a member of the $_GET superglobal
    *
    * If no $key is passed, returns the entire $_GET array.
    *
    * @todo How to retrieve from nested arrays
    * @param string $key
    * @param mixed $default Default value to use if key not found
    * @return mixed Returns null if key does not exist
    */
    public function query($key = null, $default = null)
        {
        if (null === $key) {
            // Return _GET params without routing param or other params set by system or manually on the request object
            return array_diff_key($_GET, $this->param() + array('u' => 1));
        }
        
        return (isset($_GET[$key])) ? $_GET[$key] : $default;
    }
    
    
    /**
    * Retrieve a member of the $_POST superglobal
    *
    * If no $key is passed, returns the entire $_POST array.
    *
    * @todo How to retrieve from nested arrays
    * @param string $key
    * @param mixed $default Default value to use if key not found
    * @return mixed Returns null if key does not exist
    */
    public function post($key = null, $default = null)
    {
        if (null === $key) {
            return $_POST;
        }
        
        return (isset($_POST[$key])) ? $_POST[$key] : $default;
    }

    public function jsonPost($default='{}')
    {
        $json = file_get_contents('php://input');
        if($json) {
            return json_decode($json, true);
        }
        return $default;
    }
    
    
    /**
    * Retrieve a member of the $_COOKIE superglobal
    *
    * If no $key is passed, returns the entire $_COOKIE array.
    *
    * @todo How to retrieve from nested arrays
    * @param string $key
    * @param mixed $default Default value to use if key not found
    * @return mixed Returns null if key does not exist
    */
    public function cookie($key = null, $default = null)
    {
        if (null === $key) {
            return $_COOKIE;
        }
        
        return (isset($_COOKIE[$key])) ? $_COOKIE[$key] : $default;
    }
    
    
    /**
    * Retrieve a member of the $_SERVER superglobal
    *
    * If no $key is passed, returns the entire $_SERVER array.
    *
    * @param string $key
    * @param mixed $default Default value to use if key not found
    * @return mixed Returns null if key does not exist
    */
    public function server($key = null, $default = null)
    {
        if (null === $key) {
            return $_SERVER;
        }
        
        return (isset($_SERVER[$key])) ? $_SERVER[$key] : $default;
    }
    
    
    /**
    * Retrieve a member of the $_ENV superglobal
    *
    * If no $key is passed, returns the entire $_ENV array.
    *
    * @param string $key
    * @param mixed $default Default value to use if key not found
    * @return mixed Returns null if key does not exist
    */
    public function env($key = null, $default = null)
    {
        if (null === $key) {
            return $_ENV;
        }
        
        return (isset($_ENV[$key])) ? $_ENV[$key] : $default;
    }
    
    
    /**
    * Return the value of the given HTTP header. Pass the header name as the
    * plain, HTTP-specified header name. Ex.: Ask for 'Accept' to get the
    * Accept header, 'Accept-Encoding' to get the Accept-Encoding header.
    *
    * @param string $header HTTP header name
    * @return string|false HTTP header value, or false if not found
    */
    public function header($header)
    {
        // Try to get it from the $_SERVER array first
        $temp = 'HTTP_' . strtoupper(str_replace('-', '_', $header));
        //___debug($_SERVER[$temp]);
        if (isset($_SERVER[$temp])) {
            return $_SERVER[$temp];
        }
            
        // This seems to be the only way to get the Authorization header on Apache
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (!empty($headers[$header])) {
                return $headers[$header];
            }
        }
        
        return false;
    }
    
    
    /**
    * Return the method by which the request was made
    *
    * @return string
    */
    public function method()
    {
        $sm = strtoupper($this->server('REQUEST_METHOD', 'GET'));

        // POST + '_method' override to emulate REST behavior in browsers that do not support it
        if('POST' == $sm && $this->get('_method')) {
            return strtoupper($this->get('_method'));
        }

        return $sm;
    }
    
    
    /**
     * Get a user's correct IP address
     * Retrieves IP's behind firewalls or ISP proxys like AOL
     *
     * @return string IP Address
     */
    public function ip()
    {
        $ip = FALSE;
    
        if( !empty( $_SERVER["HTTP_CLIENT_IP"] ) )
            $ip = $_SERVER["HTTP_CLIENT_IP"];
    
        if( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ){
            // Put the IP's into an array which we shall work with shortly.
            $ips = explode( ", ", $_SERVER['HTTP_X_FORWARDED_FOR'] );
            if( $ip ){
                array_unshift( $ips, $ip );
                $ip = false;
            }
    
            for( $i = 0; $i     < count($ips); $i++ ){
                if (!preg_match("/^(10|172\.16|192\.168)\./", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }
    
    
    /**
     *	Determine is incoming request is POST
     *
     *	@return boolean
     */
    public function isPost()
    {
        return ($this->method() == "POST");
    }
    
    
    /**
     *	Determine is incoming request is GET
     *
     *	@return boolean
     */
    public function isGet()
    {
        return ($this->method() == "GET");
    }
    
    
    /**
     *	Determine is incoming request is PUT
     *
     *	@return boolean
     */
    public function isPut()
    {
        return ($this->method() == "PUT");
    }
    
    
    /**
     *	Determine is incoming request is DELETE
     *
     *	@return boolean
     */
    public function isDelete()
    {
        return ($this->method() == "DELETE");
    }
    
    
    /**
     *	Determine is incoming request is HEAD
     *
     *	@return boolean
     */
    public function isHead()
    {
        return ($this->method() == "HEAD");
    }
    
    
    /**
     *	Determine is incoming request is secure HTTPS
     *
     *	@return boolean
     */
    public function isSecure()
    {
        return  (bool) ((!isset($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) != 'on') ? false : true);
    }
    
    
    /**
     * Is the request a Javascript XMLHttpRequest?
     *
     * Works with Prototype/Script.aculo.us, jQuery, YUI, Dojo, possibly others.
     *
     * @return boolean
     */
    public function isAjax()
    {
        return ($this->header('X_REQUESTED_WITH') == 'XMLHttpRequest');
    }


    /**
     * Is the request coming from a mobile device?
     *
     * Works with iPhone, Android, Windows Mobile, Windows Phone 7, Symbian, and other mobile browsers
     *
     * @return boolean
     */
    public function isMobile()
    {
        /*
        $op = strtolower($_SERVER['HTTP_X_OPERAMINI_PHONE']);
        $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
        $ac = strtolower($_SERVER['HTTP_ACCEPT']);

        return (
            strpos($ac, 'application/vnd.wap.xhtml+xml') !== false
            || strpos($ac, 'text/vnd.wap.wml') !== false
            || $op != ''
            || strpos($ua, 'iphone') !== false
            || strpos($ua, 'android') !== false
            || strpos($ua, 'iemobile') !== false 
            || strpos($ua, 'kindle') !== false
            || strpos($ua, 'sony') !== false 
            || strpos($ua, 'symbian') !== false 
            || strpos($ua, 'nokia') !== false 
            || strpos($ua, 'samsung') !== false 
            || strpos($ua, 'mobile') !== false
            || strpos($ua, 'windows ce') !== false
            || strpos($ua, 'epoc') !== false
            || strpos($ua, 'opera mini') !== false
            || strpos($ua, 'nitro') !== false
            || strpos($ua, 'j2me') !== false
            || strpos($ua, 'midp-') !== false
            || strpos($ua, 'cldc-') !== false
            || strpos($ua, 'netfront') !== false
            || strpos($ua, 'mot') !== false
            || strpos($ua, 'up.browser') !== false
            || strpos($ua, 'up.link') !== false
            || strpos($ua, 'audiovox') !== false
            || strpos($ua, 'blackberry') !== false
            || strpos($ua, 'ericsson,') !== false
            || strpos($ua, 'panasonic') !== false
            || strpos($ua, 'philips') !== false
            || strpos($ua, 'sanyo') !== false
            || strpos($ua, 'sharp') !== false
            || strpos($ua, 'sie-') !== false
            || strpos($ua, 'portalmmm') !== false
            || strpos($ua, 'blazer') !== false
            || strpos($ua, 'avantgo') !== false
            || strpos($ua, 'danger') !== false
            || strpos($ua, 'palm') !== false
            || strpos($ua, 'series60') !== false
            || strpos($ua, 'palmsource') !== false
            || strpos($ua, 'pocketpc') !== false
            || strpos($ua, 'smartphone') !== false
            || strpos($ua, 'rover') !== false
            || strpos($ua, 'ipaq') !== false
            || strpos($ua, 'au-mic,') !== false
            || strpos($ua, 'alcatel') !== false
            || strpos($ua, 'ericy') !== false
            || strpos($ua, 'up.link') !== false
            || strpos($ua, 'vodafone/') !== false
            || strpos($ua, 'wap1.') !== false
            || strpos($ua, 'wap2.') !== false
        );
        */
       $useragent=$_SERVER['HTTP_USER_AGENT'];
       if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
           return true;
       }
       return false;        
    }
    
    
    /**
     * Is the request coming from a bot or spider?
     *
     * Works with Googlebot, MSN, Yahoo, possibly others.
     *
     * @return boolean
     */
    public function isBot()
    {
        $ua =strtolower($this->server('HTTP_USER_AGENT'));
        return (
            false !== strpos($ua, 'googlebot') ||
            false !== strpos($ua, 'msnbot') ||
            false !== strpos($ua, 'yahoo!') ||
            false !== strpos($ua, 'slurp') ||
            false !== strpos($ua, 'bot') ||
            false !== strpos($ua, 'spider')
        );
    }
    
    
    /**
     * Is the request from CLI (Command-Line Interface)?
     *
     * @return boolean
     */
    public function isCli()
    {
        return !isset($_SERVER['HTTP_HOST']);
    }

    public function isSocket()
    {
        return $this->isCli();
    }



    /**
     * Is this a Flash request?
     * 
     * @return bool
     */
    public function isFlash()
    {
        return ($this->header('USER_AGENT') == 'Shockwave Flash');
    }
    
    public function getBrowser()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes separately and for good reason.
        if (preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif (preg_match('/Firefox/i',$u_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif (preg_match('/Chrome/i',$u_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif (preg_match('/Safari/i',$u_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif (preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif (preg_match('/Netscape/i',$u_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // Finally get the correct version number.
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // See how many we have.
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }

        // Check if we have a number.
        if ($version==null || $version=="") {$version="?";}

        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
        );
    }
}