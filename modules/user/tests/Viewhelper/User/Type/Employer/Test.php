<?php

class Viewhelper_User_Type_Employer_Test extends Unittest_TestCase
{
    /**
     * @covers Viewhelper_User_Type_Employer::getEditUrl()
     */
    public function testGetEditUrl()
    {
        $user = new Entity_User_Employer();
        $user->setSlug('employer-1');

        $type   = new Viewhelper_User_Type_Employer_Create();
        $type->setUser($user);

        $this->assertEquals(Route::url('projectOwnerProfileEdit', ['slug' => 'employer-1']), $type->getEditUrl());
    }

    /**
     * @covers Viewhelper_User_Type_Employer::hasCv()
     */
    public function testHasCvNotOk()
    {
        $user = new Entity_User_Employer();
        $type   = new Viewhelper_User_Type_Employer_Create();
        $type->setUser($user);

        $this->assertFalse($type->hasCv());
    }

    /**
     * @covers Viewhelper_User_Type_Employer::getProjectNotificationRelationForProfile()
     */
    public function testGetProjectNotificationRelationForProfile()
    {
        $user = new Entity_User_Employer();
        $type   = new Viewhelper_User_Type_Employer_Create();
        $type->setUser($user);

        $this->assertEmpty($type->getProjectNotificationRelationForProfile(new Model_Industry()));
        $this->assertEmpty($type->getProjectNotificationRelationForProfile(new Model_Profession()));
        $this->assertEmpty($type->getProjectNotificationRelationForProfile(new Model_Skill()));
    }
}