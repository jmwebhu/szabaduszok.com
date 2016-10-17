<?php

class Model_User_Skill extends Model_Relation
{
	protected $_table_name = 'users_skills';
	
	protected $_belongs_to = [
		'user' => [
			'model'			=> 'User',
			'foreign_key'	=> 'user_id'
		],
		'skill' => [
			'model'			=> 'Skill',
			'foreign_key'	=> 'skill_id'
		],
	];
	
	protected $_table_columns = [
		'user_id'		=> ['type' => 'int'],
		'skill_id'		=> ['type' => 'int', 'null' => true],
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
}