<?php

class Text_Conversation_Test extends Unittest_TestCase
{
    protected static $_users = [];

    /**
     * @covers Text_Conversation::getNameFromUsers()
     */
    public function testGetNameFromUsers()
    {
        $freelancer = new Entity_User_Freelancer(self::$_users[0]);
        $employer   = new Entity_User_Employer(self::$_users[1]);

        $users = [$freelancer, $employer];

        $name = Text_Conversation::getNameFromUsers($users);
        $this->assertEquals('Joó Martin, Kis Pista', $name);

        $users =  [$employer, $freelancer];
        $name = Text_Conversation::getNameFromUsers($users);
        $this->assertEquals('Kis Pista, Joó Martin', $name);
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

        $freelancer->save();

        $employer = new Model_User_Employer();
        $employer->lastname       = 'Kis';
        $employer->firstname      = 'Pista';
        $employer->address_postal_code      = '9700';
        $employer->address_city      = 'Szombathely';
        $employer->email          = uniqid() . '@gmail.com';
        $employer->phonenumber          = '06301923380';
        $employer->password       = 'Password123';

        $employer->save();

        self::$_users[] = $freelancer;
        self::$_users[] = $employer;
    }

    public static function tearDownAfterClass()
    {
        foreach (self::$_users as $user) {
            DB::delete('users')->where('user_id', '=', $user->user_id)->execute();
        }
    }
}