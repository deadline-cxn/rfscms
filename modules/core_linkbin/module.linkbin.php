<?php
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
// LINKBIN CORE MODULE
/////////////////////////////////////////////////////////////////////////////////////////
include_once("include/lib.all.php");

$RFS_ADDON_NAME="linkbin";
$RFS_ADDON_VERSION="1.0.0";
$RFS_ADDON_SUB_VERSION="0";
$RFS_ADDON_RELEASE="";
$RFS_ADDON_DESCRIPTION="RFSCMS Link Bin";
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

lib_menus_register("Links","$RFS_SITE_URL/modules/core_linkbin/linkbin.php");
///////////////////////////////////////////////////////////////
// MODULE LINK FRIENDS
function m_panel_link_friends($x) {
	eval(lib_rfs_get_globals());
	$result=lib_mysql_query("select * from link_bin where friend='yes' order by time limit $x");
	echo "<h2>Links</h2>";
	if($result) 
	while($link=$result->fetch_object()) {
		$url=$link->link;
		$url=str_replace(":","_rfs_colon_",$url);
		$url=urlencode($url);
		echo "<div class=contenttd><a href=\"$RFS_SITE_URL/link_out.php?link=$url\" target=_blank>$link->sname</a></div>";
   }
	if(lib_access_check("linkbin","edit")) {
		echo "<div style='float: left;'>";
		echo "<form action=\"$RFS_SITE_URL/admin/adm.php\" method=post><input type=hidden name=action value=edit_linkbin>";
		echo "<input type=submit name=submit value=\"edit links\"></form></div>";
		echo "<div style='clear: left;'></div>";
	}
}
///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_LINK EDIT
function adm_action_f_add_link() {
	eval( lib_rfs_get_globals() );
	$link=$_REQUEST['link'];
	$time=date( "Y-m-d H:i:s" );
	if( $data->id==0 ) $data->id=999;
	$query="insert into `link_bin` (`link`,`sname`,`time`,`bumptime`,`poster`,`description`) values('$link','$sname','$time','$time',   '$id','$description')";
	lib_mysql_query($query);
	echo "<p>Link [$link][$sname] added to linkbin...</p>\n";
	lib_log_add_entry( "*****> $data->name added a link to the linkbin [$link]" );
	adm_action_edit_linkbin();
}
function adm_action_f_modify_link() {
	eval( lib_rfs_get_globals() );
	if( $deletelink=="delete" ) {
		$l=lib_mysql_fetch_one_object( "select * from link_bin where `id`='$linkid'" );
		lib_forms_confirm(
		"Are you sure you want to delete $l->link ?",
        "$RFS_SITE_URL/admin/adm.php",
        "action=f_modify_link".$RFS_SITE_DELIMITER."deletelink=delete_go".$RFS_SITE_DELIMITER."linkid=$linkid" );
	}
	if( $deletelink=="delete_go" ) {
		$l=lib_mysql_fetch_one_object( "select * from link_bin where `id`='$linkid'" );
		lib_mysql_query( "DELETE FROM link_bin where `id` = '$linkid' limit 1", $mysql );
		lib_log_add_entry( "*****> $data->name deleted a link from the linkbin $l->short_name $l->link" );
		lib_forms_info( "$l->link deleted from the link bin","white","red" );
	}
	if( $renamelink=="modify" ) {
		echo "<p><h3>Modifying Link!</h3></p>\n";
		$short_name=addslashes( $short_name );
		$linkurl=addslashes( $linkurl );
		$description=addslashes( $description );
		$category=addslashes( $category );
		lib_mysql_query( "update link_bin set `sname` = '$short_name' where `id` = '$linkid'" );
		lib_mysql_query( "update link_bin set `link` = '$linkurl' where `id` = '$linkid'" );
		lib_mysql_query( "update link_bin set `description` = '$description' where `id` = '$linkid'" );
		$hide=0;
		if( $hidden=="yes" )$hide=1;
		if( $hidden=="no" ) $hide=0;
		lib_mysql_query( "update link_bin set `friend` = '$friend' where `id` = '$linkid'");
		lib_mysql_query( "update link_bin set `hidden` = '$hide' where `id` = '$linkid'" );
		lib_mysql_query( "update link_bin set `referral` = '$referral' where `id` = '$linkid'" );
		lib_mysql_query( "update link_bin set `referrals` = '$referrals' where `id` = '$linkid'" );
		lib_mysql_query( "update link_bin set `clicks` = '$clicks' where `id` = '$linkid'" );
		lib_mysql_query( "update link_bin set `category` = '$category' where `id` = '$linkid'" );
		lib_mysql_query( "update link_bin set `rating` = '$rating' where `id` = '$linkid'" );
	}
	adm_action_edit_linkbin();
}
function adm_action_edit_linkbin() {
	eval( lib_rfs_get_globals() );
	echo "<h3>Link Bin Editor</h3>\n";
	echo "<h2>Add Link</h2>\n";
	lib_forms_build( "$RFS_SITE_URL/admin/adm.php", "action=f_add_link", "link_bin", "", "id", "sname".$RFS_SITE_DELIMITER."link", "include", "", 20, "Add" );
	$where="";
	if(isset($filter)) {
		$where=" where $filter = 'yes' ";
	}
	$result=lib_mysql_query( "select * from link_bin $where order by time desc" );
	
	echo "Filter:	
	<a href=\"?filter=friend&action=edit_linkbin\">Friends</a>
	<a href=\"?filter=hidden&action=edit_linkbin\">Hidden</a>
	<a href=\"?filter=referral&action=edit_linkbin\">Referral</a>
	
	<br>";
	
	echo "<table width=100% border=0 cellspacing=0 cellpadding=4 align=center>\n";
	$gt=2;
	while($link=$result->fetch_object()) {
		$gt++;
		if($gt>2)$gt=1;
		echo "<tr><td class=rfs_project_table_$gt><br>\n";		
		$userdata=lib_users_get_data( $link->poster );
		echo "<table border=0 cellspacing=0 cellpadding=0 width=100% >\n";
		echo "<form enctype=\"application/x-www-form-URLencoded\" action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"f_modify_link\">\n";
		echo "<input type=\"hidden\" name=\"linkid\" value=\"$link->id\">\n";
		echo "<tr class=rfs_project_table_$gt>\n";
		echo "<td class=rfs_project_table_$gt>Short Name</td>";
		echo "<td class=rfs_project_table_$gt width=230><input type=text name=short_name value=\"$link->sname\" size=28></td>";
		echo "<td class=rfs_project_table_$gt>URL</td>";
		echo "<td class=rfs_project_table_$gt width=250><input type=text name=linkurl value=\"$link->link\" size=40> </td>\n";
		echo "<td class=rfs_project_table_$gt width=300>(submitted by $userdata->name on ".lib_string_current_time( $link->time ).")</td>\n";
		echo "<td class=rfs_project_table_$gt>Rating:</td>\n";
		echo "<td class=rfs_project_table_$gt width=100 align=center><input type=submit name=renamelink value=modify></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class=rfs_project_table_$gt>Category</td>";
		echo "<td class=rfs_project_table_$gt>\n";
		echo "<select name=category>\n";
		echo "<option>$link->category\n";
		$result2=lib_mysql_query("select * from `categories` order by name asc");
		while($cat=$result2->fetch_object()) echo "<option>$cat->name\n";
		echo "</select>\n";
		echo "</td>\n";
		echo "<td class=rfs_project_table_$gt>Description</td>";
		echo "<td class=rfs_project_table_$gt><input type=text name=description value=\"$link->description\" size=40></td>\n";
		echo "<td class=rfs_project_table_$gt><table border=0><tr>\n";
		echo "<td class=rfs_project_table_$gt>referrals</td><td class=rfs_project_table_$gt><input type=text size=4 name=referrals value=\"$link->referrals\"></td>\n";
		echo "<td class=rfs_project_table_$gt>clicks</td><td class=rfs_project_table_$gt><input type=text size=4 name=clicks value=\"$link->clicks\"></td>\n";		
		echo "<td class=rfs_project_table_$gt>";		
		
		if( lib_rfs_bool_true($link->hidden) ) echo "hidden <select name=hidden><option>yes<option>no</select>\n";
		else echo "hidden <select name=hidden><option>no<option>yes</select>\n";
		echo "<br>";				
		if( lib_rfs_bool_true($link->friend) ) echo "friend <select name=friend><option>yes<option>no</select>\n";
		else echo "friend <select name=friend><option>no<option>yes</select>\n";		
		echo "<br>";		
		if( lib_rfs_bool_true($link->referral) ) echo "referral <select name=referral><option>yes<option>no</select>\n";
		else echo "referral <select name=referral><option>no<option>yes</select>\n";		
		echo "<br>";		
		echo "</tr></table></td>\n";
		echo "<td class=rfs_project_table_$gt><select name=rating><option>$link->rating\n";
		for( $j=1; $j<6; $j++ ) echo "<option>$j\n";
		echo "</select></td>\n";
		echo "<td class=rfs_project_table_$gt align=center><input type=submit name=deletelink value=delete></td>\n";
		echo "</tr>\n";
		echo "<tr><td class=rfs_project_table_$gt>&nbsp;</td><td class=rfs_project_table_$gt>&nbsp;</td><td class=rfs_project_table_$gt>&nbsp;</td><td class=rfs_project_table_$gt>&nbsp;</td><td class=rfs_project_table_$gt>&nbsp;</td></tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		echo "</td></tr>\n";
	}
	echo "<tr><td>\n";
	echo "</td></tr></table>\n";			
	include("footer.php");
	exit();
}
?>
