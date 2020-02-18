<?php defined('SYSPATH') or die('No direct script access.');

class search_logs extends Migration
{
  public function up()
  {
     $this->create_table
     (
       'search_logs',
       array
       (
         'search_log_id'    => ['integer', 'unsigned' => true, 'null' => false],
         'content'          => ['text'],
         'user_id'          => ['integer', 'unsigned' => true],
         'created_at'       => array('datetime'),
       ),
       'search_log_id'
     );
  }

  public function down()
  {
     $this->drop_table('search_logs');
  }
}