-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 16, 2013 at 12:22 PM
-- Server version: 5.5.23-55
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `about`
--

CREATE TABLE IF NOT EXISTS `about` (
  `text` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `access`
--

CREATE TABLE IF NOT EXISTS `access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `access` text NOT NULL,
  `action` text NOT NULL,
  `page` text NOT NULL,
  `table` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=176 ;

-- --------------------------------------------------------

--
-- Table structure for table `access_methods`
--

CREATE TABLE IF NOT EXISTS `access_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` text COLLATE utf8_unicode_ci NOT NULL,
  `action` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=41 ;

-- --------------------------------------------------------

--
-- Table structure for table `admin_menu`
--

CREATE TABLE IF NOT EXISTS `admin_menu` (
  `category` text NOT NULL,
  `name` text,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icon` text NOT NULL,
  `url` text NOT NULL,
  `target` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Table structure for table `ads_skyscrapers`
--

CREATE TABLE IF NOT EXISTS `ads_skyscrapers` (
  `name` text NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` text NOT NULL,
  `html` text NOT NULL,
  `paid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `anyterm`
--

CREATE TABLE IF NOT EXISTS `anyterm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `connection_type` text NOT NULL,
  `local_port` text NOT NULL,
  `username` text NOT NULL,
  `ipaddress` text NOT NULL,
  `port` text NOT NULL,
  `command` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `arrangement`
--

CREATE TABLE IF NOT EXISTS `arrangement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` text NOT NULL,
  `mini` text NOT NULL,
  `num` int(11) NOT NULL,
  `sequence` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- Table structure for table `banned`
--

CREATE TABLE IF NOT EXISTS `banned` (
  `name` text NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` text NOT NULL,
  `link` text NOT NULL,
  `ip` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=626 ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `name` text,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=112 ;

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE IF NOT EXISTS `colors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `r` int(11) NOT NULL,
  `g` int(11) NOT NULL,
  `b` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `comics`
--

CREATE TABLE IF NOT EXISTS `comics` (
  `name` text NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` text NOT NULL,
  `author` text NOT NULL,
  `volume` text NOT NULL,
  `issue` text NOT NULL,
  `rating` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `comics_pages`
--

CREATE TABLE IF NOT EXISTS `comics_pages` (
  `name` text NOT NULL,
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL DEFAULT '0',
  `page` int(11) NOT NULL DEFAULT '0',
  `template` int(11) NOT NULL DEFAULT '0',
  `panel1` text NOT NULL,
  `panel2` text NOT NULL,
  `panel3` text NOT NULL,
  `panel4` text NOT NULL,
  `panel5` text NOT NULL,
  `panel6` text NOT NULL,
  `panel7` text NOT NULL,
  `panel8` text NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Table structure for table `comics_page_templates`
--

CREATE TABLE IF NOT EXISTS `comics_page_templates` (
  `name` text,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `panels` text NOT NULL,
  `panel1_x` text NOT NULL,
  `panel1_y` text NOT NULL,
  `panel2_x` text NOT NULL,
  `panel2_y` text NOT NULL,
  `panel3_x` text NOT NULL,
  `panel3_y` text NOT NULL,
  `panel4_x` text NOT NULL,
  `panel4_y` text NOT NULL,
  `panel5_x` text NOT NULL,
  `panel5_y` text NOT NULL,
  `panel6_x` text NOT NULL,
  `panel6_y` text NOT NULL,
  `panel7_x` text NOT NULL,
  `panel7_y` text NOT NULL,
  `panel8_x` text NOT NULL,
  `panel8_y` text NOT NULL,
  `panel1_l` text NOT NULL,
  `panel2_l` text NOT NULL,
  `panel3_l` text NOT NULL,
  `panel4_l` text NOT NULL,
  `panel5_l` text NOT NULL,
  `panel6_l` text NOT NULL,
  `panel7_l` text NOT NULL,
  `panel8_l` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `name` text NOT NULL,
  `type` text NOT NULL,
  `nid` int(11) NOT NULL DEFAULT '0',
  `poster` int(11) NOT NULL DEFAULT '0',
  `header` text NOT NULL,
  `message` text NOT NULL,
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `time` datetime DEFAULT NULL,
  `rating` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5782 ;

-- --------------------------------------------------------

--
-- Table structure for table `counters`
--

CREATE TABLE IF NOT EXISTS `counters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` text COLLATE utf8_unicode_ci NOT NULL,
  `user` text COLLATE utf8_unicode_ci NOT NULL,
  `user_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_ip` text COLLATE utf8_unicode_ci NOT NULL,
  `hits_raw` int(11) NOT NULL,
  `hits_unique` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=133 ;

-- --------------------------------------------------------

--
-- Table structure for table `criteria`
--

CREATE TABLE IF NOT EXISTS `criteria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `logic` text NOT NULL,
  `logic_x` text NOT NULL,
  `logic_y` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `db_queries`
--

CREATE TABLE IF NOT EXISTS `db_queries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `query` text COLLATE utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=355 ;

-- --------------------------------------------------------

--
-- Table structure for table `delp_last_searches`
--

CREATE TABLE IF NOT EXISTS `delp_last_searches` (
  `name` text NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `search_text` text NOT NULL,
  `link` text NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=412 ;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `name` text NOT NULL,
  `location` text NOT NULL,
  `submitter` text NOT NULL,
  `category` text NOT NULL,
  `hidden` text NOT NULL,
  `downloads` int(11) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `filetype` text NOT NULL,
  `size` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastupdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `thumb` text NOT NULL,
  `version` text NOT NULL,
  `homepage` text NOT NULL,
  `owner` text NOT NULL,
  `platform` text NOT NULL,
  `os` text NOT NULL,
  `rating` text NOT NULL,
  `worksafe` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36874 ;

-- --------------------------------------------------------

--
-- Table structure for table `forum_list`
--

CREATE TABLE IF NOT EXISTS `forum_list` (
  `name` text NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` text NOT NULL,
  `posts` int(11) NOT NULL DEFAULT '0',
  `moderator` int(11) NOT NULL DEFAULT '0',
  `password` text NOT NULL,
  `no_reply` text NOT NULL,
  `folder` text NOT NULL,
  `parent` text NOT NULL,
  `priority` text NOT NULL,
  `usepass` text NOT NULL,
  `private` text NOT NULL,
  `moderated` text NOT NULL,
  `bgcolor` text NOT NULL,
  `fgcolor` text NOT NULL,
  `last_post` int(11) NOT NULL DEFAULT '0',
  `access_group` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Forum Listing' AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Table structure for table `forum_posts`
--

CREATE TABLE IF NOT EXISTS `forum_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poster` int(11) NOT NULL DEFAULT '0',
  `bumptime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `title` text NOT NULL,
  `message` text NOT NULL,
  `thread` int(11) NOT NULL DEFAULT '0',
  `forum` int(11) NOT NULL DEFAULT '0',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `thread_top` text NOT NULL,
  `sticky` text NOT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=323 ;

-- --------------------------------------------------------

--
-- Table structure for table `link_bin`
--

CREATE TABLE IF NOT EXISTS `link_bin` (
  `name` text NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` text NOT NULL,
  `poster` int(11) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sname` text NOT NULL,
  `referrals` int(11) NOT NULL DEFAULT '0',
  `hidden` int(11) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `clicks` int(11) NOT NULL DEFAULT '0',
  `rating` int(11) NOT NULL DEFAULT '0',
  `category` text NOT NULL COMMENT 'categories',
  `bumptime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `referral` text NOT NULL,
  `reviewed` text NOT NULL,
  `friend` text NOT NULL,
  `reciprocal` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1021 ;

-- --------------------------------------------------------

--
-- Table structure for table `meme`
--

CREATE TABLE IF NOT EXISTS `meme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poster` int(11) NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `basepic` int(11) NOT NULL,
  `texttop` text COLLATE utf8_unicode_ci NOT NULL,
  `textbottom` text COLLATE utf8_unicode_ci NOT NULL,
  `rating` int(11) NOT NULL,
  `font` text COLLATE utf8_unicode_ci NOT NULL,
  `text_size` int(11) NOT NULL,
  `text_color` text COLLATE utf8_unicode_ci NOT NULL,
  `text_bg_color` text COLLATE utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `private` text COLLATE utf8_unicode_ci NOT NULL,
  `datborder` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=79 ;

-- --------------------------------------------------------

--
-- Table structure for table `menu_top`
--

CREATE TABLE IF NOT EXISTS `menu_top` (
  `name` text,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` text NOT NULL,
  `sort_order` int(11) NOT NULL,
  `access` int(11) NOT NULL,
  `target` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Table structure for table `network_devices`
--

CREATE TABLE IF NOT EXISTS `network_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Web page database id',
  `info` text NOT NULL,
  `hostname` text NOT NULL,
  `ipaddress` text NOT NULL,
  `port` int(11) NOT NULL,
  `proxy_device` text NOT NULL,
  `dname` text NOT NULL,
  `dpass` text NOT NULL,
  `mac` text NOT NULL,
  `machw` text NOT NULL,
  `resource_type` int(11) NOT NULL,
  `location` text NOT NULL,
  `model` text NOT NULL,
  `serial_number` text NOT NULL,
  `operating_system` text NOT NULL,
  `services` text NOT NULL COMMENT 'csv ports',
  `status` text NOT NULL,
  `uptime` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `name` text NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `headline` text NOT NULL,
  `wiki` text NOT NULL,
  `message` text NOT NULL,
  `category1` text NOT NULL,
  `submitter` int(11) NOT NULL DEFAULT '0',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastupdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `image_url` text NOT NULL,
  `image_link` text NOT NULL,
  `image_alt` text NOT NULL,
  `topstory` text NOT NULL,
  `published` text NOT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `rating` text NOT NULL,
  `sfw` text NOT NULL,
  `page` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='news' AUTO_INCREMENT=253 ;

-- --------------------------------------------------------

--
-- Table structure for table `objectives`
--

CREATE TABLE IF NOT EXISTS `objectives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `criteria` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pictures`
--

CREATE TABLE IF NOT EXISTS `pictures` (
  `name` text NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gallery` text NOT NULL,
  `poster` text NOT NULL,
  `sname` text NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastupdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `url` text NOT NULL,
  `sfw` text NOT NULL,
  `category` text NOT NULL,
  `rating` text NOT NULL,
  `views` text NOT NULL,
  `hidden` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1793 ;

-- --------------------------------------------------------

--
-- Table structure for table `pmsg`
--

CREATE TABLE IF NOT EXISTS `pmsg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to` text NOT NULL,
  `from` text NOT NULL,
  `subject` text NOT NULL,
  `message` text NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `read` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=149 ;

-- --------------------------------------------------------

--
-- Table structure for table `pods`
--

CREATE TABLE IF NOT EXISTS `pods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course` text NOT NULL,
  `sequence` int(11) NOT NULL,
  `prerequisites` text NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `objectives` text NOT NULL,
  `topology` int(11) NOT NULL,
  `available` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pod_completion`
--

CREATE TABLE IF NOT EXISTS `pod_completion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` text NOT NULL,
  `pod` int(11) NOT NULL,
  `objective` int(11) NOT NULL,
  `criteria_completed` text NOT NULL,
  `completed` text NOT NULL,
  `date_completed` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `name` text NOT NULL,
  `icon` text,
  `id` int(11) NOT NULL DEFAULT '0',
  `thumb` text NOT NULL,
  `version` text NOT NULL,
  `description` text NOT NULL,
  `author` text NOT NULL,
  `type` text NOT NULL,
  `started` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `windows` text NOT NULL,
  `linux` text NOT NULL,
  `bsd` text NOT NULL,
  `status` text NOT NULL,
  `html` text NOT NULL,
  `file` int(11) NOT NULL COMMENT 'files',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `resource_types`
--

CREATE TABLE IF NOT EXISTS `resource_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icon` text NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `transport_method` text NOT NULL,
  `table_ref` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rss_feeds`
--

CREATE TABLE IF NOT EXISTS `rss_feeds` (
  `name` text NOT NULL,
  `feed` text NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=59 ;

-- --------------------------------------------------------

--
-- Table structure for table `scripts`
--

CREATE TABLE IF NOT EXISTS `scripts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `script_group` int(11) NOT NULL,
  `network_device` int(11) NOT NULL,
  `type` text NOT NULL,
  `file` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `script_groups`
--

CREATE TABLE IF NOT EXISTS `script_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `type` text NOT NULL,
  `pod` int(11) NOT NULL,
  `scripts` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `script_group_types`
--

CREATE TABLE IF NOT EXISTS `script_group_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `searches`
--

CREATE TABLE IF NOT EXISTS `searches` (
  `name` text NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `search` text NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `engine` text NOT NULL,
  `fullsearch` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=194 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_vars`
--

CREATE TABLE IF NOT EXISTS `site_vars` (
  `name` text NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `slogans`
--

CREATE TABLE IF NOT EXISTS `slogans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slogan` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=164 ;

-- --------------------------------------------------------

--
-- Table structure for table `smilies`
--

CREATE TABLE IF NOT EXISTS `smilies` (
  `sfrom` text NOT NULL,
  `sto` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `snippets`
--

CREATE TABLE IF NOT EXISTS `snippets` (
  `name` text,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `code` text NOT NULL,
  `poster` text NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `category` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `topmenu`
--

CREATE TABLE IF NOT EXISTS `topmenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `link` text NOT NULL,
  `sor` int(11) NOT NULL,
  `access` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `topology`
--

CREATE TABLE IF NOT EXISTS `topology` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` text NOT NULL,
  `name` text NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `network_device` int(11) NOT NULL,
  `connected_to` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_date` text COLLATE utf8_unicode_ci NOT NULL,
  `transaction_subject` text COLLATE utf8_unicode_ci NOT NULL,
  `last_name` text COLLATE utf8_unicode_ci NOT NULL,
  `residence_country` text COLLATE utf8_unicode_ci NOT NULL,
  `item_name` text COLLATE utf8_unicode_ci NOT NULL,
  `payment_gross` text COLLATE utf8_unicode_ci NOT NULL,
  `mc_currency` text COLLATE utf8_unicode_ci NOT NULL,
  `business` text COLLATE utf8_unicode_ci NOT NULL,
  `payment_type` text COLLATE utf8_unicode_ci NOT NULL,
  `protection_eligibility` text COLLATE utf8_unicode_ci NOT NULL,
  `payer_status` text COLLATE utf8_unicode_ci NOT NULL,
  `verify_sign` text COLLATE utf8_unicode_ci NOT NULL,
  `txn_id` text COLLATE utf8_unicode_ci NOT NULL,
  `payer_email` text COLLATE utf8_unicode_ci NOT NULL,
  `tax` text COLLATE utf8_unicode_ci NOT NULL,
  `receiver_email` text COLLATE utf8_unicode_ci NOT NULL,
  `payer_id` text COLLATE utf8_unicode_ci NOT NULL,
  `item_number` text COLLATE utf8_unicode_ci NOT NULL,
  `mc_fee` text COLLATE utf8_unicode_ci NOT NULL,
  `payment_fee` text COLLATE utf8_unicode_ci NOT NULL,
  `mc_gross` text COLLATE utf8_unicode_ci NOT NULL,
  `charset` text COLLATE utf8_unicode_ci NOT NULL,
  `notify_version` text COLLATE utf8_unicode_ci NOT NULL,
  `merchant_return_link` text COLLATE utf8_unicode_ci NOT NULL,
  `auth` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `tutorials`
--

CREATE TABLE IF NOT EXISTS `tutorials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `message` text NOT NULL,
  `poster` int(11) NOT NULL DEFAULT '0',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `category` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `tutorial_categories`
--

CREATE TABLE IF NOT EXISTS `tutorial_categories` (
  `name` text,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `icon` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='fds' AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `useronline`
--

CREATE TABLE IF NOT EXISTS `useronline` (
  `name` text,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(15) NOT NULL DEFAULT '0',
  `ip` varchar(40) NOT NULL DEFAULT '',
  `loggedin` text NOT NULL,
  `page` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1270876805 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `facebook_id` text NOT NULL,
  `facebook_name` text NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `facebook_link` text NOT NULL,
  `timezone` text NOT NULL,
  `locale` text NOT NULL,
  `donated` text NOT NULL,
  `pass` text NOT NULL,
  `real_name` text NOT NULL,
  `country` text NOT NULL,
  `gender` text NOT NULL,
  `email` text NOT NULL,
  `paypal_email` text NOT NULL,
  `webpage` text NOT NULL,
  `avatar` text NOT NULL,
  `picture` text NOT NULL,
  `posts` int(11) NOT NULL DEFAULT '0',
  `show_flash` text NOT NULL,
  `website_fav` text NOT NULL,
  `sentence` text NOT NULL,
  `first_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reporter` text NOT NULL,
  `show_contact_info` text NOT NULL,
  `upload` text NOT NULL,
  `files_uploaded` int(11) NOT NULL DEFAULT '0',
  `files_downloaded` int(11) NOT NULL DEFAULT '0',
  `last_activity` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `birthday` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` int(11) NOT NULL DEFAULT '0',
  `access_groups` text NOT NULL,
  `forumposts` int(11) NOT NULL DEFAULT '0',
  `forumreplies` int(11) NOT NULL DEFAULT '0',
  `videowall` text NOT NULL,
  `theme` text NOT NULL,
  `referrals` int(11) NOT NULL DEFAULT '0',
  `comments` int(11) NOT NULL DEFAULT '0',
  `linksadded` int(11) NOT NULL DEFAULT '0',
  `logins` int(11) NOT NULL DEFAULT '0',
  `facebook_username` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='hi' AUTO_INCREMENT=1005 ;

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE IF NOT EXISTS `videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contributor` int(11) NOT NULL,
  `sname` text NOT NULL,
  `refresh` int(11) NOT NULL DEFAULT '0',
  `url` text NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `bumptime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `category` text NOT NULL,
  `hidden` text NOT NULL,
  `sfw` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1697 ;

-- --------------------------------------------------------

--
-- Table structure for table `wab_calc`
--

CREATE TABLE IF NOT EXISTS `wab_calc` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL DEFAULT '1',
  `hidden` int(11) NOT NULL DEFAULT '1',
  `type` text COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `code` text COLLATE utf8_unicode_ci NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `wab_engine`
--

CREATE TABLE IF NOT EXISTS `wab_engine` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL DEFAULT '1',
  `hidden` int(11) NOT NULL DEFAULT '1',
  `type` text COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `code` text COLLATE utf8_unicode_ci NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=61 ;

-- --------------------------------------------------------

--
-- Table structure for table `wab_showusers`
--

CREATE TABLE IF NOT EXISTS `wab_showusers` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL DEFAULT '1',
  `hidden` int(11) NOT NULL DEFAULT '1',
  `type` text COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `code` text COLLATE utf8_unicode_ci NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `wab_tgk`
--

CREATE TABLE IF NOT EXISTS `wab_tgk` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL DEFAULT '1',
  `hidden` int(11) NOT NULL DEFAULT '1',
  `type` text COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `code` text COLLATE utf8_unicode_ci NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `wiki`
--

CREATE TABLE IF NOT EXISTS `wiki` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `author` text NOT NULL,
  `text` text NOT NULL,
  `tags` text NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=460 ;
