<?php

class Business_User_Freelancer_Test extends Unittest_TestCase
{
    /**
     * @covers Business_User_Freelancer::getSearchTextFromFields()
     */
    public function testGetSearchTextFromFieldsWithSkills()
    {
        $model = new Model_User_Freelancer_Mock();
        $model->lastname = 'Joó';
        $model->firstname = 'Martin';
        $model->address_city = 'Szombathely';

        $business = new Business_User_Freelancer($model);
        $actual = $business->getSearchTextFromFields();

        $expected = 'Joó Martin  ' . date('Y-m-d') . ' Szombathely  industries professions skills';

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Business_User_Freelancer::getSearchTextFromFields()
     */
    public function testGetSearchTextFromFieldsWithAbleToBill()
    {
        $model = new Model_User_Freelancer_Mock();
        $model->lastname = 'Joó';
        $model->firstname = 'Martin';
        $model->address_city = 'Szombathely';

        $business = new Business_User_Freelancer($model);
        $actual = $business->getSearchTextFromFields();
        $expected = 'Joó Martin  ' . date('Y-m-d') . ' Szombathely  industries professions skills';
        $this->assertEquals($expected, $actual);


        $model->is_able_to_bill = 1;
        $business = new Business_User_Freelancer($model);
        $actual = $business->getSearchTextFromFields();
        $expected = 'Joó Martin  ' . date('Y-m-d') . ' Szombathely  industries professions skills Számlaképes';
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Business_User::getLastLoginFormatted()
     */
    public function testGetLastLoginFormatted()
    {
        $user = new Model_User_Freelancer();
        $user->last_login = time();

        $user1 = new Model_User_Freelancer();
        $user1->last_login = '';

        $user2 = new Model_User_Freelancer();
        $user2->last_login = null;

        $business = new Business_User_Freelancer($user);
        $business1 = new Business_User_Freelancer($user1);
        $business2 = new Business_User_Freelancer($user2);

        $this->assertEquals(date('Y-m-d', time()), $business->getLastLoginFormatted());
        $this->assertEquals('Még nem lépett be', $business1->getLastLoginFormatted());
        $this->assertEquals('Még nem lépett be', $business2->getLastLoginFormatted());
    }
}