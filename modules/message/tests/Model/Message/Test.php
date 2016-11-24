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

        $this->assertTrue(in_array(['not_empty'], $rules['sender_id']));
        $this->assertTrue(in_array(['not_empty'], $rules['receiver_id']));
        $this->assertTrue(in_array(['not_empty'], $rules['message']));
    }

    public function testGettersSetters()
    {
        $sender     = Entity_User::createUser(Entity_User::TYPE_EMPLOYER);
        $receiver   = Entity_User::createUser(Entity_User::TYPE_FREELANCER);

        $message    = new Model_Message(1);
        $message->setSender($sender);
        $message->setReceiver($receiver);
        $message->setMessage('Teszt');

        $this->assertNotEmpty($message->getSender());
        $this->assertNotEmpty($message->getReceiver());
        $this->assertEquals('Teszt', $message->getMessage());
        $this->assertNotEmpty($message->getData());
    }
}