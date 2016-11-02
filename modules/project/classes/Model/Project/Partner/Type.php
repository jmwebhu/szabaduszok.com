<?php

abstract class Model_Project_Partner_Type
{
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
}