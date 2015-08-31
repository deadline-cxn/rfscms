<?php
chdir("../../");
$RFS_LITTLE_HEADER=true;
include("header.php");
function videos_buttons() {
	global $RFS_SITE_URL;
	echo "<br>";
	lib_buttons_make_button("$RFS_SITE_URL/modules/core_videos/videos.php?action=random","Random Video");
	if(lib_access_check("videos","submit")) { lib_buttons_make_button("$RFS_SITE_URL/modules/core_videos/videos.php?action=submitvid","Submit Video");
		echo "<br>";
		videos_action_submitvid_urlform();
		echo "<br>";
	}
	echo "<br>";
}
function videos_pagefinish() {
	eval(lib_rfs_get_globals());
	include("footer.php");
	exit();
}
function videos_action_modifyvideo() {
	$id=$_REQUEST['id'];
	if( lib_access_check("videos","edit") ) {
		$video=lib_mysql_fetch_one_object("select * from videos where id='$id'");
		echo "<p align=center>";
		echo "$video->embed_code<br>";
		echo "<form enctype=application/x-www-form-URLencoded method=post action=videos.php>";
		echo "<table border=0>";
		echo "<input type=hidden name=action value=modifygo>";
		echo "<input type=hidden name=id value=\"$video->id\">";
		echo "<tr><td>Short Name</td><td><input name=sname size=100 value=\"$video->sname\"></td></tr>";
		echo "<tr><td>Description</td><td><textarea rows=10 cols=80 name=\"description\">$video->description</textarea></td></tr>";
		echo "<tr><td>URL</td><td><textarea rows=2 cols=100 name=\"vurl\">$video->url</textarea></td></tr>";
		echo "<tr><td>Image URL</td><td><textarea rows=2 cols=100 name=\"imageurl\">$video->image</textarea></td></tr>";
		echo "<tr><td>Safe For Work </td><td> <select name=sfw>";
		if(!empty($video->sfw)) echo "<option>$video->sfw";
		echo "<option>yes<option>no</select></td></tr>";
		echo "<tr><td>Hidden</td><td><select name=hidden>";
		if(!empty($video->hidden)) echo "<option>$video->hidden";
		echo "<option>no<option>yes</select></td></tr>";
		echo "<tr><td>Category:</td><td><select name=category><option>$video->category";
		$result2=lib_mysql_query("select * from `categories` order by `name` asc");
		while($cat=$result2->fetch_object()) echo "<option>$cat->name";
		echo "</select></td></tr>";
		echo "<tr><td>Embed Code:</td><td><textarea rows=10 cols=100 name=vembed_code>$video->embed_code</textarea></td></tr>";
		echo "<tr><td>&nbsp;</td><td><input type=submit name=go value=go></td></tr>";
		echo "</table>";
		echo "</form>";
		echo "</p>";
	} else {
		echo "You can't edit videos.";
	}
	videos_pagefinish();
}
function videos_action_modifygo() {
	eval(lib_rfs_get_globals());
	$video=lib_mysql_fetch_one_object("select * from videos where id='$id'");
	$vc=lib_users_get_data($video->contributor);
	if(lib_access_check("videos","edit")) {
		if((!lib_access_check("videos","editothers"))  &&
		        ($data->id!=$video->contributor)) {
			echo "This isn't your video.";
		} else {
			$sname=addslashes($sname);
			$vembed_code=addslashes($vembed_code);
			$description=addslashes($description);
			lib_mysql_query("update `videos` set `category`='$category' where `id`='$id'");
			lib_mysql_query("update `videos` set `sname`='$sname' where `id`='$id'");
			lib_mysql_query("update `videos` set `description`='$description' where `id`='$id'");
			lib_mysql_query("update `videos` set `sfw`='$sfw' where `id`='$id'");
			lib_mysql_query("update `videos` set `hidden`='$hidden' where `id`='$id'");
			lib_mysql_query("update `videos` set `url`='$vurl' where `id`='$id'");
			lib_mysql_query("update `videos` set `image`='$imageurl' where `id`='$id'");
			lib_mysql_query("update `videos` set `embed_code`='$vembed_code' where `id`='$id'");
		}
	}
	videos_action_view($id);
}


