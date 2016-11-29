<?php

class Model_Conversation extends ORM implements Conversation
{
    protected $_table_name  = 'conversations';
    protected $_primary_key = 'conversation_id';

    protected $_created_column = [
        'column' => 'created_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_updated_column = [
        'column' => 'updated_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_table_columns = [
        'conversation_id'   => ['type' => 'int',        'key' => 'PRI'],
        'name'              => ['type' => 'string',     'null' => true],
        'slug'              => ['type' => 'string',     'null' => true],
        'created_at'        => ['type' => 'datetime',   'null' => true],
        'updated_at'        => ['type' => 'datetime',   'null' => true]
    ];

    public function rules()
    {
        return [
            'name'  => [['not_empty']],
            'slug'  => [['not_empty']]
        ];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->conversation_id;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return new DateTime($this->created_at);
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return new DateTime($this->updated_at);
    }

    /**
     * @return array
     */
    public function getParticipants()
    {
        /**
         * @todo
         */
        return [];
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        /**
         * @todo
         */
        return [];
    }


}