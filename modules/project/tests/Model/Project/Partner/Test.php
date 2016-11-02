<?php

class Model_Project_Partner_Test extends Unittest_TestCase
{
    /**
     * @var Model_User
     */
    private static $_freelancer;

    /**
     * @var Model_User
     */
    private static $_employer;

    /**
     * @var Model_Project
     */
    private static $_project;

    /**
     * @var Model_Project_Partner
     */
    private static $_partner;

    /**
     * @covers Model_Project_Partner::submit()
     */
    public function testSubmitWithoutType()
    {
        $data = [
            'user_id'       => self::$_freelancer->user_id,
            'project_id'    => self::$_project->project_id
        ];

        $partner = new Model_Project_Partner();
        $partner->submit($data);

        $this->assertEquals(1, $partner->type);
    }

    /**
     * @covers Model_Project_Partner::submit()
     */
    public function testSubmitWithType()
    {
        $data = [
            'user_id'       => self::$_freelancer->user_id,
            'project_id'    => self::$_project->project_id,
            'type'          => 2
        ];

        $partner = new Model_Project_Partner();
        $partner->submit($data);

        $this->assertEquals(2, $partner->type);
    }

    /**
     * @covers Model_Project_Partner::apply()
     */
    public function testApply()
    {
        $data = [
            'user_id'       => self::$_freelancer->user_id,
            'project_id'    => self::$_project->project_id
        ];

        $partner = new Model_Project_Partner();
        $partner->apply($data);
        self::$_partner = $partner;

        $this->assertNotificationExistsInDatabaseWith([
            'notifier_user_id'  => self::$_freelancer->user_id,
            'notified_user_id'  => self::$_employer->user_id,
            'subject_id'        => self::$_project->project_id,
            'event_id'          => Model_Event_Factory::createEvent(Model_Event::TYPE_CANDIDATE_NEW)->event_id
        ]);

        $this->assertPartnerExistsInDatabaseByData($data);
        $this->assertPartnerTypeIs(Model_Project_Partner::TYPE_CANDIDATE);
    }

    /**
     * @covers Model_Project_Partner::undoApplication()
     */
    public function testUndoApplication()
    {
        $this->givenApplication();
        $this->assertPartnerExistsInDatabase();

        self::$_partner->undoApplication();

        $this->assertNotificationExistsInDatabaseWith([
            'notifier_user_id'  => self::$_freelancer->user_id,
            'notified_user_id'  => self::$_employer->user_id,
            'subject_id'        => self::$_project->project_id,
            'event_id'          => Model_Event_Factory::createEvent(Model_Event::TYPE_CANDIDATE_UNDO)->event_id
        ]);

        $this->assertPartnerNotExistsInDatabase();
    }

    /**
     * @covers Model_Project_Partner::approveApplication()
     */
    public function testApproveApplication()
    {
        $this->givenApplication();
        $this->assertPartnerExistsInDatabase();

        self::$_partner->approveApplication();

        $this->assertNotificationExistsInDatabaseWith([
            'notifier_user_id'  => self::$_employer->user_id,
            'notified_user_id'  => self::$_freelancer->user_id,
            'subject_id'        => self::$_project->project_id,
            'event_id'          => Model_Event_Factory::createEvent(Model_Event::TYPE_CANDIDATE_ACCEPT)->event_id
        ]);

        $this->assertApprovedPartnerExistsInDatabase();
    }

    /**
     * @covers Model_Project_Partner::rejectApplication()
     */
    public function testRejectApplication()
    {
        $this->givenApplication();
        $this->assertPartnerExistsInDatabase();

        self::$_partner->rejectApplication();

        $this->assertNotificationExistsInDatabaseWith([
            'notifier_user_id'  => self::$_employer->user_id,
            'notified_user_id'  => self::$_freelancer->user_id,
            'subject_id'        => self::$_project->project_id,
            'event_id'          => Model_Event_Factory::createEvent(Model_Event::TYPE_CANDIDATE_REJECT)->event_id
        ]);

        $this->assertPartnerNotExistsInDatabase();
    }

    /**
     * @covers Model_Project_Partner::cancelParticipation()
     */
    public function testCancelParticipation()
    {
        $this->givenParticipation();
        $this->assertPartnerExistsInDatabase();

        self::$_partner->cancelParticipation();

        $this->assertNotificationExistsInDatabaseWith([
            'notifier_user_id'  => self::$_employer->user_id,
            'notified_user_id'  => self::$_freelancer->user_id,
            'subject_id'        => self::$_project->project_id,
            'event_id'          => Model_Event_Factory::createEvent(Model_Event::TYPE_PARTICIPATE_REMOVE)->event_id
        ]);

        $this->assertPartnerNotExistsInDatabase();
    }

    /**
     * @covers Model_Project_Partner::throwExceptionIfEventNotPerformable()
     */
    public function testThrowExceptionIfEventNotPerformableOk()
    {
        $partner = new Model_Project_Partner();
        $partner->setType(Model_Project_Partner::TYPE_CANDIDATE);

        $result = $this->invokeMethod($partner, 'throwExceptionIfEventNotPerformable', [Model_Event::TYPE_CANDIDATE_NEW]);
        $this->assertTrue($result);
    }

