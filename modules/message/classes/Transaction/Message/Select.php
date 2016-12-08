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

    /**
     * Minden olyan uzenet, amit a kapott user kuldott, es a fogado torolt
     *
     * @param int $userId
     * @return array
     */
    public function getAllToSenderDeletedByReceiver($conversationId, $userId)
    {
        return $this->_message
            ->join(['message_interactions', 'mi_sender'], 'left')
                ->on('mi_sender.message_id', '=', 'message.message_id')
                ->on('mi_sender.user_id', '=', 'message.sender_id')
            ->join(['message_interactions', 'mi_receiver'], 'left')
                ->on('mi_receiver.message_id', '=', 'message.message_id')
                ->on('mi_receiver.user_id', '!=', 'message.sender_id')
            ->where('message.conversation_id', '=', $conversationId)
            ->and_where('message.sender_id', '=', $userId)
            ->and_where('mi_sender.is_deleted', '=', 0)
            ->and_where('mi_receiver.is_deleted', '=', 1)
            ->find_all();
    }
}