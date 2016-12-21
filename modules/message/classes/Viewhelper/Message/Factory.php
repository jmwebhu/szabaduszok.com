<?php

abstract class Viewhelper_Message_Factory
{
    /**
     * @param  Entity_Message $message
     * @param  int         $userId
     * @return Viewhelper_Message
     */
    public static function createViewhelper(Entity_Message $message, $userId)
    {
        $viewhelper = null;
        if ($userId == $message->getSenderId()) {
            $viewhelper = new Viewhelper_Message_Outgoing($message, $userId);
        } else {
            $viewhelper = new Viewhelper_Message_Incoming($message, $userId);
        }   

        Assert::notNull($viewhelper);

        return $viewhelper; 
    }
    
}