    /**
     * @covers Model_Project_Partner::throwExceptionIfEventNotPerformable()
     */
    public function testThrowExceptionIfEventNotPerformableNotOk()
    {
        $partner = new Model_Project_Partner();
        $partner->setType(Model_Project_Partner::TYPE_CANDIDATE);

        $result = $this->invokeMethod($partner, 'throwExceptionIfEventNotPerformable', [Model_Event::TYPE_PARTICIPATE_REMOVE]);
        $this->assertFalse($result);
    }

    protected function givenApplication()
    {
        if (self::$_partner == null || !self::$_partner->loaded()) {
            $data = [
                'user_id'       => self::$_freelancer->user_id,
                'project_id'    => self::$_project->project_id
            ];

            $partner = new Model_Project_Partner();
            $apply = $partner->apply($data);

            self::$_partner = $partner;
        }
    }

    protected function givenParticipation()
    {
        $this->givenApplication();
        self::$_partner = self::$_partner->approveApplication();
    }

    /**
     * @param array $data
     */
    protected function assertPartnerExistsInDatabaseByData(array $data)
    {
        $model      = new Model_Project_Partner();
        $partner    = $model->getByUserProject($data);

        $this->assertTrue($partner->loaded());
        $this->assertPartnerTypeIs(Model_Project_Partner::TYPE_CANDIDATE);
    }

    protected function assertPartnerExistsInDatabase()
    {
        $this->assertTrue(self::$_partner->loaded());
    }

    protected function assertPartnerNotExistsInDatabase()
    {
        $this->assertFalse(self::$_partner->loaded());
    }

    protected function assertApprovedPartnerExistsInDatabase()
    {
        $this->assertPartnerExistsInDatabase();

        $this->assertEquals(date('Y-m-d H:i', time()), self::$_partner->approved_at);
        $this->assertPartnerTypeIs(Model_Project_Partner::TYPE_PARTICIPANT);
    }

    /**
     * @param array $data
     */
    protected function assertNotificationExistsInDatabaseWith(array $data)
    {
        $notification = DB::select()
            ->from('notifications')
            ->where('notifier_user_id', '=', $data['notifier_user_id'])
            ->where('notified_user_id', '=', $data['notified_user_id'])
            ->where('subject_id', '=', $data['subject_id'])
            ->where('event_id', '=', $data['event_id'])
            ->limit(1)
            ->execute()->current();

        $this->assertNotNull($notification['notification_id']);
        $this->assertNotEmpty($notification['notification_id']);
        $this->assertTrue($notification['is_archived'] != 1);
    }

    protected function assertPartnerTypeIs($type)
    {
        $this->assertEquals($type, self::$_partner->type);
    }

    /**
     * @param array $data
     * @return mixed
     */
    protected function getPartner(array $data)
    {
        return DB::select()
            ->from('projects_partners')
            ->where('user_id', '=', $data['user_id'])
            ->and_where('project_id', '=', $data['project_id'])
            ->limit(1)
            ->execute()->current();
    }

    public static function setUpBeforeClass()
    {
        $freelancerData = [
            'firstname'             => 'Web',
            'lastname'              => 'Józsi',
            'email'                 => microtime() . '@szabaduszok.com',
            'password'              => 'asdfasdf123',
            'password_confirm'      => 'asdfasdf123',
            'address_postal_code'   => '9700',
            'address_city'          => 'Szombathely',
            'min_net_hourly_wage'   => 3000,
        ];

        $freelancer = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $freelancer->submit($freelancerData);

        $employerData = [
            'firstname'             => 'Martin',
            'lastname'              => 'Joó',
            'email'                 => microtime() . '@szabaduszok.com',
            'password'              => 'asdfasdf123',
            'password_confirm'      => 'asdfasdf123',
            'address_postal_code'   => '9700',
            'address_city'          => 'Szombathely',
            'phonenumber'           => '06301923380'
        ];

        $employer = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $employer->submit($employerData);

        $projectData = [
            'name'                      => 'Teszt projekt',
            'short_description'         => 'Teszt projekt Teszt projekt',
            'long_description'          => 'Teszt projekt Teszt projekt Teszt projekt',
            'email'                     => microtime() . '@szabaduszok.com',
            'phonenumber'               => '06301923380',
            'salary_type'               => 1,
            'salary_low'                => 3200,
            'user_id'                   => $employer->getUserId()
        ];

        $project = new Entity_Project();
        $project->submit($projectData);

        self::$_employer    = $employer->getModel();
        self::$_freelancer  = $freelancer->getModel();
        self::$_project     = $project->getModel();
    }

    public static function tearDownAfterClass()
    {
        self::$_employer->delete();
        self::$_freelancer->delete();
        self::$_project->delete();
    }

    public function tearDown()
    {
        DB::delete('projects_partners')->execute();
    }
}