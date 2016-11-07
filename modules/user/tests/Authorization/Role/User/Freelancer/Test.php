<?php

class Authorization_Role_User_Freelancer_Test extends Unittest_TestCase
{
    /**
     * @covers Authorization_Role_User_Freelancer::canApply()
     */
    public function testCanApplyOk()
    {
        $userMock       = $this->getUserMockSetCandidateParticipant(false, false);
        $project        = new Model_Project();
        $authorization  = new Authorization_User($project, $userMock);

        $this->assertTrue($authorization->canApply());
    }

    /**
     * @covers Authorization_Role_User_Freelancer::canApply()
     */
    public function testCanApplyNotOk()
    {
        $userMock       = $this->getUserMockSetCandidateParticipant(true, false);
        $project        = new Model_Project();
        $authorization  = new Authorization_User($project, $userMock);

        $this->assertFalse($authorization->canApply());

        $userMock       = $this->getUserMockSetCandidateParticipant(false, true);
        $authorization  = new Authorization_User($project, $userMock);

        $this->assertFalse($authorization->canApply());

        $userMock       = $this->getUserMockSetCandidateParticipant(true, true);
        $authorization  = new Authorization_User($project, $userMock);

        $this->assertFalse($authorization->canApply());
    }

    /**
     * @covers Authorization_Role_User_Freelancer::canUndoApplication()
     */
    public function testCanUndoApplicationOk()
    {
        $userMock       = $this->getUserMockSetCandidateParticipant(true, false);
        $project        = new Model_Project();
        $authorization  = new Authorization_User($project, $userMock);

        $this->assertTrue($authorization->canUndoApplication());
    }

    /**
     * @covers Authorization_Role_User_Freelancer::canUndoApplication()
     */
    public function testCanUndoApplicationNotOk()
    {
        $userMock       = $this->getUserMockSetCandidateParticipant(false, false);
        $project        = new Model_Project();
        $authorization  = new Authorization_User($project, $userMock);

        $this->assertFalse($authorization->canUndoApplication());

        $userMock       = $this->getUserMockSetCandidateParticipant(false, true);
        $project        = new Model_Project();
        $authorization  = new Authorization_User($project, $userMock);

        $this->assertFalse($authorization->canUndoApplication());

        $userMock       = $this->getUserMockSetCandidateParticipant(true, true);
        $project        = new Model_Project();
        $authorization  = new Authorization_User($project, $userMock);

        $this->assertFalse($authorization->canUndoApplication());
    }

    /**
     * @param bool $isCandidate
     * @param bool $isParticipant
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function getUserMockSetCandidateParticipant($isCandidate, $isParticipant)
    {
        $userMock             = $this->getMockBuilder('\Model_User_Freelancer')
            ->getMock();

        $userMock->expects($this->any())
            ->method('isCandidateIn')
            ->will($this->returnValue($isCandidate));

        $userMock->expects($this->any())
            ->method('isParticipantIn')
            ->will($this->returnValue($isParticipant));

        return $userMock;
    }
}