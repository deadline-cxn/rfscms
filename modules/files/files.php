<?

if($argv[1]=="scrub") {
    $RFS_CMD_LINE=true;  
    include_once("include/lib.all.php");
    sc_scrubfiles();
    exit();
}

if($argv[1]=="orph") {
    $RFS_CMD_LINE=true;  
    include_once("include/lib.all.php");
    $data=sc_getuserdata(1);    
    system("clear");
    orphan_scan("files");
    exit();
}

chdir("../../");
include("header.php");

$newpath     = $RFS_SITE_PATH."/".$_REQUEST['local'];
$httppath    = $RFS_SITE_URL."/".$_REQUEST['local'];

sc_div("files.php");

if($action=="show_temp") { 
	$_SESSION['show_temp']=true;
}
if($action=="hide_temp") {
	$_SESSION['show_temp']=false;
}
sc_button("$RFS_SITE_URL/modules/files/files.php","Show Files");

if( $data->access==255) {
    echo "<font class=lilwarn>Admin Functions</font>";
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
	
if(sc_access_check("files","edit")) {
	echo "<td>";
	sc_button("$RFS_SITE_URL/modules/files/files.php?action=show_ignore","Show Hidden");
	echo "</td>";

	echo "<td>";
	sc_button("$RFS_SITE_URL/modules/files/files.php?action=show_temp","Sort Mode");
	echo "</td>";
	
	echo "<td>";
	sc_button("$RFS_SITE_URL/modules/files/files.php?action=hide_temp","Sort Mode Off");
	echo "</td>";
}

if(sc_access_check("files","xplorer")) {	
	echo "<td>";
    sc_button("$RFS_SITE_URL/modules/xplorer/xplorer.php","Xplorer");
	echo "</td>";
}
	echo "</tr></table>"; 	
}

echo "<table border=0 cellspacing=0 cellpadding=0 >";
echo "<tr>\n";
echo "<form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/modules/files/files.php\" method=post>\n";
echo "<input type=hidden name=action value=search>\n";
echo "<td width=65 class=contenttd>Search:&nbsp;</td>\n";
echo "<td width=90 class=contenttd><input type=textbox name=criteria></td>\n";
echo "<td width=10 class=contenttd>&nbsp;in&nbsp;</td>\n";
echo "<td width=80 class=contenttd><select name=category><option>all categories\n";

$result=sc_query("select * from categories order by name asc");
$numcats=mysql_num_rows($result);
for($i=0;$i<$numcats;$i++){
    $cat=mysql_fetch_object($result);
    echo "<option>$cat->name";
}

echo "</select></td>\n";
echo "<td width=30 class=contenttd>&nbsp;and&nbsp;display&nbsp;</td>\n";
echo "<td width=15 class=contenttd><select name=amount><option>all<option>10<option>25<option>50<option>100</select></td>\n";
echo "<td width=30 class=contenttd>&nbsp;results&nbsp;</td>\n";
echo "<td width=50 class=contenttd><input type=submit value=\"go!\" name=submit> </form></td>\n";
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

if($action=="get_file_go") {
        $filedata=sc_getfiledata($_REQUEST['id']);
        if(empty($filedata))         {
            echo "Error 3392! File does not exist?\n";
            include("footer.php");
            exit();
        }
        sc_adddownloads($data->name,1);
        $dl=$filedata->downloads+1;
        sc_query("UPDATE files SET downloads='$dl' where id = '$id'");
        echo "<META HTTP-EQUIV=\"refresh\" content=\"0;URL=$filedata->location\">\n";
        $action="get_file";
}



