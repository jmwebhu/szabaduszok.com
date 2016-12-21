<?php

class Transaction_Message_Select_Factory_Test extends Unittest_TestCase
{
    /**
     * @covers Transaction_Message_Select_Factory::createSelect()
     */
    public function testCreateSelect()
    {
        $transaction = Transaction_Message_Select_Factory::createSelect();

        $this->assertNotEmpty($transaction);
        $this->assertTrue($transaction instanceof Transaction_Message_Select);
        $this->assertTrue($transaction->getMessage() instanceof Model_Message);
    }
}