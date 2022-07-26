<?php
/**
 * Functions.
 *
 * PHP version 5.5.9
 *
 * Some low level internal function available to the framework.
 *
 * @category   Framework
 * @package    Functions
 * @author     Dyutiman Chakraborty <dc@mclogics.com>
 * @copyright  2014 - 2015, HB
 * @license    https://licenses.domain.com/psl-1.0.txt Proprietary Service Licence ver. 1.0
 *
 * @link       https://framework.local
 */

/**
 * Print out an array or object contents in preformatted text
 * Useful for debugging and quickly determining contents of variables.
 *
 * <code>
 *      _debug($arrayOne, $arrayTwo ...., $objectOne, $objectTwo... $arrayN, $objectN);
 * </code>
 *
 * @return void
 */
function _debug()
{
    $objects = func_get_args();
    $content = ''; //"\n<pre>\n";
    foreach ($objects as $object) {
        $content .= print_r($object, true);
    }
    echo $content; //"\n</pre>\n";
    exit;

    return;
}//end _debug()x

function ___debug()
{
    $objects = func_get_args();
    $content = "\n<pre>\n";
    foreach ($objects as $object) {
        $content .= print_r($object, true);
    }
    echo $content;
    echo "\n</pre>\n";
    exit;

    return;
}//end _debug()x


