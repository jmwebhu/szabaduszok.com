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
     * @return Notification_Subject
     */
    public function getSubject();

    /**
     * @return Notifiable
     */
    public function getNotifier();

    /**
     * @return Notifiable
     */
    public function getNotified();

    /**
     * @return void
     */
    public function archive();

    /**
     * @return bool
     */
    public function isArchived();

    /**
     * @param array
     * @return array
     */
    public function submit(array $data);
}