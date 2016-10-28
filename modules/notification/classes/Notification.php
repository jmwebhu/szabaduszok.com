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
     * @return int
     */
    public function getId();

    /**
     * @param array
     * @return array
     */
    public function submit(array $data);
}