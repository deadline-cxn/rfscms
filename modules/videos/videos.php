<?
chdir("../../");
$RFS_LITTLE_HEADER=true;
include("header.php");
function videos_buttons() {
	eval(lib_rfs_get_globals()); 
	echo "<br>";
	lib_button("$RFS_SITE_URL/modules/videos/videos.php?action=random","Random Video");
	if(lib_access_check("videos","submit"))
		lib_button("$RFS_SITE_URL/modules/videos/videos.php?action=submitvid","Submit Video");
	echo "<br>";
}
function videos_pagefinish(){
	eval(lib_rfs_get_globals());
	include("footer.php");
	exit();
}
function videos_action_modifyvideo() {
	eval(lib_rfs_get_globals());
	if( lib_access_check("videos","edit") ) {
		$video=lib_mysql_fetch_one_object("select * from videos where id='$id'");
		echo "<p align=center>";
		echo "$video->url<br>";	
		echo "<form enctype=application/x-www-form-URLencoded method=post action=videos.php>";
		echo "<input type=hidden name=action value=modifygo>";    
		echo "<input type=hidden name=id value=\"$video->id\">";
		echo "Short Name<input name=sname value=\"$video->sname\">";
		echo "Safe For Work<select name=sfw>";
		if(!empty($video->sfw)) echo "<option>$video->sfw";
		echo "<option>yes<option>no</select>";
		echo "Hidden<select name=hidden>";
		if(!empty($video->hidden)) echo "<option>$video->hidden";
		echo "<option>no<option>yes</select>";
		echo "<select name=categorey>";
		$cat=mysql_fetch_object(lib_mysql_query("select * from `categories` where `id`='$video->category'"));
		echo "<option>$cat->name";
		$result2=lib_mysql_query("select * from categories order by name asc");
		while($cat=mysql_fetch_object($result2)) echo "<option>$cat->name";
		echo "</select>\n";
		echo "<br>";
		echo "<textarea rows=10 cols=40 name=vurl>$video->url</textarea>";	
		echo "<input type=submit name=go value=go>";
		echo "</form>";
		echo "</p>";		
	}
	else {
		echo "You can't edit videos."; 
	}
}
function videos_action_modifygo() {
	eval(lib_rfs_get_globals());
	$video=lib_mysql_fetch_one_object("select * from videos where id='$id'");
	$vc=lib_users_get_data($video->contributor);
    $categoryz=mysql_fetch_object(lib_mysql_query("select * from `categories` where `name`='$categorey'"));
    $category=$categoryz->id;
	if(lib_access_check("videos","edit")) {	
		if((!lib_access_check("videos","editothers"))  &&
			($data->id!=$video->contributor)) {
			echo "This isn't your video.";
		}
		else {
			$sname=addslashes($sname);
			$vurl=addslashes($vurl);
			lib_mysql_query("update `videos` set `category`='$category' where `id`='$id'");
			lib_mysql_query("update `videos` set `sname`='$sname' where `id`='$id'");
			lib_mysql_query("update `videos` set `sfw`='$sfw' where `id`='$id'");
			lib_mysql_query("update `videos` set `hidden`='$hidden' where `id`='$id'");
			lib_mysql_query("update `videos` set `url`='$vurl' where `id`='$id'");
		}
	}
	videos_action_view($id);
}
function videos_action_submitvid_youtube_go() {
	eval(lib_rfs_get_globals());
	if(lib_access_check("videos","submit")) {
		$ytw=file_get_contents($youtube);
		$ytw=str_replace("meta itemprop=\"videoId\" content=",$RFS_SITE_DELIMITER,$ytw);
		$ytw=str_replace("meta property=\"og:title\" content=",$RFS_SITE_DELIMITER,$ytw);
		$ytw=explode($RFS_SITE_DELIMITER,$ytw);
		$title=explode("\"",$ytw[1]);
		$youtube_code=explode("\"",$ytw[2]);
		$sname=$title[1];
		$ytcode=$youtube_code[1];
		$vurl="<iframe width=\"853\" height=\"480\" src=\"//www.youtube.com/embed/$ytcode\" frameborder=\"0\" allowfullscreen></iframe>";
		$cont=$data->id;
		$time=date("Y-m-d H:i:s"); 
		$sname=addslashes($sname);
		$url=addslashes($url);	
		$c=lib_mysql_fetch_one_object("select * from `categories` where name='$category'");
		$category=$c->id;
		lib_mysql_query(" INSERT INTO `videos` (`contributor`, `sname`,   `url`, `time`, `bumptime`, `category`, `hidden`, `sfw`)
								 VALUES ('$cont',	 	'$sname','$vurl','$time',    '$time','$category',      '0', '$sfw');");
		$id=mysql_insert_id();
		videos_action_view($id);
	}
}
function videos_action_submitvidgo() {
	eval(lib_rfs_get_globals());
	if(lib_access_check("videos","submit")) {
		$cont=$data->id;
		$time=date("Y-m-d H:i:s"); 
		$url=addslashes($url);	
		$c=lib_mysql_fetch_one_object("select * from categories where name='$category'");
		$category=$c->id;
		
		echo "	SUBMITTING VIDEO: <br>
				contributor $cont <br>
				sname $sname <br>
				url $vurl <br>
				time $time <br>
				btime $time <br>
				category $category<br>
				sfw $sfw <br>"	 ;
				
		lib_mysql_query(" INSERT INTO `videos` (`contributor`,`sname`,`url`,`time`,`bumptime`,`category`,`hidden`,`sfw`)
				  VALUES ('$cont','$sname','$vurl','$time','$time','$category','0','$sfw');");
		$id=mysql_insert_id();
		videos_action_view($id);
	}
}
function videos_action_submitvid() { 
	eval(lib_rfs_get_globals());
	if(lib_access_check("videos","submit")) {	
		
		echo "\n\n\n\n";
		echo "<h1>Submit new video</h1>\n";		
		echo "<div class='forum_box'>\n";
		echo "<h1>From Youtube</h1>\n";
		echo "<form enctype=application/x-www-form-URLencoded method=post action=\"$RFS_SITE_URL/modules/videos/videos.php\">\n";
		echo "<table border=0>\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"submitvid_youtube_go\">\n";
		echo "<tr><td>Youtube URL</td><td><input size=160 name=\"youtube\"></td></tr>\n";
		echo "<tr><td>Safe For Work</td><td><select name=sfw>";
		if(!empty($video->sfw)) echo "<option>$video->sfw";
		echo "<option>yes<option>no</select></td></tr>\n";
		$res=lib_mysql_query("select * from `categories` order by name asc");
		echo "<tr><td>Category</td><td><select name=category>";
		if(!empty($category_in)) echo "<option>$category_in";
		while($cat=mysql_fetch_object($res)) echo "<option>$cat->name";
		echo "</select></td></tr>\n";
		echo "<tr><td>&nbsp; </td><td><input type=\"submit\" value=\"Add Youtube Video\"></td></tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		echo "</div>\n";
		echo "\n\n\n\n";
		
		echo "<div class='forum_box'>\n";
		echo "<h1>From Embedded Code</h1>\n";
		echo "<form enctype=application/x-www-form-URLencoded method=post action=\"$RFS_SITE_URL/modules/videos/videos.php\">\n";
		echo "<table border=0>\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"submitvidgo\">\n";
		echo "<tr><td>Title</td><td><input size=160 name=\"sname\"></td></tr>\n";
		echo "<tr><td>Link</td><td><input size=160 name=\"link\"></td></tr>\n";		
		echo "<tr><td>Embed Code</td><td><textarea rows=10 cols=80 name=\"vurl\"></textarea></td></tr>\n";
		echo "<tr><td>Safe For Work</td><td><select name=sfw>";
		if(!empty($video->sfw)) echo "<option>$video->sfw";
		echo "<option>yes<option>no</select></td></tr>\n";
		$res=lib_mysql_query("select * from `categories` order by name asc");
		echo "<tr><td>Category</td><td><select name=category>";
		if(!empty($category_in)) echo "<option>$category_in";
		while($cat=mysql_fetch_object($res)) {
			echo "<option>$cat->name";
		}		
		echo "</select></td></tr>\n";
		echo "<tr><td>&nbsp; </td><td><input type=\"submit\" value=\"Add Video\"></td></tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		echo "</div>";
	}
}
function videos_action_removego() { 
	eval(lib_rfs_get_globals());
	if(lib_access_check("videos","delete")) {
		$video=lib_mysql_fetch_one_object("select * from `videos` where `id`='$id'");		
		lib_mysql_query("delete from `videos` where `id`='$id'");
		echo "<p>Removed $video->sname from the database...</p>";
	}
	videos_action_();
}
function videos_action_removevideo() { 
	eval(lib_rfs_get_globals());
	if(lib_access_check("videos","delete")) {
        $video=lib_mysql_fetch_one_object("select * from `videos` where `id`='$id'");        
        echo "<table border=0>\n";
        echo "<form enctype=application/x-www-form-URLencoded action=$RFS_SITE_URL/modules/videos/videos.php method=post>\n";
        echo "<input type=hidden name=action value=removego>\n";
        echo "<input type=hidden name=id value=\"$id\">\n";
        echo "<tr><td>Are you sure you want to delete [$video->sname]???</td>";
        echo "<td><input type=submit name=submit value=\"Yes\"></td></tr>\n";
        echo "</form></table>\n";
        video_pagefinish();
	}	
}
function videos_action_view($id) {
	eval(lib_rfs_get_globals());
	if(empty($id)) $id=mysql_insert_id();
	videos_buttons();
	$video=lib_mysql_fetch_one_object("select * from videos where id='$id'");
	$vc=lib_users_get_data($video->contributor);
	echo "<div class=forum_message > <center> ";	
	$category=lib_mysql_fetch_one_object("select * from categories where id ='$video->category'");
	echo "<h1>$category->name videos</h1>";	
	$res2=lib_mysql_query("select * from `videos` where `category`='$category->id' and `hidden`!='yes' order by `sname` asc");
	$linkprev="";
	$linknext="";
	while($video2=mysql_fetch_object($res2)){		
		if($video2->id==$video->id) {
			$video2=mysql_fetch_object($res2);
			if(!empty($video2->id)){
				$linknext="[<a href=videos.php?action=view&id=$video2->id>Next</a>]";
				if(!empty($video3->id))
					$linkprev="[<a href=videos.php?action=view&id=$video3->id>Previous</a>]";
					break;
				}
			}
		else { $video3=$video2; }
    }
	if(empty($linknext))
		if(!empty($video3->id))
			$linkprev="[<a href=videos.php?action=view&id=$video3->id>Previous</a>]";	
    echo "<p>$video->sname</p>"; // if(empty($vc->name)) // (contributed by: $vc->name)<br>";    
	
	// echo "SFW: [$video->sfw]<br>";
	
    if($video->sfw=="yes") {
		echo "$video->url<br>";
	}
    else {
		if($viewsfw=="yes") echo "$video->url<br>";
		else echo "<a href=videos.php?action=view&id=$video->id&viewsfw=yes><img src=\"$RFS_SITE_URL/images/icons/NSFW.png\" border=0></a><BR>";
    }
	echo "<br>";	
	echo $linkprev;
	if(lib_access_check("videos","edit"))   lib_button("$RFS_SITE_URL/modules/videos/videos.php?action=modifyvideo&id=$video->id","Edit");
	if(lib_access_check("videos","delete")) lib_button("$RFS_SITE_URL/modules/videos/videos.php?action=removevideo&id=$video->id","Delete");
	echo $linknext;
	echo "</div>";	
	videos_action_viewcat($video->category);
	videos_action_view_cats();
	videos_pagefinish();
}
function videos_action_viewcat($cat) {
	eval(lib_rfs_get_globals());
	videos_buttons();
	$res2=lib_mysql_query("select * from `videos` where `category`='$cat' and `hidden`!='yes' order by `sname` asc");
	while($vid=mysql_fetch_object($res2)) {		

		echo "<div style='margin: 5px; border: 1px; float: left; width: 100px; height: 170px;'>";
		echo "<a href=videos.php?action=view&id=$vid->id>";

		$ytturl=videos_get_thumbnail($vid->url);
		echo "<img src=\"$ytturl\" width=100 class=rfs_thumb><br>";
		
		echo "$vid->sname</a>";
		echo "</div>";
	}
	echo "<br style='clear: both;'>";
}
function videos_action_random() { 
	eval(lib_rfs_get_globals());
	$res=lib_mysql_query("select * from `videos` where `hidden`!='yes'");
	$num=mysql_num_rows($res);	
	if($num==0) { 
		videos_buttons();
		echo "<p>There are no videos.</p>";
	}
	else {
		$vid=rand(1,$num)-1;
		mysql_data_seek($res,$vid);
		$video=mysql_fetch_object($res);
		$vc=lib_users_get_data($video->contributor);
		$id=$video->id;
		videos_action_view($id);
	}
}
function videos_action_view_cats() {
	eval(lib_rfs_get_globals());
	$numcols=0;
	echo "<table border=0><tr>";
	$res=lib_mysql_query("select * from `categories` order by name asc");	
	while($cat=mysql_fetch_object($res)) {		
		$res2=lib_mysql_query("select * from `videos` where `category`='$cat->id' and `hidden`!='yes' order by `sname` asc");
		$numvids=mysql_num_rows($res2);
		if(!empty($cat->name))
			if($numvids>0){
				echo "<td>";
				echo "<table border=0 cellspacing=0 cellpadding=3 width=100%><tr><td>";
				echo "<table border=0 cellspacing=0 cellpadding=0 width=100% ><tr>";
				echo "<td class=td_cat valign=top width=220>";
				echo "<h1>
				<a href=\"$RFS_SITE_URL/modules/videos/videos.php?action=viewcat&cat=$cat->id\">
				$cat->name videos ($numvids)
				</a>
				</h1><br>";
				echo "</td></tr><tr>";
				echo "<td class=td_cat valign=top height=200 width=220>";
				if($numvids>5) $numvids=5;
				for($i=0;$i<$numvids;$i++){
					$video=mysql_fetch_object($res2);
					if($video->sfw=="no")
						$video->url="$RFS_SITE_URL/images/icons/NSFW.gif";
					echo "<table border=0>";
					echo "<tr><td valign=top>";
					echo "<a href=videos.php?action=view&id=$video->id>";
					echo "<img src=\"".videos_get_thumbnail($video->url)."\" width=100 class=rfs_thumb><br>";
					echo "$video->sname</a><br>";
					echo "</td></tr>";
					echo "</table>";
				}
					echo "</td></tr></table>";
				echo "</td></tr></table>";
				echo "</td>";
				$numcols++;
				if($numcols>3){
					echo "</tr><tr>";
					$numcols=0;
				}		
		}
	}
	echo "</tr>";
	echo "</table>";
}
function videos_action_() { 
	eval(lib_rfs_get_globals());
	echo "Videos</h1>";
	videos_action_random();
	videos_pagefinish();
}

/*
liveleak: 
code: encodeURI('<iframe width="640" height="360" src="http://www.liveleak.com/ll_embed?f=1fe3095e0f2a" frameborder="0" allowfullscreen></iframe>'),
link: "http://www.liveleak.com/view?i=cc6_1394235601"
image: "http://edge.liveleak.com/80281E/ll_a_u/thumbs/2014/Mar/7/1fe3095e0f2a_sf_6.jpg",  
*/

?>