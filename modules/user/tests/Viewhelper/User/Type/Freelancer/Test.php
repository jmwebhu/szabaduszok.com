<?php

class Viewhelper_User_Type_Freelancer_Test extends Unittest_TestCase
{
    /**
     * @var Entity_User_Freelancer
     */
    private static $_freelancer;

    /**
     * @var array
     */
    private $_industries = [];

    /**
     * @var array
     */
    private $_professions = [];

    /**
     * @var array
     */
    private $_skills = [];

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

    /**
     * @covers Viewhelper_User_Type_Freelancer::getProjectNotificationRelationForProfile()
     */
    public function testGetProjectNotificationRelationForProfileIndustry()
    {
        $industryMock  = $this->getMockBuilder('\Model_Industry')
            ->setMethods(['getAll', 'object_name'])
            ->getMock();

        $industryMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($this->_industries));

        $industryMock->expects($this->any())
            ->method('object_name')
            ->will($this->returnValue('Model_Industry'));

        $notification1 = new Model_User_Project_Notification_Industry();
        $notification1->industry_id = $this->_industries[0]->industry_id;
        $notification1->user_id = 1;

        $notification2 = new Model_User_Project_Notification_Industry();
        $notification2->industry_id = $this->_industries[3]->industry_id;
        $notification2->user_id = 1;

        $this->setMockAny('\Entity_User_Freelancer', 'getRelation', [$notification1, $notification2]);

        $type               = new Viewhelper_User_Type_Freelancer_Create();
        $type->setUser($this->_mock);

        $result = $type->getProjectNotificationRelationForProfile($industryMock);

        $this->assertEquals('selected', $result[0]['selected']);
        $this->assertEquals('selected', $result[3]['selected']);

        $this->assertEmpty($result[1]['selected']);
        $this->assertEmpty($result[2]['selected']);
        $this->assertEmpty($result[4]['selected']);
    }

    public function setUp()
    {
        $this->setUpRelation('industry', 5);
        $this->setUpRelation('profession', 5);
        $this->setUpRelation('skill', 5);
    }

    /**
     * @param int $count
     */
    protected function setUpRelation($relation, $count)
    {
        $class = 'Model_' . ucfirst($relation);
        for ($i = 0; $i < $count; $i++) {
            $model = new $class();
            $model->name = $relation . '-' . $i + 1;
            $model->{$model->primary_key()} = $i + 1;

            $property = '_' . $model->object_plural();
            $this->{$property}[] = $model;
        }
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