
INSERT INTO `admin_menu` (`name`, `id`, `icon`, `url`) VALUES
('Categories', 7, 'icons/Tag.png', 'adm.php?action=edit_categories'),
('Users', 16, 'icons/Users_Group.png', 'adm.php?action=useredit'),
('Linkbin', 5, 'icons/Recycle_Bin_Full.png', 'adm.php?action=editlinkbin'),
('Log: Rotate', 4, 'icons/Application2.png', 'adm.php?action=rotatelog'),
('Log: View', 3, 'icons/Ginux/File Types/Application.png', 'adm.php?action=viewlog'),
('Smilies', 2, 'icons/phuzion/PNG/Misc/Chat.png', 'adm.php?action=smiles'),
('Database: phpMyAdmin', 1, 'icons/Database.png', 'http://www.defectiveminds.com:2082/3rdparty/phpMyAdmin/index.php'),
('Menu: Admin', 8, 'icons/Book.png', 'adm.php?action=admin_menu_edit'),
('Menu: Top', 9, 'icons/Book.png', 'adm.php?action=menu_topedit'),
('RSS', 11, 'icons/RSS.png', 'adm.php?action=rssedit'),
('Share', 12, 'icons/facebook.png', 'adm.php?action=shareedit'),
('News: Submit', 13, 'icons/phuzion/PNG/File Types/Document.png', '$RFS_SITE_URL/news.php?showform=yes'),
('News: Edit', 14, 'icons/phuzion/PNG/File Types/Blank.png', '$RFS_SITE_URL/news.php?action=edityournews'),
('Site Vars', 15, 'icons/phuzion/PNG/Misc/Organize.png', 'adm.php?action=editsitevars'),
('Network Query Tool', 17, 'icons/Wireless.png', 'adm.php?action=nqt'),
('PHP: PHPInfo', 18, 'icons/admphp.gif', 'adm.php?action=eval&eval=phpinfo() ;'),
('Forum: Admin', 19, 'icons/Speech_Bubble.png', 'adm.php?action=forum_admin'),
('Database: Tables', 20, 'icons/Ginux/Start Menu/Run.png', 'adm.php?action=db_tables'),
('Database: Query', 21, 'icons/Ginux/Start Menu/Run.png', 'adm.php?action=db_query'),
('Themes', 23, 'icons/Ginux/File Types/BMP.png', 'adm.php?action=theme'),
('Application Builder', 24, 'icons/Sitemap - Flowchart.png', '$RFS_SITE_URL/wab.php?runapp=1'),
('PHP: Eval Code', 25, 'icons/admphp.gif', 'adm.php?action=evalform'),
('Form Generator', 26, 'icons/Todo.png', 'adm.php?action=formgenerator'),
('File: Edit', 27, 'icons/Play.png', 'adm.php?action=edit_file'),
('Xplorer', 28, 'icons/Play.png', '$RFS_SITE_URL/xplorer/xplorer.php'),
('Database: phpMyAdmin', 29, 'icons/Ginux/Start Menu/Run.png', '$RFS_SITE_URL:2082/3rdparty/phpMyAdmin/'),
('CPanel', 30, 'icons/Play.png', '$RFS_SITE_URL/cpanel'),
('WAB Engine', 31, 'icons/Play.png', '$RFS_SITE_URL/wab.php');
-;-
UPDATE `admin_menu` set category='!!!TEMP!!!';
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
INSERT INTO `menu_top` (`name`, `link`, `sort_order`, `access`) VALUES
('Admin',   'admin/adm.php',				434, 255),
('News',    'index.php', 					124, 0),
('Videos',  'videos.php', 					195, 0),
('Forum',   'forum.php?forum_list=yes', 	199, 0),
('Pictures','pics.php', 						189, 0),
('Profile', 'profile.php', 					432, 0);

