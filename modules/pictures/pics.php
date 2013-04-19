<?
if($_REQUEST['a']=="ms") {
	$id=$_REQUEST['id'];
	echo "<img src=\"$RFS_SITE_URL/include/generate.image.php/?download_it_$id.png&id=$id&owidth=512\" border=0></a>";
    exit();
}
if($_REQUEST['action']=="aname") {
	$sname=$_REQUEST['sname'];
	chdir("../../");
	include("include/lib.all.php");
	if(sc_access_check("pictures","edit")) {
		sc_query("update pictures set sname='$sname' where id='$id'");
		echo $sname;	
	} else echo "You can't edit pictures.";
	exit();
}
if($_REQUEST['action']=="adesc") {
	$desc=$_REQUEST['desc'];
	chdir("../../");
	include("include/lib.all.php");
	if(sc_access_check("pictures","edit")) {
		sc_query("update pictures set description='$desc' where id='$id'");
		echo $desc;
	} else echo "You can't edit pictures.";
	exit();
}

chdir("../../");
include("header.php");

if(empty($galleria)) $galleria="yes";

echo "<table border=0><tr>"; 

if(sc_access_check("pictures","orphanscan")) {
	echo "<td>";
	sc_button("$RFS_SITE_URL/modules/pictures/pics.php?action=addorphans","Add Orphans");
	echo "</td>";
}
if(sc_access_check("pictures","upload")) {
	echo "<td>";
    sc_button("$RFS_SITE_URL/modules/pictures/pics.php?action=uploadpic","Upload picture");
	echo "</td>";
}
if(sc_access_check("pictures","sort")) {
    $cr=mfo1("select * from categories where name='!!!TEMP!!!'");
    $res2=sc_query("select * from `pictures` where `category`='$cr->id'");
    $numpics=mysql_num_rows($res2);
    if($numpics>0){
		echo "<td>";
		sc_button("$RFS_SITE_URL/modules/pictures/pics.php?action=sorttemp","Sort $numpics Pictures");
		echo "</td>";
	}
	
}


echo "</tr></table>"; 

$ourl="$RFS_SITE_URL/modules/pictures/pics.php?action=$action&id=$id";

if(empty($action))       $action="random";
if(!empty($id))          $res=sc_query("select * from `pictures` where `id`='$id'");
if($res)                 $picture=mysql_fetch_object($res);
if(!empty($picture->id)) $category=mysql_fetch_object(sc_query("select * from `categories` where `id`='$picture->category'"));

$mcols=5;
$mrows=6;
$toget=$mcols*$mrows;

$thumbwidth=200;
$editwidth=256;
$fullsize=512;

/////////////////////////////////////////////////////////////////////////////////
// Upload picture
if($action=="uploadpic"){
			if($memeit=="yes") {
				$donotshowcats=true;
				echo "<p>Select a file to use for the caption.</p>\n";
			}
			else{
				echo "<p>Upload a picture</p>\n";
			}
        echo "<table border=0>\n";
        echo "<form  enctype=\"multipart/form-data\" action=\"$RFS_SITE_URL/modules/pictures/pics.php\" method=\"post\">\n";
        echo "<input type=hidden name=action value=uploadpicgo>\n";
		 echo "<input type=hidden name=memeit value=$memeit>\n";
		
        echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"93000000\">";
        echo "<tr><td align=right>Select file:      </td><td ><input name=\"userfile\" type=\"file\" size=80> </td></tr>\n";
       
        echo "<tr><td align=right>Safe for work:    </td><td><select name=sfw><option>yes<option>no</select></td></tr>\n";
       
	   echo "
	   <tr>
	   <td align=right>Hide from public: </td>
	   <td><select name=hidden><option>no<option>yes</select> </td>
	   </tr>
	   ";
	if($memeit=="yes") {
		
		echo "<input type=hidden name=category value=Meme>";
		echo "<input type=hidden name=hidden value=no>";
	}
	else {
		 
		echo "<tr><td align=right>Gallery:         </td><td><select name=category>\n";
        $result=sc_query("select * from categories order by name asc"); $numcats=mysql_num_rows($result);
        for($i=0;$i<$numcats;$i++) { $cat=mysql_fetch_object($result); echo "<option>$cat->name"; }
        echo "</select></td></tr>\n";		
	}
		
        echo "<tr><td align=right>Short name :</td><td><input type=textbox name=sname value=\"$name\"></td></tr>\n";
        echo "<tr><td align=right valign=top>Description:</td><td><textarea name=\"desc\" rows=\"7\" cols=\"40\"></textarea></td></tr>\n";
        echo "<tr><td>&nbsp;</td><td><input type=\"submit\" name=\"submit\" value=\"Upload!\"></td></tr>\n";
        echo "</form>\n";
        echo "</table>\n";
    }
/////////////////////////////////////////////////////////////////////////////////
// Upload picture confirm
    if($action=="uploadpicgo"){
            echo "Uploading picture...\n";
            $furl="files/pictures/".$_FILES['userfile']['name'];
            $furl =str_replace("//","/",$furl);
            if(move_uploaded_file($_FILES['userfile']['tmp_name'], $furl))            {
                $error="File is valid, and was successfully uploaded. ";
                $error.="It was stored as [$furl]\n";
                $xp_ext = explode(".",$_FILES['userfile']['name'],40);
                $j = count ($xp_ext)-1;
                $ext = "$xp_ext[$j]";
                $filetype=strtolower($ext);
                $filesizebytes=$_FILES['userfile']['size'];
                $time1=date("Y-m-d H:i:s");
                $description=addslashes($description);
                if(empty($name)) $name=$sname;
                $poster=999;
                if($data->id)$poster=$data->id;
                sc_query("INSERT INTO `pictures` (`name`) VALUES('$name');");
                $cid=mysql_fetch_object(sc_query("select * from categories where name = '$category'"));
                sc_query("update `pictures` set `category`='$cid->id'  where `name`='$name'");
                sc_query("update `pictures` set `sname`='$sname'        where `name`='$name'");
                sc_query("update `pictures` set `sfw`='$sfw'            where `name`='$name'");
                sc_query("update `pictures` set `hidden`='$hidden'      where `name`='$name'");
                sc_query("update `pictures` set description='$desc' where name='$name'");
                sc_query("update `pictures` set poster='$poster' where name='$name'");
                $furl=addslashes($furl);
                sc_query("update `pictures` set url = '$furl' where name='$name'");
                sc_query("update `pictures` set time = '$time1' where name='$name'");
                $error.= " ---- Added $name to database ---- ";
				if(!empty($memeit)) {
					$p=mfo1("select * from pictures where sname='$sname'");
					$id=$p->id;
					$action="memegenerate";
					$private=$hidden;
				}
            }
            else{
                $error ="File upload error!";
                echo "File upload error! [\n";
                echo $_FILES['userfile']['name'];
                echo "][";
                echo $_FILES['userfile']['error'];
                echo "][";
                echo $_FILES['userfile']['tmp_name'] ;
                echo "][";
                echo $uploadFile;
                echo "]\n";
            }
            if(!$error){
                $error .= "No files have been selected for upload";
            }
            sc_info("Status: [$error]","WHITE","GREEN");
           // echo "<p>[<a href=$RFS_SITE_URL/files.php?action=upload>Add another file</a>]\n";

    }
