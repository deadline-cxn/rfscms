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
		echo "<table border=0 cellspacing=0 cellpadding=0><tr><td>";        
		echo "<img src=\"".videos_get_thumbnail($video->url)."\" width=100 class=rfs_thumb>";
		echo "</td><td>";
		echo "<a href=\"$RFS_SITE_URL/modules/videos/videos.php?action=view&id=$video->id\">";
        echo "$video->sname</a><br>";
        echo lib_string_truncate($video->description,50);        
		echo "</td><tr></table>";
        echo "</td></tr>";
    }
	echo "<tr><td class=contenttd></td><td class=contenttd>";
    echo "(<a href=$RFS_SITE_URL/modules/videos/videos.php?action=random class=a_cat>Random video</a>)<br>";
    echo "(<a href=$RFS_SITE_URL/modules/videos/videos.php class=a_cat>More...</a>)";
	echo "</td></tr>";
	echo "</table>";    
}


function videos_get_thumbnail($url) {
	eval(lib_rfs_get_globals());
	
	$ytturl="$RFS_SITE_URL/modules/videos/cache/oops.png";
	$ytthumb="";
	if(stristr($url,"youtube")) {
		$ytx=explode("\"",$url);
		for($yti=0;$yti<count($ytx);$yti++) {
			if(stristr($ytx[$yti],"youtube")) {
				$ytx2=explode("/",$ytx[$yti]);
				$ytthumb=$ytx2[count($ytx2)-1];
			}
		}
	}
	if($ytthumb) {
		$yttlocal="$RFS_SITE_PATH/modules/videos/cache/$ytthumb.jpg";
		$ytturl="$RFS_SITE_URL/modules/videos/cache/$ytthumb.jpg";
		if(!file_exists($yttlocal)) {
			$ch = curl_init("http://i1.ytimg.com/vi/$ytthumb/mqdefault.jpg");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			$ytt = curl_exec($ch); 
			curl_close($ch); 
			file_put_contents("$yttlocal", $ytt);
		}
		if(!file_exists($yttlocal)) {
			$ytturl="$RFS_SITE_URL/modules/videos/cache/oops.png";
		}
	} 
	return $ytturl;
}

?>