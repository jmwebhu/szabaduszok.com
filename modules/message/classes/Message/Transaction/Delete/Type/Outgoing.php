<?php

class Message_Transaction_Delete_Type_Outgoing extends Message_Transaction_Delete_Type
{
    public function delete()
    {
        $interactions = Model_Message_Interaction::getAllByMessageExceptGivenUser(
            $this->_message->getId(), $this->_user->getId());

        foreach ($interactions as $interaction) {
            /**
             * @var Model_Message_Interaction $interaction
             */
            $interaction->setIsDeleted(true);
        }
    }
}