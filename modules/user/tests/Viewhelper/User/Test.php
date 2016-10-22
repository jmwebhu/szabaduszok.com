<?php

class Viewhelper_User_Test extends Unittest_TestCase
{
    /**
     * @var Entity_User
     */
    private static $_freelancer;

    /**
     * @var Entity_User
     */
    private static $_employer;

    /**
     * @covers Viewhelper_User::getEditUrl()
     */
    public function testGetEditUrlFreelancer()
    {
        $user = new Entity_User_Freelancer();
        $user->setSlug('freelancer-1');

        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_CREATE);

        $this->assertEquals(Route::url('freelancerProfileEdit', ['slug' => $user->getSlug()]), $viewhelper->getEditUrl($user));
    }

    /**
     * @covers Viewhelper_User::getEditUrl()
     */
    public function testGetEditUrlEmployer()
    {
        $user = new Entity_User_Employer();
        $user->setSlug('employer-1');

        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_CREATE);

        $this->assertEquals(Route::url('projectOwnerProfileEdit', ['slug' => $user->getSlug()]), $viewhelper->getEditUrl($user));
    }

    /**
     * @covers Viewhelper_User::getPageTitle()
     */
    public function testGetPageTitleFreelancerCreate()
    {
        $user       = new Entity_User_Freelancer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_CREATE);

        $this->assertEquals('Szabadúszó Regisztráció', $viewhelper->getPageTitle());
    }

    /**
     * @covers Viewhelper_User::getPageTitle()
     */
    public function testGetPageTitleFreelancerEdit()
    {
        $user       = new Entity_User_Freelancer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_EDIT);

        $this->assertEquals('Profil szerkesztése: ', $viewhelper->getPageTitle());
    }

    /**
     * @covers Viewhelper_User::getPageTitle()
     */
    public function testGetPageTitleEmployerCreate()
    {
        $user       = new Entity_User_Employer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_CREATE);

        $this->assertEquals('Megbízó Regisztráció', $viewhelper->getPageTitle());
    }

    /**
     * @covers Viewhelper_User::getPageTitle()
     */
    public function testGetPageTitleEmployerEdit()
    {
        $user       = new Entity_User_Employer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_EDIT);

        $this->assertEquals('Profil szerkesztése: ', $viewhelper->getPageTitle());
    }

    /**
     * @covers Viewhelper_User::hasPrivacyCheckbox()
     */
    public function testHasPrivacyCheckboxFreelancerCreate()
    {
        $user       = new Entity_User_Freelancer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_CREATE);

        $this->assertTrue($viewhelper->hasPrivacyCheckbox());
    }

    /**
     * @covers Viewhelper_User::hasPrivacyCheckbox()
     */
    public function testHasPrivacyCheckboxFreelancerEdit()
    {
        $user       = new Entity_User_Freelancer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_EDIT);

        $this->assertFalse($viewhelper->hasPrivacyCheckbox());
    }

    /**
     * @covers Viewhelper_User::hasPrivacyCheckbox()
     */
    public function testHasPrivacyCheckboxCreate()
    {
        $user       = new Entity_User_Employer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_CREATE);

        $this->assertTrue($viewhelper->hasPrivacyCheckbox());
    }

    /**
     * @covers Viewhelper_User::hasPrivacyCheckbox()
     */
    public function testHasPrivacyCheckboxEmployerEdit()
    {
        $user       = new Entity_User_Employer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_EDIT);

        $this->assertFalse($viewhelper->hasPrivacyCheckbox());
    }

    /**
     * @covers Viewhelper_User::getPasswordText()
     */
    public function testGetPasswordTextFreelancerCreate()
    {
        $user       = new Entity_User_Freelancer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_CREATE);

        $this->assertEquals('Legalább 6 karakter', $viewhelper->getPasswordText());
    }

    /**
     * @covers Viewhelper_User::getPasswordText()
     */
    public function testGetPasswordTextFreelancerEdit()
    {
        $user       = new Entity_User_Freelancer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_EDIT);

        $this->assertEquals('Legalább 6 karakter. Ha nem módosítod, hagyd üresen!', $viewhelper->getPasswordText());
    }

    /**
     * @covers Viewhelper_User::getPasswordText()
     */
    public function testGetPasswordTextCreate()
    {
        $user       = new Entity_User_Employer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_CREATE);

        $this->assertEquals('Legalább 6 karakter', $viewhelper->getPasswordText());
    }

    /**
     * @covers Viewhelper_User::getPasswordText()
     */
    public function testGetPasswordTextEmployerEdit()
    {
        $user       = new Entity_User_Employer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_EDIT);

        $this->assertEquals('Legalább 6 karakter. Ha nem módosítod, hagyd üresen!', $viewhelper->getPasswordText());
    }

    /**
     * @covers Viewhelper_User::hasIdInput()
     */
    public function testHasIdInputFreelancerCreate()
    {
        $user       = new Entity_User_Freelancer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_CREATE);

        $this->assertFalse($viewhelper->hasIdInput());
    }

    /**
     * @covers Viewhelper_User::hasIdInput()
     */
    public function testHasIdInputFreelancerEdit()
    {
        $user       = new Entity_User_Freelancer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_EDIT);

        $this->assertTrue($viewhelper->hasIdInput());
    }

    /**
     * @covers Viewhelper_User::hasIdInput()
     */
    public function testHasIdInputEmployerCreate()
    {
        $user       = new Entity_User_Employer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_CREATE);

        $this->assertFalse($viewhelper->hasIdInput());
    }

    /**
     * @covers Viewhelper_User::hasIdInput()
     */
    public function testHasIdInputEmployerEdit()
    {
        $user       = new Entity_User_Employer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_EDIT);

        $this->assertTrue($viewhelper->hasIdInput());
    }

    /**
     * @covers Viewhelper_User::getFormAction()
     */
    public function testGetFormActionFreelancerCreate()
    {
        $user       = new Entity_User_Freelancer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_CREATE);

        $this->assertEquals(Route::url('freelancerRegistration'), $viewhelper->getFormAction());
    }

    /**
     * @covers Viewhelper_User::getFormAction()
     */
    public function testGetFormActionFreelancerEdit()
    {
        $user       = new Entity_User_Freelancer();
        $user->setSlug('freelancer-1');
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_EDIT);

        $this->assertEquals(Route::url('freelancerProfileEdit', ['slug' => $user->getSlug()]), $viewhelper->getFormAction());
    }

    /**
     * @covers Viewhelper_User::getFormAction()
     */
    public function testGetFormActionEmployerCreate()
    {
        $user       = new Entity_User_Employer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_CREATE);

        $this->assertEquals(Route::url('projectOwnerRegistration'), $viewhelper->getFormAction());
    }

    /**
     * @covers Viewhelper_User::getFormAction()
     */
    public function testGetFormActionEmployerEdit()
    {
        $user       = new Entity_User_Employer();
        $user->setSlug('employer-1');
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_EDIT);

        $this->assertEquals(Route::url('projectOwnerProfileEdit', ['slug' => $user->getSlug()]), $viewhelper->getFormAction());
    }

    /**
     * @covers Viewhelper_User::hasPasswordRules()
     */
    public function testHasPasswordRulesFreelancerCreate()
    {
        $user       = new Entity_User_Freelancer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_CREATE);

        $this->assertTrue($viewhelper->hasPasswordRules());
    }

    /**
     * @covers Viewhelper_User::hasPasswordRules()
     */
    public function testHasPasswordRulesFreelancerEdit()
    {
        $user       = new Entity_User_Freelancer();
        $user->setSlug('freelancer-1');
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_EDIT);

        $this->assertFalse($viewhelper->hasPasswordRules());
    }

    /**
     * @covers Viewhelper_User::hasPasswordRules()
     */
    public function testHasPasswordRulesEmployerCreate()
    {
        $user       = new Entity_User_Employer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_CREATE);

        $this->assertTrue($viewhelper->hasPasswordRules());
    }

    /**
     * @covers Viewhelper_User::hasPasswordRules()
     */
    public function testHasPasswordRulesEmployerEdit()
    {
        $user       = new Entity_User_Employer();
        $user->setSlug('employer-1');
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_EDIT);

        $this->assertFalse($viewhelper->hasPasswordRules());
    }

    /**
     * @covers Viewhelper_User::hasPicture()
     */
    public function testHasPictureFreelancerCreateNotOK()
    {
        $user       = new Entity_User_Freelancer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_CREATE);

        $this->assertFalse($viewhelper->hasPicture());
    }

    /**
     * @covers Viewhelper_User::hasPicture()
     */
    public function testHasPictureFreelancerEditNotOk()
    {
        $viewhelper = Viewhelper_User_Factory::createViewhelper(self::$_freelancer, Viewhelper_User::ACTION_EDIT);
        $this->assertFalse($viewhelper->hasPicture());
    }

    /**
     * @covers Viewhelper_User::hasPicture()
     */
    public function testHasPictureFreelancerEditOk()
    {
        self::$_freelancer->setProfilePicturePath('szabaduszok-freelancer-pic.jpg');
        $viewhelper = Viewhelper_User_Factory::createViewhelper(self::$_freelancer, Viewhelper_User::ACTION_EDIT);

        $this->assertTrue($viewhelper->hasPicture());
    }

    /**
     * @covers Viewhelper_User::hasPicture()
     */
    public function testHasPictureEmployerCreateNotOK()
    {
        $user       = new Entity_User_Employer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_CREATE);

        $this->assertFalse($viewhelper->hasPicture());
    }

    /**
     * @covers Viewhelper_User::hasPicture()
     */
    public function testHasPictureEmployerEditNotOk()
    {
        $viewhelper = Viewhelper_User_Factory::createViewhelper(self::$_employer, Viewhelper_User::ACTION_EDIT);
        $this->assertFalse($viewhelper->hasPicture());
    }

    /**
     * @covers Viewhelper_User::hasPicture()
     */
    public function testHasPictureEmployerEditOk()
    {
        self::$_employer->setProfilePicturePath('szabaduszok-employer-pic.jpg');
        $viewhelper = Viewhelper_User_Factory::createViewhelper(self::$_employer, Viewhelper_User::ACTION_EDIT);

        $this->assertTrue($viewhelper->hasPicture());
    }

    /**
     * @covers Viewhelper_User::hasCv()
     */
    public function testHasCvFreelancerCreateNotOK()
    {
        $user       = new Entity_User_Freelancer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_CREATE);

        $this->assertFalse($viewhelper->hasCv());
    }

    /**
     * @covers Viewhelper_User::hasCv()
     */
    public function testHasCvFreelancerEditNotOk()
    {
        $viewhelper = Viewhelper_User_Factory::createViewhelper(self::$_freelancer, Viewhelper_User::ACTION_EDIT);
        $this->assertFalse($viewhelper->hasCv());
    }

    /**
     * @covers Viewhelper_User::hasCv()
     */
    public function testHasCvFreelancerEditOk()
    {
        self::$_freelancer->setCvPath('szabaduszok-freelancer-cv.jpg');
        $viewhelper = Viewhelper_User_Factory::createViewhelper(self::$_freelancer, Viewhelper_User::ACTION_EDIT);

        $this->assertTrue($viewhelper->hasCv());
    }

    /**
     * @covers Viewhelper_User::hasCv()
     */
    public function testHasCvEmployerCreateNotOk()
    {
        $user       = new Entity_User_Employer();
        $viewhelper = Viewhelper_User_Factory::createViewhelper($user, Viewhelper_User::ACTION_CREATE);

        $this->assertFalse($viewhelper->hasCv());
    }

    /**
     * @covers Viewhelper_User::hasCv()
     */
    public function testHasCvEmployerEditNotOk()
    {
        $viewhelper = Viewhelper_User_Factory::createViewhelper(self::$_employer, Viewhelper_User::ACTION_EDIT);
        $this->assertFalse($viewhelper->hasCv());
    }

    public static function setUpBeforeClass()
    {
        $freelancer = new Model_User_Freelancer();
        $freelancer->user_id = 1847;
        $freelancer->lastname = 'Teszt';
        $freelancer->firstname = 'Szabadúszó';
        $freelancer->email = 'teszt1847@szabaduszok.com';
        $freelancer->password = 'pass';
        $freelancer->min_net_hourly_wage = '2500';
        $freelancer->save();

        self::$_freelancer = Entity_User::createUser(Entity_User::TYPE_FREELANCER, $freelancer);

        $employer = new Model_User_Freelancer();
        $employer->user_id = 1848;
        $employer->lastname = 'Teszt';
        $employer->firstname = 'Megbízó';
        $employer->email = 'teszt1848@szabaduszok.com';
        $employer->phonenumber = '063012345678';
        $employer->password = 'pass';
        $employer->address_postal_code = '9700';
        $employer->address_city = 'Szombathely';
        $employer->save();

        self::$_employer = Entity_User::createUser(Entity_User::TYPE_EMPLOYER, $employer);
    }

    public static function tearDownAfterClass()
    {
        $freelancer = new Model_User_Freelancer(1847);
        $freelancer->delete();

        $employer = new Model_User_Employer(1848);
        $employer->delete();
    }
}