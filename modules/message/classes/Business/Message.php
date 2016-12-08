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

        return $index;
    }
}