<?php defined('SYSPATH') or die('No direct script access.');

class conversation_interactions extends Migration
{
    public function up()
    {
        $this->create_table
        (
            'conversation_interactions',
            array
            (
                'conversation_interaction_id'   => ['integer', 'unsigned' => true],
                'conversation_id'               => ['integer', 'unsigned' => true],
                'user_id'                       => ['integer', 'unsigned' => true],
                'is_deleted'                    => ['boolean'],
                'created_at'                    => ['datetime'],
                'updated_at'                    => ['datetime']
            ),
            'conversation_interaction_id'
        );

        $this->add_index('conversation_interactions', 'is_deleted', ['is_deleted']);

        $this->sql("ALTER TABLE `conversation_interactions` ADD FOREIGN KEY (`conversation_id`) REFERENCES `conversations`(`conversation_id`) ON DELETE CASCADE ON UPDATE CASCADE");
        $this->sql("ALTER TABLE `conversation_interactions` ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE");
    }

    public function down()
    {
        $this->sql("ALTER TABLE `conversation_interactions` DROP FOREIGN KEY user_id");
        $this->sql("ALTER TABLE `conversation_interactions` DROP FOREIGN KEY conversation_id");

        $this->remove_index('conversation_interactions', 'is_deleted');
        $this->drop_table('conversation_interactions');
    }
}