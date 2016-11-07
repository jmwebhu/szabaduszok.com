<?php defined('SYSPATH') or die('No direct script access.');

class users_remove_old_columns extends Migration
{
  public function up()
  {
      $this->remove_column('users', 'old_user_id');
      $this->remove_column('users', 'password_plain');
  }

  public function down()
  {
    // $this->drop_table('table_name');

    // $this->remove_column('table_name', 'column_name');
  }
}