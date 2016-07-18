<?php defined('SYSPATH') or die('No direct script access.');

class projects extends Migration
{
  public function up()
  {
    $this->create_table
    (
      'projects',
       array
       (
          'project_id'          => ['integer', 'unsigned' => true],
          'user_id'       => ['integer', 'unsigned' => true],
          'name'          => ['string[255]'],
          'short_description'   => ['text'],
          'long_description'    => ['text'],
          'email'         => ['string[255]'],
          'phonenumber'     => ['string[255]'],
          'is_active'       => ['boolean'],
          'is_paid'       => ['boolean'],
          'search_text'     => ['text'],
          'expiration_date'   => ['datetime'],
          'salary_type'     => ['integer', 'unsigned' => true],
          'salary_low'      => ['decimal'],
          'salary_high'     => ['decimal'],
          'slug'          => ['text'],
          'created_at'      => ['datetime'],
          'updated_at'      => ['datetime'], 
       ),
       'project_id'
    );

    // $this->add_column('table_name', 'column_name', array('datetime', 'default' => NULL));

    $this->add_index('projects', 'name', ['name'], 'normal');        
    $this->add_index('projects', 'is_active', ['is_active'], 'normal');    
    $this->add_index('projects', 'user_id', ['user_id'], 'normal');   

    $this->sql("ALTER TABLE `projects` ADD UNIQUE (`slug`(100))"); 

    $this->sql("ALTER TABLE `projects` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE SET NULL ON UPDATE CASCADE");
  }

  public function down()
  {
    $this->sql("ALTER TABLE `projects` DROP FOREIGN KEY user_id");

    $this->remove_index('projects', 'slug');
    $this->remove_index('projects', 'user_id');
    $this->remove_index('projects', 'is_active');
    $this->remove_index('projects', 'name');

    $this->drop_table('projects');

    // $this->remove_column('table_name', 'column_name');
  }
}