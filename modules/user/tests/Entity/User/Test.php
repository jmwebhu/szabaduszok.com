<?php

class Entity_User_Test extends Unittest_TestCase
{
    /*
     * Reflection hasznalja oket, ezert kell public, es latszolag ezert nincsenek hasznalva (initRelations)
     */
    public static $_industries  = [];
    public static $_professions = [];
    public static $_skills      = [];

    private static $_insertedUserIds        = [];
    private static $_professionsOnTheFly    = ['Teljesen új szakterület'];
    private static $_skillsOnTheFly         = ['szupererő', 'Képesség', '23 év tapasztalat'];

    /**
     * @covers Entity_User::getName()
     */
    public function testGetName()
    {
        $user = new Entity_User_Employer();
        $user->setLastname('Joó');
        $user->setFirstname('Martin');

        $this->assertEquals('Joó Martin', $user->getName());
    }

    /**
     * @covers Entity_User::unsetPasswordFrom()
     */
    public function testUnsetPasswordFromIdExistsPasswordExists()
    {
        $user = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $post = [
            'user_id' => 1,
            'password'  => 'asdf'
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);

        $this->assertEquals('asdf', $data['password']);
    }

    /**
     * @covers Entity_User::unsetPasswordFrom()
     */
    public function testUnsetPasswordFromIdExistsPasswordNotExists()
    {
        $user = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $post = [
            'user_id' => 1,
            'password'  => ''
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);

        $this->assertFalse(isset($data['password']));
        $this->assertFalse(isset($data['password_confirm']));

        $post = [
            'user_id' => 1,
            'password'  => null
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);

        $this->assertFalse(isset($data['password']));
        $this->assertFalse(isset($data['password_confirm']));

        $post = [
            'user_id' => 1
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);

        $this->assertFalse(isset($data['password']));
        $this->assertFalse(isset($data['password_confirm']));
    }

    /**
     * @covers Entity_User::unsetPasswordFrom()
     */
    public function testUnsetPasswordFromIdNotExistsPasswordExists()
    {
        $user = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $post = [
            'password'  => 'asdf'
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);
        $this->assertEquals('asdf', $data['password']);

        $post = [
            'password'  => 'asdf',
            'user_id'   => ''
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);
        $this->assertEquals('asdf', $data['password']);

        $post = [
            'password'  => 'asdf',
            'user_id'   => null
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);
        $this->assertEquals('asdf', $data['password']);
    }

    /**
     * @covers Entity_User::unsetPasswordFrom()
     */
    public function testUnsetPasswordFromIdNotExistsPasswordNotExists()
    {
        $user = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $post = [];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);

        $this->assertFalse(isset($data['password']));
        $this->assertFalse(isset($data['password_confirm']));

        $post = [
            'user_id'   => '',
            'passrod'   => ''
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);

        $this->assertFalse(isset($data['password']));
        $this->assertFalse(isset($data['password_confirm']));

        $post = [
            'user_id'   => null,
            'passrod'   => null
        ];

        $data = $this->invokeMethod($user, 'unsetPasswordFrom', [$post]);

