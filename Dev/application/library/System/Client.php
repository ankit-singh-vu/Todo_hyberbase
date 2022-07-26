<?php
/**
 * All HyperBase code is Copyright 2001 - 2012 by the original authors.
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License
 * for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program as the file LICENSE.txt; if not, please see
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt.
 * 
 * HyperBase is a registered trademark of Dyutiman Chakraborty.
 *
 * HyperBase includes works under other copyright notices and distributed
 * according to the terms of the GNU General Public License or a compatible
 * license.
 * 
 */
namespace System;

/**
 * REST Client
 * Makes RESTful HTTP requests on webservices
 *
 * @package     :HyperBase
 * @subpackage  :System
 * @version     :6.0
 * @author      :Dyutiman Chakraborty <dyutiman@mclogics.com> 
 * 
 */
class Client
{
    protected $headers = array();
    protected $params = array();
    protected $baseUrl = null;
    protected $filter = null;
    protected $request_as_json = false;

    public function __construct($callback=null)
    {
        $this->filter = $callback;
    }

    /**
     * GET
     *
     * @param string $url URL to perform action on
     * @param optional array $params Array of key => value parameters to pass
     */
    public function get($url='', array $params = array())
    {
        return $this->_fetch($url, $params, 'GET');
    }
    
    
    /**
     * POST
     * 
     * @param string $url URL to perform action on
     * @param optional array $params Array of key => value parameters to pass
     */
    public function post($url='', array $params = array())
    {
        return $this->_fetch($url, $params, 'POST');
    }
    
    
    /**
     * PUT
     * 
     * @param string $url URL to perform action on
     * @param optional array $params Array of key => value parameters to pass
     */
    public function put($url='', array $params = array())
    {
        return $this->_fetch($url, $params, 'PUT');
    }
    
    
    /**
     * DELETE
     *
     * @param string $url URL to perform action on
     * @param optional array $params Array of key => value parameters to pass
     */
    public function delete($url='', array $params = array())
    {
        $params = array_merge($this->params, $params);
        return $this->_fetch($url, $params, 'DELETE');
    }

    public function setBaseURL($url)
    {
        $this->baseUrl = $url;
        return $this;
    }

    public function setRequestHeader($key, $value)
    {
        $this->headers[] = $key.': '.$value;
        return $this;
    }

    public function setParameters($key, $value)
    {
        $this->params[$key] = $value;
        return $this;
    }

    public function setRequestAsJSON()
    {
        $this->headers[] = 'content-type: application/json';
        $this->request_as_json = true;
        return $this;
    }
    
    
    /**
     * Fetch a URL with given parameters
     */
    protected function _fetch($url, array $params = array(), $method = 'GET')
    {
        $method = strtoupper($method);

        if($this->baseUrl != null) {
            $url = $this->baseUrl . $url;
        }
        $urlParts = parse_url($url);

        //___debug($this->headers);

        $queryString = http_build_query($params);
        
        // Append params to URL as query string if not a POST
        if (strtoupper($method) != 'POST' && count($params) > 0) {
            $url = $url . "?" . $queryString;
        }
        
        //echo $url;
        //var_dump("Fetching External URL: [" . $method . "] " . $url, $params);
        
        // Use cURL
        if(function_exists('curl_init')) {
            $ch = curl_init($urlParts['host']);

            //___debug($url);

            // METHOD differences
            switch($method) {
                case 'GET':
                    curl_setopt($ch, CURLOPT_URL, $url);
                break;
                case 'POST':
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, true);
                    if(count($params) > 0) {
                        if ($this->request_as_json == true) {
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
                        } else {
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $queryString);
                        }
                    }
                break;
                 
                case 'PUT':
                    curl_setopt($ch, CURLOPT_URL, $url);
                    $putData = file_put_contents("php://memory", $queryString);
                    curl_setopt($ch, CURLOPT_PUT, true);
                    curl_setopt($ch, CURLOPT_INFILE, $putData);
                    curl_setopt($ch, CURLOPT_INFILESIZE, strlen($queryString));
                break;
                 
                case 'DELETE':
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            }
            
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the data
            curl_setopt($ch, CURLOPT_HEADER, false); // Get headers
            
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
            
            // HTTP digest authentication
            if(isset($urlParts['user']) && isset($urlParts['pass'])) {
                $this->headers[] = "Authorization: Basic ".base64_encode($urlParts['user'].':'.$urlParts['pass']);
            }
            if(count($this->headers) > 0) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
            }

            $response = curl_exec($ch);
            $responseInfo = curl_getinfo($ch);
            curl_close($ch);
            
        // Use sockets... (eventually)
        } else {
            throw new Exception(__METHOD__ . " Requres the cURL library to work.");
        }

        //___debug(array('meta' => $responseInfo, 'data' => $response));

        if(is_callable($this->filter)) {
            return call_user_func_array($this->filter, array($response, $responseInfo));
        }
        return $response;
    }
}