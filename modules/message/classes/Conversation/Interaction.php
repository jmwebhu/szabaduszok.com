<?php

interface Conversation_Interaction
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return Conversation_Participant
     */
    public function getParticipant();

    /**
     * @return bool
     */
    public function getIsDeleted();
}