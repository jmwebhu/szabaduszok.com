<?php

class Discounted extends Subscription_Decorator
{
    public function __construct(Subscription $subscription, array $discountData)
    {
        parent::__construct($subscription);
    }

    /**
     * @return string
     */
    public function name()
    {

    }

    /**
     * @return int
     */
    public function price()
    {
        // TODO: Implement price() method.
    }

    /**
     * @return array
     */
    public function usableProducts()
    {
        // TODO: Implement usableProducts() method.
    }
}