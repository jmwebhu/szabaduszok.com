<?php

class Model_Message_Interaction extends ORM implements Message_Interaction
{
    protected $_table_name  = 'message_interactions';
    protected $_primary_key = 'message_interaction_id';

    protected $_created_column = [
        'column' => 'created_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_updated_column = [
        'column' => 'updated_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_table_columns = [
        'message_interaction_id'    => ['type' => 'int',        'key' => 'PRI'],
        'message_id'                => ['type' => 'int',        'null' => true],
        'user_id'                   => ['type' => 'int',        'null' => true],
        'is_deleted'                => ['type' => 'int',        'null' => true],
        'is_readed'                 => ['type' => 'int',        'null' => true],
        'created_at'                => ['type' => 'datetime',   'null' => true],
        'updated_at'                => ['type' => 'datetime',   'null' => true]
    ];

    public function rules()
    {
        return [
            'message_id'    => [['not_empty']],
            'user_id'       => [['not_empty']]
        ];
    }

    protected $_load_with = [
        'message', 'user'
    ];

    protected $_belongs_to  = [
        'message'          => [
            'model'         => 'Message',
            'foreign_key'   => 'message_id'
        ],
        'user'          => [
            'model'         => 'User',
            'foreign_key'   => 'user_id'
        ],
    ];

    /**
     * @param int $messageId
     * @param int $userId
     * @return Model_Message_Interaction
     */
    public static function getByMessageAndUser($messageId, $userId)
    {
        return new Model_Message_Interaction([
            'message_interaction.message_id'    => $messageId,
            'message_interaction.user_id'       => $userId
        ]);
    }

    /**
     * @param int $messageId
     * @return array of Model_Message_Interaction
     */
    public static function getAllByMessage($messageId)
    {
        $model = new Model_Message_Interaction();
        return $model->where('message_interaction.message_id', '=', $messageId)->find_all();
    }

    /**
     * @param int $messageId
     * @param int $userId
     * @return array of Model_Message_Interaction
     */
    public static function getAllByMessageExceptGivenUser($messageId, $userId)
    {
        $model = new Model_Message_Interaction();
        return $model->where('message_interaction.message_id', '=', $messageId)->and_where('message_interaction.user_id', '!=', $userId)->find_all();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->message_interaction_id;
    }

    /**
     * @return Entity_Message
     */
    public function getMessage()
    {
        return new Entity_Message($this->message);
    }

    /**
     * @return Entity_User
     */
    public function getParticipant()
    {
        return Entity_User::createUser($this->user->type, $this->user);
    }

    /**
     * @return bool
     */
    public function getIsDeleted()
    {
        return (bool)$this->is_deleted;
    }

    /**
     * @return bool
     */
    public function getIsReaded()
    {
        return (bool)$this->is_readed;
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
     * @param bool $isDeleted
     */
    public function setIsDeleted($isDeleted)
    {
        $this->is_deleted = $isDeleted;
        $this->save();
    }
}
