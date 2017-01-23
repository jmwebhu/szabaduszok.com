<?php

class Arr_Test extends Unittest_TestCase
{
    /**
     * @covers Arr::removeEmptyValues()
     * @dataProvider removeEmptyValuesDataProvider
     */
    public function testRemoveEmptyValues($expected, $actual)
    {
        $this->assertEquals($expected, $actual);
    }

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

    /**
     * @covers Arr::concatValues()
     * @dataProvider concatValuesDataProvider
     */
    public function testConcatValues($expected, $actual)
    {
        $this->assertEquals($expected, $actual);
    }

    public function concatValuesDataProvider()
    {
        return [
            ['1,2,3', Arr::concatValues([1, 2, 3])],
            ['1.2.3', Arr::concatValues([1, 2, 3], '.')],
            ['one,two', Arr::concatValues(['one', 'two'])],
            ['one', Arr::concatValues(['one'])],
            ['one', Arr::concatValues(['one'], '.')],
            ['1', Arr::concatValues([1], '.')]
        ];
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
            [['3d'], Arr::uniqueString(['3d', '3d', '3D'])],
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

    public function removeEmptyValuesDataProvider()
    {
        return [
            [[0 => '112', 2 => 43], Arr::removeEmptyValues(['112', '0', 43])],  
            [[0 => 'first', 2 => 2], Arr::removeEmptyValues(['first', '', 2])],  
            [[], Arr::removeEmptyValues(['0', 0, null, false, ''])],  
        ];
    }
}
