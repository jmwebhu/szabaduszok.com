<?php

interface Notification_Subject
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getSubjectType();

    /**
     * @return array
     */
    public function getData();

    /**
     * @return string
     */
    public function getNotificationUrl();
}