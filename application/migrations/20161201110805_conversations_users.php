<?php defined('SYSPATH') or die('No direct script access.');

class conversations_users extends Migration
{
  public function up()
  {
     $this->create_table
     (
       'conversations_users',
       array
       (
           'conversation_user_id'   => ['integer', 'unsigned' => true],
           'conversation_id'        => ['integer', 'unsigned' => true],
           'user_id'                => ['integer', 'unsigned' => true]
       ),
         'conversation_user_id'
     );

      $this->sql("ALTER TABLE `conversations_users` ADD FOREIGN KEY (`conversation_id`) REFERENCES `conversations`(`conversation_id`) ON DELETE CASCADE ON UPDATE CASCADE");
      $this->sql("ALTER TABLE `conversations_users` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE");
  }

  public function down()
  {
      $this->sql("ALTER TABLE `conversations_users` DROP FOREIGN KEY user_id");
      $this->sql("ALTER TABLE `conversations_users` DROP FOREIGN KEY conversation_id");

     $this->drop_table('conversations_users');
  }
}