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
        $freelancer->user_id = 1999;
        $freelancer->lastname = 'Teszt';
        $freelancer->firstname = 'Szabadúszó';
        $freelancer->email = 'teszt' . $freelancer->user_id . '@szabaduszok.com';
        $freelancer->password = 'pass';
        $freelancer->min_net_hourly_wage = '2500';
        $freelancer->save();

        self::$_freelancer = Entity_User::createUser(Entity_User::TYPE_FREELANCER, $freelancer);

        $employer = new Model_User_Freelancer();
        $employer->user_id = 2000;
        $employer->lastname = 'Teszt';
        $employer->firstname = 'Megbízó';
        $employer->email = 'teszt' . $employer->user_id . '@szabaduszok.com';
        $employer->phonenumber = '063012345678';
        $employer->password = 'pass';
        $employer->address_postal_code = '9700';
        $employer->address_city = 'Szombathely';
        $employer->save();

        self::$_employer = Entity_User::createUser(Entity_User::TYPE_EMPLOYER, $employer);
    }

    public static function tearDownAfterClass()
    {
        $freelancer = new Model_User_Freelancer(1999);
        $freelancer->delete();

        $freelancer = new Model_User_Employer(2000);
        $freelancer->delete();
    }
}