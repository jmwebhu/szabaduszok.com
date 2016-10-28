<?php

class Notification_Formatter
{
    const EXTENSION_TWIG = 'tiwg';
    const TEMPLATE_BASE_PATH = 'Templates';

    /**
     * @var Notification
     */
    protected $_notification;

    /**
     * @var Notifier
     */
    protected $_notifier;

    /**
     * @param Notification $_notification
     * @param Notifier $_notifier
     */
    public function __construct(Notification $_notification, Notifier $_notifier)
    {
        $this->_notification    = $_notification;
        $this->_notifier        = $_notifier;
    }
    /**
     * @return string
     */
    protected function getTemplateBasePath()
    {
        self::TEMPLATE_BASE_PATH;
    }

    /**
     * @return string
     */
    public function getFormattedData()
    {
        return 'Formatted data';
    }

    /**
     * @return string
     */
    protected function getTemplateExtension()
    {
        return self::EXTENSION_TWIG;
    }
}