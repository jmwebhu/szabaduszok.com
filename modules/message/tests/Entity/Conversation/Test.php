<?php

class Entity_Conversation_Test extends Unittest_TestCase
{
    static protected $_users = [];

    /**
     * @covers Entity_Conversation::submit()
     */
    public function testSubmit()
    {
        $data = [
            'users' => [self::$_users[0]->user_id, self::$_users[1]->user_id]
        ];

        $conversation = new Entity_Conversation();
        $conversation->submit($data);

        $this->assertNotEmpty($conversation->getConversationId());
        $this->assertEquals(self::$_users[0]->getName() . ', ' . self::$_users[1]->getName(), $conversation->getName());
        $this->assertNotEmpty($conversation->getSlug());
        $this->assertNotEmpty($conversation->getCreatedAt());
        $this->assertConversationUsersExist($conversation->getConversationId(), $data['users']);

        $conversation->delete();
    }

    protected function assertConversationUsersExist($conversationId, array $userIds)
    {
        $conversationUsers = DB::select()
            ->from('conversations_users')
            ->where('conversation_id', '=', $conversationId)
            ->execute()->as_array();

        $this->assertEquals(count($userIds), count($conversationUsers));

        $ids = [];
        foreach ($conversationUsers as $conversationUser) {
            $ids[] = $conversationUser['user_id'];
        }

        foreach ($userIds as $userId) {
            $this->assertTrue(in_array($userId, $ids));
        }
    }

    public static function setUpBeforeClass()
    {
        self::setUpUsers();
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
}