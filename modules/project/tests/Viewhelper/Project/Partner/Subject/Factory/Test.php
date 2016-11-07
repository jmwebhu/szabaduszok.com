<?php

class Viewhelper_Project_Partner_Subject_Factory_Test extends Unittest_TestCase
{
    /**
     * @covers Viewhelper_Project_Partner_Subject_Factory::createSubject()
     */
    public function testCreateTypeOk()
    {
        $type = Viewhelper_Project_Partner_Subject_Factory::createSubject(new Model_Project());
        $this->assertTrue($type instanceof Viewhelper_Project_Partner_Subject_Project);

        $type = Viewhelper_Project_Partner_Subject_Factory::createSubject(new Model_User());
        $this->assertTrue($type instanceof Viewhelper_Project_Partner_Subject_User);
    }

    /**
     * @covers Viewhelper_Project_Partner_Subject_Factory::createSubject()
     * @expectedException Exception
     */
    public function testCreateTypeNotOk()
    {
        Viewhelper_Project_Partner_Subject_Factory::createSubject(new Model_Industry());
    }
}