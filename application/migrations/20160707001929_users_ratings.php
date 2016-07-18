<?php defined('SYSPATH') or die('No direct script access.');

class users_ratings extends Migration
{
  public function up()
  {
    $this->create_table
    (
      'users_ratings',
       array
       (
          'user_id' => ['integer', 'unsigned' => true],
          'rater_user_id' => ['integer', 'unsigned' => true],
          'rating_point' => ['integer', 'unsigned' => true]          
       )
    );

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));

    $this->add_index('users_ratings', 'user_id', ['user_id'], 'normal');
    $this->add_index('users_ratings', 'rater_user_id', ['rater_user_id'], 'normal');
    $this->add_index('users_ratings', 'user_id_rater_user_id', ['user_id', 'rater_user_id'], 'unique');

    $this->sql("ALTER TABLE `users_ratings` ADD FOREIGN KEY (`rater_user_id`) REFERENCES `users`(`user_id`) ON DELETE SET NULL ON UPDATE CASCADE");
    $this->sql("ALTER TABLE `users_ratings` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE SET NULL ON UPDATE CASCADE");
  }

  public function down()
  {
    $this->sql("ALTER TABLE `users_ratings` DROP FOREIGN KEY user_id");
    $this->sql("ALTER TABLE `users_ratings` DROP FOREIGN KEY rater_user_id");    

    $this->remove_index('users_ratings', 'user_id_rater_user_id');
    $this->remove_index('users_ratings', 'rater_user_id');
    $this->remove_index('users_ratings', 'user_id');

    $this->drop_table('users_ratings');

    // $this->remove_column('table_name', 'column_name');
  }
}