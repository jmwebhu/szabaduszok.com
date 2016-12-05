<?php

class Transaction_Message_Delete_Incoming extends Transaction_Message_Delete
{
    /**
     * @return void
     */
    public function execute()
    {
        $interaction = Model_Message_Interaction::getByMessageAndUser(
            $this->_message->getId(), $this->_user->getId());

        $interaction->setIsDeleted(true);
    }
}