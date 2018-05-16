/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 10.1.9-MariaDB-log : Database - base_db
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`base_db` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `base_db`;

/*Table structure for table `tblApiAccess` */

DROP TABLE IF EXISTS `tblApiAccess`;

CREATE TABLE `tblApiAccess` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(40) NOT NULL DEFAULT '',
  `all_access` tinyint(1) NOT NULL DEFAULT '1',
  `controller` varchar(50) NOT NULL DEFAULT '',
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

/*Data for the table `tblApiAccess` */

insert  into `tblApiAccess`(`id`,`key`,`all_access`,`controller`,`date_created`,`date_modified`) values (1,'LOGIN',0,'v1/login','2018-04-08 15:49:52','2018-04-08 17:12:28'),(2,'bd95200a60e47be9736970fd665f6195',0,'v1/config','2018-04-08 15:50:00','2018-04-08 15:54:14'),(23,'4IAC5KEPYCSBO30SHJSW',1,'','2018-04-20 12:29:07','2018-04-20 12:29:07'),(24,'VUG7XBYK0W85ZB8FXNR6',1,'','2018-04-20 14:27:42','2018-04-20 14:27:42'),(26,'INLEDF2N2WW4F867GWHE',1,'','2018-04-23 10:08:03','2018-04-23 10:08:03'),(27,'5FLGAGV3U9RZMM5ER2WB',1,'','2018-04-26 10:50:02','2018-04-26 10:50:02'),(28,'MU3SKDTUUGUSEY1QNR4E',1,'','2018-04-26 12:48:08','2018-04-26 12:48:08'),(29,'DLDFAGF361LHDB287QYS',1,'','2018-05-02 11:47:20','2018-05-02 11:47:20'),(30,'PBXB2EGO47QGPWVB8KU1',1,'','2018-05-08 07:08:34','2018-05-08 07:08:34');

/*Table structure for table `tblApiKey` */

DROP TABLE IF EXISTS `tblApiKey`;

CREATE TABLE `tblApiKey` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_customer_id` int(11) DEFAULT NULL,
  `key` blob NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
  `is_private_key` tinyint(1) NOT NULL DEFAULT '0',
  `ip_addresses` text,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_tblApiKey_tblCustomerBasicInfo` (`fk_customer_id`),
  CONSTRAINT `FK_tblApiKey_tblCustomerBasicInfo` FOREIGN KEY (`fk_customer_id`) REFERENCES `tblCustomerBasicInfo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

/*Data for the table `tblApiKey` */

insert  into `tblApiKey`(`id`,`fk_customer_id`,`key`,`level`,`ignore_limits`,`is_private_key`,`ip_addresses`,`date_created`) values (1,NULL,'LOGIN',0,0,0,NULL,'2018-04-20 14:32:52'),(2,NULL,'bd95200a60e47be9736970fd665f6195',0,0,0,NULL,'2018-04-20 14:33:03'),(23,15,'4IAC5KEPYCSBO30SHJSW',0,0,0,NULL,'2018-04-20 12:29:07'),(24,16,'VUG7XBYK0W85ZB8FXNR6',0,0,0,NULL,'2018-04-20 14:27:42'),(26,18,'INLEDF2N2WW4F867GWHE',0,0,0,NULL,'2018-04-23 10:08:03'),(27,19,'5FLGAGV3U9RZMM5ER2WB',0,0,0,NULL,'2018-04-26 10:50:02'),(28,20,'MU3SKDTUUGUSEY1QNR4E',0,0,0,NULL,'2018-04-26 12:48:08'),(29,21,'DLDFAGF361LHDB287QYS',0,0,0,NULL,'2018-05-02 11:47:20'),(30,22,'PBXB2EGO47QGPWVB8KU1',0,0,0,NULL,'2018-05-08 07:08:34');

/*Table structure for table `tblApiLimit` */

DROP TABLE IF EXISTS `tblApiLimit`;

CREATE TABLE `tblApiLimit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) NOT NULL,
  `count` int(10) NOT NULL,
  `hour_started` int(11) NOT NULL,
  `api_key` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tblApiLimit` */

/*Table structure for table `tblApiLogs` */

DROP TABLE IF EXISTS `tblApiLogs`;

CREATE TABLE `tblApiLogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) NOT NULL,
  `method` varchar(6) NOT NULL,
  `params` text,
  `api_key` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` int(11) NOT NULL,
  `rtime` float DEFAULT NULL,
  `authorized` varchar(1) NOT NULL,
  `response_code` smallint(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tblApiLogs` */

/*Table structure for table `tblProgram` */

DROP TABLE IF EXISTS `tblProgram`;

