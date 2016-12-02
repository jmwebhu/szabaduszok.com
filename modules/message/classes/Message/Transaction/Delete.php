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
     * @param Message $message
     * @param Conversation_Participant $user
     */
    public function __construct(Message $message, Conversation_Participant $user)
    {
        $this->_message = $message;
        $this->_user    = $user;
        $this->_type    = Message_Transaction_Delete_Type_Factory::createType($message, $user);
    }

    public function delete()
    {
        return $this->_type->delete();
    }
}