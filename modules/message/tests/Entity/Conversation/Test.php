<?php

class Entity_Conversation_Test extends Unittest_TestCase
{
    protected static $_users = [];
    protected static $_conversations = [];

    protected static $_usersForLeftPanelTest = [];
    protected static $_conversationsForLeftPanelTest = [];
    
    protected static $_messages = [];

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

    /**
     * @covers Entity_Conversation::GetOrCreateWithUsersBy()
     */
    public function testGetOrCreateWithUsersByGet()
    {
        $conversationGiven      = $this->givenConversation();
        $conversationCreated    = Entity_Conversation::getOrCreateWithUsersBy(
            $conversationGiven->getConversationId(), [self::$_users[0]->user_id, self::$_users[1]->user_id]);

        $this->assertEquals($conversationCreated->getConversationId(), $conversationGiven->getConversationId());
    }

    /**
     * @covers Entity_Conversation::GetOrCreateWithUsersBy()
     */
    public function testGetOrCreateWithUsersByCreate()
    {
        $conversationCreated    = Entity_Conversation::getOrCreateWithUsersBy(
            '', [self::$_users[0]->user_id, self::$_users[1]->user_id]);

        $this->assertNotEmpty($conversationCreated->getConversationId());

        self::$_conversations[] = $conversationCreated;
    }

    /**
     * @covers Entity_Conversation::getConversationBetween()
     */
    public function testGetConversationBetweenAlreadyExists()
    {
        foreach (self::$_conversations as $conversation) {
            DB::delete('conversations')->where('conversation_id', '=', $conversation->getConversationId())->execute();
        }

        $conversationGiven = $this->givenConversation();
        $conversationBetween = Entity_Conversation::getConversationBetween([self::$_users[0]->user_id, self::$_users[1]->user_id]);

        $this->assertNotEmpty($conversationBetween->getConversationId());
        $this->assertEquals($conversationGiven->getConversationId(), $conversationBetween->getConversationId());
    }
    
    /**
     * @covers Entity_Conversation::getConversationBetween()
     */
    public function testGetConversationBetweenNotExists()
    {
        foreach (self::$_conversations as $conversation) {
            DB::delete('conversations')->where('conversation_id', '=', $conversation->getConversationId())->execute();
        }

        $conversationBetween = Entity_Conversation::getConversationBetween([self::$_users[0]->user_id, self::$_users[1]->user_id]);

        $this->assertNotEmpty($conversationBetween->getConversationId());

        $ids = [];
        foreach (self::$_conversations as $conversation) {
            $ids[] = $conversation->getConversationId();
        }

        $this->assertNotInArray($conversationBetween->getConversationId(), $ids);

        self::$_conversations[] = $conversationBetween;
    }

    /**
     * @covers Entity_Conversation::getForLeftPanelBy()
     */
    public function testGetForLeftPanelByOneDeleted()
    {
        $conversations  = Entity_Conversation::getForLeftPanelBy(self::$_usersForLeftPanelTest[0]->user_id);

        $this->assertEquals(count(self::$_conversationsForLeftPanelTest['active']), count($conversations));
        $this->assertEquals('Joó Martin, Kis Pista', $conversations[0]->getName());
        $this->assertNotEmpty($conversations[0]->getSlug());

        $this->assertEquals('Joó Martin, Nagy Béla', $conversations[1]->getName());
        $this->assertNotEmpty($conversations[1]->getSlug());

        $this->assertUserHasConversationInteraction(
            self::$_usersForLeftPanelTest[0]->user_id, self::$_conversationsForLeftPanelTest['deleted'][0]->getId(), ['is_deleted' => 1]);
    }

    /**
     * @covers Entity_Conversation::getForLeftPanelBy()
     */
    public function testGetForLeftPanelByAllActive()
    {
        $conversations  = Entity_Conversation::getForLeftPanelBy(self::$_usersForLeftPanelTest[1]->user_id);

        $this->assertEquals(1, count($conversations));
        $this->assertEquals('Joó Martin, Kis Pista', $conversations[0]->getName());
        $this->assertNotEmpty($conversations[0]->getSlug());

        $this->assertUserHasNoConversationInteraction(
            self::$_usersForLeftPanelTest[1]->user_id, self::$_conversationsForLeftPanelTest['active'][0]->getId());
    }

    /**
     * @covers Entity_Conversation::getForLeftPanelBy()
     */
    public function testGetForLeftPanelByAllDeletedReceiver()
    {
        $conversations  = Entity_Conversation::getForLeftPanelBy(self::$_usersForLeftPanelTest[3]->user_id);

        $this->assertEquals(1, count($conversations));

        $this->assertUserHasNoConversationInteraction(
            self::$_usersForLeftPanelTest[3]->user_id, self::$_conversationsForLeftPanelTest['deleted'][0]->getId());
    }

