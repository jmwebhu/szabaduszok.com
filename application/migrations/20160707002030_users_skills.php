<?php defined('SYSPATH') or die('No direct script access.');

class users_skills extends Migration
{
  public function up()
  {
    $this->create_table
    (
      'users_skills',
       array
       (
          'user_id' => ['integer', 'unsigned' => true],
          'skill_id' => ['integer', 'unsigned' => true],
       )
    );

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));
    $this->add_index('users_skills', 'user_id', ['user_id'], 'normal');
    $this->add_index('users_skills', 'skill_id', ['skill_id'], 'normal');
    $this->add_index('users_skills', 'user_id_skill_id', ['user_id', 'skill_id'], 'unique');

    $this->sql("ALTER TABLE `users_skills` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    $this->sql("ALTER TABLE `users_skills` ADD FOREIGN KEY (`skill_id`) REFERENCES `skills`(`skill_id`) ON DELETE CASCADE ON UPDATE CASCADE");
  }

  public function down()
  {
    $this->sql("ALTER TABLE `users_skills` DROP FOREIGN KEY skill_id");
    $this->sql("ALTER TABLE `users_skills` DROP FOREIGN KEY user_id");    

    $this->remove_index('users_skills', 'user_id_skill_id');
    $this->remove_index('users_skills', 'skill_id');
    $this->remove_index('users_skills', 'user_id');

    $this->drop_table('users_skills');

    // $this->remove_column('table_name', 'column_name');
  }
}