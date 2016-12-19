<?php

class Authorization_Conversation_Test extends Unittest_TestCase
{
    protected static $_users = [];
    protected static $_conversations = [];

    /**
     * @covers Authorization_Conversation::canView()
     */
    public function testCanViewOk()
    {
        $auth = new Authorization_Conversation(
            self::$_conversations['active'][0]->getModel(), self::$_users[0]);

        $this->assertTrue($auth->canView());

        $auth = new Authorization_Conversation(
            self::$_conversations['active'][0]->getModel(), self::$_users[1]);

        $this->assertTrue($auth->canView());

        $auth = new Authorization_Conversation(
            self::$_conversations['active'][1]->getModel(), self::$_users[0]);

        $this->assertTrue($auth->canView());

        $auth = new Authorization_Conversation(
            self::$_conversations['active'][1]->getModel(), self::$_users[2]);

        $this->assertTrue($auth->canView());
    }

    /**
     * @covers Authorization_Conversation::canView()
     */
    public function testCanViewNotOk()
    {
        $auth = new Authorization_Conversation(
            self::$_conversations['active'][0]->getModel(), self::$_users[2]);

        $this->assertFalse($auth->canView());

        $auth = new Authorization_Conversation(
            self::$_conversations['active'][0]->getModel(), self::$_users[3]);

        $this->assertFalse($auth->canView());

        $auth = new Authorization_Conversation(
            self::$_conversations['active'][1]->getModel(), self::$_users[1]);

        $this->assertFalse($auth->canView());

        $auth = new Authorization_Conversation(
            self::$_conversations['active'][1]->getModel(), self::$_users[3]);

        $this->assertFalse($auth->canView());
    }

    /**
     * @covers Authorization_Conversation::canView()
     */
    public function testCanSendMessageOk()
    {
        $auth = new Authorization_Conversation(
            self::$_conversations['active'][0]->getModel(), self::$_users[0]);

        $this->assertTrue($auth->canSendMessage());

        $auth = new Authorization_Conversation(
            self::$_conversations['active'][0]->getModel(), self::$_users[1]);

        $this->assertTrue($auth->canSendMessage());

        $auth = new Authorization_Conversation(
            self::$_conversations['active'][1]->getModel(), self::$_users[0]);

        $this->assertTrue($auth->canSendMessage());

        $auth = new Authorization_Conversation(
            self::$_conversations['active'][1]->getModel(), self::$_users[2]);

        $this->assertTrue($auth->canSendMessage());
    }

    /**
     * @covers Authorization_Conversation::canView()
     */
    public function testCanSendMessageNotOk()
    {
        $auth = new Authorization_Conversation(
            self::$_conversations['active'][0]->getModel(), self::$_users[2]);

        $this->assertFalse($auth->canSendMessage());

        $auth = new Authorization_Conversation(
            self::$_conversations['active'][0]->getModel(), self::$_users[3]);

        $this->assertFalse($auth->canSendMessage());

        $auth = new Authorization_Conversation(
            self::$_conversations['active'][1]->getModel(), self::$_users[1]);

        $this->assertFalse($auth->canSendMessage());

        $auth = new Authorization_Conversation(
            self::$_conversations['active'][1]->getModel(), self::$_users[3]);

        $this->assertFalse($auth->canSendMessage());
    }

    /**
     * @covers Authorization_Conversation::canContact()
     */
    public function testCanContactOk()
    {
        $auth = new Authorization_Conversation(self::$_users[0], self::$_users[1]);
        $this->assertTrue($auth->canContact());

        $auth = new Authorization_Conversation(self::$_users[0], self::$_users[2]);
        $this->assertTrue($auth->canContact());

        $auth = new Authorization_Conversation(self::$_users[1], self::$_users[2]);
        $this->assertTrue($auth->canContact());
    }

    /**
     * @covers Authorization_Conversation::canContact()
     */
    public function testCanContactNotOk()
    {
        $auth = new Authorization_Conversation(self::$_users[0], self::$_users[0]);
        $this->assertFalse($auth->canContact());

        $auth = new Authorization_Conversation(self::$_users[1], self::$_users[1]);
        $this->assertFalse($auth->canContact());

        $auth = new Authorization_Conversation(self::$_users[2], self::$_users[2]);
        $this->assertFalse($auth->canContact());
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

        self::$_users[] = $freelancer;
        self::$_users[] = $employer;
        self::$_users[] = $employer1;
        self::$_users[] = $employer2;
        self::$_users[] = $employer3;
        self::$_users[] = $employer4;
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