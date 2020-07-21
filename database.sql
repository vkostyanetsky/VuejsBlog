CREATE TABLE IF NOT EXISTS `en_notes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `date` datetime NOT NULL,
  `change_date` datetime NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(120) NOT NULL,
  `content` text NOT NULL,
  `preview_image` varchar(100) NOT NULL,
  `exclude_from_rss` tinyint(1) unsigned NOT NULL,
  `exclude_from_sitemap` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `en_notes_tags` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(12) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `en_notes_tags_bindings` (
  `note_id` int(11) unsigned NOT NULL,
  `tag_id` smallint(5) unsigned NOT NULL,
  KEY `note_id` (`note_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `en_notes_tags_bindings_ibfk_5` FOREIGN KEY (`note_id`) REFERENCES `en_notes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `en_notes_tags_bindings_ibfk_6` FOREIGN KEY (`tag_id`) REFERENCES `en_notes_tags` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `en_pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(200) NOT NULL,
  `title` varchar(50) NOT NULL,
  `date` datetime NOT NULL,
  `change_date` datetime NOT NULL,
  `description` varchar(120) NOT NULL,
  `content` text NOT NULL,
  `preview_image` varchar(100) NOT NULL,
  `exclude_from_rss` tinyint(1) unsigned NOT NULL,
  `exclude_from_sitemap` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`path`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ru_notes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `date` datetime NOT NULL,
  `change_date` datetime NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(120) NOT NULL,
  `content` text NOT NULL,
  `preview_image` varchar(100) NOT NULL,
  `exclude_from_rss` tinyint(1) unsigned NOT NULL,
  `exclude_from_sitemap` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ru_notes_tags` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(12) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ru_notes_tags_bindings` (
  `note_id` int(11) unsigned NOT NULL,
  `tag_id` smallint(5) unsigned NOT NULL,
  KEY `note_id` (`note_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `ru_notes_tags_bindings_ibfk_5` FOREIGN KEY (`note_id`) REFERENCES `ru_notes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ru_notes_tags_bindings_ibfk_6` FOREIGN KEY (`tag_id`) REFERENCES `ru_notes_tags` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ru_pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(200) NOT NULL,
  `title` varchar(50) NOT NULL,
  `date` datetime NOT NULL,
  `change_date` datetime NOT NULL,
  `description` varchar(120) NOT NULL,
  `content` text NOT NULL,
  `preview_image` varchar(100) NOT NULL,
  `exclude_from_rss` tinyint(1) unsigned NOT NULL,
  `exclude_from_sitemap` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`path`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `en_notes_before_insert` BEFORE INSERT ON `en_notes` FOR EACH ROW BEGIN
    
    SET @date = NOW();
    
    SET NEW.`date` = @date;
    SET NEW.`change_date` = @date;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `en_notes_before_update` BEFORE UPDATE ON `en_notes` FOR EACH ROW BEGIN
    SET NEW.`change_date` = NOW();
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `en_pages_before_insert` BEFORE INSERT ON `en_pages` FOR EACH ROW BEGIN
    
    SET @date = NOW();
    
    SET NEW.`date` = @date;
    SET NEW.`change_date` = @date;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `en_pages_before_update` BEFORE UPDATE ON `en_pages` FOR EACH ROW BEGIN
    SET NEW.`change_date` = NOW();
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `ru_notes_before_insert` BEFORE INSERT ON `ru_notes` FOR EACH ROW BEGIN
    
    SET @date = NOW();
    
    SET NEW.`date` = @date;
    SET NEW.`change_date` = @date;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `ru_notes_before_update` BEFORE UPDATE ON `ru_notes` FOR EACH ROW BEGIN
    SET NEW.`change_date` = NOW();
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `ru_pages_before_insert` BEFORE INSERT ON `ru_pages` FOR EACH ROW BEGIN
    
    SET @date = NOW();
    
    SET NEW.`date` = @date;
    SET NEW.`change_date` = @date;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `ru_pages_before_update` BEFORE UPDATE ON `ru_pages` FOR EACH ROW BEGIN
    SET NEW.`change_date` = NOW();
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;