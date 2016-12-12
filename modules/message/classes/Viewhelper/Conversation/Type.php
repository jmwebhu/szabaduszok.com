<?php 

abstract class Viewhelper_Conversation_Type 
{
    /**
     * @var Conversation
     */
    protected $_conversation;
    
    /**
     * @var Conversation_Participant
     */
    protected $_authUser;


    /**
     * @param Conversation                  $_conversation   
     * @param Conversation_Participant      $_authUser   
     */
    public function __construct($_conversation, $_authUser)
    {
        $this->_conversation    = $_conversation;
        $this->_authUser        = $_authUser;
    }

    /**
     * @return mixed
     */
    public abstract function getParticipantNames();

    /**
     * @return mixed
     */
    public abstract function getParticipantProfilePictures();   
}