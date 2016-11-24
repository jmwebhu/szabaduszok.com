<?php

class Model_Message_Test extends Unittest_TestCase
{
    /**
     * @covers Model_Message::rules()
     */
    public function testRules()
    {
        $project    = new Model_Message();
        $rules      = $project->rules();

        $this->assertArrayHasKey('sender_id', $rules);
        $this->assertArrayHasKey('receiver_id', $rules);
        $this->assertArrayHasKey('message', $rules);
        $this->assertArrayHasKey('created_at', $rules);

        $this->assertTrue(in_array(['not_empty'], $rules['sender_id']));
        $this->assertTrue(in_array(['not_empty'], $rules['receiver_id']));
        $this->assertTrue(in_array(['not_empty'], $rules['message']));
        $this->assertTrue(in_array(['not_empty'], $rules['created_at']));
    }
}