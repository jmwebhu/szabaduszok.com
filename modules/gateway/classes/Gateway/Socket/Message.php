<?php

class Gateway_Socket_Message extends Gateway_Socket
{    
    /**
     * @param  Conversation $conversation
     * @param  Message      $message
     * @return void
     */
    public function signalNew(Conversation $conversation, Message $message)
    {
        $participants = $conversation->getParticipantsExcept([
            Auth::instance()->get_user()->user_id
        ]);
        
        foreach ($participants as $participant) {
            $this->sendPost(
                $this->getDataToNewSignal($conversation, $participant, $message),
                'new'
            );
        }
    }

    /**
     * @param  int $userId
     * @return void
     */
    public function signalUpdateCount($userId)
    {
        $this->sendPost(
            $this->getDataToUpdateCount($userId),
            'update-count'
        );
    }
    
    /**
     * @param  Conversation             $conversation
     * @param  Conversation_Participant $participant
     * @param  Message                  $message
     * @return void
     */
    protected function getDataToNewSignal(Conversation $conversation, Conversation_Participant $participant, Message $message)
    {
        return [
            'message'           => $message->getMessage(),
            'conversation_id'   => $conversation->getId(),
            'room'              => $participant->getId(),
            'unread_count'      => Transaction_Message_Select::getCountOfUnreadBy($participant->getId())
        ];
    }

    /**
     * @param  int $userId
     * @return array
     */
    protected function getDataToUpdateCount($userId)
    {
        return [
            'room'          => $userId,
            'unread_count'  => Transaction_Message_Select::getCountOfUnreadBy($userId)
        ];        
    }
    
    /**
     * @return string
     */
    protected function getUri()
    {
        return 'message';
    }   
}