<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
// check for config.php file
if(!file_exists("config/config.php")) { include("install/install.php"); exit(); }
// include all libraries (this will not output any text)
include_once("include/lib.all.php");
// check for site name definition
if(empty($RFS_SITE_NAME)) { include("install/install.php"); exit(); }
// housekeeping
sc_maintenance();
sc_debugheader(0);
// divert ajax requests
if(stristr($_REQUEST['action'],"sc_ajax_callback")) {
	include("include/lib.all.php");
	eval("$action();");
	exit();
}

// inlude theme definition file (if it exists)
if( file_exists("$RFS_SITE_PATH/themes/$theme/t.php")) include("$RFS_SITE_PATH/themes/$theme/t.php");
// include theme header file (if it exists)
if( file_exists("$RFS_SITE_PATH/themes/$theme/t.header.php")) include("$RFS_SITE_PATH/themes/$theme/t.header.php");
// otherwise use the default header (this file)
else {
	rfs_echo($RFS_SITE_DOC_TYPE);
	rfs_echo($RFS_SITE_HTML_OPEN);
	rfs_echo($RFS_SITE_HEAD_OPEN);    
	// get keywords from any search engine queries and put them in the seo output
	$keywords=$_GET['query'];
	if(empty($keywords)) $keywords=$_GET['q'];
	$keywords.=$RFS_SITE_SEO_KEYWORDS;	
	echo "<meta name=\"description\" 	content=\"$keywords\">";
	echo "<meta name=\"keywords\" 		content=\"$keywords\">";
	rfs_echo($RFS_SITE_TITLE);
	if(file_exists("$RFS_SITE_PATH/themes/$theme/t.css"))
		echo "<link rel=\"stylesheet\" href=\"$RFS_SITE_URL/themes/$theme/t.css\" type=\"text/css\">\n";
	echo "<link rel=\"canonical\" href=\"".sc_canonical_url()."\" />";
	rfs_echo($RFS_SITE_HEAD_CLOSE);	
	rfs_echo($RFS_SITE_BODY_OPEN);	
}
echo "<div>";
sc_menu_draw($RFS_SITE_TOP_MENU_LOCATION);
echo "</div>";
//////////////////////////////////////////////
// Load javascripts

sc_ajax_javascript();
sc_javascript();

rfs_echo($RFS_SITE_JS_MSDROPDOWN_THEME);
rfs_echo($RFS_SITE_JS_JQUERY);
rfs_echo($RFS_SITE_JS_COLOR);
rfs_echo($RFS_SITE_JS_EDITAREA);

if(!stristr(sc_canonical_url(),"/net.php"))
rfs_echo($RFS_SITE_JS_MSDROPDOWN);

//////////////////////////////////////////////
// google analytics
sc_google_analytics();
//////////////////////////////////////////////
// count the page
sc_mcount($data->name);
//////////////////////////////////////////////
// system messages
sc_system_message();
?>
