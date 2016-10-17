<?php

class Model_User_Industry extends Model_Relation
{
	protected $_table_name = 'users_industries';
	
	protected $_belongs_to = [
		'user' => [
			'model'			=> 'User',
			'foreign_key'	=> 'user_id'
		],
		'industry' => [
			'model'			=> 'Industry',
			'foreign_key'	=> 'industry_id'
		],
	];
	
	protected $_table_columns = [
		'user_id'		=> ['type' => 'int'],
		'industry_id'	=> ['type' => 'int', 'null' => true],
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
}