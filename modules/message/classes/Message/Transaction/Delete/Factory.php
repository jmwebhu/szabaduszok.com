<?php

abstract class Message_Transaction_Delete_Factory
{
    /**
     * @param Message $message
     * @param Conversation_Participant $user
     * @return Message_Transaction_Delete
     */
    public static function createDelete(Message $message, Conversation_Participant $user)
    {
        if ($message->getSender()->getId() == $user->getId()) {
            $type = new Message_Transaction_Delete_Outgoing($message, $user);
        } else {
            $type = new Message_Transaction_Delete_Incoming($message, $user);
        }

        return $type;
    }
}