/////////////////////////////////////////////////////////////////////////////////
// Remove picture	confirm
if($action=="removepicture"){
	$res=sc_query("select * from `pictures` where `id`='$id'");
	$picture=mysql_fetch_object($res);
	if( ($data->access==255) ||
		($data->id==$picture->poster)
		)	 {
		echo "<table border=0>\n";
		echo "<form enctype=application/x-www-form-URLencoded action=$RFS_SITE_URL/modules/pictures/pics.php method=post>\n";
		echo "<input type=hidden name=action value=removego>\n";
		echo "<input type=hidden name=id value=\"$id\">\n";
		echo "<tr><td>Are you sure you want to delete [$picture->sname]???</td>";
		echo "<td><input type=submit name=submit value=\"Yes\"></td></tr>\n";
		echo "<tr><td>Annihilate the file from the server?</td>";
		echo "<td><input name=\"annihilate\" type=\"checkbox\" value=\"yes\"></td></tr>\n";
		echo "</form></table>\n";
	} else {
		echo "<p>You do not have picture removal privileges.</p>";
	}
}
/////////////////////////////////////////////////////////////////////////////////
// Remove picture	confirm
if($action=="removego"){
	$res=sc_query("select * from `pictures` where `id`='$id'");
	$picture=mysql_fetch_object($res);
	if( ($data->access==255) ||
		($data->id==$picture->poster)
		)	 {
		sc_query("delete from `pictures` where `id`='$id'");
		echo "<p>Removed $picture->sname from the database...</p>";
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
/////////////////////////////////////////////////////////////////////////////////
// Add orphans
function addorphans($folder,$cat) {
        $dir_count=0;
        $dirfiles = sc_folder_to_array($folder);        
        while(list ($key, $file) = each ($dirfiles)){
            if($file!="."){
                if($file!="..")
                    if( ($file!="rendered") &&
                        ($file!="cache") ) {
                    
                    $dircheck= $folder."/".$file;
                    $dircheck=str_replace("../","",$dircheck);
					
                    if(is_dir($dircheck)) {
                        echo "$dircheck is a folder... checking<br>";
                        $dir_count += addorphans("$dircheck",$cat);  
                    } 
                    if( (sc_getfiletype($file)=="gif") ||
						   (sc_getfiletype($file)=="png") ||
                        (sc_getfiletype($file)=="jpg") ) {
                        // $ofolder=str_replace($GLOBALS['RFS_SITE_PATH'],"",$folder);
                        $url = "$folder/$file";
                        $res=sc_query("select * from `pictures` where `url`='$url'");
                        if(!mysql_num_rows($res)) {
                            $time=date("Y-m-d H:i:s");
                            sc_query("insert into `pictures` (`time`,`url`,`category`,`hidden`)
                                                       VALUES('$time','$url','$cat','yes')");
                            echo "Added [$url] to database<br>";
                            $dir_count++;
                        }
                    }
                }
            }
        }
		return $dir_count;
}
if($action=="addorphans"){
    if ($data->access==255) {
        $categoryz=mysql_fetch_object(sc_query("select * from `categories` where `name`='!!!TEMP!!!'"));
        $category=$categoryz->id;
        sc_query("delete from pictures where category='$category'");
        $dir_count = addorphans("files/pictures",$category);
        if($dir_count==0) echo "No new pictures added to database.<br>";
		 else echo "$dir_count new picture(s) added to database from $RFS_SITE_URL/files/pictures/...";
	}
}
/////////////////////////////////////////////////////////////////////////////////
// Sort !!!TEMP!!! category
if($action=="sorttemp"){
	
	if ($data->access==255) {
        if($subact=="place"){
            if(!empty($newcat)) {
                sc_query("insert into categories
                        (`name`)
                    VALUES('$newcat'); ");
                $categorey=$newcat;
            }

			$categoryz=mysql_fetch_object(sc_query("select * from `categories` where `name`='$categorey'"));
			$category=$categoryz->id;
			$res=sc_query("select * from `pictures` where `category`='$category' order by time asc");
			sc_query("update `pictures` set `category`='$category' where `id`='$id'");
			$sname=addslashes($sname);
			sc_query("update `pictures` set `sname`='$sname' where `id`='$id'");
			sc_query("update `pictures` set `sfw`='$sfw' where `id`='$id'");
			sc_query("update `pictures` set `hidden`='$hidden' where `id`='$id'");
		}

		$categoryz=mysql_fetch_object(sc_query("select * from `categories` where `name`='!!!TEMP!!!'"));
		$category=$categoryz->id;
		$res=sc_query("select * from `pictures` where `category`='$category' order by time asc");
		$numpics=mysql_num_rows($res);
		if($numpics>0){
            $picture=mysql_fetch_object($res);
            echo "<p align=center>$picture->url<br>";

            echo "<table border=0><tr><td width=610 valign=top>";
            
            echo "<center><a href='$RFS_SITE_URL/modules/pictures/pics.php?action=removego&gosorttemp=yes&id=$picture->id&annihilate=yes'><img src=$RFS_SITE_URL/images/icons/Delete.png border=0><br>Delete (Warning there is no confirmation)</a><br>";

			$size = getimagesize($picture->url);
			$nw=$size[0]; $nh=$size[1]; if($nw>600) $w=600; if($nh>600) $h=600;

            echo "<img src=\"$RFS_SITE_URL/$picture->url\" ";
			if($w) echo "width='$w' ";
			if($h) echo "height='$h' ";
			echo " border=0> </center>";
            echo "</td><td>";
			$w=""; $h="";
                echo "Select a category:<br>";

                $rc=sc_query("select * from categories order by name"); 
                $rn=mysql_num_rows($rc);

		// echo "<table border=0><tr>";
		// $table_row_counter=0;

        for($ri=0;$ri<$rn;$ri++) {
          // echo "<td>";

            echo "<div style='float: left; padding: 10px; text-align: center; width: 80px; height: 120px;'>";

            $incat=mysql_fetch_object($rc);
            $imout=$incat->image;
            if(!file_exists($RFS_SITE_PATH."/$incat->image"))
                    $imout="images/noimage_file.gif";
            if(!$incat->image)
                $imout="images/noimage.gif";

            echo "<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=sorttemp&subact=place&id=$picture->id&categorey=$incat->name&sname=$picture->sname&sfw=yes'>";
            echo "<img src='$RFS_SITE_URL/$imout' width=70 height=70><br>$incat->name</a>";

            echo "</div>";
            // echo "</td>";
          // $table_row_counter++;
          //if($table_row_counter > 8) {
            //   $table_row_counter=0;
               //echo "</tr><tr>";
         //}
        }

		// echo "</tr></td></table>";


            echo "</td></tr></table>";

			echo "<form enctype=application/x-www-form-URLencoded method=post action=$RFS_SITE_URL/modules/pictures/pics.php>";
			echo "<input type=hidden name=action value=sorttemp>";
			echo "<input type=hidden name=subact value=place>";
			echo "<input type=hidden name=id value=\"$picture->id\">";
			echo "Short Name<input name=sname value=\"$picture->sname\">";
			echo "Description<textarea name=description rows=5 cols=10>$picture->description</textarea>";           
			echo "Safe For Work<select name=sfw>";			
            if(!empty($picture->sfw)) echo "<option>$picture->sfw";            
			echo "<option>yes<option>no</select>";
			echo "Hidden<select name=hidden>";
			
            // if(!empty($picture->hidden))            echo "<option>$picture->hidden";
            
			echo "<option>no<option>yes</select>";
			$cat=mysql_fetch_object(sc_query("select * from `categories` where `id`='$picture->category'"));
			
			echo "<select name=categorey>\n";
			echo "<option>Funny<option>$cat->name\n";
			$result2=sc_query("select * from categories order by name asc");
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
}
/////////////////////////////////////////////////////////////////////////////////
// Modify picture information confirm
if($action=="modifynamego") {
    if ($data->access==255) {
        $sname=addslashes($sname);
        if($id)
        sc_query("update `pictures` set `sname`='$sname'     where `id`='$id'");
    }
    $action="view";    
}
if($action=="modifydescriptiongo") {
    if ($data->access==255) {
        $description=addslashes($description);
        if($id)
        sc_query("update `pictures` set `description`='$description'     where `id`='$id'");
    }
    $action="view";
}
if($action=="modifygo"){
	if ($data->access==255) {
		$categoryz=mysql_fetch_object(sc_query("select * from `categories` where `name`='$categorey'"));
		$category=$categoryz->id;
		sc_query("update `pictures` set `category`='$category' where `id`='$id'");
		$sname=addslashes($sname);
		sc_query("update `pictures` set `sname`='$sname'     where `id`='$id'");
		sc_query("update `pictures` set `sfw`='$sfw'         where `id`='$id'");
		sc_query("update `pictures` set `hidden`='$hidden'   where `id`='$id'");
		sc_query("update `pictures` set `gallery`='$gallery' where `id`='$id'");
		sc_query("update `pictures` set `poster`='$poster'   where `id`='$id'");
		sc_query("update `pictures` set `lastupdate`='$time' where `id`='$id'");
		sc_query("update `pictures` set `url`='$gurl'        where `id`='$id'");
		sc_query("update `pictures` set `rating`='$rating'   where `id`='$id'");
		sc_query("update `pictures` set `views`='$views'     where `id`='$id'");
		$description=addslashes($description);
		sc_query("update `pictures` set `description`='$description' where `id`='$id'");
	}
}
/////////////////////////////////////////////////////////////////////////////////
// Modify picture information
if($action=="modifypicture"){
	if ($data->access==255) {
		$res=sc_query("select * from `pictures` where `id`='$id'");
		$picture=mysql_fetch_object($res);
		echo "<center><img src=$RFS_SITE_URL/$picture->url height=$editwidth>";
		echo "<table border=0>";
		echo "<form enctype=application/x-www-form-URLencoded method=post action=$RFS_SITE_URL/modules/pictures/pics.php>";
		echo "<input type=hidden name=action value=modifygo>";
		echo "<input type=hidden name=id value=\"$picture->id\">";
		echo "<tr><td class=contenttd align=right>Short Name:</td><td class=contenttd><input size=60 name=sname value=\"$picture->sname\"></td></tr>";
		echo "<tr><td class=contenttd align=right>Location:</td><td class=contenttd><input size=60 name=gurl value=\"$picture->url\"></td></tr>";
		echo "<tr><td class=contenttd align=right>";
		echo "Category:";
		echo "</td><td class=contenttd>";
		$cat=mysql_fetch_object(sc_query("select * from `categories` where `id`='$picture->category'"));

		 echo "<select name=categorey>";
		echo "<option>$cat->name";
		$result2=sc_query("select * from categories order by name asc");
		$numcats=mysql_num_rows($result2);
		for($i2=0;$i2<$numcats;$i2++){
			$cat=mysql_fetch_object($result2);
			echo "<option>$cat->name";
		}
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


		echo "<tr><td class=contenttd align=right>Poster:</td><td class=contenttd>";//<input name=poster value=\"$picture->poster\"></td></tr>";

		echo "<select name=poster>";
			$poster=sc_getuserdata($picture->poster);

		echo "<option>$poster->name";
		$result2=sc_query_user_db("select * from users order by name asc");
		
		$numusrs=mysql_num_rows($result2);
		
		for($i2=0;$i2<$numusrs;$i2++){
			$usr=mysql_fetch_object($result2);
			echo "<option value='$usr->id'>$usr->name";
		}
		echo "</select>\n";

		echo "</td></tr>\n";

	   //  echo "<tr><td class=contenttd align=right>Gallery:</td><td class=contenttd><input name=gallery value=\"$picture->gallery\"></td></tr>";


		echo "<tr><td class=contenttd align=right>Rating:</td><td class=contenttd><input name=rating value=\"$picture->rating\"></td></tr>";
		// echo "<tr><td class=contenttd align=right>Views:</td><td class=contenttd><input name=views value=\"$picture->views\"></td></tr>";
		echo "<tr><td class=contenttd align=right>Description:</td><td class=contenttd><textarea name=description rows=8 cols=80>$picture->description</textarea></td></tr>";
		echo "<tr><td class=contenttd></td><td class=contenttd>";
		echo "<input type=submit name=go value=go>";
		echo "</form>";
		echo "</td></tr></table>";
	}
}
/////////////////////////////////////////////////////////////////////////////////
// MEME use old confirm
if($action=="memeuseoldgo") {
		$m=mfo1("select * from meme where id='$name'");
		$id=$m->basepic;
		$name="";
		$action="meme";
}
/////////////////////////////////////////////////////////////////////////////////
// MEME use old
if($action=="memeuseold") {
	$donotshowcats=true;
	sc_optionizer(	sc_phpself(), "action=memeuseoldgo", "meme", "name", 1, "Select base picture", 1);
}
/////////////////////////////////////////////////////////////////////////////////
// MEME save
if($action=="memesave") {
    sc_query("update meme set status='SAVED' where id='$mid'");
    sc_info("SAVED!","WHITE","RED");
    $action="showmemes";
}
/////////////////////////////////////////////////////////////////////////////////
// MEME generate
if($action=="memegenerate") {
    
	$donotshowcats=true;
	$name = addslashes($name);
	$texttop = addslashes($REQUEST['texttop']);
	$textbottom = addslashes($REQUEST['textbottom']);	
    
	$poster=999;
    if($data->id) $poster=$data->id;	
    if(empty($private)) $private="no";
	if($mid==0) {
        $infoout="Adding new caption";
        if(empty($texttop)) $texttop="_NEW";
		
		echo " POSTER [$poster]<br>";
		echo "PICTURE [$id] <br>";
        
$q="insert into meme
      ( `name`,`poster`, `basepic`,`texttop`,`status`)
VALUES('$name','$poster', '$id',  '$texttop', 'EDIT');";
        sc_query($q);
        $mid=mysql_insert_id();
       } else {
		$infoout="Updating caption $mid";
		sc_query("update meme set `name`  			= '$name'   	     where id='$mid'");
		sc_query("update meme set `poster`   	 	= '$poster'     	 where id='$mid'");
		sc_query("update meme set `texttop`     	= '$texttop'    	 where id='$mid'");
		sc_query("update meme set `textbottom`  	= '$textbottom' 	 where id='$mid'");
		sc_query("update meme set `font`	       = '$chgfont'      	 where id='$mid'");
		sc_query("update meme set `text_color`		= '$text_color'     where id='$mid'");
		sc_query("update meme set `text_bg_color`	= '$text_bg_color'  where id='$mid'");
		sc_query("update meme set `text_size`		= '$text_size'      where id='$mid'");
		sc_query("update meme set `private`		= '$private'        where id='$mid'");
		sc_query("update meme set `datborder`		= '$datborder'   	  where id='$mid'");
	}	
    $meme=mfo1("select * from meme where id='$mid'");
    $data=sc_getuserdata($poster);
    $action="memeedit";
    $id=$meme->basepic;
	sc_info($infoout." >> $meme->id ($mid) $meme->name >> $meme->texttop >> $meme->textbottom",	"WHITE","GREEN");
}
/////////////////////////////////////////////////////////////////////////////////
// MEME delete confirm
if( ($action=="memedeletego") ) {
	if($data->access==255){
		sc_query("delete from meme where id='$id' limit 1");
	}	
	$action="showmemes";
}
/////////////////////////////////////////////////////////////////////////////////
// MEME use old
if($action=="memedelete") {
	$donotshowcats=true;
	if($data->access==255){
	$dd="<form action=$RFS_SITE_URL/modules/pictures/pics.php method=post>Confirm delete meme:
	<input type=submit name=memedelete value=Delete>
	<input type=hidden name=action value=memedeletego>
	<input type=hidden name=id value=$id>
	</form>";	
	sc_info($dd,"black","red");	
	$t=$m->name."-".time();// /$t.png
	echo "<a href='$RFS_SITE_URL/include/generate.image.php/$t.png?id=$m->id&owidth=$fullsize' target=_blank>
	<img src='$RFS_SITE_URL/include/generate.image.php/$t.png?id=$id&owidth=256' border=0></a>";
	}
}
/////////////////////////////////////////////////////////////////////////////////
// MEME change font
if($action=="memeusefont"){
	
	$m=mfo1("select * from meme where id='$meme'");
	sc_info("$m->name($m->id) font changed to $memefont","WHITE","GREEN");
	$p=$data->id;
	if(empty($p)) $p=999;
	echo $p;
	if( ($p==$m->poster) ||
	    ($data->access==255) ) {
		sc_query("update meme set font='$memefont' where id='$meme'");
		$mid=$meme; $id=$mid;
		$action="memeedit";
	}
}
/////////////////////////////////////////////////////////////////////////////////
// MEME change color
if($action=="memechangecolor"){
	$m=mfo1("select * from meme where id='$meme'");
	if( ($data->id==$m->poster) ||
		($data->access==255) ) {
		sc_query("update meme set text_color='$text_color' where id='$meme'");
		sc_query("update meme set text_bg_color='$text_bg_color' where id='$meme'");
		$mid=$meme; $id=$mid;
		$action="memeedit";
	}
}
/////////////////////////////////////////////////////////////////////////////////
// MEME editor
if( ($action=="memeedit")  || 
	 ($action=="newmeme")   ||
	  ($action=="meme") ) {
	$donotshowcats=true;
	if($action=="meme"){ 			$pic=mfo1("select * from pictures where id='$id'");	}
	if($action=="memeedit") {
		
		if(empty($mid)) $mid=$id;
		sc_info("Editing $name caption #$mid","BLACK","#ff9900");
		$m=mfo1("select * from meme where id='$mid'");
		$pic=mfo1("select * from pictures where id='$m->basepic'");	
	}
    $p=$data->id;
    if(empty($p)) $p=999;
	if( ($m->poster==$p) || ($data->access==255) ) {
		if($m->poster!=$p)
            sc_info("NOT YOURS ADMIN! / EDIT ANYWAY (LOL)","WHITE","RED");
		if(empty($name)) $name=$m->name;
		if(empty($name)) $nout="SHOW_TEXT_10#20#name=$name".$RFS_SITE_DELIMITER;
		else  { 		
			$nout="name=$name".$RFS_SITE_DELIMITER;
		}
		echo "<table border=0 cellspacing=0 cellpadding=0><tr><td valign=top>";
		$ofont="fonts/impact.ttf";
		$ocolor="white";
		$text_bg_color="black";
		if(!empty($m->font)) $ofont=$m->font;
		if(!empty($m->text_color)) $ocolor=$m->text_color;
		if(!empty($m->text_bg_color)) $text_bg_color=$m->text_bg_color;
		if(empty($private)) $private="no";
		//echo " .. [$m->id $pic->id]<br>";
		sc_bqf( "action=memegenerate".$RFS_SITE_DELIMITER.
				 "id=$pic->id".$RFS_SITE_DELIMITER.
				 "mid=$m->id".$RFS_SITE_DELIMITER.
				 "chgfont=$m->font".$RFS_SITE_DELIMITER.
				 $nout.
				 "SHOW_SELECTOR_colors#name#text_color#$ocolor".$RFS_SITE_DELIMITER.
				 "SHOW_SELECTOR_colors#name#text_bg_color#$text_bg_color".$RFS_SITE_DELIMITER.
				 "SHOW_TEXT_10#20#datborder=$m->datborder".$RFS_SITE_DELIMITER.
				 "SHOW_TEXT_10#20#private=$private".$RFS_SITE_DELIMITER.
				 "SHOW_TEXT_10#20#text_size=$m->text_size".$RFS_SITE_DELIMITER.
				 "SHOW_TEXT_10#20#texttop=$m->texttop".$RFS_SITE_DELIMITER.
				 "SHOW_TEXT_10#20#textbottom=$m->textbottom",
				 "Go" );
		echo "<p>";
		if($action=="memeedit") {			
			$t=$m->name."-".time();			
echo "
<a href='$RFS_SITE_URL/include/generate.image.php/$t.png?id=$m->id&owidth=$fullsize' target=_blank>
<img src='$RFS_SITE_URL/include/generate.image.php/$t.png?
id=$m->id&
owidth=$editwidth'
border=0>
</a>";
		}
		if($action=="meme") 	   echo "<img src=$pic->url>";
		echo "</p>";

		echo "</td><td width=80% valign=top>";
        
		/*sc_info("<BR>Planning following features<br>
					TODO: Add upload ttf font.<br>
					TODO: Add text color pickers <br>
					TODO: Add border color picker<br>
					TODO: Add TRUE FALSE OPTIONIZER<br>
					&nbsp;","WHITE","RED");
                    */


echo "<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=memesave&mid=$m->id&showfonts=true'>";
sc_image_text("SAVE THIS MEME","HoW%20tO%20dO%20SoMeThInG.ttf",28,812,74,0,0,150,150,0,0,0,0,1,1);
echo "</a><BR>";

echo "<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=memeedit&mid=$m->id&showfonts=true'>";
$wf=str_replace("fonts/","",$m->font);

sc_image_text("Change Font ($wf)",
"HoW%20tO%20dO%20SoMeThInG.ttf",
28,812,74,0,0,10,145,148,1,1,0,1,1);
       echo "</a> <BR>";     



        $rr=100;
		if($showfonts) {
			$dir_count=0; $dirfiles = array();
			$handle=opendir("$RFS_SITE_PATH/files/fonts") or die("Unable to open filepath");
			while (false!==($file = readdir($handle))) array_push($dirfiles,$file);
			closedir($handle); reset($dirfiles); asort($dirfiles);
			while(list ($key, $file) = each ($dirfiles)){
				if($file!=".") if($file!="..") if(!is_dir($dir."/".$file)){
					$t=$m->name."-".time();
					$text_size=18;

$rr+=15; $rg+=32; $rb+=8; 
if($rr>255) $rr=100;
if($rg>255) $rg=0;
if($rb>255) $rb=0;

echo "<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=memeusefont&memefont=$file&meme=$m->id'>
<img src='$RFS_SITE_URL/include/generate.image.php/$t.png?action=showfont&font=$file&text_size=56&forcerender=1&oheight=120&forceheight=1&icr=$rr&icg=$rg&icb=$rb' border=0></a>";
				}
			}
		}

		echo "</tr></table>";
	}
	else{
		echo "<p>This is not your caption.</p>";		
	}
}
/////////////////////////////////////////////////////////////////////////////////
// MEME vote up
if($action=="muv"){
    $muv="MUV$id";    
    $action="showmemes";
    if(!$_SESSION[$muv]){        
        $m=mfo1("select * from meme where id='$id'");
        $m->rating+=1;
        sc_query("update meme set rating='$m->rating' where id='$id'");
        $_SESSION[$muv]=true;
    } else {
        sc_info("Multiple upvoting is not allowed.","white","red");
        
    }
}
/////////////////////////////////////////////////////////////////////////////////
// MEME vote down
if($action=="mdv"){
    $mdv="MDV$id";
    $action="showmemes";
	if(!$_SESSION[$mdv]){
        $m=mfo1("select * from meme where id='$id'");
        $m->rating-=1;
        sc_query("update meme set rating='$m->rating' where id='$id'");
        $_SESSION[$mdv]=true;
    }
    else {
        sc_info("Multiple downvoting is not allowed.","white","red");
        
    }
}
/////////////////////////////////////////////////////////////////////////////////
// MEME show memes
if($action=="showmemes"){

    sc_query("delete FROM meme WHERE TIMESTAMPDIFF(MINUTE,`time`,NOW()) > 5 and status = 'EDIT'");    

	$donotshowcats=true;

	echo "<table border=0 width=100% cellpadding=0 cellspacing=0 > <tr><td valign=top align=center>";

	sc_info("Public Captions","WHITE","PURPLE");

	$rz=sc_query("select * from meme where 
        `private`!='yes'
        and `status` = 'SAVED'"); $mtotal=mysql_num_rows($rz);
	
	if(empty($mtop)) $mtop=0;
	if(empty($mbot)) $mbot=$toget;

	$q="select * from meme  ";
	$q.=" where ";
	if(!empty($onlyshow))
		$q.=" `name`='$onlyshow' and";
	$q.=" `private`<>'yes' and `status` = 'SAVED'";
	$q.=" order by rating desc limit $mtop,$mbot ";

	$r=sc_query($q);
	$n=mysql_num_rows($r); 
	
	echo "<table border=0 width=100% cellpadding=0 cellspacing=0 ><tr>";
	echo "<td align=center style='background-color: #990099'>";
	
	if( $mtop > 0 ) {
		$tmtop=$mtop-$mbot;
		echo "<BR>[<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=showmemes&mtop=$tmtop&mbot=$mbot&onlyshow=$onlyshow'>PREVIOUS PAGE</a>]<BR>";
	}
	else
		echo "<BR>[NO PREVIOUS]<BR>";
	echo "</td>";

	if(!empty($onlyshow)) {
		echo "<td align=center>[<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=showmemes&mtop=$mtop&mbot=$mbot&onlyshow='>SHOW ALL CAPTIONS</a>]</td>";
	}

	echo "<td align=center style='background-color: #990099'>";
	if( ($mbot+$mtop) < $mtotal) {
		
		$mtop+=$mbot;
		echo "<BR>[<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=showmemes&mtop=$mtop&mbot=$mbot&onlyshow=$onlyshow'>NEXT PAGE</a>]<BR>";
	}
	else
		echo "<BR>[NO NEXT PAGE]<BR>";

	echo "</td>";
	
	echo "</tr></table>";
	

    echo "<table border=0 width=100%>";
    echo "<tr><td style='float:left;'>";


	for($i=0;$i<$n;$i++){
		$m=mysql_fetch_object($r);	
		$clr=sc_rgb2html(rand(66,120),0,rand(66,120));		
		echo "
        
        <div id=$m->id style='
       
//posfition:relative;
positifon:absolute; top:50%;
 float: left; float: top;'>
        
        ";
//        echo "<td valign=top align=center style='background-color: $clr;' >";
		
		$t=$m->name.$m->id;
		//echo "<a href='$RFS_SITE_URL/include/generate.image.php/$t.png?id=$m->id&owidth=$fullsize' target=_blank>
        echo "<a href='$RFS_SITE_URL/modules/pictures/pics.php?a=ms&id=$m->id' target=_blank>

		<img src='$RFS_SITE_URL/include/generate.image.php/$t.png?id=$m->id&owidth=$thumbwidth' border=0></a><br>";
		
		$muser=sc_getuserdata($m->poster); if(empty($muser->name)) $muser->name="anonymous";

        echo "
		Based: [<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=showmemes&onlyshow=$m->name'>$m->name</a>]<br>		";
    
        sc_image_text(sc_num2txt($m->rating), "OCRA.ttf",         24, 78,24,   0,0,      1,155,1, 70,70,0, 1,1   );

                    echo "
						<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=muv&id=$m->id'><img src='$RFS_SITE_URL/images/icons/thumbup.png'   border=0 width=24></a>
						<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=mdv&id=$m->id'><img src='$RFS_SITE_URL/images/icons/thumbdown.png' border=0 width=24></a>
						<br>";

		echo "[<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=memegenerate&id=$m->basepic&name=$m->name'>New Caption</a>]<br>";
		if( ($data->id==$m->poster) ||
			($data->access==255) ) {
			echo "[<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=memeedit&id=$m->id'>Edit</a>] ";
			echo "[<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=memedelete&id=$m->id'>Delete</a>] ";
		}
		echo "<br>";
		//echo "<hr>";
		// echo "</td>";
		/*
		$tr++;
		if($tr== $mcols ) {
			echo "</tr><tr>";
			$tr=0;
		}*/
		echo "
        
        </div>
        
        ";
	}
	// echo "</tr>";
	//echo "</table>";
  //  echo "</span>";

echo "<br style='clear: both;'>";

	echo "</td>";

///////////// Last 5
	echo "<td valign=top align=center width=240> ";
	sc_info("Your Private Captions","BLACK","GREEN");
	
	if(!empty($data->name)){
		$r=sc_query("select * from meme 
		where
		
		poster='$data->id' and
		private='yes'
		
		order by time desc");
		
		$mn=mysql_num_rows($r);
		
		if($mn>0) {			
			for($i=0;$i<$mn;$i++) {
				$m=mysql_fetch_object($r);				
				$t=$m->name."-".time();
				echo "<a href='$RFS_SITE_URL/include/generate.image.php/$t.png?id=$m->id&owidth=$fullsize' target=_blank>
				<img src='$RFS_SITE_URL/include/generate.image.php/$t.png?id=$m->id&owidth=$thumbwidth' border=0></a><br>";
            
            $muser=sc_getuserdata($m->poster); if(empty($muser->name)) $muser->name="anonymous";
            //echo "Contributor: $muser->name<br>
            
                echo "
						Based: [<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=showmemes&onlyshow=$m->name'>$m->name</a>]<br>";

    sc_image_text(sc_num2txt($m->rating), "OCRA.ttf", 24, 78, 24, 0, 0, 1, 155, 1, 70, 70, 0, 1, 1);
                    
        echo "<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=muv&id=$m->id'><img src='$RFS_SITE_URL/images/icons/thumbup.png'   border=0 width=24></a>
              <a href='$RFS_SITE_URL/modules/pictures/pics.php?action=mdv&id=$m->id'><img src='$RFS_SITE_URL/images/icons/thumbdown.png' border=0 width=24></a>
						<br>";

				echo "[<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=memegenerate&id=$m->basepic&name=$m->name'>New Caption</a>]<br>";
				if( ($data->id==$m->poster) ||
					($data->access==255) ) {
					echo "[<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=memeedit&id=$m->id'>Edit</a>] ";
					echo "[<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=memedelete&id=$m->id'>Delete</a>] ";
				}
				echo "<hr>";
			}
		}
		else
		{
			echo "(You haven't created any private captions)";
		}
	}
	else {
		echo"(You must login to create private captions)";
	}
	echo "</td>";
/////////// End Last 5	

///////////// Last 5
	echo "<td valign=top align=center width=210> ";
	sc_info("Last 5 Captions","BLACK","YELLOW");
	$r=sc_query("select * from meme where `private`!='yes' and `status` = 'SAVED' order by time desc");
	for($i=0;$i<5;$i++) {

		$clr=sc_rgb2html(rand(66,120),rand(66,120),0);
		echo "<div style='background-color: $clr;' >";

		$m=mysql_fetch_object($r);		
		$t=$m->name."-".time();// /$t.png
		echo "<a href='$RFS_SITE_URL/include/generate.image.php/$t.png?id=$m->id&owidth=$fullsize' target=_blank>
        <img src='$RFS_SITE_URL/include/generate.image.php/$t.png?id=$m->id&owidth=$thumbwidth' border=0></a><br>";
		$muser=sc_getuserdata($m->poster); if(empty($muser->name)) $muser->name="anonymous";
		//echo "Contributor: $muser->name<br>
        echo "
			Based: [<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=showmemes&onlyshow=$m->name'>$m->name</a>]<br>";

            sc_image_text(sc_num2txt($m->rating), "OCRA.ttf",         24, 78,24,   0,0,      1,155,1, 70,70,0, 1,1   );
            
                echo "<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=muv&id=$m->id'><img src='$RFS_SITE_URL/images/icons/thumbup.png'   border=0 width=24></a>
						<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=mdv&id=$m->id'><img src='$RFS_SITE_URL/images/icons/thumbdown.png' border=0 width=24></a>
						<br>";

		echo "[<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=memegenerate&id=$m->basepic&name=$m->name'>New Caption</a>]<br>";
		if( ($data->id==$m->poster) ||
			($data->access==255) ) {
			echo "[<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=memeedit&id=$m->id'>Edit</a>] ";
			echo "[<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=memedelete&id=$m->id'>Delete</a>] ";
		}
		echo 
		"</div>";
		echo "<hr>";
	}
	echo "</td>";
/////////// End Last 5	
	
	echo "</tr>";
	echo "</table>";
}
/////////////////////////////////////////////////////////////////////////////////
// PICTURE random
if($action=="random"){
    $res=sc_query("select * from `pictures` where `hidden`!='yes'");
    $num=mysql_num_rows($res);
    if($num>0) {
        $pict=rand(1,($num))-1;
        mysql_data_seek($res,$pict);
        $pic=mysql_fetch_object($res);
        $id=$pic->id;        
    }
    $action="view";
}

/////////////////////////////////////////////////////////////////////////////////
// PICTURE view
if($action=="view") {
	/*
	$action="viewcat";	
	$ipr=mfo1("select * from pictures where id=$id");
	$category=mfo1("select * from categories where id=$ipr->category");
	$cat=$category->id;
	*/
	
	
    $res=sc_query("select * from `pictures` where `id`='$id' order by time asc");
    $picture=mysql_fetch_object($res);
    

	$category=$picture->category;
    $res2=sc_query("select * from `pictures` where `category`='$category' and `hidden`!='yes' order by `sname` asc");
    $numres2=mysql_num_rows($res2);
    $linkprev="";
    $linknext="";
    
    for($i=0;$i<$numres2;$i++)    {
        $picture2=mysql_fetch_object($res2);
        if($picture2->id==$picture->id)        {
            $picture2=mysql_fetch_object($res2);
            if(!empty($picture2->id))            {
					$linknext="$RFS_SITE_URL/modules/pictures/pics.php?action=view&id=$picture2->id";
					
                if(!empty($picture3->id))
					$linkprev="$RFS_SITE_URL/modules/pictures/pics.php?action=view&id=$picture3->id";
					
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
			sc_button($linkprev,"Previous");
			echo "</td>";
		}
    
    
    if($id) {
		echo "<td>";
		sc_button("$RFS_SITE_URL/modules/pictures/pics.php?action=memegenerate&id=$picture->id","Caption");
		echo "</td>";
		
		echo "<td>";
		sc_button("$RFS_SITE_URL/modules/pictures/pics.php?action=random","Random Picture");
		echo "</td>";

		if(sc_access_check("pictures","edit")) {
			echo "<td>";
			sc_button("$RFS_SITE_URL/modules/pictures/pics.php?action=modifypicture&id=$picture->id","Edit");
			echo "</td>";
		}
		
		if(sc_access_check("pictures","delete")) {
			echo "<td>";
			sc_button("$RFS_SITE_URL/modules/pictures/pics.php?action=removepicture&id=$picture->id","Delete");
			echo "</td>";
		}
		
		echo "<td>";
		if(!empty($picture2->id))
			sc_button($linknext,"Next");
		echo "</td>";
		
		echo "</tr></table></center>";
		
		
		echo "<center>";
    $categorym=mfo1("select * from categories where id='$category'");	
    if(!empty($categorym->name)) {
        echo "Category: $categorym->name<br>";
    }
		
echo "<script>function changepicname(x,id) {
			var xmlhttp=new XMLHttpRequest();
			var url=\"$RFS_SITE_URL/modules/pictures/pics.php?action=aname&sname=\"+x+\"&id=\"+id;
			xmlhttp.open(\"GET\",url,false);
			xmlhttp.send();
			document.getElementById(\"picname\").innerHTML='<h1>'+xmlhttp.responseText+'</h1>'
			} </script>";
        
echo "<div id=\"picname\">";
		if(empty($picture->sname)) {
			if(sc_access_check("pictures","edit")) {
				$picture->sname="	EDIT THIS NAME!!!!
						<input id='sname' name=sname value=\"\" 
							onblur=\"changepicname(this.value,$picture->id)\"> ";
			}  
		}
echo "<h1>$picture->sname</h1>";
echo "</div>";

echo "<script>function changepicdesc(x,id) {
			var xmlhttp=new XMLHttpRequest();
			var url=\"$RFS_SITE_URL/modules/pictures/pics.php?action=adesc&desc=\"+x+\"&id=\"+id;
			xmlhttp.open(\"GET\",url,false);
			xmlhttp.send();
			document.getElementById(\"picdesc\").innerHTML='<h1>'+xmlhttp.responseText+'</h1>'
			} </script>";
		
echo "<div id=\"picdesc\">";		
		if(empty($picture->description)) {
			if(sc_access_check("pictures","edit"))  {
	$picture->description=" EDIT THIS DESCRIPTION!!!!
<textarea id='description' name=description
onblur=\"changepicdesc(this.value,$picture->id)\"></textarea>";
			}
		}
echo "<h3>$picture->description</h3>";
echo "</div>";
		
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
			echo " border=0>";
			echo "</a>";
			$w=''; $h='';
		}
		else {
			if($viewsfw=="yes") {
				echo "<img src=\"$picture->url\">";				
			}
			else{
				echo "<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=view&id=$picture->id&viewsfw=yes'><img src=\"$RFS_SITE_URL/files/pictures/NSFW.gif\" border=0></a>";
			}			
		}
		echo "<p>&nbsp;</p>";
			$page="$RFS_SITE_URL/modules/pictures/pics.php?action=view&id=$picture->id";	
			sc_facebook_comments($page);		
	}
	else {
        echo "<h1>There are no pictures!</h1>";
	}
	echo "</center>";
	
}


/////////////////////////////////////////////////////////////////////////////////
// PICTURE view category
if($action=="viewcat"){
	
	if($ipr) $ipr=mfo1("select * from pictures where id=$id");
	else $ipr=mfo1("select * from pictures where category='$cat'");
	// $category=mfo1("select * from categories where id=$ipr->category");
	// $cat=$category->id;
	
    $cat=mfo1("select * from categories where id='$cat'");
    if(!empty($cat->name)) echo "<center><font class=th>Category: $cat->name</font></center>";
    $r=sc_query("select * from `pictures` where `category`='$cat->id' and `hidden`!='yes' order by `sname` asc");
	$numpics=mysql_num_rows($r);
	
			echo "<center>";			
			if($galleria=="yes") {
				echo "<script src=\"$RFS_SITE_URL/3rdparty/galleria/galleria-1.2.9.min.js\"></script>";
				echo "<style> #galleria{ width: 700px; height: 400px; background: #000 }</style> ";
				echo "<div id=\"galleria\">";
				
				
				echo "<img src=\"$RFS_SITE_URL/$ipr->url\"> ";
			}
	
    for($i2=0;$i2<$numpics;$i2++) {
    $picture=mysql_fetch_object($r);
    if($picture->sfw=="no") $picture->url="$RFS_SITE_URL/files/pictures/NSFW.gif";
			if($galleria=="yes") {
				
				if($ipr->id!=$picture->id)
				
				echo "<img src=\"$RFS_SITE_URL/$picture->url\"> ";

			}
			else {
				echo "<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=view&id=$picture->id'>";
				$img=$RFS_SITE_URL."/".$picture->url;
				echo sc_picthumb($img,96,0,0);
				echo "</a>\n";
				
			}
    }
	
	if($galleria=="yes") {
			echo "
			</div>
			<script>
			Galleria.loadTheme('$RFS_SITE_URL/3rdparty/galleria/themes/classic/galleria.classic.js');
			Galleria.run('#galleria');
			</script>";

	}
	echo "</center>";

// $donotshowcats=true;
}

/////////////////////////////////////////////////////////////////////////////////
// PICTURE show categories
if(!$donotshowcats) {
	$res=sc_query("select * from `categories` order by name asc");
	$numcats=mysql_num_rows($res);
	echo "<table border=0 width=100%>";
	$numcols=0; echo "<tr>";
	for($i=0;$i<$numcats;$i++) {
		$cat=mysql_fetch_object($res);
		$res2=sc_query("select * from `pictures` where `category`='$cat->id' and `hidden`!='yes' order by `sname` asc");
		$numpics=mysql_num_rows($res2);

        if($numpics>0) {
            echo "<td class=contenttd>";
            echo "<table border=0 cellspacing=0 cellpadding=3 width=100%><tr><td class=contenttd>";
            echo "<table border=0 cellspacing=0 cellpadding=0 width=100% ><tr>";
            echo "<td class=contenttd valign=top width=220>";
        if(empty($cat->image)) $cat->image="buttfea2.gif";
        if(!file_exists("$RFS_SITE_PATH/$cat->image")) $cat->image="images/icons/404.png";
            echo "
            <a href='$RFS_SITE_URL/modules/pictures/pics.php?action=viewcat&cat=$cat->id'>
            <h1><img src='$RFS_SITE_URL/$cat->image' border=0 width=64 height=64>$cat->name ($numpics)</h1>
            </a>
            <br>";

            echo "</td></tr>";

            echo "<tr>";
                echo "<td class=contenttd valign=top height=200 width=220>";
                // make pictures table...
                if($numpics>5) $numpics=5;
                for($i2=0;$i2<$numpics;$i2++) {
                    $picture=mysql_fetch_object($res2);
                    if($picture->sfw=="no") $picture->url="$RFS_SITE_URL/files/pictures/NSFW.gif";
                    echo "<a href='$RFS_SITE_URL/modules/pictures/pics.php?action=view&id=$picture->id'>";
                    $img=$RFS_SITE_URL."/".$picture->url;
                    echo sc_picthumb($img,96,0,0);
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
?>
