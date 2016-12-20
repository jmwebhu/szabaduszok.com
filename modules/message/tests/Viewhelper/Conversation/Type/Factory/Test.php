<?php

class Viewhelper_Conversation_Type_Factory_Test extends Unittest_TestCase
{
    /**
     * @covers Viewhelper_Conversation_Type_Factory::createType()
     */
    public function testCreateTypeSingle()
    {
        $mock = $this->getMockBuilder('\Entity_Conversation')
            ->setMethods(array('getParticipants'))
            ->getMock();

        $mock->expects($this->any())
            ->method('getParticipants')
            ->will($this->returnValue([1, 2]));

        $type = Viewhelper_Conversation_Type_Factory::createType($mock);
        $this->assertTrue($type instanceof Viewhelper_Conversation_Type_Single);
    }

    /**
     * @covers Viewhelper_Conversation_Type_Factory::createType()
     */
    public function testCreateTypeGroup()
    {
        $mock = $this->getMockBuilder('\Entity_Conversation')
            ->setMethods(array('getParticipants'))
            ->getMock();

        $mock->expects($this->any())
            ->method('getParticipants')
            ->will($this->returnValue([1, 2, 3, 4]));

        $type = Viewhelper_Conversation_Type_Factory::createType($mock);
        $this->assertTrue($type instanceof Viewhelper_Conversation_Type_Group);
    }
}