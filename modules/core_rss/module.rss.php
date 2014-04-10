<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
// RSS CORE MODULE
/////////////////////////////////////////////////////////////////////////////////////////
include_once("include/lib.all.php");

$RFS_ADDON_NAME="rss";
$RFS_ADDON_VERSION="1.0.0";
$RFS_ADDON_SUB_VERSION="0";
$RFS_ADDON_RELEASE="";
$RFS_ADDON_DESCRIPTION="RFSCMS RSS";
$RFS_ADDON_REQUIREMENTS="";
$RFS_ADDON_COST="";
$RFS_ADDON_LICENSE="";
$RFS_ADDON_DEPENDENCIES="";
$RFS_ADDON_AUTHOR="Seth T. Parson";
$RFS_ADDON_AUTHOR_EMAIL="seth.parson@rfscms.org";
$RFS_ADDON_AUTHOR_WEBSITE="http://rfscms.org/";
$RFS_ADDON_IMAGES="";
$RFS_ADDON_FILE_URL="";
$RFS_ADDON_GIT_REPOSITORY="";
$RFS_ADDON_URL=lib_modules_get_base_url_from_file(__FILE__);

lib_menus_register("RSS Feeds","$RFS_SITE_URL/modules/core_rss/rss.php");

/////////////////////////////////////////////////////////////////////////////////////////////////////////
// MODULE RSS
function m_panel_rss() { eval(lib_rfs_get_globals());
	
	echo "<h2>News from around the world</h2>";
    include("$RFS_SITE_PATH/3rdparty/rsslib/rsslib.php");    
    $result=lib_mysql_query("select * from rss_feeds");
    $num_feeds=mysql_num_rows($result);
    for($i=0;$i<$num_feeds;$i++){
    	$feed=mysql_fetch_object($result);
    	echo RSS_display($feed->feed, 3, false);
    }    
}

?>