if($action=="get_file"){
    if($_SESSION["logged_in"]=="true"){
        $filedata=sc_getfiledata($_REQUEST['id']);
        if(empty($filedata)){
            echo "Error 3392! File does not exist?\n";
            //echo "</td></tr></table>";
            include("footer.php");
            exit();
        }

        $size = sc_sizefile($filedata->size);
        
        echo "<p><center><a href=\"$RFS_SITE_URL/modules/files/files.php?action=get_file_go&id=$filedata->id\"><font size=4>$filedata->name ($size)</a> </font></center></p>\n";
        echo "<p><center> (Right click and 'save target as' to save the file to your computer...)</center></p>\n";
        echo "<p><center>Category: $filedata->category</center></p>\n";
        echo "<p><table border=0 cellspacing=0 cellpadding=0><tr><td class=contenttd>Description</td></tr><tr><td><table border=0 bordercolor=#000000 cellspacing=0 cellpadding=4 width=100%>\n";
        echo "<tr><td class=contenttd>";
        echo nl2br($filedata->description);
        echo "</td></tr></table></td></tr></table></p>\n";
        echo "<p align=right>Posted by <a href=\"$RFS_SITE_URL/modules/profile/showprofile.php?user=$filedata->submitter\">$filedata->submitter</a>, \n";

        echo "Downloaded $filedata->downloads times</p>\n";

        $ft=sc_getfiletype($filedata->location);

        switch($ft){
            case "7z":
                echo "<p class=warning align=center>This is a 7zip file. You will need to get 7zip to unarchive it.</p><p align=center><a href=\"http://www.7-zip.org/\" target=_blank>http://www.7-zip.org/</a></p>";
            break;

            case "php":
                echo "<table border=0 width=75% cellpadding=6><tr><td class=sc_file_table_1>";
                
                show_source($filedata->location);
                echo "</td></tr></table>";
                adddownloads($data->name,1);
                $dl=$filedata->downloads+1;
                sc_query("UPDATE files SET downloads='$dl' where id = '$id'");
            break;

            default:
            break;
        }
    }

    else echo "<p> You can't download files unless you are <a href=login.php>Logged in</a>!</p>\n";

    echo "</td></tr></table>";
    include("footer.php");
    exit();

}


