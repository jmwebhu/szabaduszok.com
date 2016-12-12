<?php

class Viewhelper_Conversation_Type_Group_Test extends Unittest_TestCase
{
    /**
     * @var Model_User
     */
    protected static $_freelancer;

    /**
     * @var Model_User
     */
    protected static $_freelancerOther;
    
    /**
     * @var Model_User
     */
    protected static $_employer;

    /**
     * @var Entity_Conversation
     */
    protected static $_conversation;

    /**
     * @covers Viewhelper_Conversation_Type_Single::getParticipantNames()
     */
    public function testGetParticipantNamesFromFreelancer()
    {
        $viewhelper = new Viewhelper_Conversation_Type_Group(
            self::$_conversation, self::$_freelancer);

        $name       = $viewhelper->getParticipantNames();
        $firstName  = $viewhelper->getParticipantNames('name');
        $lastName   = $viewhelper->getParticipantNames('lastName');
        
        $this->assertEquals('Béla, Imre', $name);
        $this->assertEquals('Megbízó Béla, Szabadúszó2 Imre', $firstName);
        $this->assertEquals('Megbízó, Szabadúszó2', $lastName);
    }

    /**
     * @covers Viewhelper_Conversation_Type_Single::getParticipantNames()
     */
    public function testGetParticipantNamesFromFreelancerOther()
    {
        $viewhelper = new Viewhelper_Conversation_Type_Group(
            self::$_conversation, self::$_freelancerOther);

        $name       = $viewhelper->getParticipantNames();
        $firstName  = $viewhelper->getParticipantNames('name');
        $lastName   = $viewhelper->getParticipantNames('lastName');
        
        $this->assertEquals('János, Béla', $name);
        $this->assertEquals('Szabadúszó János, Megbízó Béla', $firstName);
        $this->assertEquals('Szabadúszó, Megbízó', $lastName);
    }

    /**
     * @covers Viewhelper_Conversation_Type_Single::getParticipantNames()
     */
    public function testGetParticipantNamesFromEmployer()
    {
        $viewhelper = new Viewhelper_Conversation_Type_Group(
            self::$_conversation, self::$_employer);

        $name       = $viewhelper->getParticipantNames();
        $firstName  = $viewhelper->getParticipantNames('name');
        $lastName   = $viewhelper->getParticipantNames('lastName');
        
        $this->assertEquals('János, Imre', $name);
        $this->assertEquals('Szabadúszó János, Szabadúszó2 Imre', $firstName);
        $this->assertEquals('Szabadúszó, Szabadúszó2', $lastName);
    }

    public static function setUpBeforeClass()
    {
        $freelancer = new Model_User_Freelancer();
        $freelancer->lastname       = 'Szabadúszó';
        $freelancer->firstname      = 'János';
        $freelancer->email          = uniqid() . '@gmail.com';
        $freelancer->password       = 'Password123';
        $freelancer->min_net_hourly_wage       = '3000';
        $freelancer->type = Entity_User::TYPE_FREELANCER;

        $freelancer->save();

        $freelancerOther = new Model_User_Freelancer();
        $freelancerOther->lastname       = 'Szabadúszó2';
        $freelancerOther->firstname      = 'Imre';
        $freelancerOther->email          = uniqid() . '@gmail.com';
        $freelancerOther->password       = 'Password123';
        $freelancerOther->min_net_hourly_wage       = '3000';
        $freelancerOther->type = Entity_User::TYPE_FREELANCER;

        $freelancerOther->save();


        $employer = new Model_User_Employer();
        $employer->lastname       = 'Megbízó';
        $employer->firstname      = 'Béla';
        $employer->address_postal_code      = '9700';
        $employer->address_city      = 'Szombathely';
        $employer->email          = uniqid() . '@gmail.com';
        $employer->phonenumber          = '06301923380';
        $employer->password       = 'Password123';
        $employer->type = Entity_User::TYPE_EMPLOYER;

        $employer->save();    

        self::$_freelancer  = $freelancer;
        self::$_freelancerOther  = $freelancerOther;
        self::$_employer    = $employer;

        $data = [
            'users' => [$freelancer->user_id, $employer->user_id, $freelancerOther->user_id]
        ];

        $conversation = new Entity_Conversation();
        $conversation->submit($data);

        self::$_conversation = $conversation;
    }
 
    public static function tearDownAfterClass()
    {
        DB::delete('users')->where('user_id', '=', self::$_freelancer->user_id)->execute();
        DB::delete('users')->where('user_id', '=', self::$_freelancerOther->user_id)->execute();
        DB::delete('users')->where('user_id', '=', self::$_employer->user_id)->execute();
        DB::delete('conversations')->where('conversation_id', '=', self::$_conversation->getId())->execute();
    }  
}