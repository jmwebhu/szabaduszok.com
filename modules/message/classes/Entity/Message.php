<?php

class Entity_Message extends Entity implements Message
{
    const TYPE_INCOMING = 'incoming';
    const TYPE_OUTGOING = 'outgoing';

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

    /**
     * @param array $data
     */
    public function submit(array $data)
    {
        $transaction    = new Transaction_Message_Insert(new Model_Message(), $data);
        $this->_model   = $transaction->execute();
        $this->mapModelToThis();
    }

    /**
     * @param  int  $userId
     * @return boolean
     */
    public function getTypeBy($userId = null)
    {
        $userId = ($userId) ? $userId : Auth::instance()->get_user()->user_id;
        return ($this->_sender_id == $userId) ? self::TYPE_OUTGOING : self::TYPE_INCOMING;
    }

    /**
     * @param  int  $userId
     * @return boolean
     */
    public function getColorBy($userId = null)
    {
        $type = $this->getTypeBy($userId);
        return ($type == self::TYPE_OUTGOING) ? 'blue' : 'gray';
    }
}