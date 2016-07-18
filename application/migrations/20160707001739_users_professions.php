<?php defined('SYSPATH') or die('No direct script access.');

class users_professions extends Migration
{
  public function up()
  {
    $this->create_table
    (
      'users_professions',
       array
       (
          'user_id' => ['integer', 'unsigned' => true],
          'profession_id' => ['integer', 'unsigned' => true],
       )
    );

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));

    $this->add_index('users_professions', 'user_id', ['user_id'], 'normal');
    $this->add_index('users_professions', 'profession_id', ['profession_id'], 'normal');
    $this->add_index('users_professions', 'user_id_profession_id', ['user_id', 'profession_id'], 'unique');

    $this->sql("ALTER TABLE `users_professions` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    $this->sql("ALTER TABLE `users_professions` ADD FOREIGN KEY (`profession_id`) REFERENCES `professions`(`profession_id`) ON DELETE CASCADE ON UPDATE CASCADE");
  }

  public function down()
  {
    $this->sql("ALTER TABLE `users_professions` DROP FOREIGN KEY profession_id");
    $this->sql("ALTER TABLE `users_professions` DROP FOREIGN KEY user_id");    

    $this->remove_index('users_professions', 'user_id_profession_id');
    $this->remove_index('users_professions', 'profession_id');
    $this->remove_index('users_professions', 'user_id');

    $this->drop_table('users_professions');

    // $this->remove_column('table_name', 'column_name');
  }
}