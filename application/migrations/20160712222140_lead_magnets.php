<?php defined('SYSPATH') or die('No direct script access.');

class lead_magnets extends Migration
{
  public function up()
  {
    $this->create_table
    (
        'lead_magnets',
        array
        (
            'lead_magnet_id'    => ['integer', 'unsigned' => true],
            'name'              => ['string'],
            'path'              => ['string'],
            'is_current'        => ['boolean', 'unsigned' => true, 'default' => null],
            'type'              => ['integer', 'unsigned' => true, 'default' => null]
        ),
        'lead_magnet_id'
    );

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));
  }

  public function down()
  {
    $this->drop_table('table_name');

    // $this->remove_column('table_name', 'column_name');
  }
}