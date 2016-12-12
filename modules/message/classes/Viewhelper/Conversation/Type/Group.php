<?php

class Viewhelper_Conversation_Type_Group extends Viewhelper_Conversation_Type
{
    /**
     * @param  string $whichname 'firstName'|'lastName'|'name'
     * @return string
     */
    public function getParticipantNames($whichname = 'firstName')
    {
        $nameMethod     = 'get' . ucfirst($whichname);
        $names          = '';

        foreach ($this->_participants as $i => $participant) {
            if ($participant->getId() != $this->_authUser->user_id) {
                $names .= $participant->{$nameMethod}() . ', ';
            }
        }

        return substr($names, 0, strlen($names) - 2);
    }
    
    public function getParticipantProfilePictures()
    {
        
    }
}