<?php

interface Notification
{
    /**
     * @return array
     */
    public function getData();

    /**
     * @return Event
     */
    public function getEvent();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return array
     */
    public function submit();
}