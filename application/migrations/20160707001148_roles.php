<?php defined('SYSPATH') or die('No direct script access.');

class roles extends Migration
{
  public function up()
  {
    $this->create_table
    (
      'roles',
       array
       (
          'id' => ['integer', 'unsigned' => true],
          'name' => ['string[32]'],
          'description' => ['string[255]'],
       ),
       'id'
    );

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));
  }

  public function down()
  {
    $this->drop_table('roles');

    // $this->remove_column('table_name', 'column_name');
  }
}