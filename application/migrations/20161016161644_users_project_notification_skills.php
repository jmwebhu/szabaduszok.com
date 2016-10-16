<?php defined('SYSPATH') or die('No direct script access.');

class users_project_notification_skills extends Migration
{
    public function up()
    {
        $this->create_table
        (
            'users_project_notification_skills',
            array
            (
                'user_project_notification_skill_id'                 => ['integer', 'unsigned' => true],
                'user_id'                => ['integer', 'unsigned' => true],
                'skill_id'               => ['integer', 'unsigned' => true],
                'created_at'          => array('datetime')
            ),
            'user_project_notification_skill_id'
        );

        $this->sql("ALTER TABLE `users_project_notification_skills` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE");
        $this->sql("ALTER TABLE `users_project_notification_skills` ADD FOREIGN KEY (`skill_id`) REFERENCES `skills`(`skill_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    }

    public function down()
    {
        $this->sql("ALTER TABLE `users_project_notification_skills` DROP FOREIGN KEY user_id");
        $this->sql("ALTER TABLE `users_project_notification_skills` DROP FOREIGN KEY skill_id");

        $this->drop_table('users_project_notification_skills');
    }
}