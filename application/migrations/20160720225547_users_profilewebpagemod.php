<?php defined('SYSPATH') or die('No direct script access.');

class users_profilewebpagemod extends Migration
{
  public function up()
  {
    // $this->create_table
    // (
    //   'table_name',
    //   array
    //   (
    //     'updated_at'          => array('datetime'),
    //     'created_at'          => array('datetime'),
    //   )
    // );

    $this->add_column('users', 'webpage', array('string[150]', 'default' => NULL));
  }

  public function down()
  {
    // $this->drop_table('table_name');

    $this->remove_column('users', 'webpage');
  }
}