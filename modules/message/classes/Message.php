<?php

interface Message
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return Message_Participant
     */
    public function getSender();

    /**
     * @return Message_Participant
     */
    public function getReceiver();

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @return DateTime
     */
    public function getDatetime();

    /**
     * @return string
     */
    public function getData();

    /**
     * @return bool
     */
    public function isReaded();

    /**
     * @return bool
     */
    public function isArchived();

    /**
     * @param Message_Participant $sender
     * @return void
     */
    public function setSender(Message_Participant $sender);

    /**
     * @param Message_Participant $receiver
     * @return void
     */
    public function setReceiver(Message_Participant $receiver);

    /**
     * @param string $message
     * @return void
     */
    public function setMessage($message);

    /**
     * @param bool $readed
     * @return void
     */
    public function setReaded($readed);

    /**
     * @param bool $archived
     * @return void
     */
    public function setArchived($archived);

    /**
     * @return void
     * @throws Exception
     */
    public function send();
}