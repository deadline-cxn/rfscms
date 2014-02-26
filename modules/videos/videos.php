<?
chdir("../../");
include("header.php");

$video=mfo1("select * from videos where id='$id'");
$vc=sc_getuserdata($video->contributor);

function video_pagefinish(){
    include("footer.php");
    exit();
}

if($action=="modifyvideo"){
	if( ($data->access==255) ||
		($data->id==$video->contributor) ) {
		echo "<p align=center>";
		echo "$video->url<br>";	
		echo "<form enctype=application/x-www-form-URLencoded method=post action=videos.php>";
		echo "<input type=hidden name=action value=modifygo>";    
		echo "<input type=hidden name=id value=\"$video->id\">";
		echo "Short Name<input name=sname value=\"$video->sname\">";
		echo "Safe For Work<select name=sfw>";
		if(!empty($video->sfw))
			echo "<option>$video->sfw";
		echo "<option>yes<option>no</select>";
		echo "Hidden<select name=hidden>";
		if(!empty($video->hidden)) echo "<option>$video->hidden";
		echo "<option>no<option>yes</select>";
		$cat=mysql_fetch_object(sc_query("select * from `categories` where `id`='$video->category'"));
		echo "<select name=categorey>";
		echo "<option>$cat->name";
		$result2=sc_query("select * from categories order by name asc");
		$numcats=mysql_num_rows($result2);
		for($i2=0;$i2<$numcats;$i2++){
			$cat=mysql_fetch_object($result2);
			echo "<option>$cat->name";
		}
		echo "</select>\n";
		echo "<br>";
		echo "<textarea rows=10 cols=40 name=vurl>$video->url</textarea>";	
		echo "<input type=submit name=go value=go>";
		echo "</form>";
		echo "</p>";		
	}
	else {
		echo "This isn't your video."; 
	}
}

if($action=="modifygo"){
    $categoryz=mysql_fetch_object(sc_query("select * from `categories` where `name`='$categorey'"));
    $category=$categoryz->id;
	if( ($data->access==255) ||	
		($data->id==$video->contributor) ) {
		sc_query("update `videos` set `category`='$category' where `id`='$id'");
		sc_query("update `videos` set `sname`='$sname' where `id`='$id'");
		sc_query("update `videos` set `sfw`='$sfw' where `id`='$id'");
		sc_query("update `videos` set `hidden`='$hidden' where `id`='$id'");
		sc_query("update `videos` set `url`='$vurl' where `id`='$id'");
	}
	else {
		echo "This isn't your video.";
	}
	$action="view";
}

if(empty($action)) $action="random";
if($action=="random"){
	$res=sc_query("select * from `videos` where `hidden`!='yes'");
	$num=mysql_num_rows($res);	
	if($num==0) {
		echo "<p>There are no videos.</p>";		
	}
	else {
		$vid=rand(1,$num)-1;
		mysql_data_seek($res,$vid);
		$video=mysql_fetch_object($res);
		$vc=sc_getuserdata($video->contributor);
		$id=$video->id;
		$action="view";
		
	}
}

if(!empty($id))
$res=sc_query("select * from `videos` where `id`='$id'");
if($res) $video=mysql_fetch_object($res);
if(!empty($video->id))
$category=mysql_fetch_object(sc_query("select * from `categories` where `id`='$video->category'"));
if(!empty($cat)) 
$category=mysql_fetch_object(sc_query("select * from `categories` where `id`='$cat  '"));

if(!empty($category->name)) {
	echo "<h1>$category->name Videos</h1>";
}
else {
	echo "<h1>Videos</h1>";
}



