<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////
// Interim Database Changes. These changes will be rotated out into the install script

$a=intval($RFS_SITE_DATABASE_UPGRADE);
$b=intval($RFS_BUILD);

if($a<700) {	

	lib_mysql_add("rfsauth","name","text","NOT NULL");
	lib_mysql_add("rfsauth","enabled","text","NOT NULL");
	lib_mysql_add("rfsauth","value","text","NOT NULL");
	lib_mysql_add("rfsauth","value2","text","NOT NULL");

	$id =	lib_mysql_data_add("rfsauth","name","EBSR",0);
			lib_mysql_data_add("rfsauth","enabled","true",$id);
			lib_mysql_data_add("rfsauth","value","",$id);
			
	$id =	lib_mysql_data_add("rfsauth","name","FACEBOOK",0);
			lib_mysql_data_add("rfsauth","enabled","false",$id);
			lib_mysql_data_add("rfsauth","value","",$id);
			lib_mysql_data_add("rfsauth","value2","",$id);
			
	$id =	lib_mysql_data_add("rfsauth","name","OPENID",0);
			lib_mysql_data_add("rfsauth","enabled","false",$id);
			lib_mysql_data_add("rfsauth","value","",$id);
			
	lib_mysql_add("users","downloads", "text", "NOT NULL");
	lib_mysql_add("users","uploads", "text", "NOT NULL");
	lib_mysql_add("users","donated", "text", "NOT NULL");
	lib_mysql_query("ALTER TABLE `site_vars` ADD `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");
	lib_mysql_query("ALTER TABLE `site_vars` ADD `desc` TEXT");
	lib_mysql_query("ALTER TABLE `site_vars` ADD `type` TEXT");
	lib_mysql_query("ALTER TABLE `menu_top` ADD `access_method` TEXT");
	lib_mysql_query("ALTER TABLE `menu_top` ADD `other_requirement` TEXT");
	lib_mysql_query("ALTER TABLE `menu_top` DROP `access`");
	lib_mysql_query("ALTER TABLE `menu_top` DROP `other_requirements`");
	lib_mysql_query("update `menu_top` set `access_method`='admin,access' where `name`='Admin'");
	lib_mysql_query("update `menu_top` set `other_requirement`='loggedin=true' where `name`='Profile'");
	lib_mysql_add("categories","worksafe", "text", "NOT NULL");
	lib_mysql_data_add("categories","name","unsorted",0);
	// MD5 hash
	lib_mysql_add("files","md5", "text", "NOT NULL");
	lib_mysql_add("files","tags","text", "NOT NULL");
	lib_mysql_add("files","ignore","text", "NOT NULL");
	// Duplicates table
	lib_mysql_add("file_duplicates", "loc1", "text", "NOT NULL");
	lib_mysql_add("file_duplicates", "size1", "text", "NOT NULL");
	lib_mysql_add("file_duplicates", "loc2", "text", "NOT NULL");
	lib_mysql_add("file_duplicates", "size2", "text", "NOT NULL");
	lib_mysql_add("file_duplicates", "md5", "text", "NOT NULL");
	lib_mysql_add("wiki","name",   		"text","NOT NULL");
	lib_mysql_add("wiki","revision",		"int",	"NOT NULL");
	lib_mysql_add("wiki","revised_by",	"text","NOT NULL");
	lib_mysql_add("wiki","revision_note","text","NOT NULL");
	lib_mysql_add("wiki","author", 		"text","NOT NULL");
	lib_mysql_add("wiki","text",   		"text","NOT NULL");
	lib_mysql_add("wiki","tags",   		"text","NOT NULL");
	lib_mysql_add("wiki","updated",		"timestamp","ON UPDATE CURRENT_TIMESTAMP NOT NULL");
	lib_file_touch_dir("$RFS_SITE_PATH/images/wiki");
	lib_file_touch_dir("$RFS_SITE_PATH/images/news");
	lib_mysql_add("news","name",		"text",	"NOT NULL");
	lib_mysql_add("news","headline",	"text",	"NOT NULL");
	lib_mysql_add("news","message",	"text",	"NOT NULL");
	lib_mysql_add("news","category1","text",	"NOT NULL");
	lib_mysql_add("news","submitter","int",		"NOT NULL DEFAULT '0'");
	lib_mysql_add("news","time",		"timestamp","NOT NULL");
	lib_mysql_add("news","lastupdate","timestamp","ON UPDATE CURRENT_TIMESTAMP NOT NULL");
	lib_mysql_add("news","image_url","text",	"NOT NULL");
	lib_mysql_add("news","image_link","text",	"NOT NULL");
	lib_mysql_add("news","image_alt","text",	"NOT NULL");
	lib_mysql_add("news","topstory",	"text",	"NOT NULL");
	lib_mysql_add("news","published","text",	"NOT NULL");
	lib_mysql_add("news","views",		"int",		"NOT NULL DEFAULT '0'");
	lib_mysql_add("news","rating",	"text",	"NOT NULL");
	lib_mysql_add("news","sfw",		"text",	"NOT NULL");
	lib_mysql_add("news","page",		"int",		"NOT NULL");
	lib_mysql_add("news","wiki",		"text",	"NOT NULL");
	lib_mysql_query( "CREATE TABLE IF NOT EXISTS `pmsg` (`id` int(11) NOT NULL AUTO_INCREMENT,`to` text NOT NULL, `from` text NOT NULL, `subject` text NOT NULL, `message` text NOT NULL, `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',`read` text NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=149 ; ");
	$logtext="Added interim database changes 700";
	lib_log_add_entry($logtext);	
}

