<?php

class Viewhelper_Conversation_Type_Single extends Viewhelper_Conversation_Type
{
    /**
     * @param  string $whichname 'firstName'|'lastName'|'name'
     * @return string
     */
    public function getParticipantNames($whichname = 'name')
    {
        $nameMethod     = 'get' . ucfirst($whichname);

        foreach ($this->_participants as $i => $participant) {
            if ($participant->getId() != $this->_authUser->user_id) {
                return $participant->{$nameMethod}();
            }
        }
    }
    
    public function getParticipantProfilePictures()
    {
        
    }
}