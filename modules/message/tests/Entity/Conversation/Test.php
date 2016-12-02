<?php

class Entity_Conversation_Test extends Unittest_TestCase
{
    protected static $_users = [];
    protected static $_conversations = [];

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

        self::$_conversations[] = $conversation;
    }

    /**
     * @covers Entity_Conversation::deleteConversation()
     */
    public function testDeleteConversation()
    {
        $conversation = $this->givenConversation();
        $conversation->deleteConversation(Entity_User::createUser(self::$_users[0]->type, self::$_users[0]));

        $this->assertConversationInteractionExists(
            $conversation->getConversationId(), self::$_users[0], ['is_deleted' => 1]);

        $this->assertConversationInteractionNotExists(
            $conversation->getConversationId(), self::$_users[1]);
    }

    private function givenConversation()
    {
        $data = [
            'users' => [self::$_users[0]->user_id, self::$_users[1]->user_id]
        ];

        $conversation = new Entity_Conversation();
        $conversation->submit($data);

        self::$_conversations[] = $conversation;

        return $conversation;
    }

    /**
     * @param int $conversationId
     * @param array $userIds
     */
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

    /**
     * @param int $conversationId
     * @param int $userId
     * @param array $flags
     */
    protected function assertConversationInteractionExists($conversationId, $userId, array $flags)
    {
        $conversationInteraction = DB::select()
            ->from('conversation_interactions')
            ->where('conversation_id', '=', $conversationId)
            ->and_where('user_id', '=', $userId)
            ->execute()->current();

        $this->assertNotEmpty($conversationInteraction['conversation_interaction_id']);
        $this->assertEquals(Arr::get($flags, 'is_deleted', 0), $conversationInteraction['is_deleted']);
    }

    /**
     * @param int $conversationId
     * @param int $userId
     */
    protected function assertConversationInteractionNotExists($conversationId, $userId)
    {
        $conversationInteraction = DB::select()
            ->from('conversation_interactions')
            ->where('conversation_id', '=', $conversationId)
            ->and_where('user_id', '=', $userId)
            ->execute()->current();

        $this->assertNull($conversationInteraction);
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