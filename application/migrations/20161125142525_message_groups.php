<?php defined('SYSPATH') or die('No direct script access.');

class message_groups extends Migration
{
  public function up()
  {
     $this->create_table
     (
       'message_groups',
       array
       (
         'message_group_id' => ['integer', 'unsigned' => true],
         'updated_at'       => array('datetime'),
         'created_at'       => array('datetime')
       )
     );

     $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));
  }

  public function down()
  {
     $this->drop_table('table_name');

     $this->remove_column('table_name', 'column_name');
  }
}