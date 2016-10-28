<?php

class Model_Event_Test extends Unittest_TestCase
{
    /**
     * @covers Model_Event::createEvent()
     */
    public function testCreateEventStandardEmpty()
    {
        $event = Model_Event::createEvent();

        $this->assertEquals('Model_Event', get_class($event));
        $this->assertNull($event->event_id);
    }

    /**
     * @covers Model_Event::createEvent()
     */
    public function testCreateEventStandardNotEmpty()
    {
        $event = Model_Event::createEvent(1);

        $this->assertEquals('Model_Event', get_class($event));
        $this->assertEquals(1, $event->event_id);
    }

    /**
     * @covers Model_Event::createEvent()
     */
    public function testCreateEventSpecial()
    {
        $event = Model_Event::createEvent(Model_Event::TYPE_PARTICIPATE_PAY);

        $this->assertTrue($event instanceof Model_Event_Participate_Pay);
        $this->assertEquals(Model_Event::TYPE_PARTICIPATE_PAY, $event->event_id);
    }
}