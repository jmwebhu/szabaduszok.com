<?php

class Gateway_Socket_Conversation extends Gateway_Socket
{
    /**
     * @return array
     */
    protected function getDataToSignal(Conversation_Participant $participant)
    {
        return [
            'conversation'      => $this->_conversation->getId(),
            'participant'       => $participant->getData(),
            'room'              => $participant->getId()
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