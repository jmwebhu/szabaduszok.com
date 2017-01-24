<?php defined('SYSPATH') or die('No direct script access.');

class messages extends Migration
{
    public function up()
    {
        $this->create_table
        (
            'messages',
            array
            (
                'message_id'        => ['integer', 'unsigned' => true],
                'conversation_id'   => ['integer', 'unsigned' => true],
                'sender_id'         => ['integer', 'unsigned' => true],
                'message'           => ['text'],
                'updated_at'        => ['datetime'],
                'created_at'        => ['datetime'],
            ),
            'message_id'
        );

        $this->sql("ALTER TABLE `messages` ADD FOREIGN KEY (`conversation_id`) REFERENCES `conversations`(`conversation_id`) ON DELETE SET NULL ON UPDATE CASCADE");
        $this->sql("ALTER TABLE `messages` ADD FOREIGN KEY (`sender_id`) REFERENCES `users`(`user_id`) ON DELETE SET NULL ON UPDATE CASCADE");
    }

    public function down()
    {
        $this->sql("ALTER TABLE `messages` DROP FOREIGN KEY user_id");
        $this->sql("ALTER TABLE `messages` DROP FOREIGN KEY conversation_id");

        $this->drop_table('messages');
    }
}