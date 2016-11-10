<?php defined('SYSPATH') or die('No direct script access.');

class users_paypal_account extends Migration
{
  public function up()
  {
     $this->add_column('users', 'paypal_account', array('string[100]', 'default' => NULL));
  }

  public function down()
  {
     $this->remove_column('users', 'paypal_account');
  }
}