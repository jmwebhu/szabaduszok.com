<?php

class Entity_Message extends Entity implements Message
{
    /**
     * @var int
     */
    protected $_message_id;

    /**
     * @var int
     */
    protected $_conversation_id;

    /**
     * @var int
     */
    protected $_sender_id;

    /**
     * @var string
     */
    protected $_message;

    /**
     * @var string
     */
    protected $_created_at;

    /**
     * @var string
     */
    protected $_updated_at;

    /**
     * @return int
     */
    public function getMessageId()
    {
        return $this->_message_id;
    }

    /**
     * @return int
     */
    public function getConversationId()
    {
        return $this->_conversation_id;
    }

    /**
     * @return int
     */
    public function getSenderId()
    {
        return $this->_sender_id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_model->getId();
    }

    /**
     * @return Conversation_Participant
     */
    public function getSender()
    {
        return $this->_model->getSender();
    }

    /**
     * @return Conversation
     */
    public function getConversation()
    {
        return $this->_model->getConversation();
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->_model->getMessage();
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->_model->getCreatedAt();
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->_model->getUpdatedAt();
    }

    public function send()
    {
        // TODO: Implement send() method.
    }

    /**
     * @param Conversation_Participant $user
     */
    public function deleteMessage(Conversation_Participant $user)
    {
        $delete = Transaction_Message_Delete_Factory::createDelete($this, $user);
        $delete->execute();
    }
}