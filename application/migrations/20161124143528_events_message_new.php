<?php defined('SYSPATH') or die('No direct script access.');

class events_message_new extends Migration
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

    $this->sql("INSERT INTO `events` (`event_id`, `name`,`template_name`, `subject_name`) VALUES(NULL, 'Új üzenet', 'message_new', 'message')");
  }

  public function down()
  {
    // $this->drop_table('table_name');

    // $this->remove_column('table_name', 'column_name');
  }
}