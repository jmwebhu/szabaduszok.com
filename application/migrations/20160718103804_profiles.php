<?php defined('SYSPATH') or die('No direct script access.');

class profiles extends Migration
{
  public function up()
  {
    $this->create_table
    (
		'profiles',
		array
		(
			'profile_id'		=> ['integer', 'unsigned' => true],
            'name'              => ['string'],
            'slug'              => ['string'],
			'icon'				=> ['string'],
            'icon_type'			=> ['string'],
			'icon_type_group'	=> ['string'],
			'base_url'          => ['string'],
			'is_active'			=> ['boolean']
			
		),
		'profile_id'
    );
	
	$this->add_index('profiles', 'is_active', ['is_active'], 'normal');
	$this->add_index('profiles', 'slug', ['slug'], 'unique');

	$this->sql("INSERT INTO `profiles` (`profile_id`, `name`,`slug`, `icon`, `icon_type`, `icon_type_group`, `base_url`, `is_active`) VALUES(NULL, 'LinkedIn', 'linkedin', 'fa-linkedin', 'fa', 'span', 'linkedin.com', '1')");
	$this->sql("INSERT INTO `profiles` (`profile_id`, `name`,`slug`, `icon`, `icon_type`, `icon_type_group`, `base_url`, `is_active`) VALUES(NULL, 'Stackoverflow', 'stackoverflow', 'fa-stack-overflow ', 'fa', 'span', 'stackoverflow.com', '1')");
	$this->sql("INSERT INTO `profiles` (`profile_id`, `name`,`slug`, `icon`, `icon_type`, `icon_type_group`, `base_url`, `is_active`) VALUES(NULL, 'Facebook', 'facebook', 'fa-facebook', 'fa', 'span', 'facebook.com', '1')");
	$this->sql("INSERT INTO `profiles` (`profile_id`, `name`,`slug`, `icon`, `icon_type`, `icon_type_group`, `base_url`, `is_active`) VALUES(NULL, 'Twitter', 'twitter', 'fa-twitter', 'fa', 'span', 'twitter.com', '1')");
  }

  public function down()
  {
    $this->drop_table('profiles');

    $this->sql("DELETE FROM `profiles` WHERE slug = 'linkedin'");
	$this->sql("DELETE FROM `profiles` WHERE slug = 'stackoverflow'");
	$this->sql("DELETE FROM `profiles` WHERE slug = 'facebook'");
	$this->sql("DELETE FROM `profiles` WHERE slug = 'twitter'");
  }
}