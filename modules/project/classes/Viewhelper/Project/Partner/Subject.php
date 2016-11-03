<?php

abstract class Viewhelper_Project_Partner_Subject
{
    /**
     * @var ORM
     */
    protected $_orm;

    /**
     * @return array
     */
    abstract public function getPartnersSeparatedByType();

    /**
     * @return array
     */
    abstract protected function getPartners();
}