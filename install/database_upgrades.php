<?
// Interim Database Changes. These changes will be rotated out into the install script
// INITIAL UPDATES
if(empty($RFS_SITE_DATABASE_UPGRADE)) {
sc_database_add("rfsauth","name","text","NOT NULL");
sc_database_add("rfsauth","enabled","text","NOT NULL");
sc_database_add("rfsauth","value","text","NOT NULL");
sc_database_add("rfsauth","value2","text","NOT NULL");

$id =	sc_database_data_add("rfsauth","name","EBSR",0);
		sc_database_data_add("rfsauth","enabled","true",$id);
		sc_database_data_add("rfsauth","value","",$id);
		
$id =	sc_database_data_add("rfsauth","name","FACEBOOK",0);
		sc_database_data_add("rfsauth","enabled","false",$id);
		sc_database_data_add("rfsauth","value","",$id);
		sc_database_data_add("rfsauth","value2","",$id);
		
$id =	sc_database_data_add("rfsauth","name","OPENID",0);
		sc_database_data_add("rfsauth","enabled","false",$id);
		sc_database_data_add("rfsauth","value","",$id);
		
sc_database_add("users","downloads", "text", "NOT NULL");
sc_database_add("users","uploads", "text", "NOT NULL");
sc_database_add("users","donated", "text", "NOT NULL");
sc_query("ALTER TABLE `site_vars` ADD `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");
sc_query("ALTER TABLE `site_vars` ADD `desc` TEXT");
sc_query("ALTER TABLE `site_vars` ADD `type` TEXT");
sc_query("ALTER TABLE `menu_top` ADD `access_method` TEXT");
sc_query("ALTER TABLE `menu_top` ADD `other_requirement` TEXT");
sc_query("ALTER TABLE `menu_top` DROP `access`");
sc_query("ALTER TABLE `menu_top` DROP `other_requirements`");
sc_query("update `menu_top` set `access_method`='admin,access' where `name`='Admin'");
sc_query("update `menu_top` set `other_requirement`='loggedin=true' where `name`='Profile'");
sc_database_add("categories","worksafe", "text", "NOT NULL");
sc_database_data_add("categories","name","unsorted",0);
// MD5 hash
sc_database_add("files","md5", "text", "NOT NULL");
sc_database_add("files","tags","text", "NOT NULL");
sc_database_add("files","ignore","text", "NOT NULL");
// Duplicates table
sc_database_add("file_duplicates", "loc1", "text", "NOT NULL");
sc_database_add("file_duplicates", "size1", "text", "NOT NULL");
sc_database_add("file_duplicates", "loc2", "text", "NOT NULL");
sc_database_add("file_duplicates", "size2", "text", "NOT NULL");
sc_database_add("file_duplicates", "md5", "text", "NOT NULL");
sc_database_add("wiki","name",   		"text","NOT NULL");
sc_database_add("wiki","revision",		"int",	"NOT NULL");
sc_database_add("wiki","revised_by",	"text","NOT NULL");
sc_database_add("wiki","revision_note","text","NOT NULL");
sc_database_add("wiki","author", 		"text","NOT NULL");
sc_database_add("wiki","text",   		"text","NOT NULL");
sc_database_add("wiki","tags",   		"text","NOT NULL");
sc_database_add("wiki","updated",		"timestamp","ON UPDATE CURRENT_TIMESTAMP NOT NULL");
sc_touch_dir("$RFS_SITE_PATH/images/wiki");
sc_touch_dir("$RFS_SITE_PATH/images/news");
sc_database_add("news","name",		"text",	"NOT NULL");
sc_database_add("news","headline",	"text",	"NOT NULL");
sc_database_add("news","message",	"text",	"NOT NULL");
sc_database_add("news","category1","text",	"NOT NULL");
sc_database_add("news","submitter","int",		"NOT NULL DEFAULT '0'");
sc_database_add("news","time",		"timestamp","NOT NULL");
sc_database_add("news","lastupdate","timestamp","ON UPDATE CURRENT_TIMESTAMP NOT NULL");
sc_database_add("news","image_url","text",	"NOT NULL");
sc_database_add("news","image_link","text",	"NOT NULL");
sc_database_add("news","image_alt","text",	"NOT NULL");
sc_database_add("news","topstory",	"text",	"NOT NULL");
sc_database_add("news","published","text",	"NOT NULL");
sc_database_add("news","views",		"int",		"NOT NULL DEFAULT '0'");
sc_database_add("news","rating",	"text",	"NOT NULL");
sc_database_add("news","sfw",		"text",	"NOT NULL");
sc_database_add("news","page",		"int",		"NOT NULL");
sc_database_add("news","wiki",		"text",	"NOT NULL");
sc_query( "CREATE TABLE IF NOT EXISTS `pmsg` (`id` int(11) NOT NULL AUTO_INCREMENT,`to` text NOT NULL, `from` text NOT NULL, `subject` text NOT NULL, `message` text NOT NULL, `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',`read` text NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=149 ; ");
}

if($RFS_SITE_DATABASE_UPGRADE<887) {
	sc_database_add("site_vars","type","text","not null");
	sc_database_add("menu_top","access","text","not null");
}

$RFS_SITE_DATABASE_UPGRADE=$RFS_BUILD;
install_database_upgrades_update($RFS_SITE_DATABASE_UPGRADE);

function install_database_upgrades_update($x) {
	$dbu=mfo1("select * from site_vars where name='database_upgrade'");
	if(empty($dbu->id)) sc_query("insert into site_vars (`name`,`value`) values('database_upgrade','$x');");
	else sc_query("update site_vars set `value` = '$x' where `name`='database_upgrade'");
}



?>