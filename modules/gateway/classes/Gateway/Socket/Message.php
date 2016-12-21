<?php

class Gateway_Socket_Message extends Gateway_Socket
{    
    /**
     * @var Conversation
     */
    protected $_conversation;

    /**
     * @var Message
     */
    protected $_message;
    

    /**
     * @param Conversation   $conversation   
     */
    public function __construct(Conversation $conversation, Message $message)
    {
        parent::__construct();
        $this->_conversation    = $conversation;
        $this->_message         = $message;
    }

    public function signal()
    {        
        $participants = $this->_conversation->getParticipantsExcept([Auth::instance()->get_user()->user_id]);
        foreach ($participants as $participant) {
            $this->signalOneParticipant($participant);
        }
    }

    /**
     * @param  Conversation_Participant   $participant
     * @return void
     */
    protected function signalOneParticipant(Conversation_Participant $participant)
    {
        $this->sendPost([
            'message'           => $this->_message->getMessage(),
            'conversation_id'   => $this->_conversation->getId(),
            'room'              => $participant->getId()
        ]);
    }
    
    /**
     * @return string
     */
    protected function getUri()
    {
        return 'message';
    }   
}