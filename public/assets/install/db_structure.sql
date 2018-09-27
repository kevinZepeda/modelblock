# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.42)
# Database: kicktime
# Generation Time: 2015-10-09 23:33:37 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table account
# ------------------------------------------------------------

DROP TABLE IF EXISTS `account`;

CREATE TABLE `account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `active` int(1) NOT NULL DEFAULT '1',
  `system_label` varchar(50) NOT NULL DEFAULT '<b>Team</b>Deck',
  `company_name` varchar(266) DEFAULT NULL,
  `timezone` varchar(100) DEFAULT 'UTC',
  `currency` varchar(3) DEFAULT 'EUR',
  `address` varchar(266) DEFAULT NULL,
  `country` varchar(3) DEFAULT NULL,
  `postal_code` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `vat` varchar(50) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `invoice_logo` varchar(266) DEFAULT NULL,
  `invoice_prefix` varchar(100) DEFAULT NULL,
  `invoice_padding` int(11) DEFAULT NULL,
  `invoice_note` longtext,
  `invoice_legal_note` longtext,
  `invoice_language` varchar(3) NOT NULL DEFAULT 'ENG',
  `invoice_layout_color` varchar(20) DEFAULT NULL,
  `system_layout_color` varchar(20) NOT NULL DEFAULT '#909090',
  `system_layout_text_color` varchar(20) DEFAULT '#ffffff',
  `system_logo` varchar(266) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table app_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_data`;