    /**
     * @covers Entity_Conversation::getForLeftPanelBy()
     */
    public function testGetForLeftPanelByAllDeletedSenderReceiver()
    {
        $conversations  = Entity_Conversation::getForLeftPanelBy(self::$_usersForLeftPanelTest[3]->user_id);

        $this->assertEquals(1, count($conversations));

        $this->assertUserHasNoConversationInteraction(
            self::$_usersForLeftPanelTest[3]->user_id, self::$_conversationsForLeftPanelTest['deleted'][0]->getId());

        $conversations  = Entity_Conversation::getForLeftPanelBy(self::$_usersForLeftPanelTest[0]->user_id);
        $this->assertEquals(3, count($conversations));

        $this->assertUserHasConversationInteraction(
            self::$_usersForLeftPanelTest[0]->user_id, self::$_conversationsForLeftPanelTest['deleted'][0]->getId(), ['is_deleted' => 1]);
    }

    /**
     * @covers Entity_Conversation::getMessagesBy()
     */
    public function testGetMessagesBy()
    {
        $this->givenMessages();
        $entity         = self::$_conversationsForLeftPanelTest['active'][0];
        $messages       = $entity->getMessagesBy(self::$_usersForLeftPanelTest[0]->user_id);

        $expectedIds    = [self::$_messages[0]->getId(), self::$_messages[1]->getId(), self::$_messages[2]->getId()];
        $actualIds      = [];

        foreach ($messages as $message) {
            $actualIds[] = $message->getId();
        }

        $this->assertEquals(3, count($actualIds));
        $this->assertEquals($expectedIds, $actualIds);
        $this->assertNotInArray(self::$_messages[3]->getId(), $actualIds);
    }

    /**
     * @covers Entity_Conversation::getMessagesByConversationsAndUser()
     */
    public function testGetMessagesByConversationsAndUser()
    {
        $this->givenMessages();
        $conversations = [
            self::$_conversationsForLeftPanelTest['active'][0], 
            self::$_conversationsForLeftPanelTest['active'][1]
        ];

        $messages = Entity_Conversation::getMessagesByConversationsAndUser($conversations, self::$_usersForLeftPanelTest[0]->getId());

        $this->assertInArray(self::$_conversationsForLeftPanelTest['active'][0]->getId(), array_keys($messages));
        $this->assertInArray(self::$_conversationsForLeftPanelTest['active'][1]->getId(), array_keys($messages));
        $this->assertEquals(2, count(array_keys($messages)));

        $this->assertEquals(3, count($messages[self::$_conversationsForLeftPanelTest['active'][0]->getId()]));
        $this->assertEquals(1, count($messages[self::$_conversationsForLeftPanelTest['active'][1]->getId()]));

        $messageIds = [
            self::$_messages[0]->getId(), self::$_messages[1]->getId(), self::$_messages[2]->getId(),
            self::$_messages[3]->getId()
        ];

        foreach ($messages[self::$_conversationsForLeftPanelTest['active'][0]->getId()] as $array) {
            foreach ($array as $message) {
                $this->assertInArray($message->getId(), $messageIds[0]);
            }
        }

        foreach ($messages[self::$_conversationsForLeftPanelTest['active'][1]->getId()] as $array) {
            foreach ($array as $message) {
                $this->assertInArray($message->getId(), $messageIds[1]);
            }
        }
    }

