<?php

class Entity_Conversation extends Entity implements Conversation
{
    /**
     * @var int
     */
    protected $_conversation_id;

    /**
     * @var string
     */
    protected $_name;

    /**
     * @var string
     */
    protected $_slug;

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
    public function getId()
    {
        return (int)$this->_conversation_id;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->_model->getSlug();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_model->getName();
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

    /**
     * @return array of Conversation_Participant
     */
    public function getParticipants()
    {
        return $this->_model->getParticipants();
    }

    /**
     * @return array of Message
     */
    public function getMessages()
    {
        return $this->_model->getMessages();
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    public function setParticipants(array $participant)
    {
        // TODO: Implement setParticipants() method.
    }

    /**
     * @param Conversation_Participant $user
     */
    public function deleteConversation(Conversation_Participant $user)
    {
        $interaction                    = new Model_Conversation_Interaction();
        $interaction->conversation_id   = $this->getId();
        $interaction->user_id           = $user->getId();
        $interaction->is_deleted        = true;
        $interaction->save();
    }
}