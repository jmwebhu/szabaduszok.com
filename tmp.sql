#
# SQL Export
# Created by Querious (1055)
# Created: 2016. november 28. 21:11:26 CET
# Encoding: Unicode (UTF-8)
#


SET @PREVIOUS_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;


DROP TABLE IF EXISTS `users_skills`;
DROP TABLE IF EXISTS `users_ratings`;
DROP TABLE IF EXISTS `users_project_notification_skills`;
DROP TABLE IF EXISTS `users_project_notification_professions`;
DROP TABLE IF EXISTS `users_project_notification_industries`;
DROP TABLE IF EXISTS `users_profiles`;
DROP TABLE IF EXISTS `users_professions`;
DROP TABLE IF EXISTS `users_industries`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `user_tokens`;
DROP TABLE IF EXISTS `skills`;
DROP TABLE IF EXISTS `signups`;
DROP TABLE IF EXISTS `roles_users`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `projects_skills`;
DROP TABLE IF EXISTS `projects_professions`;
DROP TABLE IF EXISTS `projects_partners`;
DROP TABLE IF EXISTS `projects_industries`;
DROP TABLE IF EXISTS `projects`;
DROP TABLE IF EXISTS `profiles`;
DROP TABLE IF EXISTS `professions`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `migrations`;
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `message_interactions`;
DROP TABLE IF EXISTS `lead_magnets`;
DROP TABLE IF EXISTS `landing_page_openings`;
DROP TABLE IF EXISTS `landing_pages`;
DROP TABLE IF EXISTS `industries`;
DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS `conversations_users`;
DROP TABLE IF EXISTS `conversations`;
DROP TABLE IF EXISTS `conversation_interactions`;
DROP TABLE IF EXISTS `contact`;


