<?php

class Controller_Message_List extends Controller_DefaultTemplate
{
    public function action_index()
    {
        $user           = Auth::instance()->get_user();
        $transaction    = Transaction_Conversation_Select_Factory::createSelect();

        $models         = $transaction->getForLeftPanelBy($user->user_id);
        $entity         = new Entity_Conversation();
        $conversations  = $entity->getEntitiesFromModels($models);

        $this->context->conversations = $conversations;
    }
}