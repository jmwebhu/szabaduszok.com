<?php defined('SYSPATH') or die('No direct script access.');

class lead_magnets_init extends Migration
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

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));
	$this->sql("INSERT INTO `lead_magnets` (`lead_magnet_id`, `name`,`path`, `is_current`, `type`) VALUES(NULL, 'NÉLKÜLÖZHETETLEN ESZKÖZÖK PROJEKTEK MEGVALÓSÍTÁSÁHOZ', 'nelkulozhetetlen-eszkozok-porjektek-megvalositasahoz.pdf', '1', '1')");
	$this->sql("INSERT INTO `lead_magnets` (`lead_magnet_id`, `name`,`path`, `is_current`, `type`) VALUES(NULL, 'NÉLKÜLÖZHETETLEN ESZKÖZÖK PROJEKTEK MEGVALÓSÍTÁSÁHOZ', 'nelkulozhetetlen-eszkozok-porjektek-megvalositasahoz.pdf', '1', '2')");
  }

  public function down()
  {
    // $this->drop_table('table_name');

    // $this->remove_column('table_name', 'column_name');
  }
}