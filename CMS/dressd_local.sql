/*
SQLyog Ultimate v11.33 (64 bit)
MySQL - 10.1.9-MariaDB : Database - dressd
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`dressd` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `dressd`;

/*Table structure for table `activations` */

DROP TABLE IF EXISTS `activations`;

CREATE TABLE `activations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activations_user_id_index` (`user_id`),
  CONSTRAINT `activations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `activations` */

/*Table structure for table `comments` */

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `post_id` int(10) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `commentflg` int(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `comments` */

insert  into `comments`(`id`,`user_id`,`post_id`,`comment`,`commentflg`,`status`,`created_at`,`updated_at`,`deleted_at`) values (1,'123456',6,'commented',1,1,NULL,NULL,NULL),(2,'123457',6,'commented like',1,1,NULL,NULL,NULL);

/*Table structure for table `friends` */

DROP TABLE IF EXISTS `friends`;

CREATE TABLE `friends` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `friend_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `friends` */

insert  into `friends`(`id`,`user_id`,`friend_id`,`status`,`created_at`,`updated_at`,`deleted_at`) values (37,'123457','123456',1,NULL,NULL,NULL),(38,'123456','123457',1,NULL,NULL,NULL);

/*Table structure for table `invites` */

DROP TABLE IF EXISTS `invites`;

CREATE TABLE `invites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `myfb_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fb_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `invites` */

insert  into `invites`(`id`,`myfb_id`,`fb_id`,`status`,`created_at`,`updated_at`,`deleted_at`) values (36,'123456','12334569',1,NULL,NULL,NULL),(38,'123456','12334563',1,NULL,NULL,NULL);

/*Table structure for table `likes` */

DROP TABLE IF EXISTS `likes`;

CREATE TABLE `likes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `post_id` int(10) NOT NULL DEFAULT '0',
  `like1` tinyint(1) NOT NULL DEFAULT '0',
  `like2` tinyint(1) NOT NULL DEFAULT '0',
  `like3` tinyint(1) NOT NULL DEFAULT '0',
  `like4` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `likes` */

insert  into `likes`(`id`,`user_id`,`post_id`,`like1`,`like2`,`like3`,`like4`,`status`,`created_at`,`updated_at`,`deleted_at`) values (3,'123456',6,0,0,0,1,1,NULL,NULL,NULL),(4,'123457',6,0,0,0,0,1,NULL,NULL,NULL);

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2016_01_15_105324_create_roles_table',1),(4,'2016_01_15_114412_create_role_user_table',1),(5,'2016_01_26_115212_create_permissions_table',1),(6,'2016_01_26_115523_create_permission_role_table',1),(7,'2016_02_09_132439_create_permission_user_table',1),(8,'2017_03_09_082449_create_social_logins_table',1),(9,'2017_03_09_082526_create_activations_table',1),(10,'2017_03_20_213554_create_themes_table',1),(11,'2017_03_21_042918_create_profiles_table',1);

/*Table structure for table `notifications` */

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `post_id` int(10) NOT NULL DEFAULT '0',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `notifications` */

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `permission_role` */

DROP TABLE IF EXISTS `permission_role`;

CREATE TABLE `permission_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permission_role_permission_id_index` (`permission_id`),
  KEY `permission_role_role_id_index` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `permission_role` */

insert  into `permission_role`(`id`,`permission_id`,`role_id`,`created_at`,`updated_at`) values (1,1,1,'2017-09-06 09:53:25','2017-09-06 09:53:25'),(2,2,1,'2017-09-06 09:53:25','2017-09-06 09:53:25'),(3,3,1,'2017-09-06 09:53:25','2017-09-06 09:53:25'),(4,4,1,'2017-09-06 09:53:25','2017-09-06 09:53:25');

/*Table structure for table `permission_user` */

DROP TABLE IF EXISTS `permission_user`;

CREATE TABLE `permission_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permission_user_permission_id_index` (`permission_id`),
  KEY `permission_user_user_id_index` (`user_id`),
  CONSTRAINT `permission_user_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `permission_user` */

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `permissions` */

insert  into `permissions`(`id`,`name`,`slug`,`description`,`model`,`created_at`,`updated_at`) values (1,'Can View Users','view.users','Can view users','Permission','2017-09-06 09:53:25','2017-09-06 09:53:25'),(2,'Can Create Users','create.users','Can create new users','Permission','2017-09-06 09:53:25','2017-09-06 09:53:25'),(3,'Can Edit Users','edit.users','Can edit users','Permission','2017-09-06 09:53:25','2017-09-06 09:53:25'),(4,'Can Delete Users','delete.users','Can delete users','Permission','2017-09-06 09:53:25','2017-09-06 09:53:25');

