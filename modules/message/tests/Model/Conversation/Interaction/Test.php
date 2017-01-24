<?php

class Model_Conversation_Interaction_Test extends Unittest_TestCase
{
    /**
     * @covers Model_Conversation_Interaction::rules()
     */
    public function testRules()
    {
        $interaction    = new Model_Conversation_Interaction();
        $rules          = $interaction->rules();

        $this->assertArrayHasKey('conversation_id', $rules);
        $this->assertArrayHasKey('user_id', $rules);

        $this->assertTrue(in_array(['not_empty'], $rules['conversation_id']));
        $this->assertTrue(in_array(['not_empty'], $rules['user_id']));
    }
}