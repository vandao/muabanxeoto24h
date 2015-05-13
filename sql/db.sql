-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_key` char(30) NOT NULL,
  `menu_url` varchar(255) NOT NULL,
  `position` int(11) NOT NULL,
  `is_disabled` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `menu_language`;
CREATE TABLE `menu_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(10) unsigned NOT NULL,
  `menu_id` int(11) NOT NULL,
  `menu_name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`),
  KEY `menu_id` (`menu_id`),
  CONSTRAINT `menu_language_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `system_language` (`id`),
  CONSTRAINT `menu_language_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `permission_resource`;
CREATE TABLE `permission_resource` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `section` varchar(20) NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `controller_name` varchar(50) NOT NULL,
  `action_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `permission_resource_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `permission_resource_group` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `permission_resource_group`;
CREATE TABLE `permission_resource_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `filter_prefix` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `permission_resource_group` (`id`, `name`, `filter_prefix`) VALUES
(1,	'Other',	'other'),
(2,	'Show',	'index,show,list'),
(3,	'New',	'new,add'),
(4,	'Edit',	'edit,update'),
(5,	'Ajax',	'ajax');

DROP TABLE IF EXISTS `permission_staff`;
CREATE TABLE `permission_staff` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(10) unsigned NOT NULL,
  `controller_name` varchar(50) NOT NULL,
  `action_name` varchar(50) NOT NULL,
  `is_allow` tinyint(1) NOT NULL,
  `is_custom` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `staff_id` (`staff_id`),
  CONSTRAINT `permission_staff_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `permission_staff_group`;
CREATE TABLE `permission_staff_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned NOT NULL,
  `controller_name` varchar(50) NOT NULL,
  `action_name` varchar(50) NOT NULL,
  `is_allow` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `permission_staff_group_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `staff_group` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `staff`;
CREATE TABLE `staff` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_group_id` int(10) unsigned NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(60) DEFAULT NULL,
  `is_custom_permission` tinyint(1) NOT NULL,
  `date_updated` datetime DEFAULT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`staff_group_id`),
  CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`staff_group_id`) REFERENCES `staff_group` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `staff` (`id`, `staff_group_id`, `full_name`, `email`, `password`, `is_custom_permission`, `date_updated`, `date_created`) VALUES
(1,	1,	'Admin',	'admin@my.com',	'$2a$08$L.AnVUtxL8A7ggYswFO0AupfH8s73Xfz3BaWlzehFbbfbXMbHAzNG',	0,	NULL,	'2014-06-20 06:30:23'),
(2,	2,	'Support',	'support@my.com',	'$2a$08$khZFySyzhMsbRva1YYRgtux7GWhWH6IghLt5BEx6PiWIp9fS8c/.K',	0,	NULL,	'2014-07-31 03:00:09');

DROP TABLE IF EXISTS `staff_group`;
CREATE TABLE `staff_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_disabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `staff_group` (`id`, `is_disabled`) VALUES
(1,	0),
(2,	0),
(3,	0);

DROP TABLE IF EXISTS `staff_group_language`;
CREATE TABLE `staff_group_language` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int(10) unsigned NOT NULL,
  `staff_group_id` int(10) unsigned NOT NULL,
  `staff_group` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`),
  KEY `staff_group_id` (`staff_group_id`),
  CONSTRAINT `staff_group_language_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `system_language` (`id`),
  CONSTRAINT `staff_group_language_ibfk_2` FOREIGN KEY (`staff_group_id`) REFERENCES `staff_group` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `staff_group_language` (`id`, `language_id`, `staff_group_id`, `staff_group`) VALUES
(1,	1,	1,	'Admin'),
(2,	1,	2,	'Support'),
(3,	1,	3,	'Tester'),
(4,	2,	2,	'Hỗ trợ'),
(5,	2,	3,	'Thử nghiệm'),
(6,	2,	1,	'Quản trị');