if($file_mod=="yes"){
    if(!empty($data->name))    {
        if($action=="ren")        {
            if(!empty($name)) sc_query("UPDATE files SET name='$name' where id = '$id'");
        }
        if($action=="del")        {
            $filedata=sc_getfiledata($id);
            echo "<table border=0>\n";
            echo "<form enctype=application/x-www-form-URLencoded action=$RFS_SITE_URL/modules/files/files.php method=post>\n";
            echo "<input type=hidden name=file_mod value=yes>\n";
            echo "<input type=hidden name=action value=del_conf>\n";
            echo "<input type=hidden name=id value=\"$id\">\n";
            echo "<tr><td>Are you sure you want to delete [$filedata->name]???</td><td><input type=submit name=submit value=\"Yes\"></td></tr>\n";
            echo "<tr><td>Annihilate the file from the server?</td><td><input name=\"annihilate\" type=\"checkbox\" value=\"yes\"></td></tr>\n";
            echo "<tr><td>Important! If you do not want to delete this file, <a href=$RFS_SITE_URL/modules/files/files.php>click here</a>!</td>\n";
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
            sc_query("UPDATE files SET os='$os' where id='$id'");
            sc_query("UPDATE files SET rating='$rating' where id='$id'");
            sc_query("UPDATE files SET worksafe='$sfw' where id = '$id'");

            echo "<p><a href=$RFS_SITE_URL/modules/files/files.php>File</a> modified...</p><br>\n";
        }

        if($action=="mdf")  { // show a form to modify the file attributes...        
            // if($data->access==255) sc_dtv("files");
            $filedata=sc_getfiledata($id);
            echo "<p>Modify [$filedata->name]</p>\n";
            echo "<table border=0>\n";
            echo "<form enctype=application/x-www-form-URLencoded action=$RFS_SITE_URL/modules/files/files.php method=post>\n";
            echo "<input type=hidden name=file_mod value=yes>\n";
            echo "<input type=hidden name=action value=mod>\n";
            echo "<input type=hidden name=id value=\"$id\">\n";
            echo "<tr><td align=right>Short name:</td><td><input name=name size=100 value=\"$filedata->name\"></td></tr>\n";
            echo "<tr><td align=right>Location:  </td><td><input name=location   size=100 value=\"$filedata->location\"></td></tr>\n";
            // submitter
            echo "<tr><td align=right>category:         </td><td><select name=category>\n";
            echo "<option>$filedata->category";
            $result=sc_query("select * from categories order by name asc");
            $numcats=mysql_num_rows($result);
            for($i=0;$i<$numcats;$i++) {
                $cat=mysql_fetch_object($result);
                echo "<option>$cat->name";
            }
            echo "</select></td></tr>\n";
            //echo "<tr><td align=right>Hidden:</td><td><input name=hidden value=\"$filedata->hidden\"></td></tr>\n";
            if($filedata->hidden=="") $filedata->hidden="no";
            echo "<tr><td align=right>Hidden: </td><td><select name=\"hidden\"><option>$filedata->hidden<option>yes<option>no</select</td></tr>\n";
            echo "<tr><td align=right>Downloads:</td><td><input name=downloads value=\"$filedata->downloads\"></td></tr>\n";
            echo "<tr><td align=right>Description:</td><td><textarea name=description rows=7 cols=60>".stripslashes($filedata->description)."</textarea></td></tr>\n";
            // echo "<tr><td align=right>Filetype:</td><td><input name=filetype value=\"$filedata->filetype\"></td></tr>\n"; // is this needed?
            echo "<tr><td align=right>Filesize bytes:</td><td><input name=size value=\"$filedata->size\"></td></tr>\n";
            //echo "<tr><td align=right>Thumbnail:</td><td><input name=thumbr value=\"$filedata->thumb\"></td></tr>";
            //echo "<tr><td align=right>Version:</td><td><input name=vers value=\"$filedata->version\"></td></tr>\n";
            echo "<tr><td align=right>Homepage:</td><td><input name=homepage value=\"$filedata->homepage\"></td></tr>\n";
            //echo "<tr><td align=right>Owner:</td><td><input name=owner value=\"$filedata->owner\"></td></tr>\n";
            //echo "<tr><td align=right>Platform:</td><td><input name=platform value=\"$filedata->platform\"></td></tr>\n";
            //echo "<tr><td align=right>Operating System:</td><td><input name=os value=\"$filedata->os\"></td></tr>\n";
            //echo "<tr><td align=right>Rating:</td><td><input name=os value=\"$filedata->rating\"></td></tr>\n";			
            //if($filedata->worksafe=="") $filedata->worksafe="no";
            //echo "<tr><td align=right>Worksafe: </td><td><select name=\"sfw\"><option>$filedata->worksafe<option>yes<option>no</select</td></tr>\n";
            echo "<tr><td>&nbsp;</td><td><div class=menutop><input type=submit name=shubmit value=Modify!> </div></td><td>&nbsp;</td></tr>\n";			
            echo "</form></table>\n";
            echo "</td></tr></table>";
            include("footer.php");
            exit();
        }
    }
    else echo "<p>You can't modify files if you are not <a href=$RFS_SITE_URL/modules/files/login.php>logged in</a>!</p>\n";
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

if($action=="remove_duplicates") {
	


}

function orphan_scan($dir) { eval(scg()); 

	echo "Scanning [$RFS_SITE_PATH/$dir] \n"; if(!$RFS_CMD_LINE) echo "<br>";
	$dir_count=0; $dirfiles = array();
	
	$handle=opendir($RFS_SITE_PATH."/".$dir);
	// echo $handle."\n";
	if(!$handle) return 0;
	
	while (false!==($file = readdir($handle)))
		array_push($dirfiles,$file);
	closedir($handle);
	
	reset($dirfiles);
	
	while(list ($key, $file) = each ($dirfiles))  {
        if($file!=".") {
            if($file!="..") {			

				// echo "$dir/$file... \n"; if(!$RFS_CMD_LINE) echo "<br>";
                if(is_dir($dir."/".$file)){
					if( 	($file!=".rendered") &&
							($file!=".cache")  && 
							($file!=".Trash-1000") )
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
								$dir_count++;
									
							}
                   }
               
			   }
            }
        }
    }
}

if($action=="getorphans") {
	orphan_scan("files");
	// orphan_scan("images");
	include("footer.php");
    exit();
}

