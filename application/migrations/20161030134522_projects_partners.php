<?php defined('SYSPATH') or die('No direct script access.');

class projects_partners extends Migration
{
  public function up()
  {
     $this->create_table
     (
       'projects_partners',
       array
       (
           'project_partner_id' => ['integer', 'unsigned' => true],
           'user_id'            => ['integer', 'default' => null, 'unsigned' => true],
           'project_id'         => ['integer', 'default' => null, 'unsigned' => true],
           'type'               => ['integer', 'default' => null, 'unsigned' => true],
           'updated_at'         => array('datetime'),
           'created_at'         => array('datetime'),
           'approved_at'        => array('datetime'),
       ),
        'project_partner_id'
     );

      $this->add_index('projects_partners', 'user_id_project_id', ['user_id', 'project_id'], 'unique');
      $this->add_index('users', 'type', ['type'], 'normal');

      $this->sql("ALTER TABLE `projects_partners` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE");
      $this->sql("ALTER TABLE `projects_partners` ADD FOREIGN KEY (`project_id`) REFERENCES `projects`(`project_id`) ON DELETE CASCADE ON UPDATE CASCADE");
  }

  public function down()
  {
      $this->sql("ALTER TABLE `projects_partners` DROP FOREIGN KEY user_id");
      $this->sql("ALTER TABLE `projects_partners` DROP FOREIGN KEY project_id");

      $this->drop_table('projects_partners');
  }
}