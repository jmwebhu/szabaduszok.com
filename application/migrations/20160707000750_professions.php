<?php defined('SYSPATH') or die('No direct script access.');

class professions extends Migration
{
  public function up()
  {
    $this->create_table
    (
      'professions',
       array
       (
          'profession_id' => ['integer', 'unsigned' => true],
          'name'      => ['string[255]'],
          'slug'      => ['text'],
       ),
       'profession_id'
    );

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));

    $this->add_index('professions', 'name', ['name'], 'normal');    
    $this->sql("ALTER TABLE `professions` ADD UNIQUE (`slug`(50))");
  }

  public function down()
  {
    $this->remove_index('professions', 'slug');
    $this->remove_index('professions', 'name');

    $this->drop_table('professions');

    // $this->remove_column('table_name', 'column_name');
  }
}