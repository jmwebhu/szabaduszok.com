<?php

class Notification_Formatter
{
    const EXTENSION_TWIG = 'twig';
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
    public function getFormattedData()
    {
        $data = $this->_notification->getData();
        return Twig::getHtmlFromTemplate($this->getFullTemplatePath(), $data);
    }

    /**
     * @return string
     */
    protected function getFullTemplatePath()
    {
        $event = $this->_notification->getEvent();

        // Templates/new_project.html.twig
        return $this->getTemplateBasePath() . DIRECTORY_SEPARATOR . $event->getTemplateName() .
            '.' . $this->_notifier->getTemplateFormat() . '.' . $this->getTemplateExtension();
    }

    /**
     * @return string
     */
    protected function getTemplateBasePath()
    {
        return self::TEMPLATE_BASE_PATH;
    }

    /**
     * @return string
     */
    protected function getTemplateExtension()
    {
        return self::EXTENSION_TWIG;
    }
}