<?php

abstract class Conversation_Viewhelper_Type_Factory
{
    /**
     * @param  Conversatoin $conversation 
     * @return Conversation_Viewhelper_Type                     
     */
    public static function createType(Conversatoin $conversation)
    {
        $participants   = $conversation->getParticipants();
        $type           = null;

        if (count($participants) == 2) {
            $type = new Conversation_Viewhelper_Type_Single;
        } else {
            $type = new Conversation_Viewhelper_Type_Group;
        }

        Assert::notNull($type);
        return $type;
    }
}