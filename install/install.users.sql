CREATE TABLE IF NOT EXISTS `users` ( `id` int(11) NOT NULL AUTO_INCREMENT,  `name` text NOT NULL, `alias` text NOT NULL, `name_shown` text NOT NULL, `pass` text NOT NULL, `real_name` text NOT NULL, `email` text NOT NULL, UNIQUE KEY `id` (`id`)  )
ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

