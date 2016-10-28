<?php

class Business_User_Employer_Test extends Unittest_TestCase
{
    protected $_model;

    /**
     * @covers Business_User_Employer::getSearchTextFromFields()
     */
    public function testGetSearchTextFromFieldsWithoutRelations()
    {
        $this->givenEmployerWithoutRelations();

        $business = new Business_User_Employer($this->_model);
        $actual = $business->getSearchTextFromFields();

        $expected = 'Joó Martin  ' . date('Y-m-d') . ' Szombathely    ';

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Business_User_Employer::getSearchTextFromFields()
     */
    public function testGetSearchTextFromFieldsWithoutRelationsWithCompany()
    {
        $this->givenEmployerWithoutRelations('Szabaduszok.com Zrt.');

        $business = new Business_User_Employer($this->_model);
        $actual = $business->getSearchTextFromFields();

        $expected = 'Joó Martin  ' . date('Y-m-d') . ' Szombathely Szabaduszok.com Zrt.   ';

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Business_User_Employer::getSearchTextFromFields()
     */
    public function testGetSearchTextFromFieldsWithRelationsWithoutCompany()
    {
        $model = new Model_User_Employer_Mock();
        $model->lastname = 'Joó';
        $model->firstname = 'Martin';
        $model->address_city = 'Szombathely';

        $business = new Business_User_Employer($model);
        $actual = $business->getSearchTextFromFields();

        $expected = 'Joó Martin  ' . date('Y-m-d') . ' Szombathely  industries professions ';

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Business_User_Employer::getSearchTextFromFields()
     */
    public function testGetSearchTextFromFieldsWithRelationsWithCompany()
    {
        $model = new Model_User_Employer_Mock();
        $model->lastname = 'Joó';
        $model->firstname = 'Martin';
        $model->address_city = 'Szombathely';
        $model->company_name = 'Szabaduszok.com Zrt';

        $business = new Business_User_Employer($model);
        $actual = $business->getSearchTextFromFields();

        $expected = 'Joó Martin  ' . date('Y-m-d') . ' Szombathely Szabaduszok.com Zrt industries professions ';

        $this->assertEquals($expected, $actual);
    }

    protected function givenEmployerWithoutRelations($companyName = null)
    {
        $model = new Model_User_Employer();
        $model->lastname = 'Joó';
        $model->firstname = 'Martin';
        $model->address_city = 'Szombathely';
        $model->company_name = $companyName;
        $model->is_company = ($companyName) ? 1 : 0;

        $this->_model = $model;
    }
}