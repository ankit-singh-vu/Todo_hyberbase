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
class SSH
{
    protected $host;
    protected $user;
    protected $password = null;
    protected $ssh_key_path = null;
    protected $log_file = null;
    protected $port = 22;
    protected $ensure_run_as_root = false;

    public function __construct($host, $user='root')
    {
        $this->host = $host;
        $this->user = $user;
        exec("ssh-keygen -f \"/root/.ssh/known_hosts\" -R ".$host);
    }

    public function ensure_run_as_root($state=true)
    {
        $this->ensure_run_as_root = $state;
        return $this;
    }

    public function as_root($state=true)
    {
        return $this->ensure_run_as_root($state);
    }

    public function set_log_file($path)
    {
        $this->log_file = $path;
    }

    public function set_password($password)
    {
        $this->password = $password;
    }

    public function set_auth_key_file($path)
    {
        $this->ssh_key_path = $path;
    }

    public function set_auth_key($key, $server_id)
    {
        $key_path = '/tmp/'.md5($key).'.'.$server_id.'.rsa';
        if(!file_exists($key_path)) {
            file_put_contents($key_path, $key);
            shell_exec('chmod 0600 ' . $key_path);
        }
        $this->ssh_key_path = $key_path;
    }

    public function set_port($port)
    {
        $this->port = $port;
    }

    protected function exec_auth_key($cmd)
    {
        exec('chmod 0600 '.$this->ssh_key_path);
        $commend = "/usr/bin/ssh -p ".$this->port." -o StrictHostKeyChecking=no -i ".$this->ssh_key_path." " . $this->user . "@" . $this->host . " '( ".$cmd." )' ";

        //___debug($commend);

        if ($this->log_file != false) {
            return shell_exec($commend ." >> " .$this->log_file);
        }
        return shell_exec($commend);
    }

    protected function exec_auth_password($cmd)
    {
        $commend = "sshpass -p \"".$this->password."\" /usr/bin/ssh -p ".$this->port." -o StrictHostKeyChecking=no " . $this->user . "@" . $this->host . " '( ".$cmd." )' ";
        //___debug($commend);
        if ($this->log_file != false) {
            return shell_exec($commend ." >> " .$this->log_file);
        }
        return shell_exec($commend);
    }

    public function exec($cmd, $print_cmd=false)
    {
        if($this->ensure_run_as_root == true && $this->user != 'root') {
            if(stripos($cmd, 'sudo') !== false) {
                $cmd = 'sudo ' . $cmd;
            } else {
                $cmd = 'sudo ' . str_replace('&&', '&& sudo',$cmd);
            }

        }
        if($print_cmd != false) {
            ___debug($cmd);
        }
        if($this->ssh_key_path != null) {
            return $this->exec_auth_key($cmd);
        }
        return $this->exec_auth_password($cmd);
    }

    protected function copy_to_server_auth_key($source, $target)
    {
        //___debug(file_get_contents($source));
        exec('chmod 0600 '.$this->ssh_key_path);
        $commend = "/usr/bin/scp -P ".$this->port." -o StrictHostKeyChecking=no -i ".$this->ssh_key_path." ".$source." " . $this->user . "@" . $this->host . ":".$target;
        //___debug($commend);
        if ($this->log_file != false) {
            return exec($commend ." >> " .$this->log_file);
        }
        return exec($commend);
    }

    protected function copy_to_server_auth_password($source, $target)
    {
        $commend = "sshpass -p \"".$this->password."\" /usr/bin/scp -P ".$this->port." -o StrictHostKeyChecking=no ".$source." " . $this->user . "@" . $this->host . ":".$target;
        //___debug($commend);
        if ($this->log_file != false) {
            return exec($commend ." >> " .$this->log_file);
        }
        return exec($commend);
    }

    public function put($source, $target, $variables=null)
    {
        if($variables==null) {
            if ($this->ssh_key_path != null) {
                return $this->copy_to_server_auth_key($source, $target);
            }
            return $this->copy_to_server_auth_password($source, $target);
        } else {
            $temp_complied_script_path = '/tmp/' . md5($source) . '.' . date('U') . '.out' ;
            file_put_contents($temp_complied_script_path, process_template(file_get_contents($source), $variables, '$[', ']'));
            return $this->put($temp_complied_script_path, $target);
        }
    }

    public function run($script, $params=null)
    {
        if(is_array($params)) {
            return $this->run_template($script, $params);
        } else {
            $tmp_script_path = '/tmp/' . md5(date('U')) . md5(gen_uuid()) . '.script';
            $this->put($script, $tmp_script_path);
            if($this->ensure_run_as_root == true && $this->user != 'root') {
                $return = $this->exec('chmod +x ' . $tmp_script_path . ' && sudo ' . $tmp_script_path);
            } else {
                $return = $this->exec('chmod +x ' . $tmp_script_path . ' && ' . $tmp_script_path);
            }
            $this->exec('rm -rf ' . $tmp_script_path);
            return $return;
        }
    }

    public function run_template($template_path, $variables)
    {
        if(!file_exists($template_path)) {
            \Model\Error::trigger(new \Exception($template_path . ' do not exists'));
        }
        $temp_complied_script_path = '/tmp/' . md5($template_path) . '.' . date('U') . '.sh' ;
        //___debug(process_template(file_get_contents($template_path), $variables, '$[', ']'));
        file_put_contents($temp_complied_script_path, process_template(file_get_contents($template_path), $variables, '$[', ']'));
        $result = $this->run($temp_complied_script_path);
        unlink($temp_complied_script_path);
        return $result;
    }
}



















