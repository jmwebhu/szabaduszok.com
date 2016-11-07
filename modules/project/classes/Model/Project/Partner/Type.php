<?php

abstract class Model_Project_Partner_Type
{
    const TYPE_CANDIDATES   = 'candidates';
    const TYPE_PARTICIPANTS = 'participants';

    /**
     * @return array
     */
    abstract protected function getPerformableEventIds();

    /**
     * @param Event $event
     * @return bool
     */
    public function isEventPerformable(Event $event)
    {
        return in_array($event->getId(), $this->getPerformableEventIds());
    }

    /**
     * @return string
     */
    abstract public function getTypePlural();
}