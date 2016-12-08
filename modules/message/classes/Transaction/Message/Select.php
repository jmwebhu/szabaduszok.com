<?php

class Transaction_Message_Select
{
    /**
     * @var Model_Message
     */
    protected $_message = null;

    /**
     * @param Model_Message $message
     */
    public function __construct(Model_Message $message)
    {
        $this->_message = $message;
    }

    /**
     * @param int $conversationId
     * @return array
     */
    public function getAllActiveBy($conversationId)
    {
        return $this->_message
            ->join(['message_interactions', 'mi'], 'left')
                ->on('mi.message_id', '=', 'message.message_id')
            ->where('conversation_id', '=', $conversationId)
            ->and_where('mi.is_deleted', '=', 0)
            ->find_all();
    }
}