<?
include_once("include/lib.all.php");

sc_add_menu_option("Links","$RFS_SITE_URL/modules/linkbin/linkbin.php");

///////////////////////////////////////////////////////////////
// MODULE LINK FRIENDS
function sc_module_mini_link_friends($x) { eval(scg());
	$result=sc_query("select * from link_bin where friend='yes' order by time limit $x");
	$numlinks=mysql_num_rows($result);
	echo "<h2>Link Friends</h2>";
	for($i=0;$i<$numlinks;$i++) {
		$link=mysql_fetch_object($result);
		$url=$link->link;
		$url = str_replace(":","_rfs_colon_",  $url);	
		$url=urlencode($url);
		echo "<div class=contenttd><a href=\"$RFS_SITE_URL/link_out.php?link=$url\" target=_blank>$link->sname</a></div>";
   }
	if($data->access=="255") {
		echo "<div style='float: left;'>";
		echo "<form action=\"$RFS_SITE_URL/admin/adm.php\" method=post><input type=hidden name=action value=edit_linkbin>";
		echo "<input type=submit name=submit value=\"edit links\"></form></div>";
		echo "<div style='clear: left;'></div>";
	}
}
?>