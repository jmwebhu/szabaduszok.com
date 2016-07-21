<?php defined('SYSPATH') or die('No direct script access.');

class profiles_dataupdate extends Migration
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

    //$this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));
	  
	$this->sql("INSERT INTO `profiles` (`profile_id`, `name`,`slug`, `icon`, `icon_type`, `icon_type_group`, `base_url`, `is_active`) VALUES(NULL, 'Tumblr', 'tumblr', 'fa-tumblr', 'fa', 'span', 'tumblr.com', '1')");
	$this->sql("INSERT INTO `profiles` (`profile_id`, `name`,`slug`, `icon`, `icon_type`, `icon_type_group`, `base_url`, `is_active`) VALUES(NULL, 'Behance', 'behance', 'fa-behance', 'fa', 'span', 'behance.net', '1')");
  }

  public function down()
  {
    // $this->drop_table('table_name');

    // $this->remove_column('table_name', 'column_name');
	  
	$this->sql("DELETE FROM `profiles` WHERE slug = 'tumblr'");
	$this->sql("DELETE FROM `profiles` WHERE slug = 'behance'");
  }
}