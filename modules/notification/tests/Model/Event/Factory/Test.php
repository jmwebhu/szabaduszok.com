<?php

class Model_Event_Factory_Test extends Unittest_TestCase
{
    /**
     * @covers Model_Event_Factory::createEvent()
     */
    public function testCreateEventOk()
    {
        $event = Model_Event_Factory::createEvent(Model_Event::TYPE_PROJECT_NEW);
        $this->assertTrue($event instanceof Model_Event_Project_New);
        $this->assertEquals(Model_Event::TYPE_PROJECT_NEW, $event->event_id);
    }
}