if($action=="submitvidgo") {
	if($_SESSION['logged_in'] != true) $cont=999;
	else $cont=$data->id;
	$time=date("Y-m-d H:i:s"); 
	$url=addslashes($url);	
	$c=mfo1("select * from categories where name='$category'");
	$category=$c->id;
	
	echo "SUBMITTING VIDEO: <br>
	 contributor $cont <br>
	 sname $sname <br>
	 url $vurl <br>
	 time $time <br>
	 btime $time <br>
	 category $category<br>
	 sfw $sfw <br>"
	 ;
	 
sc_query("
INSERT INTO `videos` (
`id` ,
`contributor` ,
`sname` ,
`url` ,
`time` ,
`bumptime` ,
`category` ,
`hidden` ,
`sfw`
)
VALUES (
NULL ,
'$cont',
'$sname',
'$vurl',
'$time',
'$time',
'$category',
'0',
'$sfw'
);
");

	$v=mfo1("select * from videos where `sname`='$sname'");
	$id=$v->id;
	$action="modifygo";

echo "<BR> $v->id ($v->sname) <BR>";	
	
}

if($action=="submitvid"){
	
	echo "<table border=0><form enctype=application/x-www-form-URLencoded method=post action=\"$RFS_SITE_URL/modules/videos/videos.php\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"submitvidgo\">\n";
	echo "<tr><td>Title</td><td>";
	
	echo "<input size=60 name=\"sname\"></td></tr>\n";
	
	echo "<tr><td>URL (Embeded)</td><td>";
	echo "<textarea rows=10 cols=60 name=\"vurl\"></textarea></td></tr>\n";
	echo "<tr><td>";
	echo "Safe For Work</td><td><select name=sfw>";
	if(!empty($video->sfw))
		echo "<option>$video->sfw";
	echo "<option>yes<option>no</select></td></tr>";

	$res=sc_query("select * from `categories` order by name asc");
	$numcat=mysql_num_rows($res);	
	echo "<tr><td>Category</td><td><select name=category>";
	if(!empty($category_in)) echo "<option>$category_in";
	for($i=0;$i<$numcat;$i++){
		$cat=mysql_fetch_object($res);
		echo "<option>$cat->name";
	}
	
	echo "</select></td></tr>";
	
	echo "<tr><td>&nbsp; </td><td><input type=\"submit\" value=\"Add Video\"></td></tr>\n";
	echo "</form></table>\n";        
}



if($data->access==255){

    if($action=="removego")
    {
        $res=sc_query("select * from `videos` where `id`='$id'");
        $video=mysql_fetch_object($res);

        sc_query("delete from `videos` where `id`='$id'");

        echo "<p>Removed $video->sname from the database...</p>";
        if($annihilate=="yes")
        {
            $ftr=$video->url;
            $ftr=str_replace($RFS_SITE_URL,$RFS_SITE_PATH,$ftr);
            $ftr=str_replace("//","/",$ftr);

            @unlink($ftr);
            echo "<p> $ftr annihilated!</p>\n";
        }

        if($gosorttemp=="yes") $action="sorttemp";
    }

    if($action=="removevideo")
    {
        $res=sc_query("select * from `videos` where `id`='$id'");
        $video=mysql_fetch_object($res);
        echo "<table border=0>\n";
        echo "<form enctype=application/x-www-form-URLencoded action=$RFS_SITE_URL/modules/videos/videos.php method=post>\n";
        echo "<input type=hidden name=action value=removego>\n";
        echo "<input type=hidden name=id value=\"$id\">\n";
        echo "<tr><td>Are you sure you want to delete [$video->sname]???</td>";
        echo "<td><input type=submit name=submit value=\"Fuck Yeah!\"></td></tr>\n";
        echo "<tr><td>Annihilate the file from the server?</td>";
        echo "<td><input name=\"annihilate\" type=\"checkbox\" value=\"yes\"></td></tr>\n";
        echo "</form></table>\n";
        
        video_pagefinish();
    }

    if($action=="addorphans"){
        $categoryz=mysql_fetch_object(sc_query("select * from `categories` where `name`='!!!TEMP!!!'"));
        $category=$categoryz->id;
        $dir_count=0;
        $dirfiles = array();
        $handle=@opendir("./files/videos") or die("Unable to open filepath");
        while (false!==($file = readdir($handle))) array_push($dirfiles,$file);
        closedir($handle);    
        reset($dirfiles);
	    echo "<table border=1>";
        while(list ($key, $file) = each ($dirfiles)){
            if($file!=".")
                if($file!=".."){
                    if( (sc_getfiletype($file)=="gif") ||
                        (sc_getfiletype($file)=="jpg") ){                        
                        $url = "$RFS_SITE_URL/files/videos/$file";
                        $res=sc_query("select * from `videos` where `url`='$url'");
                        
                        if(mysql_num_rows($res)>0){
                            // echo "[$url] found in database... skipping<br>";
                        }
                        else
                        {
                            // echo "$url <a href=$url><img src=\"$url\"></a><br>";
                            $time=date("Y-m-d H:i:s");
                            sc_query("insert into `videos` (`time`,`url`,`category`,`hidden`)
                                                       VALUES('$time','$url','$category','yes')");
                            echo "Added [$url] to database<br>";                            
                            $dir_count++;
                        }
                    }
                }
        }
	    echo "</table>";
        if($dir_count==0) echo "No new videos added to database.<br>";
        else              echo "$dir_count new video(s) added to database from $RFS_SITE_URL/files/videos/...";
    }

    if($action=="sorttemp"){
        if($subact=="place"){
            $categoryz=mysql_fetch_object(sc_query("select * from `categories` where `name`='$categorey'"));
            $category=$categoryz->id;
            $res=sc_query("select * from `videos` where `category`='$category' order by time asc");
            sc_query("update `videos` set `category`='$category' where `id`='$id'");
            sc_query("update `videos` set `sname`='$sname' where `id`='$id'");
            sc_query("update `videos` set `sfw`='$sfw' where `id`='$id'");
            sc_query("update `videos` set `hidden`='$hidden' where `id`='$id'");
        }
        
        $categoryz=mysql_fetch_object(sc_query("select * from `categories` where `name`='!!!TEMP!!!'"));
        $category=$categoryz->id;
        $res=sc_query("select * from `videos` where `category`='$category' order by time asc");
        $numvids=mysql_num_rows($res);
        if($numvids>0){

            $video=mysql_fetch_object($res);

            echo "<p align=center>$video->url<br><img src=\"$video->url\"><br>";
            echo "<form enctype=application/x-www-form-URLencoded method=post action=videos.php>";

            echo "<input type=hidden name=action value=sorttemp>";
            echo "<input type=hidden name=subact value=place>";
            echo "<input type=hidden name=id value=\"$video->id\">";

            echo "Short Name<input name=sname value=\"$video->sname\">";
        
            echo "Safe For Work<select name=sfw>";
            if(!empty($video->sfw)) echo "<option>$video->sfw";
            echo "<option>yes<option>no</select>";

            echo "Hidden<select name=hidden>";

            echo "<option>no<option>yes</select>";

            $cat=mysql_fetch_object(sc_query("select * from `categories` where `id`='$video->category'"));

            echo "<select name=categorey>\n";
            echo "<option>Funny<option>$cat->name\n";
            $result2=sc_query("select * from categories order by name asc");
            $numcats=mysql_num_rows($result2);
            for($i2=0;$i2<$numcats;$i2++)
            {
                $cat=mysql_fetch_object($result2);
                echo "<option>$cat->name\n";
            }
            echo "</select>\n";

            echo "<input type=submit name=go value=go>";

            echo "</form>";
            echo "</p>";
            echo "[<a href=videos.php?action=removego&gosorttemp=yes&id=$video->id&annihilate=yes>Delete</a>]<br>";
        }
        else {
            echo "<p>There are no more videos to sort.</p>";
        }

        video_pagefinish();
    }
}


echo "<center>";

if($action=="view"){

    $category=$video->category;
    $res2=sc_query("select * from `videos` where `category`='$category' and `hidden`!='yes' order by `sname` asc");
    $numres2=mysql_num_rows($res2);
    $linkprev="";
    $linknext="";
    
    for($i=0;$i<$numres2;$i++){
        $video2=mysql_fetch_object($res2);
        if($video2->id==$video->id){
            $video2=mysql_fetch_object($res2);
            if(!empty($video2->id)){
                $linknext="[<a href=videos.php?action=view&id=$video2->id>Next</a>]";
                if(!empty($video3->id))
                $linkprev="[<a href=videos.php?action=view&id=$video3->id>Previous</a>]";
                break;
            }
        }
        else{
            $video3=$video2;
        }
    }

    if(empty($linknext))
		if(!empty($video3->id))
			$linkprev="[<a href=videos.php?action=view&id=$video3->id>Previous</a>]";

    echo "<br>$video->sname (contributed by: $vc->name)<br>";

    
    if($video->sfw=="yes"){
        echo "$video->url<br>";
    }
    else{
        if($viewsfw=="yes")
         echo $video->url;
        else
         echo "<a href=videos.php?action=view&id=$video->id&viewsfw=yes><img src=\"$RFS_SITE_URL/images/icons/NSFW.png\" border=0></a><BR>";
    }
	
	echo "<br>";
    echo $linkprev;
    	
    if(  ( $data->id == $video->contributor) ||
		 ( $data->access==255) ) {
			 
        echo "[<a href=videos.php?action=modifyvideo&id=$video->id>Edit</a>]";
        echo "[<a href=videos.php?action=removevideo&id=$video->id>Delete</a>]";
    }
	echo $linknext;
	
	$action="viewcat";
	$cat=$video->category;
	
	
}

echo "<hr>";
if($action=="viewcat") {
	
	$res2=sc_query("select * from `videos` where `category`='$cat' and `hidden`!='yes' order by `sname` asc");
	for($i=0;$i<mysql_num_rows($res2);$i++) {
		$vid=mysql_fetch_object($res2);
		
		
		
		$ytthumb="";
		if(stristr($vid->url,"youtube")) {
			$ytx=explode("\"",$vid->url);
			for($yti=0;$yti<count($ytx);$yti++) {
				if(stristr($ytx[$yti],"youtube")) {
					$ytx2=explode("/",$ytx[$yti]);
					$ytthumb=$ytx2[count($ytx2)-1];
				}
			}
		}
		
		echo "<div style='float: left; width: 100px;'>";
		
		echo "<a href=videos.php?action=view&id=$vid->id>";	
		if($ytthumb)
		echo "<img src=\"http://i1.ytimg.com/vi/$ytthumb/mqdefault.jpg\" width=100><br>";
		echo "$vid->sname</a>";
		
		echo "</div>";
		
	}
	echo "<br style='clear: both;'>";
	
}


echo "<p>[<a href=videos.php?action=random>Random Video</a>]</p>";
echo "<p>[<a href=videos.php?action=submitvid>Submit new Video</a>]</p>";

echo "</center>";
echo "<hr>";

if($data->access==255){
    $res2=sc_query("select * from `videos` where `category`='52'");
    $numvids=mysql_num_rows($res2);
    if($numvids>0)
        echo "<font class=sc_admin>category: !!!TEMP!!! - $numvids videos</font>[<a href=videos.php?action=sorttemp>Sort videos</a>]<br>";
}


$numcols=0;
echo "<table border=0><tr>";
$res=sc_query("select * from `categories` order by name asc");
$numcats=mysql_num_rows($res);
for($i=0;$i<$numcats;$i++){
	
	$cat=mysql_fetch_object($res);
    $res2=sc_query("select * from `videos` where `category`='$cat->id' and `hidden`!='yes' order by `sname` asc");
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
		
			// make videos table...
			if($numvids>5) $numvids=5;
			for($i=0;$i<$numvids;$i++){
				$video=mysql_fetch_object($res2);
				
				
				if($video->sfw=="no")
					$video->url="$RFS_SITE_URL/images/icons/NSFW.gif";
				echo "<table border=0>";
				echo "<tr><td valign=top>";
				echo "<a href=videos.php?action=view&id=$video->id>";
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


video_pagefinish();

?>

