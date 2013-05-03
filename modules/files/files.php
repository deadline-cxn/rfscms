<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////

if($argv[1]=="scrub") { $RFS_CMD_LINE=true; include_once("include/lib.all.php"); sc_scrubfiles(); exit(); }
if($argv[1]=="orph")  { $RFS_CMD_LINE=true; include_once("include/lib.all.php"); $data=sc_getuserdata(1); system("clear"); orphan_scan("files"); exit(); }

if($_REQUEST['action']=="get_file_go") {
	$id=$_REQUEST['id'];
	chdir("../../");
	include_once("include/lib.all.php");
	include("modules/files/lib.files.php");
	$filedata=sc_getfiledata($id);
	if(empty($filedata)) {
		echo "Error, file does not exist?\n";
		exit();
	}
	sc_adddownloads($data->name,1);
	sc_query("UPDATE files SET downloads=downloads+1 where id = '$id'");
	sc_gotopage("$RFS_SITE_URL/$filedata->location");
	exit();
}

chdir("../../");
include_once("include/lib.all.php");
include_once("3rdparty/ycTIN.php");
include("header.php");

sc_div("files.php");

if($action=="show_temp") { 	$_SESSION['show_temp']=true;}
if($action=="hide_temp") {	$_SESSION['show_temp']=false;}
if($action=="editmodeon") {	$_SESSION['editmode']=true;}
if($action=="editmodeoff"){ $_SESSION['editmode']=false;}

echo "<table border=0><tr>"; 

if(sc_access_check("files","upload")) {
	echo "<td>";
	sc_button("$RFS_SITE_URL/modules/files/files.php?action=upload","Upload");
	echo "</td>";
}
if(sc_access_check("files","addlink")) {
	echo "<td>";    
    sc_button("$RFS_SITE_URL/modules/files/files.php?action=addfilelinktodb","Add Link as File");
	echo "</td>";
}
if(sc_access_check("files","orphanscan")) {
	echo "<td>";
    sc_button("$RFS_SITE_URL/modules/files/files.php?action=getorphans","Add orphan files");
	echo "</td>";
}
if(sc_access_check("files","purge")) {
	echo "<td>";
	sc_button("$RFS_SITE_URL/modules/files/files.php?action=purge","Purge missing files");
	echo "</td>";
}
if(sc_access_check("files","sort")) {
	echo "<td>";
	sc_button("$RFS_SITE_URL/modules/files/files.php?action=show_ignore","Show Hidden");
	echo "</td>";
	echo "<td>";
	if($_SESSION['show_temp']==true){
		sc_button("$RFS_SITE_URL/modules/files/files.php?action=hide_temp","Sort Mode Off");
	}
	else {
		sc_button("$RFS_SITE_URL/modules/files/files.php?action=show_temp","Sort Mode");
	}
	echo "</td>";
}
if(sc_access_check("files","edit")) {	
	echo "<td>";
	if($_SESSION['editmode']==true){
		sc_button("$RFS_SITE_URL/modules/files/files.php?action=editmodeoff","Edit Mode Off");		
	}else {
		sc_button("$RFS_SITE_URL/modules/files/files.php?action=editmodeon","Edit Mode On");		
	}
	echo "</td>";
}
if(sc_access_check("files","xplorer")) {	
	echo "<td>";
    sc_button("$RFS_SITE_URL/modules/xplorer/xplorer.php","Xplorer");
	echo "</td>";
}

echo "</tr></table>"; 	
echo "<table border=0 cellspacing=0 cellpadding=0 >";
echo "<tr>\n";
echo "<form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/modules/files/files.php\" method=post>\n";
echo "<input type=hidden name=action value=search>\n";
echo "<td width=65 class=contenttd>Search:&nbsp;</td>\n";
echo "<td width=90 class=contenttd><input type=textbox name=criteria></td>\n";
echo "<td width=10 class=contenttd>&nbsp;in&nbsp;</td>\n";
echo "<td width=80 class=contenttd>";

echo "<select name=category style=\"min-width:250px;\"><option>all categories\n";
$result=sc_query("select * from categories order by name asc");
$numcats=mysql_num_rows($result);
for($i=0;$i<$numcats;$i++){
    $cat=mysql_fetch_object($result);
	echo "<option data-image=\"".sc_picthumb_raw($cat->image,16,0,0)."\" >$cat->name";
}
echo "</select></td>\n";

echo "<td width=30 class=contenttd>&nbsp;and&nbsp;display&nbsp;</td>\n";
echo "<td width=15 class=contenttd><select name=amount><option>all<option>10<option>25<option>50<option>100</select></td>\n";
echo "<td width=30 class=contenttd>&nbsp;results&nbsp;</td>\n";
echo "<td width=50 class=contenttd><input type=submit value=\"go!\" name=submit> </form></td>\n";
echo "<td width=75% class=contenttd></td>";
echo "</tr></table>\n";

