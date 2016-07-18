<?php defined('SYSPATH') or die('No direct script access.');

class error_logs extends Migration
{
  public function up()
  {
    $this->create_table
    (
      'error_logs',
       array
       (
          'error_log_id'  => ['integer', 'unsigned' => true],
          'message'   => ['text'],
          'trace'     => ['text'],
          'created_at'  => ['datetime'],
       ),
       'error_log_id'
    );

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));
  }

  public function down()
  {
    $this->drop_table('error_logs');

    // $this->remove_column('table_name', 'column_name');
  }
}