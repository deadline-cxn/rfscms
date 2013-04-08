



INSERT INTO `access` (`id`, `name`, `access`, `action`, `page`, `table`) VALUES
(1, 'Administrator', '255', 'admin', 'rfswiki.php', 'wiki'),
(2, 'Administrator', '255', 'admin', 'adm.php', ''),
(3, 'Administrator', '255', 'view', 'rfswiki.php', 'wiki'),
(4, 'Administrator', '255', 'edit', 'rfswiki.php', 'wiki');

INSERT INTO `comics_page_templates` (`name`, `id`, `panels`, `panel1_x`, `panel1_y`, `panel2_x`, `panel2_y`, `panel3_x`, `panel3_y`, `panel4_x`, `panel4_y`, `panel5_x`, `panel5_y`, `panel6_x`, `panel6_y`, `panel7_x`, `panel7_y`, `panel8_x`, `panel8_y`, `panel1_l`, `panel2_l`, `panel3_l`, `panel4_l`, `panel5_l`, `panel6_l`, `panel7_l`, `panel8_l`) VALUES
('3 Panels', 13, '3', '480', '200', '480', '200', '480', '200', '', '', '', '', '', '', '', '', '', '', 'yes', 'yes', 'no', 'no', 'no', 'no', 'no', 'no'),
('6 Panels', 12, '6', '230', '200', '230', '200', '230', '200', '230', '200', '230', '200', '230', '200', '', '', '', '', 'no', 'yes', 'no', 'yes', 'no', 'no', 'no', 'no'),
('Full Page', 10, '1', '480', '620', '12', '23', '', '', '', '', '', '', '', '', '', '', '', '', 'no', 'yes', 'no', 'no', 'no', 'no', 'no', 'no'),
('2 Panels', 14, '2', '480', '310', '480', '310', '', '', '', '', '', '', '', '', '', '', '', '', 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no'),
('4 Panels', 15, '4', '480', '200', '230', '200', '230', '200', '480', '200', '', '', '', '', '', '', '', '', 'yes', 'no', 'yes', 'no', 'no', 'no', 'no', 'no'),
('Strip', 16, '3', '250', '200', '250', '200', '250', '200', '', '', '', '', '', '', '', '', '', '', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no'),
('Single Panel Small', 18, '1', '320', '380', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no');

INSERT INTO `forum_list` (`name`, `id`, `comment`, `posts`, `moderator`, `password`, `no_reply`, `folder`, `parent`, `priority`, `usepass`, `private`, `moderated`, `bgcolor`, `fgcolor`, `last_post`, `access_group`) VALUES
('Forums',        1, 'Forum Group', 0, 0, '', '', 'yes', '', '', '', '', '', '', '', 0, ''),
('General Board', 2, 'General Board', 0, 1, '', '', 'no', '1', '4', 'no', 'no', 'no', 'ff0000', '0000ff', 318, ''),
('Admin Board',   3, 'Admin Board', 0, 1, '123', '', '', '1', 'b', 'no', 'yes', 'no', '#888822', '#FF0000', 320, '');

INSERT INTO `link_bin`
(`name`,                `id`, `link`,                         `poster`,  `sname`,                `hidden`,  `description`,          `rating`,  `category`, `referral`, `reviewed`, `friend`, `reciprocal`) VALUES
('www.sethcoder.com',      1, 'http://www.sethcoder.com/',      0,       'SethCoder.com',               0,  'Home of the RFS CMS!',       0, '!!!TEMP!!!', 'yes', 		'yes', 		'', 		''			),
('www.defectiveminds.com', 2, 'http://www.defectiveminds.com/', 0,       'www.defectiveminds.com',      0,  'Defective Minds...',         0, '!!!TEMP!!!', 'yes', 		'yes', 		'', 		''			);

INSERT INTO `smilies` (`sfrom`, `sto`) VALUES
(':)\"', '<img src=\"files/pictures/smiley.gif\" alt=\":)\">'),
('^-', '&lt;'),
('^+', '&gt;');

INSERT INTO `wiki` (`name`, `author`, `text`, `tags`, `updated`) VALUES
('New Page', 'RFS', 'This is the incredible New Page that I added to my wiki.\r\n\r\nEnjoy!\r\n', '', '2010-04-05 10:24:24'),
('RFS Wiki Changes', 'RFS', '<h3>RFS Wiki History</h3><hr>1.0: Created. Able to edit simple text and link to other pages. [[page name]]\r\n\r\n1.1: Added Contents page, and create new page link\r\n\r\n1.2: Added images {{image filename}}\r\n\r\n1.3: Added image upload support \r\n\r\n1.4: Added bullet lists [[#BeginList]] & [[#EndList]]\r\n\r\n1.5: Added symbolic page links: [[@page,symbolic link]]', '', '2010-04-10 22:49:16'),
('RFS Wiki', 'RFS', '<h3>Really Frickin Simple Wiki editor</h3>{rfswiki.png}\r\n\r\nCurrent version is 1.5 (see [@RFS Wiki Changes,History])\r\n\r\nRFS Wiki is really simple. It is part of the [RFS Content Management System].\r\n\r\nSee [RFS Wiki Installation Guide] for a brief overview of how to install it.\r\n\r\nThis implementation of the RFS Wiki has some added functionality over the one available for download.\r\nSome of it is customized to this site, but some changes I have made will be added to the distributed version.\r\n\r\nMy plan is to add in an access system that will allow users to be given access to moderate pages which they are given access.\r\n\r\nYou can download the [PHP] script for RFS Wiki in the <a href=files.php class=rfswiki_link>Files</a> page of the main site.\r\n\r\nIf you would like to contribute to this wiki, please login and apply to become a wiki contributor.\r\n\r\n[Examples] page shows how to use this wiki. It is different than other wiki''s. In fact it is really frickin simple to use.\r\n', '', '2010-04-10 22:59:55'),
('RFS Content Management System', 'RFS', '<h3>RFS Content Management System</h3>RFS CMS consists of several smaller projects that have slowly evolved & merged together over time.\r\n\r\n[RFS Wiki]\r\n[@RFS Website Application Builder,RFS WAB Engine]\r\n', '', '2010-04-15 23:59:39'),
('RFS Website Application Builder', 'RFS', '<h3>RFS Website Application Builder (RFS WAB)</h3>A component of the [RFS Content Management System].\r\n\r\nThe WAB Engine uses [MySQL] and [PHP] to create ''living apps'' that can be managed entirely from the RFS web page.  You can easily create plug-ins for [@RFS Content Management System,RFS CMS] website.\r\n\r\nThe best way to describe how this WAB works is to think of it as a ''Web Site Application Wiki''.\r\n\r\nYou can create new links to functions that do not exist yet and when it encounters them, it asks you to fill in the code.\r\n\r\nThe forms you create will pass data to the new functions using PHP [$_REQUEST] superglobals.\r\n\r\nSee some <a href=http://www.defectiveminds.com/wab.php?runapp=1>Working examples</a> using the version of WAB Engine.\r\n\r\n<h3>Features:</h3>[#BeginList]\r\nCreate new pages for the website.\r\nStore functions in MySQL database.\r\nForm actions linked to function names.\r\nParse php files / edit them in a graphical style in the browser.\r\nManage tables in the website database in an easy way.\r\nCreate forms and functions that interact with the database in an easy way.\r\n[#EndList]\r\n\r\nThe forms used by WAB must have a hidden formdata called action. It will look for a function named by the action.\r\n\r\nFor example a form has the following hidden variable:\r\naction=eat_food\r\n\r\nWhen the page is returned it looks for the function:\r\nWAB_App_Name_action_eat_food();\r\n\r\nIf it doesn''t exists, it will ask you edit the code for it.\r\n\r\nMore to come as it develops.\r\n\r\n', '', '2012-11-29 09:54:40'),
('Examples', 'RFS', '<h3>RFS Wiki Examples Page</h3>{rfswiki.png}\r\nThis page shows you how to add stuff.\r\n\r\n<hr><h2>To add a page:</h2>Step 1:\r\nEdit a page (link below). Then to add a new page simply type the new page name surrounded by brackets. Like this:\r\n\r\n[[New Page]]\r\n\r\nIt will create a new page link like this one:\r\n\r\n[New Page]\r\n\r\nStep 2:\r\nClick on the link [New Page], it will give you instructions to edit the page.\r\n\r\n<hr><h2>To add an image:</h2>Step1:\r\nEdit a page, and add an image name surrounded by curly brackets. Like this:\r\n\r\n{{Imagename.png}}\r\n\r\nIt will create an image like this:\r\n\r\n{Imagename.png}\r\n\r\nStep 2:\r\nIf there is no image file available it will give you a link to upload a new image. Do it. Then you are done.\r\n\r\n<hr><h2>To add HTML code:</h2>Just add HTML as you normally would. It will not be changed. Certain HTML will not work, such as textarea tags. You shouldn''t need to use them here anyway.\r\n\r\n<hr><h2>To add a bullet list:</h2>[[#BeginList]]\r\nList entry 1\r\nList entry 2\r\nList entry 3\r\n[[#EndList]]\r\n\r\nThis will look like:\r\n[#BeginList]\r\nList entry 1\r\nList entry 2\r\nList entry 3\r\n[#EndList]\r\n\r\n<hr><h2>To make a symbolic link to another page:</h2>\r\n[[@My Fake Examples Link,Examples]]\r\n\r\n[@Examples,My Fake Examples Link]\r\n\r\n<hr>\r\n', '', '2010-04-10 22:39:33'),
('Home', 'RFS', '[@RFS Content Management System,RFS CMS]\r\n\r\nThis is the wiki page that is built into the RFS CMS system. See these [Examples] to learn how to use it. The concept behind this wiki page is to keep it as simple as possible while still allowing users to add robust content.\r\n\r\nThis is a work in progress, and currently  you could classify it as in beta. If you have any suggestions or would like to help work on this code please contact me.\r\n\r\nEnjoy!\r\n\r\n\r\n\r\n[Contents]', '', '2012-11-29 09:43:58');

INSERT INTO `wab_engine` (`name`, `id`, `parent`, `hidden`, `type`, `value`, `description`, `code`) VALUES
('wab_engine', 1, 1, 0, 'database', 'wab_engine', 'WAB Engine', ''),
('wab_engine', 56, 1, 1, 'function', 'wab_engine_action_show_snippet', NULL, 'echo \"wab engine\";');
