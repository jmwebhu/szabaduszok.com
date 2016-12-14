<?php

class Controller_Message_List extends Controller_DefaultTemplate
{
    public function action_index()
    {
        $user                   = Auth::instance()->get_user();
        $this->context->title   = 'Üzenetek ' . $user->name();
        $entity = new Entity_Conversation;

        $this->context->conversationUrl = $entity->getBySlug($this->request->param('slug'));

        $this->context->conversations   = Entity_Conversation::getForLeftPanelBy($user->user_id);
        $this->context->messages        = Business_Message::groupGivenMessagesByTextifiedDays(
            $this->context->conversationUrl->getMessagesBy($user->user_id));
    }
}