<?php

class Model_Conversation_Test extends Unittest_TestCase
{
    /**
     * @covers Model_Conversation::rules()
     */
    public function testRules()
    {
        $conversation   = new Model_Conversation();
        $rules          = $conversation->rules();

        $this->assertArrayHasKey('name', $rules);
        $this->assertArrayHasKey('slug', $rules);

        $this->assertTrue(in_array(['not_empty'], $rules['name']));
        $this->assertTrue(in_array(['not_empty'], $rules['slug']));
    }
}