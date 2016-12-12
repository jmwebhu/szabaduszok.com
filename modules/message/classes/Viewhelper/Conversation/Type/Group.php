<?php

class Viewhelper_Conversation_Type_Group extends Viewhelper_Conversation_Type
{
    public function getParticipantNames($whichname = 'name')
    {
        $nameMethod     = 'get' . ucfirst($whichname);

        foreach ($this->_participants as $i => $participant) {
            $tmp = '';

            if ($participant->getId() != Auth::instance()->get_user()->user_id) {
                $names .= $participant->{$nameMethod}();

                if (!Arr::isLastIndex($i, $this->_participants, 1)) {
                    $names .= ', ';
                }
            }
        }

        return $names;
    }
    
    public function getParticipantProfilePictures()
    {
        
    }
}