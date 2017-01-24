<?php

abstract class Transaction_Message_Delete implements Transaction
{
    /**
     * @var Message
     */
    protected $_message = null;

    /**
     * @var Conversation_Participant
     */
    protected $_user    = null;

    /**
     * @param Message $message
     * @param Conversation_Participant $user
     */
    public function __construct(Message $message, Conversation_Participant $user)
    {
        $this->_message = $message;
        $this->_user    = $user;
    }
}