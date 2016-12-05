<?php

class Transaction_Conversation_Select
{
    /**
     * @var Model_Conversation
     */
    protected $_conversation = null;

    /**
     * @param Model_Conversation $_conversation
     */
    public function __construct(Model_Conversation $_conversation)
    {
        $this->_conversation = $_conversation;
    }

    /**
     * @param Model_Conversation $conversation
     */
    public function setConversation($conversation)
    {
        $this->_conversation = $conversation;
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