DROP TABLE IF EXISTS `static_content`;
CREATE TABLE `static_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `static_content_group_id` int(10) unsigned NOT NULL,
  `static_content_key` char(50) NOT NULL,
  `image_extension` char(4) DEFAULT NULL,
  `image_position` enum('top','bottom','left','right') DEFAULT NULL,
  `position` tinyint(4) NOT NULL,
  `is_disabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `static_content_group_id` (`static_content_group_id`),
  CONSTRAINT `static_content_ibfk_1` FOREIGN KEY (`static_content_group_id`) REFERENCES `static_content_group` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `static_content_group`;
CREATE TABLE `static_content_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `static_content_group_key` char(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `static_content_group_language`;
CREATE TABLE `static_content_group_language` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int(10) unsigned NOT NULL,
  `static_content_group_id` int(10) unsigned NOT NULL,
  `static_content_group` varchar(255) NOT NULL,
  `static_content_group_page_title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`),
  KEY `static_content_group_id` (`static_content_group_id`),
  CONSTRAINT `static_content_group_language_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `system_language` (`id`),
  CONSTRAINT `static_content_group_language_ibfk_2` FOREIGN KEY (`static_content_group_id`) REFERENCES `static_content_group` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `static_content_language`;
CREATE TABLE `static_content_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(10) unsigned NOT NULL,
  `static_content_id` int(10) unsigned NOT NULL,
  `static_content_title` varchar(255) NOT NULL,
  `static_content_content` text NOT NULL,
  `static_content_page_title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`),
  KEY `static_content_id` (`static_content_id`),
  CONSTRAINT `static_content_language_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `system_language` (`id`),
  CONSTRAINT `static_content_language_ibfk_2` FOREIGN KEY (`static_content_id`) REFERENCES `static_content` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `system_config`;
CREATE TABLE `system_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `system_config` (`id`, `key`, `value`) VALUES
(1,	'Backend_Number_Of_Item_Per_Page',	'20'),
(2,	'Backend_Start_Number_Of_Item_Per_Page',	'10'),
(3,	'Backend_End_Number_Of_Item_Per_Page',	'100'),
(4,	'Backend_Step_Number_Of_Item_Per_Page',	'10'),
(5,	'Cookie_Encrypt_Key',	'english-crush'),
(6,	'Sendmail-From',	'{\"from\": \"smart.mozo@kiss-concept.com\", \"from_name\": \"Big Labs\"}'),
(7,	'Sendmail-Reply-To',	'{\"reply_to\": \"\", \"reply_to_name\": \"\"}'),
(8,	'Sendmail-Provider',	'gmail'),
(9,	'Sendmail-Gmail-Account',	'[{\"username\": \"\", \"password\": \"password\"}]'),
(10,	'Sendmail-Gmail-Amazon-SES',	'[{\"username\": \"\", \"password\": \"\"}]'),
(11,	'Sendmail-Gmail-Mandrill',	'[{\"username\": \"\", \"password\": \"\"}]'),
(12,	'Sendmail-Receive-Contact-Us',	'ww'),
(13,	'Production_Facebook_App_Id',	'test'),
(14,	'Production_Facebook_Secret',	'test'),
(15,	'Production_Facebook_Scope',	'email,offline_access,user_birthday,public_profile,publish_actions,publish_stream,user_friends'),
(16,	'Frontend_Number_Of_Item_Per_Page',	'20'),
(17,	'Frontend_Start_Number_Of_Item_Per_Page',	'10'),
(18,	'Frontend_End_Number_Of_Item_Per_Page',	'100'),
(19,	'Frontend_Step_Number_Of_Item_Per_Page',	'10'),
(20,	'Development_Facebook_App_Id',	'test'),
(21,	'Development_Facebook_Secret',	'test'),
(22,	'Development_Facebook_Scope',	'email,user_birthday,public_profile,publish_actions,user_friends');

DROP TABLE IF EXISTS `system_label`;
CREATE TABLE `system_label` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(100) NOT NULL,
  `label_key` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `system_label_language`;
CREATE TABLE `system_label_language` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `system_label_id` int(10) unsigned NOT NULL,
  `language_id` int(10) unsigned NOT NULL,
  `label_value` varchar(100) NOT NULL,
  `label_hint` varchar(255) DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `system_label_id` (`system_label_id`),
  KEY `language_id` (`language_id`),
  CONSTRAINT `system_label_language_ibfk_1` FOREIGN KEY (`system_label_id`) REFERENCES `system_label` (`id`),
  CONSTRAINT `system_label_language_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `system_language` (`id`),
  CONSTRAINT `system_label_language_ibfk_3` FOREIGN KEY (`language_id`) REFERENCES `system_language` (`id`),
  CONSTRAINT `system_label_language_ibfk_4` FOREIGN KEY (`language_id`) REFERENCES `system_language` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `system_language`;
