<?php

class Viewhelper_User_Test extends Unittest_TestCase
{
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
}