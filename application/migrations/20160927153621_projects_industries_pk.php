<?php defined('SYSPATH') or die('No direct script access.');

class projects_industries_pk extends Migration
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

      $this->sql("alter table projects_industries add column `id` int(11) unsigned primary KEY AUTO_INCREMENT;");
  }

  public function down()
  {
    // $this->drop_table('table_name');

    // $this->remove_column('table_name', 'column_name');
  }
}