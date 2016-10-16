<?php defined('SYSPATH') or die('No direct script access.');

class users_project_notification_industries extends Migration
{
  public function up()
  {
    $this->create_table
    (
     'users_project_notification_industries',
     array
       (
         'user_project_notification_industry_id'                 => ['integer', 'unsigned' => true],
         'user_id'                => ['integer', 'unsigned' => true],
         'industry_id'               => ['integer', 'unsigned' => true],
         'created_at'          => array('datetime')
       ),
        'user_project_notification_industry_id'
     );

      $this->sql("ALTER TABLE `users_project_notification_industries` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE");
      $this->sql("ALTER TABLE `users_project_notification_industries` ADD FOREIGN KEY (`industry_id`) REFERENCES `industries`(`industry_id`) ON DELETE CASCADE ON UPDATE CASCADE");
  }

  public function down()
  {
      $this->sql("ALTER TABLE `users_project_notification_industries` DROP FOREIGN KEY user_id");
      $this->sql("ALTER TABLE `users_project_notification_industries` DROP FOREIGN KEY industry_id");

        $this->drop_table('users_project_notification_industries');
  }
}