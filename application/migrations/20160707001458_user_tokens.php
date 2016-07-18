<?php defined('SYSPATH') or die('No direct script access.');

class user_tokens extends Migration
{
  public function up()
  {
    $this->create_table
    (
      'user_tokens',
       array
       (
          'id' => ['integer', 'unsigned' => true],
          'user_id'      => ['integer', 'unsigned' => true],
          'user_agent'      => ['string[40]'],
          'token' => ['string[40]'],
          'created' => ['integer', 'unsigned' => true],
          'expires' => ['integer', 'unsigned' => true],
       )
    );

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));
  }

  public function down()
  {
    $this->drop_table('user_tokens');

    // $this->remove_column('table_name', 'column_name');
  }
}