CREATE TABLE `app_data` (
  `config` varchar(255) DEFAULT NULL,
  `value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table board_templates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `board_templates`;

CREATE TABLE `board_templates` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) unsigned NOT NULL,
  `name` varchar(266) NOT NULL DEFAULT '',
  `columns` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Account Reference` (`account_id`),
  CONSTRAINT `Account Reference` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table boards
# ------------------------------------------------------------

DROP TABLE IF EXISTS `boards`;

CREATE TABLE `boards` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `name` varchar(266) DEFAULT '',
  `description` text,
  `columns` text NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `public` int(1) NOT NULL DEFAULT '0',
  `public_hash` varchar(266) NOT NULL DEFAULT '',
  `lock` int(1) NOT NULL DEFAULT '0',
  `default` int(1) NOT NULL DEFAULT '0',
  `parent_board_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table customers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `company_number` varchar(100) DEFAULT NULL,
  `country` varchar(3) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `postal_code` varchar(100) DEFAULT NULL,
  `contact_full_name` varchar(200) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `b_customer_name` varchar(100) DEFAULT NULL,
  `b_vat` varchar(100) DEFAULT NULL,
  `b_country` varchar(3) DEFAULT NULL,
  `b_city` varchar(100) DEFAULT NULL,
  `b_address` varchar(200) DEFAULT NULL,
  `b_postal_code` varchar(100) DEFAULT NULL,
  `b_contact_full_name` varchar(200) DEFAULT NULL,
  `b_phone_number` varchar(50) DEFAULT NULL,
  `b_email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table invoice_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `invoice_category`;

CREATE TABLE `invoice_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `name` varchar(244) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table invoice_items
# ------------------------------------------------------------

DROP TABLE IF EXISTS `invoice_items`;

CREATE TABLE `invoice_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `invoice_id` int(11) unsigned NOT NULL,
  `item_type` enum('ITEM','TAX','PRE-TAX') NOT NULL DEFAULT 'ITEM',
  `quantity` int(11) NOT NULL DEFAULT '1',
  `label_1` text NOT NULL,
  `label_2` text NOT NULL,
  `factor` varchar(100) NOT NULL DEFAULT '0',
  `item_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `Invoice Id` (`invoice_id`),
  CONSTRAINT `Invoice Id` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table invoices
# ------------------------------------------------------------

DROP TABLE IF EXISTS `invoices`;

CREATE TABLE `invoices` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `customer_id` int(11) unsigned DEFAULT NULL,
  `category_id` int(11) unsigned DEFAULT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'EUR',
  `billed_from` longtext,
  `billed_to` longtext,
  `type` enum('INVOICE','QUOTE','DRAFT','RECURRING','EXPENSE') NOT NULL DEFAULT 'DRAFT',
  `invoice_number` varchar(100) DEFAULT NULL,
  `status` enum('UNPAID','PAID','STORNO','FRAUD','CREDIT','DEBT','COLLECT') NOT NULL DEFAULT 'UNPAID',
  `invoice_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `r_due_period` enum('MONTHLY','DAILY','WEEKLY','YEARLY') DEFAULT NULL,
  `r_ready` int(1) NOT NULL DEFAULT '0',
  `r_due_days` int(11) DEFAULT NULL,
  `r_next_date` date DEFAULT NULL,
  `r_end_date` date DEFAULT NULL,
  `notes` longtext,
  `legal_notes` longtext,
  `language` varchar(3) NOT NULL DEFAULT 'ENG',
  `invoice_logo` varchar(266) DEFAULT NULL,
  `invoice_title` varchar(266) DEFAULT NULL,
  `invoice_subtotals` double(10,2) NOT NULL,
  `invoice_pre_tax` decimal(10,2) NOT NULL,
  `invoice_tax` decimal(10,2) NOT NULL,
  `archived` int(1) NOT NULL DEFAULT '0',
  `layout_color` varchar(20) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `customer_relation` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table module_params_map
# ------------------------------------------------------------

DROP TABLE IF EXISTS `module_params_map`;

CREATE TABLE `module_params_map` (
  `module` varchar(50) NOT NULL DEFAULT '',
  `param` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


LOCK TABLES `module_params_map` WRITE;
/*!40000 ALTER TABLE `module_params_map` DISABLE KEYS */;

INSERT INTO `module_params_map` (`module`, `param`)
VALUES
  ('NOTIFICATIONS','NOTE_SUBJECT_UPDATE'),
  ('NOTIFICATIONS','NOTE_DESCIPRITON_UPDATE'),
  ('NOTIFICATIONS','NOTE_TYPE_UPDATE'),
  ('NOTIFICATIONS','NOTE_PROJECT_UPDATE'),
  ('NOTIFICATIONS','NOTE_ESTIMATE_UPDATE'),
  ('NOTIFICATIONS','NOTE_OWNER_UPDATE'),
  ('NOTIFICATIONS','NOTE_COMMENT_UPDATE'),
  ('NOTIFICATIONS','NOTE_PRIORITY_UPDATE'),
  ('NOTIFICATIONS','NOTE_STATE_UPDATE'),
  ('NOTIFICATIONS','NOTE_MANAGER_UPDATE');

/*!40000 ALTER TABLE `module_params_map` ENABLE KEYS */;
UNLOCK TABLES;

# Dump of table password_resets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table project_requirements
# ------------------------------------------------------------

DROP TABLE IF EXISTS `project_requirements`;

CREATE TABLE `project_requirements` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `subject` longtext NOT NULL,
  `details` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table projects
# ------------------------------------------------------------

DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `customer_id` int(11) unsigned DEFAULT NULL,
  `project_name` varchar(200) NOT NULL DEFAULT '',
  `project_description` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table questionnarie_templates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `questionnarie_templates`;

CREATE TABLE `questionnarie_templates` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `template` longtext,
  `public` int(11) NOT NULL DEFAULT '0',
  `type` enum('TEMPLATE','QA') NOT NULL DEFAULT 'TEMPLATE',
  `target` enum('CUSTOMER','PROJECT') DEFAULT NULL,
  `status` enum('PENDING','SUBMITTED','REVIEWED') DEFAULT NULL,
  `submission_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `reviewer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table sharepoint
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sharepoint`;

CREATE TABLE `sharepoint` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `file_hash` varchar(244) NOT NULL DEFAULT '',
  `name` varchar(244) NOT NULL DEFAULT '',
  `tags` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table task_comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `task_comments`;

CREATE TABLE `task_comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `task_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `comment` longtext NOT NULL,
  `comment_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table task_history
# ------------------------------------------------------------

DROP TABLE IF EXISTS `task_history`;

CREATE TABLE `task_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `change_action` enum('OWNER','PRIORITY','TYPE','DESCRIPTION',' SUBJECT','BOARD','PROJECT','ESTIMATE') DEFAULT NULL,
  `new_value` varchar(50) DEFAULT NULL,
  `change_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table task_positions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `task_positions`;

CREATE TABLE `task_positions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `board_id` int(11) DEFAULT NULL,
  `task_id` int(11) unsigned DEFAULT NULL,
  `size_x` int(11) DEFAULT NULL,
  `size_y` int(11) DEFAULT NULL,
  `visible` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `task_reference` (`task_id`),
  CONSTRAINT `task_reference` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table tasks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tasks`;

CREATE TABLE `tasks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `manager_id` int(11) unsigned DEFAULT NULL,
  `project_id` int(11) unsigned DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `subject` text,
  `description` longtext,
  `estimate` varchar(266) DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT '900',
  PRIMARY KEY (`id`),
  KEY `manage_relation` (`manager_id`),
  KEY `project_relation` (`project_id`),
  CONSTRAINT `manage_relation` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `project_relation` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table time_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `time_users`;

CREATE TABLE `time_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `value` varchar(100) NOT NULL DEFAULT '',
  `comment` text,
  `time` time NOT NULL,
  `date` datetime NOT NULL,
  `billable` int(1) NOT NULL DEFAULT '0',
  `approved` int(1) NOT NULL DEFAULT '0',
  `comment_request` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table user_departments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_departments`;

CREATE TABLE `user_departments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) unsigned NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `account_reference` (`account_id`),
  CONSTRAINT `account_reference` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `password` varchar(60) COLLATE utf8_unicode_ci DEFAULT '',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table users_config
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users_config`;

CREATE TABLE `users_config` (
  `user_id` int(11) unsigned NOT NULL,
  `config` varchar(100) NOT NULL DEFAULT '',
  `value` longtext NOT NULL,
  KEY `User Index` (`user_id`),
  CONSTRAINT `User Config Relation` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table users_extended
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users_extended`;

CREATE TABLE `users_extended` (
  `user_id` int(11) unsigned DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `active` int(1) NOT NULL DEFAULT '1',
  `user_level` enum('ADMIN','USER','MANAGER','CLIENT') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'USER',
  `first_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `language` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ENG',
  `avatar` varchar(266) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stopwatch_start` datetime DEFAULT NULL,
  `customer_id` int(11) unsigned DEFAULT NULL,
  `department_id` int(11) unsigned DEFAULT NULL,
  UNIQUE KEY `user_id` (`user_id`),
  KEY `Customer Relation` (`customer_id`),
  KEY `Department Relation` (`department_id`),
  CONSTRAINT `Customer Relation` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `User Relation` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

# Dump of table projects_wiki
# ------------------------------------------------------------

DROP TABLE IF EXISTS `projects_wiki`;

CREATE TABLE `projects_wiki` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `page_title` varchar(244) NOT NULL DEFAULT '',
  `content` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;

INSERT INTO `app_data` (`config`, `value`)
VALUES
  ('APPLICATION_VERSION','3.1.0');

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