if($a<889) {
	lib_mysql_add("site_vars","type","text","not null");
	lib_mysql_add("menu_top","access_method","text","not null");
	$logtext="Added interim database changes 889";
	lib_log_add_entry($logtext);
}
if($a<890) {
	lib_mysql_add("menu_top","access_method","text","not null");
	lib_mysql_add("menu_top","other_requirement","text","not null");
	$logtext="Added interim database changes 890";
	lib_log_add_entry($logtext);
}
if($a<891) {
	lib_mysql_add("site_vars","desc","text","not null");
	$logtext="Added interim database changes 891";
	lib_log_add_entry($logtext);
}
if($a<901) {
	
	$logtext="Added interim database changes 901";
	lib_log_add_entry($logtext);
}

if($a<903) {
	lib_mysql_add("todo_list","name","text","not null");
	lib_mysql_add("todo_list","description","text","not null");
	lib_mysql_add("todo_list","assigned_to","text","not null");
	lib_mysql_add("todo_list","assigned_to_group","text","not null");
	lib_mysql_add("todo_list","public","text","not null");
	lib_mysql_add("todo_list","owner","text","not null");
	lib_mysql_add("todo_list","type","text","not null");

	lib_mysql_add("todo_list_task","name","text","not null");
	lib_mysql_add("todo_list_task","list","text","not null");
	lib_mysql_add("todo_list_task","priority","text","not null");
	lib_mysql_add("todo_list_task","description","text","not null");
	lib_mysql_add("todo_list_task","resolve_action","text","not null");
	lib_mysql_add("todo_list_task","step","text","not null");
	lib_mysql_add("todo_list_task","status","text","not null");
	lib_mysql_add("todo_list_task","opened","timestamp","DEFAULT CURRENT_TIMESTAMP");
	lib_mysql_add("todo_list_task","opened_by","text","not null");
	lib_mysql_add("todo_list_task","due","timestamp","not null");
	lib_mysql_add("todo_list_task","closed","timestamp","not null");
	lib_mysql_add("todo_list_task","closed_by","text","not null");	

	lib_mysql_add("todo_list_status","name","text","not null");
	lib_mysql_data_add("todo_list_status","name","Open","");
	lib_mysql_data_add("todo_list_status","name","In Progress","");
	lib_mysql_data_add("todo_list_status","name","Resolved","");
	lib_mysql_data_add("todo_list_status","name","Closed","");

	lib_mysql_add("todo_list_type","name","text","not null");
	lib_mysql_data_add("todo_list_type","name","Personal","");
	lib_mysql_data_add("todo_list_type","name","Bug","");
	lib_mysql_data_add("todo_list_type","name","Task","");

	
	$logtext="Added interim database changes 903";
	lib_log_add_entry($logtext);	
}


if($a<925) {

	$r=lib_mysql_query("select * from categories");
	while($cat=$r->fetch_object()){ 
		lib_mysql_query("update pictures set `category` = '$cat->name' where `category` = '$cat->id'");
		if($cat->worksafe=="no") {
			lib_mysql_query("update files set worksafe='no' where worksafe!='no and category = '$cat->name'");
			lib_mysql_query("update pictures set worksafe='no' where category = '$cat->name'");
		}
	}
	$logtext="Added interim database changes 925";
	lib_log_add_entry($logtext);
}

