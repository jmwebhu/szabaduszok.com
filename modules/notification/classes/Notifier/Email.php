<?php

class Notifier_Email extends Notifier
{
    /**
     * @param $target
     * @param string $context
     * @return bool
     */
    protected function sendTo($target, $context)
    {
        return Email::send($target, '[' . $this->_notification->getEvent()->getNotificationSubject() . ']', $context);
    }

    /**
     * @return string
     */
    public function getTemplateFormat()
    {
        return self::TEMPLATE_FORMAT_HTML;
    }
}