/*Table structure for table `posts` */

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo2` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `style_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdtime` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiredtime` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiredhour` int(10) NOT NULL,
  `like1` int(10) NOT NULL DEFAULT '0',
  `like2` int(10) NOT NULL DEFAULT '0',
  `like3` int(10) NOT NULL DEFAULT '0',
  `like4` int(10) NOT NULL DEFAULT '0',
  `comment` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `posts` */

insert  into `posts`(`id`,`user_id`,`subject`,`photo`,`photo2`,`style_id`,`location`,`createdtime`,`expiredtime`,`expiredhour`,`like1`,`like2`,`like3`,`like4`,`comment`,`status`,`created_at`,`updated_at`,`deleted_at`) values (5,'123456','subject','post_1504866208.jpg','post2_1504866208.jpg','1','','111111111','111222222',4,0,0,0,0,0,1,NULL,NULL,NULL),(6,'123456','subject','post_1504866233.jpg','','2','','111111111','111222222',4,0,0,0,1,2,1,NULL,NULL,NULL);

/*Table structure for table `profiles` */

DROP TABLE IF EXISTS `profiles`;

CREATE TABLE `profiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mynotification` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `friendnotification` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `profiles` */

insert  into `profiles`(`id`,`user_id`,`mynotification`,`friendnotification`,`created_at`,`updated_at`) values (1,'123456',0,0,'2017-09-06 09:53:25','2017-09-06 09:53:25'),(2,'123457',1,1,'2017-09-06 09:53:25','2017-09-06 09:53:25'),(4,'123458',1,1,NULL,NULL),(5,'123459',1,1,NULL,NULL),(6,'12334444',1,1,NULL,NULL),(7,'204713763361289',1,1,NULL,NULL);

/*Table structure for table `role_user` */

DROP TABLE IF EXISTS `role_user`;

CREATE TABLE `role_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_user_role_id_index` (`role_id`),
  KEY `role_user_user_id_index` (`user_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `role_user` */

insert  into `role_user`(`id`,`role_id`,`user_id`,`created_at`,`updated_at`) values (1,1,1,'2017-09-06 09:53:25','2017-09-06 09:53:25'),(2,2,2,'2017-09-06 09:53:25','2017-09-06 09:53:25');

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `roles` */

insert  into `roles`(`id`,`name`,`slug`,`description`,`level`,`created_at`,`updated_at`) values (1,'Admin','admin','Admin Role',5,'2017-09-06 09:53:25','2017-09-06 09:53:25'),(2,'User','user','User Role',1,'2017-09-06 09:53:25','2017-09-06 09:53:25'),(3,'Unverified','unverified','Unverified Role',0,'2017-09-06 09:53:25','2017-09-06 09:53:25');

/*Table structure for table `saves` */

DROP TABLE IF EXISTS `saves`;

