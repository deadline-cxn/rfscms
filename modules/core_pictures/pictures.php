<?
if($_REQUEST['a']=="ms") {
	$mid=$_REQUEST['mid'];
	echo "<img src=\"$RFS_SITE_URL/include/generate.image.php/?download_it_$mid.png&mid=$mid&owidth=512\" border=0></a>";
    exit();
}
if($_REQUEST['action']=="aname") {
	$sname=$_REQUEST['sname'];
	chdir("../../");
	include("include/lib.all.php");
	if(lib_access_check("pictures","edit")) {
		lib_mysql_query("update pictures set sname='$sname' where id='$id'");
		echo $sname;	
	} else echo "You can't edit pictures.";
	exit();
}
if($_REQUEST['action']=="adesc") {
	$desc=$_REQUEST['desc'];
	chdir("../../");
	include("include/lib.all.php");
	if(lib_access_check("pictures","edit")) {
		lib_mysql_query("update pictures set description='$desc' where id='$id'");
		echo $desc;
	} else echo "You can't edit pictures.";
	exit();
}
chdir("../../");
$RFS_LITTLE_HEADER=true;
include("header.php");
if(empty($galleria)) {
	$galleria="no";
	if(lib_rfs_bool_true($RFS_SITE_GALLERIAS))
		$galleria="yes";
}
function pictures_show_buttons() {
	eval(lib_rfs_get_globals());
	echo "<table border=0><tr>"; 
	if(lib_access_check("pictures","orphanscan")) {
		echo "<td>";
		lib_buttons_make_button("$RFS_SITE_URL/modules/core_pictures/pictures.php?action=addorphans","Add Orphans");
		echo "</td>";
	}
	if(lib_access_check("pictures","upload")) {
		echo "<td>";
		lib_buttons_make_button("$RFS_SITE_URL/modules/core_pictures/pictures.php?action=uploadpic","Upload picture");
		echo "</td>";
	}
	if(lib_access_check("pictures","sort")) {
		$res2=lib_mysql_query("select * from `pictures` where `category`='unsorted'");
		$numpics=mysql_num_rows($res2);
		if($numpics>0){
			echo "<td>";
			lib_buttons_make_button("$RFS_SITE_URL/modules/core_pictures/pictures.php?action=sorttemp&category=unsorted","Sort $numpics Pictures");
			echo "</td>";
		}
	}
	echo "</tr></table>"; 
}
$ourl="$RFS_SITE_URL/modules/core_pictures/pictures.php?action=$action&id=$id";
$thumbwidth=200;
$editwidth=256;
$fullsize=512;
function pictures_action_showmemes() {
	eval(lib_rfs_get_globals()); lib_domain_gotopage("$RFS_SITE_URL/modules/memes/memes.php");
}
function pictures_action_uploadpic() {
	eval(lib_rfs_get_globals());
	echo "<h1>Upload a picture</h1>\n";
	lib_forms_build(	"$RFS_SITE_URL/modules/core_pictures/pictures.php",
		"action=uploadpicgo".$RFS_SITE_DELIMITER.
		"MAX_FILE_SIZE=99999999".$RFS_SITE_DELIMITER.
		"SHOW_SELECTOR_categories#name#category#Choose a category".$RFS_SITE_DELIMITER.
		"SHOW_SELECTOR_NOTABLE#IG#hidden#no#yes".$RFS_SITE_DELIMITER.
		"SHOW_SELECTOR_NOTABLE#IG#sfw#yes#no".$RFS_SITE_DELIMITER.
		"SHOW_CLEARFOCUSTEXT_sname=name".$RFS_SITE_DELIMITER.
		"SHOW_TEXTAREA_10#100#desc".$RFS_SITE_DELIMITER.
		"SHOW_FILE_userfile",		
		"",	"",	"",	"",	"",	"","",
		"Upload");
	include("footer.php");
	exit();
}
function pictures_action_uploadpicgo() {
	eval(lib_rfs_get_globals());
	echo "<h1>Uploading picture...</h1>\n";
	$furl="files/pictures/".$_FILES['userfile']['name'];
	$furl=str_replace("//","/",$furl);
	if(move_uploaded_file($_FILES['userfile']['tmp_name'], $furl)) {
		$error="File is valid, and was successfully uploaded. It was stored as [$furl]\n";
		$time1=date("Y-m-d H:i:s");
		$desc=addslashes($desc);		
		$poster=999;
		if($data->id)$poster=$data->id;
		$furl=addslashes($furl);
		lib_mysql_query("INSERT INTO `pictures` (`url`) VALUES('$furl');");
		$id=mysql_insert_id();
		lib_mysql_query("update `pictures` set `category`='$category'  	where `id`='$id'");
		lib_mysql_query("update `pictures` set `sname`='$sname'        where `id`='$id'");
		lib_mysql_query("update `pictures` set `sfw`='$sfw'            where `id`='$id'");
		lib_mysql_query("update `pictures` set `hidden`='$hidden'      where `id`='$id'");
		lib_mysql_query("update `pictures` set description='$desc' 		where `id`='$id'");
		lib_mysql_query("update `pictures` set poster='$poster' 		where `id`='$id'");
		lib_mysql_query("update `pictures` set time = '$time1' 			where `id`='$id'");
		$error.= " ---- Added $name (id:$id) to database ---- ";
	}
	else{
		$error ="File upload error!";
		echo "File upload error! [\n";
		echo $_FILES['userfile']['name']."][".$_FILES['userfile']['error']."][".$_FILES['userfile']['tmp_name']."][ $uploadFile]\n";
	}
	if(!$error){
		$error .= "No files have been selected for upload";
	}
	lib_forms_info("Status: [$error]","WHITE","GREEN");
	pictures_action_view($id);
}
function pictures_action_removepicture() {
	eval(lib_rfs_get_globals());
	echo "<h1>Remove picture: $id</h1>";
	$res=lib_mysql_query("select * from `pictures` where `id`='$id'");
	$picture=mysql_fetch_object($res);
	if( ($data->access==255) ||
		($data->id==$picture->poster) ){	
			
			
		echo lib_images_thumb("$RFS_SITE_URL/$picture->url",200,0,1);
			
		echo "<form enctype=application/x-www-form-URLencoded action=$RFS_SITE_URL/modules/core_pictures/pictures.php method=post>\n";
		echo "<table border=0>\n";
		echo "<input type=hidden name=action value=removego>\n";
		echo "<input type=hidden name=id value=\"$id\">\n";
		echo "<tr><td>Are you sure you want to delete [$picture->id]???</td>";
		echo "<td><input type=submit name=submit value=\"Yes\"></td></tr>\n";
		echo "<tr><td>Annihilate the file from the server?</td>";
		echo "<td><input name=\"annihilate\" type=\"checkbox\" value=\"yes\"></td></tr>\n";
		echo "</table></form>\n";
	} else {
		echo "<p>You do not have picture removal privileges.</p>";
	}
}
function pictures_action_removego() {
	eval(lib_rfs_get_globals());
	$res=lib_mysql_query("select * from `pictures` where `id`='$id'");
	$picture=mysql_fetch_object($res);
	if( ($data->access==255) ||
		($data->id==$picture->poster) )	 {
		
		lib_mysql_query("delete from `pictures` where `id`='$id'");
		echo "<p>Removed $picture->id from the database...</p>";
		if($annihilate=="yes"){
			$ftr=$picture->url;
			$ftr=str_replace($RFS_SITE_URL,$RFS_SITE_PATH,$ftr);
			$ftr=str_replace("//","/",$ftr);
			@unlink($ftr);
			echo "<p> $ftr annihilated!</p>\n";
		}
		if($gosorttemp=="yes") $action="sorttemp";
	} else {
		echo "<p>You do not have picture removal privileges.</p>";
	}
}
function pictures_action_addorphans() {
	eval(lib_rfs_get_globals());
	echo "<h1>Add orphan pictures</h1>";
    if(lib_access_check("pictures","orphanscan")) {
        lib_mysql_query("delete from pictures where category='$category'");
        $dir_count = addorphans("files/pictures",$category);
        if($dir_count==0) echo "No new pictures added to database.<br>";
		else echo "$dir_count new picture(s) added to database from $RFS_SITE_URL/files/pictures/...";
	}
	else {
		lib_forms_warn("You are not authorized to scan orphan pictures.");
	}
	include("footer.php");
}
function pictures_action_sorttemp() {
	eval(lib_rfs_get_globals());
	if(lib_access_check("pictures","sort")) {
        if($subact=="place"){
            if(!empty($newcat)) {
                lib_mysql_query("insert into categories (`name`) VALUES('$newcat'); ");
                $category=$newcat;
            }

			$res=lib_mysql_query("select * from `pictures` where `category`='$category' order by time asc");
			lib_mysql_query("update `pictures` set `category`='$category' where `id`='$id'");
			$sname=addslashes($sname);
			lib_mysql_query("update `pictures` set `sname`='$sname' where `id`='$id'");
			lib_mysql_query("update `pictures` set `sfw`='$sfw' where `id`='$id'");
			lib_mysql_query("update `pictures` set `hidden`='$hidden' where `id`='$id'");
		}

		$res=lib_mysql_query("select * from `pictures` where `category`='unsorted' order by time asc");
		$numpics=mysql_num_rows($res);
		if($numpics>0){
            $picture=mysql_fetch_object($res);
            echo "<p align=center>$picture->url<br>";
            echo "<table border=0><tr><td width=610 valign=top>";
            echo "<center><a href='$RFS_SITE_URL/modules/core_pictures/pictures.php?action=removego&gosorttemp=yes&id=$picture->id&annihilate=yes'><img src=$RFS_SITE_URL/images/icons/Delete.png border=0><br>Delete (Warning there is no confirmation)</a><br>";
			$size = getimagesize($picture->url);
			$nw=$size[0]; $nh=$size[1]; if($nw>600) $w=600; if($nh>600) $h=600;
            echo "<img src=\"$RFS_SITE_URL/$picture->url\" ";
			if($w) echo "width='$w' ";
			if($h) echo "height='$h' ";
			echo " border=0> </center>";
            echo "</td><td>";
			$w=""; $h="";
			echo "Select a category:<br>";
            $rc=lib_mysql_query("select * from categories where name != 'unsorted' order by name"); 
            $rn=mysql_num_rows($rc);

			for($ri=0;$ri<$rn;$ri++) {
				echo "<div style='float: left; padding: 10px; text-align: center; width: 80px; height: 120px;'>";
				$incat=mysql_fetch_object($rc);
				$imout=$incat->image;
				if(!file_exists($RFS_SITE_PATH."/$incat->image"))
					$imout="images/noimage_file.gif";
				if(!$incat->image)
					$imout="images/noimage.gif";
				echo "<a href='$RFS_SITE_URL/modules/core_pictures/pictures.php?action=sorttemp&subact=place&id=$picture->id&category=$incat->name&sname=$picture->sname&sfw=yes'>";
				echo "<img src='$RFS_SITE_URL/$imout' width=70 height=70><br>$incat->name</a>";
				echo "</div>";
			}
            echo "</td></tr></table>";
			echo "<form enctype=application/x-www-form-URLencoded method=post action=$RFS_SITE_URL/modules/core_pictures/pictures.php>";
			echo "<input type=hidden name=action value=sorttemp>";
			echo "<input type=hidden name=subact value=place>";
			echo "<input type=hidden name=id value=\"$picture->id\">";
			echo "Short Name<input name=sname value=\"$picture->sname\">";
			echo "Description<textarea name=description rows=5 cols=10>$picture->description</textarea>";           
			echo "Safe For Work<select name=sfw>";			
            if(!empty($picture->sfw)) echo "<option>$picture->sfw";            
			echo "<option>yes<option>no</select>";
			echo "Hidden<select name=hidden>";

			echo "<option>no<option>yes</select>";
			$cat=mysql_fetch_object(lib_mysql_query("select * from `categories` where `name`='$picture->category'"));
			echo "<select name=category>\n";
			echo "<option>Funny<option>$cat->name\n";
			$result2=lib_mysql_query("select * from categories order by name asc");
			$numcats=mysql_num_rows($result2);
			for($i2=0;$i2<$numcats;$i2++){
				$cat=mysql_fetch_object($result2);
				echo "<option>$cat->name\n";}
			echo "</select>\n";
			
			echo " (Or New category)<input name=newcat value=\"\">";
			
			echo "<input type=submit name=go value=go>";
			echo "</form>";
			echo "</p>";
			
		}
		else{
			echo "<p>There are no more pictures to sort.</p>";
		}
	}
	else {
		lib_forms_warn("You are not authorized to sort pictures.");
	}
	include("footer.php");
}
function pictures_action_modifynamego() {
	eval(lib_rfs_get_globals());
    if(lib_access_check("pictures","edit")) {
        $sname=addslashes($sname);
        if($id)
        lib_mysql_query("update `pictures` set `sname`='$sname' where `id`='$id'");
    }
	else {
		lib_forms_warn("You are not authorized to modify pictures.");
	}
	pictures_action_view($id);
}
function pictures_action_modifydescriptiongo() {
	eval(lib_rfs_get_globals());
    if(lib_access_check("pictures","edit")) {
        $description=addslashes($description);
        if($id)
        lib_mysql_query("update `pictures` set `description`='$description'     where `id`='$id'");
    }
	else {
		lib_forms_warn("You are not authorized to modify pictures.");
	}
	pictures_action_view($id);
}
function pictures_action_modifygo(){
	eval(lib_rfs_get_globals());
    if(lib_access_check("pictures","edit")) {
		lib_mysql_query("update `pictures` set `category`='$category' where `id`='$id'");
		$sname=addslashes($sname);
		lib_mysql_query("update `pictures` set `sname`='$sname'     where `id`='$id'");
		lib_mysql_query("update `pictures` set `sfw`='$sfw'         where `id`='$id'");
		lib_mysql_query("update `pictures` set `hidden`='$hidden'   where `id`='$id'");
		lib_mysql_query("update `pictures` set `category`='$category' where `id`='$id'");
		lib_mysql_query("update `pictures` set `poster`='$poster'   where `id`='$id'");
		lib_mysql_query("update `pictures` set `lastupdate`='$time' where `id`='$id'");
		lib_mysql_query("update `pictures` set `url`='$gurl'        where `id`='$id'");
		lib_mysql_query("update `pictures` set `rating`='$rating'   where `id`='$id'");
		lib_mysql_query("update `pictures` set `views`='$views'     where `id`='$id'");
		$description=addslashes($description);
		lib_mysql_query("update `pictures` set `description`='$description' where `id`='$id'");
	}
	else {
		lib_forms_warn("You are not authorized to modify pictures.");
	}
	pictures_action_view($id);
}
function pictures_action_modifypicture() {
	eval(lib_rfs_get_globals());
    if(lib_access_check("pictures","edit")) {
		$res=lib_mysql_query("select * from `pictures` where `id`='$id'");
		$picture=mysql_fetch_object($res);
		echo "<center><img src=$RFS_SITE_URL/$picture->url height=$editwidth>";
		echo "<form enctype=application/x-www-form-URLencoded method=post action=$RFS_SITE_URL/modules/core_pictures/pictures.php>";
		echo "<table border=0>";
		echo "<input type=hidden name=action value=modifygo>";
		echo "<input type=hidden name=id value=\"$picture->id\">";
		echo "<tr><td class=contenttd align=right>Short Name:</td><td class=contenttd><input size=60 name=sname value=\"$picture->sname\"></td></tr>";
		echo "<tr><td class=contenttd align=right>Location:</td><td class=contenttd><input size=60 name=gurl value=\"$picture->url\"></td></tr>";
		echo "<tr><td class=contenttd align=right>";
		echo "Category:";
		echo "</td><td class=contenttd>";
		$cat=mysql_fetch_object(lib_mysql_query("select * from `categories` where `name`='$picture->category'"));
		echo "<select name=category>";
		echo "<option>$cat->name";
		$result2=lib_mysql_query("select * from categories order by name asc");
		while($cat=mysql_fetch_object($result2)) echo "<option>$cat->name";
		echo "</select>\n";
		echo "</td></tr>";
		echo "<tr><td class=contenttd>";
		echo "Safe For Work:</td><td class=contenttd><select name=sfw>";
		if(!empty($picture->sfw)) echo "<option>$picture->sfw";
		echo "<option>yes<option>no</select>";
		echo "</td></tr>";
		echo "<tr><td class=contenttd align=right>";
		echo "Hidden:</td><td class=contenttd><select name=hidden>";
        if(!empty($picture->hidden)) echo "<option>$picture->hidden";
		echo "<option>no<option>yes</select>";
		echo "</td></tr>";
		echo "<tr><td class=contenttd align=right>Poster:</td><td class=contenttd>";
		echo "<select name=poster>";
			$poster=lib_users_get_data($picture->poster);
		echo "<option>$poster->name";
		$result2=lib_mysql_query_user_db("select * from `users` order by name asc");
		while($usr=mysql_fetch_object($result2)) echo "<option value='$usr->id'>$usr->name";
		echo "</select>\n";
		echo "</td></tr>\n";
		echo "<tr><td class=contenttd align=right>Rating:</td><td class=contenttd><input name=rating value=\"$picture->rating\"></td></tr>";
		echo "<tr><td class=contenttd align=right>Description:</td><td class=contenttd><textarea name=description rows=8 cols=80>$picture->description</textarea></td></tr>";
		echo "<tr><td class=contenttd></td><td class=contenttd>";
		echo "<input type=submit name=go value=go>";
		
		echo "</td></tr></table>";
		echo "</form>";
	}
	include("footer.php");
	exit();
}
function pictures_action_random() {
	eval(lib_rfs_get_globals());
    $res=lib_mysql_query("select * from `pictures` where `hidden`!='yes'");
    $num=mysql_num_rows($res);
    if($num>0) {
        $pict=rand(1,($num))-1;
        mysql_data_seek($res,$pict);
        $pic=mysql_fetch_object($res);
        pictures_action_view($pic->id);
    }
}
function pictures_action_view($id) {
	echo "<h1>Pictures</h1>";
	pictures_show_buttons();
	eval(lib_rfs_get_globals());
    $res=lib_mysql_query("select * from `pictures` where `id`='$id' order by time asc");
    $picture=mysql_fetch_object($res);
	$category=$picture->category;
    $res2=lib_mysql_query("select * from `pictures` where `category`='$category' and `hidden`!='yes' order by `sname` asc");
    $numres2=mysql_num_rows($res2);
    $linkprev="";
    $linknext="";
    for($i=0;$i<$numres2;$i++)    {
        $picture2=mysql_fetch_object($res2);
        if($picture2->id==$picture->id)        {
            $picture2=mysql_fetch_object($res2);
            if(!empty($picture2->id))            {
					$linknext="$RFS_SITE_URL/modules/core_pictures/pictures.php?action=view&id=$picture2->id";
                if(!empty($picture3->id))
					$linkprev="$RFS_SITE_URL/modules/core_pictures/pictures.php?action=view&id=$picture3->id";
                break;
            }
        }
        else        {
            $picture3=$picture2;
        }
    }
	echo "<center><table border=0><tr>";
	if(!empty($picture3->id)) {
		echo "<td>";
		lib_buttons_make_button($linkprev,"Previous");
		echo "</td>";
	}    
    if($id) {
		if(lib_rfs_bool_true($RFS_SITE_CAPTIONS)){
			echo "<td>";
			lib_buttons_make_button("$RFS_SITE_URL/modules/memes/memes.php?action=memegenerate&basepic=$picture->id","Caption");
			echo "</td>";
		}
		echo "<td>";
		lib_buttons_make_button("$RFS_SITE_URL/modules/core_pictures/pictures.php?action=random","Random Picture");
		echo "</td>";
		if(lib_access_check("pictures","edit")) {
			echo "<td>";
			lib_buttons_make_button("$RFS_SITE_URL/modules/core_pictures/pictures.php?action=modifypicture&id=$picture->id","Edit");
			echo "</td>";
		}			
		if(lib_access_check("pictures","delete")) {
			echo "<td>";
			lib_buttons_make_button("$RFS_SITE_URL/modules/core_pictures/pictures.php?action=removepicture&id=$picture->id","Delete");
			echo "</td>";
		}			
		echo "<td>";
		if(!empty($picture2->id))
			lib_buttons_make_button($linknext,"Next");
		echo "</td>";
		echo "</tr></table></center>";	
		echo "<center>";
		$categorym=lib_mysql_fetch_one_object("select * from categories where name='$category'");
		if(!empty($categorym->name)) echo "Category: $categorym->name<br>";	
		if(empty($picture->sname)) {
			if(lib_access_check("pictures","edit")) {
				lib_ajax("Short Name,70","pictures","id","$id","sname",70,"","pictures","edit","");
			}
		}
		else {
			echo $picture->sname;
		}
		echo "<br>";
		if(empty($picture->description)) {		
			if(lib_access_check("pictures","edit")) {
				lib_ajax("Description,70","pictures","id","$id","description","5,70","textarea","pictures","edit","");
			}
		}
		else {
			echo $picture->description;
		}
		echo "<br>";
		if($picture->sfw=="yes") {
			$size = getimagesize("$RFS_SITE_PATH/$picture->url");
			$nw=$size[0]; $nh=$size[1];
			if($nw>1000) $w=1000;
			else if($nh>1000) $h=1000;
			if(!stristr($picture->url,$RFS_SITE_URL))
				$picture->url="$RFS_SITE_URL/$picture->url";
			echo "<a href=\"$picture->url\" target=_blank>";
			echo "<img src=\"$picture->url\" ";
			if($w) echo "width='$w' ";
			if($h) echo "height='$h' ";
			echo " style=' border:solid 1px #222222; border-radius:15px; ' border=0>";
			echo "</a>";
			$w=''; $h='';
		}
		else {
			if($viewsfw=="yes") echo "<img src=\"$picture->url\">";				
			else echo "<a href='$RFS_SITE_URL/modules/core_pictures/pictures.php?action=view&id=$picture->id&viewsfw=yes'><img src=\"$RFS_SITE_URL/files/pictures/NSFW.gif\" border=0></a>";		
		}
		echo "<p>&nbsp;</p>";
			$page="$RFS_SITE_URL/modules/core_pictures/pictures.php?action=view&id=$picture->id";	
			lib_social_facebook_comments($page);
	}
	else {
		echo "<h1>There are no pictures!</h1>";
	}
	echo "</center>";
	include("footer.php");
	
}
function pictures_action_viewcat($cizat) {
	echo "<h1>Pictures</h1>";
	pictures_show_buttons();
	eval(lib_rfs_get_globals());	
	if(empty($cat)) $cat=$_REQUEST['cat'];
	if(empty($cat)) $cat=$cizat;
	if($ipr) $ipr=lib_mysql_fetch_one_object("select * from pictures where id=$id");
	else $ipr=lib_mysql_fetch_one_object("select * from pictures where category='$cat'");
	$catn=lib_mysql_fetch_one_object("select * from categories where name='$cat'");
    $cati=lib_mysql_fetch_one_object("select * from categories where id='$cat'");
	if($catn->id) $cat=$catn;
	if($cati->id) $cat=$cati;
    if(!empty($cat->name)) echo "<center><font class=th>Category: $cat->name</font></center>";
    $r=lib_mysql_query("select * from `pictures` where `category`='$cat->name' and `hidden`!='yes' order by `sname` asc");
	$numpics=mysql_num_rows($r);	
	echo "<center>";			

	if(lib_rfs_bool_true($RFS_SITE_GALLERIAS)){
		echo "<script src=\"$RFS_SITE_URL/3rdparty/galleria/galleria-1.2.9.min.js\"></script>";
		echo "<style>
				#galleria{
					width: 800px;
					height:
					600px;
					background: #000;
					}
				</style> ";
		echo "<div id=\"galleria\">";					
		echo "<img src=\"$RFS_SITE_URL/$ipr->url\"
		data-title=\"$ipr->sname\"
		data-description=\"$ipr->description\"
		> ";
	}

    while($picture=mysql_fetch_object($r)) {
		if($picture->sfw=="no")
			$picture->url="files/pictures/NSFW.gif";			
		if(!empty($picture->url)) {
			$img="$RFS_SITE_URL/$picture->url";
			if(lib_rfs_bool_true($RFS_SITE_GALLERIAS)){
				if($ipr->id!=$picture->id)				
					echo "<img src=\"$img\"
						data-title=\"$picture->sname\"
						data-description=\"$picture->description\">";
			}
			else {
				echo "<a href='$RFS_SITE_URL/modules/core_pictures/pictures.php?action=view&id=$picture->id'>";
				echo lib_images_thumb($img,96,0,1);
				echo "</a>\n";
			}
		}
    }
	
	//if($galleria=="yes")
	if(lib_rfs_bool_true($RFS_SITE_GALLERIAS)) {
		echo "</div><script>Galleria.loadTheme('$RFS_SITE_URL/3rdparty/galleria/themes/classic/galleria.classic.js');
				Galleria.run('#galleria'); </script>";
	}
	echo "</center>";
	include("footer.php");
	exit();
}
function pictures_action_viewcats() {
	eval(lib_rfs_get_globals());
	if(!$donotshowcats) {
		$res=lib_mysql_query("select * from `categories` order by name asc");
		echo "<table border=0 width=100%>";
		$numcols=0;
		
		echo "<tr>";
		while($cat=mysql_fetch_object($res)) {
			$res2=lib_mysql_query("select * from `pictures` where `category`='$cat->name' and `hidden`!='yes' order by `sname` asc limit 6");
			$numpics=mysql_num_rows($res2);
			if($numpics>0) {
				echo "<td class=contenttd>";
				echo "<table border=0 cellspacing=0 cellpadding=3 width=100%><tr><td class=contenttd>";
				echo "<table border=0 cellspacing=0 cellpadding=0 width=100% ><tr>";
				echo "<td class=contenttd valign=top width=220>";
				if(empty($cat->image)) $cat->image="buttfea2.gif";
				if(!file_exists("$RFS_SITE_PATH/$cat->image"))
					$cat->image="images/icons/404.png";
				echo "<a href='$RFS_SITE_URL/modules/core_pictures/pictures.php?action=viewcat&cat=$cat->id'><h1><img src='$RFS_SITE_URL/$cat->image' border=0 width=64 height=64>$cat->name ($numpics)</h1></a><br>";
				echo "</td></tr>";
				echo "<tr>";
				echo "<td class=contenttd valign=top height=200 width=220>";
				while($picture=mysql_fetch_object($res2)) {
					if($picture->sfw=="no") $picture->url="$RFS_SITE_URL/files/pictures/NSFW.gif";
					echo "<a href='$RFS_SITE_URL/modules/core_pictures/pictures.php?action=view&id=$picture->id'>";
					$img=$RFS_SITE_URL."/".$picture->url;
					echo lib_images_thumb($img,96,0,1);
					echo "</a>\n";
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
		echo "</tr></table>";
	}
	include("footer.php");
	exit();
}
function pictures_action_() {
	eval(lib_rfs_get_globals());
	echo "<h1>Pictures</h1>";
	pictures_show_buttons();
	pictures_action_viewcats();
}

?>
