<?php

class Authorization_User_Test extends Unittest_TestCase
{
    /**
     * @covers Authorization_User::hasCancel()
     */
    public function testHasCancel()
    {
        $user = new Model_User();
        $authorization = new Authorization_User($user);

        $this->assertFalse($authorization->hasCancel());
    }

    /**
 * @covers Authorization_User::canSeeProjectNotification()
 */
    public function testCanSeeProjectNotificationOk()
    {
        $subject = new Model_User();
        $subject->user_id = 12;

        $owner = new Model_User();
        $owner->user_id = 12;

        $authorization = new Authorization_User($subject, $owner);

        $this->assertTrue($authorization->canSeeProjectNotification());
    }

    /**
     * @covers Authorization_User::canSeeProjectNotification()
     */
    public function testCanSeeProjectNotificationNotOk()
    {
        $subject = new Model_User();
        $subject->user_id = 13;

        $owner = new Model_User();
        $owner->user_id = 12;

        $authorization = new Authorization_User($subject, $owner);

        $this->assertFalse($authorization->canSeeProjectNotification());
    }

    /**
     * @covers Authorization_User::canRate()
     */
    public function testCanRateSameTypeNotOk()
    {
        // Szabaduszo ertekel szabaduszot
        $rated = new Model_User_Freelancer();
        $rated->type = 1;
        $rated->user_id = 1;

        $rater = new Model_User_Freelancer();
        $rater->type = 1;
        $rater->user_id = 2;

        $authorization = new Authorization_User($rated, $rater);
        $this->assertFalse($authorization->canRate());

        // Megbizo ertekel megbizot
        $rated = new Model_User_Employer();
        $rated->type = 2;
        $rated->user_id = 1;

        $rater = new Model_User_Employer();
        $rater->type = 2;
        $rater->user_id = 2;

        $authorization = new Authorization_User($rated, $rater);
        $this->assertFalse($authorization->canRate());
    }

    /**
     * @covers Authorization_User::canRate()
     */
    public function testCanRateMyselfNotOk()
    {
        // Szabaduszo ertekeli sajat magat
        $rated = new Model_User_Freelancer();
        $rated->type = 1;
        $rated->user_id = 1;

        $authorization = new Authorization_User($rated, $rated);
        $this->assertFalse($authorization->canRate());

        // Megbizo ertekeli sajat magat
        $rated = new Model_User_Employer();
        $rated->type = 2;
        $rated->user_id = 1;

        $authorization = new Authorization_User($rated, $rated);
        $this->assertFalse($authorization->canRate());
    }

    /**
     * @covers Authorization_User::canRate()
     */
    public function testCanRateNotLoggedInNotOk()
    {
        $authMock = $this->getAuthMock(false);

        $rated = new Model_User_Freelancer();
        $rated->type = 1;
        $rated->user_id = 1;

        $rater = new Model_User_Employer();
        $rater->type = 2;
        $rater->user_id = 2;

        $authorization = new Authorization_User($rated, $rater);
        $this->assertFalse($authorization->canRate($authMock));
    }

    /**
     * @covers Authorization_User::canRate()
     */
    public function testCanRateOk()
    {
        $authMock = $this->getAuthMock(true);

        $rated = new Model_User_Freelancer();
        $rated->type = 1;
        $rated->user_id = 1;

        $rater = new Model_User_Employer();
        $rater->type = 2;
        $rater->user_id = 2;

        $authorization = new Authorization_User($rated, $rater);
        $this->assertTrue($authorization->canRate($authMock));
    }

    /**
     * @covers Authorization_User::canEdit()
     */
    public function testCanEditNotOk()
    {
        $subject = new Model_User_Freelancer();
        $subject->type = 1;
        $subject->user_id = 1;

        $editor = new Model_User_Freelancer();
        $editor->type = 1;
        $editor->user_id = 2;

        $authorization = new Authorization_User($subject, $editor);
        $this->assertFalse($authorization->canEdit());
    }

    /**
     * @covers Authorization_User::canEdit()
     */
    public function testCanEditOk()
    {
        $subject = new Model_User_Freelancer();
        $subject->type = 1;
        $subject->user_id = 1;

        $authorization = new Authorization_User($subject, $subject);
        $this->assertTrue($authorization->canEdit());
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function getAuthMock($return)
    {
        $config = ['session_type' => null];
        $authMock             = $this->getMockBuilder('\Auth')
            ->setConstructorArgs([$config])
            ->getMock();

        $authMock->expects($this->any())
            ->method('logged_in')
            ->will($this->returnValue($return));

        return $authMock;
    }
}