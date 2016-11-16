<?php defined('SYSPATH') or die('No direct script access.');

class users extends Migration
{
  public function up()
  {
    $this->create_table
    (
      'users',
      array
      (
        'user_id'                 => ['integer', 'unsigned' => true],
        'lastname'                => ['string[100]'],
        'firstname'               => ['string[100]'],
        'email'                   => ['string[100]'],
        'password'                => ['string[64]'],
        'logins'                  => ['integer', 'unsigned' => true],
        'last_login'              => ['integer', 'unsigned' => true],
        'address_postal_code'     => ['integer', 'unsigned' => true],
        'address_city'            => ['string[100]'],
        'address_street'          => ['string[255]'],
        'phonenumber'             => ['string[50]'],
        'slug'                    => ['text'],
        'type'                    => ['integer', 'unsigned' => true],
        'min_net_hourly_wage'     => ['decimal'],
        'short_description'       => ['text'],
        'profile_picture_path'    => ['text'],
        'list_picture_path'       => ['text'],
        'cv_path'                 => ['text'],
        'is_company'              => ['boolean'],
        'company_name'            => ['string[100]'],
        'created_at'              => ['datetime'],
        'updated_at'              => ['datetime'],
        'rating_points_sum'       => ['integer', 'unsigned' => true],
        'raters_count'            => ['integer', 'unsigned' => true],
        'rating_points_avg'       => ['decimal', 'unsigned' => true],
        'skill_relation'          => ['integer', 'unsigned' => true],
        'is_admin'                => ['boolean'],
        'search_text'             => ['text'],
        'old_user_id'             => ['integer', 'unsigned' => true],
        'password_plain'          => ['string[20]'],
      ),
      'user_id'
    );

    //$this->add_column('users', 'need_project_notification', array('integer', 'default' => 1));

    $this->add_index('users', 'is_company', ['is_company'], 'normal');     
    $this->add_index('users', 'type', ['type'], 'normal');   
    $this->add_index('users', 'email', ['email'], 'unique');     

    $this->sql("ALTER TABLE `users` ADD UNIQUE (`slug`(100))"); 
    $this->sql("ALTER TABLE `users` ADD INDEX (`search_text`(100))"); 
  }

  public function down()
  {
    $this->remove_index('users', 'search_text');
    $this->remove_index('users', 'slug');
    $this->remove_index('users', 'email');
    $this->remove_index('users', 'type');
    $this->remove_index('users', 'is_company');

    $this->drop_table('users');

    // $this->remove_column('table_name', 'column_name');
  }
}