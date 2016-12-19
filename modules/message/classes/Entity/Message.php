<?php

class Entity_Message extends Entity implements Message
{
    /**
     * @var Viewhelper_Message
     */
    protected $_viewhelper;
    

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

    public function send(array $data)
    {
        return $this->submit($data);
    }

    public function __construct($value = null)
    {
        parent::__construct($value);
        $this->_viewhelper = Viewhelper_Message_Factory::createViewhelper($this, Auth::instance()->get_user()->user_id);
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

        return $this->_message_id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_viewhelper->getType();
    }

    /**
     * @return string
     */
    public function getColor($userId = null)
    {
        return $this->_viewhelper->getColor();
    }

    /**
     * @return string
     */
    public function getCreatedatForView()
    {
        return $this->_viewhelper->getCreatedAt();
    }
}