<?php defined('SYSPATH') or die('No direct script access.');

class users_project_notification extends Migration
{
  public function up()
  {
    $this->create_table
    (
      'users_project_notification',
       array
       (
          'users_project_notification_id' => ['integer', 'unsigned' => true],
          'user_id' => ['integer', 'unsigned' => true],
          'industry_id' => ['integer', 'unsigned' => true],
          'profession_id' => ['integer', 'unsigned' => true],
          'skill_id' => ['integer', 'unsigned' => true],
          'created_at'      => ['datetime'],
          'updated_at'      => ['datetime'], 
       ),
       'users_project_notification_id'
    );

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));

    $this->add_index('users_project_notification', 'user_id', ['user_id'], 'normal');        
    $this->add_index('users_project_notification', 'industry_id', ['industry_id'], 'normal');
    $this->add_index('users_project_notification', 'profession_id', ['profession_id'], 'normal');
    $this->add_index('users_project_notification', 'skill_id', ['skill_id'], 'normal');    
    
    $this->sql("ALTER TABLE `users_project_notification` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    $this->sql("ALTER TABLE `users_project_notification` ADD FOREIGN KEY (`industry_id`) REFERENCES `industries`(`industry_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    $this->sql("ALTER TABLE `users_project_notification` ADD FOREIGN KEY (`profession_id`) REFERENCES `professions`(`profession_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    $this->sql("ALTER TABLE `users_project_notification` ADD FOREIGN KEY (`skill_id`) REFERENCES `skills`(`skill_id`) ON DELETE CASCADE ON UPDATE CASCADE");
  }

  public function down()
  {
    $this->sql("ALTER TABLE `users_project_notification` DROP FOREIGN KEY skill_id");
    $this->sql("ALTER TABLE `users_project_notification` DROP FOREIGN KEY profession_id");   
    $this->sql("ALTER TABLE `users_project_notification` DROP FOREIGN KEY industry_id");   
    $this->sql("ALTER TABLE `users_project_notification` DROP FOREIGN KEY user_id");    

    $this->remove_index('users_project_notification', 'skill_id');
    $this->remove_index('users_project_notification', 'profession_id');
    $this->remove_index('users_project_notification', 'industry_id');
    $this->remove_index('users_project_notification', 'user_id');

    $this->drop_table('users_project_notification');

    // $this->remove_column('table_name', 'column_name');
  }
}