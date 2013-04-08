-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 13, 2012 at 11:43 PM
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
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `donated` text COLLATE utf8_unicode_ci NOT NULL,
  `pass` text COLLATE utf8_unicode_ci NOT NULL,
  `real_name` text COLLATE utf8_unicode_ci NOT NULL,
  `country` text COLLATE utf8_unicode_ci NOT NULL,
  `gender` text COLLATE utf8_unicode_ci NOT NULL,
  `email` text COLLATE utf8_unicode_ci NOT NULL,
  `paypal_email` text COLLATE utf8_unicode_ci NOT NULL,
  `webpage` text COLLATE utf8_unicode_ci NOT NULL,
  `avatar` text COLLATE utf8_unicode_ci NOT NULL,
  `picture` text COLLATE utf8_unicode_ci NOT NULL,
  `posts` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `show_flash` text COLLATE utf8_unicode_ci NOT NULL,
  `website_fav` text COLLATE utf8_unicode_ci NOT NULL,
  `sentence` text COLLATE utf8_unicode_ci NOT NULL,
  `first_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reporter` text COLLATE utf8_unicode_ci NOT NULL,
  `show_contact_info` text COLLATE utf8_unicode_ci NOT NULL,
  `upload` text COLLATE utf8_unicode_ci NOT NULL,
  `files_uploaded` int(11) NOT NULL DEFAULT '0',
  `files_downloaded` int(11) NOT NULL DEFAULT '0',
  `last_activity` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `birthday` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` int(11) NOT NULL DEFAULT '0',
  `forumposts` int(11) NOT NULL DEFAULT '0',
  `forumreplies` int(11) NOT NULL DEFAULT '0',
  `videowall` text COLLATE utf8_unicode_ci NOT NULL,
  `theme` text COLLATE utf8_unicode_ci NOT NULL,
  `referrals` int(11) NOT NULL DEFAULT '0',
  `comments` int(11) NOT NULL DEFAULT '0',
  `linksadded` int(11) NOT NULL DEFAULT '0',
  `logins` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1000 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`name`, `donated`, `pass`, `real_name`, `country`, `gender`, `email`, `paypal_email`, `webpage`, `avatar`, `picture`, `posts`, `id`, `show_flash`, `website_fav`, `sentence`, `first_login`, `reporter`, `show_contact_info`, `upload`, `files_uploaded`, `files_downloaded`, `last_activity`, `last_login`, `birthday`, `access`, `forumposts`, `forumreplies`, `videowall`, `theme`, `referrals`, `comments`, `linksadded`, `logins`) VALUES
('seth.parson', '', '123', 'Seth', '', '', 'defectiveseth@gmail.com', '', '', '', '', 0, 1, '', '', '', '0000-00-00 00:00:00', '', '', '', 0, 0, '2012-12-13 22:57:50', '2012-12-13 22:57:45', '0000-00-00 00:00:00', 255, 0, 0, '', 'default', 0, 0, 0, 0),
('anonymous', '', '', '', '', '', '', '', '', '', '', 0, 999, '', '', '', '0000-00-00 00:00:00', '', '', '', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0, '', '', 0, 0, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
