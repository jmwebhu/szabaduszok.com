<?php

class Model_Project_Skill extends Model_Relation
{
    protected $_relationFk = 'project_id';
	protected $_table_name = 'projects_skills';
    protected $_primary_key = 'id';
	
	protected $_belongs_to = [
		'project' => [
			'model'			=> 'Project',
			'foreign_key'	=> 'project_id'
		],
		'skill' => [
			'model'			=> 'Skill',
			'foreign_key'	=> 'skill_id'
		],
	];
	
	protected $_table_columns = [
        'id'            => ['type' => 'int', 'key' => 'PRI'],
		'project_id'	=> ['type' => 'int', 'null' => true],
		'skill_id'		=> ['type' => 'int', 'null' => true],
	];

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
}