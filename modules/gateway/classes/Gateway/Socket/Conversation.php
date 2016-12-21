<?php

class Gateway_Socket_Conversation extends Gateway_Socket
{
    /**
     * @return array
     */
    protected function getDataToSignal(Conversation_Participant $participant)
    {
        $user = Auth::instance()->get_user();
        return [
            'conversation_id'   => $this->_conversation->getId(),
            'room'              => $participant->getId(),
            'firstname'         => $user->firstname,
            'lastname'          => $user->lastname,
            'picture'           => $user->list_picture_path,
            'root'              => URL::base(true, false)
        ];
    }

    /**
     * @return string
     */
    protected function getUri()
    {
        return 'conversation';
    }
}