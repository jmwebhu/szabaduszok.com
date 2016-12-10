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

    /**
     * @covers Arr::isLastIndex()
     * @dataProvider isLastIndexDataProvider
     */
    public function testIsLastIndex($expected, $actual)
    {
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Arr::setKey()
     * @dataProvider setKeyDataProvider
     */
    public function testSetKey($expected, $actual)
    {
        $this->assertEquals($expected, $actual);
    }

    public function setKeyDataProvider()
    {
        return [
            [['one' => 1, 'two' => 2, 'three' => []], Arr::setKey(['one' => 1, 'two' => 2], 'three')],
            [['one' => 1, 'two' => 2, 'three' => 3], Arr::setKey(['one' => 1, 'two' => 2], 'three', 3)],
            [['one' => 1, 'two' => 2, 'three' => 3], Arr::setKey(['one' => 1, 'two' => 2, 'three' => 3], 'three', 2)],
            [['one' => 1, 'two' => 2, 3 => []], Arr::setKey(['one' => 1, 'two' => 2], 3)],
            [['one' => 1, 'two' => 2, 3 => 3], Arr::setKey(['one' => 1, 'two' => 2], 3, 3)],
            [['one' => 1, 'two' => 2, 3 => 3], Arr::setKey(['one' => 1, 'two' => 2, 3 => 3], 3, 2)]
        ];
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

    public function isLastIndexDataProvider()
    {
        return [
            [true, Arr::isLastIndex(0, [])],
            [true, Arr::isLastIndex(0, ['a'])],
            [true, Arr::isLastIndex(2, [1, 2, 3])],
            [false, Arr::isLastIndex(1, [])],
            [false, Arr::isLastIndex(3, ['a'])],
            [false, Arr::isLastIndex(1, [1, 2, 3])],
        ];
    }
}
