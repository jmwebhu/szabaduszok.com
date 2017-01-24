<?php defined('SYSPATH') or die('No direct script access.');

class message_interactions extends Migration
{
    public function up()
    {
        $this->create_table
        (
            'message_interactions',
            array
            (
                'message_interaction_id'   => ['integer', 'unsigned' => true],
                'message_id'               => ['integer', 'unsigned' => true],
                'user_id'                       => ['integer', 'unsigned' => true],
                'is_deleted'                    => ['boolean'],
                'is_readed'                     => ['boolean'],
                'created_at'                    => ['datetime'],
                'updated_at'                    => ['datetime']
            ),
            'message_interaction_id'
        );

        $this->add_index('message_interactions', 'is_deleted', ['is_deleted']);
        $this->add_index('message_interactions', 'is_readed', ['is_readed']);

        $this->sql("ALTER TABLE `message_interactions` ADD FOREIGN KEY (`message_id`) REFERENCES `messages`(`message_id`) ON DELETE CASCADE ON UPDATE CASCADE");
        $this->sql("ALTER TABLE `message_interactions` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    }

    public function down()
    {
        $this->sql("ALTER TABLE `message_interactions` DROP FOREIGN KEY user_id");
        $this->sql("ALTER TABLE `message_interactions` DROP FOREIGN KEY message_id");

        $this->remove_index('message_interactions', 'is_deleted');
        $this->remove_index('message_interactions', 'is_readed');

        $this->drop_table('message_interactions');
    }
}