CREATE TABLE `contact` (
  `contact_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `message` text,
  `email` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`contact_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `conversation_interactions` (
  `conversation_interaction_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` int(11) unsigned DEFAULT '0',
  `user_id` int(11) unsigned DEFAULT '0',
  `is_deleted` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`conversation_interaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `conversations` (
  `conversation_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `slug` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`conversation_id`),
  KEY `slug` (`slug`(100)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `conversations_users` (
  `conversation_user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` int(11) unsigned DEFAULT '0',
  `user_id` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`conversation_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `events` (
  `event_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `template_name` varchar(255) DEFAULT NULL,
  `subject_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;


CREATE TABLE `industries` (
  `industry_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `slug` text,
  PRIMARY KEY (`industry_id`),
  UNIQUE KEY `slug` (`slug`(50)),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;


CREATE TABLE `landing_pages` (
  `landing_page_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(10) unsigned DEFAULT NULL,
  `counter` int(10) unsigned DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`landing_page_id`),
  KEY `name_index` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `landing_page_openings` (
  `landing_page_opening_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `landing_page_id` int(10) unsigned DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`landing_page_opening_id`),
  KEY `landing_page_id_index` (`landing_page_id`),
  CONSTRAINT `landing_page_openings_ibfk_1` FOREIGN KEY (`landing_page_id`) REFERENCES `landing_pages` (`landing_page_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `lead_magnets` (
  `lead_magnet_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `is_current` tinyint(1) unsigned DEFAULT NULL,
  `type` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`lead_magnet_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


CREATE TABLE `message_interactions` (
  `message_interaction_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `message_id` int(11) unsigned DEFAULT '0',
  `user_id` int(11) unsigned DEFAULT '0',
  `is_deleted` tinyint(1) unsigned DEFAULT '0',
  `is_readed` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`message_interaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `messages` (
  `message_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` int(11) unsigned DEFAULT '0',
  `sender_id` int(11) unsigned DEFAULT '0',
  `message` longtext,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `migrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(30) NOT NULL,
  `name` varchar(100) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;


CREATE TABLE `notifications` (
  `notification_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `notifier_user_id` int(10) unsigned DEFAULT NULL,
  `notified_user_id` int(10) unsigned DEFAULT NULL,
  `subject_id` int(10) unsigned DEFAULT NULL,
  `subject_name` varchar(255) DEFAULT NULL,
  `event_id` int(10) unsigned DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `extra_data_json` text,
  `is_archived` tinyint(1) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`notification_id`),
  KEY `subject_id` (`subject_id`),
  KEY `is_archived` (`is_archived`),
  KEY `notifier_user_id` (`notifier_user_id`),
  KEY `notified_user_id` (`notified_user_id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`notifier_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`notified_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `notifications_ibfk_3` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=utf8;


CREATE TABLE `professions` (
  `profession_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `slug` text,
  PRIMARY KEY (`profession_id`),
  UNIQUE KEY `slug` (`slug`(50)),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `profiles` (
  `profile_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `icon_type` varchar(255) DEFAULT NULL,
  `icon_type_group` varchar(255) DEFAULT NULL,
  `base_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`profile_id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;


CREATE TABLE `projects` (
  `project_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `short_description` text,
  `long_description` text,
  `email` varchar(255) DEFAULT NULL,
  `phonenumber` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `is_paid` tinyint(1) DEFAULT NULL,
  `search_text` text,
  `expiration_date` datetime DEFAULT NULL,
  `salary_type` int(10) unsigned DEFAULT NULL,
  `salary_low` decimal(10,0) DEFAULT NULL,
  `salary_high` decimal(10,0) DEFAULT NULL,
  `slug` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`project_id`),
  UNIQUE KEY `slug` (`slug`(100)),
  KEY `name` (`name`),
  KEY `is_active` (`is_active`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10036 DEFAULT CHARSET=utf8;


CREATE TABLE `projects_industries` (
  `project_id` int(10) unsigned DEFAULT NULL,
  `industry_id` int(10) unsigned DEFAULT NULL,
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_id_industry_id` (`project_id`,`industry_id`),
  KEY `project_id` (`project_id`),
  KEY `industry_id` (`industry_id`),
  CONSTRAINT `projects_industries_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projects_industries_ibfk_2` FOREIGN KEY (`industry_id`) REFERENCES `industries` (`industry_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


CREATE TABLE `projects_partners` (
  `project_partner_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `project_id` int(10) unsigned DEFAULT NULL,
  `type` int(10) unsigned DEFAULT NULL,
  `notification_id` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  PRIMARY KEY (`project_partner_id`),
  UNIQUE KEY `user_id_project_id` (`user_id`,`project_id`),
  KEY `type` (`type`),
  KEY `project_id` (`project_id`),
  KEY `notification_id` (`notification_id`),
  CONSTRAINT `projects_partners_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projects_partners_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projects_partners_ibfk_3` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`notification_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `projects_professions` (
  `project_id` int(10) unsigned DEFAULT NULL,
  `profession_id` int(10) unsigned DEFAULT NULL,
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_id_profession_id` (`project_id`,`profession_id`),
  KEY `project_id` (`project_id`),
  KEY `profession_id` (`profession_id`),
  CONSTRAINT `projects_professions_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projects_professions_ibfk_2` FOREIGN KEY (`profession_id`) REFERENCES `professions` (`profession_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


CREATE TABLE `projects_skills` (
  `project_id` int(10) unsigned DEFAULT NULL,
  `skill_id` int(10) unsigned DEFAULT NULL,
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_id_skill_id` (`project_id`,`skill_id`),
  KEY `project_id` (`project_id`),
  KEY `skill_id` (`skill_id`),
  CONSTRAINT `projects_skills_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projects_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`skill_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `roles_users` (
  `user_id` int(10) unsigned DEFAULT NULL,
  `role_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `signups` (
  `signup_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`signup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `skills` (
  `skill_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `slug` text,
  PRIMARY KEY (`skill_id`),
  UNIQUE KEY `slug` (`slug`(50)),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


CREATE TABLE `user_tokens` (
  `id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `user_agent` varchar(40) DEFAULT NULL,
  `token` varchar(40) DEFAULT NULL,
  `created` int(10) unsigned DEFAULT NULL,
  `expires` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lastname` varchar(100) DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `logins` int(10) unsigned DEFAULT NULL,
  `last_login` int(10) unsigned DEFAULT NULL,
  `address_postal_code` int(10) unsigned DEFAULT NULL,
  `address_city` varchar(100) DEFAULT NULL,
  `address_street` varchar(255) DEFAULT NULL,
  `phonenumber` varchar(50) DEFAULT NULL,
  `slug` text,
  `type` int(10) unsigned DEFAULT NULL,
  `min_net_hourly_wage` decimal(10,0) DEFAULT NULL,
  `short_description` text,
  `profile_picture_path` text,
  `list_picture_path` text,
  `cv_path` text,
  `is_company` tinyint(1) DEFAULT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `rating_points_sum` int(10) unsigned DEFAULT NULL,
  `raters_count` int(10) unsigned DEFAULT NULL,
  `rating_points_avg` decimal(10,0) unsigned DEFAULT NULL,
  `skill_relation` int(10) unsigned DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT NULL,
  `search_text` text,
  `need_project_notification` tinyint(1) unsigned DEFAULT '1',
  `landing_page_id` int(10) unsigned DEFAULT NULL,
  `webpage` varchar(150) DEFAULT NULL,
  `salt` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `slug` (`slug`(100)),
  KEY `is_company` (`is_company`),
  KEY `type` (`type`),
  KEY `search_text` (`search_text`(100)),
  KEY `landing_page_id` (`landing_page_id`),
  KEY `need_project_notification` (`need_project_notification`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`landing_page_id`) REFERENCES `landing_pages` (`landing_page_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=863 DEFAULT CHARSET=utf8;


CREATE TABLE `users_industries` (
  `user_id` int(10) unsigned DEFAULT NULL,
  `industry_id` int(10) unsigned DEFAULT NULL,
  UNIQUE KEY `user_id_industry_id` (`user_id`,`industry_id`),
  KEY `user_id` (`user_id`),
  KEY `industry_id` (`industry_id`),
  CONSTRAINT `users_industries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_industries_ibfk_2` FOREIGN KEY (`industry_id`) REFERENCES `industries` (`industry_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `users_professions` (
  `user_id` int(10) unsigned DEFAULT NULL,
  `profession_id` int(10) unsigned DEFAULT NULL,
  UNIQUE KEY `user_id_profession_id` (`user_id`,`profession_id`),
  KEY `user_id` (`user_id`),
  KEY `profession_id` (`profession_id`),
  CONSTRAINT `users_professions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_professions_ibfk_2` FOREIGN KEY (`profession_id`) REFERENCES `professions` (`profession_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `users_profiles` (
  `user_id` int(10) unsigned DEFAULT NULL,
  `profile_id` int(10) unsigned DEFAULT NULL,
  `url` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  UNIQUE KEY `user_id_profile_id` (`user_id`,`profile_id`),
  KEY `user_id` (`user_id`),
  KEY `profile_id` (`profile_id`),
  CONSTRAINT `users_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_profiles_ibfk_2` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`profile_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `users_project_notification_industries` (
  `user_project_notification_industry_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `industry_id` int(10) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_project_notification_industry_id`),
  UNIQUE KEY `user_id_industry_id` (`user_id`,`industry_id`),
  KEY `industry_id` (`industry_id`),
  CONSTRAINT `users_project_notification_industries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_project_notification_industries_ibfk_2` FOREIGN KEY (`industry_id`) REFERENCES `industries` (`industry_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;


CREATE TABLE `users_project_notification_professions` (
  `user_project_notification_profession_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `profession_id` int(10) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_project_notification_profession_id`),
  UNIQUE KEY `user_id_profession_id` (`user_id`,`profession_id`),
  KEY `profession_id` (`profession_id`),
  CONSTRAINT `users_project_notification_professions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_project_notification_professions_ibfk_2` FOREIGN KEY (`profession_id`) REFERENCES `professions` (`profession_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;


CREATE TABLE `users_project_notification_skills` (
  `user_project_notification_skill_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `skill_id` int(10) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_project_notification_skill_id`),
  UNIQUE KEY `user_id_skill_id` (`user_id`,`skill_id`),
  KEY `skill_id` (`skill_id`),
  CONSTRAINT `users_project_notification_skills_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_project_notification_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`skill_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8;


CREATE TABLE `users_ratings` (
  `user_id` int(10) unsigned DEFAULT NULL,
  `rater_user_id` int(10) unsigned DEFAULT NULL,
  `rating_point` int(10) unsigned DEFAULT NULL,
  UNIQUE KEY `user_id_rater_user_id` (`user_id`,`rater_user_id`),
  KEY `user_id` (`user_id`),
  KEY `rater_user_id` (`rater_user_id`),
  CONSTRAINT `users_ratings_ibfk_1` FOREIGN KEY (`rater_user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `users_ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `users_skills` (
  `user_id` int(10) unsigned DEFAULT NULL,
  `skill_id` int(10) unsigned DEFAULT NULL,
  UNIQUE KEY `user_id_skill_id` (`user_id`,`skill_id`),
  KEY `user_id` (`user_id`),
  KEY `skill_id` (`skill_id`),
  CONSTRAINT `users_skills_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`skill_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




SET FOREIGN_KEY_CHECKS = @PREVIOUS_FOREIGN_KEY_CHECKS;


SET @PREVIOUS_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;


LOCK TABLES `contact` WRITE;
ALTER TABLE `contact` DISABLE KEYS;
ALTER TABLE `contact` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `conversation_interactions` WRITE;
ALTER TABLE `conversation_interactions` DISABLE KEYS;
ALTER TABLE `conversation_interactions` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `conversations` WRITE;
ALTER TABLE `conversations` DISABLE KEYS;
ALTER TABLE `conversations` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `conversations_users` WRITE;
ALTER TABLE `conversations_users` DISABLE KEYS;
ALTER TABLE `conversations_users` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `events` WRITE;
ALTER TABLE `events` DISABLE KEYS;
INSERT INTO `events` (`event_id`, `name`, `template_name`, `subject_name`) VALUES 
	(1,'Új projekt','project_new','project'),
	(2,'Új jelentkező','candidate_new','user'),
	(3,'Jelentkezés visszavonása','candidate_undo','user'),
	(4,'Jelentkezés jóváhagyása','candidate_accept','user'),
	(5,'Jelentkezés elutasítása','candidate_reject','user'),
	(6,'Résztvevő törlése','participate_remove','user'),
	(7,'Résztvevő kifizetése','participate_pay','user'),
	(8,'Profil értékelése','profile_rate','user'),
	(9,'Új üzenet','message_new','message');
ALTER TABLE `events` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `industries` WRITE;
ALTER TABLE `industries` DISABLE KEYS;
INSERT INTO `industries` (`industry_id`, `name`, `slug`) VALUES 
	(1,'Informatika','informatika'),
	(2,'Marketing','marketing'),
	(3,'Média, PR','media-pr'),
	(4,'Menedzsment','menedzsment'),
	(5,'Adminisztráció','adminisztracio'),
	(6,'Pénzügy','penzugy'),
	(7,'Ipar','ipar');
ALTER TABLE `industries` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `landing_pages` WRITE;
ALTER TABLE `landing_pages` DISABLE KEYS;
ALTER TABLE `landing_pages` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `landing_page_openings` WRITE;
ALTER TABLE `landing_page_openings` DISABLE KEYS;
ALTER TABLE `landing_page_openings` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `lead_magnets` WRITE;
ALTER TABLE `lead_magnets` DISABLE KEYS;
INSERT INTO `lead_magnets` (`lead_magnet_id`, `name`, `path`, `is_current`, `type`) VALUES 
	(1,'NÉLKÜLÖZHETETLEN ESZKÖZÖK PROJEKTEK MEGVALÓSÍTÁSÁHOZ','nelkulozhetetlen-eszkozok-porjektek-megvalositasahoz.pdf',1,1),
	(2,'NÉLKÜLÖZHETETLEN ESZKÖZÖK PROJEKTEK MEGVALÓSÍTÁSÁHOZ','nelkulozhetetlen-eszkozok-porjektek-megvalositasahoz.pdf',1,2);
ALTER TABLE `lead_magnets` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `message_interactions` WRITE;
ALTER TABLE `message_interactions` DISABLE KEYS;
ALTER TABLE `message_interactions` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `messages` WRITE;
ALTER TABLE `messages` DISABLE KEYS;
ALTER TABLE `messages` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `migrations` WRITE;
ALTER TABLE `migrations` DISABLE KEYS;
INSERT INTO `migrations` (`id`, `hash`, `name`, `updated_at`, `created_at`) VALUES 
	(1,'20160706001419','skills',NULL,NULL),
	(2,'20160706233521','users',NULL,NULL),
	(3,'20160706234718','projects',NULL,NULL),
	(4,'20160706235748','contact',NULL,NULL),
	(5,'20160707000220','error_logs',NULL,NULL),
	(6,'20160707000351','industries',NULL,NULL),
	(7,'20160707000523','migrations',NULL,NULL),
	(8,'20160707000750','professions',NULL,NULL),
	(9,'20160707000828','projects_industries',NULL,NULL),
	(10,'20160707001014','projects_professions',NULL,NULL),
	(11,'20160707001056','projects_skills',NULL,NULL),
	(12,'20160707001148','roles',NULL,NULL),
	(13,'20160707001248','roles_users',NULL,NULL),
	(14,'20160707001458','user_tokens',NULL,NULL),
	(15,'20160707001655','users_industries',NULL,NULL),
	(16,'20160707001739','users_professions',NULL,NULL),
	(17,'20160707001816','users_project_notification',NULL,NULL),
	(18,'20160707001929','users_ratings',NULL,NULL),
	(19,'20160707002030','users_skills',NULL,NULL),
	(20,'20160707002143','landing_pages',NULL,NULL),
	(21,'20160707002445','landing_page_openings',NULL,NULL),
	(22,'20160708115724','projects_notifications',NULL,NULL),
	(23,'20160712172807','userslandingmod',NULL,NULL),
	(24,'20160712174212','signups',NULL,NULL),
	(25,'20160712222140','lead_magnets',NULL,NULL),
	(26,'20160718103804','profiles',NULL,NULL),
	(27,'20160718104159','users_profiles',NULL,NULL),
	(28,'20160720225547','users_profilewebpagemod',NULL,NULL),
	(29,'20160721135304','profiles_dataupdate',NULL,NULL),
	(30,'20160802105624','lead_magnets_init',NULL,NULL),
	(31,'20160927153405','projects_skills_pk',NULL,NULL),
	(32,'20160927153600','projects_professions_pk',NULL,NULL),
	(33,'20160927153621','projects_industries_pk',NULL,NULL),
	(34,'20161016161044','users_project_notification_drop',NULL,NULL),
	(35,'20161016161121','users_project_notification_industries',NULL,NULL),
	(36,'20161016161538','users_project_notification_professions',NULL,NULL),
	(37,'20161016161644','users_project_notification_skills',NULL,NULL),
	(38,'20161028104011','events',NULL,NULL),
	(39,'20161028111825','notifications',NULL,NULL),
	(40,'20161030104750','projects_notifications_drop',NULL,NULL),
	(41,'20161030134522','projects_partners',NULL,NULL),
	(42,'20161107181511','users_remove_old_columns',NULL,NULL),
	(43,'20161117104603','users_salt',NULL,NULL),
	(44,'20161117170902','drop_old_tables',NULL,NULL),
	(45,'20161124130739','messages',NULL,NULL),
	(46,'20161124143528','events_message_new',NULL,NULL);
ALTER TABLE `migrations` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `notifications` WRITE;
ALTER TABLE `notifications` DISABLE KEYS;
INSERT INTO `notifications` (`notification_id`, `notifier_user_id`, `notified_user_id`, `subject_id`, `subject_name`, `event_id`, `url`, `extra_data_json`, `is_archived`, `updated_at`, `created_at`) VALUES 
	(178,1,2,15,'message',9,'http://127.0.0.1/szabaduszok.com/uzenetek','{"message":"Hello"}',NULL,NULL,'2016-11-25 10:36:00'),
	(179,1,2,16,'message',9,'http://127.0.0.1/szabaduszok.com/uzenetek','{"message":"Hello"}',NULL,NULL,'2016-11-25 11:18:00');
ALTER TABLE `notifications` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `professions` WRITE;
ALTER TABLE `professions` DISABLE KEYS;
INSERT INTO `professions` (`profession_id`, `name`, `slug`) VALUES 
	(1,'webfejlesztés','webfejlesztes');
ALTER TABLE `professions` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `profiles` WRITE;
ALTER TABLE `profiles` DISABLE KEYS;
INSERT INTO `profiles` (`profile_id`, `name`, `slug`, `icon`, `icon_type`, `icon_type_group`, `base_url`, `is_active`) VALUES 
	(1,'LinkedIn','linkedin','fa-linkedin','fa','span','linkedin.com',1),
	(2,'Stackoverflow','stackoverflow','fa-stack-overflow ','fa','span','stackoverflow.com',1),
	(3,'Facebook','facebook','fa-facebook','fa','span','facebook.com',1),
	(4,'Twitter','twitter','fa-twitter','fa','span','twitter.com',1),
	(5,'Tumblr','tumblr','fa-tumblr','fa','span','tumblr.com',1),
	(6,'Behance','behance','fa-behance','fa','span','behance.net',1);
ALTER TABLE `profiles` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `projects` WRITE;
ALTER TABLE `projects` DISABLE KEYS;
INSERT INTO `projects` (`project_id`, `user_id`, `name`, `short_description`, `long_description`, `email`, `phonenumber`, `is_active`, `is_paid`, `search_text`, `expiration_date`, `salary_type`, `salary_low`, `salary_high`, `slug`, `created_at`, `updated_at`) VALUES 
	(10000,NULL,'Teszt projekt','Teszt projekt Teszt projekt','Teszt projekt Teszt projekt Teszt projekt','5836ef513834c@szabaduszok.com','06301923380',1,1,NULL,'2016-12-24 00:00:00',1,3200,NULL,'teszt-projekt','2016-11-24 14:46:00','2016-11-24 14:46:00'),
	(10001,6,'Teszt projekt','Teszt projekt Teszt projekt','Teszt projekt Teszt projekt Teszt projekt','5836ef517671d@szabaduszok.com','06301923380',1,1,NULL,'2016-12-24 00:00:00',1,3200,NULL,'teszt-projekt-10001','2016-11-24 14:46:00','2016-11-24 14:46:00'),
	(10003,775,'Teszt projekt','Teszt projekt Teszt projekt','Teszt projekt Teszt projekt Teszt projekt','5836efbcc5b83@szabaduszok.com','06301923380',1,1,NULL,'2016-12-24 00:00:00',1,3200,NULL,'teszt-projekt-10003','2016-11-24 14:48:00','2016-11-24 14:48:00'),
	(10007,805,'Teszt projekt','Teszt projekt Teszt projekt','Teszt projekt Teszt projekt Teszt projekt','5836f15b60b3a@szabaduszok.com','06301923380',1,1,'Teszt projekt Teszt projekt Teszt projekt Teszt projekt Teszt projekt Teszt projekt 5836f15b60b3a@szabaduszok.com 06301923380 2016-11-24 Joó Martin Szombathely    ','2016-12-24 00:00:00',1,3200,NULL,'teszt-projekt-10007','2016-11-24 14:55:00','2016-11-24 14:55:00'),
	(10008,818,'Teszt projekt','Teszt projekt Teszt projekt','Teszt projekt Teszt projekt Teszt projekt','5836f1cc36a82@szabaduszok.com','06301923380',1,1,'Teszt projekt Teszt projekt Teszt projekt Teszt projekt Teszt projekt Teszt projekt 5836f1cc36a82@szabaduszok.com 06301923380 2016-11-24 Joó Martin Szombathely    ','2016-12-24 00:00:00',1,3200,NULL,'teszt-projekt-10008','2016-11-24 14:57:00','2016-11-24 14:57:00'),
	(10016,856,'Teszt projekt','Teszt projekt Teszt projekt','Teszt projekt Teszt projekt Teszt projekt','5836f8a64ca37@szabaduszok.com','06301923380',1,1,'Teszt projekt Teszt projekt Teszt projekt Teszt projekt Teszt projekt Teszt projekt 5836f8a64ca37@szabaduszok.com 06301923380 2016-11-24 Joó Martin Szombathely    ','2016-12-24 00:00:00',1,3200,NULL,'teszt-projekt-10016','2016-11-24 15:26:00','2016-11-24 15:26:00'),
	(10018,860,'Teszt projekt','Teszt projekt Teszt projekt','Teszt projekt Teszt projekt Teszt projekt','5836f8e0afd4a@szabaduszok.com','06301923380',1,1,'Teszt projekt Teszt projekt Teszt projekt Teszt projekt Teszt projekt Teszt projekt 5836f8e0afd4a@szabaduszok.com 06301923380 2016-11-24 Joó Martin Szombathely    ','2016-12-24 00:00:00',1,3200,NULL,'teszt-projekt-10018','2016-11-24 15:27:00','2016-11-24 15:27:00'),
	(10021,1,'Teszt IT','Teszt ITTeszt IT','<p><b></b>Teszt ITTeszt ITTeszt ITTeszt IT<br></p>','megbizo@szabaduszok.com','06301923380',1,1,'Teszt IT Teszt ITTeszt IT <p><b></b>Teszt ITTeszt ITTeszt ITTeszt IT<br></p> megbizo@szabaduszok.com 06301923380 2016-11-25 Megbízó Martin Szombathely Jmweb Zrt. Informatika,  webfejlesztés,  ','2016-12-25 00:00:00',1,3000,0,'teszt-it','2016-11-25 08:43:00','2016-11-25 08:43:00'),
	(10035,1,'Teszt IT','Teszt ITTeszt IT','<p>Teszt ITTeszt ITTeszt ITTeszt IT<br></p>','megbizo@szabaduszok.com','06301923380',1,1,'Teszt IT Teszt ITTeszt IT <p>Teszt ITTeszt ITTeszt ITTeszt IT<br></p> megbizo@szabaduszok.com 06301923380 2016-11-25 Megbízó Martin Szombathely Jmweb Zrt. Informatika,  webfejlesztés,  php, ','2016-12-25 00:00:00',1,2500,3200,'teszt-it-10035','2016-11-25 09:30:00','2016-11-25 09:30:00');
ALTER TABLE `projects` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `projects_industries` WRITE;
ALTER TABLE `projects_industries` DISABLE KEYS;
INSERT INTO `projects_industries` (`project_id`, `industry_id`, `id`) VALUES 
	(10021,1,1),
	(10035,1,2);
ALTER TABLE `projects_industries` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `projects_partners` WRITE;
ALTER TABLE `projects_partners` DISABLE KEYS;
ALTER TABLE `projects_partners` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `projects_professions` WRITE;
ALTER TABLE `projects_professions` DISABLE KEYS;
INSERT INTO `projects_professions` (`project_id`, `profession_id`, `id`) VALUES 
	(10021,1,1),
	(10035,1,2);
ALTER TABLE `projects_professions` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `projects_skills` WRITE;
ALTER TABLE `projects_skills` DISABLE KEYS;
INSERT INTO `projects_skills` (`project_id`, `skill_id`, `id`) VALUES 
	(10035,1,1);
ALTER TABLE `projects_skills` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `roles` WRITE;
ALTER TABLE `roles` DISABLE KEYS;
ALTER TABLE `roles` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `roles_users` WRITE;
ALTER TABLE `roles_users` DISABLE KEYS;
ALTER TABLE `roles_users` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `signups` WRITE;
ALTER TABLE `signups` DISABLE KEYS;
ALTER TABLE `signups` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `skills` WRITE;
ALTER TABLE `skills` DISABLE KEYS;
INSERT INTO `skills` (`skill_id`, `name`, `slug`) VALUES 
	(1,'php','php'),
	(2,'mysql','mysql'),
	(3,'java','java');
ALTER TABLE `skills` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `user_tokens` WRITE;
ALTER TABLE `user_tokens` DISABLE KEYS;
ALTER TABLE `user_tokens` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `users` WRITE;
ALTER TABLE `users` DISABLE KEYS;
INSERT INTO `users` (`user_id`, `lastname`, `firstname`, `email`, `password`, `logins`, `last_login`, `address_postal_code`, `address_city`, `address_street`, `phonenumber`, `slug`, `type`, `min_net_hourly_wage`, `short_description`, `profile_picture_path`, `list_picture_path`, `cv_path`, `is_company`, `company_name`, `created_at`, `updated_at`, `rating_points_sum`, `raters_count`, `rating_points_avg`, `skill_relation`, `is_admin`, `search_text`, `need_project_notification`, `landing_page_id`, `webpage`, `salt`) VALUES 
	(1,'Megbízó','Martin','megbizo@szabaduszok.com','de1d34af8d9cd8b27b1f3c76a23a062c30cfe54174e73e8d5b0e36cdfa6af60e',NULL,1480062564,9700,'Szombathely','','06301923380','megbizo-martin',2,NULL,'',NULL,NULL,NULL,1,'Jmweb Zrt.','2016-11-24 13:59:00','2016-11-25 09:29:00',NULL,NULL,NULL,NULL,NULL,'Megbízó Martin  2016-11-24 Szombathely Jmweb Zrt. Informatika,  webfejlesztés,  ',1,NULL,NULL,'f1ad67a2acc6753f8cf1d56ba61e2456d35289f67896ee84d63d195937ee5cde'),
	(2,'Szabadúszó','János','janos@szabaduszok.com','b3f2007aac9b9b9bcd5ba6aa03f4214f99bf4179222958d74f29e8bebc74d274',NULL,1480067634,9700,'Szombathely','',NULL,'szabaduszo-janos',1,3000,'5 éves tapasztalat webes rendszerek fejlesztésében. Nagyvállalati rendszerek, folyamatirányítás, számlázás, üzteli folyamatok, nagy terhelésű rendszerek. 5 éves tapasztalat webes rendszerek fejlesztésében. Nagyvállalati rendszerek, folyamatirányítás, számlázás, üzteli folyamatok, nagy terhelésű rendszerek. 5 éves tapasztalat webes rendszerek fejlesztésében. Nagyvállalati rendszerek, folyamatirányítás, számlázás, üzteli folyamatok, nagy terhelésű rendszerek.',NULL,NULL,'szabaduszok-szabaduszo-janos-cv.pdf',NULL,NULL,'2016-11-24 13:59:00','2016-11-25 11:16:00',22,13,2,1,NULL,'Szabadúszó János 5 éves tapasztalat webes rendszerek fejlesztésében. Nagyvállalati rendszerek, folyamatirányítás, számlázás, üzteli folyamatok, nagy terhelésű rendszerek. 5 éves tapasztalat webes rendszerek fejlesztésében. Nagyvállalati rendszerek, folyamatirányítás, számlázás, üzteli folyamatok, nagy terhelésű rendszerek. 5 éves tapasztalat webes rendszerek fejlesztésében. Nagyvállalati rendszerek, folyamatirányítás, számlázás, üzteli folyamatok, nagy terhelésű rendszerek. 2016-11-25 Szombathely  Informatika,  webfejlesztés,  phpmysqljava, ',1,NULL,'http://szabaduszok.com','5a3f5568b53fe83dc8560266be5de272b31c8220e7095278fbf92874a17bd280'),
	(5,'Józsi','Web','5836ef5161d08@szabaduszok.com','59896a0fad3f3383640e706e80946997cc30273561b7638d56fa1ca24d2b8df6',NULL,1479995217,9700,'Szombathely',NULL,NULL,'jozsi-web',1,3000,NULL,NULL,NULL,NULL,NULL,NULL,'2016-11-24 14:46:00','2016-11-24 14:46:00',NULL,NULL,NULL,1,NULL,'Józsi Web  2016-11-24 Szombathely    ',1,NULL,NULL,'c04e86db38f719f01e971ed734956ba93c3ab086e5cae0d2bf22f4376e64a60e'),
	(6,'Joó','Martin','5836ef5172a3f@szabaduszok.com','ffe3025c65ca5ab04198e5a95e27741c51b11571d93e07c88bf22f95f154d119',NULL,1479995217,9700,'Szombathely',NULL,'06301923380','joo-martin',2,NULL,NULL,NULL,NULL,NULL,0,NULL,'2016-11-24 14:46:00','2016-11-24 14:46:00',NULL,NULL,NULL,NULL,NULL,'Joó Martin  2016-11-24 Szombathely    ',1,NULL,NULL,'4a738e69465a0c211fe8a2ebc3c47e4e658a111bdd96feac8e72132512f4132d'),
	(774,'Józsi','Web','5836efbcbaf97@szabaduszok.com','987d16abb08bbc84608146a5f6b62f5f55980e27b6049c3e9bff64ccea75fed5',NULL,1479995324,9700,'Szombathely',NULL,NULL,'jozsi-web-774',1,3000,NULL,NULL,NULL,NULL,NULL,NULL,'2016-11-24 14:48:00','2016-11-24 14:48:00',NULL,NULL,NULL,1,NULL,'Józsi Web  2016-11-24 Szombathely    ',1,NULL,NULL,'12a748488ec095c5211e75e81ae87f99279b541c1c43d1fe2693f47c8ca719f9'),
	(775,'Joó','Martin','5836efbcc23cb@szabaduszok.com','f5e5c040dc375a051b5cf0a221eb9a3f02f501d31a6129001bd0d8066c9959f5',NULL,1479995324,9700,'Szombathely',NULL,'06301923380','joo-martin-775',2,NULL,NULL,NULL,NULL,NULL,0,NULL,'2016-11-24 14:48:00','2016-11-24 14:48:00',NULL,NULL,NULL,NULL,NULL,'Joó Martin  2016-11-24 Szombathely    ',1,NULL,NULL,'09d3c47ef126b1532fefb8e4c16f83743708b24b0b67e6b430b637efb97d3873'),
	(804,'Józsi','Web','5836f15b550db@szabaduszok.com','448a895c8b513144b397bcd32b50155dfebf2a683d2e7583546966054d4f5cd5',NULL,1479995739,9700,'Szombathely',NULL,NULL,'jozsi-web-804',1,3000,NULL,NULL,NULL,NULL,NULL,NULL,'2016-11-24 14:55:00','2016-11-24 14:55:00',NULL,NULL,NULL,1,NULL,'Józsi Web  2016-11-24 Szombathely    ',1,NULL,NULL,'5301a3ade9994c0da3eb7a5abb18bd635ad42f9a91e632ce6a581d43d0bf8ed7'),
	(805,'Joó','Martin','5836f15b5cd72@szabaduszok.com','d86b44e3e3eb9b09b62b4ee2e430a9976ae87e2549934cdcab96f1fd6bbe8c10',NULL,1479995739,9700,'Szombathely',NULL,'06301923380','joo-martin-805',2,NULL,NULL,NULL,NULL,NULL,0,NULL,'2016-11-24 14:55:00','2016-11-24 14:55:00',NULL,NULL,NULL,NULL,NULL,'Joó Martin  2016-11-24 Szombathely    ',1,NULL,NULL,'5791b25dc94057e3269b590fd65391ab7af2a8abc52544f3e7cc1ad885d47058'),
	(817,'Józsi','Web','5836f1cc0e5f9@szabaduszok.com','efcc57f129363ca7b2c58a5902a712031b6f815ba1063d10489e975a19efe949',NULL,1479995852,9700,'Szombathely',NULL,NULL,'jozsi-web-817',1,3000,NULL,NULL,NULL,NULL,NULL,NULL,'2016-11-24 14:57:00','2016-11-24 14:57:00',NULL,NULL,NULL,1,NULL,'Józsi Web  2016-11-24 Szombathely    ',1,NULL,NULL,'984122ed7e08ac273e020f0e71f98da89bd39b0f0e9d059273dd715081fc9a91'),
	(818,'Joó','Martin','5836f1cc32b31@szabaduszok.com','bf2af2885714cfd94edbe144ab02d903348e20e97625501403ce7204e0cdc119',NULL,1479995852,9700,'Szombathely',NULL,'06301923380','joo-martin-818',2,NULL,NULL,NULL,NULL,NULL,0,NULL,'2016-11-24 14:57:00','2016-11-24 14:57:00',NULL,NULL,NULL,NULL,NULL,'Joó Martin  2016-11-24 Szombathely    ',1,NULL,NULL,'b22fa4d03b1f53a9386bc3481811b05473378169afe6e853940cc28d8d90d642'),
	(855,'Józsi','Web','5836f8a5f24f0@szabaduszok.com','2367d1e445f6acf0da0750dd817515191a28c3a8e76b2eb4003ef405264c7f32',NULL,1479997606,9700,'Szombathely',NULL,NULL,'jozsi-web-855',1,3000,NULL,NULL,NULL,NULL,NULL,NULL,'2016-11-24 15:26:00','2016-11-24 15:26:00',NULL,NULL,NULL,1,NULL,'Józsi Web  2016-11-24 Szombathely    ',1,NULL,NULL,'634f3c28b1a498776eec395ff467bdcb28be7e8b31bc9b72b8f8494131eb9413'),
	(856,'Joó','Martin','5836f8a63c8f1@szabaduszok.com','7e873b22168e2c587120117352deb1ffc1af78a816e48f1df4da7fdc9598ca9d',NULL,1479997606,9700,'Szombathely',NULL,'06301923380','joo-martin-856',2,NULL,NULL,NULL,NULL,NULL,0,NULL,'2016-11-24 15:26:00','2016-11-24 15:26:00',NULL,NULL,NULL,NULL,NULL,'Joó Martin  2016-11-24 Szombathely    ',1,NULL,NULL,'c9415068b0e8eba40cba76e4943f3d28bd8f978587961214ac863b0d2ffcaac7'),
	(859,'Józsi','Web','5836f8e04acb1@szabaduszok.com','6e470a5573ee84f5fd5f8a06086ef769ce1d4e7dfd0e8f0aca73d2d67100b628',NULL,1479997664,9700,'Szombathely',NULL,NULL,'jozsi-web-859',1,3000,NULL,NULL,NULL,NULL,NULL,NULL,'2016-11-24 15:27:00','2016-11-24 15:27:00',NULL,NULL,NULL,1,NULL,'Józsi Web  2016-11-24 Szombathely    ',1,NULL,NULL,'6efbc507a7bb3c4f582fcc37eb057ff0c029c706bd110154c1c2a6582ed9aa59'),
	(860,'Joó','Martin','5836f8e09fe36@szabaduszok.com','0ec33befaffbbf8c5c671fdb7245287c2edf040c6197f882196c73638c435fc2',NULL,1479997664,9700,'Szombathely',NULL,'06301923380','joo-martin-860',2,NULL,NULL,NULL,NULL,NULL,0,NULL,'2016-11-24 15:27:00','2016-11-24 15:27:00',NULL,NULL,NULL,NULL,NULL,'Joó Martin  2016-11-24 Szombathely    ',1,NULL,NULL,'bf65ec9b39844a42c40371a658855226abbeb0ece0c85ae716cb9962fcefdac2');
ALTER TABLE `users` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `users_industries` WRITE;
ALTER TABLE `users_industries` DISABLE KEYS;
INSERT INTO `users_industries` (`user_id`, `industry_id`) VALUES 
	(1,1),
	(2,1);
ALTER TABLE `users_industries` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `users_professions` WRITE;
ALTER TABLE `users_professions` DISABLE KEYS;
INSERT INTO `users_professions` (`user_id`, `profession_id`) VALUES 
	(1,1),
	(2,1);
ALTER TABLE `users_professions` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `users_profiles` WRITE;
ALTER TABLE `users_profiles` DISABLE KEYS;
INSERT INTO `users_profiles` (`user_id`, `profile_id`, `url`, `created_at`, `updated_at`) VALUES 
	(2,1,'http://linkedin.com/janos',NULL,NULL),
	(2,2,'http://stackoverflow.com/janos',NULL,NULL),
	(2,3,'http://facebook.com/janos',NULL,NULL);
ALTER TABLE `users_profiles` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `users_project_notification_industries` WRITE;
ALTER TABLE `users_project_notification_industries` DISABLE KEYS;
INSERT INTO `users_project_notification_industries` (`user_project_notification_industry_id`, `user_id`, `industry_id`, `created_at`) VALUES 
	(27,2,1,'2016-11-25 10:53:00');
ALTER TABLE `users_project_notification_industries` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `users_project_notification_professions` WRITE;
ALTER TABLE `users_project_notification_professions` DISABLE KEYS;
INSERT INTO `users_project_notification_professions` (`user_project_notification_profession_id`, `user_id`, `profession_id`, `created_at`) VALUES 
	(72,2,1,'2016-11-25 10:53:00');
ALTER TABLE `users_project_notification_professions` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `users_project_notification_skills` WRITE;
ALTER TABLE `users_project_notification_skills` DISABLE KEYS;
INSERT INTO `users_project_notification_skills` (`user_project_notification_skill_id`, `user_id`, `skill_id`, `created_at`) VALUES 
	(154,2,1,'2016-11-25 10:53:00'),
	(155,2,2,'2016-11-25 10:53:00'),
	(156,2,3,'2016-11-25 10:53:00');
ALTER TABLE `users_project_notification_skills` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `users_ratings` WRITE;
ALTER TABLE `users_ratings` DISABLE KEYS;
ALTER TABLE `users_ratings` ENABLE KEYS;
UNLOCK TABLES;


LOCK TABLES `users_skills` WRITE;
ALTER TABLE `users_skills` DISABLE KEYS;
INSERT INTO `users_skills` (`user_id`, `skill_id`) VALUES 
	(2,1),
	(2,2),
	(2,3);
ALTER TABLE `users_skills` ENABLE KEYS;
UNLOCK TABLES;




SET FOREIGN_KEY_CHECKS = @PREVIOUS_FOREIGN_KEY_CHECKS;


