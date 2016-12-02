<?php defined('SYSPATH') or die('No direct script access.');

class conversations extends Migration
{
  public function up()
  {
     $this->create_table
     (
       'conversations',
       array
       (
         'conversation_id'  => ['integer', 'unsigned' => true],
         'name'             => ['text'],
         'slug'             => ['text'],
         'updated_at'       => array('datetime'),
         'created_at'       => array('datetime'),
       ),
       'conversation_id'
     );
  }

  public function down()
  {
     $this->drop_table('conversations');
  }
}