<?php

interface Transaction
{
    /**
     * @param mixed $data
     * @return mixed
     */
    public function execute();
}