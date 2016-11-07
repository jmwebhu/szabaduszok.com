<?php

interface Event
{
    /**
     * @return string
     */
    public function getTemplateName();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getNotifierClass();

    /**
     * @return string
     */
    public function getNotifiedClass();

    /**
     * @return string
     */
    public function getSubjectName();
}