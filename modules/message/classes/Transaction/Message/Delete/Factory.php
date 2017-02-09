<?php

abstract class Transaction_Message_Delete_Factory
{
    /**
     * @param Message $message
     * @param Conversation_Participant $user
     * @return Transaction_Message_Delete
     */
    public static function createDelete(Message $message, Conversation_Participant $user)
    {
        if ($message->getSender()->getId() == $user->getId()) {
            return new Transaction_Message_Delete_Outgoing($message, $user);
        } else {
            return new Transaction_Message_Delete_Incoming($message, $user);
        }
    }
}
