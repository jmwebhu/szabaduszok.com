<?php

class Business_Conversation extends Business
{
    /**
     * @param  array  $userIds
     * @return array
     */
    public static function getConcatedUserIdsFrom(array $userIds)
    {
        $reversedUserIds = array_reverse($userIds);

        return [
            'original'  => Arr::concatValues($userIds),
            'reversed'  => Arr::concatValues($reversedUserIds)
        ];
    }
}