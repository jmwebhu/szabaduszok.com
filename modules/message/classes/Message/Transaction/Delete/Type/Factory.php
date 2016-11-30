<?php

abstract class Message_Transaction_Delete_Type_Factory
{
    /**
     * @param Message $message
     * @param Conversation_Participant $user
     * @return Message_Transaction_Delete_Type
     */
    public static function createType(Message $message, Conversation_Participant $user)
    {
        if ($message->getSender()->getId() == $user->getId()) {
            $type = new Message_Transaction_Delete_Type_Outgoing();
        } else {
            $type = new Message_Transaction_Delete_Type_Incoming();
        }

        return $type;
    }
}