<?php

interface Transaction
{
    /**
     * @return mixed
     */
    public function execute();
}