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
        $activeMessages                 = $this->_transactionMessageSelect->getAllActiveBy($this->_conversation->getId());
        $deletedByReceiverMessages      = $this->_transactionMessageSelect->getAllToSenderDeletedByReceiver(
            $this->_conversation->getId(), $userId);

        $lastDeletedMessagesBySender    = $this->_transactionMessageSelect->getLastToReceiverDeletedBySender(
            $this->_conversation->getId(), $userId);

        return array_merge($activeMessages, $deletedByReceiverMessages, $lastDeletedMessagesBySender);
    }

    /**
     * @param int $userId
     * @return array
     */
    protected function getAllBy($userId)
    {
        return $this->_conversation
            ->join('conversations_users', 'left')
                ->on('conversations_users.conversation_id', '=', 'conversation.conversation_id')

            ->where('conversations_users.user_id', '=', $userId)
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