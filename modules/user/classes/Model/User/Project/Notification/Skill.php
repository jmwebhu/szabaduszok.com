<?php

class Model_User_Project_Notification_Skill extends Model_User_Relation
{
    protected $_table_name  = 'users_project_notification_skills';
    protected $_primary_key = 'users_project_notification_skill_id';
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
        'skill' => [
            'model'         => 'Skill',
            'foreign_key'   => 'skill_id'
        ]
    ];

    protected $_table_columns = [
        'user_project_notification_skill_id'	=> ['type' => 'int', 'key' => 'PRI'],
        'user_id'						=> ['type' => 'int', 'null' => true],
        'skill_id' 					=> ['type' => 'int', 'null' => true],
        'created_at' 					=> ['type' => 'datetime', 'null' => true]
    ];

    /**
     * @return string
     */
    public function getPrimaryKeyForEndModel()
    {
        return 'skill_id';
    }

    /**
     * @return Model_Skill
     */
    public function getEndRelationModel()
    {
        return new Model_Skill();
    }

    /**
     * @return string
     */
    public function getSearchedRelationIdsPropertyName()
    {
        return '_searchedSkillIds';
    }

    /**
     * @return string
     */
    public function getForeignKey()
    {
        return 'user_id';
    }

    /**
     * @return Model_User_Project_Notification_Skill
     */
    protected function createModel()
    {
        return new Model_User_Project_Notification_Skill();
    }
}