if($action=="addfilelinktodb") {
    echo "<table border=0>\n";
    echo "<form enctype=application/x-www-form-URLencoded action=$RFS_SITE_URL/modules/files/files.php method=post>\n";
    echo "<input type=hidden name=action value=addfilelinktodb_go>\n";
    echo "<input type=hidden name=file_add value=\"$file_add\">\n";
    echo "<tr><td>Name </td><td><input name=name value=\"$filedata->name\"></td></tr>\n";
    echo "<tr><td>File Link  </td><td><input name=file_url value=\"\" size=110></td></tr>\n";
    echo "<tr><td>Version</td><td><input name=version value=\"\"></td></tr>\n";
    echo "<tr><td>Size in bytes</td><td><input name=size></td></tr>\n";
    echo "<tr><td align=right>Safe for work:    </td><td><select name=sfw><option>yes<option>no</select></td></tr>\n";
    echo "<tr><td align=right>category:         </td><td><select name=category>\n";
    $result=sc_query("select * from categories order by name asc"); $numcats=mysql_num_rows($result); for($i=0;$i<$numcats;$i++)
	{ $cat=mysql_fetch_object($result);  echo "<option>$cat->name"; }
    echo "</select></td></tr>\n";
    echo "<tr><td>Description</td><td><textarea name=description rows=7 cols=60>$filedata->description</textarea></td></tr>\n";
    echo "<tr><td>Homepage</td><td><input name=homepage></td></tr>\n";
    echo "<tr><td>Platform</td><td><input name=platform value=i686></td></tr>\n";
    echo "<tr><td>Operating System</td><td><input name=os value=Windows></td></tr>\n";
    echo "<tr><td>Company</td><td><input name=owner></td></tr>\n";
    echo "<tr><td>&nbsp;</td><td><input type=submit name=shubmit value=Add!></td><td>&nbsp;</td></tr>\n";
    echo "</form></table>\n";
    include("footer.php");
    exit();
}
if($action=="addfilelinktodb_go") {
    $file_url=addslashes($file_url);
    $file_add=addslashes($file_add);
    $description=addslashes($description);
    $name=addslashes($name);
    $filetype=sc_getfiletype($file_add);
    echo "<p>New file link added: $name</p>";
        $time1=date("Y-m-d H:i:s");
        sc_query("INSERT INTO `files` (`name`) VALUES ('$name');");
        sc_query("UPDATE files SET location='$file_url' where name='$name'");
        sc_query("UPDATE files SET submitter='$data->name' where name='$name'");
        sc_query("UPDATE files SET category='$category' where name='$name'");
        sc_query("UPDATE files SET description='$description' where name='$name'");
        sc_query("UPDATE files SET category='$category' where name='$name'");
        sc_query("UPDATE files SET filetype='$filetype' where name='$name'");
        sc_query("UPDATE files SET size='$size' where name='$name'");
        //sc_query("UPDATE files SET local_path='$file_add' where name='$name'");
        sc_query("UPDATE files SET time='$time1' where name='$name'");
        sc_query("UPDATE files SET worksafe='$sfw' where name='$name'");
        sc_query("UPDATE files SET homepage='$homepage' where name='$name'");
        sc_query("UPDATE files SET platform='$platform' where name='$name'");
        sc_query("UPDATE files SET os='$os' where name='$name'");
        sc_query("UPDATE files SET owner='$owner' where name='$name'");
        sc_query("UPDATE files SET version='$version' where name='$name'");
}

if($action=="addfiletodb") {
    echo "<p>You are adding:</p><p>$file_url</p><p>$file_add</p>\n";
    echo "<table border=0>\n";
    echo "<form enctype=application/x-www-form-URLencoded action=$RFS_SITE_URL/modules/files/files.php method=post>\n";
    echo "<input type=hidden name=action value=addfiletodb_go>\n";
    echo "<input type=hidden name=file_url value=\"$file_url\">\n";
    echo "<input type=hidden name=file_add value=\"$file_add\">\n";
    echo "<tr><td>Short name </td><td><input name=name value=\"$filedata->name\"></td></tr>\n";
    echo "<tr><td align=right>Safe for work:    </td><td><select name=sfw><option>yes<option>no</select></td></tr>\n";
    echo "<tr><td align=right>category:         </td><td><select name=category>\n";
    $result=sc_query("select * from categories order by name asc");
    $numcats=mysql_num_rows($result);
    for($i=0;$i<$numcats;$i++)
    {
        $cat=mysql_fetch_object($result);
        echo "<option>$cat->name";
    }

    echo "</select></td></tr>\n";
    echo "<tr><td>Description</td><td><textarea name=description rows=7 cols=60>$filedata->description</textarea></td></tr>\n";
    echo "<tr><td>&nbsp;</td><td><input type=submit name=shubmit value=Add!></td><td>&nbsp;</td></tr>\n";
    echo "</form></table>\n";   
    include("footer.php");
    exit();
}
if($action=="addfiletodb_go") {
    $file_url=addslashes($file_url);
    $file_add=addslashes($file_add);
    $description=addslashes($description);
    $name=addslashes($name);
    $filetype=sc_getfiletype($file_add);
    $fsize = filesize($file_add);
    $fsize = intval($fsize);
    if($fsize!="0")     {
        $time1=date("Y-m-d H:i:s");
        sc_query("INSERT INTO `files` (`name`) VALUES('$name');");
        sc_query("UPDATE files SET location='$file_url' where name='$name'");
        sc_query("UPDATE files SET submitter='$data->name' where name='$name'");
        sc_query("UPDATE files SET category='$category' where name='$name'");
        sc_query("UPDATE files SET description='$description' where name='$name'");
        sc_query("UPDATE files SET category='$category' where name='$name'");
        sc_query("UPDATE files SET filetype='$filetype' where name='$name'");
        sc_query("UPDATE files SET size='$fsize' where name='$name'");
        //sc_query("UPDATE files SET local_path='$file_add' where name='$name'");
        sc_query("UPDATE files SET time='$time1' where name='$name'");
        sc_query("UPDATE files SET worksafe='$sfw' where name='$name'");
    }
}

