<?php

class Text_Conversation
{
    /**
     * @param array of Conversation_Participant $users
     */
    public static function getNameFromUsers(array $users)
    {
        $name = '';
        foreach ($users as $i => $user) {
            /**
             * @var Conversation_Participant $user
             */

            $name .= $user->getName();
            if (!Arr::isLastIndex($i, $users)) {
                $name .= ', ';
            }
        }

        return $name;
    }
}