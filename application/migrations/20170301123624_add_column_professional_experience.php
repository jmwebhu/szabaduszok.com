<?php defined('SYSPATH') or die('No direct script access.');

class add_column_professional_experience extends Migration
{
    public function up()
    {
        $this->add_column('users', 'professional_experience', array('decimal[10,2]', 'default' => NULL));
    }

    public function down()
    {
        $this->remove_column('users', 'professional_experience');
    }
}