function gen_uuid($model=false) {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

function generate_ssh_key($key_bits=4096, $key_type=OPENSSL_KEYTYPE_RSA)
{
    $rsaKey = openssl_pkey_new(array(
        'private_key_bits' => $key_bits,
        'private_key_type' => $key_type
    ));
    $privKey = openssl_pkey_get_private($rsaKey);
    openssl_pkey_export($privKey, $pem); //Private Key
    $pubKey = sshEncodePublicKey($rsaKey); //Public Key
    return array(
        'private_key'   => $pem,
        'public_key'    => $pubKey
    );
}

function sshEncodePublicKey($privKey) {
    $keyInfo = openssl_pkey_get_details($privKey);
    $buffer  = pack("N", 7) . "ssh-rsa" .
        sshEncodeBuffer($keyInfo['rsa']['e']) .
        sshEncodeBuffer($keyInfo['rsa']['n']);
    return "ssh-rsa " . base64_encode($buffer);
}

function sshEncodeBuffer($buffer) {
    $len = strlen($buffer);
    if (ord($buffer[0]) & 0x80) {
        $len++;
        $buffer = "\x00" . $buffer;
    }
    return pack("Na*", $len, $buffer);
}

function sortByOrder($a, $b)
{
    return $a['order'] - $b['order'];
}

function get_session_user_id($default=null)
{
    $user = get_session_user($default);
    if($user == $default) {
        return $default;
    }
    return $user->id;
}

function get_session_user($default=null)
{
    if(!isset($_COOKIE['access_token'])) {
        return $default;
    }
    $session = \Model\Session::find_by_token($_COOKIE['access_token']);
    if(!$session) {
        return $default;
    }
    try {
        return \Model\User::find($session->user_id);
    } catch (\Exception $e) {
        return $default;
    }
}

function get_session_tenant_id($default=null)
{
    $tenant = get_session_tenant($default);
    if($tenant == $default) {
        return $default;
    }
    return $tenant->id;
}

function get_session_tenant($default=null)
{
    $user = get_session_user();
    if(!$user) {
        return $default;
    }
    try {
        return \Model\Tenant::find($user->c_tenant);
    } catch (\Exception $e) {
        return $default;
    }
}


/**
 * Returns the instance of Kernel Object.
 *
 * @param array $config Optionally the configuration parameters can be provided.
 *
 * @return \System\Kernel
 */
function Kernel(array $config = array())
{
    return \System\Kernel::getInstance($config);
}//end Kernel()


function norm_str($string) {
    return	trim(strtolower(
        str_replace('.','',$string)));
}

function in_array_norm($needle,$haystack) {
    return	in_array(norm_str($needle),$haystack);
}

function parse_name($fullname) {
    $titles			=	array('dr','miss','mr','mrs','ms','judge');
    $prefices		=	array('ben','bin','da','dal','de','del','der','de','e',
        'la','le','san','st','ste','van','vel','von');
    $suffices		=	array('esq','esquire','jr','sr','2','ii','iii','iv');

    $pieces			=	explode(',',preg_replace('/\s+/',' ',trim($fullname)));
    $n_pieces		=	count($pieces);

    $out = array();

    switch($n_pieces) {
        case	1:	// array(title first middles last suffix)
            $subp	=	explode(' ',trim($pieces[0]));
            $n_subp	=	count($subp);
            for($i = 0; $i < $n_subp; $i++) {
                $curr				=	@trim($subp[$i]);
                $next				=	@trim($subp[$i+1]);

                if($i == 0 && in_array_norm($curr,$titles)) {
                    $out['title']	=	$curr;
                    continue;
                }

                if(!isset($out['first'])) {
                    $out['first']	=	$curr;
                    continue;
                }

                if($i == $n_subp-2 && $next && in_array_norm($next,$suffices)) {
                    if($out['last']) {
                        $out['last']	.=	" $curr";
                    }
                    else {
                        $out['last']	=	$curr;
                    }
                    $out['suffix']		=	$next;
                    break;
                }

                if($i == $n_subp-1) {
                    if(isset($out['last'])) {
                        $out['last']	.=	" $curr";
                    }
                    else {
                        $out['last']	=	$curr;
                    }
                    continue;
                }

                if(in_array_norm($curr,$prefices)) {
                    if($out['last']) {
                        $out['last']	.=	" $curr";
                    }
                    else {
                        $out['last']	=	$curr;
                    }
                    continue;
                }

                if($next == 'y' || $next == 'Y') {
                    if($out['last']) {
                        $out['last']	.=	" $curr";
                    }
                    else {
                        $out['last']	=	$curr;
                    }
                    continue;
                }

                if($out['last']) {
                    $out['last']	.=	" $curr";
                    continue;
                }

                if($out['middle']) {
                    $out['middle']		.=	" $curr";
                }
                else {
                    $out['middle']		=	$curr;
                }
            }
            break;
        case	2:
            switch(in_array_norm($pieces[1],$suffices)) {
                case	TRUE: // array(title first middles last,suffix)
                    $subp	=	explode(' ',trim($pieces[0]));
                    $n_subp	=	count($subp);
                    for($i = 0; $i < $n_subp; $i++) {
                        $curr				=	trim($subp[$i]);
                        $next				=	trim($subp[$i+1]);

                        if($i == 0 && in_array_norm($curr,$titles)) {
                            $out['title']	=	$curr;
                            continue;
                        }

                        if(!$out['first']) {
                            $out['first']	=	$curr;
                            continue;
                        }

                        if($i == $n_subp-1) {
                            if($out['last']) {
                                $out['last']	.=	" $curr";
                            }
                            else {
                                $out['last']	=	$curr;
                            }
                            continue;
                        }

                        if(in_array_norm($curr,$prefices)) {
                            if($out['last']) {
                                $out['last']	.=	" $curr";
                            }
                            else {
                                $out['last']	=	$curr;
                            }
                            continue;
                        }

                        if($next == 'y' || $next == 'Y') {
                            if($out['last']) {
                                $out['last']	.=	" $curr";
                            }
                            else {
                                $out['last']	=	$curr;
                            }
                            continue;
                        }

                        if($out['last']) {
                            $out['last']	.=	" $curr";
                            continue;
                        }

                        if($out['middle']) {
                            $out['middle']		.=	" $curr";
                        }
                        else {
                            $out['middle']		=	$curr;
                        }
                    }
                    $out['suffix']	=	trim($pieces[1]);
                    break;
                case	FALSE: // array(last,title first middles suffix)
                    $subp	=	explode(' ',trim($pieces[1]));
                    $n_subp	=	count($subp);
                    for($i = 0; $i < $n_subp; $i++) {
                        $curr				=	trim($subp[$i]);
                        $next				=	trim($subp[$i+1]);

                        if($i == 0 && in_array_norm($curr,$titles)) {
                            $out['title']	=	$curr;
                            continue;
                        }

                        if(!$out['first']) {
                            $out['first']	=	$curr;
                            continue;
                        }

                        if($i == $n_subp-2 && $next &&
                            in_array_norm($next,$suffices)) {
                            if($out['middle']) {
                                $out['middle']	.=	" $curr";
                            }
                            else {
                                $out['middle']	=	$curr;
                            }
                            $out['suffix']		=	$next;
                            break;
                        }

                        if($i == $n_subp-1 && in_array_norm($curr,$suffices)) {
                            $out['suffix']		=	$curr;
                            continue;
                        }

                        if($out['middle']) {
                            $out['middle']		.=	" $curr";
                        }
                        else {
                            $out['middle']		=	$curr;
                        }
                    }
                    $out['last']	=	$pieces[0];
                    break;
            }
            unset($pieces);
            break;
        case	3:	// array(last,title first middles,suffix)
            $subp	=	explode(' ',trim($pieces[1]));
            $n_subp	=	count($subp);
            for($i = 0; $i < $n_subp; $i++) {
                $curr				=	trim($subp[$i]);
                $next				=	trim($subp[$i+1]);
                if($i == 0 && in_array_norm($curr,$titles)) {
                    $out['title']	=	$curr;
                    continue;
                }

                if(!$out['first']) {
                    $out['first']	=	$curr;
                    continue;
                }

                if($out['middle']) {
                    $out['middle']		.=	" $curr";
                }
                else {
                    $out['middle']		=	$curr;
                }
            }

            $out['last']				=	trim($pieces[0]);
            $out['suffix']				=	trim($pieces[2]);
            break;
        default:	// unparseable
            unset($pieces);
            break;
    }

    return $out;
}

/**
 * @param $template
 * @param array $variables
 * @param string $varAppend
 * @param string $varPrepend
 * @return mixed
 */
function process_template($template, array $variables=array(), $varAppend='${', $varPrepend='}')
{
    //___debug($variables);
    foreach($variables as $key => $value) {
        $var = $varAppend . $key . $varPrepend;
        if(stripos($template, $var)!== false) {
            $template = str_replace($var, $value, $template);
        }
    }
    return $template;
}

function isOnline($url){
    $agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";$ch=curl_init();
    curl_setopt ($ch, CURLOPT_URL,$url );
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch,CURLOPT_VERBOSE,false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch,CURLOPT_SSLVERSION,3);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);
    $page=curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if($httpcode>=200 && $httpcode<=308) return true;
    else return false;
}



