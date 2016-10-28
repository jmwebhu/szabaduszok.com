<?php

abstract class Notifier
{
    /**
     * @var Notification
     */
    protected $_notification;

    /**
     * @param string $context
     * @return bool
     */
    abstract protected function send($context);

    /**
     * @return string
     */
    abstract public function getTemplateFormat();

    /**
     * @param Notification $notification
     */
    public function setNotification($notification)
    {
        $this->_notification = $notification;
    }

    /**
     * @return bool
     */
    public function sendNotification()
    {
        $formatter  = new Notification_Formatter($this->_notification, $this);
        $context    = $formatter->getFormattedData();

        return $this->send($context);
    }
}