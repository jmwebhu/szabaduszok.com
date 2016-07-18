<?php defined('SYSPATH') or die('No direct script access.');

class projects_skills extends Migration
{
  public function up()
  {
    $this->create_table
    (
      'projects_skills',
       array
       (
          'project_id' => ['integer', 'unsigned' => true],
          'skill_id' => ['integer', 'unsigned' => true],
       )
    );

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));

    $this->add_index('projects_skills', 'project_id', ['project_id'], 'normal');
    $this->add_index('projects_skills', 'skill_id', ['skill_id'], 'normal');
    $this->add_index('projects_skills', 'project_id_skill_id', ['project_id', 'skill_id'], 'unique');

    $this->sql("ALTER TABLE `projects_skills` ADD FOREIGN KEY (`project_id`) REFERENCES `projects`(`project_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    $this->sql("ALTER TABLE `projects_skills` ADD FOREIGN KEY (`skill_id`) REFERENCES `skills`(`skill_id`) ON DELETE CASCADE ON UPDATE CASCADE");
  }

  public function down()
  {
    $this->sql("ALTER TABLE `projects_skills` DROP FOREIGN KEY skill_id");
    $this->sql("ALTER TABLE `projects_skills` DROP FOREIGN KEY project_id");    

    $this->remove_index('projects_skills', 'project_id_skill_id');
    $this->remove_index('projects_skills', 'skill_id');
    $this->remove_index('projects_skills', 'project_id');

    $this->drop_table('projects_skills');

    // $this->remove_column('table_name', 'column_name');
  }
}