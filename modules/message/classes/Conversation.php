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
     * @return Conversation_Participant[]
     */
    public function getParticipants();

    /**
     * @param int[]
     * @return Conversation_Participant[]
     */
    public function getParticipantsExcept(array $userIds);

    /**
     * @return Message[]
     */
    public function getMessages();

    /**
     * @param array $data
     * @return void
     */
    public function submit(array $data);
}