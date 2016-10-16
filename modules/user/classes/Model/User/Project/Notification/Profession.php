<?php

class Model_User_Project_Notification_Profession extends Model_Relation
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
        'user_id'						=> ['type' => 'int', 'null' => true],
        'profession_id' 					=> ['type' => 'int', 'null' => true],
        'created_at' 					=> ['type' => 'datetime', 'null' => true]
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
     * @param array $ids
     */
    public static function createBy(array $ids)
    {
        foreach ($ids as $id) {
            $industry               = new Model_User_Project_Notification_Profession();
            $industry->user_id      =  Auth::instance()->get_user()->user_id;
            $industry->profession_id  = $id;

            $industry->save();
        }
    }
}