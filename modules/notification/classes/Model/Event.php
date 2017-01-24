<?php

class Model_Event extends ORM implements Event
{
    const TYPE_PROJECT_NEW              = 1;
    const TYPE_CANDIDATE_NEW            = 2;
    const TYPE_CANDIDATE_UNDO           = 3;
    const TYPE_CANDIDATE_ACCEPT         = 4;
    const TYPE_CANDIDATE_REJECT         = 5;
    const TYPE_PARTICIPATE_REMOVE       = 6;
    const TYPE_PARTICIPATE_PAY          = 7;
    const TYPE_PROFILE_RATE             = 8;
    const TYPE_MESSAGE_NEW              = 9;

    protected $_table_name      = 'events';
    protected $_primary_key     = 'event_id';

    protected $_table_columns = [
        'event_id'      => ['type' => 'int',        'key' => 'PRI'],
        'name'          => ['type' => 'string',     'null' => true],
        'template_name' => ['type' => 'string',     'null' => true],
        'subject_name'  => ['type' => 'string',     'null' => true]
    ];

    /**
     * @return int
     */
    public function getId()
    {
        return $this->event_id;
    }

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

    public function getNotifierClass()
    {

    }

    public function getNotifiedClass()
    {

    }
}