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
-- Table structure for table `site_vars`
--

CREATE TABLE IF NOT EXISTS `site_vars` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `site_vars`
--

INSERT INTO `site_vars` (`name`, `value`) VALUES
('path', '/media/TB/xampp/htdocs'),
('url', 'http://galaxian.3dnetlabs.info'),
('name', 'Galaxian'),
('slogan', 'A RFSCMS Website'),
('singletablewidth', '910'),
('doubletablewidth', '435'),
('theme_dropdown', 'false'),
('top_menu_location', 'top'),
('show_link_friends', 'true'),
('show_top_referrers', 'true'),
('show_link_bin', 'true'),
('show_online_users', 'true'),
('copyright', 'Created with RFS CMS Copyright (c) 2012 Seth T. Parson'),
('show_rss_news', 'true'),
('default_theme', 'default'),
('force_theme', 'on'),
('forced_theme', 'black'),
('SESSION_ID', 'galaxian'),
('nav_font', 'ltromatic.ttf'),
('error_log', 'errors.log'),
('nav_img', '0');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
