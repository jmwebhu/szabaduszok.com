<?php

class Model_Message_Interaction_Test extends Unittest_TestCase
{
    /**
     * @covers Model_Message_Interaction::rules()
     */
    public function testRules()
    {
        $interaction   = new Model_Message_Interaction();
        $rules          = $interaction->rules();

        $this->assertArrayHasKey('message_id', $rules);
        $this->assertArrayHasKey('user_id', $rules);

        $this->assertTrue(in_array(['not_empty'], $rules['message_id']));
        $this->assertTrue(in_array(['not_empty'], $rules['user_id']));
    }
}