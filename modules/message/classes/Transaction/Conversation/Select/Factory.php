<?php

abstract class Transaction_Conversation_Select_Factory
{
    /**
     * @return Transaction_Conversation_Select
     */
    public static function createSelect()
    {
        return new Transaction_Conversation_Select(
            new Model_Conversation(), new Model_Message(), new Transaction_Message_Select(new Model_Message()));
    }
}