function videos_action_submitvid_internet_go() {
	
	$url=$_REQUEST['url'];
	$category=$_REQUEST['category'];
	$sfw=$_REQUEST['sfw'];
	
	$go="generic";
	if(stristr($url,"twitch"))		$go="twitch";
	if(stristr($url,"youtube")) 	$go="youtube";
	if(stristr($url,"liveleak"))	$go="liveleak";
	if(stristr($url,"vimeo"))    	$go="vimeo";
	if(stristr($url,"dailymotion"))	$go="dailymotion";
	$e="videos_action_submitvid_$go"."_go();";
	eval($e);
		/*	<meta property="og:title" content="Name">
			<meta property="og:description" content="stuff">
			<meta property="og:image" content="image url ">
			<meta property="og:type" content="video">
			<meta property="og:video" content="video url">
			<meta property="og:video:secure_url" content="secure url">
			<meta property="og:video:type"   content="mime type">
			<meta property="og:video:width"  content="850">
			<meta property="og:video:height" content="480">
			<meta property="og:site_name" content="Video Website">
			<meta property="og:url" content="video url">
			<meta property="video:duration" content="314"> */

}

function videos_action_submitvid_generic_go() {
	$url=$_REQUEST['url'];
	$category=$_REQUEST['category'];
	$sfw=$_REQUEST['sfw'];
	
	d_echo(__FILE__." ".__LINE__);
	d_echo("videos_action_submitvid_generic_go()");
	
	if(lib_access_check("videos","submit")) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)');
		$html_raw=curl_exec($ch);
		curl_close($ch);
		
		d_echo($html_raw);
		
		$html = new DOMDocument();
		@$html->loadHTML($html_raw);
		foreach($html->getElementsByTagName('meta') as $meta) {
			
			$ax=strtolower($meta->getAttribute('property'));
			$bx=$meta->getAttribute('content');
			switch($ax){
				case "og:title": 		$sname      = str_replace("_"," ",addslashes($bx)); break;
				case "og:description": 	$description= addslashes($bx); break;
				case "og:image": 		$oimage     = addslashes($bx); $image=$oimage;
					echo $image."<BR>";
				break;
				case "og:video":
				case "embedurl": 		$embed_code= addslashes($bx); break;
			}
			if(strtolower($meta->getAttribute('itemprop'))=="embedurl") $embed_code=$meta->getAttribute('content');
			if(strtolower($meta->getAttribute('name')) == "twitter:player") 
				if(empty($embed_code)) $embed_code=$meta->getAttribute('content');
		}
		$vembed_code="<iframe src=\"$embed_code\" width=\"850\" height=\"480\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
		$cont		 = $data->id;
		$time		 = date("Y-m-d H:i:s");
		$url	 	 = addslashes($url);
		$q=" INSERT INTO `videos` (`contributor`, `sname`, `image`,   `original_image`, `description`,  `embed_code`,      `url`,       `time`,     `bumptime`, `category`,    `hidden`,  `sfw`)
						   VALUES ('$cont',      '$sname', '$image', '$oimage',         '$description', '$vembed_code' ,   '$url' ,     '$time',    '$time',    '$category',    '0', 	'$sfw');";
		$res=lib_mysql_query($q);
		$q="select * from videos order by time desc limit 1";
		$vid=lib_mysql_fetch_one_object($q);
		videos_action_view($vid->id);
	}
}


