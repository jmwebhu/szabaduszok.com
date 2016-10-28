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
    public function getNotificationSubject();
}