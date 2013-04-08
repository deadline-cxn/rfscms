-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 13, 2012 at 11:47 PM
-- Server version: 5.5.28-0ubuntu0.12.04.2
-- PHP Version: 5.3.10-1ubuntu3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `galaxian`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu_top`
--

CREATE TABLE IF NOT EXISTS `menu_top` (
  `name` text COLLATE utf8_unicode_ci,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` text COLLATE utf8_unicode_ci NOT NULL,
  `sort_order` int(11) NOT NULL,
  `access` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

--
-- Dumping data for table `menu_top`
--

INSERT INTO `menu_top` (`name`, `id`, `link`, `sort_order`, `access`) VALUES
('Admin', 9, 'admin/adm.php', 434, 255),
('News', 10, 'index.php', 124, 0),
('Videos', 11, 'videos.php', 195, 0),
('Forum', 12, 'forum.php?forum_list=yes', 199, 0),
('Pictures', 13, 'pics.php', 189, 0),
('Profile', 14, 'profile.php', 432, 0),
('Wiki', 15, 'rfswiki.php', 140, 0),
('Comics', 16, 'comics.php', 155, 0),
('Meme Generator', 17, 'pics.php?action=showmemes', 192, 0),
('Video Wall', 18, 'v.php', 196, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
