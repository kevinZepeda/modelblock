DROP PROCEDURE IF EXISTS addBoardDefaultField;
CREATE PROCEDURE addBoardDefaultField
(IN con CHAR(20))
BEGIN
	DECLARE CheckExists int;
    SET CheckExists = 0;
    SELECT count(*) INTO CheckExists FROM information_schema.COLUMNS  WHERE TABLE_NAME = 'boards' AND COLUMN_NAME = 'default';
    IF (CheckExists = 0) THEN
       ALTER TABLE boards ADD `default` int(1) NOT NULL DEFAULT '0';
    END IF;
END;
CALL addBoardDefaultField('');
DROP PROCEDURE IF EXISTS addBoardDefaultField;


DROP PROCEDURE IF EXISTS addClientUserLevel;
CREATE PROCEDURE addClientUserLevel
(IN con CHAR(20))
BEGIN
	DECLARE CheckExists int;
    SET CheckExists = 0;
    SELECT count(*) INTO CheckExists  FROM information_schema.COLUMNS  WHERE TABLE_NAME = 'users_extended' AND COLUMN_NAME = 'user_level' AND COLUMN_TYPE LIKE '%CLIENT%';
    IF (CheckExists = 0) THEN
      ALTER TABLE users_extended MODIFY COLUMN `user_level` ENUM('ADMIN','USER','MANAGER','CLIENT') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'USER';
    END IF;
END;
CALL addClientUserLevel('');
DROP PROCEDURE IF EXISTS addClientUserLevel;


DROP PROCEDURE IF EXISTS addCustomerIdToUserExtended;
CREATE PROCEDURE addCustomerIdToUserExtended
(IN con CHAR(20))
BEGIN
	DECLARE CheckExists int;
    SET CheckExists = 0;
    SELECT count(*) INTO CheckExists  FROM information_schema.COLUMNS  WHERE TABLE_NAME = 'users_extended' AND COLUMN_NAME = 'customer_id';
    IF (CheckExists = 0) THEN
      ALTER TABLE users_extended ADD `customer_id` int(11) unsigned DEFAULT NULL;
    END IF;
END;
CALL addCustomerIdToUserExtended('');
DROP PROCEDURE IF EXISTS addCustomerIdToUserExtended;


DROP PROCEDURE IF EXISTS addCustomerRelationToUserExtended;
CREATE PROCEDURE addCustomerRelationToUserExtended
(IN con CHAR(20))
BEGIN
	DECLARE CheckExists int;
    SET CheckExists = 0;
    SELECT count(*) INTO CheckExists FROM information_schema.statistics WHERE table_name = 'users_extended' AND column_name = 'customer_id' AND index_name LIKE 'Customer Relation';
    IF (CheckExists = 0) THEN
      ALTER TABLE users_extended ADD KEY `Customer Relation` (`customer_id`);
      ALTER TABLE users_extended ADD CONSTRAINT `Customer Relation` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
    END IF;
END;
CALL addCustomerRelationToUserExtended('');
DROP PROCEDURE IF EXISTS addCustomerRelationToUserExtended;


