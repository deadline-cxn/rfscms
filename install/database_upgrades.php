<?
// Interim Database Changes. These changes will be rotated out into the install script
$a=intval($RFS_SITE_DATABASE_UPGRADE);
$b=intval($RFS_BUILD);
if(empty($RFS_SITE_DATABASE_UPGRADE)) {	
	
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
}

if($a<889) {
	lib_mysql_add("site_vars","type","text","not null");
	lib_mysql_add("menu_top","access_method","text","not null");
}
if($a<890) {
	lib_mysql_add("menu_top","access_method","text","not null");
	lib_mysql_add("menu_top","other_requirement","text","not null");
}
if($a<891) {
	lib_mysql_add("site_vars","desc","text","not null");
}
if($a<901) {
	lib_access_add_method("debug", "view");
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

	lib_access_add_method("todo_list", "add");	
}


if($a<923) {
	lib_file_touch_dir("$RFS_SITE_PATH/modules/videos/cache");
}

if($a<925) {
	lib_access_add_method("admin", "access");
	lib_access_add_method("admin", "categories");
	$r=lib_mysql_query("select * from categories");
	while($cat=mysql_fetch_object($r)){ 
		lib_mysql_query("update pictures set `category` = '$cat->name' where `category` = '$cat->id'");
		if($cat->worksafe=="no") {
			lib_mysql_query("update files set worksafe='no' where worksafe!='no and category = '$cat->name'");
			lib_mysql_query("update pictures set worksafe='no' where category = '$cat->name'");
		}
	}
}

if($a<932) {
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
}

if($a < $b) {
	$RFS_SITE_DATABASE_UPGRADE=intval($RFS_BUILD);
	$dbu=lib_mysql_fetch_one_object("select * from site_vars where name='database_upgrade'");
	if(empty($dbu->id)) lib_mysql_query("insert into site_vars (`name`,`value`) values('database_upgrade','$RFS_SITE_DATABASE_UPGRADE');");
	else lib_mysql_query("update site_vars set `value` = '$RFS_SITE_DATABASE_UPGRADE' where `name`='database_upgrade'");
	echo "Added interim database changes $RFS_SITE_DATABASE_UPGRADE<br>";
}


?>
