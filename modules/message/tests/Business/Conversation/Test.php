<?php

class Business_Conversation_Test extends Unittest_TestCase
{
    /**
     * @var string[]
     */
    protected $_slugs = [];
    
    /**
     * @covers Business_Conversation::getConcatedUserIdsFrom()
     * @dataProvider getConcatedUserIdsFromDataProvider()
     */
    public function testGetConcatedUserIdsFrom($expected, $actual)
    {
        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers Business_Conversation::putIntoFirstPlace()
     */
    public function testPutIntoFirstPlace()
    {
        // Kozepso elem
        $conversations  = $this->getConversations();
        $replaced       = Business_Conversation::putIntoFirstPlace(
            $conversations, $this->_slugs[7]);

        $this->assertEquals($replaced[0]->getSlug(), $this->_slugs[7]);
        $this->assertNotEquals($replaced[7]->getSlug(), $this->_slugs[7]);

        $this->assertEquals($replaced[7]->getSlug(), $this->_slugs[6]);
        $this->assertEquals($replaced[8]->getSlug(), $this->_slugs[8]);
        $this->assertEquals($replaced[9]->getSlug(), $this->_slugs[9]);

        // Elso elem
        $conversations  = $this->getConversations();
        $replaced       = Business_Conversation::putIntoFirstPlace(
            $conversations, $this->_slugs[0]);

        $this->assertEquals($replaced[0]->getSlug(), $this->_slugs[0]);

        for ($i = 1; $i < 10; $i++) {
            $this->assertEquals($replaced[$i]->getSlug(), $this->_slugs[$i]);    
        }

        // Utolso elem
        $conversations  = $this->getConversations();
        $replaced       = Business_Conversation::putIntoFirstPlace(
            $conversations, $this->_slugs[9]);

        $this->assertEquals($replaced[0]->getSlug(), $this->_slugs[9]);
        $this->assertNotEquals($replaced[9]->getSlug(), $this->_slugs[9]);

        for ($i = 1; $i < 10; $i++) {
            $this->assertEquals($replaced[$i]->getSlug(), $this->_slugs[$i - 1]);            
        }
    }

    protected function getConversations()
    {
        $models     = [];
        $entities   = [];

        for ($i = 0; $i < 10; $i++) {
            $model = new Model_Conversation;
            $model->slug = Text::randomString($i + 1);

            $this->_slugs[$i] = $model->slug;

            $models[$i] = $model;
        }

        foreach ($models as $model) {
            $entity     = new Entity_Conversation($model);
            $entities[] = $entity;
        }

        return $entities;
    }
    
    

    public function getConcatedUserIdsFromDataProvider()
    {
        return [
            [['original' => '1,2', 'reversed' => '2,1'], Business_Conversation::getConcatedUserIdsFrom([1, 2])],
            [['original' => '1,2,3', 'reversed' => '3,2,1'], Business_Conversation::getConcatedUserIdsFrom([1, 2, 3])],
        ];
    }
    
}