<?php

interface Message_Interaction
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return Message
     */
    public function getMessage();

    /**
     * @return Conversation_Participant
     */
    public function getParticipant();

    /**
     * @return bool
     */
    public function getIsDeleted();

    /**
     * @return bool
     */
    public function getIsReaded();

    /**
     * @return DateTime
     */
    public function getCreatedAt();

    /**
     * @return DateTime
     */
    public function getUpdatedAt();
}