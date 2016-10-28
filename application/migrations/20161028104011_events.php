<?php defined('SYSPATH') or die('No direct script access.');

class events extends Migration
{
  public function up()
  {
     $this->create_table
     (
       'events',
       array
       (
           'event_id'          => ['integer', 'unsigned' => true],
           'name'              => ['string'],
           'template_name'     => ['string'],
           'subject_name'      => ['string']
       ),
        'event_id'
     );

      $this->sql("INSERT INTO `events` (`event_id`, `name`,`template_name`, `subject_name`) VALUES(NULL, 'Új projekt', 'project_new', 'project')");
      $this->sql("INSERT INTO `events` (`event_id`, `name`,`template_name`, `subject_name`) VALUES(NULL, 'Új jelentkező', 'candidate_new', 'user')");
      $this->sql("INSERT INTO `events` (`event_id`, `name`,`template_name`, `subject_name`) VALUES(NULL, 'Jelentkezés visszavonása', 'candidate_undo', 'user')");
      $this->sql("INSERT INTO `events` (`event_id`, `name`,`template_name`, `subject_name`) VALUES(NULL, 'Jelentkezés jóváhagyása', 'candidate_accept', 'user')");
      $this->sql("INSERT INTO `events` (`event_id`, `name`,`template_name`, `subject_name`) VALUES(NULL, 'Jelentkezés elutasítása', 'candidate_reject', 'user')");
      $this->sql("INSERT INTO `events` (`event_id`, `name`,`template_name`, `subject_name`) VALUES(NULL, 'Résztvevő törlése', 'participate_remove', 'user')");
      $this->sql("INSERT INTO `events` (`event_id`, `name`,`template_name`, `subject_name`) VALUES(NULL, 'Résztvevő kifizetése', 'participate_pay', 'user')");
      $this->sql("INSERT INTO `events` (`event_id`, `name`,`template_name`, `subject_name`) VALUES(NULL, 'Profil értékelése', 'profile_rate', 'user')");
  }

  public function down()
  {
    $this->drop_table('events');
  }
}