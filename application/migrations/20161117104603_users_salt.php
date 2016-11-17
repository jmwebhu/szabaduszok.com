<?php defined('SYSPATH') or die('No direct script access.');

class users_salt extends Migration
{
  public function up()
  {
    $this->add_column('users', 'salt', array('string[64]', 'default' => NULL));
  }

  public function down()
  {
    $this->remove_column('users', 'salt');
  }
}