if($action=="purge") {
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

if($action=="upload") {
    if(empty($data->name)) {  echo "<p>You must be logged in to upload files...</p>\n"; }
    else {
        if($data->access!=255) {  echo "<p>You are not authorized to upload files!</p>\n";  }
        else {
            sc_div("UPLOAD FILE FORM START");
            echo "<p>Add a file?!?!</p>\n";
            echo "<table border=0>\n\n\n";
            echo "<form enctype=\"multipart/form-data\" action=\"$RFS_SITE_URL/modules/files/files.php\" method=\"post\">\n";
            echo "<input type=\"hidden\" name=\"give_file\" value=\"yes\">\n";
            echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"99990000000\">\n";

            echo "<tr><td align=right>Put file in:</td><td>\n";
                echo "<select name=local>\n";
                if(!empty($path)) echo "<option>$path\n";
                echo "<option>files\n";
                echo "<option>images\n";
                echo "</select></td></tr>\n";

            echo "<tr>  <td align=right>Select file:      </td>
                        <td ><input name=\"userfile\" type=\"file\" size=80> </td></tr>\n";

            echo "<tr>  <td align=right>Hide from public: </td>
                        <td><select name=hidden><option>yes<option>no</select>
                        (no will place file entry into database viewable by the public)</td></tr>\n";

            echo "<tr>  <td align=right>Safe for work:    </td>
                        <td><select name=sfw><option>yes<option>no</select></td></tr>\n";

            echo "<tr>  <td align=right>category:         </td>
                        <td><select name=category>\n";
            $result=sc_query("select * from categories order by name asc");
            $numcats=mysql_num_rows($result);
            for($i=0;$i<$numcats;$i++) { $cat=mysql_fetch_object($result); echo "<option>$cat->name"; }
            echo "</select></td></tr>\n";
            echo "<tr><td align=right>Short name :</td><td><input type=textbox name=name value=\"$name\"></td></tr>\n";
            echo "<tr><td align=right valign=top>Description:</td><td><textarea name=\"desc\" rows=\"7\" cols=\"40\"></textarea></td></tr>\n";
            echo "<tr><td>&nbsp;</td><td><input type=\"submit\" name=\"submit\" value=\"Upload!\"></td></tr>\n";
            echo "</form>\n";
            echo "</table>\n";
            sc_div("UPLOAD FILE FORM END");
        }
    }

    echo "<hr>";
    include("footer.php");
    exit();
}

if($give_file=="yes") {
    if(empty($data->name)) {  echo "<p> You must be logged in to upload files... <a href=join.php>JOIN</a> now!</p>\n"; }
    else    {
        if($data->access!=255) { echo "<p>You are not authorized to upload files!</p>\n"; }
        else        {
            echo "<p> Uploading files... </p>\n";
            $uploadFile=$newpath."/".$_FILES['userfile']['name'];
            $uploadFile =str_replace("//","/",$uploadFile);
				if(!stristr($uploadFile,$RFS_SITE_PATH)) 
					$uploadFile=$RFS_SITE_PATH.$uploadFile;
            if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadFile))             {
                system("chmod 755 $uploadFile");
                $error="File is valid, and was successfully uploaded. ";
                echo "<P>You sent: ".$_FILES['userfile']['name'].", a ".$_FILES['userfile']['size']." byte file with a mime type of ".$_FILES['userfile']['type']."</p>\n";
                echo "<p>It was stored as [$httppath"."/".$_FILES['userfile']['name']."]</p>\n";
                if($hidden=="no")                 {
                    $xp_ext = explode(".",$_FILES['userfile']['name'],40);
                    $j = count ($xp_ext)-1;
                    $ext = "$xp_ext[$j]";
                    $filetype=strtolower($ext);
                    $filesizebytes=$_FILES['userfile']['size'];
                    $time1=date("Y-m-d H:i:s");
                    $httppath=$httppath."/".$_FILES['userfile']['name'];
                    $description=addslashes($description);
                    $finfo=pathinfo($uploadFile);
                    $nfname=$finfo['file'].".".$finfo['extension'];
                    if(empty($name)) $name=$nfname;
                    $name=addslashes($name);
                    sc_query("INSERT INTO `files` (`name`) VALUES('$name');");
                    sc_query("UPDATE files SET location='$httppath' where name='$name'");
                    sc_query("UPDATE files SET submitter='$data->name' where name='$name'");
                    sc_query("UPDATE files SET category='$category' where name='$name'");
                    sc_query("UPDATE files SET description='$desc' where name='$name'");
                    sc_query("UPDATE files SET category='$category' where name='$name'");
                    sc_query("UPDATE files SET filetype='$filetype' where name='$name'");
                    sc_query("UPDATE files SET size='$filesizebytes' where name='$name'");
                    //sc_query("UPDATE files SET local_path='$uploadFile' where name='$name'");
                    sc_query("UPDATE files SET time='$time1' where name='$name'");
                    sc_query("UPDATE files SET worksafe='$sfw' where name='$name'");
                    $extra_sp=$_FILES['userfile']['size']/10240;
                    $data->files_uploaded=$data->files_uploaded+1;
                    sc_query("update users set `files_uploaded`='$data->files_uploaded' where `name`='$data->name'");
                }
            }   else  {
                //UPLOAD_ERR_OK        //Value: 0; There is no error, the file uploaded with success.
                //UPLOAD_ERR_INI_SIZE  //Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini.
                //UPLOAD_ERR_FORM_SIZE //Value: 2; The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.
                //UPLOAD_ERR_PARTIAL   //Value: 3; The uploaded file was only partially uploaded.
                //UPLOAD_ERR_NO_FILE
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
            if(!$error)            {
                $error .= "No files have been selected for upload";
            }
            echo "<P>Status: [$error]</P>\n";
            echo "<p>[<a href=$RFS_SITE_URL/modules/files/files.php?action=upload>Add another file</a>]\n";
            echo "[<a href=$RFS_SITE_URL/modules/files/files.php>Files</a>]</p>\n";
        }
    }
}

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

