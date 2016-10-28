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
     * @param string $context
     * @return bool
     */
    abstract protected function sendTo($target, $context);

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
     * @param string $target
     * @return bool
     */
    public function sendNotificationTo($target)
    {
        $formatter  = new Notification_Formatter($this->_notification, $this);
        $context    = $formatter->getFormattedData();

        return $this->sendTo($target, $context);
    }
}