<?php

class Model_Project_Partner_Type_Factory_Test extends Unittest_TestCase
{
    /**
     * @covers Model_Project_Partner_Type_Factory::createType()
     */
    public function testCreateTypeCandidateOk()
    {
        $type = Model_Project_Partner_Type_Factory::createType(Model_Project_Partner::TYPE_CANDIDATE);
        $this->assertTrue($type instanceof Model_Project_Partner_Type_Candidate);
    }

    /**
     * @covers Model_Project_Partner_Type_Factory::createType()
     */
    public function testCreateTypeParticipantOk()
    {
        $type = Model_Project_Partner_Type_Factory::createType(Model_Project_Partner::TYPE_PARTICIPANT);
        $this->assertTrue($type instanceof Model_Project_Partner_Type_Participant);
    }

    /**
     * @covers Model_Project_Partner_Type_Factory::createType()
     */
    public function testCreateTypeNoTypeGiven()
    {
        $type = Model_Project_Partner_Type_Factory::createType();
        $this->assertTrue($type instanceof Model_Project_Partner_Type_Candidate);

        $type = Model_Project_Partner_Type_Factory::createType('');
        $this->assertTrue($type instanceof Model_Project_Partner_Type_Candidate);

        $type = Model_Project_Partner_Type_Factory::createType(null);
        $this->assertTrue($type instanceof Model_Project_Partner_Type_Candidate);
    }
}