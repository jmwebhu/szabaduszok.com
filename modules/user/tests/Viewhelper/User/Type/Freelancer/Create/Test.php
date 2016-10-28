<?php

class Viewhelper_User_Type_Freelancer_Create_Test extends Unittest_TestCase
{
    /**
     * @var Entity_User_Freelancer
     */
    private static $_freelancer;

    /**
     * @covers Viewhelper_User_Type_Freelancer_Create::getPageTitle()
     */
    public function testGetPageTitle()
    {
        $user   = new Entity_User_Freelancer();
        $type   = new Viewhelper_User_Type_Freelancer_Create();
        $type->setUser($user);

        $this->assertEquals('Szabadúszó Regisztráció', $type->getPageTitle());
    }

    /**
     * @covers Viewhelper_User_Type_Freelancer_Create::hasPrivacyCheckbox()
     */
    public function testHasPrivacyCheckbox()
    {
        $user   = new Entity_User_Freelancer();
        $type   = new Viewhelper_User_Type_Freelancer_Create();
        $type->setUser($user);

        $this->assertTrue($type->hasPrivacyCheckbox());
    }

    /**
     * @covers Viewhelper_User_Type_Freelancer_Create::getPasswordText()
     */
    public function testGetPasswordText()
    {
        $user   = new Entity_User_Freelancer();
        $type   = new Viewhelper_User_Type_Freelancer_Create();
        $type->setUser($user);

        $this->assertEquals('Legalább 6 karakter', $type->getPasswordText());
    }

    /**
     * @covers Viewhelper_User_Type_Freelancer_Create::hasIdInput()
     */
    public function testHasIdInput()
    {
        $user   = new Entity_User_Freelancer();
        $type   = new Viewhelper_User_Type_Freelancer_Create();
        $type->setUser($user);

        $this->assertFalse($type->hasIdInput());
    }

    /**
     * @covers Viewhelper_User_Type_Freelancer_Create::getFormAction()
     */
    public function testGetFormAction()
    {
        $user   = new Entity_User_Freelancer();
        $type   = new Viewhelper_User_Type_Freelancer_Create();
        $type->setUser($user);

        $this->assertEquals(Route::url('freelancerRegistration'), $type->getFormAction());
    }

    /**
     * @covers Viewhelper_User_Type_Freelancer_Create::hasPasswordRules()
     */
    public function testHasPasswordRules()
    {
        $user   = new Entity_User_Freelancer();
        $type   = new Viewhelper_User_Type_Freelancer_Create();
        $type->setUser($user);

        $this->assertTrue($type->hasPasswordRules());
    }
}