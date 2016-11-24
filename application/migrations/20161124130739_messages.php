<?php defined('SYSPATH') or die('No direct script access.');

class messages extends Migration
{
  public function up()
  {
     $this->create_table
     (
       'messages',
       array
       (
           'message_id'     => ['integer', 'unsigned' => true],
           'sender_id'      => ['integer', 'unsigned' => true],
           'receiver_id'    => ['integer', 'unsigned' => true],
           'message'        => ['text'],
           'created_at'     => ['datetime']
       ),
       'message_id'
     );
  }

  public function down()
  {
     $this->drop_table('messages');
  }
}