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
                $name = $participant->{$nameMethod}();

                if (empty(trim($name))) {
                    $name = $participant->getCompanyName();
                }

                return $name;
            }
        }
    }
    
    /**
     * @return string[]
     */
    public function getParticipantProfilePictures()
    {
        foreach ($this->_participants as $i => $participant) {
            if ($participant->getId() != $this->_authUser->user_id) {
                return [$participant->getListPicturePath()];
            }
        }
    }
}