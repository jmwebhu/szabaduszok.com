<?php

class Business_User_Freelancer_Test extends Unittest_TestCase
{
    /**
     * @covers Business_User_Freelancer::getSearchTextFromFields()
     */
    public function testGetSearchTextFromFieldsWithSkills()
    {
        $model = new Model_User_Employer_Mock();
        $model->lastname = 'Joó';
        $model->firstname = 'Martin';
        $model->address_city = 'Szombathely';

        $business = new Business_User_Freelancer($model);
        $actual = $business->getSearchTextFromFields();

        $expected = 'Joó Martin  ' . date('Y-m-d') . ' Szombathely  industries professions skills';

        $this->assertEquals($expected, $actual);
    }
}