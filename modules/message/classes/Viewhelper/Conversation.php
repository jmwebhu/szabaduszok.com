<?php

class Viewhelper_Conversation
{
    /**
     * @var Viewhelper_Conversation_Type
     */
    protected $_type;
    
    /**
     * @var Conversation
     */
    protected $_conversation;


    /**
     * @param Conversation   $_conversation   
     */
    public function __construct(Conversation $_conversation, Model_User $authUser)
    {        
        $this->_conversation    = $_conversation;
        $this->_type            = Viewhelper_Conversation_Type_Factory::createType($_conversation, $authUser);
    }

    /**
     * @param  string $whichname 'first'|'last'|'name'
     * @return string
     */
    public function getParticipantNames($whichname = 'name')
    {
        return $this->_type->getParticipantNames($whichname);
    }

    /**
     * @return string[]
     */
    public function getParticipantProfilePictures()
    {
        return $this->_type->getParticipantProfilePictures();
    }
}