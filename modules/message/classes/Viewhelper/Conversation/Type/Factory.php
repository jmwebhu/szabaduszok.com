<?php

abstract class Viewhelper_Conversation_Type_Factory
{
    /**
     * @param Conversation|Conversatoin $conversation
     * @param null $authUser
     * @return Conversation_Viewhelper_Type
     */
    public static function createType(Conversation $conversation, $authUser = null)
    {
        if ($authUser == null) {
            $authUser = Auth::instance()->get_user();
        }

        $participants   = $conversation->getParticipants();
        $type           = null;

        if (count($participants) == 2) {
            $type = new Viewhelper_Conversation_Type_Single($conversation, $authUser);
        } else {
            $type = new Viewhelper_Conversation_Type_Group($conversation, $authUser);
        }

        Assert::notNull($type);
        return $type;
    }
}