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

    /**
     * @param  array  $conversations
     * @param  string $slug
     * @return [type]
     */
    public static function putIntoFirstPlace(array $conversations, $slug)
    {
        $result = $conversations;
        $toPut  = null;

        foreach ($result as $conversation) {
            if ($conversation->getSlug() == $slug) {
                $toPut = $conversation;
                break;
            }
        }

        Assert::notNull($toPut);

        foreach ($result as $i => $conversation) {
            if ($conversation->getSlug() == $slug) {
                unset($result[$i]);
            }
        }

        array_unshift($result, $toPut);

        return $result;
    }
}