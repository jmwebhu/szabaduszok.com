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

        $this->assertNotificationExistsInDatabaseWith([
            'notifier_user_id'  => self::$_freelancer->user_id,
            'notified_user_id'  => self::$_employer->user_id,
            'subject_id'        => self::$_project->project_id,
            'event_id'          => Model_Event_Factory::createEvent(Model_Event::TYPE_CANDIDATE_NEW)->event_id
        ]);

        $this->assertPartnerExistsInDatabaseAfterApply($data);
    }

    /**
     * @param array $data
     */
    protected function assertPartnerExistsInDatabaseAfterApply(array $data)
    {
        $partner = $this->getPartner($data);

        $this->assertEquals(1, $partner['type']);
        $this->assertNull($partner['approved_at']);
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
}