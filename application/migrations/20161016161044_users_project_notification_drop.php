<?php defined('SYSPATH') or die('No direct script access.');

class users_project_notification_drop extends Migration
{
  public function up()
  {
      $this->drop_table('users_project_notification');
  }

  public function down()
  {
    // $this->drop_table('table_name');

    // $this->remove_column('table_name', 'column_name');
  }
}