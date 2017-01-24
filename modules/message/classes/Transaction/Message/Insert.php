<?php

class Transaction_Message_Insert implements Transaction
{
    /**
     * @var Model_Message
     */
    protected $_message = null;

    /**
     * @var array
     */
    protected $_data = [];

    /**
     * @return Model_Message
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @param Model_Message $message
     * @param array $data
     */
    public function __construct(Model_Message $message, array $data)
    {
        $this->_message = $message;
        $this->_data    = $data;
    }

    public function execute()
    {
        $this->_message->submit($this->_data);
        $this->addInteractions();

        return $this->_message;
    }

    protected function addInteractions()
    {
        foreach ($this->_message->conversation->users->find_all() as $user) {
            $interaction                = new Model_Message_Interaction();
            $interaction->message_id    = $this->_message->message_id;
            $interaction->user_id       = $user->user_id;
            $interaction->is_deleted    = false;
            $interaction->is_readed     = ($user->user_id == $this->_message->sender_id) ? true : false;

            $interaction->save();
        }
    }
}