<?php

class Viewhelper_Conversation_Type_Single_Test extends Unittest_TestCase
{
    /**
     * @var Model_User
     */
    protected static $_freelancer;
    
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
        $viewhelper = new Viewhelper_Conversation_Type_Single(
            self::$_conversation, self::$_freelancer);

        $name       = $viewhelper->getParticipantNames();
        $firstName  = $viewhelper->getParticipantNames('firstName');
        $lastName   = $viewhelper->getParticipantNames('lastName');
        
        $this->assertEquals('Megbízó Béla', $name);
        $this->assertEquals('Béla', $firstName);
        $this->assertEquals('Megbízó', $lastName);
    }

    /**
     * @covers Viewhelper_Conversation_Type_Single::getParticipantNames()
     */
    public function testGetParticipantNamesFromEmployer()
    {
        $viewhelper = new Viewhelper_Conversation_Type_Single(
            self::$_conversation, self::$_employer);

        $name       = $viewhelper->getParticipantNames();
        $firstName  = $viewhelper->getParticipantNames('firstName');
        $lastName   = $viewhelper->getParticipantNames('lastName');
        
        $this->assertEquals('Szabadúszó János', $name);
        $this->assertEquals('János', $firstName);
        $this->assertEquals('Szabadúszó', $lastName);
    }

    /**
     * @covers Viewhelper_Conversation_Type_Single::getParticipantProfilePictures()
     */
    public function testGetParticipantProfilePicturesFromFreelancer()
    {
        $viewhelper = new Viewhelper_Conversation_Type_Single(
            self::$_conversation, self::$_freelancer);

        $pictures = $viewhelper->getParticipantProfilePictures();
        
        $this->assertEquals('megbizo-bela-list.jpg', $pictures[0]);
    }

    /**
     * @covers Viewhelper_Conversation_Type_Single::getParticipantProfilePictures()
     */
    public function testGetParticipantProfilePicturesFromEmployer()
    {
        $viewhelper = new Viewhelper_Conversation_Type_Single(
            self::$_conversation, self::$_employer);

        $pictures = $viewhelper->getParticipantProfilePictures();
        
        $this->assertEquals('szabaduszo-janos-list.jpg', $pictures[0]);
    }

    public static function setUpBeforeClass()
    {
        $freelancer = new Model_User_Freelancer();
        $freelancer->lastname       = 'Szabadúszó';
        $freelancer->firstname      = 'János';
        $freelancer->email          = uniqid() . '@gmail.com';
        $freelancer->password       = 'Password123';
        $freelancer->min_net_hourly_wage       = '3000';
        $freelancer->list_picture_path  = 'szabaduszo-janos-list.jpg';
        $freelancer->type = Entity_User::TYPE_FREELANCER;

        $freelancer->save();


        $employer = new Model_User_Employer();
        $employer->lastname       = 'Megbízó';
        $employer->firstname      = 'Béla';
        $employer->address_postal_code      = '9700';
        $employer->address_city      = 'Szombathely';
        $employer->email          = uniqid() . '@gmail.com';
        $employer->phonenumber          = '06301923380';
        $employer->password       = 'Password123';
        $employer->list_picture_path  = 'megbizo-bela-list.jpg';
        $employer->type = Entity_User::TYPE_EMPLOYER;

        $employer->save();    

        self::$_freelancer  = $freelancer;
        self::$_employer    = $employer;

        $data = [
            'users' => [$freelancer->user_id, $employer->user_id]
        ];

        $conversation = new Entity_Conversation();
        $conversation->submit($data);

        self::$_conversation = $conversation;
    }
 
    public static function tearDownAfterClass()
    {
        DB::delete('users')->where('user_id', '=', self::$_freelancer->user_id)->execute();
        DB::delete('users')->where('user_id', '=', self::$_employer->user_id)->execute();
        DB::delete('conversations')->where('conversation_id', '=', self::$_conversation->getId())->execute();
    }  
}