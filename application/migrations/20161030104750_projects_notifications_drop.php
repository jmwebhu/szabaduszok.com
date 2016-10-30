<?php defined('SYSPATH') or die('No direct script access.');

class projects_notifications_drop extends Migration
{
  public function up()
  {
      $this->drop_table('projects_notifications');
  }

  public function down()
  {
  }
}