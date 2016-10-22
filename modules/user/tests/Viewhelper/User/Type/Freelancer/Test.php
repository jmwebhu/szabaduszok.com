<?php

class Viewhelper_User_Type_Freelancer_Test extends Unittest_TestCase
{
    /**
     * @var Entity_User_Freelancer
     */
    private static $_freelancer;

    /**
     * @covers Viewhelper_User_Type_Freelancer::getEditUrl()
     */
    public function testGetEditUrl()
    {
        $user = new Entity_User_Freelancer();
        $user->setSlug('freelancer-1');

        $type   = new Viewhelper_User_Type_Freelancer_Create();
        $type->setUser($user);

        $this->assertEquals(Route::url('freelancerProfileEdit', ['slug' => 'freelancer-1']), $type->getEditUrl());
    }

    /**
     * @covers Viewhelper_User_Type_Freelancer::hasCv()
     */
    public function testHasCvNotOk()
    {
        $type   = new Viewhelper_User_Type_Freelancer_Create();
        $type->setUser(self::$_freelancer);

        $this->assertFalse($type->hasCv());
    }

    /**
     * @covers Viewhelper_User_Type_Freelancer::hasCv()
     */
    public function testHasCvOk()
    {
        self::$_freelancer->setCvPath('szabaduszok-freelancer-1-cv.pdf');
        $type   = new Viewhelper_User_Type_Freelancer_Create();
        $type->setUser(self::$_freelancer);

        $this->assertTrue($type->hasCv());
    }

    public static function setUpBeforeClass()
    {
        $freelancer = new Model_User_Freelancer();
        $freelancer->user_id = 769;
        $freelancer->lastname = 'Teszt';
        $freelancer->firstname = 'Szabadúszó';
        $freelancer->email = 'teszt' . $freelancer->user_id . '@szabaduszok.com';
        $freelancer->password = 'pass';
        $freelancer->min_net_hourly_wage = '2500';
        $freelancer->save();

        self::$_freelancer = Entity_User::createUser(Entity_User::TYPE_FREELANCER, $freelancer);
    }

    public static function tearDownAfterClass()
    {
        $freelancer = new Model_User_Freelancer(769);
        $freelancer->delete();
    }
}