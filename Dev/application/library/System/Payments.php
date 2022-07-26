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
 * Payments
 *
 * @package     :HyperBase
 * @subpackage  :System
 * @version     :6.0
 * @author      :Dyutiman Chakraborty <dyutiman@mclogics.com> 
 * 
 */
abstract class Payments
{
    /**
     * @var Kernel
     */
    protected $kernel;

    /**
     * @var array
     */
    protected $method_map = array(
        'create'    => 'subscription_create_link',
        'change'    => 'subscription_change_link',
        'update'    => 'subscription_update_link',
        'cancel'    => 'subscription_cancel_link',

        'created'   => 'subscription_created',
        'updated'   => 'subscription_updated',
        'canceled'  => 'subscription_canceled',

        'success'   => 'subscription_payment_success',
        'refunded'  => 'subscription_payment_refunded',
        'failed'    => 'subscription_payment_failed'
    );

    /**
     * Payments constructor.
     * @param Kernel $kernel
     */
    public function __construct(\System\Kernel $kernel)
    {
        $this->kernel = $kernel;
        $kernel->addMethod('payment', function() {
            return $this;
        });
    }

    abstract protected function subscription_create_link(\Model\Subscription $subscription, array $params = array());
    abstract protected function subscription_change_link(\Model\Subscription $subscription, array $params = array());
    abstract protected function subscription_update_link(\Model\Subscription $subscription, array $params = array());
    abstract protected function subscription_cancel_link(\Model\Subscription $subscription, array $params = array());

    abstract protected function subscription_created(\Model\Subscription $subscription, array $params = array());
    abstract protected function subscription_updated(\Model\Subscription $subscription, array $params = array());
    abstract protected function subscription_canceled(\Model\Subscription $subscription, array $params = array());

    abstract protected function subscription_payment_success(\Model\Subscription $subscription, array $params = array());
    abstract protected function subscription_payment_refunded(\Model\Subscription $subscription, array $params = array());
    abstract protected function subscription_payment_failed(\Model\Subscription $subscription, array $params = array());

    /**
     * @param $method
     * @param $args
     * @return mixed
     * @throws \Exception
     */
    public function __call($method, $args)
    {
        if(isset($this->method_map[$method])) {
            $function = $this->method_map[$method];
            return $this->$function($args[0], isset($args[1])?$args[1]:array());
        }
        throw new \Exception('Method ' . $method . ' do not exist');
    }

}