if($action=="get_file"){
	
	if(		(sc_yes($RFS_SITE_ALLOW_FREE_DOWNLOADS)) ||
			($_SESSION["logged_in"]=="true") ) {		
		
		$filedata=sc_getfiledata($_REQUEST['id']);
		if(empty($filedata)) {
            echo "Error 3392! File does not exist?\n";
            include("footer.php");
            exit();
		}

		$size = sc_sizefile($filedata->size);		
        
       echo "<p>";
		echo "<a href=\"$RFS_SITE_URL/modules/files/files.php?action=get_file_go&id=$filedata->id\" target=_new_window>";
		echo "<img src=\"$RFS_SITE_URL/images/icons/Download.png\" border=0>";
		echo "<font size=4>";
		echo "$filedata->name ($size)";
		echo "</font>";
		echo "</a>";
		echo "</p>\n";
		echo "<p>(Right click and 'save target as' to save the file to your computer...)</p>\n";

		echo "<table border=0><tr>";
		if(sc_access_check("files","edit")) {
			echo "<td>";
				sc_button("$RFS_SITE_URL/modules/files/files.php?action=mdf&file_mod=yes&id=$filedata->id","Edit");		
			echo "</td>";
		}
	
		if(sc_access_check("files","delete")) {
			echo "<td>";
			sc_button("$RFS_SITE_URL/modules/files/files.php?action=del&file_mod=yes&id=$filedata->id","Delete");
			echo "</td>";
		}
		
		echo "</tr></table>";
		
		if(!empty($filedata->thumb)) {
			echo "<img src=$filedata->thumb>";
		}

		echo "<table border=0>";

		echo "<tr><td>Posted by:</td><td> <a href=\"$RFS_SITE_URL/modules/profile/showprofile.php?user=$filedata->submitter\">$filedata->submitter</a></td></tr>";
		echo "<tr><td>Downloaded:</td><td> $filedata->downloads times</td></tr>";
		echo "<tr><td>Rating:</td><td> $filedata->rating</td></tr>";
		
		echo "<tr><td>Category:</td><td>$filedata->category</td></tr>";

		echo "<tr><td>Version:</td><td>$filedata->version</td></tr>";
		echo "<tr><td>Homepage:</td><td>$filedata->homepage</td></tr>";
		echo "<tr><td>Bytes:</td><td>$filedata->size ($size)</td></tr>";
		echo "<tr><td>md5 hash:</td><td>".md5($filedata->location)."</td></tr>";

		echo "<tr><td>Platform:</td><td>$filedata->platform</td></tr>";
		echo "<tr><td>Operating System:</td><td>$filedata->os</td></tr>";

		echo "<tr><td>Added:</td><td>$filedata->time</td></tr>";
		echo "<tr><td>Last update:</td><td>$filedata->lastupdate</td></tr>";
		echo "<tr><td>Safe for work:</td><td>$filedata->worksafe</td></tr>";

		echo "</table>";
					
		if(!empty($filedata->description)) {
			echo "<p>";
			echo "<table border=0 cellspacing=0 cellpadding=0>\n";
			echo "<tr>";
			echo "<td class=contenttd>";
			echo "Description";
			echo "</td>\n";
			echo "</tr>\n";
			echo "<tr>";
			echo "<td>";
			
			echo "<table border=0 bordercolor=#000000 cellspacing=0 cellpadding=4 width=100%>\n";
			echo "<tr>";
			echo "<td class=contenttd>\n";
			echo nl2br(stripslashes($filedata->description));
			echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "</p>\n";			
		}
		
		echo "<table border=0 width=100% >";
		echo "<tr>";
		echo "<td>";

		$ft=sc_getfiletype($filedata->location);	
		echo "<pre>";
			sc_file_get_readme("$RFS_SITE_PATH/$filedata->location");
		echo"</pre>";

		switch($ft){

			case "exe":
			case "msi":
			case "msu":
			case "dll":

				echo "<pre>";
				$fver=system("pev -p $filedata->location");
				echo "FILE VERSION: $fver";
				
				if( (!empty($fver)) &&
					(empty($filedata->version)) ) {
					echo " UPDATING DATABASE...";
					sc_query("update files set version='$fver' where id='$filedata->id'");
				}
				
				echo "<br><br>";
				
				echo system("pev $filedata->location");				
				echo "</pre>";
				break;

			case "ttf":
			case "otf":
			case "fon":
			case "eot":
				sc_image_text(	"$filedata->name",
						"$filedata->name", 72, // font, fontsize
						1,1, // w,h
						0,0, // offset x, offset y
						244,245,1, // RGB Inner
						1,1,0, 		// RGB Outer
						0,	 // force render
						0	// force height
						);
						
						echo "<br>";
				$ttf = new ycTIN_TTF();
				//open font file
				if ($ttf->open("$RFS_SITE_PATH/$filedata->location")) {
					//get name table
					$rs = $ttf->getNameTable();
					//display result
					echo "<pre>";
					print_r($rs);
					echo "</pre>";
				}
				
				break;
				

			case "adf":
				echo "Contents:<br><pre>";
				echo system("unadf -r $filedata->location");
				echo "</pre>";
				break;

			case "dms":
				echo "Contents:<br><pre>";				
				echo system("xdms f $filedata->location");
				echo "</pre>";
				break;

			case "tar":
				echo "Contents:<br><pre>";				
				echo system("tar -tvf $filedata->location");
				echo "</pre>";
				break;


			case "tgz":
			case "gz":
				echo "Contents:<br><pre>";				
				echo system("tar -tvzf $filedata->location");
				echo "</pre>";
				break;

			case "7z":
				echo "<p>This is a 7zip file. You will need to get 7zip to unarchive it. <a href=\"http://www.7-zip.org/\" target=_blank>http://www.7-zip.org/</a></p>";

			case "iso":
			case "cab":			
			case "chm":
			case "cpio":
			case "cramfs":
			case "deb":
			case "dmg":
			case "fat":
			case "hfs":
			case "lzma":
			case "xz": 
			case "wim":
			case "mbr":
			case "msi":
			case "nsis":
			case "ntfs":
			case "rpm":
			case "udf":
			case "vhd": 
			case "xar":
			case "z":			
			case "bz2":
			case "lzh":
			case "lha":
			case "arj":
			case "arc":
			case "rar":
			case "zip":

				echo "Contents:<br><pre>";
				echo system("7z l '$filedata->location'");
				echo "</pre>";
				break;

			case "ace":
				echo "Contents:<br><pre>";
				echo system("unace v $filedata->location");
				echo "</pre>";

				break;
							
			case "crx":
			case "css":
			case "html":
			case "c":
			case "cpp":
			case "h":
			case "hpp":
			case "sh":
			case "bat":
			case "perl":
			case "lua":
			case "js":
			case "php":
				echo "<table border=0 width=75% cellpadding=6><tr><td class=sc_file_table_1>";
				
				show_source($filedata->location);
				echo "</td></tr></table>";
				sc_adddownloads($data->name,1);
				$dl=$filedata->downloads+1;
				sc_query("UPDATE files SET downloads='$dl' where id = '$id'");
				break;
				
				
			case "gif":
			case "jpg":
			case "jpeg":
			
			
			
			case "png":
			
				$image_size   = @getimagesize($filedata->location);
				$image_height = $image_size[1];
				$image_width  = $image_size[0];
				echo "<hr>IMAGE: $image_width x $image_height <BR>";
				
				
				$exif = exif_read_data($filedata->location, 0, true);
				echo "<hr>EXIF Information:<br>";
				foreach ($exif as $key => $section) {
					foreach ($section as $name => $val) {
						echo "$key.$name: $val<br />\n";
					}
				}
				echo "<pre>";
				echo system("7z l $filedata->location");
				echo "</pre>";
				
				
				break;

			default:
				break;
		}
	}

	else echo "<p> You can't download files unless you are <a href=\"$RFS_SITE_URL/login.php\">Logged in</a>!</p>\n";

	echo "</td></tr></table>";
	
	include("footer.php");
	exit();
}