        $this->assertFalse(isset($data['password']));
        $this->assertFalse(isset($data['password_confirm']));
    }

    /**
     * @covers Entity_User::createUser()
     */
    public function testCreateUserOk()
    {
        $user = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $this->assertTrue($user instanceof Entity_User_Freelancer);

        $user = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $this->assertTrue($user instanceof Entity_User_Employer);
    }

    /**
     * @covers Entity_User::createUser()
     * @expectedException Exception
     */
    public function testCreateUserNotOk()
    {
        Entity_User::createUser(9912);
    }

    /**
     * @covers Entity_User::submitUser()
     */
    public function testSubmitUserEmployerWithoutRelations()
    {
        $employer = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $data = [
            'is_company'            => 'on',
            'company_name'          => 'Szabaduszok.com Zrt.',
            'lastname'              => 'Joó',
            'firstname'             => 'Martin',
            'email'                 => uniqid() . '@jmweb.hu',
            'password'              => 'Password123',
            'password_confirm'      => 'Password123',
            'address_postal_code'   => '9700',
            'address_city'          => 'Szombathely',
            'phonenumber'           => '06301923380',
            'short_description'     => 'Rövid "bemutatkozás'
        ];

        $employer->submitUser($data, $this->getMailinglistMockToCreate('Gateway_Mailinglist_Mailchimp_Employer'));
        self::$_insertedUserIds[] = $employer->getUserId();

        $this->assertUserIdExistsInDatabase($employer->getUserId());
        $this->assertUserIdExistsInSession($employer->getUserId());
        $this->assertUserIdExistsInCache($employer->getUserId());
        $this->assertEmailNotExistsInSignup($employer->getEmail());
        $this->assertEquals(1, $employer->getIsCompany());
        $this->assertEquals('Szabaduszok.com Zrt.', $employer->getCompanyName());
        $this->assertNotEmpty($employer->getPassword());
        $this->assertNotEmpty($employer->getSalt());
        $this->assertNotEmpty($employer->getPassword());
        $this->assertNotEmpty($employer->getSalt());
        $this->assertNotEmpty($employer->getSlug());
        $this->assertNotEmpty($employer->getSearchText());
        $this->assertEquals("Rövid 'bemutatkozás", $employer->getShortDescription());
    }

    /**
     * @covers Entity_User::submitUser()
     */
    public function testSubmitUserUpdateEmployerWithoutRelations()
    {
        $employer = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $data = [
            'is_company'            => 'on',
            'company_name'          => 'Szabaduszok.com Zrt.',
            'lastname'              => 'Joó',
            'firstname'             => 'Martin',
            'email'                 => uniqid() . '@jmweb.hu',
            'password'              => 'Password123',
            'password_confirm'      => 'Password123',
            'address_postal_code'   => '9700',
            'address_city'          => 'Szombathely',
            'phonenumber'           => '06301923380',
            'short_description'     => 'Rövid bemutatkozás'
        ];

        $employer->submitUser($data, $this->getMailinglistMockToCreate('Gateway_Mailinglist_Mailchimp_Employer'));

        self::$_insertedUserIds[] = $employer->getUserId();

        $this->assertUserIdExistsInDatabase($employer->getUserId());
        $this->assertUserIdExistsInSession($employer->getUserId());
        $this->assertUserIdExistsInCache($employer->getUserId());
        $this->assertEmailNotExistsInSignup($employer->getEmail());
        $this->assertEquals(1, $employer->getIsCompany());
        $this->assertEquals('Szabaduszok.com Zrt.', $employer->getCompanyName());
        $this->assertNotEmpty($employer->getPassword());
        $this->assertNotEmpty($employer->getSalt());
        $this->assertNotEmpty($employer->getSlug());
        $this->assertNotEmpty($employer->getSearchText());

        $data = [
            'user_id'               => $employer->getUserId(),
            'is_company'            => 'on',
            'company_name'          => 'Szabaduszok.com Kft.',
            'lastname'              => 'Joó',
            'firstname'             => 'Martin',
            'email'                 => uniqid() . '@jmweb.hu',
            'password'              => 'Password1234',
            'password_confirm'      => 'Password1234',
            'address_postal_code'   => '1010',
            'address_city'          => 'Budapest',
            'phonenumber'           => '06301923380',
            'short_description'     => 'Rövid bemutatkozás, kicsit hosszabb'
        ];

        $employer->submitUser($data, $this->getMailinglistMockToUpdate('Gateway_Mailinglist_Mailchimp_Employer'));
        self::$_insertedUserIds[] = $employer->getUserId();

        $this->assertUserIdExistsInDatabase($employer->getUserId());
        $this->assertUserIdExistsInSession($employer->getUserId());
        $this->assertUserIdExistsInCache($employer->getUserId());
        $this->assertEmailNotExistsInSignup($employer->getEmail());
        $this->assertEquals(1, $employer->getIsCompany());
        $this->assertEquals('Szabaduszok.com Kft.', $employer->getCompanyName());
        $this->assertNotEmpty($employer->getPassword());
        $this->assertNotEmpty($employer->getSalt());
        $this->assertNotEmpty($employer->getSlug());
        $this->assertNotEmpty($employer->getSearchText());
    }

    /**
     * @covers Entity_User::submitUser()
     */
    public function testSubmitUserEmployerWithRelations()
    {
        $employer = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $data = [
            'is_company'            => '',
            'company_name'          => '',
            'lastname'              => 'Joó',
            'firstname'             => 'Martin',
            'email'                 => uniqid() . '@jmweb.hu',
            'password'              => 'Password123',
            'password_confirm'      => 'Password123',
            'address_postal_code'   => '9700',
            'address_city'          => 'Szombathely',
            'phonenumber'           => '06301923380',
            'short_description'     => 'Rövid bemutatkozás',
            'industries'            => [self::$_industries[0]],
            'professions'           => [self::$_professions[1], self::$_professions[2]]
        ];

        $employer->submitUser($data, $this->getMailinglistMockToCreate('Gateway_Mailinglist_Mailchimp_Employer'));
        self::$_insertedUserIds[] = $employer->getUserId();

        $this->assertUserIdExistsInDatabase($employer->getUserId());
        $this->assertUserIdExistsInSession($employer->getUserId());
        $this->assertUserIdExistsInCache($employer->getUserId());
        $this->assertEmailNotExistsInSignup($employer->getEmail());
        $this->assertUserRelationExistsInDatabase('industry', [self::$_industries[0]], $employer->getUserId());
        $this->assertUserRelationExistsInDatabase('profession', [self::$_professions[1], self::$_professions[2]], $employer->getUserId());
        $this->assertEquals(0, $employer->getIsCompany());
        $this->assertEquals('', $employer->getCompanyName());
        $this->assertNotEmpty($employer->getPassword());
        $this->assertNotEmpty($employer->getSalt());
        $this->assertNotEmpty($employer->getSlug());
    }

    /**
     * @covers Entity_User::submitUser()
     */
    public function testSubmitUserFreelancerWithoutRelations()
    {
        $freelancer = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $data = [
            'lastname'              => 'Joó',
            'firstname'             => 'Martin',
            'email'                 => uniqid() . '@szabaduszok.com',
            'password'              => 'Password123',
            'password_confirm'      => 'Password123',
            'address_postal_code'   => '9700',
            'address_city'          => 'Szombathely',
            'phonenumber'           => '06301923380',
            'short_description'     => 'Rövid bemutatkozás',
            'min_net_hourly_wage'   => '2500',
            'webpage'               => 'szabaduszok.com',
            'professional_experience'  => '5',
            'is_able_to_bill'       => 'on'
        ];

        $freelancer->submitUser($data, $this->getMailinglistMockToCreate('Gateway_Mailinglist_Mailchimp_Freelancer'));

        self::$_insertedUserIds[] = $freelancer->getUserId();

        $this->assertUserIdExistsInDatabase($freelancer->getUserId());
        $this->assertUserIdExistsInSession($freelancer->getUserId());
        $this->assertUserIdExistsInCache($freelancer->getUserId());
        $this->assertEmailNotExistsInSignup($freelancer->getEmail());
        $this->assertNotEmpty($freelancer->getPassword());
        $this->assertNotEmpty($freelancer->getSalt());
        $this->assertNotEmpty($freelancer->getSlug());
        $this->assertEquals('http://szabaduszok.com', $freelancer->getWebpage());
        $this->assertEquals('2500', $freelancer->getMinNetHourlyWage());
        $this->assertEquals('1', $freelancer->getSkillRelation());
        $this->assertEquals('1', $freelancer->getNeedProjectNotification());
        $this->assertEquals('5', $freelancer->getProfessionalExperience());
        $this->assertEquals(1, $freelancer->getIsAbleToBill());
    }

    /**
     * @covers Entity_User::submitUser()
     */
    public function testSubmitUserFreelancerWithProfiles()
    {
        $freelancer = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $profiles = ['https://linkedin.com/jm', 'https://facebook.com/jm'];

        $data = [
            'lastname'              => 'Joó',
            'firstname'             => 'Martin',
            'email'                 => uniqid() . '@szabaduszok.com',
            'password'              => 'Password123',
            'password_confirm'      => 'Password123',
            'address_postal_code'   => '9700',
            'address_city'          => 'Szombathely',
            'phonenumber'           => '06301923380',
            'short_description'     => 'Rövid bemutatkozás',
            'min_net_hourly_wage'   => '2500',
            'webpage'               => 'szabaduszok.com',
            'profiles'              => $profiles,
        ];

        $freelancer->submitUser($data, $this->getMailinglistMockToCreate('Gateway_Mailinglist_Mailchimp_Freelancer'));

        self::$_insertedUserIds[] = $freelancer->getUserId();

        $this->assertUserIdExistsInDatabase($freelancer->getUserId());
        $this->assertUserIdExistsInSession($freelancer->getUserId());
        $this->assertUserIdExistsInCache($freelancer->getUserId());
        $this->assertEmailNotExistsInSignup($freelancer->getEmail());
        $this->assertNotEmpty($freelancer->getPassword());
        $this->assertNotEmpty($freelancer->getSalt());
        $this->assertNotEmpty($freelancer->getSlug());
        $this->assertEquals('http://szabaduszok.com', $freelancer->getWebpage());
        $this->assertEquals('2500', $freelancer->getMinNetHourlyWage());
        $this->assertEquals('1', $freelancer->getSkillRelation());
        $this->assertEquals('1', $freelancer->getNeedProjectNotification());

        $this->assertUserProfilesExistInDatabase($profiles, $freelancer->getUserId());
    }

    public function testSubmitFreelancerProfessionalExperience()
    {
        $freelancer = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $data = [
            'lastname' => 'Joó',
            'firstname' => 'Martin',
            'email' => uniqid() . '@szabaduszok.com',
            'password' => 'Password123',
            'password_confirm' => 'Password123',
            'address_postal_code' => '9700',
            'address_city' => 'Szombathely',
            'phonenumber' => '06301923380',
            'short_description' => 'Rövid bemutatkozás',
            'min_net_hourly_wage' => '2500',
            'webpage' => 'szabaduszok.com',
            'professional_experience' => '5'
        ];

        $freelancer->submitUser($data, $this->getMailinglistMockToCreate('Gateway_Mailinglist_Mailchimp_Freelancer'));
        self::$_insertedUserIds[] = $freelancer->getUserId();
        $this->assertEquals('5', $freelancer->getProfessionalExperience());

        $data['professional_experience'] = '2.5';
        $data['email'] = uniqid() . '@szabaduszok.com';
        $freelancer = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $freelancer->submitUser($data, $this->getMailinglistMockToCreate('Gateway_Mailinglist_Mailchimp_Freelancer'));
        self::$_insertedUserIds[] = $freelancer->getUserId();

        $orm = ORM::factory('User_Freelancer', $freelancer->getUserId());
        $this->assertEquals('2.5', $orm->professional_experience);


        $data['professional_experience'] = '2,5';
        $data['email'] = uniqid() . '@szabaduszok.com';
        $freelancer = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $freelancer->submitUser($data, $this->getMailinglistMockToCreate('Gateway_Mailinglist_Mailchimp_Freelancer'));
        self::$_insertedUserIds[] = $freelancer->getUserId();

        $orm = ORM::factory('User_Freelancer', $freelancer->getUserId());
        $this->assertEquals('2.5', $orm->professional_experience);
    }

    public function testSubmitFreelancerAbleToBill()
    {
        $freelancer1 = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $freelancer1Email = uniqid() . '@szabaduszok.com';
        $freelancer2Email = uniqid() . '@szabaduszok.com';

        $data = [
            'lastname' => 'Joó',
            'firstname' => 'Martin',
            'email' => $freelancer1Email,
            'password' => 'Password123',
            'password_confirm' => 'Password123',
            'address_postal_code' => '9700',
            'address_city' => 'Szombathely',
            'phonenumber' => '06301923380',
            'short_description' => 'Rövid bemutatkozás',
            'min_net_hourly_wage' => '2500',
            'webpage' => 'szabaduszok.com',
            'professional_experience' => '5',
        ];

        $freelancer1->submitUser($data, $this->getMailinglistMockToCreate('Gateway_Mailinglist_Mailchimp_Freelancer'));
        self::$_insertedUserIds[] = $freelancer1->getUserId();
        $orm = ORM::factory('User_Freelancer', $freelancer1->getUserId());
        $this->assertEquals(0, $orm->is_able_to_bill);

        $data['is_able_to_bill'] = 'on';
        $data['email'] = $freelancer2Email;
        $freelancer2 = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $freelancer2->submitUser($data, $this->getMailinglistMockToCreate('Gateway_Mailinglist_Mailchimp_Freelancer'));
        self::$_insertedUserIds[] = $freelancer2->getUserId();
        $orm = ORM::factory('User_Freelancer', $freelancer2->getUserId());
        $this->assertEquals(1, $orm->is_able_to_bill);

        $data['is_able_to_bill'] = 'on';
        $data['email'] = $freelancer1Email;
        $data['user_id'] = $freelancer1->getId();
        $freelancer1->submitUser($data, $this->getMailinglistMockToUpdate('Gateway_Mailinglist_Mailchimp_Freelancer'));
        $orm = ORM::factory('User_Freelancer', $freelancer2->getUserId());
        $this->assertEquals(1, $orm->is_able_to_bill);

        $data['is_able_to_bill'] = 'off';
        $data['email'] = $freelancer2Email;
        $data['user_id'] = $freelancer2->getId();
        $freelancer2->submitUser($data, $this->getMailinglistMockToUpdate('Gateway_Mailinglist_Mailchimp_Freelancer'));
        $orm = ORM::factory('User_Freelancer', $freelancer2->getUserId());
        $this->assertEquals(0, $orm->is_able_to_bill);
    }

    /**
     * @covers Entity_User::submitUser()
     */
    public function testSubmitUserFreelancerWithRelations()
    {
        $freelancer = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $data = [
            'lastname'              => 'Joó',
            'firstname'             => 'Martin',
            'email'                 => uniqid() . '@szabaduszok.com',
            'password'              => 'Password123',
            'password_confirm'      => 'Password123',
            'address_postal_code'   => '9700',
            'address_city'          => 'Szombathely',
            'phonenumber'           => '06301923380',
            'short_description'     => 'Rövid bemutatkozás',
            'min_net_hourly_wage'   => '2500',
            'webpage'               => 'szabaduszok.com',
            'industries'            => [self::$_industries[0]],
            'professions'           => [self::$_professions[0], self::$_professions[1], self::$_professions[2]],
            'skills'                => [self::$_skills[2], self::$_skills[3], self::$_skills[5], self::$_skills[6], self::$_skills[7], self::$_skills[8]]
        ];

        $freelancer->submitUser($data, $this->getMailinglistMockToCreate('Gateway_Mailinglist_Mailchimp_Freelancer'));
        self::$_insertedUserIds[] = $freelancer->getUserId();

        $this->assertUserIdExistsInDatabase($freelancer->getUserId());
        $this->assertUserIdExistsInSession($freelancer->getUserId());
        $this->assertUserIdExistsInCache($freelancer->getUserId());
        $this->assertEmailNotExistsInSignup($freelancer->getEmail());
        $this->assertNotEmpty($freelancer->getPassword());
        $this->assertNotEmpty($freelancer->getSalt());
        $this->assertNotEmpty($freelancer->getSlug());
        $this->assertEquals('http://szabaduszok.com', $freelancer->getWebpage());
        $this->assertEquals('2500', $freelancer->getMinNetHourlyWage());
        $this->assertEquals('1', $freelancer->getSkillRelation());
        $this->assertEquals('1', $freelancer->getNeedProjectNotification());

        $this->assertUserRelationExistsInDatabase('industry', [self::$_industries[0]], $freelancer->getUserId());
        $this->assertUserRelationExistsInDatabase('profession', [self::$_professions[0], self::$_professions[1], self::$_professions[2]], $freelancer->getUserId());
        $this->assertUserRelationExistsInDatabase('skill', [self::$_skills[2], self::$_skills[3], self::$_skills[5], self::$_skills[6], self::$_skills[7], self::$_skills[8]], $freelancer->getUserId());

        $this->assertUserProjectNotoficationExistsInDatabase('industry', [self::$_industries[0]], $freelancer->getUserId());
        $this->assertUserProjectNotoficationExistsInDatabase('profession', [self::$_professions[0], self::$_professions[1], self::$_professions[2]], $freelancer->getUserId());
        $this->assertUserProjectNotoficationExistsInDatabase('skill', [self::$_skills[2], self::$_skills[3], self::$_skills[5], self::$_skills[6], self::$_skills[7], self::$_skills[8]], $freelancer->getUserId());
    }

    /**
     * @covers Entity_User::submitUser()
     */
    public function testSubmitUserFreelancerWithRelationAndProfiles()
    {
        $freelancer = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $profiles = ['https://linkedin.com/jm', 'https://facebook.com/jm'];

        $data = [
            'lastname'              => 'Joó',
            'firstname'             => 'Martin',
            'email'                 => uniqid() . '@szabaduszok.com',
            'password'              => 'Password123',
            'password_confirm'      => 'Password123',
            'address_postal_code'   => '9700',
            'address_city'          => 'Szombathely',
            'phonenumber'           => '06301923380',
            'short_description'     => 'Rövid bemutatkozás',
            'min_net_hourly_wage'   => '2500',
            'webpage'               => 'szabaduszok.com',
            'industries'            => [self::$_industries[0]],
            'professions'           => [self::$_professions[0], self::$_professions[1], self::$_professions[2]],
            'skills'                => [self::$_skills[2], self::$_skills[3], self::$_skills[5], self::$_skills[6], self::$_skills[7], self::$_skills[8]],
            'profiles'              => $profiles
        ];

        $freelancer->submitUser($data, $this->getMailinglistMockToCreate('Gateway_Mailinglist_Mailchimp_Freelancer'));
        self::$_insertedUserIds[] = $freelancer->getUserId();

        $this->assertUserIdExistsInDatabase($freelancer->getUserId());
        $this->assertUserIdExistsInSession($freelancer->getUserId());
        $this->assertUserIdExistsInCache($freelancer->getUserId());
        $this->assertEmailNotExistsInSignup($freelancer->getEmail());
        $this->assertNotEmpty($freelancer->getPassword());
        $this->assertNotEmpty($freelancer->getSalt());
        $this->assertNotEmpty($freelancer->getSlug());
        $this->assertEquals('http://szabaduszok.com', $freelancer->getWebpage());
        $this->assertEquals('2500', $freelancer->getMinNetHourlyWage());
        $this->assertEquals('1', $freelancer->getSkillRelation());
        $this->assertEquals('1', $freelancer->getNeedProjectNotification());

        $this->assertUserRelationExistsInDatabase('industry', [self::$_industries[0]], $freelancer->getUserId());
        $this->assertUserRelationExistsInDatabase('profession', [self::$_professions[0], self::$_professions[1], self::$_professions[2]], $freelancer->getUserId());
        $this->assertUserRelationExistsInDatabase('skill', [self::$_skills[2], self::$_skills[3], self::$_skills[5], self::$_skills[6], self::$_skills[7], self::$_skills[8]], $freelancer->getUserId());

        $this->assertUserProjectNotoficationExistsInDatabase('industry', [self::$_industries[0]], $freelancer->getUserId());
        $this->assertUserProjectNotoficationExistsInDatabase('profession', [self::$_professions[0], self::$_professions[1], self::$_professions[2]], $freelancer->getUserId());
        $this->assertUserProjectNotoficationExistsInDatabase('skill', [self::$_skills[2], self::$_skills[3], self::$_skills[5], self::$_skills[6], self::$_skills[7], self::$_skills[8]], $freelancer->getUserId());

        $this->assertUserProfilesExistInDatabase($profiles, $freelancer->getUserId());
    }

    /**
     * @covers Entity_User::submitUser()
     * @group issue #6
     * @see https://github.com/jmwebhu/szabaduszok.com/issues/6
     */
    public function testSubmitUserFreelancerWithNonExistingRelations()
    {
        $freelancer = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $data = [
            'lastname'              => 'Joó',
            'firstname'             => 'Martin',
            'email'                 => uniqid() . '@szabaduszok.com',
            'password'              => 'Password123',
            'password_confirm'      => 'Password123',
            'address_postal_code'   => '9700',
            'address_city'          => 'Szombathely',
            'phonenumber'           => '06301923380',
            'short_description'     => 'Rövid bemutatkozás',
            'min_net_hourly_wage'   => '2500',
            'webpage'               => 'szabaduszok.com',
            'industries'            => [self::$_industries[0]],
            'professions'           => [self::$_professions[0], self::$_professions[1], self::$_professions[2], self::$_professionsOnTheFly[0], 'TELJESEN új Szakterület'],
            'skills'                => [self::$_skills[2], self::$_skills[3], self::$_skills[5], self::$_skills[6], self::$_skills[7], self::$_skills[8], self::$_skillsOnTheFly[0], self::$_skillsOnTheFly[1]]
        ];

        $freelancer->submitUser($data, $this->getMailinglistMockToCreate('Gateway_Mailinglist_Mailchimp_Freelancer'));

        self::$_insertedUserIds[] = $freelancer->getUserId();

        $this->assertUserIdExistsInDatabase($freelancer->getUserId());
        $this->assertUserIdExistsInSession($freelancer->getUserId());
        $this->assertUserIdExistsInCache($freelancer->getUserId());
        $this->assertEmailNotExistsInSignup($freelancer->getEmail());
        $this->assertNotEmpty($freelancer->getPassword());
        $this->assertNotEmpty($freelancer->getSalt());
        $this->assertNotEmpty($freelancer->getSlug());
        $this->assertEquals('http://szabaduszok.com', $freelancer->getWebpage());
        $this->assertEquals('2500', $freelancer->getMinNetHourlyWage());
        $this->assertEquals('1', $freelancer->getSkillRelation());
        $this->assertEquals('1', $freelancer->getNeedProjectNotification());

        $lastProfessionId = DB::select('profession_id')->from('professions')->order_by('profession_id', 'DESC')->limit(1)->execute()->get('profession_id');
        $lastSkillIds = DB::select()->from('skills')->order_by('skill_id', 'DESC')->limit(2)->execute()->as_array();

        $this->assertUserRelationExistsInDatabase('industry', [self::$_industries[0]], $freelancer->getUserId());
        $this->assertUserRelationExistsInDatabase('profession', [self::$_professions[0], self::$_professions[1], self::$_professions[2], $lastProfessionId], $freelancer->getUserId());
        $this->assertUserRelationExistsInDatabase('skill', [self::$_skills[2], self::$_skills[3], self::$_skills[5], self::$_skills[6], self::$_skills[7], self::$_skills[8], $lastSkillIds[0]['skill_id'], $lastSkillIds[1]['skill_id']], $freelancer->getUserId());

        $this->assertUserProjectNotoficationExistsInDatabase('industry', [self::$_industries[0]], $freelancer->getUserId());
        $this->assertUserProjectNotoficationExistsInDatabase('profession', [self::$_professions[0], self::$_professions[1], self::$_professions[2], $lastProfessionId], $freelancer->getUserId());
        $this->assertUserProjectNotoficationExistsInDatabase('skill', [self::$_skills[2], self::$_skills[3], self::$_skills[5], self::$_skills[6], self::$_skills[7], self::$_skills[8], $lastSkillIds[0]['skill_id'], $lastSkillIds[1]['skill_id']], $freelancer->getUserId());
    }

    /**
     * @covers Entity_User::submitUser()
     */
    public function testSubmitUserFreelancerValidationNotOk()
    {
        $freelancer = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $data = [
            'lastname'              => '',
            'firstname'             => '',
            'email'                 => '',
            'password'              => 'Password123',
            'password_confirm'      => 'Password123',
            'address_postal_code'   => '9700',
            'address_city'          => 'Szombathely',
            'phonenumber'           => '06301923380',
            'short_description'     => 'Rövid bemutatkozás',
            'min_net_hourly_wage'   => '',
            'webpage'               => 'szabaduszok.com'
        ];

        $result = true;

        try {
            $freelancer->submitUser($data);
        } catch (ORM_Validation_Exception $ovex) {
            $result = false;
            $errors = $ovex->errors('models');

            $this->assertArrayHasKey('lastname', $errors);
            $this->assertArrayHasKey('firstname', $errors);
            $this->assertArrayHasKey('email', $errors);
            $this->assertArrayHasKey('min_net_hourly_wage', $errors);
        }

        $this->assertFalse($result);
    }

    /**
     * @covers Entity_User::submitUser()
     */
    public function testSubmitUserEmployerValidationNotOk()
    {
        $employer = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $data = [
            'lastname'              => '',
            'firstname'             => '',
            'email'                 => '',
            'password'              => 'Password123',
            'password_confirm'      => 'Password123',
            'address_postal_code'   => '',
            'address_city'          => null,
            'phonenumber'           => '',
            'short_description'     => 'Rövid bemutatkozás',
            'min_net_hourly_wage'   => '',
            'webpage'               => 'szabaduszok.com'
        ];

        $result = true;

        try {
            $employer->submitUser($data);
        } catch (ORM_Validation_Exception $ovex) {
            $result = false;
            $errors = $ovex->errors('models');

            $this->assertArrayHasKey('lastname', $errors);
            $this->assertArrayHasKey('firstname', $errors);
            $this->assertArrayHasKey('email', $errors);
            $this->assertArrayHasKey('address_city', $errors);
            $this->assertArrayHasKey('address_postal_code', $errors);
            $this->assertArrayHasKey('phonenumber', $errors);
        }

        $this->assertFalse($result);
    }

    /**
     * @covers Entity_User::submitUser()
     * @group issue #17
     * @see https://github.com/jmwebhu/szabaduszok.com/issues/17
     */
    public function testSubmitUserFreelancerWithRelationIdLikeText()
    {
        $freelancer = Entity_User::createUser(Entity_User::TYPE_FREELANCER);
        $data = [
            'lastname'              => 'Joó',
            'firstname'             => 'Martin',
            'email'                 => uniqid() . '@szabaduszok.com',
            'password'              => 'Password123',
            'password_confirm'      => 'Password123',
            'address_postal_code'   => '9700',
            'address_city'          => 'Szombathely',
            'phonenumber'           => '06301923380',
            'short_description'     => 'Rövid bemutatkozás',
            'min_net_hourly_wage'   => '2500',
            'webpage'               => 'szabaduszok.com',
            'industries'            => [self::$_industries[0]],
            'professions'           => [self::$_professions[0], self::$_professions[1], self::$_professions[2]],
            'skills'                => [self::$_skills[2], self::$_skills[3], self::$_skills[5], self::$_skills[6], self::$_skills[7], self::$_skills[8], self::$_skillsOnTheFly[2]]
        ];

        $freelancer->submitUser($data, $this->getMailinglistMockToCreate('Gateway_Mailinglist_Mailchimp_Freelancer'));
        self::$_insertedUserIds[] = $freelancer->getUserId();

        $lastSkillId = DB::select('skill_id')->from('skills')->order_by('skill_id', 'DESC')->limit(1)->execute()->get('skill_id');

        $this->assertUserIdExistsInDatabase($freelancer->getUserId());
        $this->assertUserIdExistsInSession($freelancer->getUserId());
        $this->assertUserIdExistsInCache($freelancer->getUserId());
        $this->assertEmailNotExistsInSignup($freelancer->getEmail());
        $this->assertNotEmpty($freelancer->getPassword());
        $this->assertNotEmpty($freelancer->getSalt());
        $this->assertNotEmpty($freelancer->getSlug());
        $this->assertEquals('http://szabaduszok.com', $freelancer->getWebpage());
        $this->assertEquals('2500', $freelancer->getMinNetHourlyWage());
        $this->assertEquals('1', $freelancer->getSkillRelation());
        $this->assertEquals('1', $freelancer->getNeedProjectNotification());

        $this->assertUserRelationExistsInDatabase('industry', [self::$_industries[0]], $freelancer->getUserId());
        $this->assertUserRelationExistsInDatabase('profession', [self::$_professions[0], self::$_professions[1], self::$_professions[2]], $freelancer->getUserId());
        $this->assertUserRelationExistsInDatabase('skill', [self::$_skills[2], self::$_skills[3], self::$_skills[5], self::$_skills[6], self::$_skills[7], self::$_skills[8], $lastSkillId], $freelancer->getUserId());

        $this->assertUserProjectNotoficationExistsInDatabase('industry', [self::$_industries[0]], $freelancer->getUserId());
        $this->assertUserProjectNotoficationExistsInDatabase('profession', [self::$_professions[0], self::$_professions[1], self::$_professions[2]], $freelancer->getUserId());
        $this->assertUserProjectNotoficationExistsInDatabase('skill', [self::$_skills[2], self::$_skills[3], self::$_skills[5], self::$_skills[6], self::$_skills[7], self::$_skills[8], $lastSkillId], $freelancer->getUserId());
    }

    public function assertUserIdExistsInDatabase($id)
    {
        $user = DB::select()->from('users')->where('user_id', '=', $id)->execute()->current();
        $this->assertEquals($id, Arr::get($user, 'user_id'));
    }

    public function assertUserIdExistsInSession($id)
    {
        $sessionUser = Session::instance()->get('auth_user');
        $this->assertTrue($sessionUser->loaded());
        $this->assertEquals($id, $sessionUser->user_id);
    }

    public function assertUserIdExistsInCache($id)
    {
        $cacheUsers = Cache::instance()->get('users');
        $cacheUser = Arr::get($cacheUsers, $id);

        $this->assertTrue($cacheUser->loaded());
        $this->assertEquals($id, $cacheUser->user_id);
    }

    public function assertEmailNotExistsInSignup($email)
    {
        $signup = DB::select()->from('signups')->where('email', '=', $email)->execute()->current();
        $this->assertEmpty($signup);
    }

    public function assertUserRelationExistsInDatabase($relationSingularName, array $relationIds, $userId)
    {
        $class = 'Model_' . ucfirst($relationSingularName);
        $model = new $class();

        $relations = DB::select()
            ->from('users_' . $model->object_plural())
            ->where('user_id', '=', $userId)
            ->execute()->as_array();

        $this->assertEquals(count($relationIds), count($relations));

        /*foreach ($relations as $relation) {
            $this->assertTrue(in_array($relation[$model->primary_key()], $relationIds));
        }*/
    }

    public function assertUserProjectNotoficationExistsInDatabase($relationSingularName, array $relationIds, $userId)
    {
        $class = 'Model_' . ucfirst($relationSingularName);
        $model = new $class();

        $notifications = DB::select()
            ->from('users_project_notification_' . $model->object_plural())
            ->where('user_id', '=', $userId)
            ->and_where($model->primary_key(), 'IN', $relationIds)
            ->execute()->as_array();

        $this->assertEquals(count($relationIds), count($notifications));

        foreach ($notifications as $notification) {
            $this->assertTrue(in_array($notification[$model->primary_key()], $relationIds));
        }
    }

    public function assertUserProfilesExistInDatabase(array $profiles, $userId)
    {
        $userProfiles = DB::select()
            ->from('users_profiles')
            ->where('user_id', '=', $userId)
            ->execute()->as_array();

        foreach ($userProfiles as $userProfile) {
            $this->assertTrue(in_array($userProfile['url'], $profiles));
        }
    }

    public static function setUpBeforeClass()
    {
        self::truncateUsers();
        self::truncateRelations();
        self::initRelation('industry', 3);
        self::initRelation('profession', 5);
        self::initRelation('skill', 10);   
    }

    public static function tearDownAfterClass()
    {
        self::truncateUsers();
        self::truncateRelations();   
    }

    protected static function truncateUsers()
    {
        if (self::$_insertedUserIds) {
            DB::delete('users')->where('user_id', 'IN', self::$_insertedUserIds)->execute();
            Cache::instance()->set('users', []);
        }
    }

    protected static function truncateRelations()
    {
        if (self::$_industries) {
            $delete = DB::delete('industries')->where('industry_id', 'IN', self::$_industries)->execute();
            Cache::instance()->set('industries', []);
        }

        if (self::$_professions) {
            DB::delete('professions')->where('profession_id', 'IN', self::$_professions)->execute();
            Cache::instance()->set('professions', []);
        }

        if (self::$_skills) {
            DB::delete('skills')->where('skill_id', 'IN', self::$_skills)->execute();
            Cache::instance()->set('skills', []);
        }

        foreach (self::$_professionsOnTheFly as $item) {
            DB::delete('professions')->where('name', '=', $item)->execute();
        }

        foreach (self::$_skillsOnTheFly as $item) {
            DB::delete('skills')->where('name', '=', $item)->execute();
        }
    }

    /**
     * @param string $relation
     * @param int $count
     */
    protected static function initRelation($relation, $count)
    {
        $class = 'Model_' . ucfirst($relation);

        for ($i = 0; $i < $count; $i++) {
            $relationModel = new $class();
            $relationModel->name = $relation . '-' . ($i + 1);

            $relationModel->save();

            $classReflection = new ReflectionClass('Entity_User_Test');
            $ids = $classReflection->getStaticPropertyValue('_' . $relationModel->object_plural());
            $idCol = $relation . '_id';
            $ids[] = $relationModel->{$idCol};
            $classReflection->setStaticPropertyValue('_' . $relationModel->object_plural(), $ids);
        }
    }

    /**
     * @param string $class
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMailinglistMockToCreate($class)
    {
        $mailinglistMock = $this->getMockBuilder('\\' . $class)
            ->disableOriginalConstructor()
            ->setMethods(['subscribe', 'update'])
            ->getMock();

        $mailinglistMock->expects($this->once())
            ->method('subscribe')
            ->will($this->returnValue(true));

        $mailinglistMock->expects($this->never())
            ->method('update')
            ->will($this->returnValue(false));

        return $mailinglistMock;
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMailinglistMockToUpdate($class)
    {
        $mailinglistMock = $this->getMockBuilder('\\' . $class)
            ->disableOriginalConstructor()
            ->setMethods(['subscribe', 'update'])
            ->getMock();

        $mailinglistMock->expects($this->once())
            ->method('update')
            ->will($this->returnValue(false));

        $mailinglistMock->expects($this->never())
            ->method('subscribe')
            ->will($this->returnValue(true));

        return $mailinglistMock;
    }
}