function videos_action_submitvid_twitch_go() {
	$url=$_REQUEST['url'];
	$category=$_REQUEST['category'];
	$sfw=$_REQUEST['sfw'];
	
	d_echo(__FILE__." ".__LINE__);
	d_echo("videos_action_submitvid_generic_go()");
	
	if(lib_access_check("videos","submit")) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)');
		$html_raw=curl_exec($ch);
		curl_close($ch);
		
		d_echo($html_raw);
		
		$html = new DOMDocument();
		@$html->loadHTML($html_raw);
		foreach($html->getElementsByTagName('meta') as $meta) {
			
			$ax=strtolower($meta->getAttribute('property'));
			$bx=$meta->getAttribute('content');
			switch($ax){
				case "og:title": 		$sname      = str_replace("_"," ",addslashes($bx)); break;
				case "og:description": 	$description= addslashes($bx); break;
				case "og:image": 		$oimage     = addslashes($bx); $image=$oimage;
					echo $image."<BR>";
				break;
				case "og:video":
				case "embedurl": 		$embed_code= addslashes($bx); break;
			}
			if(strtolower($meta->getAttribute('itemprop'))=="embedurl") $embed_code=$meta->getAttribute('content');
			if(strtolower($meta->getAttribute('name')) == "twitter:player") 
				if(empty($embed_code)) $embed_code=$meta->getAttribute('content');
		}
		$vembed_code="<iframe src=\"$embed_code\" width=\"850\" height=\"480\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
		$ex			=explode("/",$url);
		$twitchchan	=$ex[count($ex)-1];
		$vembed_code="<object type=\"application/x-shockwave-flash\" height=\"850\" width=\"480\" id=\"live_embed_player_flash\" data=\"http://www.twitch.tv/widgets/live_embed_player.swf?channel=$twitchchan\" bgcolor=\"#000000\"><param name=\"allowFullScreen\" value=\"true\" /> <param name=\"allowScriptAccess\" value=\"always\" />	<param name=\"allowNetworking\" value=\"all\" /><param name=\"movie\" value=\"http://www.twitch.tv/widgets/live_embed_player.swf\" /><param name=\"flashvars\" value=\"hostname=www.twitch.tv&channel=$twitchchan&auto_play=true&start_volume=25\" /></object>";
		$sname 		= "Twitch: $sname";
		$cont		= $data->id;
		$time		= date("Y-m-d H:i:s");
		$url	 	= addslashes($url);
		$vembed_code=videos_convert_embed_size($vembed_code,"650","480");
		$q=" INSERT INTO `videos` (`contributor`, `sname`, `image`,   `original_image`, `description`,  `embed_code`,      `url`,       `time`,     `bumptime`, `category`,    `hidden`,  `sfw`)
						   VALUES ('$cont',      '$sname', '$image', '$oimage',         '$description', '$vembed_code' ,   '$url' ,     '$time',    '$time',    '$category',    '0', 	'$sfw');";
		$res=lib_mysql_query($q);
		$q="select * from videos order by time desc limit 1";
		$vid=lib_mysql_fetch_one_object($q);
		videos_action_view($vid->id);
	}
}


function videos_action_submitvid_dailymotion_go() {
	$url=$_REQUEST['url'];
	$category=$_REQUEST['category'];
	$sfw=$_REQUEST['sfw'];
	
	d_echo(__FILE__." ".__LINE__);
	d_echo("videos_action_submitvid_generic_go()");
	
	if(lib_access_check("videos","submit")) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)');
		$html_raw=curl_exec($ch);
		curl_close($ch);
		
		d_echo($html_raw);
		
		$html = new DOMDocument();
		@$html->loadHTML($html_raw);
		foreach($html->getElementsByTagName('meta') as $meta) {
			
			$ax=strtolower($meta->getAttribute('property'));
			$bx=$meta->getAttribute('content');
			switch($ax){
				case "og:title": 		$sname      = str_replace("_"," ",addslashes($bx)); break;
				case "og:description": 	$description= addslashes($bx); break;
				case "og:image": 		$oimage     = addslashes($bx); $image=$oimage; break;
				case "og:video": 		$embed_code= addslashes($bx); break;
			}
			if(strtolower($meta->getAttribute('itemprop'))=="embedurl") $embed_code=$meta->getAttribute('content');
			if(strtolower($meta->getAttribute('name')) == "twitter:player") 
				if(empty($embed_code)) $embed_code=$meta->getAttribute('content');
		}
		$vembed_code="<iframe src=\"$embed_code\" width=\"850\" height=\"480\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
		$cont		= $data->id;
		$time		= date("Y-m-d H:i:s");
		$url	 	= addslashes($url);
		$q=" INSERT INTO `videos` (`contributor`, `sname`, `image`,   `original_image`, `description`,  `embed_code`,      `url`,       `time`,     `bumptime`, `category`,    `hidden`,  `sfw`)
						   VALUES ('$cont',      '$sname', '$image', '$oimage',         '$description', '$vembed_code' ,   '$url' ,     '$time',    '$time',    '$category',    '0', 	'$sfw');";
		$res=lib_mysql_query($q);
		$q="select * from videos order by time desc limit 1";
		$vid=lib_mysql_fetch_one_object($q);
		videos_action_view($vid->id);
	}
}

