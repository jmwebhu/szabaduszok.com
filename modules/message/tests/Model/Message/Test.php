<?php

class Model_Message_Test extends Unittest_TestCase
{
    /**
     * @var Entity_User_Freelancer
     */
    private static $_freelancer = null;

    /**
     * @var Entity_User_Employer
     */
    private static $_employer   = null;

    /**
     * @var Model_Message
     */
    private static $_message   = null;

    /**
     * @covers Model_Message::rules()
     */
    public function testRules()
    {
        $project    = new Model_Message();
        $rules      = $project->rules();

        $this->assertArrayHasKey('sender_id', $rules);
        $this->assertArrayHasKey('receiver_id', $rules);
        $this->assertArrayHasKey('message', $rules);

        $this->assertTrue(in_array(['not_empty'], $rules['sender_id']));
        $this->assertTrue(in_array(['not_empty'], $rules['receiver_id']));
        $this->assertTrue(in_array(['not_empty'], $rules['message']));
    }

    public function testGettersSetters()
    {
        $sender     = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $receiver   = Entity_User::createUser(Entity_User::TYPE_FREELANCER);

        $message    = new Model_Message(1);
        $message->setSender($sender);
        $message->setReceiver($receiver);
        $message->setMessage('Teszt');

        $this->assertNotEmpty($message->getSender());
        $this->assertNotEmpty($message->getReceiver());
        $this->assertEquals('Teszt', $message->getMessage());
        $this->assertNotEmpty($message->getData());
    }

    /**
     * @covers Model_Message::send()
     */
    public function testSend()
    {
        $message = new Model_Message();
        $message->setSender(self::$_employer);
        $message->setReceiver(self::$_freelancer);
        $message->setMessage('Hello, teszt');

        $message->send();
        self::$_message = $message;

        $this->assertNotificationExistsInDatabaseWith([
            'notifier_user_id'  => self::$_employer->getUserId(),
            'notified_user_id'  => self::$_freelancer->getUserId(),
            'subject_id'        => $message->getId(),
            'event_id'          => Model_Event::TYPE_MESSAGE_NEW
        ]);

        $this->assertMessageExistsInDatabaseWith([
            'sender_id'         => self::$_employer->getUserId(),
            'receiver_id'       => self::$_freelancer->getUserId(),
            'message'           => $message->message
        ]);
    }

    /**
     * @param array $data
     */
    protected function assertNotificationExistsInDatabaseWith(array $data)
    {
        $notification = DB::select()
            ->from('notifications')
            ->where('notifier_user_id', '=', $data['notifier_user_id'])
            ->where('notified_user_id', '=', $data['notified_user_id'])
            ->where('subject_id', '=', $data['subject_id'])
            ->where('event_id', '=', $data['event_id'])
            ->limit(1)
            ->execute()->current();

        $this->assertNotNull($notification['notification_id']);
        $this->assertNotEmpty($notification['notification_id']);
        $this->assertTrue($notification['is_archived'] != 1);
        $this->assertContains('message', $notification['extra_data_json']);
    }

    /**
     * @param array $data
     */
    protected function assertMessageExistsInDatabaseWith(array $data)
    {
        $message = DB::select()
            ->from('messages')
            ->where('sender_id', '=', $data['sender_id'])
            ->where('receiver_id', '=', $data['receiver_id'])
            ->limit(1)
            ->execute()->current();

        $this->assertNotNull($message['message_id']);
        $this->assertNotEmpty($message['message_id']);
        $this->assertEquals($data['message'], $message['message']);
    }

    public static function setUpBeforeClass()
    {
        Cache::instance()->delete_all();
        $freelancerData = [
            'firstname'             => 'Web',
            'lastname'              => 'Józsi',
            'email'                 => uniqid() . '@szabaduszok.com',
            'password'              => 'asdfasdf123',
            'password_confirm'      => 'asdfasdf123',
            'address_postal_code'   => '9700',
            'address_city'          => 'Szombathely',
            'min_net_hourly_wage'   => 3000,
        ];

        $freelancer = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $freelancer->submitUser($freelancerData);

        $employerData = [
            'firstname'             => 'Martin',
            'lastname'              => 'Joó',
            'email'                 => uniqid() . '@szabaduszok.com',
            'password'              => 'asdfasdf123',
            'password_confirm'      => 'asdfasdf123',
            'address_postal_code'   => '9700',
            'address_city'          => 'Szombathely',
            'phonenumber'           => '06301923380'
        ];

        $employer = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $employer->submitUser($employerData);

        self::$_freelancer  = $freelancer;
        self::$_employer    = $employer;
    }

    public static function tearDownAfterClass()
    {
        self::$_employer->delete();
        self::$_freelancer->delete();
        self::$_message->delete();
    }
}