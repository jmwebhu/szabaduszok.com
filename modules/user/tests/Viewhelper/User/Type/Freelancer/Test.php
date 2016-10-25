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
        $industryMock = $this->getRelationMock('industry');
        $notifications = $this->getNotificationArray('industry', [0, 3]);

        $this->setMockAny('\Entity_User_Freelancer', 'getRelation', $notifications);

        $type               = new Viewhelper_User_Type_Freelancer_Create();
        $type->setUser($this->_mock);

        $result = $type->getProjectNotificationRelationForProfile($industryMock);

        $this->assertEquals('selected', $result[0]['selected']);
        $this->assertEquals('selected', $result[3]['selected']);

        $this->assertEmpty($result[1]['selected']);
        $this->assertEmpty($result[2]['selected']);
        $this->assertEmpty($result[4]['selected']);
    }

    /**
     * @covers Viewhelper_User_Type_Freelancer::getProjectNotificationRelationForProfile()
     */
    public function testGetProjectNotificationRelationForProfileProfession()
    {
        $industryMock = $this->getRelationMock('profession');
        $notifications = $this->getNotificationArray('profession', [1, 2, 3]);

        $this->setMockAny('\Entity_User_Freelancer', 'getRelation', $notifications);

        $type               = new Viewhelper_User_Type_Freelancer_Create();
        $type->setUser($this->_mock);

        $result = $type->getProjectNotificationRelationForProfile($industryMock);

        $this->assertEquals('selected', $result[1]['selected']);
        $this->assertEquals('selected', $result[2]['selected']);
        $this->assertEquals('selected', $result[3]['selected']);

        $this->assertEmpty($result[0]['selected']);
        $this->assertEmpty($result[4]['selected']);
    }

    /**
     * @covers Viewhelper_User_Type_Freelancer::getProjectNotificationRelationForProfile()
     */
    public function testGetProjectNotificationRelationForProfileSkill()
    {
        $industryMock = $this->getRelationMock('skill');
        $notifications = $this->getNotificationArray('skill', [1, 2, 3, 6, 9]);

        $this->setMockAny('\Entity_User_Freelancer', 'getRelation', $notifications);

        $type               = new Viewhelper_User_Type_Freelancer_Create();
        $type->setUser($this->_mock);

        $result = $type->getProjectNotificationRelationForProfile($industryMock);

        $this->assertEquals('selected', $result[1]['selected']);
        $this->assertEquals('selected', $result[2]['selected']);
        $this->assertEquals('selected', $result[3]['selected']);
        $this->assertEquals('selected', $result[6]['selected']);
        $this->assertEquals('selected', $result[9]['selected']);

        $this->assertEmpty($result[0]['selected']);
        $this->assertEmpty($result[4]['selected']);
        $this->assertEmpty($result[5]['selected']);
        $this->assertEmpty($result[7]['selected']);
        $this->assertEmpty($result[8]['selected']);
    }

    /**
     * @param string $relation
     * @param array $indexes
     * @return array
     */
    protected function getNotificationArray($relation, array $indexes)
    {
        $class              = 'Model_' . ucfirst($relation);
        $model              = new $class();
        $notificationClass  = 'Model_User_Project_Notification_' . ucfirst($relation);
        $property           = '_' . $model->object_plural();
        $notifications      = [];

        foreach ($indexes as $index) {
            $notification = new $notificationClass();
            $notification->{$model->primary_key()} = $this->{$property}[$index]->{$model->primary_key()};
            $notification->user_id = 1;

            $notifications[] = $notification;
        }

        return $notifications;
    }

    /**
     * @param string $relation
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function getRelationMock($relation)
    {
        $class      = 'Model_' . ucfirst($relation);
        $object     = new $class();
        $property   = '_' . $object->object_plural();

        $relationMock  = $this->getMockBuilder('\\' . $class)
            ->setMethods(['getAll', 'object_name'])
            ->getMock();

        $relationMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue($this->{$property}));

        $relationMock->expects($this->any())
            ->method('object_name')
            ->will($this->returnValue($class));

        return $relationMock;
    }

    public function setUp()
    {
        $this->setUpRelation('industry', 5);
        $this->setUpRelation('profession', 5);
        $this->setUpRelation('skill', 10);
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