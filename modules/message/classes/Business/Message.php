<?php

class Business_Message extends Business
{
    /**
     * Visszaadja azt az indexet, ami elott az adott tombben mar nem egymas utan kovetkeznek az azonositok
     *
     * @param Model_Message[] $messages
     * @return int
     */
    public static function getIndexBeforeIdNotContinous(array $messages)
    {
        $index = 0;
        for ($i = count($messages) - 1; $i > 0; $i--) {
            /**
             * @var $message Model_Message
             */
            $message    = $messages[$i];
            $index      = $i;

            if ($messages[$i - 1]->message_id != $message->message_id - 1) {
                break;
            }
        }

        if (count($messages) == 2) {
            $index--;
        }

        return $index;
    }

    /**
     * @param Model_Message[] $messages
     * @return Model_Message[]
     */
    public static function getLastDeletedFrom(array $messages)
    {
        if (empty($messages)) {
            return [];
        }

        $lastId = Transaction_Message_Select_Factory::createSelect()->getLastId();
        $count  = (count($messages) == 0) ? 0 : count($messages) - 1;

        if ($messages[$count]->message_id != $lastId) {
            return [];
        }

        $index                  = Business_Message::getIndexBeforeIdNotContinous($messages);
        $lastDeletedMessages    = array_slice($messages, $index, count($messages) - $index);

        foreach ($lastDeletedMessages as $lastDeletedMessage) {
            $lastDeletedMessage->isDeleted = true;
        }

        return $lastDeletedMessages;
    }

    /**
     * @param  Model_Message[]
     * @return array
     */
    public static function groupGivenMessagesByTextifiedDays(array $messages)
    {
        $groupedMessages = [];
        foreach ($messages as $message) {
            
            if ($message instanceof Model_Message) {
                $textifiedDay   = Date::textifyDay(date('Y-m-d', strtotime($message->created_at)));
                $entity         = new Entity_Message($message);
            } else {
                $textifiedDay   = Date::textifyDay(date('Y-m-d', strtotime($message->getCreatedAt()->format('Y-m-d H:i'))));
                $entity         = $message;
            }

            $forView                        = $entity->getCreatedAtForView();
            $groupedMessages                = Arr::setKey($groupedMessages, $forView);
            $groupedMessages[$forView][]    = $message;
        }    

        return $groupedMessages;
    }
}