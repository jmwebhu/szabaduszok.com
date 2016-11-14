<?php

class Arr_Test extends Unittest_TestCase
{
    /**
     * @covers Arr::uniqueString()
     * @dataProvider uniqueStringProvider
     */
    public function testUniqueString($expected, $actual)
    {
        $this->assertEquals($expected, $actual);
    }

    public function uniqueStringProvider()
    {
        return [
            [['webfejlesztés'], Arr::uniqueString(['webfejlesztés', 'Webfejlesztés'])],
            [['webfejlesztés', 'beágyazott rendszerek'], Arr::uniqueString(['webfejlesztés', 'beágyazott rendszerek'])],
            [['Webfejlesztés', 'java'], Arr::uniqueString(['Webfejlesztés', 'WebFEJLESZTÉS', 'java', 'JAVA'])],
            [[1, 2], Arr::uniqueString(['1', 2])],
        ];
    }
}
