// RFSCMS INITIAL DATABASE FILL
// -;- = delimiter
-;-
INSERT INTO `users` (`name`, `id` ) VALUES ('anonymous', '999');
-;-
INSERT INTO `arrangement` (`location`, `mini`, `type`, `tableref`, `tablerefid` `num`, `sequence`,`access`,`page`) VALUES ('middle', 'news_top_story', 'internal_query','','', 5, 999,'','');
-;-
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('admin', 'access');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('comics', 'create');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('course', 'edit');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('debug', 'view');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('exam_questions', 'edit');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('exams', 'add');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('files', 'upload');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('forums', 'add');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('linkbin', 'edit');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('memes', 'upload');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('news', 'edit');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('pictures', 'orphanscan');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('slogan', 'edit');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('static_html', 'edit');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('todo_list', 'add');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('videos', 'submit');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('wiki', 'admin');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('comics', 'admin');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('comics', 'delete');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('comics', 'deleteothers');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('comics', 'edit');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('comics', 'editothers');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('comics', 'publish');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('comics', 'unpublish');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('wiki', 'edit');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('wiki', 'delete');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('wiki', 'editothers');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('wiki', 'deleteothers');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('wiki', 'uploadfile');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('memes', 'edit');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('memes', 'delete');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('pictures', 'upload');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('pictures', 'edit');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('pictures', 'delete');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('pictures', 'sort');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('files', 'addlink');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('files', 'orphanscan');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('files', 'purge');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('files', 'sort');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('files', 'edit');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('files', 'delete');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('files', 'xplorer');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('files', 'xplorershell');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('admin', 'categories');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('forums', 'admin');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('forums', 'edit');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('forums', 'delete');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('forums', 'moderate');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('news', 'editothers');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('news', 'submit');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('news', 'delete');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('news', 'deleteothers');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('linkbin', 'add');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('linkbin', 'delete');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('videos', 'edit');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('videos', 'editothers');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('videos', 'delete');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('videos', 'deleteothers');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('course', 'delete');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('exams', 'create');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('exams', 'delete');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('exams', 'deleteothers');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('exams', 'edit');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('exams', 'editothers');
INSERT INTO `access_methods` (`page`, `paction`) VALUES ('exams', 'viewresults');
-;-
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'wiki', 'uploadfile');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'wiki', 'deleteothers');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'wiki', 'editothers');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'wiki', 'delete');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'wiki', 'edit');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'comics', 'unpublish');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'comics', 'publish');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'comics', 'editothers');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'comics', 'edit');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'comics', 'deleteothers');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'comics', 'delete');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'comics', 'admin');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'wiki', 'admin');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'videos', 'submit');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'todo_list', 'add');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'static_html', 'edit');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'slogan', 'edit');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'pictures', 'orphanscan');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'news', 'edit');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'memes', 'upload');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'linkbin', 'edit');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'forums', 'add');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'files', 'upload');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'exams', 'add');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'exam_questions', 'edit');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'debug', 'view');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'course', 'edit');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'comics', 'create');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'admin', 'access');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'exams', 'create');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'exams', 'delete');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'exams', 'deleteothers');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'exams', 'edit');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'exams', 'editothers');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'exams', 'viewresults');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'course', 'delete');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'memes', 'edit');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'memes', 'delete');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'pictures', 'upload');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'pictures', 'edit');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'pictures', 'delete');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'pictures', 'sort');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'files', 'addlink');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'files', 'orphanscan');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'files', 'purge');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'files', 'sort');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'files', 'edit');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'files', 'delete');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'files', 'xplorer');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'files', 'xplorershell');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'admin', 'categories');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'forums', 'admin');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'forums', 'edit');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'forums', 'delete');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'forums', 'moderate');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'news', 'editothers');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'news', 'submit');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'news', 'delete');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'news', 'deleteothers');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'linkbin', 'add');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'linkbin', 'delete');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'videos', 'edit');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'videos', 'editothers');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'videos', 'delete');
-;-                                                              
INSERT INTO `access` (`name`, `page`, `paction`) VALUES ('Administrator', 'videos', 'deleteothers');
-;-
INSERT INTO `categories` (`name`, `image`, `worksafe`) VALUES
('!!!TEMP!!!', '0', ''),
('News', '0', ''),
('Videos', '0', ''),
('Interesting', '0', ''),
('Funny', '0', ''),
('Image', '0', ''),
('Social', '0', ''),
('Random', '0', ''),
('Music', '0', ''),
('unsorted', '', '');
-;-
INSERT INTO `colors` (`name`, `r`, `g`, `b`) VALUES
('black', 0, 0, 0),
('white', 255, 255, 255),
('red', 255, 0, 0),
('blue', 0, 0, 255),
('green', 0, 255, 0),
('yellow', 255, 255, 0),
('purple', 255, 0, 255);
-;-
INSERT INTO `db_queries` (`query`, `time`) VALUES
('SELECT * FROM users', '2014-05-22 02:24:37'),
('SELECT name,email,donated FROM users', '2014-05-22 02:25:33');
-;-
INSERT INTO `menu_top` (`name`, `id`, `link`, `sort_order`, `target`, `access_method`, `other_requirement`) VALUES
('Admin', 19, '$RFS_SITE_URL/admin/adm.php', 5000, '', 'admin,access', NULL),
('News', 21, '$RFS_SITE_URL/', 0, '', NULL, NULL),
('Wiki', 23, '$RFS_SITE_URL/modules/core_wiki/wiki.php', 20, '', '', NULL),
('Forum', 12, '$RFS_SITE_URL/modules/core_forums/forums.php', 199, '', NULL, NULL),
('Profile', 15, '$RFS_SITE_URL/modules/core_profile/profile.php', 799, '', 'logged_in,true', 'loggedin=true'),
('Videos', 31, '$RFS_SITE_URL/modules/core_videos/videos.php', 599, '', NULL, NULL),
('Files', 35, '$RFS_SITE_URL/modules/core_files/files.php', 51, '', '', NULL),
('Pictures', 33, '$RFS_SITE_URL/modules/core_pictures/pictures.php', 465, '', '', NULL);
-;-
INSERT INTO `panel_types` (`name`, `table`, `key`, `other`) VALUES
('results', '', '', ''),
('eval', '', '', ''),
('static', '', '', '');
-;-
INSERT INTO `rfsauth` (`name`, `enabled`, `value`, `value2`) VALUES
('EBSR', '', '', ''),
('', 'true', '', ''),
('FACEBOOK', '', '', ''),
('', 'false', '', ''),
('OPENID', '', '', ''),
('', 'true', '', ''),
('', 'false', '', '');
-;-
INSERT INTO `site_vars` ( `name`, `value`, `desc`, `type`) VALUES
('theme_dropdown', 'true', NULL, NULL),
('default_theme', 'default', NULL, NULL),
('force_theme', 'false', NULL, NULL),
('forced_theme', 'default', NULL, NULL),
('slogan', 'Really Freaking Simple Content Management System', NULL, NULL),
('database_upgrade', '1', NULL, 'text');
-;-
INSERT INTO `site_vars_available` (`var`, `type`, `description`) VALUES
('LOCALE', 'text', ''),
('OS', 'text', ''),
('PATH_SEP', 'text', ''),
('HEAD', 'text', ''),
('FONT', 'text', ''),
('NAV_IMG_TOP', 'text', ''),
('URL', 'text', ''),
('DELIMITER', 'text', ''),
('SUDO_CMD', 'text', ''),
('PATH', 'text', ''),
('CHECK_UPDATE', 'bool', ''),
('DEFAULT_THEME', 'theme', ''),
('FORCE_THEME', 'bool', ''),
('FORCED_THEME', 'theme', ''),
('SESSION_ID', 'text', ''),
('SESSION_USER', 'text', ''),
('ADMIN', 'text', ''),
('ADMIN_EMAIL', 'text', ''),
('SLOGAN', 'text', ''),
('URL', 'text', ''),
('ERROR_LOG', 'file', ''),
('THEME_DROPDOWN', 'bool', ''),
('ADDTHIS_ACCT', 'text', ''),
('FACEBOOK_APP_ID', 'text', ''),
('FACEBOOK_SECRET', 'text', ''),
('FACEBOOK_SDK', 'text', ''),
('FACEBOOK_NEWS_COMMENTS', 'bool', ''),
('FACEBOOK_WIKI_COMMENTS', 'bool', ''),
('ALLOW_FREE_DOWNLOADS', 'bool', ''),
('MENU_TOP_LOCATION', 'menu_location', ''),
('MENU_LEFT_LOCATION', 'menu_location', ''),
('FOOTER', 'text', ''),
('COPYRIGHT', 'text', ''),
('JOIN_FORM_CODE', 'text', ''),
('LOGIN_FORM_CODE', 'text', ''),
('LOGGED_IN_CODE', 'text', ''),
('JS_JQUERY', 'file', ''),
('JS_COLOR', 'file', ''),
('JS_MOOTOOLS', 'file', ''),
('JS_EDITAREA', 'file', ''),
('JS_MSDROPDOWN', 'file', ''),
('JS_MSDROPDOWN_THEME', 'text', ''),
('TITLE', 'text', ''),
('NAME', 'text', ''),
('SEO_KEYWORDS', 'text', ''),
('DOC_TYPE', 'text', ''),
('HTML_OPEN', 'text', ''),
('HEAD_OPEN', 'text', ''),
('HEAD_CLOSE', 'text', ''),
('BODY_OPEN', 'text', ''),
('BODY_CLOSE', 'text', ''),
('HTML_CLOSE', 'text', ''),
('GOOGLE_ADSENSE', 'text', ''),
('PAYPAL_BUTTON1', 'text', ''),
('PAYPAL_BUTTON1_MSG', 'text', ''),
('PAYPAL_BUTTON2', 'text', ''),
('PAYPAL_BUTTON2_MSG', 'text', ''),
('SHOW_SOCIALS', 'text', ''),
('ADDTHIS_ACCT', 'text', ''),
('GOOGLE_ANALYTICS', 'text', ''),
('GALLERIAS', 'bool', ''),
('CAPTIONS', 'bool', '');
-;-
INSERT INTO `site_var_types` (`name`, `table`, `key`, `other`) VALUES
('text', '', '', ''),
('bool', '', '', 'on,off'),
('theme', '', '', ''),
('file', '', '', ''),
('menu_location', '', '', ''),
('picture', 'pictures', 'name', ''),
('video', 'videos', 'name', ''),
('text', '', '', '');
-;-
INSERT INTO `todo_list_status` (`name`) VALUES
('Open'),
('In Progress'),
('Resolved'),
('Closed');
-;-
INSERT INTO `todo_list_type` (`name`) VALUES
('Personal'),
('Bug'),
('Task');
