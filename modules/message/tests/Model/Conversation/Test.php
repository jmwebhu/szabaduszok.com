<?php

class Model_Conversation_Test extends Unittest_TestCase
{
    protected static $_users = [];
    protected static $_conversations = [];

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
     * @covers Model_Conversation::getForLeftPanelBy()
     */
    public function testGetForLeftPanelByAllActive()
    {
        $model          = new Model_Conversation();
        $conversations  = $model->getForLeftPanelBy(self::$_users[0]->user_id);

        $this->assertEquals(2, count($conversations));
        $this->assertEquals('Joó Martin, Kis Pista', $conversations[0]->getName());
        $this->assertEquals('joo-martin-kis-pista', $conversations[0]->getSlug());

        $this->assertEquals('Joó Martin, Nagy Béla', $conversations[1]->getName());
        $this->assertEquals('joo-martin-nagy-bela', $conversations[1]->getSlug());
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

        self::$_conversations[] = $conversation;
        self::$_conversations[] = $conversation1;
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

        self::$_users[] = $freelancer;
        self::$_users[] = $employer;
        self::$_users[] = $employer1;
    }

    public static function tearDownAfterClass()
    {
        foreach (self::$_users as $user) {
            DB::delete('users')->where('user_id', '=', $user->user_id)->execute();
        }

        foreach (self::$_conversations as $conversation) {
            DB::delete('conversations')->where('conversation_id', '=', $conversation->getId())->execute();
        }
    }
}