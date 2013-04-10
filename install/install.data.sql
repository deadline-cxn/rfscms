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
('News', 10, 'index.php', 124, 0),
('Videos', 11, '$RFS_SITE_URL/modules/videos/videos.php', 195, 0),
('Forum', 12, '$RFS_SITE_URL/modules/forums/forum.php?forum_list=yes', 199, 0),
('Pictures', 13, '$RFS_SITE_URL/modules/pictures/pics.php', 189, 0),
('Profile', 14, '$RFS_SITE_URL/modules/profile/profile.php', 432, 0),
('Wiki', 15, '$RFS_SITE_URL/modules/wiki/rfswiki.php', 140, 0),
('Comics', 16, '$RFS_SITE_URL/modules/comics/comics.php', 155, 0),
('Meme Generator', 17, '$RFS_SITE_URL/modules/pictures/pics.php?action=showmemes', 192, 0),
('Video Wall', 18, '$RFS_SITE_URL/modules/video_wall/v.php', 196, 0);
