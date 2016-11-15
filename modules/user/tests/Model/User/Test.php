<?php

class Model_User_Test extends Unittest_TestCase
{
    /**
     * @covers Model_User::createUser()
     */
    public function testCreateUserEmployer()
    {
        $user = Model_User::createUser(Entity_User::TYPE_EMPLOYER);
        $this->assertTrue($user instanceof Model_User_Employer);
    }

    /**
     * @covers Model_User::createUser()
     */
    public function testCreateUserFreelancer()
    {
        $user = Model_User::createUser(Entity_User::TYPE_FREELANCER);
        $this->assertTrue($user instanceof Model_User_Freelancer);
    }

    /**
     * @covers Model_User::rules()
     */
    public function testRulesUser()
    {
        $user   = new Model_User();
        $rules  = $user->rules();

        $this->assertArrayHasKey('lastname', $rules);
        $this->assertArrayHasKey('firstname', $rules);
        $this->assertArrayHasKey('email', $rules);
        $this->assertArrayHasKey('password', $rules);
        $this->assertArrayHasKey('address_postal_code', $rules);

        $this->assertTrue(in_array(['not_empty'], $rules['lastname']));
        $this->assertTrue(in_array(['not_empty'], $rules['firstname']));
        $this->assertTrue(in_array(['not_empty'], $rules['password']));
    }


    /**
     * @covers Model_User_Freelancer::rules()
     */
    public function testRulesFreelancer()
    {
        $user   = new Model_User_Freelancer();
        $rules  = $user->rules();

        $this->assertArrayHasKey('lastname', $rules);
        $this->assertArrayHasKey('firstname', $rules);
        $this->assertArrayHasKey('email', $rules);
        $this->assertArrayHasKey('password', $rules);
        $this->assertArrayHasKey('address_postal_code', $rules);
        $this->assertArrayHasKey('min_net_hourly_wage', $rules);
        $this->assertArrayHasKey('webpage', $rules);

        $this->assertTrue(in_array(['not_empty'], $rules['lastname']));
        $this->assertTrue(in_array(['not_empty'], $rules['firstname']));
        $this->assertTrue(in_array(['not_empty'], $rules['password']));
        $this->assertTrue(in_array(['not_empty'], $rules['min_net_hourly_wage']));
    }

    /**
     * @covers Model_User_Employer::rules()
     */
    public function testRulesEmployer()
    {
        $user   = new Model_User_Employer();
        $rules  = $user->rules();

        $this->assertArrayHasKey('lastname', $rules);
        $this->assertArrayHasKey('firstname', $rules);
        $this->assertArrayHasKey('email', $rules);
        $this->assertArrayHasKey('password', $rules);
        $this->assertArrayHasKey('address_postal_code', $rules);
        $this->assertArrayHasKey('address_postal_code', $rules);
        $this->assertArrayHasKey('phonenumber', $rules);

        $this->assertTrue(in_array(['not_empty'], $rules['lastname']));
        $this->assertTrue(in_array(['not_empty'], $rules['firstname']));
        $this->assertTrue(in_array(['not_empty'], $rules['password']));
        $this->assertTrue(in_array(['not_empty'], $rules['address_postal_code']));
        $this->assertTrue(in_array(['not_empty'], $rules['phonenumber']));
    }
}