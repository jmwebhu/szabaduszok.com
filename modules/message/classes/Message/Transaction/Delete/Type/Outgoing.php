<?php

class Message_Transaction_Delete_Type_Outgoing extends Message_Transaction_Delete_Type
{
    public function delete()
    {
        $interactions = Model_Message_Interaction::getAllByMessage($this->_message->getId());

        foreach ($interactions as $interaction) {
            /**
             * @var Model_Message_Interaction $interaction
             */
            $interaction->setIsDeleted(true);
        }
    }
}