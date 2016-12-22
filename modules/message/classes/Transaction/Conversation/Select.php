<?php

class Transaction_Conversation_Select
{
    /**
     * @var Model_Conversation
     */
    protected $_conversation = null;

    /**
     * @var Model_Message
     */
    protected $_message = null;

    /**
     * @var Transaction_Message_Select
     */
    protected $_transactionMessageSelect = null;

    /**
     * @param Model_Conversation $conversation
     * @param Model_Message $message
     * @param Transaction_Message_Select $messageSelect
     */
    public function __construct(
        Model_Conversation $conversation, Model_Message $message, Transaction_Message_Select $messageSelect)
    {
        $this->_conversation                = $conversation;
        $this->_message                     = $message;
        $this->_transactionMessageSelect    = $messageSelect;
    }

    /**
     * @param Model_Conversation $conversation
     */
    public function setConversation($conversation)
    {
        $this->_conversation = $conversation;
    }

    /**
     * @return Model_Conversation
     */
    public function getConversation()
    {
        return $this->_conversation;
    }

    /**
     * @return Model_Message
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @return Transaction_Message_Select
     */
    public function getTransactionMessageSelect()
    {
        return $this->_transactionMessageSelect;
    }

    /**
     * BAL OLDALI PANEL
     * Visszaadja az uzenetek oldal bal oldali paneleben megjeleno beszelgeteseket.
     * Azokat, amikben szerepel a felasznalo, ES nincs hozza torolt interacio
     *
     * @param int $userId
     * @return array
     */
    public function getForLeftPanelBy($userId)
    {
        $allByUser      = $this->getAllBy($userId);
        $forLeftPanel   = [];

        foreach ($allByUser as $conversation) {
            $this->_conversation = $conversation;

            /**
             * @var Model_Conversation $conversation
             */
            if (!$this->hasDeletedInteractionBy($userId)) {
                $forLeftPanel[] = $conversation;
            }
        }

        return $forLeftPanel;
    }

    /**
     * UZENETEK EGY ADOTT BESZELGETESHEZ 
     */
    public function getMessagesBy($userId)
    {
        return $this->_transactionMessageSelect->getAllVisibleBy($this->_conversation->getId(), $userId);

        /**
         * Meg nincs torles
         */

        // $lastDeletedMessagesBySender    = $this->_transactionMessageSelect->getLastToReceiverDeletedBySender(
        //     $this->_conversation->getId(), $userId);

        // return array_merge($visibleMessages, $lastDeletedMessagesBySender);
    }

    /**
     * @param  array  $concatedUserIds
     * @return int
     */
    public function getConversationIdBetween(array $concatedUserIds)
    {
        return DB::query(Database::SELECT, '
            SELECT conversation_id
            FROM (
              SELECT conversation_id, GROUP_CONCAT(DISTINCT user_id) user_ids
              FROM conversations_users

              GROUP BY conversation_id
              HAVING COUNT(user_id) = 2
            ) A
            WHERE user_ids = "' . Arr::get($concatedUserIds, 'original', '') . '"
            OR user_ids = "' . Arr::get($concatedUserIds, 'reversed', '') . '"
            LIMIT 1;
        ')->execute()->get('conversation_id');
    }

    /**
     * @param int $userId
     * @return array
     */
    protected function getAllBy($userId)
    {
        return $this->_conversation
            ->join(['conversations_users', 'cu'], 'left')
                ->on('cu.conversation_id', '=', 'conversation.conversation_id')

            ->join(['messages', 'm'], 'left')
                ->on('m.conversation_id', '=', 'conversation.conversation_id')

            ->order_by('m.created_at', 'desc')
            ->group_by('conversation_id')

            ->where('cu.user_id', '=', $userId)
            ->find_all();
    }

    /**
     * @param int $userId
     * @return bool
     */
    protected function hasDeletedInteractionBy($userId)
    {
        return ($this->_conversation->interactions
            ->where('user_id', '=', $userId)->and_where('is_deleted', '=', 1)->count_all() > 0);
    }
}