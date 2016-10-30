<?php

abstract class Notifier
{
    const TEMPLATE_FORMAT_HTML = 'html';
    const TEMPLATE_FORMAT_JSON = 'json';

    /**
     * @var Notification
     */
    protected $_notification;

    /**
     * Notifier constructor.
     * @param Notification $_notification
     */
    public function __construct(Notification $_notification)
    {
        $this->_notification = $_notification;
    }


    /**
     * @return Notification
     */
    public function getNotification()
    {
        return $this->_notification;
    }

    /**
     * @param Notifiable $target
     * @param string $context
     * @return bool
     */
    abstract protected function sendTo(Notifiable $target, $context);

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
     * @param Notifiable $target
     * @return bool
     */
    public function sendNotificationTo(Notifiable $target)
    {
        $formatter  = new Notification_Formatter($this->_notification, $this);
        $context    = $formatter->getFormattedData();

        return $this->sendTo($target, $context);
    }
}