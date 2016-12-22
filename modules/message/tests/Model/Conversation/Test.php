<?php

class Model_Conversation_Test extends Unittest_TestCase
{
    /**
     * @var Model_User[]
     */
    protected static $_users;

    /**
     * @var Entity_Conversation[]
     */
    protected static $_conversations;

    /**
     * @covers Model_Conversation::rules()
     */
    public function testRules()
    {
        $conversation   = new Model_Conversation();
        $rules          = $conversation->rules();

        $this->assertArrayHasKey('name', $rules);

        $this->assertTrue(in_array(['not_empty'], $rules['name']));
    }

    /**
     * @covers Model_Conversation::getParticipantsExcept()
     */
    public function testGetParticipantsExcept()
    {
       $conversation = self::$_conversations[0]->getModel();

       $participants = $conversation->getParticipantsExcept([self::$_users[0]->user_id]);
       $ids = $this->getParticipantIds($participants);
       $this->assertEquals([self::$_users[1]->user_id], $ids);

       $participants = $conversation->getParticipantsExcept([self::$_users[1]->user_id]);
       $ids = $this->getParticipantIds($participants);
       $this->assertEquals([self::$_users[0]->user_id], $ids);

       $participants = $conversation->getParticipantsExcept([self::$_users[0]->user_id, self::$_users[1]->user_id]);
       $this->assertEmpty($participants);

       $participants = $conversation->getParticipantsExcept([]);
       $this->assertEmpty($participants);
    }

    /**
     * @param  Entity_User[]  $participants
     * @return int[]
     */
    protected function getParticipantIds(array $participants)
    {
        $ids = [];
        foreach ($participants as $participant) {
            $ids[] = $participant->getId();
        }

        return $ids;
    }
    

    public static function setUpBeforeClass()
    {
        self::setUpUsers();
        self::setUpConversation();
    }

    protected static function setUpUsers()
    {
        $freelancer = new Model_User_Freelancer();
        $freelancer->lastname       = 'Joó';
        $freelancer->firstname      = 'Martin';
        $freelancer->email          = uniqid() . '@gmail.com';
        $freelancer->password       = 'Password123';
        $freelancer->min_net_hourly_wage       = '3000';
        $freelancer->type = Entity_User::TYPE_FREELANCER;

        $freelancer->save();

        $employer = new Model_User_Employer();
        $employer->lastname       = 'Kis';
        $employer->firstname      = 'Pista';
        $employer->address_postal_code      = '9700';
        $employer->address_city      = 'Szombathely';
        $employer->email          = uniqid() . '@gmail.com';
        $employer->phonenumber          = '06301923380';
        $employer->password       = 'Password123';
        $employer->type = Entity_User::TYPE_EMPLOYER;

        $employer->save();

        self::$_users[] = $freelancer;
        self::$_users[] = $employer;
    }

    protected static function setUpConversation()
    {
        $data = [
            'users' => [self::$_users[0]->user_id, self::$_users[1]->user_id]
        ];

        $conversation = new Entity_Conversation();
        $conversation->submit($data);

        self::$_conversations[] = $conversation;
    }

    public static function tearDownAfterClass()
    {
        foreach (self::$_users as $user) {
            DB::delete('users')->where('user_id', '=', $user->user_id)->execute();
        }

        foreach (self::$_conversations as $conversation) {
            DB::delete('conversations')->where('conversation_id', '=', $conversation->getConversationId())->execute();
        }
    }
}