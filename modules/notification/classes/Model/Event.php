<?php

class Model_Event extends ORM implements Event
{
    const TYPE_PARTICIPATE_PAY = 7;

    protected $_table_name      = 'events';
    protected $_primary_key     = 'event_id';

    protected $_table_columns = [
        'event_id'      => ['type' => 'int',        'key' => 'PRI'],
        'user_id'       => ['type' => 'int',        'null' => true],
        'name'          => ['type' => 'string',     'null' => true],
        'template_name' => ['type' => 'string',     'null' => true],
        'subject_name'  => ['type' => 'string',     'null' => true]
    ];

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return $this->template_name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSubjectName()
    {
        return $this->subject_name;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->object();
    }

    /**
     * @param int|null $value
     * @return Event
     */
    public static function createEvent($value = null)
    {
        switch ($value) {
            case self::TYPE_PARTICIPATE_PAY:
                $event = new Model_Event_Participate_Pay($value);
                break;

            default:
                $event = new Model_Event($value);
                break;
        }

        Assert::notNull($event);

        return $event;
    }
}