<?php defined('SYSPATH') or die('No direct script access.');

class projects_professions extends Migration
{
  public function up()
  {
    $this->create_table
    (
      'projects_professions',
       array
       (
          'project_id' => ['integer', 'unsigned' => true],
          'profession_id' => ['integer', 'unsigned' => true],
       )
    );

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));

    $this->add_index('projects_professions', 'project_id', ['project_id'], 'normal');
    $this->add_index('projects_professions', 'profession_id', ['profession_id'], 'normal');
    $this->add_index('projects_professions', 'project_id_profession_id', ['project_id', 'profession_id'], 'unique');

    $this->sql("ALTER TABLE `projects_professions` ADD FOREIGN KEY (`project_id`) REFERENCES `projects`(`project_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    $this->sql("ALTER TABLE `projects_professions` ADD FOREIGN KEY (`profession_id`) REFERENCES `professions`(`profession_id`) ON DELETE CASCADE ON UPDATE CASCADE");
  }

  public function down()
  {
    $this->sql("ALTER TABLE `projects_professions` DROP FOREIGN KEY profession_id");
    $this->sql("ALTER TABLE `projects_professions` DROP FOREIGN KEY project_id");    

    $this->remove_index('projects_professions', 'project_id_profession_id');
    $this->remove_index('projects_professions', 'profession_id');
    $this->remove_index('projects_professions', 'project_id');

    $this->drop_table('projects_professions');

    // $this->remove_column('table_name', 'column_name');
  }
}