<?php defined('SYSPATH') or die('No direct script access.');

class signups extends Migration
{
  public function up()
  {
    $this->create_table
    (
       'signups',
       array
       (
           'signup_id'          => ['integer', 'unsigned' => true],
           'email'              => ['string'],
           'updated_at'         => array('datetime'),
           'created_at'         => array('datetime'),
           'type'               => ['integer']
       ),
       'signup_id'
    );

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));
  }

  public function down()
  {
    $this->drop_table('table_name');

    // $this->remove_column('table_name', 'column_name');
  }
}