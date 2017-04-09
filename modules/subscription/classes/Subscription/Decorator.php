<?php

abstract class Subscription_Decorator implements Subscription
{
    /**
     * @var Subscription
     */
    protected $subscription;

    /**
     * @param Subscription $subscription
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }
}