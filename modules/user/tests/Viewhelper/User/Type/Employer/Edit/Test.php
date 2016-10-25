<?php

class Viewhelper_User_Type_Employer_Edit_Test extends Unittest_TestCase
{
    /**
     * @covers Viewhelper_User_Type_Employer_Edit::getPageTitle()
     */
    public function testGetPageTitle()
    {
        $user   = new Entity_User_Employer();
        $type   = new Viewhelper_User_Type_Employer_Edit();
        $type->setUser($user);

        $this->assertEquals('Profil szerkesztése: ', $type->getPageTitle());
    }

    /**
     * @covers Viewhelper_User_Type_Employer_Edit::hasPrivacyCheckbox()
     */
    public function testHasPrivacyCheckbox()
    {
        $user   = new Entity_User_Employer();
        $type   = new Viewhelper_User_Type_Employer_Edit();
        $type->setUser($user);

        $this->assertFalse($type->hasPrivacyCheckbox());
    }

    /**
     * @covers Viewhelper_User_Type_Employer_Edit::getPasswordText()
     */
    public function testGetPasswordText()
    {
        $user   = new Entity_User_Employer();
        $type   = new Viewhelper_User_Type_Employer_Edit();
        $type->setUser($user);

        $this->assertEquals('Legalább 6 karakter. Ha nem módosítod, hagyd üresen!', $type->getPasswordText());
    }

    /**
     * @covers Viewhelper_User_Type_Employer_Edit::hasIdInput()
     */
    public function testHasIdInput()
    {
        $user   = new Entity_User_Employer();
        $type   = new Viewhelper_User_Type_Employer_Edit();
        $type->setUser($user);

        $this->assertTrue($type->hasIdInput());
    }

    /**
     * @covers Viewhelper_User_Type_Employer_Edit::getFormAction()
     */
    public function testGetFormAction()
    {
        $user   = new Entity_User_Employer();
        $user->setSlug('employer-1');
        $type   = new Viewhelper_User_Type_Employer_Edit();
        $type->setUser($user);

        $this->assertEquals(Route::url('projectOwnerProfileEdit', ['slug' => 'employer-1']), $type->getFormAction());
    }

    /**
     * @covers Viewhelper_User_Type_Employer_Edit::hasPasswordRules()
     */
    public function testHasPasswordRules()
    {
        $user   = new Entity_User_Employer();
        $type   = new Viewhelper_User_Type_Employer_Edit();
        $type->setUser($user);

        $this->assertFalse($type->hasPasswordRules());
    }
}