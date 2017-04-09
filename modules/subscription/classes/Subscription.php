<?php

interface Subscription
{
    /**
     * @return string
     */
    public function name();

    /**
     * @return int
     */
    public function price();

    /**
     * @return array
     */
    public function usableProducts();
}