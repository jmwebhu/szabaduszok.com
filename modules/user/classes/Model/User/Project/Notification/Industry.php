<?php

class Model_User_Project_Notification_Industry extends Model_User_Relation
{
    protected $_table_name  = 'users_project_notification_industries';
    protected $_primary_key = 'users_project_notification_industry_id';
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
        'industry' => [
            'model'         => 'Industry',
            'foreign_key'   => 'industry_id'
        ]
    ];

    protected $_table_columns = [
        'user_project_notification_industry_id'	=> ['type' => 'int', 'key' => 'PRI'],
        'user_id'						=> ['type' => 'int', 'null' => true],
        'industry_id' 					=> ['type' => 'int', 'null' => true],
        'created_at' 					=> ['type' => 'datetime', 'null' => true]
    ];

    /**
     * @return string
     */
    public function getPrimaryKeyForEndModel()
    {
        return 'industry_id';
    }

    /**
     * @return Model_Industry
     */
    public function getEndRelationModel()
    {
        return new Model_Industry();
    }

    /**
     * @return string
     */
    public function getSearchedRelationIdsPropertyName()
    {
        return '_searchedIndustryIds';
    }

    /**
     * @return string
     */
    public function getForeignKey()
    {
        return 'user_id';
    }

    /**
     * @return Model_User_Project_Notification_Industry
     */
    protected function createModel()
    {
        return new Model_User_Project_Notification_Industry();
    }

    /**
     * @param string $value
     * @return string
     */
    public function getOrCreateBy($value)
    {
        return new Model_Industry($value);
    }
}