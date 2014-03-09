<?
include_once("include/lib.all.php");

lib_menus_register("Videos","$RFS_SITE_URL/modules/videos/videos.php");

lib_access_add_method("videos", "submit");
lib_access_add_method("videos", "edit");
lib_access_add_method("videos", "editothers");
lib_access_add_method("videos", "delete");
lib_access_add_method("videos", "deleteothers");

////////////////////////////////////////////////////////////////////////////////////////////////////////
// MODULE VIDEOS
function module_videos($x) { eval(lib_rfs_get_globals());
    echo "<h2>Last $x Videos</h2>";
    $res2=lib_mysql_query("select * from `videos` order by time desc limit 0,$x");
	echo "<table border=0 cellspacing=0 cellpadding=0>";
    while($video=mysql_fetch_object($res2)) {        
        if($video->sfw=="no") $video->url="$RFS_SITE_URL/files/videos/NSFW.gif";        
        echo "<tr><td class=contenttd>";
        echo "<a href=\"$RFS_SITE_URL/modules/videos/videos.php?action=view&id=$video->id\">";
        echo "$video->sname</a><br>";
        echo lib_string_truncate($video->description,50);        
        echo "</td></tr>";
    }
	echo "<tr><td class=contenttd></td><td class=contenttd>";
    echo "(<a href=$RFS_SITE_URL/modules/videos/videos.php?action=random class=a_cat>Random video</a>)<br>";
    echo "(<a href=$RFS_SITE_URL/modules/videos/videos.php class=a_cat>More...</a>)";
	echo "</td></tr>";
	echo "</table>";    
}

?>