<?php

class Model_Message_Test extends Unittest_TestCase
{
    /**
     * @covers Model_Message::rules()
     */
    public function testRules()
    {
        $message    = new Model_Message();
        $rules      = $message->rules();

        $this->assertArrayHasKey('sender_id', $rules);
        $this->assertArrayHasKey('conversation_id', $rules);
        $this->assertArrayHasKey('message', $rules);

        $this->assertTrue(in_array(['not_empty'], $rules['sender_id']));
        $this->assertTrue(in_array(['not_empty'], $rules['conversation_id']));
        $this->assertTrue(in_array(['not_empty'], $rules['message']));
    }
}