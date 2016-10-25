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
     * @covers Text_User::alterCheckboxValue()
     */
    public function testAlterCheckboxValues()
    {
        $this->assertEquals(['is_company' => 1, 'company_name' => 'Szabaduszok.com Zrt.'], Text_User::alterCheckboxValue(['is_company' => 'on', 'company_name' => 'Szabaduszok.com Zrt.']));
        $this->assertEquals(['is_company' => 0, 'company_name' => null], Text_User::alterCheckboxValue(['is_company' => 'off', 'company_name' => 'Szabaduszok.com Zrt.']));
        $this->assertEquals(['is_company' => 0, 'company_name' => null], Text_User::alterCheckboxValue(['is_company' => 'off', 'company_name' => '']));
        $this->assertEquals(['is_company' => 0, 'company_name' => null], Text_User::alterCheckboxValue(['is_company' => '', 'company_name' => '']));
        $this->assertEquals(['is_company' => 0, 'company_name' => null], Text_User::alterCheckboxValue([]));
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
}