DROP PROCEDURE IF EXISTS createAppDataTable;
CREATE PROCEDURE createAppDataTable
(IN con CHAR(20))
BEGIN
	DECLARE CheckExists int;
    SET CheckExists = 0;
    SELECT count(*) INTO CheckExists FROM information_schema.tables  WHERE table_name = 'app_data';
    IF (CheckExists = 0) THEN
      CREATE TABLE `app_data` (
        `config` varchar(255) DEFAULT NULL,
        `value` text
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
      INSERT INTO `app_data` (`config`, `value`)
      VALUES ('APPLICATION_VERSION','2.0.0');
    END IF;
END;
CALL createAppDataTable('');
DROP PROCEDURE IF EXISTS createAppDataTable;

DROP PROCEDURE IF EXISTS addAccountLayoutColorsAndLogo;
CREATE PROCEDURE addAccountLayoutColorsAndLogo
(IN con CHAR(20))
BEGIN
	DECLARE CheckExists int;
    SET CheckExists = 0;
    SELECT count(*) INTO CheckExists  FROM information_schema.COLUMNS  WHERE TABLE_NAME = 'account' AND COLUMN_NAME = 'system_layout_color';
    IF (CheckExists = 0) THEN
      ALTER TABLE account ADD `system_layout_color` varchar(20) NOT NULL DEFAULT '#909090';
      UPDATE account SET system_layout_color = '#909090';
    END IF;
    SET CheckExists = 0;
    SELECT count(*) INTO CheckExists  FROM information_schema.COLUMNS  WHERE TABLE_NAME = 'account' AND COLUMN_NAME = 'system_layout_text_color';
    IF (CheckExists = 0) THEN
      ALTER TABLE account ADD `system_layout_text_color` varchar(20) DEFAULT '#ffffff';
      UPDATE account SET system_layout_text_color = '#ffffff';
    END IF;
    SET CheckExists = 0;
    SELECT count(*) INTO CheckExists  FROM information_schema.COLUMNS  WHERE TABLE_NAME = 'account' AND COLUMN_NAME = 'system_logo';
    IF (CheckExists = 0) THEN
      ALTER TABLE account ADD `system_logo` varchar(266) DEFAULT NULL;
    END IF;
    UPDATE `app_data` SET `value` = '2.1.0' WHERE `config` = 'APPLICATION_VERSION';
END;
CALL addAccountLayoutColorsAndLogo('');
DROP PROCEDURE IF EXISTS addAccountLayoutColorsAndLogo;

DROP PROCEDURE IF EXISTS addNewChainedBoardFeature;
CREATE PROCEDURE addNewChainedBoardFeature
(IN con CHAR(20))
BEGIN
	DECLARE CheckExists int;
    SET CheckExists = 0;
    SELECT count(*) INTO CheckExists FROM app_data  WHERE `value` = '2.1.0';
    IF (CheckExists = 0) THEN

      CREATE TABLE `user_departments` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `account_id` int(11) unsigned NOT NULL,
        `name` varchar(50) NOT NULL DEFAULT '',
        PRIMARY KEY (`id`),
        KEY `account_reference` (`account_id`),
        CONSTRAINT `account_reference` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
      ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

      ALTER TABLE boards MODIFY COLUMN `name` varchar(266) DEFAULT '';
      ALTER TABLE boards MODIFY COLUMN `description` text;
      ALTER TABLE boards ADD `parent_board_id` int(11) DEFAULT NULL;
      ALTER TABLE boards ADD `department_id` int(11) DEFAULT NULL;
      ALTER TABLE users_extended ADD `department_id` int(11) unsigned DEFAULT NULL;
      ALTER TABLE users_extended ADD KEY `Department Relation` (`department_id`);

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

      INSERT INTO `task_positions` (user_id, board_id, size_x, size_y, task_id, account_id)
      SELECT t.user_id, t.board_id, t.size_x, t.size_y, t.id, t.account_id FROM `tasks` as t WHERE t.board_id IS NOT NULL;

      ALTER TABLE tasks DROP COLUMN board_id;
      ALTER TABLE tasks DROP COLUMN user_id;
      ALTER TABLE tasks DROP COLUMN size_x;
      ALTER TABLE tasks DROP COLUMN size_y;

    END IF;

    UPDATE `app_data` SET `value` = '2.3.0' WHERE `config` = 'APPLICATION_VERSION';
END;
CALL addNewChainedBoardFeature('');
DROP PROCEDURE IF EXISTS addNewChainedBoardFeature;

DROP PROCEDURE IF EXISTS addWiki;
CREATE PROCEDURE addWiki
(IN con CHAR(20))
BEGIN
	DECLARE CheckExists int;
    SELECT count(*) INTO CheckExists FROM information_schema.tables  WHERE table_name = 'projects_wiki';
    IF (CheckExists = 0) THEN
       CREATE TABLE `projects_wiki` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `account_id` int(11) NOT NULL,
        `project_id` int(11) NOT NULL,
        `page_title` varchar(244) NOT NULL DEFAULT '',
        `content` longtext NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;
    END IF;
    UPDATE `app_data` SET `value` = '3.1.0' WHERE `config` = 'APPLICATION_VERSION';
END;
CALL addWiki('');
DROP PROCEDURE IF EXISTS addWiki;

DROP PROCEDURE IF EXISTS minorUpgrade320;
CREATE PROCEDURE minorUpgrade320
(IN con CHAR(20))
BEGIN
  UPDATE `app_data` SET `value` = '3.2.0' WHERE `config` = 'APPLICATION_VERSION';
END;
CALL minorUpgrade320('');
DROP PROCEDURE IF EXISTS minorUpgrade320;