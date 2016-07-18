<?php defined('SYSPATH') or die('No direct script access.');

class users_industries extends Migration
{
  public function up()
  {
    $this->create_table
    (
      'users_industries',
       array
       (
          'user_id' => ['integer', 'unsigned' => true],
          'industry_id' => ['integer', 'unsigned' => true],
       )
    );

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));

    $this->add_index('users_industries', 'user_id', ['user_id'], 'normal');
    $this->add_index('users_industries', 'industry_id', ['industry_id'], 'normal');
    $this->add_index('users_industries', 'user_id_industry_id', ['user_id', 'industry_id'], 'unique');

    $this->sql("ALTER TABLE `users_industries` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    $this->sql("ALTER TABLE `users_industries` ADD FOREIGN KEY (`industry_id`) REFERENCES `industries`(`industry_id`) ON DELETE CASCADE ON UPDATE CASCADE");
  }

  public function down()
  {
    $this->sql("ALTER TABLE `users_industries` DROP FOREIGN KEY industry_id");
    $this->sql("ALTER TABLE `users_industries` DROP FOREIGN KEY user_id");    

    $this->remove_index('users_industries', 'user_id_industry_id');
    $this->remove_index('users_industries', 'industry_id');
    $this->remove_index('users_industries', 'user_id');

    $this->drop_table('users_industries');

    // $this->remove_column('table_name', 'column_name');
  }
}