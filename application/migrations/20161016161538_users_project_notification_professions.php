<?php defined('SYSPATH') or die('No direct script access.');

class users_project_notification_professions extends Migration
{
    public function up()
    {
        $this->create_table
        (
            'users_project_notification_professions',
            array
            (
                'user_project_notification_profession_id'                 => ['integer', 'unsigned' => true],
                'user_id'                => ['integer', 'unsigned' => true],
                'profession_id'               => ['integer', 'unsigned' => true],
                'created_at'          => array('datetime')
            ),
            'user_project_notification_profession_id'
        );

        $this->sql("ALTER TABLE `users_project_notification_professions` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE");
        $this->sql("ALTER TABLE `users_project_notification_professions` ADD FOREIGN KEY (`profession_id`) REFERENCES `professions`(`profession_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    }

    public function down()
    {
        $this->sql("ALTER TABLE `users_project_notification_professions` DROP FOREIGN KEY user_id");
        $this->sql("ALTER TABLE `users_project_notification_professions` DROP FOREIGN KEY profession_id");

        $this->drop_table('users_project_notification_professions');
    }
}