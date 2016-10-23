<?php

class Model_User_Project_Notification_Profession extends Model_User_Project_Notification
{
    protected $_table_name  = 'users_project_notification_professions';
    protected $_primary_key = 'users_project_notification_profession_id';
    protected $_relationFk  = 'user_id';

    protected $_created_column = [
        'column' => 'created_at',
        'format' => 'Y-m-d H:i'
    ];

    protected $_belongs_to = [
        'user' => [
            'model'         => 'User',
            'foreign_key'   => 'user_id'
        ],
        'profession' => [
            'model'         => 'Profession',
            'foreign_key'   => 'profession_id'
        ]
    ];

    protected $_table_columns = [
        'user_project_notification_profession_id'	=> ['type' => 'int', 'key' => 'PRI'],
        'user_id'						            => ['type' => 'int', 'null' => true],
        'profession_id' 					        => ['type' => 'int', 'null' => true],
        'created_at' 					            => ['type' => 'datetime', 'null' => true]
    ];

    /**
     * @return string
     */
    public function getPrimaryKeyForEndModel()
    {
        return 'profession_id';
    }

    /**
     * @return Model_Profession
     */
    public function getEndRelationModel()
    {
        return new Model_Profession();
    }

    /**
     * @return string
     */
    public function getSearchedRelationIdsPropertyName()
    {
        return '_searchedProfessionIds';
    }

    /**
     * @return string
     */
    public function getForeignKey()
    {
        return 'user_id';
    }

    /**
     * @return Model_User_Project_Notification_Profession
     */
    protected function createModel()
    {
        return new Model_User_Project_Notification_Profession();
    }
}