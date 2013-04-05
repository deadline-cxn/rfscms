-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 13, 2012 at 11:49 PM
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
-- Table structure for table `admin_menu`
--

CREATE TABLE IF NOT EXISTS `admin_menu` (
  `category` text COLLATE utf8_unicode_ci NOT NULL,
  `name` text COLLATE utf8_unicode_ci,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icon` text COLLATE utf8_unicode_ci NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `target` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=37 ;

--
-- Dumping data for table `admin_menu`
--

INSERT INTO `admin_menu` (`category`, `name`, `id`, `icon`, `url`, `target`) VALUES
('!!!TEMP!!!', 'Categories', 7, 'icons/Tag.png', '$RFS_SITE_URL/admin/adm.php?action=edit_categories', ''),
('!!!TEMP!!!', 'Users', 16, 'icons/user_group_colored.png', '$RFS_SITE_URL/admin/adm.php?action=useredit', ''),
('!!!TEMP!!!', 'Linkbin', 5, 'icons/Recycle_Bin_Full.png', '$RFS_SITE_URL/admin/adm.php?action=editlinkbin', ''),
('!!!TEMP!!!', 'Log: Rotate', 4, 'icons/Refresh.png', '$RFS_SITE_URL/admin/adm.php?action=rotatelog', ''),
('!!!TEMP!!!', 'Log: View', 3, 'icons/Document.png', '$RFS_SITE_URL/admin/adm.php?action=viewlog', ''),
('!!!TEMP!!!', 'Smilies', 2, 'icons/Smiley_Happy.png', '$RFS_SITE_URL/admin/adm.php?action=smiles', ''),
('!!!TEMP!!!', 'Menu: Admin', 8, 'icons/Book.png', '$RFS_SITE_URL/admin/adm.php?action=admin_menu_edit', ''),
('!!!TEMP!!!', 'Menu: Top', 9, 'icons/Book.png', '$RFS_SITE_URL/admin/adm.php?action=menu_topedit', ''),
('!!!TEMP!!!', 'RSS', 11, 'icons/RSS.png', '$RFS_SITE_URL/admin/adm.php?action=rssedit', ''),
('!!!TEMP!!!', 'Share', 12, 'icons/facebook.png', '$RFS_SITE_URL/admin/adm.php?action=shareedit', ''),
('!!!TEMP!!!', 'News: Submit', 13, 'icons/Documents.png', '$RFS_SITE_URL/news.php?showform=yes', ''),
('!!!TEMP!!!', 'News: Edit', 14, 'icons/Documents.png', '$RFS_SITE_URL/news.php?action=edityournews', ''),
('!!!TEMP!!!', 'Site Vars', 15, 'icons/blueprint tool.png', '$RFS_SITE_URL/admin/adm.php?action=editsitevars', ''),
('!!!TEMP!!!', 'Network Query Tool', 17, 'icons/Wireless.png', '$RFS_SITE_URL/admin/adm.php?action=nqt', ''),
('!!!TEMP!!!', 'Forum: Admin', 19, 'icons/Speech_Bubble.png', '$RFS_SITE_URL/admin/adm.php?action=forum_admin', ''),
('!!!TEMP!!!', 'Database: Query', 21, 'icons/db.png', '$RFS_SITE_URL/admin/adm.php?action=db_query', ''),
('!!!TEMP!!!', 'Themes', 23, 'icons/screen_blazeoflight.png', '$RFS_SITE_URL/admin/adm.php?action=theme', ''),
('!!!TEMP!!!', 'Application Builder', 24, 'icons/blueprint tool.png', '$RFS_SITE_URL/wab.php?runapp=1', ''),
('!!!TEMP!!!', 'PHP: Eval Code', 25, 'icons/Settings.png', '$RFS_SITE_URL/admin/adm.php?action=evalform', ''),
('!!!TEMP!!!', 'Form Generator', 26, 'icons/Todo.png', '$RFS_SITE_URL/admin/adm.php?action=formgenerator', ''),
('!!!TEMP!!!', 'File: Edit', 27, 'icons/Play.png', '$RFS_SITE_URL/admin/adm.php?action=edit_file', ''),
('!!!TEMP!!!', 'Xplorer', 28, 'icons/screen_windows.png', '$RFS_SITE_URL/xplorer/xplorer.php', ''),
('!!!TEMP!!!', 'Database: phpMyAdmin', 29, 'icons/db.png', '$RFS_SITE_URL/3rdparty/phpmyadmin/', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
