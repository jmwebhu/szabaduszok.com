<?php

class Text_User_Test extends Unittest_TestCase
{
    /**
     * @covers Text_User::fixPostalCode()
     * @dataProvider postalCodeDataProvider
     */
    public function testFixPostalCode($expected, $actualPost)
    {
        $this->assertEquals($expected, $actualPost['address_postal_code']);
    }

    /**
     * @covers Text_User::fixUrl()
     * @dataProvider fixUrlDataProvider
     */
    public function testFixUrl($expected, $actualPost)
    {
        $this->assertEquals($expected, $actualPost['webpage']);
    }

    /**
     * @covers Text_User::alterCheckboxValue()
     * @dataProvider alterCheckboxValuesDataProvider
     */
    public function testAlterCheckboxValues($expectedPost, $actualPost)
    {
        $this->assertEquals($expectedPost, $actualPost);
    }

    public function postalCodeDataProvider()
    {
        return [
            ['9700', Text_User::fixPostalCode(['address_postal_code' => '9700'])],
            ['9700', Text_User::fixPostalCode(['address_postal_code' => 9700])],
            [null, Text_User::fixPostalCode(['address_postal_code' => ''])],
            [null, Text_User::fixPostalCode(['address_postal_code' => null])],
            [null, Text_User::fixPostalCode(['address_postal_code' => 0])],
        ];
    }

    public function fixUrlDataProvider()
    {
        return [
            ['http://szabaduszok.com', Text_User::fixUrl(['webpage' => 'szabaduszok.com'], 'webpage')],
            ['', Text_User::fixUrl(['webpage' => ''], 'webpage')],
            [null, Text_User::fixUrl(['webpage' => null], 'webpage')],
            ['http://szabaduszok.com', Text_User::fixUrl(['webpage' => 'http://szabaduszok.com'], 'webpage')],
            ['https://szabaduszok.com', Text_User::fixUrl(['webpage' => 'https://szabaduszok.com'], 'webpage')],
        ];
    }

    public function alterCheckboxValuesDataProvider()
    {
        return [
            [['is_company' => 1, 'company_name' => 'Szabaduszok.com Zrt.'], Text_User::alterCheckboxValue(['is_company' => 'on', 'company_name' => 'Szabaduszok.com Zrt.'])],
            [['is_company' => 0, 'company_name' => null], Text_User::alterCheckboxValue(['is_company' => 'off', 'company_name' => 'Szabaduszok.com Zrt.'])],
            [['is_company' => 0, 'company_name' => null], Text_User::alterCheckboxValue(['is_company' => 'off', 'company_name' => ''])],
            [['is_company' => 0, 'company_name' => null], Text_User::alterCheckboxValue(['is_company' => '', 'company_name' => ''])],
            [['is_company' => 0, 'company_name' => null], Text_User::alterCheckboxValue([])],
        ];
    }
}