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
     * @param int $userId
     * @return Model_Message[]
     */
    public function getAllVisibleBy($conversationId, $userId)
    {
        return $this->_message
            ->join(['message_interactions', 'mi'], 'left')
                ->on('mi.message_id', '=', 'message.message_id')
            ->where('message.conversation_id', '=', $conversationId)
            ->and_where('mi.user_id', '=', $userId)
            ->and_where('mi.is_deleted', '=', 0)
            ->find_all();
    }

    /**
     * Minden olyan uzenet, amit az adott user fogadott, es a kuldo torolt. Csak az utolsokat adja vissza.
     *
     * @param int $conversationId
     * @param int $userId
     * @return Model_Message[]
     */
    public function getLastToReceiverDeletedBySender($conversationId, $userId)
    {
        $messages = $this->deletedMessagesBaseSelectBy($conversationId)
            ->and_where('message.sender_id', '!=', $userId)
            ->and_where('mi_sender.is_deleted', '=', 1)
            ->and_where('mi_receiver.is_deleted', '=', 1)
            ->find_all();

        return Business_Message::getLastDeletedFrom($messages);
    }

    /**
     * @return int
     */
    public function getLastId()
    {
        return DB::select($this->_message->primary_key())
            ->from($this->_message->table_name())
            ->order_by($this->_message->primary_key(), 'DESC')
            ->limit(1)
            ->execute()->get($this->_message->primary_key());
    }

    /**
     * @param int $conversationId
     * @return Model_Message
     */
    protected function deletedMessagesBaseSelectBy($conversationId)
    {
        return $this->_message
            ->join(['message_interactions', 'mi_sender'], 'left')
                ->on('mi_sender.message_id', '=', 'message.message_id')
                ->on('mi_sender.user_id', '=', 'message.sender_id')
            ->join(['message_interactions', 'mi_receiver'], 'left')
                ->on('mi_receiver.message_id', '=', 'message.message_id')
                ->on('mi_receiver.user_id', '!=', 'message.sender_id')
            ->where('message.conversation_id', '=', $conversationId);
    }
}