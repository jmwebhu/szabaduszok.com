<?php

class Model_Message_Interaction_Test extends Unittest_TestCase
{
    protected static $_users                = [];
    protected static $_conversations        = [];
    protected static $_messages             = [];
    protected static $_messageInteractions  = [];

    /**
     * @covers Model_Message_Interaction::rules()
     */
    public function testRules()
    {
        $interaction   = new Model_Message_Interaction();
        $rules          = $interaction->rules();

        $this->assertArrayHasKey('message_id', $rules);
        $this->assertArrayHasKey('user_id', $rules);

        $this->assertTrue(in_array(['not_empty'], $rules['message_id']));
        $this->assertTrue(in_array(['not_empty'], $rules['user_id']));
    }

    /**
     * @covers Model_Message_Interaction::getByMessageAndUser()
     */
    public function testGetByMessageAndUserSender()
    {
        $interaction = Model_Message_Interaction::getByMessageAndUser(self::$_messages[0]->getId(), self::$_users[0]->getId());

        $this->assertEquals(1, $interaction->is_readed);
        $this->assertEquals(0, $interaction->is_deleted);
    }

    /**
     * @covers Model_Message_Interaction::getByMessageAndUser()
     */
    public function testGetByMessageAndUserReceiver()
    {
        $interaction = Model_Message_Interaction::getByMessageAndUser(self::$_messages[0]->getId(), self::$_users[1]->getId());

        $this->assertEquals(0, $interaction->is_readed);
        $this->assertEquals(0, $interaction->is_deleted);
    }

    /**
     * @covers Model_Message_Interaction::getAllByMessageExceptGivenUser()
     */
    public function testGetAllByMessageExceptGivenUser()
    {
        $interactions = Model_Message_Interaction::getAllByMessageExceptGivenUser(self::$_messages[0]->getId(), self::$_users[0]->getId());

        $this->assertEquals(1, count($interactions));

        $interaction = $interactions[0];

        $this->assertEquals(self::$_users[1], $interaction->user_id);
        $this->assertEquals(0, $interaction->is_readed);
        $this->assertEquals(0, $interaction->is_deleted);
    }

    public static function setUpBeforeClass()
    {
        self::setUpUsers();
        self::setUpConversations();
        self::setUpMessages();
    }

    public static function tearDownAfterClass()
    {
        foreach (self::$_messages as $message) {
            $message->getModel()->delete();
        }

        foreach (self::$_users as $user) {
            DB::delete('users')->where('user_id', '=', $user->user_id)->execute();
        }

        foreach (self::$_conversations as $conversation) {
            $conversation->getModel()->delete();
        }

        foreach (self::$_messageInteractions as $message) {
            $message->getModel()->delete();
        }
    }

    protected static function setUpUsers()
    {
        $freelancer = new Model_User_Freelancer();
        $freelancer->lastname       = 'Joó';
        $freelancer->firstname      = 'Martin';
        $freelancer->email          = uniqid() . '@gmail.com';
        $freelancer->password       = 'Password123';
        $freelancer->min_net_hourly_wage       = '3000';

        $freelancer->save();

        $employer = new Model_User_Employer();
        $employer->lastname       = 'Joó';
        $employer->firstname      = 'Martin';
        $employer->address_postal_code      = '9700';
        $employer->address_city      = 'Szombathely';
        $employer->email          = uniqid() . '@gmail.com';
        $employer->phonenumber          = '06301923380';
        $employer->password       = 'Password123';

        $employer->save();

        self::$_users[] = $freelancer;
        self::$_users[] = $employer;
    }

    protected static function setUpConversations()
    {
        $data = [
            'name'  => 'fasdfdfsd',
            'users' => [self::$_users[0]->user_id, self::$_users[1]->user_id]
        ];

        $entity = new Entity_Conversation();
        $entity->submit($data);

        self::$_conversations[] = $entity;
    }

    protected static function setUpMessages()
    {
        $data = [
            'conversation_id'   => self::$_conversations[0]->getId(),
            'sender_id'         => self::$_users[0]->user_id,
            'message'           => 'Teszt'
        ];

        $entity = new Entity_Message();
        $entity->submit($data);

        self::$_messages[] = $entity;
    }
}