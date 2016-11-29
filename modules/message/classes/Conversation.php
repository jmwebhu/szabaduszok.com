<?php

interface Conversation
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getSlug();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return DateTime
     */
    public function getCreatedAt();

    /**
     * @return DateTime
     */
    public function getUpdatedAt();

    /**
     * @return array of Conversation_Participant
     */
    public function getParticipants();

    /**
     * @return array of Message
     */
    public function getMessages();

    /**
     * @param array $data
     * @return void
     */
    public function submit(array $data);
}