<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
// VIDEOS CORE MODULE
/////////////////////////////////////////////////////////////////////////////////////////
include_once("include/lib.all.php");

$RFS_ADDON_NAME="videos";
$RFS_ADDON_VERSION="1.0.0";
$RFS_ADDON_SUB_VERSION="0";
$RFS_ADDON_RELEASE="";
$RFS_ADDON_DESCRIPTION="RFSCMS Videos";
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

lib_menus_register("Videos","$RFS_SITE_URL/modules/core_videos/videos.php");
////////////////////////////////////////////////////////////////////////////////////////////////////////
// PANELS
function m_panel_videos($x) { eval(lib_rfs_get_globals());
    echo "<h2>Last $x Videos</h2>";
    $res2=lib_mysql_query("select * from `videos` order by time desc limit 0,$x");
	echo "<table border=0 cellspacing=0 cellpadding=0>";
    while($video=$res2->fetch_object()) {        
        if($video->sfw=="no") $video->embed_code="$RFS_SITE_URL/files/videos/NSFW.gif";        
		
		$vlink="<a href=\"$RFS_SITE_URL/modules/core_videos/videos.php?action=view&id=$video->id\"
		alt=\"$video->sname\"
		text=\"$video->sname\"
		title=\"$video->sname\"
		>";
		
        echo "<tr><td class=contenttd>";
		echo "<table border=0 cellspacing=0 cellpadding=0><tr><td>";
		echo $vlink;
		echo "<img src=\"".videos_get_thumbnail($video->embed_code)."\" width=100 class='rfs_thumb' title=\"$video->sname\">";
		echo "</a>";
		echo "</td><td style='padding: 10px;'>";
		echo $vlink;
		$vname=lib_string_truncate($video->sname,20);
        echo "$vname</a><br>";
        echo lib_string_truncate($video->description,50);        
		echo "</td><tr></table>";
        echo "</td></tr>";
    }
	echo "<tr><td class=contenttd></td><td class=contenttd>";
    echo "(<a href=$RFS_SITE_URL/modules/core_videos/videos.php?action=random class=a_cat>Random video</a>)<br>";
    echo "(<a href=$RFS_SITE_URL/modules/core_videos/videos.php class=a_cat>More...</a>)";
	echo "</td></tr>";
	echo "</table>";
}

function videos_get_url_from_code($code) {
	$youtube="";
	if(stristr($code,"youtube")) {
		$ytx=explode("\"",$code);
		for($yti=0;$yti<count($ytx);$yti++) {
			if(stristr($ytx[$yti],"youtube")) {
				$ytx2=explode("/",$ytx[$yti]);
				$youtube=$ytx2[count($ytx2)-1];
			}
		}
	}
	if(!empty($youtube)) {
		$url="http://www.youtube.com/watch?v=$youtube";
	}
	return $url;
}

function videos_get_thumbnail($url) {
	eval(lib_rfs_get_globals());
	$ytturl="$RFS_SITE_URL/modules/core_videos/cache/oops.png";
	$ytthumb="";
	
	// <meta property="og:image" content="https://i1.ytimg.com/vi/fx6AXR8ehiA/hqdefault.jpg">
	// <meta property="og:image" content="http://edge.liveleak.com/80281E/ll_a_u/thumbs/2014/Jun/5/5625eccb67d8_sf_3.jpg"/>

	
	if( (stristr($url,"youtube")) || 
		(stristr($url,"youtu.be")) ) {
		$ytx=explode("\"",$url);
		for($yti=0;$yti<count($ytx);$yti++) {
			if(stristr($ytx[$yti],"youtube")) {
				$ytx2=explode("/",$ytx[$yti]);
				$ytthumb=$ytx2[count($ytx2)-1];
			}
		}
	}
	
	if($ytthumb) {
		$yttlocal="$RFS_SITE_PATH/modules/core_videos/cache/$ytthumb.jpg";
		$ytturl="$RFS_SITE_URL/modules/core_videos/cache/$ytthumb.jpg";
		if(!file_exists($yttlocal)) {
			$ch = curl_init("http://i1.ytimg.com/vi/$ytthumb/mqdefault.jpg");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$ytt = curl_exec($ch);
			curl_close($ch);
			file_put_contents("$yttlocal", $ytt);
		}
		if(!file_exists($yttlocal)) {
			$ytturl="$RFS_SITE_URL/modules/core_videos/cache/oops.png";
		}
	} 
	return $ytturl;
}

?>