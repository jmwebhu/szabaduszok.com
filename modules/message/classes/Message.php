<?php

interface Message
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return Conversation_Participant
     */
    public function getSender();

    /**
     * @return Conversation
     */
    public function getConversation();

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @return void
     */
    public function send();

    /**
     * @return DateTime
     */
    public function getCreatedAt();

    /**
     * @return DateTime
     */
    public function getUpdatedAt();
}