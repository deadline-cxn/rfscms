<?
include_once("include/lib.all.php");

sc_access_method_add("forums", "admin"); 
sc_access_method_add("forums", "add"); 
sc_access_method_add("forums", "edit");
sc_access_method_add("forums", "delete");
sc_access_method_add("forums", "moderate");

function sc_module_mini_latest_forum_threads($x) { eval(scg());
    sc_div("FORUMS MODULE SECTION");
    echo "<h2>Last $x Threads</h2>";
    echo "<table border=0 cellspacing=0 width=190>";
    $result = sc_query("select * from forum_posts where `thread_top`='yes' order by bumptime desc limit 0,$x");
    $numposts=mysql_num_rows($result);    
	if($numposts) {
		$gt=1;$i=0;
		for($i=0;$i<$numposts;$i++) {
				$gt++; if($gt>2) $gt=1;
				echo "<tr><td class=\"sc_forum_table_$gt\">";
				$thread=mysql_fetch_object($result);
				echo "<a href=\"$RFS_SITE_URL/modules/forums/forums.php?action=get_thread&thread=$thread->thread&forum_which=$thread->forum\">";
				echo "<img src=\"$RFS_SITE_URL/images/icons/icon_minipost.gif\" border=0 >";
				echo "$thread->title </a>";
				echo "</td></tr>";
		}
		
	}
    echo "</table>";
    echo "<p align=right>(<a href=$RFS_SITE_URL/modules/forums/forums.php class=\"a_cat\" align=right>More...</a>)</p>";
}


?>
