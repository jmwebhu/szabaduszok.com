<?php

interface Notifiable
{
    /**
     * @param Notification $notification
     * @return void
     */
    public function setNotification(Notification $notification);

    /**
     * @return bool
     */
    public function sendNotification();

    /**
     * @return array
     */
    public function getNotifiers();

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @return int
     */
    public function getId();
}