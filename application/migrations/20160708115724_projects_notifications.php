<?php defined('SYSPATH') or die('No direct script access.');

class projects_notifications extends Migration
{
  public function up()
  {
    $this->create_table
    (
      'projects_notifications',
      array
      (
        'project_notification_id' => ['integer', 'unsigned' => true],
        'project_id'              => ['integer', 'unsigned' => true],
        'user_id'                 => ['integer', 'unsigned' => true],
        'is_sended'               => ['boolean', 'unsigned' => true, 'default' => 0],
        'updated_at'              => array('datetime'),
        'created_at'              => array('datetime'),
      ),
      'project_notification_id'
    );

    $this->add_index('projects_notifications', 'project_id', ['project_id'], 'normal');
    $this->add_index('projects_notifications', 'user_id', ['user_id'], 'normal');
    $this->add_index('projects_notifications', 'is_sended', ['is_sended'], 'normal');

    $this->sql("ALTER TABLE `projects_notifications` ADD FOREIGN KEY (`project_id`) REFERENCES `projects`(`project_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    $this->sql("ALTER TABLE `projects_notifications` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE");    

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));
  }

  public function down()
  {
    $this->sql("ALTER TABLE `projects_notifications` DROP FOREIGN KEY user_id");
    $this->sql("ALTER TABLE `projects_notifications` DROP FOREIGN KEY project_id");

    $this->remove_index('projects_notifications', 'is_sended');
    $this->remove_index('projects_notifications', 'user_id');
    $this->remove_index('projects_notifications', 'project_id');

    $this->drop_table('projects_notifications');

    // $this->remove_column('table_name', 'column_name');
  }
}