    protected function givenMessages()
    {
        $data = [
            'conversation_id'   => self::$_conversationsForLeftPanelTest['active'][0]->getId(),
            'sender_id'         => self::$_usersForLeftPanelTest[0]->user_id,
            'message'           => 'Első'
        ];   

        $message = new Entity_Message;
        $message->send($data);

        $data = [
            'conversation_id'   => self::$_conversationsForLeftPanelTest['active'][0]->getId(),
            'sender_id'         => self::$_usersForLeftPanelTest[1]->user_id,
            'message'           => 'Második'
        ];   

        $message1 = new Entity_Message;
        $message1->send($data);

        $data = [
            'conversation_id'   => self::$_conversationsForLeftPanelTest['active'][0]->getId(),
            'sender_id'         => self::$_usersForLeftPanelTest[1]->user_id,
            'message'           => 'Harmadik'
        ];   

        $message2 = new Entity_Message;
        $message2->send($data);

        $data = [
            'conversation_id'   => self::$_conversationsForLeftPanelTest['active'][1]->getId(),
            'sender_id'         => self::$_usersForLeftPanelTest[0]->user_id,
            'message'           => 'Másik beszélgetés'
        ];   

        $message3 = new Entity_Message;
        $message3->send($data);

        self::$_messages = [$message, $message1, $message2, $message3];
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

        $employer1 = new Model_User_Employer();
        $employer1->lastname       = 'Nagy';
        $employer1->firstname      = 'Béla';
        $employer1->address_postal_code      = '9700';
        $employer1->address_city      = 'Szombathely';
        $employer1->email          = uniqid() . '@gmail.com';
        $employer1->phonenumber          = '06301923380';
        $employer1->password       = 'Password123';
        $employer1->type = Entity_User::TYPE_EMPLOYER;

        $employer1->save();

        $employer2 = new Model_User_Employer();
        $employer2->lastname       = 'Horváth';
        $employer2->firstname      = 'Péter';
        $employer2->address_postal_code      = '9700';
        $employer2->address_city      = 'Szombathely';
        $employer2->email          = uniqid() . '@gmail.com';
        $employer2->phonenumber          = '06301923380';
        $employer2->password       = 'Password123';
        $employer2->type = Entity_User::TYPE_EMPLOYER;

        $employer2->save();

        $employer3 = new Model_User_Employer();
        $employer3->lastname       = 'Lukács';
        $employer3->firstname      = 'Laci';
        $employer3->address_postal_code      = '9700';
        $employer3->address_city      = 'Szombathely';
        $employer3->email          = uniqid() . '@gmail.com';
        $employer3->phonenumber          = '06301923380';
        $employer3->password       = 'Password123';
        $employer3->type = Entity_User::TYPE_EMPLOYER;

        $employer3->save();

        $employer4 = new Model_User_Employer();
        $employer4->lastname       = 'Lukács';
        $employer4->firstname      = 'Laci';
        $employer4->address_postal_code      = '9700';
        $employer4->address_city      = 'Szombathely';
        $employer4->email          = uniqid() . '@gmail.com';
        $employer4->phonenumber          = '06301923380';
        $employer4->password       = 'Password123';
        $employer4->type = Entity_User::TYPE_EMPLOYER;

        $employer4->save();

        self::$_usersForLeftPanelTest[] = $freelancer;
        self::$_usersForLeftPanelTest[] = $employer;
        self::$_usersForLeftPanelTest[] = $employer1;
        self::$_usersForLeftPanelTest[] = $employer2;
        self::$_usersForLeftPanelTest[] = $employer3;
        self::$_usersForLeftPanelTest[] = $employer4;

        $data = [
            'users' => [self::$_usersForLeftPanelTest[0]->user_id, self::$_usersForLeftPanelTest[1]->user_id]
        ];

        $conversation = new Entity_Conversation();
        $conversation->submit($data);

        $data = [
            'users' => [self::$_usersForLeftPanelTest[0]->user_id, self::$_usersForLeftPanelTest[2]->user_id]
        ];

        $conversation1 = new Entity_Conversation();
        $conversation1->submit($data);

        $data = [
            'users' => [self::$_usersForLeftPanelTest[0]->user_id, self::$_usersForLeftPanelTest[3]->user_id]
        ];

        $conversation2 = new Entity_Conversation();
        $conversation2->submit($data);

        $conversation2->deleteConversation(Entity_User::createUser(self::$_usersForLeftPanelTest[0]->type,self::$_usersForLeftPanelTest[0]));

        $data = [
            'users' => [self::$_usersForLeftPanelTest[0]->user_id, self::$_usersForLeftPanelTest[4]->user_id]
        ];

        $conversation3 = new Entity_Conversation();
        $conversation3->submit($data);

        self::$_conversationsForLeftPanelTest['active'][] = $conversation;
        self::$_conversationsForLeftPanelTest['active'][] = $conversation1;
        self::$_conversationsForLeftPanelTest['active'][] = $conversation3;
        self::$_conversationsForLeftPanelTest['deleted'][] = $conversation2;
    }

    public static function tearDownAfterClass()
    {
        foreach (self::$_users as $user) {
            DB::delete('users')->where('user_id', '=', $user->user_id)->execute();
        }

        foreach (self::$_usersForLeftPanelTest as $user) {
            DB::delete('users')->where('user_id', '=', $user->user_id)->execute();
        }

        foreach (self::$_conversations as $conversation) {
            DB::delete('conversations')->where('conversation_id', '=', $conversation->getConversationId())->execute();
        }

        foreach (self::$_conversationsForLeftPanelTest as $array) {
            foreach ($array as $item) {
                DB::delete('conversations')->where('conversation_id', '=', $item->getId())->execute();
            }
        }

        foreach (self::$_messages as $message) {
            DB::delete('messages')->where('message_id', '=', $message->getId())->execute();
        }
    }

    private function givenConversation($userIndex = 0, $otherUserIndex = 1)
    {
        $data = [
            'users' => [self::$_users[$userIndex]->user_id, self::$_users[$otherUserIndex]->user_id]
        ];

        $conversation = new Entity_Conversation();
        $conversation->submit($data);

        self::$_conversations[] = $conversation;

        return $conversation;
    }
}