function videos_action_submitvid_vimeo_go() {
	$url=$_REQUEST['url'];
	$category=$_REQUEST['category'];
	$sfw=$_REQUEST['sfw'];
	if(lib_access_check("videos","submit")) {
		$html_raw = file_get_contents($url);
		$html = new DOMDocument();
		@$html->loadHTML($html_raw);
		foreach($html->getElementsByTagName('meta') as $meta) {
			$ax=$meta->getAttribute('property');
			$bx=$meta->getAttribute('content');
			switch($ax){ 			
				case "og:title": 		$sname      = addslashes($bx); break;	
				case "og:description": 	$description= addslashes($bx); break;
				case "og:image": 		$oimage     = addslashes($bx); $image=$oimage; break;
			}
		}
		$vx=explode("/",$url);
		$embed_code=$vx[count($vx)-1];
		$vembed_code = "<iframe src=\"http://player.vimeo.com/video/$embed_code\" width=\"850\" height=\"480\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe> ";
		$cont		 = $data->id;
		$time		 = date("Y-m-d H:i:s");
		$url	 	 = addslashes($url);		
		$q=" INSERT INTO `videos` (`contributor`, `sname`, `image`, `original_image`, `description`, `embed_code`,      `url`,       `time`, `bumptime`, `category`,    `hidden`,  `sfw`)
						   VALUES ('$cont',      '$sname', '$image', '$oimage', '$description', '$vembed_code' , '$url' ,'$time',    '$time','$category', '0', 		'$sfw');";
		lib_mysql_query($q);
		$q="select * from videos order by time desc limit 1";
		$vid=lib_mysql_fetch_one_object($q);
		$_GLOBALS['id']=$vid->id;
		videos_action_view($vid->id);
	}
}
function videos_action_submitvid_liveleak_go() {
	$url=$_REQUEST['url'];
	$category=$_REQUEST['category'];
	$sfw=$_REQUEST['sfw'];
	if(lib_access_check("videos","submit")) {		
		$html_raw = file_get_contents($url);
		$html = new DOMDocument();
		@$html->loadHTML($html_raw);
		foreach($html->getElementsByTagName('meta') as $meta) {
			$ax=$meta->getAttribute('property');
			$bx=$meta->getAttribute('content');
			switch($ax){ 			
				case "og:title": 		$sname = str_replace("LiveLeak.com - ","",$bx); break;	
				case "og:description": 	$description=addslashes($bx); break;
				case "og:image": 		$oimage=addslashes($bx); $image=$oimage; break;
			}
		}
		$ec=explode("/",$image); $ed=explode("_",$ec[count($ec)-1]); $embed_code=$ed[0];
		$vembed_code = "<iframe width=\"850\" height=\"480\" src=\"http://www.liveleak.com/ll_embed?f=$embed_code\" frameborder=\"0\" allowfullscreen></iframe>";
		$cont		 = $data->id;
		$time		 = date("Y-m-d H:i:s");
		$sname		 = addslashes($sname);
		$url	 	 = addslashes($url);
		$q=" INSERT INTO `videos` (`contributor`, `sname`, `image`, `original_image`, `description`, `embed_code`,      `url`,       `time`, `bumptime`, `category`,    `hidden`,  `sfw`)
						   VALUES ('$cont',      '$sname', '$image', '$oimage', '$description', '$vembed_code' , '$url' ,'$time',    '$time','$category', '0', 		'$sfw');";
		lib_mysql_query($q);
		$q="select * from videos order by time desc limit 1";
		$vid=lib_mysql_fetch_one_object($q);
		$_GLOBALS['id']=$vid->id;
		videos_action_view($vid->id);
	}	
}
function videos_action_submitvid_youtube_go() {
	$url=$_REQUEST['url'];
	$category=$_REQUEST['category'];
	$sfw=$_REQUEST['sfw'];
	
	if(lib_access_check("videos","submit")) {
		$html_raw = file_get_contents($url);
		$html = new DOMDocument();
		@$html->loadHTML($html_raw);
		foreach($html->getElementsByTagName('meta') as $meta) {
			$ax=$meta->getAttribute('property');
			$bx=$meta->getAttribute('content');
			switch($ax){ 			
				case "og:title": 		$sname = $bx; break;
				case "og:description": 	$description=$bx; break;
				case "og:image": 		$oimage=addslashes($bx); $image=$oimage; break;
				case "og:url": 			$ex=explode("=",$bx); $ytcode=$ex[1]; break;
			}
		}
		$vembed_code = "<iframe width=\"850\" height=\"480\" src=\"//www.youtube.com/embed/$ytcode?autoplay=1\" frameborder=\"0\" allowfullscreen></iframe>";
		$cont		 = $data->id;
		$time		 = date("Y-m-d H:i:s");
		$sname		 = addslashes($sname);
		$url		 = addslashes($url);
		$description = addslashes($description);		
		$q=" INSERT INTO `videos` (`contributor`, `sname`, `image`, `original_image`, `description`, 	`embed_code`,  	`url`,       `time`,    `bumptime`, `category`,    `hidden`,  `sfw`)
						   VALUES ('$cont',      '$sname', '$image', '$oimage', 	  '$description',  '$vembed_code' , '$url' ,    '$time',    '$time',    '$category',   '0',       '$sfw');";
		lib_mysql_query($q);
		$q="select * from videos order by time desc limit 1";
		$vid=lib_mysql_fetch_one_object($q);
		videos_action_view($vid->id);
	}
}

