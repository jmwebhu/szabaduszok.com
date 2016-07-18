<?php defined('SYSPATH') or die('No direct script access.');

class projects_industries extends Migration
{
  public function up()
  {
    $this->create_table
    (
      'projects_industries',
       array
       (
          'project_id' => ['integer', 'unsigned' => true],
          'industry_id' => ['integer', 'unsigned' => true],
       )
    );

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));

    $this->add_index('projects_industries', 'project_id', ['project_id'], 'normal');
    $this->add_index('projects_industries', 'industry_id', ['industry_id'], 'normal');
    $this->add_index('projects_industries', 'project_id_industry_id', ['project_id', 'industry_id'], 'unique');

    $this->sql("ALTER TABLE `projects_industries` ADD FOREIGN KEY (`project_id`) REFERENCES `projects`(`project_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    $this->sql("ALTER TABLE `projects_industries` ADD FOREIGN KEY (`industry_id`) REFERENCES `industries`(`industry_id`) ON DELETE CASCADE ON UPDATE CASCADE");
  }

  public function down()
  {
    $this->sql("ALTER TABLE `projects_industries` DROP FOREIGN KEY industry_id");
    $this->sql("ALTER TABLE `projects_industries` DROP FOREIGN KEY project_id");    

    $this->remove_index('projects_industries', 'project_id_industry_id');
    $this->remove_index('projects_industries', 'industry_id');
    $this->remove_index('projects_industries', 'project_id');

    $this->drop_table('projects_industries');

    // $this->remove_column('table_name', 'column_name');
  }
}