if($a<932) {	
	$logtext="Added interim database changes 932";
	lib_log_add_entry($logtext);
}
if($a<940) {
	
	$logtext="Added interim database changes 940";
	lib_log_add_entry($logtext);
}

if($a<956) {
	
	lib_mysql_add("videos","embed_code","text","not null");
	$r=lib_mysql_query("select * from videos");
	while($v=$r->fetch_object()) {
		if(empty($v->embed_code)) {
			$v->embed_code=$v->url;
			lib_mysql_query("update videos set `embed_code`='$v->embed_code' where id='$v->id'");
		}
		else {
			$url=videos_get_url_from_code($v->embed_code);
			lib_mysql_query("update videos set `url`='$url' where id='$v->id'");
		}
		if(is_numeric($v->category)) {
			$c=lib_mysql_fetch_one_object("select * from categories where id='$v->category'");
			lib_mysql_query("update videos set `category`='$c->name' where id='$v->id'");
		}
	}
	$logtext="Added interim database changes 955";
	lib_log_add_entry($logtext);
}

if($a<964) {
	
	lib_mysql_add("addon_database","name","text","not null");
	lib_mysql_add("addon_database","datetime_added","timestamp","NOT NULL");
	lib_mysql_add("addon_database","datetime_updated","timestamp","NOT NULL");
	lib_mysql_add("addon_database","version","text","not null");
	lib_mysql_add("addon_database","sub_version","text","not null");
	lib_mysql_add("addon_database","release","text","not null");
	lib_mysql_add("addon_database","description","text","not null");
	lib_mysql_add("addon_database","requirements","text","not null");
	lib_mysql_add("addon_database","cost","text","not null");
	lib_mysql_add("addon_database","license","text","not null");
	lib_mysql_add("addon_database","dependencies","text","not null");
	lib_mysql_add("addon_database","author","text","not null");
	lib_mysql_add("addon_database","author_email","text","not null");
	lib_mysql_add("addon_database","author_website","text","not null");
	lib_mysql_add("addon_database","rating","text","not null");
	lib_mysql_add("addon_database","images","text","not null");
	$logtext="Added interim database changes 964";
	lib_log_add_entry($logtext);
}

