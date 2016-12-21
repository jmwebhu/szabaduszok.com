<?php

class Gateway_Socket_Conversation extends Gateway_Socket
{
    /**
     * @param  Conversation $conversation
     * @return void
     */
    public function signalNew(Conversation $conversation)
    {
        $participants = $conversation->getParticipantsExcept([
            Auth::instance()->get_user()->user_id
        ]);
        
        foreach ($participants as $participant) {
            $this->sendPost(
                $this->getDataToNewSignal($participant, $conversation),
                'new'
            );
        }
    }

    /**
     * @param  Conversation_Participant $participant
     * @param  Conversation             $conversation
     * @return array
     */
    protected function getDataToNewSignal(Conversation_Participant $participant, Conversation $conversation)
    {
        $user = Auth::instance()->get_user();
        return [
            'conversation_id'   => $conversation->getId(),
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