<?php 

abstract class Viewhelper_Conversation_Type 
{
    /**
     * @var Conversation
     */
    protected $_conversation;
    
    /**
     * @var Model_User
     */
    protected $_authUser;

    /**
     * @var Conversation_Participant[]
     */
    protected $_participants = [];

    /**
     * @param Conversation  $_conversation   
     * @param Model_User    $_authUser   
     */
    public function __construct(Conversation $_conversation, Model_User $_authUser)
    {
        $this->_conversation    = $_conversation;
        $this->_authUser        = $_authUser;
        $this->_participants    = $_conversation->getParticipants();            
    }

    /**
     * @param  string $whichname 'firstName'|'lastName'|'name'
     * @return string
     */
    abstract public function getParticipantNames($whichname);

    /**
     * @return mixed
     */
    abstract public function getParticipantProfilePictures();   
}