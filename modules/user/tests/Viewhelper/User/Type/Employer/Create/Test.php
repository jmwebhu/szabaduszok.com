<?php

class Viewhelper_User_Type_Employer_Create_Test extends Unittest_TestCase
{
    /**
     * @covers Viewhelper_User_Type_Employer_Create::getPageTitle()
     */
    public function testGetPageTitle()
    {
        $user   = new Entity_User_Employer();
        $type   = new Viewhelper_User_Type_Employer_Create();
        $type->setUser($user);

        $this->assertEquals('Megbízó Regisztráció', $type->getPageTitle());
    }

    /**
     * @covers Viewhelper_User_Type_Employer_Create::hasPrivacyCheckbox()
     */
    public function testHasPrivacyCheckbox()
    {
        $user   = new Entity_User_Employer();
        $type   = new Viewhelper_User_Type_Employer_Create();
        $type->setUser($user);

        $this->assertTrue($type->hasPrivacyCheckbox());
    }

    /**
     * @covers Viewhelper_User_Type_Employer_Create::getPasswordText()
     */
    public function testGetPasswordText()
    {
        $user   = new Entity_User_Employer();
        $type   = new Viewhelper_User_Type_Employer_Create();
        $type->setUser($user);

        $this->assertEquals('Legalább 6 karakter', $type->getPasswordText());
    }

    /**
     * @covers Viewhelper_User_Type_Employer_Create::hasIdInput()
     */
    public function testHasIdInput()
    {
        $user   = new Entity_User_Employer();
        $type   = new Viewhelper_User_Type_Employer_Create();
        $type->setUser($user);

        $this->assertFalse($type->hasIdInput());
    }

    /**
     * @covers Viewhelper_User_Type_Employer_Create::getFormAction()
     */
    public function testGetFormAction()
    {
        $user   = new Entity_User_Employer();
        $type   = new Viewhelper_User_Type_Employer_Create();
        $type->setUser($user);

        $this->assertEquals(Route::url('projectOwnerRegistration'), $type->getFormAction());
    }

    /**
     * @covers Viewhelper_User_Type_Employer_Create::hasPasswordRules()
     */
    public function testHasPasswordRules()
    {
        $user   = new Entity_User_Employer();
        $type   = new Viewhelper_User_Type_Employer_Create();
        $type->setUser($user);

        $this->assertTrue($type->hasPasswordRules());
    }
}