if (!function_exists('spyc_load')) {
    /**
     * Parses YAML to array.
     * @param string $string YAML string.
     * @return array
     */
    function spyc_load ($string) {
        return \System\Spyc::YAMLLoadString($string);
    }
}

if (!function_exists('spyc_load_file')) {
    /**
     * Parses YAML to array.
     * @param string $file Path to YAML file.
     * @return array
     */
    function spyc_load_file ($file) {
        return \System\Spyc::YAMLLoad($file);
    }
}

if (!function_exists('spyc_dump')) {
    /**
     * Dumps array to YAML.
     * @param array $data Array.
     * @return string
     */
    function spyc_dump ($data) {
        return \System\Spyc::YAMLDump($data, false, false, true);
    }
}


function websocket_open($url){
    $key=base64_encode(uniqid());
    $query=parse_url($url);

    //___debug($query);

    $header="GET / HTTP/1.1\r\n"
        ."Host: ".$query['host'].":".$query['port']."\r\n"
        ."pragma: no-cache\r\n"
        ."cache-control: no-cache\r\n"
        ."Upgrade: WebSocket\r\n"
        ."Connection: Upgrade\r\n"
        ."Sec-WebSocket-Key: $key\r\n"
        ."Sec-WebSocket-Version: 13\r\n"
        ."\r\n";
    $sp=fsockopen($query['host'],$query['port'], $errno, $errstr,1);
    if(!$sp) die("Unable to connect to server ".$url);
    // Ask for connection upgrade to websocket
    fwrite($sp,$header);
    stream_set_timeout($sp,5);
    $reaponse_header=fread($sp, 1024);
    if(!strpos($reaponse_header," 101 ")
        || !strpos($reaponse_header,'Sec-WebSocket-Accept: ')){
        die("Server did not accept to upgrade connection to websocket == "
            .$reaponse_header);
    }
    return $sp;
}

