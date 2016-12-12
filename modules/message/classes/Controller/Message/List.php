<?php

class Controller_Message_List extends Controller_DefaultTemplate
{
    public function action_index()
    {
        $user                           = Auth::instance()->get_user();
        $this->context->conversations   = Entity_Conversation::getForLeftPanelBy($user->user_id);
    }
}