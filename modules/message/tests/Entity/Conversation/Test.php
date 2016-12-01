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

        ];
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
}