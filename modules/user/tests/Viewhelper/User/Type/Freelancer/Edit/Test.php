<?php

class Viewhelper_User_Type_Freelancer_Edit_Test extends Unittest_TestCase
{
    /**
     * @covers Viewhelper_User_Type_Freelancer_Edit::getPageTitle()
     */
    public function testGetPageTitle()
    {
        $user   = new Entity_User_Freelancer();
        $type   = new Viewhelper_User_Type_Freelancer_Edit();
        $type->setUser($user);

        $this->assertEquals('Profil szerkesztése: ', $type->getPageTitle());
    }

    /**
     * @covers Viewhelper_User_Type_Freelancer_Edit::hasPrivacyCheckbox()
     */
    public function testHasPrivacyCheckbox()
    {
        $user   = new Entity_User_Freelancer();
        $type   = new Viewhelper_User_Type_Freelancer_Edit();
        $type->setUser($user);

        $this->assertFalse($type->hasPrivacyCheckbox());
    }

    /**
     * @covers Viewhelper_User_Type_Freelancer_Edit::getPasswordText()
     */
    public function testGetPasswordText()
    {
        $user   = new Entity_User_Freelancer();
        $type   = new Viewhelper_User_Type_Freelancer_Edit();
        $type->setUser($user);

        $this->assertEquals('Legalább 6 karakter. Ha nem módosítod, hagyd üresen!', $type->getPasswordText());
    }

    /**
     * @covers Viewhelper_User_Type_Freelancer_Edit::hasIdInput()
     */
    public function testHasIdInput()
    {
        $user   = new Entity_User_Freelancer();
        $type   = new Viewhelper_User_Type_Freelancer_Edit();
        $type->setUser($user);

        $this->assertTrue($type->hasIdInput());
    }

    /**
     * @covers Viewhelper_User_Type_Freelancer_Edit::getFormAction()
     */
    public function testGetFormAction()
    {
        $user   = new Entity_User_Freelancer();
        $user->setSlug('freelancer-1');
        $type   = new Viewhelper_User_Type_Freelancer_Edit();
        $type->setUser($user);

        $this->assertEquals(Route::url('freelancerProfileEdit', ['slug' => 'freelancer-1']), $type->getFormAction());
    }

    /**
     * @covers Viewhelper_User_Type_Freelancer_Edit::hasPasswordRules()
     */
    public function testHasPasswordRules()
    {
        $user   = new Entity_User_Freelancer();
        $type   = new Viewhelper_User_Type_Freelancer_Edit();
        $type->setUser($user);

        $this->assertFalse($type->hasPasswordRules());
    }
}