<?php defined('SYSPATH') or die('No direct script access.');

class landing_pages extends Migration
{
  public function up()
  {
    $this->add_column('landing_pages', 'name', array('string[100]', 'default' => NULL));
  }

  public function down()
  {
    $this->remove_column('landing_pages', 'name');
  }
}