function sc_fileheader() { 
	$file_header=$GLOBALS['file_header'];    
    echo "<tr height=16>\n";
    echo "<td class=tdfilehead >Type</td>\n";
    echo "<td class=tdfilehead width=380 >File</td>\n";    
    echo "<td class=tdfilehead></td>\n";
    echo "<td class=tdfilehead width=80 >Size</td>\n";
    // echo "<td class=tdfilehead width=180 >Category</td>\n";
    //echo "<td class=tdfilehead width=380 >Description</td>\n";
    //echo "<td class=tdfilehead >&nbsp;</td>\n";
	//echo "<td class=tdfilehead >&nbsp;</td>\n";
	//echo "<td class=tdfilehead >&nbsp;</td>\n";
    echo "</tr>\n";
}

function show1file($filedata,$bg) { eval(scg());

    echo "<tr class=sc_file_table_$bg >\n";

    $filetype=sc_getfiletype($filedata->location);

    $fti="images/icons/filetypes/$filetype.gif";
    if(file_exists("images/icons/filetypes/$filetype.png")) $fti="images/icons/filetypes/$filetype.png";

    echo "<td class=sc_file_table_$bg >";

    echo "<img src=$RFS_SITE_URL/$fti border=0 alt=\"$filedata->name\" width=16>"; 

    echo "<a href=\"$RFS_SITE_URL/modules/files/files.php?action=get_file&id=$filedata->id\">$filedata->name</a>";

	if($_SESSION['show_temp']==true) {
		echo "<br>$filedata->location";
	}

	echo "</td>\n";

        $size=(sc_sizefile($filedata->size));

	//echo "<td class=sc_file_table_$bg width=100>";
	// $wpui="wp.gif";
	// if(empty($filedata->homepage)) $wpui="wp_no.gif";
	// if($wpui=="wp.gif") echo "<a href=\"$filedata->homepage\" target=_blank>";
	//echo "<img src=\"$RFS_SITE_URL/images/$wpui\" border=0 title=\"$filedata->homepage\" alt=\"$filedata->homepage\">";
	// if($wpui=="wp.gif") echo "</a>";
	//echo "&nbsp;</td>\n";

        echo "<td class=sc_file_table_$bg >$size &nbsp;</td>\n";

// 	echo "<td class=sc_file_table_$bg >"; $floc=$RFS_SITE_PATH."/".$filedata->location; if(file_exists($floc)) { echo md5($floc); } echo "</td>\n";

	echo "<td class=sc_file_table_$bg >";
         echo sc_trunc($filedata->description,45);
        echo "</td>\n";

    echo "<td class=sc_file_table_$bg >";
    $data=$GLOBALS['data'];
    if( ($filedata->submitter==$data->name) || ($data->access==255)) {
        echo "[<a href=\"$RFS_SITE_URL/modules/files/files.php?action=mdf&file_mod=yes&id=$filedata->id\">edit</a>]";
        echo "[<a href=\"$RFS_SITE_URL/modules/files/files.php?action=del&file_mod=yes&id=$filedata->id\">delete</a>]";
	}
    echo "</td></tr>\n";
}

