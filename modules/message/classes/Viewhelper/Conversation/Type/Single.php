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
            if ($participant->getId() != Auth::instance()->get_user()->user_id) {
                return $participant->{$nameMethod}();
            }
        }
    }
    
    public function getParticipantProfilePictures()
    {
        $names          = '';
        $nameMethod     = 'get' . ucfirst($whichname);

        foreach ($this->_participants as $i => $participant) {
            if ($participant->getId() != Auth::instance()->get_user()->user_id) {
                $tmp = $participant->{$nameMethod}();

                if (!Arr::isLastIndex($i, $participants, 1)) {
                    $tmp .= ', ';
                }

                $names .= $tmp;
            }
        }

        return $names;   
    }
}