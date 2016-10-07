<?php defined('SYSPATH') or die('No direct script access.');

class industries extends Migration
{
  public function up()
  {
    $this->create_table
    (
      'industries',
       array
       (
          'industry_id' => ['integer', 'unsigned' => true],
          'name'      => ['string[255]'],
          'slug'      => ['text'],
       ),
       'industry_id'
    );

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));

    $this->add_index('industries', 'name', ['name'], 'normal');    
    $this->sql("ALTER TABLE `industries` ADD UNIQUE (`slug`(50))");

      $this->sql("INSERT INTO `industries`(name, slug) VALUES('Informatika', 'informatika');");
      $this->sql("INSERT INTO `industries`(name, slug) VALUES('Marketing', 'marketing');");
      $this->sql("INSERT INTO `industries`(name, slug) VALUES('Média, PR', 'media-pr');");
      $this->sql("INSERT INTO `industries`(name, slug) VALUES('Menedzsment', 'menedzsment');");
      $this->sql("INSERT INTO `industries`(name, slug) VALUES('Adminisztráció', 'adminisztracio');");
      $this->sql("INSERT INTO `industries`(name, slug) VALUES('Pénzügy', 'penzugy');");
      $this->sql("INSERT INTO `industries`(name, slug) VALUES('Ipar', 'ipar');");
  }

  public function down()
  {
    $this->remove_index('industries', 'slug');
    $this->remove_index('industries', 'name');

    $this->drop_table('industries');

    // $this->remove_column('table_name', 'column_name');
  }
}