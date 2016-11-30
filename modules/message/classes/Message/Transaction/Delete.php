<?php

class Message_Transaction_Delete
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
     * @var Message_Transaction_Delete_Type
     */
    protected $_type    = null;

    /**
     * Message_Transaction_Delete constructor.
     * @param Message $_message
     * @param Conversation_Participant $_user
     */
    public function __construct(Message $_message, Conversation_Participant $_user)
    {
        $this->_message = $_message;
        $this->_user    = $_user;
    }

    public function delete()
    {

    }
}