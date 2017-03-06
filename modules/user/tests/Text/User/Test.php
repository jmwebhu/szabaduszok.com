<?php

class Text_User_Test extends Unittest_TestCase
{
    /**
     * @covers Text_User::fixPostalCode()
     */
    public function testFixPostalCode()
    {
        $this->assertEquals('9700', Text_User::fixPostalCode(['address_postal_code' => '9700'])['address_postal_code']);
        $this->assertEquals('9700', Text_User::fixPostalCode(['address_postal_code' => 9700])['address_postal_code']);
        $this->assertEquals(null, Text_User::fixPostalCode(['address_postal_code' => ''])['address_postal_code']);
        $this->assertEquals(null, Text_User::fixPostalCode(['address_postal_code' => null])['address_postal_code']);
        $this->assertEquals(null, Text_User::fixPostalCode(['address_postal_code' => 0])['address_postal_code']);
    }

    /**
     * @covers Text_User::fixUrl()
     */
    public function testFixUrl()
    {
        $this->assertEquals('http://szabaduszok.com', Text_User::fixUrl(['webpage' => 'szabaduszok.com'], 'webpage')['webpage']);
        $this->assertEquals('', Text_User::fixUrl(['webpage' => ''], 'webpage')['webpage']);
        $this->assertEquals(null, Text_User::fixUrl(['webpage' => null], 'webpage')['webpage']);
        $this->assertEquals('http://szabaduszok.com', Text_User::fixUrl(['webpage' => 'http://szabaduszok.com'], 'webpage')['webpage']);
        $this->assertEquals('https://szabaduszok.com', Text_User::fixUrl(['webpage' => 'https://szabaduszok.com'], 'webpage')['webpage']);
    }

    /**
     * @covers Text_User::alterCheckboxCompanyValue()
     */
    public function testAlterCheckboxCompanyValue()
    {
        $this->assertEquals(['is_company' => 1, 'company_name' => 'Szabaduszok.com Zrt.'], Text_User::alterCheckboxCompanyValue(['is_company' => 'on', 'company_name' => 'Szabaduszok.com Zrt.']));
        $this->assertEquals(['is_company' => 0, 'company_name' => null], Text_User::alterCheckboxCompanyValue(['is_company' => 'off', 'company_name' => 'Szabaduszok.com Zrt.']));
        $this->assertEquals(['is_company' => 0, 'company_name' => null], Text_User::alterCheckboxCompanyValue(['is_company' => 'off', 'company_name' => '']));
        $this->assertEquals(['is_company' => 0, 'company_name' => null], Text_User::alterCheckboxCompanyValue(['is_company' => '', 'company_name' => '']));
        $this->assertEquals(['is_company' => 0, 'company_name' => null], Text_User::alterCheckboxCompanyValue([]));
    }

    /**
     * @covers Text_User::alterCheckboxAbleToBillValue()
     */
    public function testAlterCheckboxAbleToBillValue()
    {
        $this->assertEquals(['is_able_to_bill' => 1], Text_User::alterCheckboxAbleToBillValue(['is_able_to_bill' => 'on']));
        $this->assertEquals(['is_able_to_bill' => 0], Text_User::alterCheckboxAbleToBillValue(['is_able_to_bill' => 'off']));
        $this->assertEquals(['is_able_to_bill' => 0], Text_User::alterCheckboxAbleToBillValue(['is_able_to_bill' => '']));
        $this->assertEquals(['is_able_to_bill' => 0], Text_User::alterCheckboxAbleToBillValue(['is_able_to_bill' => null]));
        $this->assertEquals(['is_able_to_bill' => 0], Text_User::alterCheckboxAbleToBillValue([]));
    }

    /**
     * @covers Text_User::addHttpTo()
     */
    public function testAddHttpTo()
    {
        $text = new Text_User();
        $this->assertEquals('http://szabaduszok.com', $this->invokeMethod($text, 'addHttpTo', ['szabaduszok.com']));
        $this->assertEquals('http://szabaduszok.com', $this->invokeMethod($text, 'addHttpTo', ['http://szabaduszok.com']));
        $this->assertEquals('https://szabaduszok.com', $this->invokeMethod($text, 'addHttpTo', ['https://szabaduszok.com']));
    }
    
    /** @test */
    function it_replace_commas_to_dots_in_professional_experience()
    {
        $this->assertEquals(['professional_experience' => '12.5'], Text_User::fixProfessionalExperience(['professional_experience' => '12,5']));
        $this->assertEquals(['professional_experience' => '12.5'], Text_User::fixProfessionalExperience(['professional_experience' => '12.5']));
        $this->assertEquals(['professional_experience' => '4'], Text_User::fixProfessionalExperience(['professional_experience' => '4']));

        $this->assertEquals(['professional_experience' => ''], Text_User::fixProfessionalExperience(['professional_experience' => '']));
        $this->assertEquals(['professional_experience' => ''], Text_User::fixProfessionalExperience([]));
    }
}