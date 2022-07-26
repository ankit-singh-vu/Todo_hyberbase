<?php

namespace App;

class Stripe extends \System\Client
{
    public function __construct($callback = null)
    {
        parent::__construct(function($data) {
            return json_decode($data, true);
        });
        $this->setRequestHeader('Authorization', 'Bearer ' . $_ENV['STRIPE_SECRET_KEY']);
        //$this->setRequestTypeJSON();
        $this->setBaseURL('https://api.stripe.com/v1');
    }
}
























