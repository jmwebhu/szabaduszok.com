<?php

class Business_Conversation_Test extends Unittest_TestCase
{
    /**
     * @covers Business_Conversation::getConcatedUserIdsFrom()
     * @dataProvider getConcatedUserIdsFromDataProvider()
     */
    public function testGetConcatedUserIdsFrom($expected, $actual)
    {
        $this->assertEquals($expected, $actual);
    }

    public function getConcatedUserIdsFromDataProvider()
    {
        return [
            [['original' => '1,2', 'reversed' => '2,1'], Business_Conversation::getConcatedUserIdsFrom([1, 2])],
            [['original' => '1,2,3', 'reversed' => '3,2,1'], Business_Conversation::getConcatedUserIdsFrom([1, 2, 3])],
        ];
    }
    
}