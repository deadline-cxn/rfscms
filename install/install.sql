CREATE TABLE IF NOT EXISTS `access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `access` varchar(64) NOT NULL DEFAULT '',
  `paction` varchar(64) NOT NULL DEFAULT '',
  `page` varchar(64) NOT NULL DEFAULT '',
  `ptable` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `access_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(64) NOT NULL DEFAULT '',
  `paction` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `addon_database` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `datetime_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `datetime_updated` timestamp NOT NULL ,
  `version` varchar(64) NOT NULL DEFAULT '',
  `sub_version` varchar(64) NOT NULL DEFAULT '',
  `release` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(64) NOT NULL DEFAULT '',
  `requirements` varchar(64) NOT NULL DEFAULT '',
  `cost` varchar(64) NOT NULL DEFAULT '',
  `license` varchar(64) NOT NULL DEFAULT '',
  `dependencies` varchar(64) NOT NULL DEFAULT '',
  `author` varchar(64) NOT NULL DEFAULT '',
  `author_email` varchar(64) NOT NULL DEFAULT '',
  `author_website` varchar(64) NOT NULL DEFAULT '',
  `rating` varchar(64) NOT NULL DEFAULT '',
  `images` varchar(64) NOT NULL DEFAULT '',
  `file_url` varchar(64) NOT NULL DEFAULT '',
  `git_repository` varchar(64) NOT NULL DEFAULT '',
  `core` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `admin_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `category` varchar(64) NOT NULL DEFAULT '',
  `icon` varchar(64) NOT NULL DEFAULT '',
  `url` varchar(64) NOT NULL DEFAULT '',
  `target` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

CREATE TABLE IF NOT EXISTS `arrangement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(64) NOT NULL DEFAULT '',
  `panel` text,
  `type` varchar(64) NOT NULL DEFAULT '',
  `tableref` varchar(64) NOT NULL DEFAULT '',
  `tablerefid` varchar(64) NOT NULL DEFAULT '',
  `num` int(11) NOT NULL,
  `sequence` int(11) NOT NULL,
  `access` varchar(64) NOT NULL DEFAULT '',
  `page` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `arrangementid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tableref` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `banned` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `domain` varchar(64) NOT NULL DEFAULT '',
  `link` varchar(64) NOT NULL DEFAULT '',
  `ip` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `image` varchar(64) NOT NULL DEFAULT '',
  `worksafe` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

CREATE TABLE IF NOT EXISTS `colors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `r` int(11) NOT NULL,
  `g` int(11) NOT NULL,
  `b` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

CREATE TABLE IF NOT EXISTS `comics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `title` varchar(64) NOT NULL DEFAULT '',
  `time` datetime NOT NULL ,
  `published` varchar(64) NOT NULL DEFAULT '',
  `author` varchar(64) NOT NULL DEFAULT '',
  `volume` varchar(64) NOT NULL DEFAULT '',
  `issue` varchar(64) NOT NULL DEFAULT '',
  `rating` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `comics_pages` (
  `name` varchar(64) NOT NULL DEFAULT '',
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL DEFAULT '0',
  `page` int(11) NOT NULL DEFAULT '0',
  `template` int(11) NOT NULL DEFAULT '0',
  `panel1` varchar(64) NOT NULL DEFAULT '',
  `panel2` varchar(64) NOT NULL DEFAULT '',
  `panel3` varchar(64) NOT NULL DEFAULT '',
  `panel4` varchar(64) NOT NULL DEFAULT '',
  `panel5` varchar(64) NOT NULL DEFAULT '',
  `panel6` varchar(64) NOT NULL DEFAULT '',
  `panel7` varchar(64) NOT NULL DEFAULT '',
  `panel8` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `comics_page_templates` (
  `name` text,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `panels` varchar(64) NOT NULL DEFAULT '',
  `panel1_x` varchar(64) NOT NULL DEFAULT '',
  `panel1_y` varchar(64) NOT NULL DEFAULT '',
  `panel2_x` varchar(64) NOT NULL DEFAULT '',
  `panel2_y` varchar(64) NOT NULL DEFAULT '',
  `panel3_x` varchar(64) NOT NULL DEFAULT '',
  `panel3_y` varchar(64) NOT NULL DEFAULT '',
  `panel4_x` varchar(64) NOT NULL DEFAULT '',
  `panel4_y` varchar(64) NOT NULL DEFAULT '',
  `panel5_x` varchar(64) NOT NULL DEFAULT '',
  `panel5_y` varchar(64) NOT NULL DEFAULT '',
  `panel6_x` varchar(64) NOT NULL DEFAULT '',
  `panel6_y` varchar(64) NOT NULL DEFAULT '',
  `panel7_x` varchar(64) NOT NULL DEFAULT '',
  `panel7_y` varchar(64) NOT NULL DEFAULT '',
  `panel8_x` varchar(64) NOT NULL DEFAULT '',
  `panel8_y` varchar(64) NOT NULL DEFAULT '',
  `panel1_l` varchar(64) NOT NULL DEFAULT '',
  `panel2_l` varchar(64) NOT NULL DEFAULT '',
  `panel3_l` varchar(64) NOT NULL DEFAULT '',
  `panel4_l` varchar(64) NOT NULL DEFAULT '',
  `panel5_l` varchar(64) NOT NULL DEFAULT '',
  `panel6_l` varchar(64) NOT NULL DEFAULT '',
  `panel7_l` varchar(64) NOT NULL DEFAULT '',
  `panel8_l` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `comments` (
  `name` varchar(64) NOT NULL DEFAULT '',
  `type` varchar(64) NOT NULL DEFAULT '',
  `nid` int(11) NOT NULL DEFAULT '0',
  `poster` int(11) NOT NULL DEFAULT '0',
  `header` varchar(64) NOT NULL DEFAULT '',
  `message` varchar(64) NOT NULL DEFAULT '',
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `time` datetime DEFAULT NULL,
  `rating` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `counters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(64) NOT NULL DEFAULT '',
  `user` varchar(64) NOT NULL DEFAULT '',
  `user_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_ip` varchar(64) NOT NULL DEFAULT '',
  `hits_raw` int(11) NOT NULL,
  `hits_unique` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

