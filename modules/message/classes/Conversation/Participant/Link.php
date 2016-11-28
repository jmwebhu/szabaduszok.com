<?php

interface Conversation_Participant_Link
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return Conversation
     */
    public function getConversation();

    /**
     * @return Conversation_Participant
     */
    public function getParticipant();
}