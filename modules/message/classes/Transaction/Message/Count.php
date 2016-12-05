<?php

abstract class Transaction_Message_Count implements Transaction
{
    /**
     * @var Model_Conversation
     */
    protected $_conversation;

    /**
     * @var int
     */
    protected $_userId;

    /**
     * @param Model_Conversation $conversation
     * @param int $userId
     */
    public function __construct(Model_Conversation $conversation, $userId)
    {
        $this->_conversation    = $conversation;
        $this->_userId          = $userId;
    }

    /**
     * @return ORM
     */
    protected function baseSelect()
    {
        return $this->_conversation->messages
            ->join('message_interactions', 'left')
            ->on('message_interactions.message_id', '=', 'message.message_id')

            ->where('message_interactions.is_deleted', '=', 0)
            ->and_where('message_interactions.user_id', '=', $this->_userId);
    }
}