CREATE TABLE `saves` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `post_id` int(10) NOT NULL DEFAULT '0',
  `savedflg` int(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `saves` */

insert  into `saves`(`id`,`user_id`,`post_id`,`savedflg`,`status`,`created_at`,`updated_at`,`deleted_at`) values (1,'123457',6,1,1,NULL,NULL,NULL);

/*Table structure for table `social_logins` */

DROP TABLE IF EXISTS `social_logins`;

CREATE TABLE `social_logins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `provider` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `social_id` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `social_logins_user_id_index` (`user_id`),
  CONSTRAINT `social_logins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `social_logins` */

/*Table structure for table `styles` */

DROP TABLE IF EXISTS `styles`;

CREATE TABLE `styles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `styles` */

insert  into `styles`(`id`,`name`,`status`,`created_at`,`updated_at`,`deleted_at`) values (1,'Professional',1,NULL,NULL,NULL),(2,'Sporty',1,NULL,NULL,NULL),(3,'Casual',1,NULL,NULL,NULL),(4,'Preppy',1,NULL,NULL,NULL),(5,'Edgy',1,NULL,NULL,NULL),(6,'Trendy',1,NULL,NULL,NULL),(7,'Hipster',1,NULL,NULL,NULL);

/*Table structure for table `themes` */

DROP TABLE IF EXISTS `themes`;

CREATE TABLE `themes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `taggable_id` int(10) unsigned NOT NULL,
  `taggable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `themes_name_unique` (`name`),
  UNIQUE KEY `themes_link_unique` (`link`),
  KEY `themes_taggable_id_taggable_type_index` (`taggable_id`,`taggable_type`),
  KEY `themes_id_index` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `themes` */

insert  into `themes`(`id`,`name`,`link`,`notes`,`status`,`taggable_id`,`taggable_type`,`created_at`,`updated_at`,`deleted_at`) values (1,'Default','null',NULL,1,1,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(2,'Darkly','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/darkly/bootstrap.min.css',NULL,1,2,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(3,'Cyborg','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cyborg/bootstrap.min.css',NULL,1,3,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(4,'Cosmo','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cosmo/bootstrap.min.css',NULL,1,4,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(5,'Cerulean','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/cerulean/bootstrap.min.css',NULL,1,5,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(6,'Flatly','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/flatly/bootstrap.min.css',NULL,1,6,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(7,'Journal','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/journal/bootstrap.min.css',NULL,1,7,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(8,'Lumen','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/lumen/bootstrap.min.css',NULL,1,8,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(9,'Paper','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/paper/bootstrap.min.css',NULL,1,9,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(10,'Readable','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/readable/bootstrap.min.css',NULL,1,10,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(11,'Sandstone','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/sandstone/bootstrap.min.css',NULL,1,11,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(12,'Simplex','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/simplex/bootstrap.min.css',NULL,1,12,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(13,'Slate','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/slate/bootstrap.min.css',NULL,1,13,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(14,'Spacelab','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/spacelab/bootstrap.min.css',NULL,1,14,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(15,'Superhero','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/superhero/bootstrap.min.css',NULL,1,15,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(16,'United','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/united/bootstrap.min.css',NULL,1,16,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(17,'Yeti','https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/yeti/bootstrap.min.css',NULL,1,17,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(18,'Bootstrap 4.0.0 Alpha','https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css',NULL,1,18,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(19,'Materialize','https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css',NULL,1,19,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(20,'Bootstrap Material Design 0.3.0','https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.3.0/css/material-fullpalette.min.css',NULL,1,20,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(21,'Bootstrap Material Design 0.5.10','https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/css/bootstrap-material-design.min.css',NULL,1,21,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(22,'Bootstrap Material Design 4.0.0','https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/4.0.0/bootstrap-material-design.min.css',NULL,1,22,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(23,'Bootstrap Material Design 4.0.2','https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/4.0.2/bootstrap-material-design.min.css',NULL,1,23,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(24,'mdbootstrap','https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.3.1/css/mdb.min.css',NULL,1,24,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(25,'bootflat','https://cdnjs.cloudflare.com/ajax/libs/bootflat/2.0.4/css/bootflat.min.css',NULL,1,25,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(26,'flat-ui','https://cdnjs.cloudflare.com/ajax/libs/flat-ui/2.3.0/css/flat-ui.min.css',NULL,1,26,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL),(27,'m8tro-bootstrap','https://cdnjs.cloudflare.com/ajax/libs/m8tro-bootstrap/3.3.7/m8tro.min.css',NULL,1,27,'theme','2017-09-06 09:53:25','2017-09-06 09:53:25',NULL);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `signup_ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signup_confirmation_ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signup_sm_ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_name_unique` (`name`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`first_name`,`last_name`,`email`,`password`,`remember_token`,`activated`,`token`,`avatar`,`signup_ip_address`,`signup_confirmation_ip_address`,`signup_sm_ip_address`,`admin_ip_address`,`created_at`,`updated_at`) values (1,'Admin','Admin','1','admin@admin.com','$2y$10$xUxFUmavj1Eu9.EThVGwkeLBC17Y2vmq4aEePtye98QXXw25KXWwK','pvXELNeST6wIxamNdXVfGCt2iVk4v8AKtTFxm4Mgj0BxKNtW0p3I2cIuBUsg',1,'fOaryVsMIFJbjd7u0EentxmQVB6PryRPxl1nsT3YtSXvV2Vy5lNsxlVRTdJWXeRG','',NULL,'23.146.108.90',NULL,'244.54.214.34','2017-09-06 09:53:25','2017-09-06 09:53:25'),(2,'hannah48','Shawn','Schinner','user@user.com','$2y$10$92ULr6vJi7hx1kTbyIdue.sMybyrJiviIJzqoGKjnlyLdgZT1bmUK','8AZ7QgF1XJ6kvkwtY2Z1XySk3lGu32dQbDrdnuQBr7qyNH44cnFggUqve2z4',1,'QeMG3XNniGjHch2mgIEkyBFMT5zDBiuHAaM04XoQExtL6jouSHPaCSLOy6oOfQGs','','6.248.87.162','138.123.230.70',NULL,NULL,'2017-09-06 09:53:25','2017-09-06 09:53:25'),(3,'123456','blue','test','future.syg1118@gmail.com','QeMG3XNniGjHch2mgIEkyBFMT5zDBiuHAaM04XoQExtL6jouSHPaCSLOy6oOfQGs',NULL,1,'QeMG3XNniGjHch2mgIEkyBFMT5zDBiuHAaM04XoQExtL6jouSHPaCSLOy6oOfQGs','https://i.pinimg.com/736x/f9/4c/90/f94c90a4220e63a16b2fd4232feb0425--chinese-clothing-asian-beauty.jpg',NULL,NULL,NULL,NULL,NULL,NULL),(4,'123457','kan','test','kan@gmail.com','QeMG3XNniGjHch2mgIEkyBFMT5zDBiuHAaM04XoQExtL6jouSHPaCSLOy6oOfQGd',NULL,1,'QeMG3XNniGjHch2mgIEkyBFMT5zDBiuHAaM04XoQExtL6jouSHPaCSLOy6oOfQGd','https://i.pinimg.com/236x/54/f9/be/54f9be64efd60b2e9ab6b9f13dcd6de7.jpg',NULL,NULL,NULL,NULL,NULL,NULL),(5,'123458','cat','test','cat@gmail.com','QeMG3XNniGjHch2mgIEkyBFMT5zDBiuHAaM04XoQExtL6jouSHPaCSLOy6oOfQGd',NULL,1,'QeMG3XNniGjHch2mgIEkyBFMT5zDBiuHAaM04XoQExtL6jouSHPaCSLOy6oOfQGd','https://i.pinimg.com/236x/54/f9/be/54f9be64efd60b2e9ab6b9f13dcd6de7.jpg',NULL,NULL,NULL,NULL,NULL,NULL),(6,'123459','jery','test','jery@gmail.com','QeMG3XNniGjHch2mgIEkyBFMT5zDBiuHAaM04XoQExtL6jouSHPaCSLOy6oOfQGd',NULL,1,'QeMG3XNniGjHch2mgIEkyBFMT5zDBiuHAaM04XoQExtL6jouSHPaCSLOy6oOfQGd','https://i.pinimg.com/236x/54/f9/be/54f9be64efd60b2e9ab6b9f13dcd6de7.jpg',NULL,NULL,NULL,NULL,NULL,NULL),(7,'12334444','sdfs','dfsdf','sdffsdf','sdfsdfsdfsdfsdf',NULL,1,'sdfsdfsdfsdfsdf','sdfsdfsdf',NULL,NULL,NULL,NULL,NULL,NULL),(8,'204713763361289','Simon','Ronaldo','blackcat88108@gmail.com','EAAD1wtB50e4BAFmShDCqHIVVAwXMq45ZCvaA44fQ6WNRByogm8J43vS5sWPlwpzBSagErEcA4RiKZAF6nwz6sis99gP58SeHOSu0lyp4Cbg3ZCqCo0vUHqcE6fQZCZAFVZBGXZAHODeR8BKOlZBDN05o7FjeKmOeByMjjPZBxIadJQiUFQWVpcAD0CZAZBpP8LMZBmqPYjt87IX1Bgr9QpZBPTE0T1GedyAFo2dTJO2js4pePROSWvQrA7GiNOBc9mWDuVFgZD',NULL,1,'EAAD1wtB50e4BAFmShDCqHIVVAwXMq45ZCvaA44fQ6WNRByogm8J43vS5sWPlwpzBSagErEcA4RiKZAF6nwz6sis99gP58SeHOSu0lyp4Cbg3ZCqCo0vUHqcE6fQZCZAFVZBGXZAHODeR8BKOlZBDN05o7FjeKmOeByMjjPZBxIadJQiUFQWVpcAD0CZAZBpP8LMZBmqPYjt87IX1Bgr9QpZBPTE0T1GedyAFo2dTJO2js4pePROSWvQrA7GiNOBc9mWDuVFgZD','https://graph.facebook.com/204713763361289/picture?type=large',NULL,NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `userstyles` */

DROP TABLE IF EXISTS `userstyles`;

CREATE TABLE `userstyles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `style_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profiles_theme_id_foreign` (`style_id`),
  KEY `profiles_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `userstyles` */

insert  into `userstyles`(`id`,`user_id`,`style_id`,`created_at`,`updated_at`) values (3,'123456',1,NULL,NULL),(4,'123456',2,NULL,NULL),(5,'123456',3,NULL,NULL),(41,'204713763361289',1,NULL,NULL),(42,'204713763361289',2,NULL,NULL),(43,'204713763361289',3,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
