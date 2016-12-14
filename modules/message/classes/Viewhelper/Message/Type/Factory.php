<?php

abstract class Viewhelper_Message_Type_Factory
{
    /**
     * @param  Entity_Message $message
     * @param  int         $userId
     * @return Viewhelper_Message_Type
     */
    public static function createType(Entity_Message $message, $userId)
    {
        $type = null;
        if ($userId == $message->getSenderId()) {
            $type = new Viewhelper_Message_Type_Outgoing($message, $userId);
        } else {
            $type = new Viewhelper_Message_Type_Incoming($message, $userId);
        }   

        Assert::notNull($type);

        return $type; 
    }
    
}