CREATE TABLE `tblProgram` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `program_code` varchar(30) DEFAULT NULL,
  `program_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `tblProgram` */

insert  into `tblProgram`(`id`,`program_code`,`program_name`) values (1,'EPOINT','EPOINT PAYMENT');

/*Table structure for table `tblSession` */

DROP TABLE IF EXISTS `tblSession`;

CREATE TABLE `tblSession` (
  `id` varchar(60) NOT NULL,
  `fk_userid` int(11) DEFAULT NULL COMMENT 'tblUserInfo',
  `fullname` varchar(50) DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `tblSession` */

insert  into `tblSession`(`id`,`fk_userid`,`fullname`,`ip_address`,`timestamp`,`data`) values ('37jiu11oarvohjnlr3ftcjto1g3g782k',1,'Admin, Super','::1',1526499476,'__ci_last_regenerate|i:1526498613;username|s:10:\"superadmin\";userno|s:10:\"superadmin\";logged_in|b:1;fullname|s:12:\"Admin, Super\";userid|s:1:\"1\";role|s:1:\"1\";random|s:24:\"HCTZoV4Ag12yNmEFPt8sBcUn\";'),('h8502sc1rmng7n85mjq9c262fhv5vgiv',2,'Puyawan, Maner','70.187.112.49',1526497138,'__ci_last_regenerate|i:1526492830;username|s:5:\"maner\";userno|s:5:\"maner\";logged_in|b:1;fullname|s:14:\"Puyawan, Maner\";userid|s:1:\"2\";role|s:1:\"1\";random|s:24:\"xQmRJNaX74AzGFnlTDZUWpoy\";'),('t6r1tguli0rbvggh49gf6eoel8hffnti',NULL,NULL,'::1',1526497962,'__ci_last_regenerate|i:1526497962;');

/*Table structure for table `tblSystemCategory` */

DROP TABLE IF EXISTS `tblSystemCategory`;

CREATE TABLE `tblSystemCategory` (
  `syscat_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `description` text,
  `arranged` int(11) DEFAULT NULL,
  `icon` varchar(150) DEFAULT NULL,
  `cat_label` text COMMENT 'tblLanguageToken',
  PRIMARY KEY (`syscat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `tblSystemCategory` */

insert  into `tblSystemCategory`(`syscat_id`,`description`,`arranged`,`icon`,`cat_label`) values (5,'Manage',2,'fa-gears',NULL),(7,'Global Settings',1,'fa-globe',NULL);

/*Table structure for table `tblSystemMenu` */

DROP TABLE IF EXISTS `tblSystemMenu`;

CREATE TABLE `tblSystemMenu` (
  `menuid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `link` text,
  `title` text COMMENT 'maximum of 18 character',
  `status` enum('SHOW','HIDDEN') CHARACTER SET latin1 DEFAULT NULL,
  `arranged` int(11) DEFAULT NULL,
  `comments` text,
  `icon` text,
  `view_folder` text COMMENT '''~'' is = to ''/''',
  `menu_label` text COMMENT 'tblLanguageToken',
  PRIMARY KEY (`menuid`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Data for the table `tblSystemMenu` */

insert  into `tblSystemMenu`(`menuid`,`link`,`title`,`status`,`arranged`,`comments`,`icon`,`view_folder`,`menu_label`) values (1,'system_settings','System Settings','SHOW',4,NULL,'fa-wrench','settings',NULL),(11,'usermanagement','Users','SHOW',1,NULL,'fa-user','manage',NULL),(12,'rolemanagement','Roles','SHOW',2,NULL,'fa-group','manage',NULL),(13,'menumanagement','Menus','SHOW',3,NULL,'fa-list','manage',NULL);

/*Table structure for table `tblSystemMenuCategory` */

DROP TABLE IF EXISTS `tblSystemMenuCategory`;

CREATE TABLE `tblSystemMenuCategory` (
  `menucat_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fk_syscatid` int(11) unsigned DEFAULT NULL COMMENT 'tblSystemCategory',
  `fk_menuid` int(11) unsigned DEFAULT NULL COMMENT 'tblSystemMenu',
  PRIMARY KEY (`menucat_id`),
  KEY `FK_tblSystemMenuCategory_tblSystemCategory` (`fk_syscatid`),
  KEY `FK_tblSystemMenuCategory_tblSystemMenu` (`fk_menuid`),
  CONSTRAINT `FK_tblSystemMenuCategory_tblSystemCategory` FOREIGN KEY (`fk_syscatid`) REFERENCES `tblSystemCategory` (`syscat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_tblSystemMenuCategory_tblSystemMenu` FOREIGN KEY (`fk_menuid`) REFERENCES `tblSystemMenu` (`menuid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

/*Data for the table `tblSystemMenuCategory` */

insert  into `tblSystemMenuCategory`(`menucat_id`,`fk_syscatid`,`fk_menuid`) values (10,5,11),(11,5,12),(12,5,13),(20,7,1);

/*Table structure for table `tblSystemMenuSub` */

DROP TABLE IF EXISTS `tblSystemMenuSub`;

CREATE TABLE `tblSystemMenuSub` (
  `sub_menuid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fk_menuid` int(11) unsigned DEFAULT NULL COMMENT 'tblSystemMenu',
  `link` text,
  `title` text COMMENT 'maximum of 18 character',
  `status` enum('SHOW','HIDDEN') CHARACTER SET latin1 DEFAULT NULL,
  `arranged` int(11) DEFAULT NULL,
  `comments` text,
  `icon` text,
  `fk_template_id` int(11) unsigned DEFAULT NULL COMMENT 'tblFieldTemplate',
  `view_folder` text COMMENT '''~'' is = to ''/''',
  `menu_label` text COMMENT 'tblLanguageToken',
  PRIMARY KEY (`sub_menuid`),
  KEY `FK_tblSystemMenu_tblFieldTemplate` (`fk_template_id`),
  KEY `FK_tblSystemMenuSub_tblSystemMenu` (`fk_menuid`),
  CONSTRAINT `FK_tblSystemMenuSub_tblSystemMenu` FOREIGN KEY (`fk_menuid`) REFERENCES `tblSystemMenu` (`menuid`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tblSystemMenuSub` */

/*Table structure for table `tblSystemRole` */

DROP TABLE IF EXISTS `tblSystemRole`;

CREATE TABLE `tblSystemRole` (
  `roleid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `description` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `fixed` enum('YES','NO') CHARACTER SET latin1 DEFAULT 'NO',
  `icon` varchar(150) CHARACTER SET latin1 DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`roleid`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

/*Data for the table `tblSystemRole` */

insert  into `tblSystemRole`(`roleid`,`code`,`description`,`fixed`,`icon`,`created_by`,`date_created`) values (1,'superadmin','Super Administrator','YES','fa-key',NULL,NULL);

/*Table structure for table `tblSystemRoleMenu` */

DROP TABLE IF EXISTS `tblSystemRoleMenu`;

CREATE TABLE `tblSystemRoleMenu` (
  `role_menu_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fk_roleid` int(11) unsigned DEFAULT NULL COMMENT 'tblSystemRole',
  `fk_menucat_id` int(11) unsigned DEFAULT NULL COMMENT 'tblMenuCategory',
  `can_read` enum('1','0') CHARACTER SET latin1 NOT NULL DEFAULT '1',
  `can_add` enum('1','0') CHARACTER SET latin1 NOT NULL DEFAULT '1',
  `can_edit` enum('1','0') CHARACTER SET latin1 NOT NULL DEFAULT '1',
  `can_delete` enum('1','0') CHARACTER SET latin1 NOT NULL DEFAULT '1',
  `datecreated` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`role_menu_id`),
  KEY `FK_tblsystem_module_menu_menuid` (`fk_menucat_id`),
  KEY `FK_tblsystem_module_menu_moduleid` (`fk_roleid`),
  CONSTRAINT `FK_tblSystemRoleMenu_tblSystemMenuCategory` FOREIGN KEY (`fk_menucat_id`) REFERENCES `tblSystemMenuCategory` (`menucat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_tblSystemRoleMenu_tblSystemRole` FOREIGN KEY (`fk_roleid`) REFERENCES `tblSystemRole` (`roleid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8;

/*Data for the table `tblSystemRoleMenu` */

insert  into `tblSystemRoleMenu`(`role_menu_id`,`fk_roleid`,`fk_menucat_id`,`can_read`,`can_add`,`can_edit`,`can_delete`,`datecreated`,`created_by`) values (112,1,20,'1','1','1','1','2018-04-25 15:10:15',NULL),(113,1,10,'1','1','1','1','2018-04-25 15:10:15',NULL),(114,1,11,'1','1','1','1','2018-04-25 15:10:15',NULL),(115,1,12,'1','1','1','1','2018-04-25 15:10:15',NULL);

/*Table structure for table `tblSystemSettings` */

DROP TABLE IF EXISTS `tblSystemSettings`;

CREATE TABLE `tblSystemSettings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `code` varchar(30) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `value` varchar(30) DEFAULT NULL,
  `setting_type` enum('OTHERS','NOTIFICATION','SCHEDULE') DEFAULT 'OTHERS',
  `is_active` enum('YES','NO') DEFAULT 'YES',
  `sms_message` blob,
  `email_message` blob,
  `subject` varchar(200) DEFAULT NULL,
  `from` text,
  `to` text,
  `cc` text,
  `bcc` text,
  `updated_by` int(11) DEFAULT NULL COMMENT 'tblUserInfo',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `tblSystemSettings` */

insert  into `tblSystemSettings`(`id`,`name`,`code`,`description`,`value`,`setting_type`,`is_active`,`sms_message`,`email_message`,`subject`,`from`,`to`,`cc`,`bcc`,`updated_by`) values (1,'sample for confirmation','CA','This is to determine for confirmation yes or no','1','OTHERS','YES',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,'sample for notification','CANM','This is the message that user will received via email and text .',NULL,'NOTIFICATION','YES','<p>Welcome to MLOC! Your account has been activated and was approved for {amount} USD credit line.</p>\r\n','<p>Hello, {firstname}<br />\r\n<br />\r\nWelcome to MLOC! Your account has been activated and was approved for ${amount} credit line.<br />\r\n<br />\r\nRegards,<br />\r\nMLOC Team</p>\r\n','New MLOC Registration',NULL,NULL,NULL,NULL,NULL),(10,'sample for cron jobs','FPFL','This is process for scheduling of cron jobs.','19:30:00','SCHEDULE','YES',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `tblUserInfo` */

DROP TABLE IF EXISTS `tblUserInfo`;

CREATE TABLE `tblUserInfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_userid` int(11) unsigned DEFAULT NULL COMMENT 'tblUserInformation',
  `username` text,
  `password` text CHARACTER SET latin1,
  `status` enum('ACTIVE','INACTIVE') CHARACTER SET latin1 DEFAULT 'ACTIVE',
  `ipadd` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbluser_info_userid` (`fk_userid`),
  CONSTRAINT `FK_tblUserInfo_tblUserInformation` FOREIGN KEY (`fk_userid`) REFERENCES `tblUserInformation` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `tblUserInfo` */

insert  into `tblUserInfo`(`id`,`fk_userid`,`username`,`password`,`status`,`ipadd`) values (1,1,'superadmin','21232f297a57a5a743894a0e4a801fc3','ACTIVE','::1');

/*Table structure for table `tblUserInformation` */

DROP TABLE IF EXISTS `tblUserInformation`;

CREATE TABLE `tblUserInformation` (
  `userid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userno` text,
  `lname` text,
  `fname` text,
  `mname` text,
  `bdate` date DEFAULT NULL,
  `bplace` text,
  `gender` enum('MALE','FEMALE') CHARACTER SET latin1 DEFAULT NULL,
  `email` varchar(150) CHARACTER SET latin1 DEFAULT NULL,
  `mobile` varchar(150) CHARACTER SET latin1 DEFAULT NULL,
  `createdby` int(11) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `tblUserInformation` */

insert  into `tblUserInformation`(`userid`,`userno`,`lname`,`fname`,`mname`,`bdate`,`bplace`,`gender`,`email`,`mobile`,`createdby`,`datecreated`) values (1,'superadmin','Admin','Super',NULL,'1985-09-18','Tanza','MALE','rambolista@gmail.com',NULL,NULL,NULL);

/*Table structure for table `tblUserRole` */

DROP TABLE IF EXISTS `tblUserRole`;

CREATE TABLE `tblUserRole` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_userid` int(11) unsigned DEFAULT NULL COMMENT 'tblUserInformation',
  `fk_roleid` int(11) unsigned DEFAULT NULL COMMENT 'tblSystemRole',
  PRIMARY KEY (`id`),
  KEY `FK_tblUserRole_tblSystemRole` (`fk_roleid`),
  KEY `FK_tblUserRole_tblUserInformation` (`fk_userid`),
  CONSTRAINT `FK_tblUserRole_tblSystemRole` FOREIGN KEY (`fk_roleid`) REFERENCES `tblSystemRole` (`roleid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_tblUserRole_tblUserInformation` FOREIGN KEY (`fk_userid`) REFERENCES `tblUserInformation` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

/*Data for the table `tblUserRole` */

insert  into `tblUserRole`(`id`,`fk_userid`,`fk_roleid`) values (1,1,1);

/* Trigger structure for table `tblApiKey` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `trg_after_tblApiKey_insert` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `trg_after_tblApiKey_insert` AFTER INSERT ON `tblApiKey` FOR EACH ROW BEGIN
	INSERT INTO tblApiAccess 
	SET `key` = NEW.key;
    END */$$


DELIMITER ;

/* Trigger structure for table `tblApiKey` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `trg_after_tblApiKey_update` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `trg_after_tblApiKey_update` AFTER UPDATE ON `tblApiKey` FOR EACH ROW BEGIN
	UPDATE tblApiAccess 
	SET `key` = NEW.key WHERE `key`=OLD.key;
    END */$$


DELIMITER ;

/* Trigger structure for table `tblApiKey` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `trg_after_tblApiKey_delete` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `trg_after_tblApiKey_delete` AFTER DELETE ON `tblApiKey` FOR EACH ROW BEGIN
	DELETE FROM tblApiAccess 
	WHERE `key` = OLD.key;
    END */$$


DELIMITER ;

/* Trigger structure for table `tblUserInformation` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `trg_after_tblUserInformation_insert` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `trg_after_tblUserInformation_insert` AFTER INSERT ON `tblUserInformation` FOR EACH ROW BEGIN
	/** INSERT IN tbluser_info*/
	INSERT INTO tblUserInfo (fk_userid,username,`password`) VALUES (NEW.userid,NEW.userno,md5(NEW.lname));
    END */$$


DELIMITER ;

/* Function  structure for function  `fn_date_format` */

/*!50003 DROP FUNCTION IF EXISTS `fn_date_format` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `fn_date_format`(_date DATETIME) RETURNS varchar(255) CHARSET utf8
BEGIN
	DECLARE _return VARCHAR(150);
	SET _return = DATE_FORMAT(_date,'%M %d, %Y %h:%i %p');
	RETURN _return;
END */$$
DELIMITER ;

/* Function  structure for function  `fn_systemrole_get` */

/*!50003 DROP FUNCTION IF EXISTS `fn_systemrole_get` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `fn_systemrole_get`(_role INT) RETURNS varchar(150) CHARSET latin1
BEGIN
    DECLARE utype VARCHAR(150);
    SET utype = (SELECT description FROM tblSystemRole WHERE moduleid=_role);
    RETURN utype;
    END */$$
DELIMITER ;

/* Function  structure for function  `fn_user_fullname_get` */

/*!50003 DROP FUNCTION IF EXISTS `fn_user_fullname_get` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `fn_user_fullname_get`(_userid INT) RETURNS varchar(150) CHARSET latin1
BEGIN
    DECLARE fullname VARCHAR(150);
    SET fullname = (SELECT CONCAT(lname,', ',fname,' ',IFNULL(mname,'')) FROM tblUserInformation WHERE userid=_userid);
    RETURN fullname;
    END */$$
DELIMITER ;

/* Procedure structure for procedure `prc_get_SystemCategory` */

/*!50003 DROP PROCEDURE IF EXISTS  `prc_get_SystemCategory` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `prc_get_SystemCategory`(_where TEXT)
BEGIN
SET @QUERY = CONCAT('SELECT * FROM tblSystemCategory a ',_where);
  PREPARE stmt FROM @QUERY;
  EXECUTE stmt;
  DEALLOCATE PREPARE stmt;
END */$$
DELIMITER ;

/* Procedure structure for procedure `prc_get_SystemMenu` */

/*!50003 DROP PROCEDURE IF EXISTS  `prc_get_SystemMenu` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `prc_get_SystemMenu`(_where TEXT)
BEGIN
SET @QUERY = CONCAT('SELECT a.menuid,a.link,a.title,a.status,a.arranged,a.comments,a.icon,a.fk_template_id FROM `tblSystemMenu` a ',_where);
  PREPARE stmt FROM @QUERY;
  EXECUTE stmt;
  DEALLOCATE PREPARE stmt;
END */$$
DELIMITER ;

/* Procedure structure for procedure `prc_get_SystemRole` */

/*!50003 DROP PROCEDURE IF EXISTS  `prc_get_SystemRole` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `prc_get_SystemRole`(_where TEXT)
BEGIN
SET @QUERY = CONCAT('SELECT a.roleid,a.code,a.description,a.fixed,a.icon,a.created_by,a.date_created FROM tblSystemRole a ',_where);
  PREPARE stmt FROM @QUERY;
  EXECUTE stmt;
  DEALLOCATE PREPARE stmt;
END */$$
DELIMITER ;

/* Procedure structure for procedure `prc_get_SystemRoleMenu` */

/*!50003 DROP PROCEDURE IF EXISTS  `prc_get_SystemRoleMenu` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `prc_get_SystemRoleMenu`(
	IN `_syscatid` VARCHAR(100),
	IN `_role` VARCHAR(100),
	IN `_userid` VARCHAR(100)
)
BEGIN
	#Routine body goes here...
	if IFNULL(_syscatid,'')='' then
		SELECT DISTINCT fk_syscatid,c.description AS title,'#' AS `link`,c.icon,c.cat_label as label FROM tblSystemRoleMenu a
		INNER JOIN tblSystemMenuCategory b ON b.menucat_id=a.fk_menucat_id
		INNER JOIN tblSystemCategory c ON c.syscat_id=b.fk_syscatid
		INNER JOIN tblSystemMenu d ON d.menuid=b.fk_menuid
		WHERE FIND_IN_SET(fk_roleid,_role) AND d.status='SHOW' ORDER BY c.arranged;
	else
		SELECT DISTINCT fk_menucat_id,fk_syscatid,title,d.link,d.icon,can_read,can_add,can_edit,can_delete,d.view_folder,d.menu_label AS label FROM tblSystemRoleMenu a
		INNER JOIN tblSystemMenuCategory b ON b.menucat_id=a.fk_menucat_id
		INNER JOIN tblSystemCategory c ON c.syscat_id=b.fk_syscatid
		INNER JOIN tblSystemMenu d ON d.menuid=b.fk_menuid
		WHERE FIND_IN_SET(fk_roleid,_role) AND fk_syscatid=_syscatid AND d.status='SHOW' ORDER BY d.arranged;
	end if;
END */$$
DELIMITER ;

/* Procedure structure for procedure `prc_get_UserInfo` */

/*!50003 DROP PROCEDURE IF EXISTS  `prc_get_UserInfo` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `prc_get_UserInfo`(
	IN `_where` TEXT
)
BEGIN
SET @QUERY = CONCAT('SELECT a.fk_userid,a.username,a.status,a.ipadd,CONCAT(b.lname,\', \',b.fname,\' \',IFNULL(mname,"")) AS fullname,b.lname,b.fname,b.mname,b.userno,b.gender,b.email,
GROUP_CONCAT(d.description) AS role_assigned
FROM tblUserInfo a 
INNER JOIN tblUserInformation b ON b.userid=a.fk_userid
LEFT JOIN tblUserRole c ON c.fk_userid=a.fk_userid
LEFT JOIN tblSystemRole d ON d.roleid=c.fk_roleid ',_where);
  PREPARE stmt FROM @QUERY;
  EXECUTE stmt;
  DEALLOCATE PREPARE stmt;
	
END */$$
DELIMITER ;

/* Procedure structure for procedure `prc_get_UserInformation` */

/*!50003 DROP PROCEDURE IF EXISTS  `prc_get_UserInformation` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `prc_get_UserInformation`(_where TEXT)
BEGIN
SET @QUERY = CONCAT('SELECT * FROM tblUserInformation  ',_where);
  PREPARE stmt FROM @QUERY;
  EXECUTE stmt;
  DEALLOCATE PREPARE stmt;
	
END */$$
DELIMITER ;

/* Procedure structure for procedure `prc_get_UserInfo_login` */

/*!50003 DROP PROCEDURE IF EXISTS  `prc_get_UserInfo_login` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `prc_get_UserInfo_login`(
	IN `_where` TEXT
)
BEGIN
SET @QUERY = CONCAT('SELECT a.fk_userid,a.username,a.status,a.ipadd,CONCAT(b.lname,\', \',b.fname) AS FULLNAME,b.userno,b.gender FROM tblUserInfo a INNER JOIN tblUserInformation b ON b.userid=a.fk_userid ',_where);
  PREPARE stmt FROM @QUERY;
  EXECUTE stmt;
  DEALLOCATE PREPARE stmt;
END */$$
DELIMITER ;

/* Procedure structure for procedure `prc_get_UserRole` */

/*!50003 DROP PROCEDURE IF EXISTS  `prc_get_UserRole` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `prc_get_UserRole`(_userid VARCHAR(11))
BEGIN
	declare _count int(11);
	SET _count = (SELECT COUNT(fk_roleid) FROM tblUserRole a 
		      INNER JOIN tblSystemRole b ON b.roleid=a.fk_roleid
		      WHERE a.fk_userid=_userid);
	if _count>1 then
		SELECT GROUP_CONCAT(fk_roleid) as fk_roleid,'All' as `code`, 'All' as description, 'fa fa-star' as icon
		FROM tblUserRole a 
		INNER JOIN tblSystemRole b ON b.roleid=a.fk_roleid
		WHERE a.fk_userid=_userid
		UNION
		SELECT fk_roleid,b.code,b.description,b.icon FROM tblUserRole a 
		INNER JOIN tblSystemRole b ON b.roleid=a.fk_roleid
		WHERE a.fk_userid=_userid;
	else
		SELECT fk_roleid,b.code,b.description,b.icon FROM tblUserRole a 
		INNER JOIN tblSystemRole b ON b.roleid=a.fk_roleid
		WHERE a.fk_userid=_userid;
	end if;
	
    END */$$
DELIMITER ;

/* Procedure structure for procedure `prc_set_SystemCategory` */

/*!50003 DROP PROCEDURE IF EXISTS  `prc_set_SystemCategory` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `prc_set_SystemCategory`(
    _action VARCHAR(1),
    _syscat_id VARCHAR(11),
    _description VARCHAR(150),
    _arrange VARCHAR(11),
    _icon VARCHAR(50),
    OUT syscat_id_return int(11)
)
BEGIN
IF _description='' THEN SET _description=NULL; END IF;
IF _arrange='' THEN SET _arrange=NULL; END IF;
IF _icon='' THEN SET _icon=NULL; END IF;
SET syscat_id_return = _syscat_id;
  CASE _action
    WHEN 'S' THEN
	/*Search if syscat_id exists*/
	IF EXISTS (SELECT syscat_id FROM tblSystemCategory WHERE syscat_id = _syscat_id)
	  THEN
	    UPDATE tblSystemCategory 
	    SET description = _description,
		arranged=_arrange,
		icon=_icon
	    WHERE syscat_id = _syscat_id;
	  ELSE 
	    INSERT INTO tblSystemCategory (description,arranged,icon) 
	    VALUES (_description,_arrange,_icon); 
	    SET syscat_id_return = LAST_INSERT_ID();
	END IF;
    WHEN 'D' THEN 
	DELETE FROM tblSystemCategory WHERE syscat_id = _syscat_id;
  END CASE;
END */$$
DELIMITER ;

/* Procedure structure for procedure `prc_set_SystemMenu` */

/*!50003 DROP PROCEDURE IF EXISTS  `prc_set_SystemMenu` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`superadmin`@`%` PROCEDURE `prc_set_SystemMenu`(
    _menuid VARCHAR(11),
    _title VARCHAR(150),
    _icon VARCHAR(50),
    _status VARCHAR(10),
    _arranged VARCHAR(11),
    _assigned_syscat_id VARCHAR(11)
)
BEGIN
IF _title='' THEN SET _title=NULL; END IF;
IF _icon='' THEN SET _icon=NULL; END IF;
IF _status='' THEN SET _status=NULL; END IF;
IF _arranged='' THEN SET _arranged=NULL; END IF;
IF _assigned_syscat_id='' THEN SET _assigned_syscat_id=NULL; END IF;
UPDATE tblSystemMenu 
SET title = _title,
    icon=_icon,
    `status`=_status,
    arranged=_arranged
WHERE menuid = _menuid;
IF EXISTS (SELECT fk_syscatid FROM tblSystemMenuCategory WHERE fk_menuid = _menuid)
then
	UPDATE tblSystemMenuCategory SET fk_syscatid = _assigned_syscat_id WHERE fk_menuid = _menuid;
else
	INSERT INTO tblSystemMenuCategory(fk_syscatid,fk_menuid) VALUES (_assigned_syscat_id,_menuid);
end if;
END */$$
DELIMITER ;

/* Procedure structure for procedure `prc_set_SystemMenuCategory` */

/*!50003 DROP PROCEDURE IF EXISTS  `prc_set_SystemMenuCategory` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `prc_set_SystemMenuCategory`(
    _action VARCHAR(1),
    _syscat_id VARCHAR(255),
    _menuid VARCHAR(255)
    
)
BEGIN
  CASE _action
    WHEN 'S' THEN
	/*Search if syscat_id exists*/
	IF NOT EXISTS (SELECT fk_syscatid FROM tblSystemMenuCategory WHERE fk_syscatid = _syscat_id AND fk_menuid=_menuid)
	  THEN
	    INSERT INTO tblSystemMenuCategory (fk_syscatid,fk_menuid) 
	    VALUES (_syscat_id,_menuid); 
	END IF;
    WHEN 'D' THEN 
	DELETE FROM tblSystemMenuCategory WHERE fk_syscatid = _syscat_id AND NOT FIND_IN_SET(fk_menuid,_menuid);
  END CASE;
END */$$
DELIMITER ;

/* Procedure structure for procedure `prc_set_SystemRoleMenu` */

/*!50003 DROP PROCEDURE IF EXISTS  `prc_set_SystemRoleMenu` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `prc_set_SystemRoleMenu`(
    _action VARCHAR(1),
    _roleid VARCHAR(11),
    _menucat_id VARCHAR(11),
    _canread VARCHAR(1),
    _canadd VARCHAR(1),
    _canedit VARCHAR(1),
    _candelete VARCHAR(1)
)
BEGIN
  CASE _action
    WHEN 'S' THEN
	    INSERT INTO tblSystemRoleMenu (fk_roleid,fk_menucat_id,can_read,can_add,can_edit,can_delete,datecreated)
	    VALUES (_roleid,_menucat_id,_canread,_canadd,_canedit,_candelete,CURRENT_TIMESTAMP); 
    WHEN 'D' THEN 
	DELETE FROM tblSystemRoleMenu WHERE fk_roleid=_roleid;
  END CASE;
END */$$
DELIMITER ;

/* Procedure structure for procedure `prc_set_UserRole` */

/*!50003 DROP PROCEDURE IF EXISTS  `prc_set_UserRole` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `prc_set_UserRole`(
    _action VARCHAR(1),
    _userid VARCHAR(11),
    _roleid VARCHAR(11),
    _type VARCHAR(11)
    
)
BEGIN
  CASE _action
    WHEN 'S' THEN
	/*Search if syscat_id exists*/
	IF NOT EXISTS (SELECT fk_userid FROM tblUserRole WHERE fk_userid = _userid AND fk_roleid=_roleid)
	  THEN
	    INSERT INTO tblUserRole (fk_userid,fk_roleid) 
	    VALUES (_userid,_roleid); 
	END IF;
    WHEN 'D' THEN 
	if _type='role' then
		DELETE FROM tblUserRole WHERE fk_userid = _userid AND NOT FIND_IN_SET(fk_roleid,_roleid);
	else
		DELETE FROM tblUserRole WHERE fk_roleid = _roleid AND NOT FIND_IN_SET(fk_userid,_userid);
	end if;
  END CASE;
END */$$
DELIMITER ;

/* Procedure structure for procedure `prc_set_SystemRole` */

/*!50003 DROP PROCEDURE IF EXISTS  `prc_set_SystemRole` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`superadmin`@`%` PROCEDURE `prc_set_SystemRole`(
    _action VARCHAR(1),
    _roleid VARCHAR(11),
    _code VARCHAR(150),
    _description VARCHAR(100),
    _icon VARCHAR(100),
    _createdby INT(11),
    OUT _roleid_return INT(11)
    
)
BEGIN
IF _code='' THEN SET _code=NULL; END IF;
IF _description='' THEN SET _description=NULL; END IF;
SET _roleid_return = _roleid;
  CASE _action
    WHEN 'S' THEN
	/*Search if _userid exists*/
	IF EXISTS (SELECT roleid FROM tblSystemRole WHERE roleid = _roleid)
	  THEN
	    UPDATE tblSystemRole 
	    SET `code`= _code,
		description=_description,
		icon=_icon
	    WHERE roleid = _roleid;
	    
	  ELSE 
	    INSERT INTO tblSystemRole (`code`,description,icon,created_by,date_created) 
	    VALUES (_code,_description,_icon,_createdby,CURRENT_TIMESTAMP); 
	    
	    SET _roleid_return = LAST_INSERT_ID();
	END IF;
    WHEN 'D' THEN 
	DELETE FROM tblSystemRole WHERE roleid = _roleid ;
  END CASE;
END */$$
DELIMITER ;

/* Procedure structure for procedure `prc_set_UserInformation` */

/*!50003 DROP PROCEDURE IF EXISTS  `prc_set_UserInformation` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`superadmin`@`%` PROCEDURE `prc_set_UserInformation`(
    _action varchar(1),
    _userid VARCHAR(11),
    _userno VARCHAR(150),
    _lname VARCHAR(100),
    _fname VARCHAR(100),
    _mname VARCHAR(100),
    _email VARCHAR(150),
    _password TEXT,
    _createdby int(11),
    OUT _userid_return int(11)
    
)
BEGIN
IF _userid='' THEN SET _userid=NULL; END IF;
IF _userno='' THEN SET _userno=NULL; END IF;
IF _email='' THEN SET _email=NULL; END IF;
SET _userid_return = _userid;
  CASE _action
    WHEN 'S' THEN
	/*Search if _userid exists*/
	IF EXISTS (SELECT userid FROM tblUserInformation WHERE userid = _userid)
	  THEN
	    UPDATE tblUserInformation 
	    SET userno = _userno,
		lname=_lname,
		fname=_fname,
		mname=_mname,
		email=_email
	    WHERE userid = _userid;
	    
	    UPDATE tblUserInfo SET username=_userno WHERE fk_userid = _userid;
	    IF _password<>'' THEN 
	    UPDATE tblUserInfo SET `password`=_password WHERE fk_userid = _userid;
	    END IF;
	    
	  ELSE 
	    INSERT INTO tblUserInformation (userno,lname,fname,mname,email,createdby,datecreated) 
	    VALUES (_userno,_lname,_fname,_mname,_email,_createdby,current_timestamp); 
	    
	    set _userid_return = last_insert_id();
	    set _userid = _userid_return;
	    
	    if _password<>'' then 
	    update tblUserInfo SET `password`=_password WHERE fk_userid = _userid;
	    end if;
	END IF;
    WHEN 'D' THEN 
	UPDATE tblUserInfo SET `status`='INACTIVE' WHERE fk_userid = _userid;
    WHEN 'A' THEN 
	UPDATE tblUserInfo SET `status`='ACTIVE' WHERE fk_userid = _userid;
  END CASE;
END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
