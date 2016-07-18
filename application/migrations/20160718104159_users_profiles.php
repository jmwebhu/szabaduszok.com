<?php defined('SYSPATH') or die('No direct script access.');

class users_profiles extends Migration
{
  public function up()
  {
    $this->create_table
    (
		'users_profiles',
		array
		(
			'user_id'		=> ['integer', 'unsigned' => true],
            'profile_id'	=> ['integer', 'unsigned' => true],
			'url'			=> ['text'],
            'created_at'	=> ['datetime'],
			'updated_at'	=> ['datetime']         			
		)
    );
	
	$this->add_index('users_profiles', 'user_id', ['user_id'], 'normal');
    $this->add_index('users_profiles', 'profile_id', ['profile_id'], 'normal');
    $this->add_index('users_profiles', 'user_id_profile_id', ['user_id', 'profile_id'], 'unique');

    $this->sql("ALTER TABLE `users_profiles` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    $this->sql("ALTER TABLE `users_profiles` ADD FOREIGN KEY (`profile_id`) REFERENCES `profiles`(`profile_id`) ON DELETE CASCADE ON UPDATE CASCADE");
  }

  public function down()
  {
	$this->sql("ALTER TABLE `users_profiles` DROP FOREIGN KEY profile_id");
    $this->sql("ALTER TABLE `users_profiles` DROP FOREIGN KEY user_id");    

    $this->remove_index('users_profiles', 'user_id_profile_id');
    $this->remove_index('users_profiles', 'profile_id');
    $this->remove_index('users_profiles', 'user_id');
	
    $this->drop_table('users_profiles');

    // $this->remove_column('table_name', 'column_name');
  }
}