if($a<984) {
    $r=lib_mysql_query("select * from menu_top");
    while($link=$r->fetch_object()) {
        if(!strstr($link->link,"rfs")) {
            $link->link = str_replace("modules/","modules/core_",$link->link);
            $link->link = str_replace("core_core_","core_",$link->link);
            $link->link = str_replace("core_core_","core_",$link->link);            
            lib_mysql_query("update menu_top set `link`='$link->link' where `id`='$link->id'");
        }
    }

    $r=lib_mysql_query("select * from admin_menu");
    while($link=$r->fetch_object()) {
        if(!strstr($link->url,"rfs")) {
            $link->url = str_replace("modules/","modules/core_",$link->url);
            $link->url = str_replace("core_core_","core_",$link->url);
            $link->url = str_replace("core_core_","core_",$link->url);            
            lib_mysql_query("update admin_menu set `url`='$link->url' where `id`='$link->id'");
        }
    }    
    lib_file_touch_dir("$RFS_SITE_PATH/modules/core_videos/cache");
    $logtext="Added interim database changes 984";
	lib_log_add_entry($logtext);
}
if($a<986) {
	mysql_query("ALTER TABLE `files` CHANGE `lastupdate` `lastupdate` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;");
	$logtext="Added interim database changes 986";
	lib_log_add_entry($logtext);
}
if($a<1053) {
	lib_mysql_add("arrangement","type","text","not null");
	lib_mysql_add("arrangement","tableref","text","not null");
	lib_mysql_add("arrangementid","tableref","text","not null");
	lib_mysql_add("arrangement","access","text","not null");
	lib_mysql_add("arrangement","page","text","not null");
	$logtext="Added interim database changes 1053";
	lib_log_add_entry($logtext);
}
if($a<1065) {
	lib_mysql_add("site_var_types","name","text","not null");
	lib_mysql_add("site_var_types","table","text","not null");
	lib_mysql_add("site_var_types","key","text","not null");
	lib_mysql_add("site_var_types","other","text","not null");
	
	lib_mysql_query("insert into `site_var_types` (`name`,`table`,`key`,`other`) values ('text','','','');");
	lib_mysql_query("insert into `site_var_types` (`name`,`table`,`key`,`other`) values ('bool','','','on,off');");
	lib_mysql_query("insert into `site_var_types` (`name`,`table`,`key`,`other`) values ('theme','','','');");
	lib_mysql_query("insert into `site_var_types` (`name`,`table`,`key`,`other`) values ('file','','','');");
	lib_mysql_query("insert into `site_var_types` (`name`,`table`,`key`,`other`) values ('menu_location','','','');");
	lib_mysql_query("insert into `site_var_types` (`name`,`table`,`key`,`other`) values ('picture','pictures','name','');");
	lib_mysql_query("insert into `site_var_types` (`name`,`table`,`key`,`other`) values ('video','videos','name','');");
	
	lib_mysql_add("site_vars_available","var","text","not null");
	lib_mysql_add("site_vars_available","type","text","not null");
	lib_mysql_add("site_vars_available","description","text","not null");
					
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`) values ('LOCALE','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('OS','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('PATH_SEP','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('HEAD','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('FONT','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('NAV_IMG_TOP','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('URL','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('DELIMITER','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('SUDO_CMD','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('PATH','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('CHECK_UPDATE','bool','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('DEFAULT_THEME','theme','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('FORCE_THEME','bool','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('FORCED_THEME','theme','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('SESSION_ID','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('SESSION_USER','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('ADMIN','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('ADMIN_EMAIL','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('SLOGAN','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('URL','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('ERROR_LOG','file','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('THEME_DROPDOWN','bool','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('ADDTHIS_ACCT','text','');");	 
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('FACEBOOK_APP_ID','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('FACEBOOK_SECRET','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('FACEBOOK_SDK','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('FACEBOOK_NEWS_COMMENTS','bool','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('FACEBOOK_WIKI_COMMENTS','bool','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('ALLOW_FREE_DOWNLOADS','bool','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('MENU_TOP_LOCATION','menu_location','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('MENU_LEFT_LOCATION','menu_location','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('FOOTER','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('COPYRIGHT','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('JOIN_FORM_CODE','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('LOGIN_FORM_CODE','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('LOGGED_IN_CODE','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('JS_JQUERY','file','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('JS_COLOR','file','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('JS_MOOTOOLS','file','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('JS_EDITAREA','file','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('JS_MSDROPDOWN','file','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('JS_MSDROPDOWN_THEME','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('TITLE','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('NAME','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('SEO_KEYWORDS','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('DOC_TYPE','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('HTML_OPEN','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('HEAD_OPEN','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('HEAD_CLOSE','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('BODY_OPEN','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('BODY_CLOSE','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('HTML_CLOSE','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('GOOGLE_ADSENSE','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('PAYPAL_BUTTON1','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('PAYPAL_BUTTON1_MSG','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('PAYPAL_BUTTON2','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('PAYPAL_BUTTON2_MSG','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('SHOW_SOCIALS','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('ADDTHIS_ACCT','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('GOOGLE_ANALYTICS','text','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('GALLERIAS','bool','');");
	lib_mysql_query("insert into `site_vars_available` (`var`,`type`,`description`)	values ('CAPTIONS','bool','');");
	
	$logtext="Added interim database changes 1065";
	lib_log_add_entry($logtext);
}

if($a<1067) {
    lib_mysql_query("ALTER TABLE arrangement CHANGE mini panel text");
    $logtext="Added interim database changes 1067";
	lib_log_add_entry($logtext);
}

if($a<1073) {
	lib_mysql_add("forum_posts","locked","text","not null");
	$logtext="Added interim database changes 1073";
	lib_log_add_entry($logtext);
}

if($a<1077) {
	lib_mysql_query("alter table static_html add column name text not null");
	$logtext="Added interim database changes 1078";
	lib_log_add_entry($logtext);
}

if($a<1078) {
    lib_mysql_add("panel_types","name","text","not null");
	lib_mysql_add("panel_types","table","text","not null");
	lib_mysql_add("panel_types","key","text","not null");
	lib_mysql_add("panel_types","other","text","not null");
	lib_mysql_query("insert into `panel_types` (`name`,`table`,`key`,`other`) values ('results','','','');");
	lib_mysql_query("insert into `panel_types` (`name`,`table`,`key`,`other`) values ('eval','','','');");
	lib_mysql_query("insert into `panel_types` (`name`,`table`,`key`,`other`) values ('static','','','');");
    $logtext="Added interim database changes 1078";
	lib_log_add_entry($logtext);
}
if($a<1103) {
	lib_mysql_add("users","forumposts","text","not null");
	lib_mysql_add("users","forumreplies","text","not null");
	$logtext="Added interim database changes 1103";
	lib_log_add_entry($logtext);
}

if($a<1107) {
	lib_mysql_query("ALTER TABLE videos CHANGE poster contributor text");
	$logtext="Added interim database changes 1107";
	lib_log_add_entry($logtext);
}

if($a<1124) {
	lib_mysql_query("ALTER TABLE wiki ADD id INT(11)AUTO_INCREMENT PRIMARY KEY");
	$logtext="Added interim database changes 1124";
	lib_log_add_entry($logtext);
}

if($a<1127) {
	
	lib_mysql_add("addon_database","file_url","text","not null");	
	lib_mysql_add("addon_database","git_repository","text","NOT NULL");
	lib_mysql_add("addon_database","core","text","not null");
	$logtext="Added interim database changes 1127";
	lib_log_add_entry($logtext);
}

if($a<1166) {
	lib_mysql_query("
	CREATE TABLE IF NOT EXISTS `users` (
	  `name` text COLLATE utf8_unicode_ci NOT NULL,
	  `alias` text COLLATE utf8_unicode_ci NOT NULL,
	  `name_shown` text COLLATE utf8_unicode_ci NOT NULL,
	  `donated` text COLLATE utf8_unicode_ci NOT NULL,
	  `pass` text COLLATE utf8_unicode_ci NOT NULL,
	  `real_name` text COLLATE utf8_unicode_ci NOT NULL,
	  `facebook_id` text COLLATE utf8_unicode_ci NOT NULL,
	  `facebook_name` text COLLATE utf8_unicode_ci NOT NULL,
	  `first_name` text COLLATE utf8_unicode_ci NOT NULL,
	  `last_name` text COLLATE utf8_unicode_ci NOT NULL,
	  `facebook_link` text COLLATE utf8_unicode_ci NOT NULL,
	  `timezone` text COLLATE utf8_unicode_ci NOT NULL,
	  `locale` text COLLATE utf8_unicode_ci NOT NULL,
	  `country` text COLLATE utf8_unicode_ci NOT NULL,
	  `gender` text COLLATE utf8_unicode_ci NOT NULL,
	  `email` text COLLATE utf8_unicode_ci NOT NULL,
	  `paypal_email` text COLLATE utf8_unicode_ci NOT NULL,
	  `webpage` text COLLATE utf8_unicode_ci NOT NULL,
	  `avatar` text COLLATE utf8_unicode_ci NOT NULL,
	  `picture` text COLLATE utf8_unicode_ci NOT NULL,
	  `posts` int(11) NOT NULL DEFAULT '0',
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `show_flash` text COLLATE utf8_unicode_ci NOT NULL,
	  `website_fav` text COLLATE utf8_unicode_ci NOT NULL,
	  `sentence` text COLLATE utf8_unicode_ci NOT NULL,
	  `first_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `reporter` text COLLATE utf8_unicode_ci NOT NULL,
	  `show_contact_info` text COLLATE utf8_unicode_ci NOT NULL,
	  `upload` text COLLATE utf8_unicode_ci NOT NULL,
	  `files_uploaded` int(11) NOT NULL DEFAULT '0',
	  `files_downloaded` int(11) NOT NULL DEFAULT '0',
	  `last_activity` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
	  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `birthday` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	  `access` int(11) NOT NULL DEFAULT '0',
	  `forumposts` int(11) NOT NULL DEFAULT '0',
	  `forumreplies` int(11) NOT NULL DEFAULT '0',
	  `videowall` text COLLATE utf8_unicode_ci NOT NULL,
	  `theme` text COLLATE utf8_unicode_ci NOT NULL,
	  `referrals` int(11) NOT NULL DEFAULT '0',
	  `comments` int(11) NOT NULL DEFAULT '0',
	  `linksadded` int(11) NOT NULL DEFAULT '0',
	  `logins` int(11) NOT NULL DEFAULT '0',
	  UNIQUE KEY `id` (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1005 ;
	");
	lib_mysql_query("ALTER TABLE `users` ADD `facebook_id` text NOT NULL;");
	lib_mysql_query("ALTER TABLE `users` ADD `facebook_username` text NOT NULL;");
	lib_mysql_query("ALTER TABLE `users` ADD `facebook_name` text NOT NULL;");
	lib_mysql_query("ALTER TABLE `users` ADD `first_name` text NOT NULL;");
	lib_mysql_query("ALTER TABLE `users` ADD `last_name` text NOT NULL;");
	lib_mysql_query("ALTER TABLE `users` ADD `facebook_link` text NOT NULL;");
	lib_mysql_query("ALTER TABLE `users` ADD `timezone` text NOT NULL;");
	lib_mysql_query("ALTER TABLE `users` ADD `locale` text NOT NULL;");
	lib_mysql_query("ALTER TABLE `users` ADD `country` text NOT NULL;");
	lib_mysql_query("ALTER TABLE `users` ADD `gender` text NOT NULL;");
	lib_mysql_query("ALTER TABLE `users` ADD `email` text NOT NULL;");
	lib_mysql_query("ALTER TABLE `users` ADD `paypal_email` text NOT NULL;");
	lib_mysql_query("ALTER TABLE `users` ADD `first_login` timestamp NOT NULL;");
}

if($a<1203) {
	lib_mysql_query("ALTER TABLE `access_methods` CHANGE `action` `paction` text");
	lib_mysql_query("ALTER TABLE `access` CHANGE `action` `paction` text");
	lib_mysql_query("ALTER TABLE `access` CHANGE `table` `ptable` text");
}

if($a<1204) {
	lib_access_add_method("linkbin","edit");
	lib_access_add_method("linkbin","add");
	lib_access_add_method("linkbin","delete");	
	lib_access_add_method("debug", "view");
	lib_access_add_method("todo_list", "add");
	lib_access_add_method("admin", "access");
	lib_access_add_method("admin", "categories");
	lib_access_add_method("memes", "upload");
	lib_access_add_method("memes", "edit");
	lib_access_add_method("memes", "delete");
	lib_access_add_method("pictures", "orphanscan");
	lib_access_add_method("pictures", "upload");
	lib_access_add_method("pictures", "edit");
	lib_access_add_method("pictures", "delete");
	lib_access_add_method("pictures", "sort");
	lib_access_add_method("files", "upload");
	lib_access_add_method("files", "addlink");
	lib_access_add_method("files", "orphanscan");
	lib_access_add_method("files", "purge");
	lib_access_add_method("files", "sort");
	lib_access_add_method("files", "edit");
	lib_access_add_method("files", "delete");
	lib_access_add_method("files", "xplorer");
	lib_access_add_method("files", "xplorershell");	
	lib_access_add_method("forums", "admin");
	lib_access_add_method("forums", "add");
	lib_access_add_method("forums", "edit");
	lib_access_add_method("forums", "delete");
	lib_access_add_method("forums", "moderate");
	lib_access_add_method("news", "edit");
	lib_access_add_method("news", "editothers");
	lib_access_add_method("news", "submit");
	lib_access_add_method("news", "delete");
	lib_access_add_method("news", "deleteothers");	
	lib_access_add_method("videos", "submit");
	lib_access_add_method("videos", "edit");
	lib_access_add_method("videos", "editothers");
	lib_access_add_method("videos", "delete");
	lib_access_add_method("videos", "deleteothers");	

	$logtext="Added interim database changes 1204";
	lib_log_add_entry($logtext);	
}
if($a<1216) {
	lib_mysql_data_add("categories","name","Live Streams",0);
	$logtext="Added interim database changes 1216";
	lib_log_add_entry($logtext);	
}

if($a < $b) {
	lib_forms_inform("Database upgraded from $a to $b<br>");
	$RFS_SITE_DATABASE_UPGRADE=intval($RFS_BUILD);
	$dbu=lib_mysql_fetch_one_object("select * from site_vars where name='database_upgrade'");
	if(empty($dbu->id)) lib_mysql_query("insert into site_vars (`name`,`value`) values('database_upgrade','$RFS_SITE_DATABASE_UPGRADE');");
	else lib_mysql_query("update site_vars set `value` = '$RFS_SITE_DATABASE_UPGRADE' where `name`='database_upgrade'");
	$logtext="Added interim database changes $RFS_SITE_DATABASE_UPGRADE";	
	lib_log_add_entry($logtext);
}

?>