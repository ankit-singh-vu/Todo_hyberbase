<?php

/**
 * All HyperBase code is Copyright 2001 - 2012 by the original authors.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS
 * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
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
 * HyperBase includes works under other copyright notices and distributed
 * according to the terms of the GNU General Public License or a compatible
 * license.
 *
 */

namespace System;

class Test extends \PHPUnit_Framework_TestCase
{
    protected $kernel;
    
    public function __construct() {
        $this->kernel = \Kernel();
        parent::__construct();
    }
    
    protected function setSession(array $params=array())
    {
        $_SESSION = $params;
    }
    
    protected function debug($var)
    {
        print_r($var);
        exit;
    }
    
    protected function logActivity($message)
    {
        echo $message . "\n";
    }
    
    public function testConfig($key=false, $default=false)
    {
        if($key==false) {
            return $GLOBALS['__HB_TEST_CONFIGS'];
        }
        if(isset($GLOBALS['__HB_TEST_CONFIGS'][$key])) {
            return $GLOBALS['__HB_TEST_CONFIGS'][$key];
        }
        return $default;
    }
    
    protected function exeRequest($requestUrl, $requestMethod='GET', $varaiables=array(), $dispatch=true)
    {
        if($requestMethod == 'GET') {
            $_GET = $varaiables;
        }
        if($requestMethod == 'POST') {
            $_POST = $varaiables;
        }
        $router = $this->kernel->router();
        $params = $this->kernel->router()->match($requestMethod, $requestUrl);
        $this->kernel->request()->setParams($params);
        $this->kernel->request()->route = $router->matchedRoute()->name();
        
        // Required params
        $content = false;
        if(isset($params['module']) && isset($params['action'])) {
            $this->kernel->request()->module = $params['module'];
            $this->kernel->request()->action = $params['action'];
            // Matched route
            $this->kernel->events()->trigger('route_match');
            // Run/execute
            $content = $this->kernel->dispatchRequest($this->kernel->request()->module, $this->kernel->request()->action);
        } else {
            $content = $this->kernel->events()->filter('route_not_found', $content);
        }
        
        if(false === $content) {
            return \System\Exception\FileNotFound("Requested file or page not found. Please check the URL and try again.");
        }
        
        if($dispatch == true) {
            $content = $this->kernel->events()->filter('dispatch_content', $content);
        }
        
        return $content;
    }
    
    
}