<?php
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
    $num_feeds=$result->num_rows;
    for($i=0;$i<$num_feeds;$i++){
    	$feed=$result->fetch_object();
    	echo RSS_display($feed->feed, 3, false);
    }    
}

///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_RSS EDITOR
/*function adm_action_f_rss_edit_go_edit() {
	eval( lib_rfs_get_globals() );
	if( $update=="update" ) lib_mysql_query( "UPDATE rss_feeds SET `feed`='$edfeed' where `id`='$oid'" );
	if( $delete=="delete" ) lib_mysql_query( "DELETE FROM rss_feeds WHERE id = '$oid' " );
	adm_action_rss_edit();
}
function adm_action_f_rss_edit_go_add() {
	eval( lib_rfs_get_globals() );
	lib_mysql_query( "insert into rss_feeds values('$edfeed',0);" );
	adm_action_rss_edit();
}
function adm_action_rss_edit() {
	eval( lib_rfs_get_globals() );
	$result=lib_mysql_query( "select * from rss_feeds" );
	$num_feeds=$result->num_rows;
	echo "<h3>Editing RSS Feeds </h3>";

	for( $i=0; $i<$num_feeds; $i++ ) {
		echo "<table border=0 cellspacing=0 cellpadding=0>\n";
		echo "<form enctype=\"application/x-www-form-URLencoded\" action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\">\n";
		echo "<input type=hidden name=action value=rsseditgoedit>\n";
		$feed=$result->fetch_object();
		echo "<tr><td>Feed URL</td> <td><input type=textbox name=edfeed value=\"$feed->feed\" size=100></td>\n";
		echo "<td><input type=submit value=delete name=delete></td>\n";
		echo "<td><input type=submit value=update name=update> <input type=hidden value=$feed->id name=oid></td>\n";
		echo "</tr>\n";
		echo "</form></table>\n";
	}
	echo "<table border=0 cellspacing=0 cellpadding=0>\n";
	echo "<form enctype=\"application/x-www-form-URLencoded\" action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\">\n";
	echo "<input type=hidden name=action value=rsseditgoadd>\n";
	echo "<tr><td>New Feed</td><td><input type=textbox name=edfeed value=\"\" size=100></td>\n";
	echo "<td><input type=submit value=add name=add></td>\n";
	echo "</form></table>\n";
	include("footer.php");
	exit();
}*/

?>
