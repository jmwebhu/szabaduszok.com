<?php

class Text_Conversation
{
    /**
     * @param Entity_User[]
     */
    public static function getNameFromUsers(array $users)
    {
        $name = '';
        foreach ($users as $i => $user) {
            /**
             * @var Entity_User $user
             */

            $userFullName = $user->getName();
            if (empty(trim($userFullName))) {
                $userFullName = $user->getCompanyName();
            }

            $name .= $userFullName;
            if (!Arr::isLastIndex($i, $users)) {
                $name .= ', ';
            }
        }

        return $name;
    }
}