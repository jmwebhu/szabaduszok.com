<?php

class Transaction_Conversation_Select_Test extends Unittest_TestCase
{
    protected static $_users = [];
    protected static $_conversations = [];
    protected static $_messages = [];

    /**
     * @covers Transaction_Conversation_Select::getForLeftPanelBy()
     */
    public function testGetForLeftPanelByOneDeleted()
    {
        $transaction    = new Transaction_Conversation_Select(new Model_Conversation());
        $conversations  = $transaction->getForLeftPanelBy(self::$_users[0]->user_id);

        $this->assertEquals(count(self::$_conversations['active']), count($conversations));
        $this->assertEquals('Joó Martin, Kis Pista', $conversations[0]->getName());
        $this->assertNotEmpty($conversations[0]->getSlug());

        $this->assertEquals('Joó Martin, Nagy Béla', $conversations[1]->getName());
        $this->assertNotEmpty($conversations[1]->getSlug());

        $this->assertUserHasInteraction(
            self::$_users[0]->user_id, self::$_conversations['deleted'][0]->getId(), ['is_deleted' => 1]);
    }

    /**
     * @covers Transaction_Conversation_Select::getForLeftPanelBy()
     */
    public function testGetForLeftPanelByAllActive()
    {
        $transaction    = new Transaction_Conversation_Select(new Model_Conversation());
        $conversations  = $transaction->getForLeftPanelBy(self::$_users[1]->user_id);

        $this->assertEquals(1, count($conversations));
        $this->assertEquals('Joó Martin, Kis Pista', $conversations[0]->getName());
        $this->assertNotEmpty($conversations[0]->getSlug());

        $this->assertUserHasNoInteraction(
            self::$_users[1]->user_id, self::$_conversations['active'][0]->getId());
    }

    /**
     * @covers Transaction_Conversation_Select::getForLeftPanelBy()
     */
    public function testGetForLeftPanelByAllDeletedReceiver()
    {
        $transaction    = new Transaction_Conversation_Select(new Model_Conversation());
        $conversations  = $transaction->getForLeftPanelBy(self::$_users[3]->user_id);

        $this->assertEquals(1, count($conversations));

        $this->assertUserHasNoInteraction(
            self::$_users[3]->user_id, self::$_conversations['deleted'][0]->getId());
    }

    /**
     * @covers Transaction_Conversation_Select::getForLeftPanelBy()
     */
    public function testGetForLeftPanelByAllDeletedSenderReceiver()
    {
        $transaction    = new Transaction_Conversation_Select(new Model_Conversation());
        $conversations  = $transaction->getForLeftPanelBy(self::$_users[3]->user_id);

        $this->assertEquals(1, count($conversations));

        $this->assertUserHasNoInteraction(
            self::$_users[3]->user_id, self::$_conversations['deleted'][0]->getId());

        $transaction->setConversation(new Model_Conversation());
        $conversations  = $transaction->getForLeftPanelBy(self::$_users[0]->user_id);
        $this->assertEquals(3, count($conversations));

        $this->assertUserHasInteraction(
            self::$_users[0]->user_id, self::$_conversations['deleted'][0]->getId(), ['is_deleted' => 1]);
    }

    /**
     * @param $userId
     * @param $conversationId
     * @param array $flags
     */
    protected function assertUserHasInteraction($userId, $conversationId, array $flags)
    {
        $interaction = DB::select()
            ->from('conversation_interactions')
            ->where('user_id', '=', $userId)
            ->and_where('conversation_id', '=', $conversationId)
            ->execute()->current();

        $this->assertNotNull($interaction);
        $this->assertNotEmpty($interaction['conversation_interaction_id']);
        $this->assertEquals(Arr::get($flags, 'is_deleted', 0), $interaction['is_deleted']);
    }

    /**
     * @param int $userId
     * @param int $conversationId
     */
    protected function assertUserHasNoInteraction($userId, $conversationId)
    {
        $interaction = DB::select()
            ->from('conversation_interactions')
            ->where('user_id', '=', $userId)
            ->and_where('conversation_id', '=', $conversationId)
            ->execute()->current();

        $this->assertNull($interaction);
    }

    public static function setUpBeforeClass()
    {
        self::setUpUsers();
        self::setUpConversations();
    }

    protected static function setUpConversations()
    {
        $data = [
            'users' => [self::$_users[0]->user_id, self::$_users[1]->user_id]
        ];

        $conversation = new Entity_Conversation();
        $conversation->submit($data);

        $data = [
            'users' => [self::$_users[0]->user_id, self::$_users[2]->user_id]
        ];

        $conversation1 = new Entity_Conversation();
        $conversation1->submit($data);

        $data = [
            'users' => [self::$_users[0]->user_id, self::$_users[3]->user_id]
        ];

        $conversation2 = new Entity_Conversation();
        $conversation2->submit($data);

        $conversation2->deleteConversation(Entity_User::createUser(self::$_users[0]->type,self::$_users[0]));

        $data = [
            'users' => [self::$_users[0]->user_id, self::$_users[4]->user_id]
        ];

        $conversation3 = new Entity_Conversation();
        $conversation3->submit($data);

        self::$_conversations['active'][] = $conversation;
        self::$_conversations['active'][] = $conversation1;
        self::$_conversations['active'][] = $conversation3;
        self::$_conversations['deleted'][] = $conversation2;
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

        self::$_users[] = $freelancer;
        self::$_users[] = $employer;
        self::$_users[] = $employer1;
        self::$_users[] = $employer2;
        self::$_users[] = $employer3;
    }

    public static function tearDownAfterClass()
    {
        foreach (self::$_users as $user) {
            DB::delete('users')->where('user_id', '=', $user->user_id)->execute();
        }

        foreach (self::$_conversations as $array) {
            foreach ($array as $item) {
                DB::delete('conversations')->where('conversation_id', '=', $item->getId())->execute();
            }
        }
    }
}