if($file_mod=="yes"){
	
	if(!sc_access_check("files","edit")) {
		echo "You don't have access to edit files.<br>";
		include("footer.php");
		exit();
	}	

    if(!empty($data->name))    {
        if($action=="ren")        {
            if(!empty($name)) sc_query("UPDATE files SET name='$name' where id = '$id'");
        }
        if($action=="del")        {
            $filedata=sc_getfiledata($id);
			echo "<p></p>";						
            echo "<table border=0>\n";
            echo "<form enctype=application/x-www-form-URLencoded action=$RFS_SITE_URL/modules/files/files.php method=post>\n";
			echo "<input type=hidden name=retpage value=\"$retpage\">";
            echo "<input type=hidden name=file_mod value=yes>\n";
            echo "<input type=hidden name=action value=del_conf>\n";
            echo "<input type=hidden name=id value=\"$id\">\n";
            echo "<tr><td>Are you sure you want to delete [$filedata->name]???</td><td><input type=submit name=submit value=\"Yes\"></td></tr>\n";
            echo "<tr><td>Annihilate the file from the server?</td><td><input name=\"annihilate\" type=\"checkbox\" value=\"yes\"></td></tr>\n";
            echo "<tr><td>Important! If you do not want to delete this file, 
				<a href=\"$retpage\">click here</a>!</td>\n";
            echo "<td>&nbsp;</td><td>&nbsp;</td></tr>\n";
            echo "</form></table>\n";
            echo "</td></tr></table>";
			
            include("footer.php");
            exit();
        }
        if($action=="del_conf")        {
            $filedata=sc_getfiledata($id);
            sc_query("delete from files where id = '$id'");
            echo "<p>Delete [$filedata->name] is deleted from the database...</p>\n";
            if($annihilate=="yes") {
                unlink($RFS_SITE_PATH."/".$filedata->location);
                echo "<p> $filedata->location annihilated!</p>\n";
				if(!empty($retpage)) {
					sc_gotopage($retpage);
					exit();
				}
            }
        }
        if($action=="mod")        {
			
            if(!empty($name)) sc_query("UPDATE files SET name='".addslashes($name)."' where id = '$id'");
            if(!empty($location)){
                sc_query("UPDATE files SET location='$location' where id = '$id'");
                $filetype=sc_getfiletype($location);
                sc_query("UPDATE files SET filetype='$filetype' where id = '$id'");
            }

            sc_query("UPDATE files SET category='$category' where id='$id'");
            sc_query("UPDATE files SET hidden='$hidden' where id='$id'");
            sc_query("UPDATE files SET downloads='$downloads' where id='$id'");
            sc_query("UPDATE files SET description='".addslashes($description)."' where id = '$id'");
            sc_query("UPDATE files SET size='$size' where id='$id'");
            $time=date("Y-m-d H:i:s");
            sc_query("UPDATE files SET time='$time' where id='$id'");
            sc_query("UPDATE files SET thumb='$thumbr' where id='$id'");
            sc_query("UPDATE files SET version='$vers' where id='$id'");
            sc_query("UPDATE files SET homepage='$homepage' where id='$id'");
            sc_query("UPDATE files SET owner='$owner' where id='$id'");
            sc_query("UPDATE files SET platform='$platform' where id='$id'");
            sc_query("UPDATE files SET os='$fos' where id='$id'");
            sc_query("UPDATE files SET rating='$rating' where id='$id'");
            sc_query("UPDATE files SET worksafe='$sfw' where id = '$id'");

            echo "<p><a href=$RFS_SITE_URL/modules/files/files.php>File</a> modified...</p><br>\n";
        }

        if($action=="mdf")  { // show a form to modify the file attributes...        
            // if($data->access==255) sc_dtv("files");
            $filedata=sc_getfiledata($id);
            echo "<p>Modify [$filedata->name]</p>\n";
			
			sc_ajax("Name,80","files","id","$id","name",70,"","files","edit","");
			sc_ajax("Location,80","files","id","$id","location",70,"","files","edit","");
			sc_ajax("Thumbnail,80","files","id","$id","thumb",70,"","files","edit","");
			sc_ajax("Homepage,80","files","id","$id","homepage",70,"","files","edit","");
			
			sc_ajax("Category,80","files","id","$id","category",70,"select,table,categories,name","files","edit","");
						
			sc_ajax("Description,80","files","id","$id","description","15,70","textarea","files","edit","");
			
			sc_ajax("Filesize,80","files","id","$id","size",30,"","files","edit","");
			sc_ajax("Version,80","files","id","$id","version",30,"","files","edit","");			
			sc_ajax("Owner,80","files","id","$id","owner",30,"","files","edit","");
			sc_ajax("Platform,80","files","id","$id","platform",30,"","files","edit","");
			sc_ajax("OS,80","files","id","$id","os",30,"","files","edit","");
			
			sc_ajax("Downloads,80","files","id","$id","downloads",10,"","files","edit","");
			sc_ajax("Hidden,80","files","id","$id","hidden",10,"","files","edit","");
			sc_ajax("Worksafe,80","files","id","$id","worksafe",10,"","files","edit","");
			sc_ajax("Rating,80","files","id","$id","rating",10,"","files","edit","");
			
			
            include("footer.php");
            exit();
        }
    }
    else echo "<p>You can't modify files if you are not <a href=$RFS_SITE_URL/login.php>logged in</a>!</p>\n";
}

