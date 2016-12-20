<?php

abstract class Transaction_Conversation_Select_Factory
{
    /**
     * @param Model_Conversation $conversation
     * @return Transaction_Conversation_Select
     */
    public static function createSelect(Model_Conversation $conversation = null)
    {
        if ($conversation == null) {
            $conversation = new Model_Conversation;
        }

        return new Transaction_Conversation_Select(
            $conversation, new Model_Message, new Transaction_Message_Select(new Model_Message));
    }
}