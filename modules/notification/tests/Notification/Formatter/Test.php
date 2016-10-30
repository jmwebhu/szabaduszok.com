<?php

class Notification_Formatter_Test extends Unittest_TestCase
{
    public function testGetFullTemplatePath()
    {
        $event = Model_Event_Factory::createEvent(Model_Event::TYPE_PROJECT_NEW);
        $this->setMockAny('\Notification', 'getEvent', $event);

        $notifier = new Notifier_Email($this->_mock);
        $formatter = new Notification_Formatter($this->_mock, $notifier);
        $template = $this->invokeMethod($formatter, 'getFullTemplatePath', []);

        $this->assertEquals('Templates/project_new.html.twig', $template);
    }
}