CREATE TABLE IF NOT EXISTS `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(64) NOT NULL DEFAULT '',
  `image` varchar(64) NOT NULL DEFAULT '',
  `available` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `course_component` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `image` varchar(64) NOT NULL DEFAULT '',
  `parent` varchar(64) NOT NULL DEFAULT '',
  `sequence` varchar(64) NOT NULL DEFAULT '',
  `type` text NOT NULL COMMENT 'course_component_type',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `course_component_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `image` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `db_queries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `query` varchar(64) NOT NULL DEFAULT '',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `files` (
  `name` varchar(64) NOT NULL DEFAULT '',
  `location` varchar(64) NOT NULL DEFAULT '',
  `submitter` varchar(64) NOT NULL DEFAULT '',
  `category` varchar(64) NOT NULL DEFAULT '',
  `hidden` varchar(64) NOT NULL DEFAULT '',
  `downloads` int(11) NOT NULL DEFAULT '0',
  `description` varchar(64) NOT NULL DEFAULT '',
  `filetype` varchar(64) NOT NULL DEFAULT '',
  `size` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL ,
  `lastupdate` datetime NOT NULL ,
  `thumb` varchar(64) NOT NULL DEFAULT '',
  `version` varchar(64) NOT NULL DEFAULT '',
  `homepage` varchar(64) NOT NULL DEFAULT '',
  `owner` varchar(64) NOT NULL DEFAULT '',
  `platform` varchar(64) NOT NULL DEFAULT '',
  `os` varchar(64) NOT NULL DEFAULT '',
  `rating` varchar(64) NOT NULL DEFAULT '',
  `worksafe` varchar(64) NOT NULL DEFAULT '',
  `md5` varchar(64) NOT NULL DEFAULT '',
  `tags` varchar(64) NOT NULL DEFAULT '',
  `ignore` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `file_duplicates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loc1` varchar(64) NOT NULL DEFAULT '',
  `size1` varchar(64) NOT NULL DEFAULT '',
  `loc2` varchar(64) NOT NULL DEFAULT '',
  `size2` varchar(64) NOT NULL DEFAULT '',
  `md5` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `forum_list` (
  `name` varchar(64) NOT NULL DEFAULT '',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` varchar(64) NOT NULL DEFAULT '',
  `posts` int(11) NOT NULL DEFAULT '0',
  `moderator` int(11) NOT NULL DEFAULT '0',
  `password` varchar(64) NOT NULL DEFAULT '',
  `no_reply` varchar(64) NOT NULL DEFAULT '',
  `folder` varchar(64) NOT NULL DEFAULT '',
  `parent` varchar(64) NOT NULL DEFAULT '',
  `priority` varchar(64) NOT NULL DEFAULT '',
  `usepass` varchar(64) NOT NULL DEFAULT '',
  `private` varchar(64) NOT NULL DEFAULT '',
  `moderated` varchar(64) NOT NULL DEFAULT '',
  `bgcolor` varchar(64) NOT NULL DEFAULT '',
  `fgcolor` varchar(64) NOT NULL DEFAULT '',
  `last_post` int(11) NOT NULL DEFAULT '0',
  `access_group` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Forum Listing' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `forum_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poster` int(11) NOT NULL DEFAULT '0',
  `bumptime` datetime NOT NULL ,
  `title` varchar(64) NOT NULL DEFAULT '',
  `message` varchar(64) NOT NULL DEFAULT '',
  `thread` int(11) NOT NULL DEFAULT '0',
  `forum` int(11) NOT NULL DEFAULT '0',
  `time` datetime NOT NULL ,
  `thread_top` varchar(64) NOT NULL DEFAULT '',
  `sticky` varchar(64) NOT NULL DEFAULT '',
  `views` int(11) NOT NULL DEFAULT '0',
  `locked` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `link_bin` (
  `name` varchar(64) NOT NULL DEFAULT '',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(64) NOT NULL DEFAULT '',
  `poster` int(11) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL ,
  `sname` varchar(64) NOT NULL DEFAULT '',
  `referrals` int(11) NOT NULL DEFAULT '0',
  `hidden` int(11) NOT NULL DEFAULT '0',
  `description` varchar(64) NOT NULL DEFAULT '',
  `clicks` int(11) NOT NULL DEFAULT '0',
  `rating` int(11) NOT NULL DEFAULT '0',
  `category` text NOT NULL COMMENT 'categories',
  `bumptime` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `referral` varchar(64) NOT NULL DEFAULT '',
  `reviewed` varchar(64) NOT NULL DEFAULT '',
  `friend` varchar(64) NOT NULL DEFAULT '',
  `reciprocal` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `meme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poster` int(11) NOT NULL,
  `name` varchar(64) NOT NULL DEFAULT '',
  `basepic` int(11) NOT NULL,
  `texttop` varchar(64) NOT NULL DEFAULT '',
  `textbottom` varchar(64) NOT NULL DEFAULT '',
  `rating` int(11) NOT NULL,
  `font` varchar(64) NOT NULL DEFAULT '',
  `text_size` int(11) NOT NULL,
  `text_color` varchar(64) NOT NULL DEFAULT '',
  `text_bg_color` varchar(64) NOT NULL DEFAULT '',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `private` varchar(64) NOT NULL DEFAULT '',
  `datborder` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `menu_top` (
  `name` text COLLATE utf8_unicode_ci,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(64) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL,
  `target` varchar(64) NOT NULL DEFAULT '',
  `access_method` text COLLATE utf8_unicode_ci,
  `other_requirement` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `headline` varchar(64) NOT NULL DEFAULT '',
  `message` varchar(64) NOT NULL DEFAULT '',
  `category1` varchar(64) NOT NULL DEFAULT '',
  `submitter` int(11) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `image_url` varchar(64) NOT NULL DEFAULT '',
  `image_link` varchar(64) NOT NULL DEFAULT '',
  `image_alt` varchar(64) NOT NULL DEFAULT '',
  `topstory` varchar(64) NOT NULL DEFAULT '',
  `published` varchar(64) NOT NULL DEFAULT '',
  `views` int(11) NOT NULL DEFAULT '0',
  `rating` varchar(64) NOT NULL DEFAULT '',
  `sfw` varchar(64) NOT NULL DEFAULT '',
  `page` int(11) NOT NULL,
  `wiki` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `panel_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `key` varchar(64) NOT NULL DEFAULT '',
  `other` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=100 ;

CREATE TABLE IF NOT EXISTS `pictures` (
  `name` varchar(64) NOT NULL DEFAULT '',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gallery` varchar(64) NOT NULL DEFAULT '',
  `poster` varchar(64) NOT NULL DEFAULT '',
  `sname` varchar(64) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastupdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `url` varchar(64) NOT NULL DEFAULT '',
  `sfw` varchar(64) NOT NULL DEFAULT '',
  `category` varchar(64) NOT NULL DEFAULT '',
  `rating` varchar(64) NOT NULL DEFAULT '',
  `views` varchar(64) NOT NULL DEFAULT '',
  `hidden` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `pmsg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to` varchar(64) NOT NULL DEFAULT '',
  `from` varchar(64) NOT NULL DEFAULT '',
  `subject` varchar(64) NOT NULL DEFAULT '',
  `message` varchar(64) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `read` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rfsauth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `enabled` varchar(64) NOT NULL DEFAULT '',
  `value` varchar(64) NOT NULL DEFAULT '',
  `value2` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=105 ;

CREATE TABLE IF NOT EXISTS `rss_feeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `feed` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `searches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `search` varchar(64) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `engine` varchar(64) NOT NULL DEFAULT '',
  `fullsearch` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `site_vars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `value` varchar(64) NOT NULL DEFAULT '',
  `desc` text,
  `type` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

CREATE TABLE IF NOT EXISTS `site_vars_available` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `var` varchar(64) NOT NULL DEFAULT '',
  `type` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2047 ;

CREATE TABLE IF NOT EXISTS `site_var_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `key` varchar(64) NOT NULL DEFAULT '',
  `other` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=232 ;

CREATE TABLE IF NOT EXISTS `slogans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slogan` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `smilies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sfrom` varchar(64) NOT NULL DEFAULT '',
  `sto` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `static_html` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `html` varchar(64) NOT NULL DEFAULT '',
  `owner` varchar(64) NOT NULL DEFAULT '',
  `name` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `todo_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(64) NOT NULL DEFAULT '',
  `assigned_to` varchar(64) NOT NULL DEFAULT '',
  `assigned_to_group` varchar(64) NOT NULL DEFAULT '',
  `public` varchar(64) NOT NULL DEFAULT '',
  `owner` varchar(64) NOT NULL DEFAULT '',
  `type` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `todo_list_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `todo_list_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `list` varchar(64) NOT NULL DEFAULT '',
  `priority` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(64) NOT NULL DEFAULT '',
  `resolve_action` varchar(64) NOT NULL DEFAULT '',
  `step` varchar(64) NOT NULL DEFAULT '',
  `status` varchar(64) NOT NULL DEFAULT '',
  `opened` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `opened_by` varchar(64) NOT NULL DEFAULT '',
  `due` timestamp NOT NULL,
  `closed` timestamp NOT NULL,
  `closed_by` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `todo_list_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_date` varchar(64) NOT NULL DEFAULT '',
  `transaction_subject` varchar(64) NOT NULL DEFAULT '',
  `last_name` varchar(64) NOT NULL DEFAULT '',
  `residence_country` varchar(64) NOT NULL DEFAULT '',
  `item_name` varchar(64) NOT NULL DEFAULT '',
  `payment_gross` varchar(64) NOT NULL DEFAULT '',
  `mc_currency` varchar(64) NOT NULL DEFAULT '',
  `business` varchar(64) NOT NULL DEFAULT '',
  `payment_type` varchar(64) NOT NULL DEFAULT '',
  `protection_eligibility` varchar(64) NOT NULL DEFAULT '',
  `payer_status` varchar(64) NOT NULL DEFAULT '',
  `verify_sign` varchar(64) NOT NULL DEFAULT '',
  `txn_id` varchar(64) NOT NULL DEFAULT '',
  `payer_email` varchar(64) NOT NULL DEFAULT '',
  `tax` varchar(64) NOT NULL DEFAULT '',
  `receiver_email` varchar(64) NOT NULL DEFAULT '',
  `payer_id` varchar(64) NOT NULL DEFAULT '',
  `item_number` varchar(64) NOT NULL DEFAULT '',
  `mc_fee` varchar(64) NOT NULL DEFAULT '',
  `payment_fee` varchar(64) NOT NULL DEFAULT '',
  `mc_gross` varchar(64) NOT NULL DEFAULT '',
  `charset` varchar(64) NOT NULL DEFAULT '',
  `notify_version` varchar(64) NOT NULL DEFAULT '',
  `merchant_return_link` varchar(64) NOT NULL DEFAULT '',
  `auth` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

CREATE TABLE IF NOT EXISTS `tutorials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '',
  `message` varchar(64) NOT NULL DEFAULT '',
  `poster` int(11) NOT NULL DEFAULT '0',
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `category` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `useronline` (
  `name` text,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(15) NOT NULL DEFAULT '0',
  `ip` varchar(40) NOT NULL DEFAULT '',
  `loggedin` varchar(64) NOT NULL DEFAULT '',
  `page` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `facebook_id` varchar(64) NOT NULL DEFAULT '',
  `facebook_name` varchar(64) NOT NULL DEFAULT '',
  `first_name` varchar(64) NOT NULL DEFAULT '',
  `last_name` varchar(64) NOT NULL DEFAULT '',
  `facebook_link` varchar(64) NOT NULL DEFAULT '',
  `timezone` varchar(64) NOT NULL DEFAULT '',
  `locale` varchar(64) NOT NULL DEFAULT '',
  `donated` varchar(64) NOT NULL DEFAULT '',
  `pass` varchar(64) NOT NULL DEFAULT '',
  `real_name` varchar(64) NOT NULL DEFAULT '',
  `country` varchar(64) NOT NULL DEFAULT '',
  `gender` varchar(64) NOT NULL DEFAULT '',
  `email` varchar(64) NOT NULL DEFAULT '',
  `paypal_email` varchar(64) NOT NULL DEFAULT '',
  `webpage` varchar(64) NOT NULL DEFAULT '',
  `avatar` varchar(64) NOT NULL DEFAULT '',
  `picture` varchar(64) NOT NULL DEFAULT '',
  `posts` int(11) NOT NULL DEFAULT '0',
  `show_flash` varchar(64) NOT NULL DEFAULT '',
  `website_fav` varchar(64) NOT NULL DEFAULT '',
  `sentence` varchar(64) NOT NULL DEFAULT '',
  `first_login` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reporter` varchar(64) NOT NULL DEFAULT '',
  `show_contact_info` varchar(64) NOT NULL DEFAULT '',
  `upload` varchar(64) NOT NULL DEFAULT '',
  `files_uploaded` int(11) NOT NULL DEFAULT '0',
  `files_downloaded` int(11) NOT NULL DEFAULT '0',
  `last_activity` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `birthday` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `access` int(11) NOT NULL DEFAULT '0',
  `access_groups` varchar(64) NOT NULL DEFAULT '',
  `forumposts` int(11) NOT NULL DEFAULT '0',
  `forumreplies` int(11) NOT NULL DEFAULT '0',
  `videowall` varchar(64) NOT NULL DEFAULT '',
  `theme` varchar(64) NOT NULL DEFAULT '',
  `referrals` int(11) NOT NULL DEFAULT '0',
  `comments` int(11) NOT NULL DEFAULT '0',
  `linksadded` int(11) NOT NULL DEFAULT '0',
  `logins` int(11) NOT NULL DEFAULT '0',
  `facebook_username` varchar(64) NOT NULL DEFAULT '',
  `downloads` varchar(64) NOT NULL DEFAULT '',
  `uploads` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='hi' AUTO_INCREMENT=1001 ;

INSERT INTO `users` (`id`, `name`, `facebook_id`, `facebook_name`, `first_name`, `last_name`, `facebook_link`, `timezone`, `locale`, `donated`, `pass`, `real_name`, `country`, `gender`, `email`, `paypal_email`, `webpage`, `avatar`, `picture`, `posts`, `show_flash`, `website_fav`, `sentence`, `first_login`, `reporter`, `show_contact_info`, `upload`, `files_uploaded`, `files_downloaded`, `last_activity`, `last_login`, `birthday`, `access`, `access_groups`, `forumposts`, `forumreplies`, `videowall`, `theme`, `referrals`, `comments`, `linksadded`, `logins`, `facebook_username`, `downloads`, `uploads`)
VALUES (999, 'anonymous', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '', '', '', '0000-00-00 00:00:00', '', '', '', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '', 0, 0, '', '', 0, 0, 0, 0, '', '', '');

CREATE TABLE IF NOT EXISTS `videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contributor` int(11) NOT NULL,
  `sname` varchar(64) NOT NULL DEFAULT '',
  `refresh` int(11) NOT NULL DEFAULT '0',
  `url` varchar(64) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bumptime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `category` varchar(64) NOT NULL DEFAULT '',
  `hidden` varchar(64) NOT NULL DEFAULT '',
  `sfw` varchar(64) NOT NULL DEFAULT '',
  `embed_code` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1697 ;

CREATE TABLE IF NOT EXISTS `wab_engine` (
  `name` varchar(64) NOT NULL DEFAULT '',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL DEFAULT '1',
  `hidden` int(11) NOT NULL DEFAULT '1',
  `type` varchar(64) NOT NULL DEFAULT '',
  `value` varchar(64) NOT NULL DEFAULT '',
  `description` text COLLATE utf8_unicode_ci,
  `code` varchar(64) NOT NULL DEFAULT '',
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=61 ;

CREATE TABLE IF NOT EXISTS `wiki` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `revision` int(11) NOT NULL,
  `revised_by` varchar(64) NOT NULL DEFAULT '',
  `revision_note` varchar(64) NOT NULL DEFAULT '',
  `author` varchar(64) NOT NULL DEFAULT '',
  `text` varchar(64) NOT NULL DEFAULT '',
  `tags` varchar(64) NOT NULL DEFAULT '',
  `updated` timestamp NOT NULL  ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
