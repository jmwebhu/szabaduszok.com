<?php

class Transaction_Message_Delete_Factory_Test extends Unittest_TestCase
{
    protected $_messageMock;

    protected $_senderParticipantMock;

    protected $_receiverParticipantMock;

    /**
     * @covers Transaction_Message_Delete_Factory::createDelete()
     */
    public function testcreateDeleteOutgoing()
    {
        $type = Transaction_Message_Delete_Factory::createDelete($this->_messageMock, $this->_senderParticipantMock);
        $this->assertTrue($type instanceof Transaction_Message_Delete_Outgoing);
    }

    /**
     * @covers Transaction_Message_Delete_Factory::createDelete()
     */
    public function testcreateDeleteIncoming()
    {
        $type = Transaction_Message_Delete_Factory::createDelete($this->_messageMock, $this->_receiverParticipantMock);
        $this->assertTrue($type instanceof Transaction_Message_Delete_Incoming);
    }

    public function setUp()
    {
        $senderParticipantMock = $this->getMockBuilder('\Entity_User_Freelancer')
            ->setMethods(array('getId'))
            ->getMock();

        $senderParticipantMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));

        $messageMock = $this->getMockBuilder('\Entity_Message')
            ->setMethods(array('getSender'))
            ->getMock();

        $messageMock->expects($this->any())
            ->method('getSender')
            ->will($this->returnValue($senderParticipantMock));

        $receiverParticipantMock = $this->getMockBuilder('\Entity_User_Employer')
            ->setMethods(array('getId'))
            ->getMock();

        $receiverParticipantMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(2));

        $this->_messageMock             = $messageMock;
        $this->_senderParticipantMock   = $senderParticipantMock;
        $this->_receiverParticipantMock = $receiverParticipantMock;
    }
}