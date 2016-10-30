<?php

class Notifier_Email extends Notifier
{
    /**
     * @param Notifiable $target
     * @param string $context
     * @return bool
     */
    protected function sendTo(Notifiable $target, $context)
    {
        return Email::send($target->getEmail(), '[' . strtoupper($this->_notification->getEvent()->getName()) . ']', $context);
    }

    /**
     * @return string
     */
    public function getTemplateFormat()
    {
        return self::TEMPLATE_FORMAT_HTML;
    }
}