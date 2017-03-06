<?php defined('SYSPATH') or die('No direct script access.');

class add_column_able_to_bill extends Migration
{
  public function up()
  {
      $this->add_column('users', 'is_able_to_bill', array('boolean'));
  }

  public function down()
  {
      $this->remove_column('users', 'is_able_to_bill');
  }
}