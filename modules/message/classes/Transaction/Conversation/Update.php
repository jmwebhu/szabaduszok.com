<?php

class Transaction_Conversation_Update
{
    /**
     * @var Model_Conversation
     */
    protected $_conversation = null;

    public function __construct(Model_Conversation $conversation)
    {
        $this->_conversation = $conversation;
    }

    public function flagMessagesAsRead($userId)
    {
        $result = DB::select('mi.message_interaction_id')
            ->from(['message_interactions', 'mi'])
            ->join(['messages', 'm'], 'left')
                ->on('mi.message_id', '=', 'm.message_id')
            ->where('m.conversation_id', '=', $this->_conversation->conversation_id)
            ->and_where('mi.user_id', '=', $userId)
            ->and_where('mi.is_readed', '=', 0)
            ->execute()->as_array();

        $ids = Arr::DBQueryConvertSingleArray($result);

        if ($ids) {
            DB::update('message_interactions')->set(['is_readed' => 1])->where('message_interaction_id', 'IN', $ids)->execute();
        }

        return true;
    }
    
}