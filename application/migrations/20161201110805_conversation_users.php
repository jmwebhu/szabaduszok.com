<?php defined('SYSPATH') or die('No direct script access.');

class conversation_users extends Migration
{
  public function up()
  {
     $this->create_table
     (
       'conversation_users',
       array
       (
           'conversation_user_id'   => ['integer', 'unsigned' => true],
           'conversation_id'        => ['integer', 'unsigned' => true],
           'user_id'                => ['integer', 'unsigned' => true]
       ),
         'conversation_user_id'
     );

      $this->sql("ALTER TABLE `conversation_users` ADD FOREIGN KEY (`conversation_id`) REFERENCES `conversations`(`conversation_id`) ON DELETE SET NULL ON UPDATE CASCADE");
      $this->sql("ALTER TABLE `conversation_users` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE SET NULL ON UPDATE CASCADE");
  }

  public function down()
  {
      $this->sql("ALTER TABLE `conversation_users` DROP FOREIGN KEY user_id");
      $this->sql("ALTER TABLE `conversation_users` DROP FOREIGN KEY conversation_id");

     $this->drop_table('conversation_users');
  }
}