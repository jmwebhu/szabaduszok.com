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
     * @covers Authorization_User::canEdit()
     */
    public function testCanEditOk()
    {
        $subject = new Model_User();
        $subject->user_id = 12;

        $owner = new Model_User();
        $owner->user_id = 12;

        $authorization = new Authorization_User($subject, $owner);

        $this->assertTrue($authorization->canSeeProjectNotification());
    }

    /**
     * @covers Authorization_User::canEdit()
     */
    public function testCanEditNotOk()
    {
        $subject = new Model_User();
        $subject->user_id = 13;

        $owner = new Model_User();
        $owner->user_id = 12;

        $authorization = new Authorization_User($subject, $owner);

        $this->assertFalse($authorization->canSeeProjectNotification());
    }
}