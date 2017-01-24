<?php

class Transaction_Message_Delete_Outgoing extends Transaction_Message_Delete
{
    public function execute()
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