if($action=="remove_duplicates") {
}

function orphan_scan($dir) { eval(scg());
	if(!$RFS_CMD_LINE) {
		if(!sc_access_check("files","orphanscan")) {
			echo "You don't have access to scan orphan files.<br>";
			include("footer.php");
			exit();
		}
	}
	echo "Scanning [$RFS_SITE_PATH/$dir] \n"; if(!$RFS_CMD_LINE) echo "<br>";
	$dir_count=0; $dirfiles = array();
	$handle=opendir($RFS_SITE_PATH."/".$dir);
	if(!$handle) return 0;
	while (false!==($file = readdir($handle))) array_push($dirfiles,$file);
	closedir($handle);
	reset($dirfiles);	
	while(list ($key, $file) = each ($dirfiles))  {
        if($file!=".") {
            if($file!="..") {			
                if(is_dir($dir."/".$file)){
  				if(substr($file,0,1)!=".")
				    orphan_scan($dir."/".$file);
				}
				else {
			        $filefound=0; 
                    $url = "$dir/$file";
						$loc=addslashes("$dir/$file");
                    $res=sc_query("select * from `files` where `location` like '%$loc%'");
						if($res)  {
							if(mysql_num_rows($res)>0)
								$filefound=1;
								$res=sc_query("select * from `files` where `name` = '$file'");
								if($res)
									if(mysql_num_rows($res)>0) $filefound=1;
						}
						if($filefound){
                    }
                    else{
                        $time=date("Y-m-d H:i:s");
                        $filetype=sc_getfiletype($file);
                        $filesizebytes=filesize(getcwd()."/$dir/$file");

						if($filesizebytes>0) {

								$name=addslashes($file);
									$infile=addslashes($file);							
								sc_query("INSERT INTO `files` (`name`) VALUES('$infile');");
									$loc=addslashes("$dir/$file");
								sc_query("UPDATE files SET `location`='$loc' where name='$name'");

									$dname="system";
									if(!empty($data)) $dname=$data->name;							

								sc_query("UPDATE files SET `submitter`='$dname' where name='$name'");

								sc_query("UPDATE files SET `category`='!!!TEMP!!!' where name='$name'");
								sc_query("UPDATE files SET `hidden`='no' where name='$name'");
								sc_query("UPDATE files SET `time`='$time' where name='$name'");
								sc_query("UPDATE files SET filetype='$filetype' where name='$name'");
								sc_query("UPDATE files SET size='$filesizebytes' where name='$name'");
								echo "Added [$url] size[$filesizebytes] to database \n"; if(!$RFS_CMD_LINE) echo "<br>";
								sc_flush_buffers();
								$dir_count++;
								
			}
                   }
    	       }
            }
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////
if($action=="getorphans") {
	orphan_scan("files");
	include("footer.php");
    exit();
}
///////////////////////////////////////////////////////////////////////////////////
if($action=="purge") {
if(!sc_access_check("files","purge")) {
		echo "You don't have access to purge files.<br>";
		include("footer.php");
		exit();
	}	
	
	$r=sc_query("select * from files");
	for($i=0;$i<mysql_num_rows($r);$i++){
		$file=mysql_fetch_object($r);
		if(!file_exists($file->location)) {
			echo "$file->location purged<br>";
			sc_query("delete from files where location = '$file->location'");
		}
	}		
	include("footer.php");
	exit();	
}

///////////////////////////////////////////////////////////////////////////////////

function files_action_f_upload_go() { eval(scg());
	sc_var("fu_dir");
	
	if($fu_dir=="- Select -") { sc_info("You must choose a location","WHITE","RED"); files_action_upload(); exit(); }
	sc_var("MAX_FILE_SIZE");
	sc_var("fu_userfile");
	sc_var("fu_hidden");
	sc_var("fu_sfw");
	sc_var("fu_category");
	sc_var("fu_name");
	sc_var("fu_desc");
	$newpath     = "$RFS_SITE_PATH/$fu_dir";
	$httppath    = "$RFS_SITE_URL/$fu_dir";

	if(empty($data->name)) {
		echo "<p> You must be logged in to upload files...</p>\n";
	}
	else {
		if(!sc_access_check("files","upload")) { echo "<p>You are not authorized to upload files!</p>\n";
	}
	else {
		echo "<p> Uploading files... </p>\n";
		$uploadFile=$newpath."/".$_FILES['fu_userfile']['name'];
		$uploadFile =str_replace("//","/",$uploadFile);
		if(!stristr($uploadFile,$RFS_SITE_PATH)) 
		$uploadFile=$RFS_SITE_PATH.$uploadFile;
		if(move_uploaded_file($_FILES['fu_userfile']['tmp_name'], $uploadFile)) {
			system("chmod 755 $uploadFile");
			$error="File is valid, and was successfully uploaded. ";
			echo "<P>You sent: ".$_FILES['fu_userfile']['name'].", a ".$_FILES['fu_userfile']['size']." byte file with a mime type of ".$_FILES['fu_userfile']['type']."</p>\n";
			echo "<p>It was stored as [$httppath"."/".$_FILES['fu_userfile']['name']."]</p>\n";
			if($fu_hidden=="no") {
				$httppath=$httppath."/".$_FILES['fu_userfile']['name'];					
				$finfo=pathinfo($uploadFile);
				$filetype=strtolower($finfo['extension']);
				if(empty($fu_name)) $fu_name=$_FILES['fu_userfile']['name'];
				$fu_name=addslashes($fu_name);
				$time1=date("Y-m-d H:i:s");
				sc_query("INSERT INTO `files` 	(`name`, 		`submitter`, 		`time`, `worksafe`, 	`hidden`, 		`category`, 	 `filetype`)
											VALUES	('$fu_name',	'$data->name', '$time1', '$fu_sfw',	'$fu_hidden','$fu_category', '$filetype');");
				$id=mysql_insert_id();
				echo "DATABASE ID[$id]<br>";
				
				echo "<a href=\"$RFS_SITE_URL/modules/files/files.php?action=get_file&id=$id\">View file information</a><br>";
				$httppath=str_replace("$RFS_SITE_URL/","",$httppath);
				sc_query("UPDATE files SET location	='$httppath' 		where id='$id'");								
				$fu_desc=addslashes($fu_desc);
				sc_query("UPDATE files SET description	='$fu_desc'	 	where id='$id'");	
				$filesizebytes=$_FILES['fu_userfile']['size'];
				sc_query("UPDATE files SET size			='$filesizebytes'	where id='$id'");					
				$extra_sp=$_FILES['fu_userfile']['size']/10240;
				$data->files_uploaded=$data->files_uploaded+1;
				sc_query("update users set `files_uploaded`='$data->files_uploaded' where `name`='$data->name'");
			}
		}
		else {
			//UPLOAD_ERR_OK        //Value: 0; There is no error, the file uploaded with success.
			//UPLOAD_ERR_INI_SIZE  //Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini.
			//UPLOAD_ERR_FORM_SIZE //Value: 2; The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.
			//UPLOAD_ERR_PARTIAL   //Value: 3; The uploaded file was only partially uploaded.
			//UPLOAD_ERR_NO_FILE
			$error ="File upload error!";
			echo "File upload error! [\n".$_FILES['fu_userfile']['name']."][".$_FILES['fu_userfile']['error']."][".$_FILES['fu_userfile']['tmp_name']."][".$uploadFile."]\n";
		}
		if(!$error) {
			$error .= "No files have been selected for upload";
		}
		echo "<P>Status: [$error]</P>\n";
		echo "<p>[<a href=$RFS_SITE_URL/modules/files/files.php?action=upload>Add another file</a>]\n";
		echo "[<a href=$RFS_SITE_URL/modules/files/files.php>Files</a>]</p>\n";
		}
	}
}
function files_action_upload() { eval(scg()); 
	if(empty($data->name)) {  echo "<p>You must be logged in to upload files...</p>\n"; }
	else {
		if(!sc_access_check("files","upload")) {  echo "<p>You are not authorized to upload files!</p>\n";  }
			else {
				sc_div("UPLOAD FILE FORM START");
				echo "<p>Upload a file</p>\n";				
				echo "<form enctype=\"multipart/form-data\" action=\"$RFS_SITE_URL/modules/files/files.php\" method=\"post\">\n";
				echo "<table border=0>\n\n\n";
				echo " <!--  ******************************************************************************** --> \n";
				echo "<tr>\n";			
				
				echo "<input type=\"hidden\" name=\"action\" value=\"f_upload_go\">\n";
				echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"99990000000\">\n";

				echo "<td align=right>Put file in:</td><td>\n";
				sc_optionize_folder("fu_dir","files","",1,0,$path);
				echo "</td></tr>\n";
				echo "<tr>  <td align=right>Select file:      </td>
				
					<td ><input name=\"fu_userfile\" type=\"file\" size=80
					
					> </td></tr>\n";
				echo "<tr>  <td align=right>Hide from public: </td><td><select name=fu_hidden><option>no<option>yes</select>(no will place file entry into database viewable by the public)</td></tr>\n";
				echo "<tr>  <td align=right>Safe for work:    </td><td><select name=fu_sfw><option>yes<option>no</select></td></tr>\n";
				echo "<tr>  <td align=right>category:         </td><td><select name=fu_category>\n";
				$result=sc_query("select * from categories order by name asc");
				$numcats=mysql_num_rows($result);
				for($i=0;$i<$numcats;$i++) { $cat=mysql_fetch_object($result); echo "<option>$cat->name"; }
				echo "</select></td></tr>\n";
				echo "<tr><td align=right>Short name :</td><td><input type=textbox name=fu_name value=\"$name\"></td></tr>\n";
				echo "<tr><td align=right valign=top>Description:</td><td><textarea name=\"fu_desc\" rows=\"7\" cols=\"40\"></textarea></td></tr>\n";
				echo "<tr><td>&nbsp;</td><td><input type=\"submit\" name=\"submit\" value=\"Upload!\"></td>";
				echo "</tr>\n";
				
				echo "</table>\n";
						echo "</form>\n";
		
				echo " <!--  ******************************************************************************** --> \n";
				
				sc_div("UPLOAD FILE FORM END");
			}
		}
		echo "<hr>";
		include("footer.php");
		exit();
}
///////////////////////////////////////////////////////////////////////////////////
if($give_file=="avatar"){
    if(empty($data->name)) echo "<p> You must be logged in to upload files... <a href=join.php>JOIN</a> now!</p>\n";
    else     {
        echo "<p> Uploading files... </p>\n";
        $f_ext=sc_getfiletype($_FILES['userfile']['name']);
        $uploadFile=$RFS_SITE_PATH."/images/avatars/".$_FILES['userfile']['name'];
        if( ($f_ext=="png") || ($f_ext=="gif")||($f_ext=="jpg")||($f_ext=="swf") ) {
            $oldname=$_FILES['userfile']['name'];
            if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadFile)){
                system("chmod 755 $uploadFile");
                $error="File is valid, and was successfully uploaded. ";
                echo "<P>You sent: ".$_FILES['userfile']['name'].", a ".$_FILES['userfile']['size']." byte file with a mime type of ".$_FILES['userfile']['type']."</p>\n";
                $oldname=$_FILES['userfile']['name'];
                $newname=$data->name.".".$f_ext;
                rename($RFS_SITE_PATH."/images/avatars/".$oldname,$RFS_SITE_PATH."/images/avatars/".$newname);
                $httppath=$httppath."/".$newname;
                echo "<p>It was stored as [<a href=\"$httppath\" target=\"_blank\">$httppath</a>]</p>\n";
                sc_setuservar($data->name,"avatar",$httppath);
            } else {
                $error ="File upload error!";
                echo "File upload error! [";
                echo $_FILES['userfile']['name'];
                echo "][";
                echo $_FILES['userfile']['error'];
                echo "][";
                echo $_FILES['userfile']['tmp_name'] ;
                echo "][";
                echo $uploadFile;
                echo "]\n";
            }
            if(!$error) $error .= "No files have been selected for upload";
            echo "<P>Status: [$error]</P>\n";
        }
        else echo "<p>Invalid filetype ($f_ext) for an avatar!</p>";
    }
    echo "</td></tr></table>";
    include("footer.php");
    exit();
}
if($action=="upload_avatar"){
    if(empty($data->name))    {
        echo "<p>You must be logged in to upload files...</p>\n";
    }    else     {
        echo "<p>Upload your very own avatar picture! Upload an swf, gif, or jpg avatar!</p>\n";
        echo "<table border=0>\n";
        echo "<form enctype=\"multipart/form-data\" action=\"$RFS_SITE_URL/modules/files/files.php\" method=\"post\">\n";
        echo "<input type=hidden name=give_file value=avatar>\n";
        echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"99900000\">";
        echo "<input type=hidden name=local value=\"images/avatars\">\n";
        echo "<input type=hidden name=hidden value=yes>\n";
        echo "<tr><td align=right>$RFS_SITE_URL/images/avatars/</td><td><input name=\"userfile\" type=\"file\"> </td></tr>\n";
        echo "<tr><td>&nbsp;</td><td><input type=\"submit\" name=\"submit\" value=\"Upload!\"></td></tr>\n";
        echo "</form>\n";
        echo "</table>\n";
    }
    echo "</td></tr></table>";
    include("footer.php");
    exit();
}



///////////////////////////////////////////////////////////////////////////////////

function show1file($filedata,$bg) { eval(scg());

	echo "<div id=\"$filedata->id\" class='sc_file_table_$bg' style=' display: block; '> ";

    $filetype=sc_getfiletype($filedata->location);
    $fti="images/icons/filetypes/$filetype.gif";
    if(file_exists("images/icons/filetypes/$filetype.png"))
		$fti="images/icons/filetypes/$filetype.png";

	echo "<div style='float:left;'>";
	
	echo "<div style='float:left;'>";
		
		echo "<a href=\"$RFS_SITE_URL/modules/files/files.php?action=get_file&id=$filedata->id\">";
		echo "<img src=$RFS_SITE_URL/$fti border=0 alt=\"$filedata->name\" width=16>"; 
		echo "</a>";
		
	echo "</div>";
	
	echo "<div style='float:left; min-width:200px;'>";
		if( (sc_access_check("files","edit")) &&
			($_SESSION['editmode']==true) ) {
			sc_ajax("Name"	,"files","name","$filedata->name","name",20,"","files","edit","");
		} else {	
			echo "<a href=\"$RFS_SITE_URL/modules/files/files.php?action=get_file&id=$filedata->id\">$filedata->name</a>";
		}
	echo "</div>";
	
	$size=(sc_sizefile($filedata->size));

	echo "<div style='float:left;'>";
	if( ($filetype=="ttf") || 
		($filetype=="otf") ||
		($filetype=="fon") ) {
			$fn=stripslashes("$filedata->name");
		sc_image_text($fn,$fn,12, 1,1, 0,0, 244,245,1, 1,1,0, 1,0 );		
		} else {	
			if( (sc_access_check("files","edit")) &&
					($_SESSION['editmode']==true) ) {
				sc_ajax(""	,"files","name","$filedata->name","description","9,45","textarea","files","edit","");
			} else {	
				
				echo "<div style='float: left; width: 300px;'>";
				$fd=sc_trunc($filedata->description,80);
				echo str_replace("<","&lt;",$fd);
				echo "</div>";
				
			}
		}
		
		echo "</div>";
	echo "</div>";	
		
		$data=$GLOBALS['data'];
		if( (sc_access_check("files","edit")) &&
			($_SESSION['editmode']==true) ) {
			$hide="nohide";
			if($_SESSION['show_temp']==true) $hide="hide";			
			
			sc_ajax("","files","id","$filedata->id","category",70,"select,table,categories,name,$hide","files","edit","");
			
		
		}		
		if((sc_access_check("files","delete")) &&
			($_SESSION['editmode']==true) ) {
			sc_button("$RFS_SITE_URL/modules/files/files.php?action=del&file_mod=yes&id=$filedata->id&retpage=".urlencode(sc_canonical_url()),"Delete");
		}
		
	echo "<div style='float:left;'>";
	if(!empty($size)) echo "$size ";
	if(!empty($filedata->version)) echo"$filedata->version ";
	if(!empty($filedata->platform)) echo"$filedata->platform ";
	if(!empty($filedata->os)) echo"$filedata->os ";
	if(!empty($filedata->downloads)) echo"$filedata->downloads ";
		
	echo "</div>
	</div>
	<div style='clear:both;'></div>";
}

///////////////////////////////////////////////////////////////////////////////////

if($action=="file_change_category") {
	sc_query("update files set category = '$name' where id = '$id'");
	$name=""; $action="search"; $category="all categories";	
}

if(($action=="show_temp") || ($_SESSION['show_temp']==true) ) {
	$action="listcategory";
	$category="!!!TEMP!!!";
	$amount="50";
	$query=" where `hidden`='yes' or category='!!!TEMP!!!' ";
	sc_warn("Showing files that have corrupted or hidden categories");
}

if( ($action=="listcategory") ||
	($action=="search") ) {
    $category=rtrim($category);
	if($category=="all categories") $category="all";
    if($action=="search"){
		$query=" where (`name` like '%$criteria%' or `description` like '%$criteria%' or `category` like '%$criteria%') ";
        if($category!="all")
			$query.="and `category` = '$category' ";
		else {
			$query.="and `category` != 'ignore' ";
		}
    }
	
    if($action=="listcategory") if(empty($query)) $query="where `category` = '$category' ";	
    if($top=="")    $top=0;
    if($amount=="") $amount=25;
    if($amount!="all") $limit="$top,$amount";
    else               $limit="";
	
	$nexttop=$top+$amount+1;
	$prevtop=$top-$amount;
	if($prevtop<0) $prevtop=0;
	
    $filelist= sc_getfilelist($query,$limit);
    $x=count($filelist);
	
    if($x==0){
		echo "<p>Your search for $criteria yielded no results...</p>";
	}
	else {
		if($x==1){
			echo "<p>Your search for $criteria yielded no result...</p>";
		}
		else {
			echo "<p>Your search for $criteria yielded $x results:</p>";
		}
	}
	
	if($prevtop>0) 
		sc_button("$RFS_SITE_URL/modules/files/files.php?action=listcategory&amount=$amount&top=$prevtop&category=$category&criteria=$criteria","PREV PAGE");
	if($x==$amount) 
		sc_button("$RFS_SITE_URL/modules/files/files.php?action=listcategory&amount=$amount&top=$nexttop&category=$category&criteria=$criteria","NEXT PAGE");

    if(count($filelist)) {
		echo "<h1>".ucwords($buffer)."</h1>";
		$i=0; $bg=0;
		while($i<count($filelist)){
			$filedata=sc_getfiledata($filelist[$i]);
			if(!empty($filedata->name)){
				$bg++; if($bg>1) $bg=0;
				$i++;
				show1file($filedata,$bg);
				$la=$amount;
				if(empty($la)) $la=5;
				if($i==$la){
					break;
				}
			}
		}		
	}
	
	if($prevtop>0) 
		sc_button("$RFS_SITE_URL/modules/files/files.php?action=listcategory&amount=$amount&top=$prevtop&category=$category&criteria=$criteria","PREV PAGE");
	if($x==$amount) 
		sc_button("$RFS_SITE_URL/modules/files/files.php?action=listcategory&amount=$amount&top=$nexttop&category=$category&criteria=$criteria","NEXT PAGE");
	
    include("footer.php");
    exit();
}

$result=sc_query("
select * from categories 
	where 	name != 'ignore' and
			name != '!!!TEMP!!!'
					order by name asc");
$numcats=mysql_num_rows($result);
for($i=0;$i<$numcats;$i++) {
    $cat=mysql_fetch_object($result);
    if(!empty($cat->name))     {
        $i=0; $bg=0;
        // $buffer=rtrim($buffer);
        $buffer=rtrim($cat->name);
        $filelist=sc_getfilelist("where category = '$buffer' and hidden='no' ",50);

		if(count($filelist)){
			
			echo "<div class=file_list >";
						
			echo "<div class=file_category >";
			
			$iconp=$RFS_SITE_PATH."/".$cat->image;
			$icon=$RFS_SITE_URL."/".$cat->image;
			if(file_exists($iconp)) {
				echo "<a href=\"$RFS_SITE_URL/modules/files/files.php?action=listcategory&category=$buffer\">";
				echo "<img src=$icon border=0 width=32 height=32>";
				echo "</a>";
			}
			
			echo "<a href=\"$RFS_SITE_URL/modules/files/files.php?action=listcategory&category=$buffer\" >";			
			echo ucwords("$buffer");			
			echo "</a>";
			echo "</div>";
			
			while($i<count($filelist)) {
				$filedata=sc_getfiledata($filelist[$i]);
				$bg=$bg+1; if($bg>1) $bg=0;
					show1file($filedata,$bg);
				$i=$i+1;				
				$la=$amount;
				if(empty($la)) $la=5;
				if($i==$la){
					break;
					}
			}
			echo "</div>";
			if($i==$la){
				
			}
			
		}
	}
}
include("footer.php");

?>
