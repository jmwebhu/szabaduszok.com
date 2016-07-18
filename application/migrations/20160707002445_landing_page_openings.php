<?php defined('SYSPATH') or die('No direct script access.');

class landing_page_openings extends Migration
{
  public function up()
  {
    $this->create_table
    (
      'landing_page_openings',
       array
       (
          'landing_page_opening_id' => ['integer', 'unsigned' => true],
          'landing_page_id' => ['integer', 'unsigned' => true],
          'datetime' => ['datetime'],
       ),
       'landing_page_opening_id'
    );

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));
    $this->add_index('landing_page_openings', 'landing_page_id_index', ['landing_page_id'], 'normal');
    $this->sql("ALTER TABLE `landing_page_openings` ADD FOREIGN KEY (`landing_page_id`) REFERENCES `landing_pages`(`landing_page_id`) ON DELETE SET NULL ON UPDATE CASCADE");
  }

  public function down()
  {
    $this->drop_table('landing_page_openings');

    // $this->remove_column('table_name', 'column_name');
  }
}