function videos_action_submitvidgo() {
	eval(lib_rfs_get_globals());	
	if(lib_access_check("videos","submit")) {
		$cont=$data->id;
		$time=date("Y-m-d H:i:s");
		$url=addslashes($url);
		$description=addslashes($description);
		lib_mysql_query(" INSERT INTO `videos` (`contributor`,`sname`,`description`,`embed_code`, `url`,`time`,`bumptime`,`category`,`hidden`,`sfw`)
										VALUES ('$cont','$sname', '$description', '$vembed_code','$vurl','$time','$time','$category','0','$sfw');");
		$q="select * from videos order by time desc limit 1";
		$vid=lib_mysql_fetch_one_object($q);
		videos_action_view($vid->id);
	}
}
function videos_action_submitvid() {
	if(lib_access_check("videos","submit")) {
		echo "<h1>Submit new video</h1>\n";
		echo "<div class='forum_box'>\n";
		echo "<h1>URL</h1>\n";
		videos_action_submitvid_urlform();
		echo "<h1>Enter Embed Code</h1>\n";
		videos_action_submitvid_embedform();
	}
}
function videos_action_removego($id) {
	if(empty($id)) $id=$_REQUEST['id'];
	$video=lib_mysql_fetch_one_object("select * from `videos` where `id`='$id'");
	if(lib_access_check("videos","delete")) {		
		lib_mysql_query("delete from `videos` where `id`='$id'");
		echo "<p>Removed $video->sname from the database...</p>";
	}
	videos_action_viewcat($video->category);
}
function videos_action_removevideo($id) {
	if(empty($id)) $id=$_REQUEST['id'];
	global $RFS_SITE_URL;
	if(lib_access_check("videos","delete")) {
		$video=lib_mysql_fetch_one_object("select * from `videos` where `id`='$id'");
		echo "<table border=0>\n";
		echo "<form enctype=application/x-www-form-URLencoded action=$RFS_SITE_URL/modules/core_videos/videos.php method=post>\n";
		echo "<input type=hidden name=action value=removego>\n";
		echo "<input type=hidden name=id value=\"$id\">\n";
		echo "<tr><td>Are you sure you want to delete [$video->sname]???</td>";
		echo "<td><input type=submit name=submit value=\"Yes\"></td></tr>\n";
		echo "</form></table>\n";
		video_pagefinish();
	}
}
function videos_action_view($id) {
	if(empty($id)) $id=$_REQUEST['id'];
	global $RFS_SITE_URL;
	videos_buttons();
	$video=lib_mysql_fetch_one_object("select * from videos where id='$id'");
	$vc=lib_users_get_data($video->contributor);
	echo "<div class=forum_message > <center> ";
	echo "<h1>$video->category videos</h1>";
	$res2=lib_mysql_query("select * from `videos` where `category`='$category' and `hidden`!='yes' order by `sname` asc");
	$linkprev="";
	$linknext="";
	
	while($video2=$res2->fetch_object()) {
		if($video2->id==$video->id) {
			$video2=$res2->fetch_object();
			if(!empty($video2->id)) {
				$linknext="[<a href=videos.php?action=view&id=$video2->id>Next</a>]";
				if(!empty($video3->id))
					$linkprev="[<a href=videos.php?action=view&id=$video3->id>Previous</a>]";
				break;
			}
		} else {
			$video3=$video2;
		}
	}
	if(empty($linknext))
		if(!empty($video3->id))
			$linkprev="[<a href=videos.php?action=view&id=$video3->id>Previous</a>]";
	echo "<p>$video->sname</p>"; 
	echo "<p>$video->description</p>";
	if($video->sfw=="yes") {
		echo "$video->embed_code<br>";
	} else {
		if($viewsfw=="yes") echo "$video->embed_code<br>";
		else echo "<a href=videos.php?action=view&id=$video->id&viewsfw=yes><img src=\"$RFS_SITE_URL/images/icons/NSFW.png\" border=0></a><BR>";
	}
	echo "<br><br>";
	echo $linkprev;
	if(lib_access_check("videos","edit"))   lib_buttons_make_button("$RFS_SITE_URL/modules/core_videos/videos.php?action=modifyvideo&id=$video->id","Edit");
	if(lib_access_check("videos","delete")) lib_buttons_make_button("$RFS_SITE_URL/modules/core_videos/videos.php?action=removevideo&id=$video->id","Delete");
	echo $linknext;
	echo "</div>";
	videos_action_viewcat($video->category);
	videos_action_view_cats();
	videos_pagefinish();
}
function videos_action_viewcat($cat) {
	if(empty($cat)) $cat=$_REQUEST['cat'];
	videos_buttons();
	$res2=lib_mysql_query("select * from `videos` where `category`='$cat' and `hidden`!='yes' order by `sname` asc");
	while($vid=$res2->fetch_object()) {
		echo "<div style='margin: 5px; border: 1px; float: left; width: 100px; height: 170px;'>";
		echo "<a href=\"videos.php?action=view&id=$vid->id\" title=\"$vid->sname\">";
		$ytturl=videos_get_thumbnail($vid);
		echo "<img src=\"$ytturl\" width=100 class=rfs_thumb><br>";
		echo "$vid->sname</a>";
		echo "</div>";
	}
	echo "<br style='clear: both;'>";
}
function videos_action_view_cats() {
	eval(lib_rfs_get_globals());
	$numcols=0;
	echo "<table border=0><tr>";
	$res=lib_mysql_query("select * from `categories` order by name asc");
	while($cat=$res->fetch_object()) {
		$res2=lib_mysql_query("select * from `videos` where `category`='$cat->name' and `hidden`!='yes' order by `sname` asc");
		$numvids=$res2->num_rows;
		if(!empty($cat->name))
			if($numvids>0) {
				echo "<td>";
				echo "<table border=0 cellspacing=0 cellpadding=3 width=100%><tr><td>";
				echo "<table border=0 cellspacing=0 cellpadding=0 width=100% ><tr>";
				echo "<td class=td_cat valign=top width=220>";
				echo "<h1>
				<a href=\"$RFS_SITE_URL/modules/core_videos/videos.php?action=viewcat&cat=$cat->name\">
				$cat->name videos ($numvids)
				</a>
				</h1><br>";
				echo "</td></tr><tr>";
				echo "<td class=td_cat valign=top height=200 width=220>";
				if($numvids>5) $numvids=5;
				for($i=0; $i<$numvids; $i++) {
					$video=$res2->fetch_object();
					if($video->sfw=="no")
						$video->embed_code="$RFS_SITE_URL/images/icons/NSFW.gif";
					echo "<table border=0>";
					echo "<tr><td valign=top>";
					echo "<a href=videos.php?action=view&id=$video->id>";
					
					echo "<img src=\"".videos_get_thumbnail($video)."\" width=100 class=rfs_thumb><br>";
					
					echo "$video->sname</a><br>";
					echo "</td></tr>";
					echo "</table>";
				}
				echo "</td></tr></table>";
				echo "</td></tr></table>";
				echo "</td>";
				$numcols++;
				if($numcols>3) {
					echo "</tr><tr>";
					$numcols=0;
				}
			}
	}
	echo "</tr>";
	echo "</table>";
}
function videos_action_random() { videos_action_(); }
function videos_action_randoms() {
	$res=lib_mysql_query("select * from `videos` where `hidden`!='yes'");
	$num=$res->num_rows;
	if($num==0) { videos_buttons(); echo "<p>There are no videos.</p>"; }
	else {	
		$vid=rand(1,$num)-1;
		mysqli_data_seek($res,$vid);
		$video=$res->fetch_object();
		$vc=lib_users_get_data($video->contributor);
		
		videos_action_view($video->id);
	}
}
function videos_action_() {
	eval(lib_rfs_get_globals());
	echo "<h1>Videos</h1>";
	videos_action_randoms();
	videos_pagefinish();
}

?>
