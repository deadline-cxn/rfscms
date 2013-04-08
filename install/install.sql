
CREATE TABLE IF NOT EXISTS `access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `access` text COLLATE utf8_unicode_ci NOT NULL,
  `action` text COLLATE utf8_unicode_ci NOT NULL,
  `page` text COLLATE utf8_unicode_ci NOT NULL,
  `table` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `admin_menu` (
  `category` text COLLATE utf8_unicode_ci NOT NULL,
  `name` text COLLATE utf8_unicode_ci,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `icon` text COLLATE utf8_unicode_ci NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `target` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=37 ;

CREATE TABLE IF NOT EXISTS `ads_skyscrapers` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` text COLLATE utf8_unicode_ci NOT NULL,
  `html` text COLLATE utf8_unicode_ci NOT NULL,
  `paid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `banned` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` text COLLATE utf8_unicode_ci NOT NULL,
  `link` text COLLATE utf8_unicode_ci NOT NULL,
  `ip` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `categories` (
  `name` text COLLATE utf8_unicode_ci,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=76 ;

CREATE TABLE IF NOT EXISTS `colors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `r` int(11) NOT NULL,
  `g` int(11) NOT NULL,
  `b` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

CREATE TABLE IF NOT EXISTS `comics` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` text COLLATE utf8_unicode_ci NOT NULL,
  `author` text COLLATE utf8_unicode_ci NOT NULL,
  `volume` text COLLATE utf8_unicode_ci NOT NULL,
  `issue` text COLLATE utf8_unicode_ci NOT NULL,
  `rating` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `comics_pages` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL DEFAULT '0',
  `page` int(11) NOT NULL DEFAULT '0',
  `template` int(11) NOT NULL DEFAULT '0',
  `panel1` text COLLATE utf8_unicode_ci NOT NULL,
  `panel2` text COLLATE utf8_unicode_ci NOT NULL,
  `panel3` text COLLATE utf8_unicode_ci NOT NULL,
  `panel4` text COLLATE utf8_unicode_ci NOT NULL,
  `panel5` text COLLATE utf8_unicode_ci NOT NULL,
  `panel6` text COLLATE utf8_unicode_ci NOT NULL,
  `panel7` text COLLATE utf8_unicode_ci NOT NULL,
  `panel8` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `comics_page_templates` (
  `name` text COLLATE utf8_unicode_ci,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `panels` text COLLATE utf8_unicode_ci NOT NULL,
  `panel1_x` text COLLATE utf8_unicode_ci NOT NULL,
  `panel1_y` text COLLATE utf8_unicode_ci NOT NULL,
  `panel2_x` text COLLATE utf8_unicode_ci NOT NULL,
  `panel2_y` text COLLATE utf8_unicode_ci NOT NULL,
  `panel3_x` text COLLATE utf8_unicode_ci NOT NULL,
  `panel3_y` text COLLATE utf8_unicode_ci NOT NULL,
  `panel4_x` text COLLATE utf8_unicode_ci NOT NULL,
  `panel4_y` text COLLATE utf8_unicode_ci NOT NULL,
  `panel5_x` text COLLATE utf8_unicode_ci NOT NULL,
  `panel5_y` text COLLATE utf8_unicode_ci NOT NULL,
  `panel6_x` text COLLATE utf8_unicode_ci NOT NULL,
  `panel6_y` text COLLATE utf8_unicode_ci NOT NULL,
  `panel7_x` text COLLATE utf8_unicode_ci NOT NULL,
  `panel7_y` text COLLATE utf8_unicode_ci NOT NULL,
  `panel8_x` text COLLATE utf8_unicode_ci NOT NULL,
  `panel8_y` text COLLATE utf8_unicode_ci NOT NULL,
  `panel1_l` text COLLATE utf8_unicode_ci NOT NULL,
  `panel2_l` text COLLATE utf8_unicode_ci NOT NULL,
  `panel3_l` text COLLATE utf8_unicode_ci NOT NULL,
  `panel4_l` text COLLATE utf8_unicode_ci NOT NULL,
  `panel5_l` text COLLATE utf8_unicode_ci NOT NULL,
  `panel6_l` text COLLATE utf8_unicode_ci NOT NULL,
  `panel7_l` text COLLATE utf8_unicode_ci NOT NULL,
  `panel8_l` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

CREATE TABLE IF NOT EXISTS `comments` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `type` text COLLATE utf8_unicode_ci NOT NULL,
  `nid` int(11) NOT NULL DEFAULT '0',
  `poster` int(11) NOT NULL DEFAULT '0',
  `header` text COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `time` datetime DEFAULT NULL,
  `rating` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `counters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` text COLLATE utf8_unicode_ci NOT NULL,
  `user` text COLLATE utf8_unicode_ci NOT NULL,
  `user_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_ip` text COLLATE utf8_unicode_ci NOT NULL,
  `hits_raw` int(11) NOT NULL,
  `hits_unique` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

CREATE TABLE IF NOT EXISTS `db_queries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `query` text COLLATE utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=156 ;

CREATE TABLE IF NOT EXISTS `delp_last_searches` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `search_text` text COLLATE utf8_unicode_ci NOT NULL,
  `link` text COLLATE utf8_unicode_ci NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `files` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `location` text COLLATE utf8_unicode_ci NOT NULL,
  `submitter` text COLLATE utf8_unicode_ci NOT NULL,
  `category` text COLLATE utf8_unicode_ci NOT NULL,
  `hidden` text COLLATE utf8_unicode_ci NOT NULL,
  `downloads` int(11) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `filetype` text COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastupdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `thumb` text COLLATE utf8_unicode_ci NOT NULL,
  `version` text COLLATE utf8_unicode_ci NOT NULL,
  `homepage` text COLLATE utf8_unicode_ci NOT NULL,
  `owner` text COLLATE utf8_unicode_ci NOT NULL,
  `platform` text COLLATE utf8_unicode_ci NOT NULL,
  `os` text COLLATE utf8_unicode_ci NOT NULL,
  `rating` text COLLATE utf8_unicode_ci NOT NULL,
  `worksafe` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `forum_list` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `posts` int(11) NOT NULL DEFAULT '0',
  `moderator` int(11) NOT NULL DEFAULT '0',
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  `no_reply` text COLLATE utf8_unicode_ci NOT NULL,
  `folder` text COLLATE utf8_unicode_ci NOT NULL,
  `parent` text COLLATE utf8_unicode_ci NOT NULL,
  `priority` text COLLATE utf8_unicode_ci NOT NULL,
  `usepass` text COLLATE utf8_unicode_ci NOT NULL,
  `private` text COLLATE utf8_unicode_ci NOT NULL,
  `moderated` text COLLATE utf8_unicode_ci NOT NULL,
  `bgcolor` text COLLATE utf8_unicode_ci NOT NULL,
  `fgcolor` text COLLATE utf8_unicode_ci NOT NULL,
  `last_post` int(11) NOT NULL DEFAULT '0',
  `access_group` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `forum_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poster` int(11) NOT NULL DEFAULT '0',
  `bumptime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `thread` int(11) NOT NULL DEFAULT '0',
  `forum` int(11) NOT NULL DEFAULT '0',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `thread_top` text COLLATE utf8_unicode_ci NOT NULL,
  `sticky` text COLLATE utf8_unicode_ci NOT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `link_bin` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` text COLLATE utf8_unicode_ci NOT NULL,
  `poster` int(11) NOT NULL DEFAULT '0',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sname` text COLLATE utf8_unicode_ci NOT NULL,
  `referrals` int(11) NOT NULL DEFAULT '0',
  `hidden` int(11) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `clicks` int(11) NOT NULL DEFAULT '0',
  `rating` int(11) NOT NULL DEFAULT '0',
  `category` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'categories',
  `bumptime` datetime NOT NULL,
  `referral` text COLLATE utf8_unicode_ci NOT NULL,
  `reviewed` text COLLATE utf8_unicode_ci NOT NULL,
  `friend` text COLLATE utf8_unicode_ci NOT NULL,
  `reciprocal` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

CREATE TABLE IF NOT EXISTS `meme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poster` int(11) NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `basepic` int(11) NOT NULL,
  `texttop` text COLLATE utf8_unicode_ci NOT NULL,
  `texttop_color` text COLLATE utf8_unicode_ci NOT NULL,
  `textbottom` text COLLATE utf8_unicode_ci NOT NULL,
  `textbottom_color` text COLLATE utf8_unicode_ci NOT NULL,
  `rating` int(11) NOT NULL,
  `font` text COLLATE utf8_unicode_ci NOT NULL,
  `text_size` int(11) NOT NULL,
  `text_color` text COLLATE utf8_unicode_ci NOT NULL,
  `text_bg_color` text COLLATE utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `private` text COLLATE utf8_unicode_ci NOT NULL,
  `datborder` text COLLATE utf8_unicode_ci NOT NULL,
  `status` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=418 ;

CREATE TABLE IF NOT EXISTS `menu_top` (
  `name` text COLLATE utf8_unicode_ci,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` text COLLATE utf8_unicode_ci NOT NULL,
  `sort_order` int(11) NOT NULL,
  `access` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

CREATE TABLE IF NOT EXISTS `pictures` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gallery` text COLLATE utf8_unicode_ci NOT NULL,
  `poster` text COLLATE utf8_unicode_ci NOT NULL,
  `sname` text COLLATE utf8_unicode_ci NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastupdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `sfw` text COLLATE utf8_unicode_ci NOT NULL,
  `category` text COLLATE utf8_unicode_ci NOT NULL,
  `rating` text COLLATE utf8_unicode_ci NOT NULL,
  `views` text COLLATE utf8_unicode_ci NOT NULL,
  `hidden` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7164 ;

CREATE TABLE IF NOT EXISTS `rss_feeds` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `feed` text COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `searches` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `search` text COLLATE utf8_unicode_ci NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `engine` text COLLATE utf8_unicode_ci NOT NULL,
  `fullsearch` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=57 ;

CREATE TABLE IF NOT EXISTS `site_vars` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `slogans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slogan` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `smilies` (
  `sfrom` text COLLATE utf8_unicode_ci NOT NULL,
  `sto` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `useronline` (
  `name` text COLLATE utf8_unicode_ci,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(15) NOT NULL DEFAULT '0',
  `ip` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `loggedin` text COLLATE utf8_unicode_ci NOT NULL,
  `page` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=20 ;

CREATE TABLE IF NOT EXISTS `videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contributor` int(11) NOT NULL,
  `sname` text COLLATE utf8_unicode_ci NOT NULL,
  `refresh` int(11) NOT NULL DEFAULT '0',
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `bumptime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `category` text COLLATE utf8_unicode_ci NOT NULL,
  `hidden` text COLLATE utf8_unicode_ci NOT NULL,
  `sfw` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=57 ;

CREATE TABLE IF NOT EXISTS `wiki` (
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `author` text COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `tags` text COLLATE utf8_unicode_ci NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
