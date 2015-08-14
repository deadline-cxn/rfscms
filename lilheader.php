<?php
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
// check for config.php file
if(!file_exists("config/config.php")) { include("install/install.php"); exit(); }
// include all libraries (this will not output any text)
include_once("include/lib.all.php");
// check for site name definition
if(empty($RFS_SITE_NAME)) { include("install/install.php"); exit(); }
// housekeeping
lib_rfs_maintenance();
lib_debug_header(0);
// divert ajax requests
if(!empty($_REQUEST['action'])) {
    if(stristr($_REQUEST['action'],"lib_ajax_callback")) {
    	include("include/lib.all.php");
    	eval("$action();");
    	exit();
    }
}
$RFS_LITTLE_HEADER=true;
lib_log_count($data->name);
// include theme definition file (if it exists)
if( file_exists("$RFS_SITE_PATH/themes/$theme/t.php")) include("$RFS_SITE_PATH/themes/$theme/t.php");
// include theme header file (if it exists)
if( file_exists("$RFS_SITE_PATH/themes/$theme/t.header.php")) include("$RFS_SITE_PATH/themes/$theme/t.header.php");
// otherwise use the default header (this file)
else {
	lib_rfs_echo($RFS_SITE_DOC_TYPE);
	lib_rfs_echo($RFS_SITE_HTML_OPEN);
	lib_rfs_echo($RFS_SITE_HEAD_OPEN);    
	// get keywords from any search engine queries and put them in the seo output
	$keywords=$_GET['query'];
	if(empty($keywords)) $keywords=$_GET['q'];
	$keywords.=$RFS_SITE_SEO_KEYWORDS;	
	echo "<meta name=\"description\" 	content=\"$keywords\">";
	echo "<meta name=\"keywords\" 		content=\"$keywords\">";
	lib_rfs_echo($RFS_SITE_TITLE);
	if(file_exists("$RFS_SITE_PATH/themes/$theme/t.css"))
		echo "<link rel=\"stylesheet\" href=\"$RFS_SITE_URL/themes/$theme/t.css\" type=\"text/css\">\n";
	echo "<link rel=\"canonical\" href=\"".lib_domain_canonical_url()."\" />";
	lib_rfs_echo($RFS_SITE_HEAD_CLOSE);	
	lib_rfs_echo($RFS_SITE_BODY_OPEN);	

	if(!$RFS_DO_NOT_SHOW_MENU) {
		echo "<table border=0 width=100% class=rfs_top_menu_table cellpadding=0 cellspacing=0>";
		echo "<tr class=rfs_top_menu_table_td>";
		echo "<td class=rfs_top_menu_table_td valign=top>";
		echo "<table border=0 cellpadding=0 cellspacing=0 class=rfs_top_menu_table>";
		echo "<tr class=rfs_top_menu_table_td>";
		//echo "<td class=rfs_top_menu_table_td>";
		// echo "$RFS_SITE_NAME";
		// echo "</td>";
			lib_menus_draw($RFS_THEME_TOP_MENU_LOCATION);
		echo "<td class=rfs_top_menu_table_td>";
		// echo " : ";
		echo "</td>";
		echo "<td align=right class=rfs_top_menu_table_td>";
			echo "<table border=0 cellspacing=0 cellpadding=0><tr>\n";
			echo "<td class=rfs_top_menu_table_inner class=contenttd>";
				lib_forms_theme_select();
			echo "</td></tr></table>\n";
		echo "</td>";
		echo "<td class=rfs_top_menu_table_td>";
		// echo " : ";
		echo "</td>";
		echo "<td class=logged_in_td>";
		if($_SESSION["logged_in"]!="true")    {
			lib_rfs_echo($RFS_SITE_LOGIN_FORM_CODE);
			echo "</td><td class=logged_in_td>";
		}
		else    {
			echo "</td>";
			echo "<td class=logged_in_td>";
			lib_rfs_echo($RFS_SITE_LOGGED_IN_CODE);
		}
		echo "</td></tr></table>";
		echo "</td></tr></table>";
		
	if(empty($data->donated)) {
				lib_social_paypal();
		
		lib_social_google_adsense($RFS_SITE_GOOGLE_ADSENSE);		
		}
	}
}
//////////////////////////////////////////////
// Load javascripts
lib_ajax_javascript();
rfs_javascript();
//lib_rfs_echo($RFS_SITE_JS_MSDROPDOWN_THEME);
lib_rfs_echo($RFS_SITE_JS_JQUERY);
lib_rfs_echo($RFS_SITE_JS_COLOR);
lib_rfs_echo($RFS_SITE_JS_EDITAREA);
//if(!stristr(lib_domain_canonical_url(),"/net.php")) lib_rfs_echo($RFS_SITE_JS_MSDROPDOWN);
//////////////////////////////////////////////
// google analytics
lib_social_google_analytics();
//////////////////////////////////////////////
// count the page
lib_log_count($data->name);
//////////////////////////////////////////////
// system messages
lib_forms_system_message();
//////////////////////////////////////////////
// do action
lib_rfs_do_action();
?>
