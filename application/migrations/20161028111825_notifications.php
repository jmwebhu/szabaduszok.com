<?php defined('SYSPATH') or die('No direct script access.');

class notifications extends Migration
{
  public function up()
  {
     $this->create_table
     (
       'notifications',
       array
       (
            'notification_id'           => ['integer', 'unsigned' => true],
            'notifier_user_id'          => ['integer', 'default' => null, 'unsigned' => true],
            'notified_user_id'          => ['integer', 'default' => null, 'unsigned' => true],
            'subject_id'                => ['integer', 'default' => null, 'unsigned' => true],
            'subject_name'              => ['string'],
            'event_id'                  => ['integer', 'default' => null, 'unsigned' => true],
            'url'                       => ['string'],
            'extra_data_json'           => ['string'],
            'is_archived'               => ['boolean', 'unsigned' => true],
            'updated_at'                => array('datetime'),
            'created_at'                => array('datetime'),
       ),
         'notification_id'
     );

      $this->add_index('notifications', 'subject_id', ['subject_id'], 'normal');
      $this->add_index('notifications', 'is_archived', ['is_archived'], 'normal');

      $this->sql("ALTER TABLE `notifications` ADD FOREIGN KEY (`notifier_user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE");
      $this->sql("ALTER TABLE `notifications` ADD FOREIGN KEY (`notified_user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE ON UPDATE CASCADE");
      $this->sql("ALTER TABLE `notifications` ADD FOREIGN KEY (`event_id`) REFERENCES `events`(`event_id`) ON DELETE CASCADE ON UPDATE CASCADE");
  }

  public function down()
  {
      $this->remove_index('notifications', 'subject_id');
      $this->remove_index('notifications', 'is_archived');

      $this->sql("ALTER TABLE `notifications` DROP FOREIGN KEY notifier_user_id");
      $this->sql("ALTER TABLE `notifications` DROP FOREIGN KEY notified_user_id");
      $this->sql("ALTER TABLE `notifications` DROP FOREIGN KEY event_id");

      $this->drop_table('notifications');
  }
}