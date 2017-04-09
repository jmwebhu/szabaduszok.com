<?php

class Subscription_Repeating extends Subscription_Decorator
{
    /**
     * @var int
     */
    protected $interval;

    /**
     * @param Subscription $subscription
     * @param int $interval
     */
    public function __construct(Subscription $subscription, $interval)
    {
        parent::__construct($subscription);
        $this->interval = $interval;
    }

    /**
     * @return string
     */
    public function name()
    {
        // @todo
    }

    /**
     * @return int
     */
    public function price()
    {
        return $this->subscription->price() * $this->interval;
    }

    /**
     * @return array
     */
    public function usableProducts()
    {
        return $this->subscription->usableProducts();
    }
}