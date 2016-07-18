<?php defined('SYSPATH') or die('No direct script access.');

class migrations extends Migration
{
  public function up()
  {
    /*$this->create_table
    (
      'migrations',
       array
       (
          'id' => ['integer', 'unsigned' => true],
          'hash'      => ['string[30]'],
          'name'      => ['string[100]'],
          'updated_at'  => ['datetime'],
          'created_at'  => ['datetime'],
       ),
       'id'
    );*/

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));
  }

  public function down()
  {
    // $this->drop_table('migrations');

    // $this->remove_column('table_name', 'column_name');
  }
}