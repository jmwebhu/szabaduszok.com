<?php

class Model_User_Profession extends Model_Relation
{
	protected $_table_name = 'users_professions';
	
	protected $_belongs_to = [
		'user' => [
			'model'			=> 'User',
			'foreign_key'	=> 'user_id'
		],
		'profession' => [
			'model'			=> 'Profession',
			'foreign_key'	=> 'profession_id'
		],
	];
	
	protected $_table_columns = [
		'user_id'		=> ['type' => 'int'],
		'profession_id'	=> ['type' => 'int'],
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
}