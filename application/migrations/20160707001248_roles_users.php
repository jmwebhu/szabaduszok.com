<?php defined('SYSPATH') or die('No direct script access.');

class roles_users extends Migration
{
  public function up()
  {
    $this->create_table
    (
      'roles_users',
       array
       (
          'user_id' => ['integer', 'unsigned' => true],
          'role_id' => ['integer', 'unsigned' => true],
       )
    );

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));
  }

  public function down()
  {
    $this->drop_table('roles_users');

    // $this->remove_column('table_name', 'column_name');
  }
}