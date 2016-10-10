<?php

class Model_Project_Profession extends Model_Project_Relation
{
	protected $_table_name = 'projects_professions';
    protected $_primary_key = 'id';
	
	protected $_belongs_to = [
		'project' => [
			'model'			=> 'User',
			'foreign_key'	=> 'project_id'
		],
		'profession' => [
			'model'			=> 'Profession',
			'foreign_key'	=> 'profession_id'
		],
	];
	
	protected $_table_columns = [
        'id'            => ['type' => 'int', 'key' => 'PRI'],
		'project_id'	=> ['type' => 'int', 'null' => true],
		'profession_id'	=> ['type' => 'int', 'null' => true],
	];

    /**
     * @return Model_Profession
     */
    public function getEndRelationModel()
    {
        return new Model_Profession();
    }
}