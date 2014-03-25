INSERT INTO `users` (`name`, `id` ) VALUES
('anonymous', '999');
-;-
INSERT INTO `site_vars` (`name`, `value`) VALUES
('singletablewidth', '910'),
('doubletablewidth', '435'),
('theme_dropdown', 'true'),
('default_theme', 'default'),
('force_theme', 'false'),
('forced_theme', 'default');
-;-
INSERT INTO `categories` (`name`, `id`, `image`) VALUES
('!!!TEMP!!!', 1, '0'),
('News', 2, '0'),
('Videos', 3, '0'),
('Interesting', 4, '0'),
('Funny', 5, '0'),
('Image', 6, '0'),
('Social', 7, '0'),
('Random', 8, '0'),
('Music', 9, '0');
-;-
INSERT INTO `colors` (`id`, `name`, `r`, `g`, `b`) VALUES
(1, 'black', 0, 0, 0),
(2, 'white', 255, 255, 255),
(3, 'red', 255, 0, 0),
(4, 'blue', 0, 0, 255),
(5, 'green', 0, 255, 0),
(6, 'yellow', 255, 255, 0),
(7, 'purple', 255, 0, 255);
-;-
INSERT INTO `menu_top` (`name`, `id`, `link`, `sort_order`, `access`) VALUES
('Admin', 9, '$RFS_SITE_URL/admin/adm.php', 434, 255),
('News', 10, '$RFS_SITE_URL/', 124, 0),
('Videos', 11, '$RFS_SITE_URL/modules/core_videos/videos.php', 195, 0),
('Forum', 12, '$RFS_SITE_URL/modules/core_forums/forums.php?forum_list=yes', 199, 0),
('Files', 13, '$RFS_SITE_URL/modules/core_files/files.php', 179, 0),
('Pictures', 14, '$RFS_SITE_URL/modules/core_pictures/pics.php', 189, 0),
('Profile', 15, '$RFS_SITE_URL/modules/core_profile/profile.php', 432, 0),
('Wiki', 16, '$RFS_SITE_URL/modules/core_wiki/wiki.php', 140, 0),
('Comics', 17, '$RFS_SITE_URL/modules/core_comics/comics.php', 155, 0),
('Meme Generator', 18, '$RFS_SITE_URL/modules/core_memes/memes.php', 192, 0),
('Video Wall', 19, '$RFS_SITE_URL/modules/core_video_wall/video_wall.php', 196, 0);
-;-
INSERT INTO `arrangement` (`id`, `location`, `mini`, `num`, `sequence`) VALUES
(17, 'middle', 'news_top_story', 5, 999);
-;-
INSERT INTO `access_methods` (`id`, `page`, `action`) VALUES
(1, 'admin', 'access'),
(5, 'news', 'edit'),
(11, 'news', 'deleteothers'),
(7, 'news', 'submit'),
(8, 'news', 'delete'),
(10, 'news', 'editothers'),
(12, 'wiki', 'admin'),
(13, 'files', 'upload'),
(14, 'files', 'addlink'),
(15, 'files', 'orphanscan'),
(16, 'files', 'purge'),
(17, 'files', 'sort'),
(18, 'files', 'edit'),
(19, 'files', 'delete'),
(20, 'files', 'xplorer'),
(21, 'files', 'xplorershell'),
(22, 'forums', 'add'),
(23, 'forums', 'edit'),
(24, 'forums', 'delete'),
(25, 'forums', 'moderate'),
(26, 'exams', 'add'),
(27, 'exams', 'delete'),
(28, 'exams', 'deleteothers'),
(29, 'exams', 'edit'),
(30, 'exams', 'editothers'),
(31, 'comics', 'create'),
(32, 'comics', 'delete'),
(33, 'comics', 'deleteothers'),
(34, 'comics', 'edit'),
(35, 'comics', 'editothers'),
(36, 'wiki', 'editothers'),
(37, 'wiki', 'deleteothers'),
(38, 'pictures', 'orphanscan'),
(39, 'pictures', 'upload'),
(40, 'pictures', 'edit'),
(41, 'pictures', 'delete'),
(42, 'pictures', 'sort');
-;-
INSERT INTO `access` (`id`, `name`, `access`, `action`, `page`, `table`) VALUES
(1,  'Administrator,  '', 'admin', 'access', ''),
(60, 'Administrator', '', 'editothers', 'exams', ''),
(59, 'Administrator', '', 'edit', 'exams', ''),
(58, 'Administrator', '', 'deleteothers', 'exams', ''),
(57, 'Administrator', '', 'delete', 'exams', ''),
(56, 'Administrator', '', 'add', 'exams', ''),
(55, 'Administrator', '', 'xplorershell', 'files', ''),
(54, 'Administrator', '', 'xplorer', 'files', ''),
(53, 'Administrator', '', 'delete', 'files', ''),
(52, 'Administrator', '', 'edit', 'files', ''),
(51, 'Administrator', '', 'sort', 'files', ''),
(50, 'Administrator', '', 'purge', 'files', ''),
(49, 'Administrator', '', 'orphanscan', 'files', ''),
(48, 'Administrator', '', 'addlink', 'files', ''),
(47, 'Administrator', '', 'upload', 'files', ''),
(46, 'Administrator', '', 'editothers', 'comics', ''),
(45, 'Administrator', '', 'edit', 'comics', ''),
(44, 'Administrator', '', 'deleteothers', 'comics', ''),
(43, 'Administrator', '', 'delete', 'comics', ''),
(42, 'Administrator', '', 'create', 'comics', ''),
(41, 'Administrator', '', 'moderate', 'forums', ''),
(40, 'Administrator', '', 'delete', 'forums', ''),
(39, 'Administrator', '', 'edit', 'forums', ''),
(38, 'Administrator', '', 'add', 'forums', ''),
(37, 'Administrator', '', 'admin', 'wiki', ''),
(61, 'Administrator', '', 'edit', 'news', ''),
(62, 'Administrator', '', 'editothers', 'news', ''),
(63, 'Administrator', '', 'submit', 'news', ''),
(64, 'Administrator', '', 'delete', 'news', ''),
(65, 'Administrator', '', 'deleteothers', 'news', ''),
(66, 'Administrator', '', 'editothers', 'wiki', ''),
(67, 'Administrator', '', 'deleteothers', 'wiki', ''),
(68, 'Administrator', '', 'orphanscan', 'pictures', ''),
(69, 'Administrator', '', 'upload', 'pictures', ''),
(70, 'Administrator', '', 'edit', 'pictures', ''),
(71, 'Administrator', '', 'delete', 'pictures', ''),
(72, 'Administrator', '', 'sort', 'pictures', '');