function websocket_write($sp, $data,$final=true){
    // Assamble header: FINal 0x80 | Opcode 0x02
    $header=chr(($final?0x80:0) | 0x02); // 0x02 binary

    // Mask 0x80 | payload length (0-125)
    if(strlen($data)<126) $header.=chr(0x80 | strlen($data));
    elseif (strlen($data)<0xFFFF) $header.=chr(0x80 | 126) . pack("n",strlen($data));
    elseif(PHP_INT_SIZE>4) // 64 bit
        $header.=chr(0x80 | 127) . pack("Q",strlen($data));
    else  // 32 bit (pack Q dosen't work)
        $header.=chr(0x80 | 127) . pack("N",0) . pack("N",strlen($data));

    // Add mask
    $mask=pack("N",rand(1,0x7FFFFFFF));
    $header.=$mask;

    // Mask application data.
    for($i = 0; $i < strlen($data); $i++)
        $data[$i]=chr(ord($data[$i]) ^ ord($mask[$i % 4]));

    return fwrite($sp,$header.$data);
}

function websocket_read($sp,$wait_for_end=true,&$err=''){
    $out_buffer="";
    do{
        // Read header
        $header=fread($sp,2);
        if(!$header) die("Reading header from websocket failed");
        $opcode = ord($header[0]) & 0x0F;
        $final = ord($header[0]) & 0x80;
        $masked = ord($header[1]) & 0x80;
        $payload_len = ord($header[1]) & 0x7F;

        // Get payload length extensions
        $ext_len=0;
        if($payload_len>125) $ext_len+=2;
        if($payload_len>126) $ext_len+=6;
        if($ext_len){
            $ext=fread($sp,$ext_len);
            if(!$ext) die("Reading header extension from websocket failed");

            // Set extented paylod length
            $payload_len=$ext_len;
            for($i=0;$i<$ext_len;$i++)
                $payload_len += ord($ext[$i]) << ($ext_len-$i-1)*8;
        }

        // Get Mask key
        if($masked){
            $mask=fread($sp,4);
            if(!$mask) die("Reading header mask from websocket failed");
        }

        // Get application data
        $data_len=$payload_len-$ext_len-($masked?4:0);
        $frame_data=fread($sp,$data_len);
        if(!$frame_data) die("Reading from websocket failed");

        // if opcode ping, reuse headers to send a pong and continue to read
        if($opcode==9){
            // Assamble header: FINal 0x80 | Opcode 0x02
            $header[0]=chr(($final?0x80:0) | 0x0A); // 0x0A Pong
            fwrite($sp,$header.$ext.$mask.$frame_data);

            // Recieve and unmask data
        }elseif($opcode<9){
            $data="";
            if($masked)
                for ($i = 0; $i < $data_len; $i++)
                    $data.= $frame_data[$i] ^ $mask[$i % 4];
            else
                $data.= $frame_data;
            $out_buffer.=$data;
        }

        // wait for Final
    }while($wait_for_end && !$final);

    return $out_buffer;
}

function get_wordpress_versions()
{
    $wpversions = \Kernel()->config('app.wordpress.versions');
    if(!is_array($wpversions)) {
        $wpapiURL = 'https://api.github.com/repos/WordPress/WordPress/tags';
        $last_check = \Kernel() ->get_preference('system', 'wp_version_last_update');
        if ($last_check == false) {
            $last_check = 0;
        }
        $max_delay = date('U') - (60 * 60);
        if ($last_check < $max_delay) {

            $agent = 'RedShift/1.0 (Ubuntu; U; Ubuntu 18.04; en-US; rv:2.0.2.24)';
            $max_list_size = 30;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, $agent);
            curl_setopt($ch, CURLOPT_URL, $wpapiURL);
            $wpdata = json_decode(curl_exec($ch), true);
            curl_close($ch);

            $wp_versions = array();
            $x = 0;
            foreach ($wpdata as $wpv) {
                $wp_versions[] = $wpv['name'];
                $x++;
                if ($x >= $max_list_size) {
                    break;
                }
            }
            \Kernel()->set_preference('system', 'wp_version_last_update', date('U'));
            \Kernel()->set_preference('system', 'wp_version', $wp_versions);

        } else {
            $wp_versions = \Kernel()->get_preference('system', 'wp_version');
        }
        return $wp_versions;
    } else {
        return $wpversions;
    }
}

function build_domain_resource($domain)
{
    return rebuild_domain_resource($domain);
}

function rebuild_domain_resource($domain)
{

}