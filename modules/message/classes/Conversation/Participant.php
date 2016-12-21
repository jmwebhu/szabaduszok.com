<?php

interface Conversation_Participant
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getData();
}