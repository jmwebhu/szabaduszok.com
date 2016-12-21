<?php

class Model_Conversation_User_Test extends Unittest_TestCase
{
    /**
     * @covers Model_Conversation_User::rules()
     */
    public function testRules()
    {
        $conversationUser   = new Model_Conversation_User();
        $rules              = $conversationUser->rules();

        $this->assertArrayHasKey('user_id', $rules);
        $this->assertArrayHasKey('conversation_id', $rules);

        $this->assertTrue(in_array(['not_empty'], $rules['user_id']));
        $this->assertTrue(in_array(['not_empty'], $rules['conversation_id']));
    }
}