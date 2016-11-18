<?php

class Text_Test extends Unittest_Testcase
{
	/**
	 * @covers Text::isId()
	 * @dataProvider isIdDataProvider
	 * @group issue #17
	 */
	public function testIsId($expected, $actual)
	{
		$this->assertEquals($expected, $actual);
	}

	public function isIdDataProvider()
	{
		return [
			[false, Text::isId('1999 óta foglalkozom informatikával')],
			[true, Text::isId(217)],
			[true, Text::isId('332')],
			[false, Text::isId('Webfejlesztés')],
			[false, Text::isId(['Webfejlesztés'])],
			[false, Text::isId('123Webfejlesztés4231')]
		];
	}
}