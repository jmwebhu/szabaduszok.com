<?php

class Transaction_Conversation_Count implements Transaction
{
    /**
     * @var Model_Conversation
     */
    protected $_conversation = null;

    /**
     * @var int
     */
    protected $_userId;

    /**
     * @param Model_Conversation $conversation
     */
    public function setConversation($conversation)
    {
        $this->_conversation = $conversation;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->_userId = $userId;
    }

    /**
     * @param Model_Conversation $conversation
     * @param int $userId
     */
    public function __construct(Model_Conversation $conversation, $userId)
    {
        $this->_conversation = $conversation;
        $this->_userId = $userId;
    }

    /**
     * @return array
     */
    public function execute()
    {
        return [
            'all'       => (new Transaction_Message_Count_All($this->_conversation, $this->_userId))->execute(),
            'unread'    => (new Transaction_Message_Count_Unread($this->_conversation, $this->_userId))->execute()
        ];
    }
}