<?php defined('SYSPATH') or die('No direct script access.');

class userslandingmod extends Migration
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

    $this->add_column('users', 'need_project_notification', array('boolean', 'default' => 1, 'unsigned' => true));
    $this->add_column('users', 'landing_page_id', array('integer', 'default' => null, 'unsigned' => true));
    
    $this->add_index('users', 'landing_page_id', ['landing_page_id'], 'normal');
    $this->add_index('users', 'need_project_notification', ['need_project_notification'], 'normal');
    
    $this->sql("ALTER TABLE `users` ADD FOREIGN KEY (`landing_page_id`) REFERENCES `landing_pages`(`landing_page_id`) ON DELETE SET NULL ON UPDATE CASCADE");
  }

  public function down()
  {
    //$this->drop_table('table_name');

    $this->sql("ALTER TABLE `users` DROP FOREIGN KEY landing_page_id");
    
    $this->remove_index('users', 'landing_page_id');
    $this->remove_index('users', 'need_project_notification');
    
    $this->remove_column('users', 'landing_page_id');    
    $this->remove_column('users', 'need_project_notification');    
  }
}