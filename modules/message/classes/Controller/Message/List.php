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
        $messagesByConversations        = Entity_Conversation::getMessagesByConversationsAndUser(
            $this->context->conversations, $user->user_id);

        foreach ($messagesByConversations as &$messages) {
            $messages = Business_Message::groupGivenMessagesByTextifiedDays($messages);
        }
        
        $this->context->messages = $messagesByConversations;
        //echo Debug::vars($this->context->messages[1362]);
        //exit;

        /*foreach ($this->context->messages[1362] as $date => $messagesByConversation) {
            echo Debug::vars($date);

            foreach ($messagesByConversation as $message) {
                echo Debug::vars($message->getMessage());
            }
        }
        exit;*/
    }
}