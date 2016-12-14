<?php

class Controller_Message_List extends Controller_DefaultTemplate
{
    public function action_index()
    {
        $user   = Auth::instance()->get_user();
        $entity = new Entity_Conversation;

        $conversation = $entity->getBySlug($this->request->param('slug'));

        $this->context->conversations   = Entity_Conversation::getForLeftPanelBy($user->user_id);
        $messagesByConversations        = Entity_Conversation::getMessagesByConversationsAndUser(
            $this->context->conversations, $user->user_id);

        foreach ($messagesByConversations as &$messages) {
            $messages = Business_Message::groupGivenMessagesByTextifiedDays($messages);
        }
        
        $this->context->messages = $messagesByConversations;
    }
}