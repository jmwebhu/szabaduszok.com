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

    /**
     * @return array
     */
    protected function getDataToSignal(Conversation_Participant $participant)
    {
        return [
            'message'           => $this->_message->getMessage(),
            'conversation_id'   => $this->_conversation->getId(),
            'room'              => $participant->getId()
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