CREATE TABLE `system_language` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `language_code` char(2) NOT NULL,
  `position` tinyint(4) NOT NULL,
  `is_default` tinyint(1) NOT NULL,
  `is_disabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `system_language` (`id`, `language_code`, `position`, `is_default`, `is_disabled`) VALUES
(1,	'en',	1,	1,	0),
(2,	'vi',	2,	0,	0),
(3,	'cn',	3,	0,	0);

DROP TABLE IF EXISTS `system_language_language`;
CREATE TABLE `system_language_language` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int(10) unsigned NOT NULL,
  `system_language_id` int(10) unsigned NOT NULL,
  `language_name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`),
  KEY `system_language_id` (`system_language_id`),
  CONSTRAINT `system_language_language_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `system_language` (`id`),
  CONSTRAINT `system_language_language_ibfk_2` FOREIGN KEY (`system_language_id`) REFERENCES `system_language` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `system_language_language` (`id`, `language_id`, `system_language_id`, `language_name`) VALUES
(1,	1,	1,	'English'),
(2,	1,	2,	'Vietnamese'),
(3,	1,	3,	'Chinese');

DROP TABLE IF EXISTS `template`;
CREATE TABLE `template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template_category_id` int(10) unsigned NOT NULL,
  `template_group_id` int(10) unsigned NOT NULL,
  `template_key` char(30) NOT NULL,
  `template_variable` varchar(255) NOT NULL,
  `is_disabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`template_category_id`),
  KEY `group_id` (`template_group_id`),
  CONSTRAINT `template_ibfk_1` FOREIGN KEY (`template_category_id`) REFERENCES `template_category` (`id`),
  CONSTRAINT `template_ibfk_2` FOREIGN KEY (`template_group_id`) REFERENCES `template_group` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `template_category`;
CREATE TABLE `template_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template_category_key` char(30) NOT NULL,
  `is_disabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `template_category_language`;
CREATE TABLE `template_category_language` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int(10) unsigned NOT NULL,
  `template_category_id` int(10) unsigned NOT NULL,
  `template_category` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`),
  KEY `template_category_id` (`template_category_id`),
  CONSTRAINT `template_category_language_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `system_language` (`id`),
  CONSTRAINT `template_category_language_ibfk_2` FOREIGN KEY (`template_category_id`) REFERENCES `template_category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `template_group`;
CREATE TABLE `template_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template_group_key` char(30) NOT NULL,
  `is_disabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `template_group_language`;
CREATE TABLE `template_group_language` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int(10) unsigned NOT NULL,
  `template_group_id` int(10) unsigned NOT NULL,
  `template_group` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`),
  KEY `template_group_id` (`template_group_id`),
  CONSTRAINT `template_group_language_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `system_language` (`id`),
  CONSTRAINT `template_group_language_ibfk_2` FOREIGN KEY (`template_group_id`) REFERENCES `template_group` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `template_language`;
CREATE TABLE `template_language` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int(10) unsigned NOT NULL,
  `template_id` int(10) unsigned NOT NULL,
  `template_subject` text NOT NULL,
  `template_body` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`),
  KEY `template_id` (`template_id`),
  CONSTRAINT `template_language_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `system_language` (`id`),
  CONSTRAINT `template_language_ibfk_2` FOREIGN KEY (`template_id`) REFERENCES `template` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int(10) unsigned NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `provider` char(20) NOT NULL,
  `avatar` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `gender` char(10) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `ip_number` bigint(20) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `system_language` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `user_social_account`;
CREATE TABLE `user_social_account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `provider` char(20) NOT NULL,
  `identity` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `date_updated` datetime DEFAULT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `provider_identity` (`provider`,`identity`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_social_account_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2015-05-13 05:57:30
