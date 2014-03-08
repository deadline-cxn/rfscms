<?
include_once("include/lib.all.php");

lib_menus_register("RSS Feeds","$RFS_SITE_URL/modules/rss/rss.php");

/////////////////////////////////////////////////////////////////////////////////////////////////////////
// MODULE RSS
function sc_module_mini_rss() { eval(lib_rfs_get_globals());
	lib_div("RSS MODULE SECTION");
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
