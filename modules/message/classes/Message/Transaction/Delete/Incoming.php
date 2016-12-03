<?php

class Message_Transaction_Delete_Incoming extends Message_Transaction_Delete
{
    /**
     * @return void
     */
    public function delete()
    {
        $interaction = Model_Message_Interaction::getByMessageAndUser(
            $this->_message->getId(), $this->_user->getId());

        $interaction->setIsDeleted(true);
    }
}