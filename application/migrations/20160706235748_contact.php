<?php defined('SYSPATH') or die('No direct script access.');

class contact extends Migration
{
  public function up()
  {
    $this->create_table
    (
      'contact',
       array
       (
          'contact_id'  => ['integer', 'unsigned' => true],
          'user_id'   => ['integer', 'unsigned' => true],
          'message'   => ['text'],
          'email'     => ['string[255]'],
          'created_at'  => ['datetime'],
       ),
       'contact_id'
    );
    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));

    $this->add_index('contact', 'user_id', ['user_id'], 'normal');    
    $this->sql("ALTER TABLE `contact` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE SET NULL ON UPDATE CASCADE");
  }

  public function down()
  {
    $this->sql("ALTER TABLE `contact` DROP FOREIGN KEY user_id");

    $this->remove_index('contact', 'user_id');

    $this->drop_table('contact');

    // $this->remove_column('table_name', 'column_name');
  }
}