if($action=="file_change_category") {
	sc_query("update files set category = '$name' where id = '$id'");
	$name=""; $action="search"; $category="all categories";	
}

if( ($action=="show_temp") || ($_SESSION['show_temp']==true) ) {
	$action="listcategory"; $category="!!!TEMP!!!"; $amount="all";
	$query="where category not in (select name from categories)";
	sc_warn("Showing files that have corrupted or hidden categories");
}

if( ($action=="listcategory") ||  ($action=="search") ) {
    $category=rtrim($category);
    if($action=="search"){
	
	$query="where (`name` like '%$criteria%' or `description` like '%$criteria%' or `category` like '%$criteria%') ";
        if($category!="all categories")
			$query.="and `category` = '$category' ";
		else
			$query.="and `category` != 'ignore' ";
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
	}else 
	if($x==1){
		echo "<p>Your search for $criteria yielded no result...</p>";
	}else{
		echo "<p>Your search for $criteria yielded $x results:</p>";
	}
	
	if($prevtop>0) 
		echo "<a href=\"$RFS_SITE_URL/modules/files/files.php?action=listcategory&amount=$amount&top=$prevtop&category=$category\">PREV PAGE</a>";
	echo "<a href=\"$RFS_SITE_URL/modules/files/files.php?action=listcategory&amount=$amount&top=$nexttop&category=$category\">NEXT PAGE</a>";
	
    if(count($filelist)) {
		echo "<h1>".ucwords($buffer)."</h1>";
       echo "<table border=0 bordercolor=#000000 cellspacing=0 cellpadding=0 width=$site_singletablewidth>\n";
       sc_fileheader();
		$i=0; $bg=0;
		while($i<count($filelist)){
			$filedata=sc_getfiledata($filelist[$i]);
			if(!empty($filedata->name)){
				$bg=$bg+1; if($bg>1) $bg=0;
				$i=$i+1;
				show1file($filedata,$bg);
				$la=$amount;
				if(empty($la)) $la=5;
				if($i==$la){
					break;
				}
			}
		}
		echo "</table>\n";
	}
    include("footer.php");
    exit();
}

echo "<table border=0 bordercolor=#000000 cellspacing=0 cellpadding=5 width=100% >";

$result=sc_query("select * from categories where name != 'ignore' order by name asc");
$numcats=mysql_num_rows($result);
for($i=0;$i<$numcats;$i++) {
    $cat=mysql_fetch_object($result);
    if(!empty($cat->name))     {
        $i=0; $bg=0;
        $buffer=rtrim($buffer);
        $buffer=rtrim($cat->name);
        $filelist=sc_getfilelist("where category = '$buffer'",50);

		if(count($filelist)){
			echo "<tr>";
			echo "<td class=sc_top_file_table> ";
			$iconp=$RFS_SITE_PATH."/".$cat->image;
			$icon=$RFS_SITE_URL."/".$cat->image;
			if(file_exists($iconp)) {
				echo "<img src=$icon border=0 width=32 height=32><br>";
			}
			echo ucwords("$buffer<br>");

			sc_button("$RFS_SITE_URL/modules/files/files.php?action=listcategory&category=$buffer","Show All $buffer");
			echo "</td>";

			echo "<td class=sc_top_file_table></td>";
			echo "<td class=sc_top_file_table></td>";
			echo "<td class=sc_top_file_table></td>";			
			echo "<td class=sc_top_file_table></td>";
			echo "<td class=sc_top_file_table></td>";
			echo "<td class=sc_top_file_table></td>";

			echo "</tr>";
			
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
			if($i==$la){
				
			}
		}
	}
}
echo "</td></tr></table>";

include("footer.php");

?>
