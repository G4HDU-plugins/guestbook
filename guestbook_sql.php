CREATE TABLE guestbook (
	`guestbook_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`guestbook_name` VARCHAR(128) NOT NULL DEFAULT '',
	`guestbook_email` VARCHAR(128) NOT NULL DEFAULT '',
	`guestbook_url` VARCHAR(128) NOT NULL DEFAULT '',
	`guestbook_comment` TEXT NOT NULL,
	`guestbook_userid` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`guestbook_udf1` TINYTEXT NOT NULL,
	`guestbook_udf2` TINYTEXT NOT NULL,
	`guestbook_udf3` TINYTEXT NOT NULL,
	`guestbook_udf4` TINYTEXT NOT NULL,
	`guestbook_udf5` TINYTEXT NOT NULL,
	`guestbook_udf6` TINYTEXT NOT NULL,
	`guestbook_date` INT(11) UNSIGNED NOT NULL DEFAULT '0',
	`guestbook_user` VARCHAR(100) NOT NULL DEFAULT '0',
	`guestbook_approved` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	`guestbook_confirmed` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	`guestbook_ip` CHAR(50) NOT NULL DEFAULT '',
	`guestbook_host` VARCHAR(128) NOT NULL DEFAULT '',
	`guestbook_emailconfirmcode` CHAR(64) NULL DEFAULT NULL,
	`guestbook_entered` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`guestbook_updates` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`guestbook_id`)
) ENGINE=MyISAM;


