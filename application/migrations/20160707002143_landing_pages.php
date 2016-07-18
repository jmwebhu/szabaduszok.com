<?php defined('SYSPATH') or die('No direct script access.');

class landing_pages extends Migration
{
  public function up()
  {
    $this->create_table
    (
      'landing_pages',
       array
       (
          'landing_page_id' => ['integer', 'unsigned' => true],
          'type' => ['integer', 'unsigned' => true],
          'counter' => ['integer', 'unsigned' => true],
       ),
       'landing_page_id'
    );

    $this->add_index('landing_pages', 'name_index', ['name'], 'normal');
  }

  public function down()
  {
    $this->drop_table('landing_pages');

    // $this->remove_column('table_name', 'column_name');
  }
}