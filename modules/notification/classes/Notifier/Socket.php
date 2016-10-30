<?php

class Notifier_Socket extends Notifier
{
    protected function sendTo(Notifiable $target, $context)
    {
        return true;
    }

    /**
     * @return string
     */
    public function getTemplateFormat()
    {
        return self::TEMPLATE_FORMAT_JSON;
    }
}