<?php

class Model_Project_Partner_Type_Test extends Unittest_TestCase
{
    /**
     * @covers Model_Project_Partner_Type_Candidate::isEventPerformable()
     */
    public function testIsEventPerformableCandidateOk()
    {
        $eventNew   = Model_Event_Factory::createEvent(Model_Event::TYPE_CANDIDATE_NEW);
        $type       = Model_Project_Partner_Type_Factory::createType(Model_Project_Partner::TYPE_CANDIDATE);
        $this->assertTrue($type->isEventPerformable($eventNew));

        $eventUndo   = Model_Event_Factory::createEvent(Model_Event::TYPE_CANDIDATE_UNDO);
        $this->assertTrue($type->isEventPerformable($eventUndo));

        $eventAccept   = Model_Event_Factory::createEvent(Model_Event::TYPE_CANDIDATE_ACCEPT);
        $this->assertTrue($type->isEventPerformable($eventAccept));

        $eventReject   = Model_Event_Factory::createEvent(Model_Event::TYPE_CANDIDATE_REJECT);
        $this->assertTrue($type->isEventPerformable($eventReject));
    }

    /**
     * @covers Model_Project_Partner_Type_Candidate::isEventPerformable()
     */
    public function testIsEventPerformableCandidateNotOk()
    {
        $eventRemove    = Model_Event_Factory::createEvent(Model_Event::TYPE_PARTICIPATE_REMOVE);
        $type           = Model_Project_Partner_Type_Factory::createType(Model_Project_Partner::TYPE_CANDIDATE);
        $this->assertFalse($type->isEventPerformable($eventRemove));

        $eventPay   = Model_Event_Factory::createEvent(Model_Event::TYPE_PARTICIPATE_PAY);
        $this->assertFalse($type->isEventPerformable($eventPay));
    }

    /**
     * @covers Model_Project_Partner_Type_Participant::isEventPerformable()
     */
    public function testIsEventPerformableParticipantOk()
    {
        $eventRemove    = Model_Event_Factory::createEvent(Model_Event::TYPE_PARTICIPATE_REMOVE);
        $type           = Model_Project_Partner_Type_Factory::createType(Model_Project_Partner::TYPE_PARTICIPANT);
        $this->assertTrue($type->isEventPerformable($eventRemove));

        $eventPay   = Model_Event_Factory::createEvent(Model_Event::TYPE_PARTICIPATE_PAY);
        $this->assertTrue($type->isEventPerformable($eventPay));
    }

    /**
     * @covers Model_Project_Partner_Type_Participant::isEventPerformable()
     */
    public function testIsEventPerformableParticipantNotOk()
    {
        $eventNew   = Model_Event_Factory::createEvent(Model_Event::TYPE_CANDIDATE_NEW);
        $type       = Model_Project_Partner_Type_Factory::createType(Model_Project_Partner::TYPE_PARTICIPANT);
        $this->assertFalse($type->isEventPerformable($eventNew));

        $eventUndo   = Model_Event_Factory::createEvent(Model_Event::TYPE_CANDIDATE_UNDO);
        $this->assertFalse($type->isEventPerformable($eventUndo));

        $eventAccept   = Model_Event_Factory::createEvent(Model_Event::TYPE_CANDIDATE_ACCEPT);
        $this->assertFalse($type->isEventPerformable($eventAccept));

        $eventReject   = Model_Event_Factory::createEvent(Model_Event::TYPE_CANDIDATE_REJECT);
        $this->assertFalse($type->isEventPerformable($eventReject));
    }
}