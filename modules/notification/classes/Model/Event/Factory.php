<?php

class Model_Event_Factory
{
    private static $_classes = [
        Model_Event::TYPE_PROJECT_NEW           => Model_Event_Project_New::class,
        Model_Event::TYPE_CANDIDATE_NEW         => Model_Event_Candidate_New::class,
        Model_Event::TYPE_CANDIDATE_UNDO        => Model_Event_Candidate_Undo::class,
        Model_Event::TYPE_CANDIDATE_ACCEPT      => Model_Event_Candidate_Accept::class,
        Model_Event::TYPE_CANDIDATE_REJECT      => Model_Event_Candidate_Reject::class,
        Model_Event::TYPE_PARTICIPATE_REMOVE    => Model_Event_Participate_Remove::class,
        Model_Event::TYPE_PARTICIPATE_PAY       => Model_Event_Participate_Pay::class,
        Model_Event::TYPE_PROFILE_RATE          => Model_Event_Profile_Rate::class,
        Model_Event::TYPE_MESSAGE_NEW           => Model_Event_Message_New::class
    ];
    /**
     * @param int $id
     * @return Event
     */
    public static function createEvent($id)
    {
        $class = Arr::get(self::$_classes, $id);
        Assert::notNull($class);

        $event = new $class($id);
        Assert::notNull($event);

        return $event;
    }
}