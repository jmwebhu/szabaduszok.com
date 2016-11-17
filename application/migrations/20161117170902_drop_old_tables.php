<?php defined('SYSPATH') or die('No direct script access.');

class drop_old_tables extends Migration
{
  public function up()
  {
    $this->drop_table('error_logs');
  }

  public function down()
  {
    // $this->drop_table('table_name');

    // $this->remove_column('table_name', 'column_name');
  }
}