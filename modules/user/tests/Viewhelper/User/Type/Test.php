<?php

class Viewhelper_User_Type_Test extends Unittest_TestCase
{
    /**
     * @var Entity_User_Freelancer
     */
    private static $_freelancer;

    /**
     * @var Entity_User_Employer
     */
    private static $_employer;

    /**
     * @covers Viewhelper_User_Type::hasPicture()
     */
    public function testHasPictureFreelancerNotOk()
    {
        $type   = new Viewhelper_User_Type_Freelancer_Create();
        $type->setUser(self::$_freelancer);

        $this->assertFalse($type->hasPicture());
    }

    /**
     * @covers Viewhelper_User_Type::hasPicture()
     */
    public function testHasPictureFreelancerOk()
    {
        self::$_freelancer->setProfilePicturePath('szabaduszok-freelancer-1-pic.jpg');
        $type   = new Viewhelper_User_Type_Freelancer_Create();
        $type->setUser(self::$_freelancer);

        $this->assertTrue($type->hasPicture());
    }

    /**
     * @covers Viewhelper_User_Type::hasPicture()
     */
    public function testHasPictureEmployerNotOk()
    {
        $type   = new Viewhelper_User_Type_Employer_Create();
        $type->setUser(self::$_employer);

        $this->assertFalse($type->hasPicture());
    }

    /**
     * @covers Viewhelper_User_Type::hasPicture()
     */
    public function testHasPictureEmployerOk()
    {
        self::$_employer->setProfilePicturePath('szabaduszok-employer-1-pic.jpg');
        $type   = new Viewhelper_User_Type_Freelancer_Create();
        $type->setUser(self::$_employer);

        $this->assertTrue($type->hasPicture());
    }

    public static function setUpBeforeClass()
    {
        $freelancer = new Model_User_Freelancer();
        $freelancer->lastname = 'Teszt';
        $freelancer->firstname = 'Szabadúszó';
        $freelancer->email = uniqid() . '@szabaduszok.com';
        $freelancer->password = 'password123';
        $freelancer->min_net_hourly_wage = '2500';

        $freelancer->save();
        self::$_freelancer = Entity_User::createUser(Entity_User::TYPE_FREELANCER, $freelancer);

        $employer = new Model_User_Employer();
        $employer->lastname = 'Teszt';
        $employer->firstname = 'Megbízó';
        $employer->email = uniqid() . '@szabaduszok.com';
        $employer->phonenumber = '063012345678';
        $employer->password = 'password123';
        $employer->address_postal_code = '9700';
        $employer->address_city = 'Szombathely';
        
        $employer->save();
        self::$_employer = Entity_User::createUser(Entity_User::TYPE_EMPLOYER, $employer);
    }

    public static function tearDownAfterClass()
    {
        self::$_freelancer->getModel()->delete();
        self::$_employer->getModel()->delete();
    }
}