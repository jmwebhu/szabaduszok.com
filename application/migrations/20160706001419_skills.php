<?php defined('SYSPATH') or die('No direct script access.');

class skills extends Migration
{
  public function up()
  {
    $this->create_table
    (
      'skills',
       array
       (
          'skill_id' => ['integer', 'unsigned' => true],
          'name'      => ['string[255]'],
          'slug'      => ['text'],
       ),
       'skill_id'
    );

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));

    $this->add_index('skills', 'name', ['name'], 'normal');    
    $this->sql("ALTER TABLE `skills` ADD UNIQUE (`slug`(50))");
  }

  public function down()
  {
    
    $this->remove_index('skills', 'slug');
    $this->remove_index('skills', 'name');

    $this->drop_table('skills');

    // $this->remove_column('table_name', 'column_name');
  }
}