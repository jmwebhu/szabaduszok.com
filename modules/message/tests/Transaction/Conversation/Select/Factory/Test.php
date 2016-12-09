<?php

class Transaction_Conversation_Select_Factory_Test extends Unittest_TestCase
{
    /**
     * @covers Transaction_Conversation_Select_Factory::createSelect()
     */
    public function testCreateSelect()
    {
        $transaction = Transaction_Conversation_Select_Factory::createSelect();

        $this->assertNotEmpty($transaction);
        $this->assertTrue($transaction instanceof Transaction_Conversation_Select);
        $this->assertTrue($transaction->getConversation() instanceof Model_Conversation);
        $this->assertTrue($transaction->getMessage() instanceof Model_Message);
        $this->assertTrue($transaction->getTransactionMessageSelect() instanceof Transaction_Message_Select);
    }
}