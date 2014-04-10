<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
// FILES CORE MODULE
/////////////////////////////////////////////////////////////////////////////////////////
include_once("include/lib.all.php");

$RFS_ADDON_NAME="files";
$RFS_ADDON_VERSION="2.4.1";
$RFS_ADDON_SUB_VERSION="0";
$RFS_ADDON_RELEASE="";
$RFS_ADDON_DESCRIPTION="RFSCMS File Manager";
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

lib_menus_register("Files","$RFS_SITE_URL/modules/core_files/files.php");
////////////////////////////////////////////////////////////////
// PANELS
function m_panel_files($x) {
    eval(lib_rfs_get_globals());
	$RFS_ADDON_URL=lib_modules_get_url("files");
    echo "<h2>Last $x Files</h2>";
    $result=lib_mysql_query("select * from files where category !='unsorted' and hidden ='no' order by `time` desc limit 0,$x");
    $numfiles=mysql_num_rows($result);
    echo "<table border=0 cellspacing=0 cellpadding=0 >";
    $gt=2;
    for($i=0;$i<$numfiles;$i++){
        $file=mysql_fetch_object($result);
        $link="$RFS_ADDON_URL?action=get_file&id=$file->id";
        $fdescription=str_replace('"',"&quote;",stripslashes($file->description));
        $gt++; if($gt>2)$gt=1;
        echo "<tr><td class=rfs_file_table_$gt>";
        echo "<a href=\"$link\">$file->name</a> ";
        echo"</td><td class=rfs_file_table_$gt>";
        echo lib_file_sizefile($file->size);
        echo "</td></tr>";
    }
    echo "</table>";
}
////////////////////////////////////////////////////////////////
// AJAX
function lib_ajax_callback_files_add_tag() {
	eval(lib_rfs_get_globals());
	$q="update `$rfatable` set `$rfafield`='$rfaajv' where `$rfaikey` = '$rfakv'";
	lib_mysql_query($q);
	$tx=explode(",",$rfaajv);
	foreach($tx as $k => $v) { lib_tags_add_tag($v); } // echo " [$v] <br>";
	echo "TAGGED";	
	exit();
}
function lib_ajax_callback_files_new_category() {
	eval(lib_rfs_get_globals());	
	if(lib_access_check($rfaapage,$rfaact)) {
		$q="insert into categories (`name`, `image`, `worksafe` ) values ('$rfaajv', '', 'yes')";
		lib_mysql_query($q);
		$q="update `$rfatable` set `$rfafield`='$rfaajv' where `$rfaikey` = '$rfakv'";
		lib_mysql_query($q);
		echo "<font style='color:white; background-color:green;'>NEW CATEGORY: $rfaajv</font>";
	}
}
function lib_ajax_callback_file_ignore() {eval(lib_rfs_get_globals());
	if(lib_access_check($rfaapage,$rfaact)) {
		$q="update files set `ignore`='yes' where id='$rfakv'";
		echo $q;
		lib_mysql_query($q);
		echo "<font style='color:white; background-color:green;'>IGNORED</font>";
	}
}
function lib_ajax_callback_files_move_to_pictures() { eval(lib_rfs_get_globals());
	if(lib_access_check($rfaapage,$rfaact)) {
		$f=lib_mysql_fetch_one_object("select * from files where id='$rfakv'");
		$oname="$RFS_SITE_PATH/$f->location";
		$snamex=explode("/",$f->location); $sname=$snamex[count($snamex)-1];
		$nname="$RFS_SITE_PATH/files/pictures/$sname";
		$nsloc="files/pictures/$sname";
		if(rename($oname,$nname)) {
			$q="delete from `files` where `id`='$rfakv'";	
			lib_mysql_query($q);
			$q="insert into `pictures` (`time`,`url`,`category`,`hidden`) VALUES('$time','$nsloc','unsorted','yes')";
			lib_mysql_query($q);
		
			echo "<font style='color:white; background-color:green;'>MOVED</font>";
		}
		else {
			echo "<font style='color:white; background-color:red;'>FAILURE</font>";
		}
	}
}
function lib_ajax_callback_file_move()  { eval(lib_rfs_get_globals());
	if(lib_access_check($rfaapage,$rfaact)) {
		$f=lib_mysql_fetch_one_object("select * from files where id='$rfakv'");
		$oname="$RFS_SITE_PATH/$f->location";
		$nname="$RFS_SITE_PATH/$rfaajv";
		if(rename($oname,$nname)) {
			$snamex=explode("/",$rfaajv); $sname=$snamex[count($snamex)-1];
			$q="update `$rfatable` set `$rfafield`='$rfaajv' where `$rfaikey` = '$rfakv'";
			lib_mysql_query($q);
			$q="update `$rfatable` set `name` = '$sname' where `$rfaikey` = '$rfakv'" ;
			lib_mysql_query($q);
			echo "<font style='color:white; background-color:green;'>MOVED</font>";
		}
		else {
			echo "<font style='color:white; background-color:red;'>FAILURE</font>";
		}
	}
}
function lib_ajax_callback_rename_file() { eval(lib_rfs_get_globals());
 	if(lib_access_check($rfaapage,$rfaact)) {
		$f=lib_mysql_fetch_one_object("select * from files where id='$rfakv'");
		$loc=$RFS_SITE_PATH."/".$f->location;
		$oname=$loc;
		$nname=str_replace($f->name,$rfaajv,$loc);
		if(rename($oname,$nname)) {
			$q="update `$rfatable` set `$rfafield`='$rfaajv' where `$rfaikey` = '$rfakv'";
			lib_mysql_query($q);
			$nloc=str_replace($f->name,$rfaajv,$f->location);
			$q="update `$rfatable` set `location`='$nloc' where `location` = '$f->location'";
			lib_mysql_query($q);
			echo "<font style='color:white; background-color:green;'>RENAMED</font>";
		}
		else {	
			echo "<font style='color:white; background-color:red;'>FAILURE</font>";
		}
	}
	exit;
}
function lib_ajax_callback_delete_file() { eval(lib_rfs_get_globals());
	if(lib_access_check($rfaapage,$rfaact)) {
		m_files_delete($rfakv,"yes");
	}
	else   echo "<font style='color:white; background-color:red;'>NOT AUTHORIZED</font>";
	exit;
}
function lib_ajax_javascript_file() { eval(lib_rfs_get_globals());
echo '
<script>
function lib_ajax_javascript_dupefile_delete(name,ajv,table,ikey,kv,field,page,act,callback) {
			var http=new XMLHttpRequest();
			var url = "'.$RFS_SITE_URL.'/header.php";
			var params = "action="+callback+
			"&rfaajv="   +encodeURIComponent(ajv)+
			"&rfanname=" +encodeURIComponent(name)+
			"&rfatable=" +encodeURIComponent(table)+
			"&rfaikey="  +encodeURIComponent(ikey)+
			"&rfakv="    +encodeURIComponent(kv)+
			"&rfafield=" +encodeURIComponent(field)+
			"&rfaapage=" +encodeURIComponent(page)+
			"&rfaact="   +encodeURIComponent(act);
			document.getElementById("dfd_"+kv).innerHTML="'.lib_ajax_spinner().'";
			http.open("POST", url, true);
			http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			http.setRequestHeader("Content-length", params.length);
			http.setRequestHeader("Connection", "close");
			http.onreadystatechange = function() {
					if(http.readyState == 4 && http.status == 200) {
					document.getElementById("dfd_"+kv).innerHTML=http.responseText;	
					document.getElementById("dfd_"+kv).style.display = "none";
				}
			}
			http.send(params);
		}</script>';
}
////////////////////////////////////////////////////////////////
// FUNCTIONS
function m_files_show1filee($filedata,$bg) {
    eval(lib_rfs_get_globals());
    echo "<tr>";
    foreach($filedata as $k => $v) {
        echo "<td>";
        if(empty($v)) $v="=====================================";
		echo lib_string_truncate($v,10);        
        echo "</td>";
    }
    echo "</tr>";
}
function m_files_show1file($filedata,$bg) { eval(lib_rfs_get_globals());

	if((($_SESSION['editmode']==true) || ($_SESSION['show_temp']==true)) ) $fedit=true;
	if(($filedata->worksafe!="no") || ($_SESSION['worksafemode']=="off") ) $fworksafe=true;

	// rfs_update_file($filedata->id);
	$filedata=lib_mysql_fetch_one_object("select * from files where id='$filedata->id'");
	
	echo "<div style='clear: both;' id=\"$filedata->id\" >";
	
	///////////////////////////////////
	
	echo "<div style='display: block; float:left;' class='rfs_file_table_outer_$bg'>"; 
	
	///////////////////////////////////

    $filetype=lib_file_getfiletype($filedata->location);
    $fti="images/icons/filetypes/$filetype.gif";
    if(file_exists("images/icons/filetypes/$filetype.png"))
		$fti="images/icons/filetypes/$filetype.png";
		
	$filedata->description=stripslashes($filedata->description);
	$fd=lib_string_truncate($filedata->description,180);
	$dout=str_replace("<","&lt;",$filedata->description);
	$fd=str_replace("<","&lt;",$fd);
	
	$dout=str_replace("\"","'",$dout);
	
	///////////////////////////////////
	
	echo "<div style='display: block; float:left; width: 18px; max-width: 18px; min-width: 18px;' class='rfs_file_table_$bg'>"; 
		echo "<a href=\"$addon_folder?action=get_file&id=$filedata->id\">";
		echo "<img src=$RFS_SITE_URL/$fti border=0 alt=\"$filedata->name\" width=16>"; 
		echo "</a>";
	echo "</div>";
	
	///////////////////////////////////
	
	if($fedit || $_SESSION['deletemode']) $nwidth=550; else $nwidth=250;

	echo "<div style='display: block; 
						float:left; 
						width:$nwidth"."px; 
						max-width:$nwidth"."px; 
						min-width:$nwidth"."px;' 
				class='rfs_file_table_$bg'>";

	if($fedit || $_SESSION['deletemode']) {
		if(lib_access_check("files","delete")) {
			echo "$filedata->location <br>";			
			lib_ajax("Delete", "files",   "id", "$filedata->id",     "id",       20,"button,nolabel", "files","delete","lib_ajax_callback_delete_file");
		}			

		echo "<div style='display: block; float:left; width: 250px; max-width: 250px; min-width:250px;' class='rfs_file_table_$bg'>";
			echo"$filedata->md5 ";
			if(!empty($filedata->md5)) {
				$fdr=lib_mysql_query("select * from files where md5='$filedata->md5'");
				if(mysql_num_rows($fdr)>1) {
					echo "<br>Matching MD5:<br>";
					for($jjq=0;$jjq<mysql_num_rows($fdr);$jjq++) {
						$dfile=mysql_fetch_object($fdr);
						if($dfile->id!=$filedata->id) {
							echo "<div style='display: block; float:left; padding:5px; margin:5px; background-color: #500; color: #f00;
							border: 1px dashed #f00; border-radius: 10px;
							' id='dfd_$dfile->id'> ";
								echo "<a href=\"$addon_folder?action=get_file&id=$dfile->id\"
									title=\"matching file $dfile->location\">";
								$ftype=lib_file_getfiletype($dfile->name);
								if( ($ftype=="jpg") || ($ftype=="png") || ($ftype=="gif") || ($ftype=="bmp") || ($ftype=="svg") || ($ftype=="jpeg") )
									if( ($filedata->worksafe!="no") || ($_SESSION['worksafemode']=="off") )
										echo rfs_picthumb("$RFS_SITE_URL/$dfile->location",60,0,1);
								echo "<br>$dfile->name";
								echo "</a><BR>
								$dfile->location<br>";
								echo "<div style='clear:both;'>";
									lib_tags_show_tags("files",$dfile->id);
								echo "</div>";
								echo "<div style='clear:both;'>";
									if(lib_access_check("files","delete")) {
										lib_ajax("Delete", "files",   "id", "$dfile->id", "id", 20,"button,nolabel", "files","delete","lib_ajax_callback_delete_file,lib_ajax_javascript_dupefile_delete");
									}
								echo "</div>";
							echo "</div>";
						}			
					}
				}
			}
		echo "</div>";		
	}
	
		if((lib_access_check("files","edit")) && $fedit) {
			lib_ajax("Name"	,"files","id","$filedata->id","name",36,"nohide","files","edit","lib_ajax_callback_rename_file");
			echo "<br>URL <a href=\"$RFS_SITE_URL/$filedata->location\" target=\"_blank\">$filedata->name</a> ";
		}
		else {
			$shortname=lib_string_truncate($filedata->name,24);
			if(substr($shortname, strlen($shortname)-3)=="...") $shortname.=$filetype;
			echo "<a class=\"file_link\" href=\"$addon_folder?action=get_file&id=$filedata->id\"	 >$shortname</a>";
		}
		
		echo "<br>";

		if(	($filetype=="jpg") ||
			($filetype=="png") ||
			($filetype=="gif") ||
			($filetype=="bmp") ||
			($filetype=="svg") ||
			($filetype=="jpeg"))
			if($fworksafe) {
				if($_SESSION['thumbs'])
					echo lib_images_thumb("$RFS_SITE_URL/$filedata->location",$nwidth,0,1)."<br>";	
				}

		if(	($filetype=="webm") ||
			($filetype=="mp3") ||
			($filetype=="wav") ||
			($filetype=="wma") ||
			($filetype=="mpg") ||		
			($filetype=="mpeg") ||
			($filetype=="wmv") ||
			($filetype=="avi") ||
			($filetype=="flv")  ) {		
				if($fworksafe) {
					echo "<br><div style='display: block; float: left;' name=\"play$filedata->id\" id=\"play$filedata->id\"></div><a href=\"#\" onclick='playvid(\"play$filedata->id\",\"$RFS_SITE_URL/$filedata->location\");' >Play</a><a href=\"#\" onclick='stopvid(\"play$filedata->id\");' > Stop </a><br>";
		}
	}
		
		$data=$GLOBALS['data'];
		if($fedit) {
			
			if(lib_access_check("files","edit")) {
				echo "<div style='float: left;'>";
				
				lib_ajax("Category","files","id","$filedata->id","category",70,"select,table,categories,name,hide","files","edit","");
				lib_ajax("New Category","files","id","$filedata->id","category",36,"","files","edit","lib_ajax_callback_files_new_category");
				
				lib_ajax("Tags",    "files","id","$filedata->id","tags",    36,"nohide","files","edit","lib_ajax_callback_files_add_tag");				
				lib_ajax("Move to Pictures", "files",   "id", "$filedata->id",     "id", 20,"button", "files","edit","lib_ajax_callback_files_move_to_pictures");
				lib_ajax("Ignore", "files",   "id", "$filedata->id", "id", 20, "button,nolabel", "files","delete","lib_ajax_callback_file_ignore");
				echo "</div>";
			}
		}
		
		// echo "<div style='display: block; float:left;' class='rfs_file_table_$bg' id=\"tags_$filedata->id\"> &nbsp; </div>"; 
		
		if($_SESSION['tagmode'])
				lib_tags_add_link("files",$filedata->id);
		lib_tags_show_tags("files",$filedata->id);

	echo "</div>";
	
	///////////////////////////////////
	
	echo "<div style='display: block;
						float:left;
						width:340px;
						max-width:340px;
						min-width:340px;'
				class='rfs_file_table_$bg'>";
	
	if( ($filetype=="ttf") || 
		($filetype=="otf") ||
		($filetype=="fon") ) {
			$fn=stripslashes("$filedata->name");
			lib_images_text($fn,$fn, 12, 1,1, 0,0, 244,245,1, 1,1,0, 1,0 );
			
			}
			else {
				echo "$fd &nbsp;";
			}
			if( (lib_access_check("files","edit")) && $fedit) {
				lib_ajax("Description"	,"files","name","$filedata->name","description","9,45","textarea","files","edit","");

				lib_ajax("Location",    	"files","id",	   "$filedata->id","location", 76,"nohide","files","edit","lib_ajax_callback_file_move");

				lib_ajax("Hidden",    	"files","id",	   "$filedata->id","hidden", 36,"nohide","files","edit","");
				if(lib_rfs_bool_true($filedata->hidden))
					lib_forms_info("HIDDEN","WHITE","RED");

				$filedata->location=str_replace("/","/<wbr />",$filedata->location);
				echo "<br>[$filedata->location]";
				
			}
			
		
	echo "</div>";
	
	///////////////////////////////////
		
	echo "<div style='display: block; float:left; width: 90px; max-width: 90px; min-width: 90px;' class='rfs_file_table_$bg'>";
		$size=(lib_file_sizefile($filedata->size));
		echo "$size ";
		if($fedit)
			echo "<br> Submitted by:<br>$filedata->submitter ";
	echo "</div>";
	
	///////////////////////////////////
	
	echo "<div style='display: block; float:left; width: 90px; max-width: 90px; min-width: 90px;' class='rfs_file_table_$bg'>";
		echo"$filedata->version &nbsp;";
	echo "</div>";
	
	///////////////////////////////////
		
	echo "<div style='display: block; float:left; width: 90px; max-width: 90px; min-width: 90px;' class='rfs_file_table_$bg'>";
		echo"$filedata->platform &nbsp;";
	echo "</div>";
	
	///////////////////////////////////
	
	echo "<div style='display: block; float:left; width: 90px; max-width: 90px; min-width: 90px;' class='rfs_file_table_$bg'>"; 
		echo"$filedata->os &nbsp;";
	echo "</div>";
	
	
		
	///////////////////////////////////
	
	echo "</div>";
	
	echo "</div>";
	
}
function m_files_scrubfiledatabase() {
	lib_mysql_query(" CREATE TABLE files2 like files; ");
	lib_mysql_query(" INSERT files2 SELECT * FROM files GROUP BY location;" );
	lib_mysql_query(" RENAME TABLE `files`  TO `files_scrub`; ");
	lib_mysql_query(" RENAME TABLE `files2` TO `files`; " );
	lib_mysql_query(" DROP TABLE files_scrub; ");
}
function m_files_getfiledata($file){
    $query = "select * from files where `name` = '$file' ";
    if(intval($file)!=0)
    $query = "select * from files where `id` = '$file'";
    $result = lib_mysql_query($query);
    if(mysql_num_rows($result) >0 ) $filedata = mysql_fetch_object($result);
    return $filedata;
}
function m_files_getfilelist($filesearch,$limit){
    $query = "select * from files";
    if(!empty($filesearch)) $query.=" ".$filesearch;
		
	if(!stristr($query,"order by"))
		$query.=" order by `name` asc ";
    if(!empty($limit)) $query.=" limit $limit";	
	$query=str_replace("where","where (`ignore` != 'yes') and ",$query);	
	

    $result = lib_mysql_query($query);
    $i=0; $k=mysql_num_rows($result);
    while($i<$k) {
        $der = mysql_fetch_array($result);
        $filelist[$i] = $der['id'];
        $i=$i+1;
    }
    return $filelist;
}
function m_files_md5_scan($RFS_CMD_LINE) {
	$filelist=m_files_getfilelist(" ",0);
	for($i=0;$i<count($filelist);$i++) {
		$filedata=m_files_getfiledata($filelist[$i]);
		$fl=stripslashes($filedata->location);
		$tmd5=@md5_file ($fl);
		if($tmd5) {
			if($tmd5!=$filedata->md5) {
				if(!empty($filedata->md5))
					echo "(MD5 WARNING) $filedata->location $tmd5 (database: $filedata->md5)  \n"; if(!$RFS_CMD_LINE) echo "<br>";
				else {
					echo "(MD5 UPDATED) $filedata->location $tmd5  \n"; if(!$RFS_CMD_LINE) echo "<br>";
					lib_mysql_query("UPDATE files SET md5='$tmd5' where id='$filedata->id'");
				}
			} 
			else {
				/// echo ".";
				// echo "(MD5 MATCHES) $filedata->location $tmd5 $filedata->md5 \n";  if(!$RFS_CMD_LINE) echo "<br>";
			}
		}
	}
}
function m_files_quick_md5_scan($RFS_CMD_LINE) {
	$filelist=m_files_getfilelist(" ",0);
	for($i=0;$i<count($filelist);$i++) {
		$filedata=m_files_getfiledata($filelist[$i]);
		$fl=stripslashes($filedata->location);
		if(empty($filedata->md5)) {
			$tmd5=@md5_file ($fl);
			if($tmd5) {
				if($tmd5!=$filedata->md5) {
					if(!empty($filedata->md5))
						echo "(MD5 WARNING) $filedata->location $tmd5 (database: $filedata->md5)  \n"; if(!$RFS_CMD_LINE) echo "<br>";
					else {
						echo "(MD5 UPDATED) $filedata->location $tmd5  \n"; if(!$RFS_CMD_LINE) echo "<br>";
						lib_mysql_query("UPDATE files SET md5='$tmd5' where id='$filedata->id'");
					}
				} 
				else {
					/// echo ".";
					// echo "(MD5 MATCHES) $filedata->location $tmd5 $filedata->md5 \n";  if(!$RFS_CMD_LINE) echo "<br>";
				}
			}
		}
	}
}
function m_files_orphan_scan($dir,$RFS_CMD_LINE) { eval(lib_rfs_get_globals());
	if(!$RFS_CMD_LINE) {
		if(!lib_access_check("files","orphanscan")) {
			echo "You don't have access to scan orphan files.<br>";
			return;
		}
	}
	echo "Scanning [$RFS_SITE_PATH/$dir] \n"; if(!$RFS_CMD_LINE) echo "<br>";
	$dir_count=0; $dirfiles = array();
	$handle=opendir($RFS_SITE_PATH."/".$dir);
	if(!$handle) return 0;
	while (false!==($file = readdir($handle))) array_push($dirfiles,$file);
	closedir($handle);
	reset($dirfiles);
	
    $result = lib_mysql_query("select * from files");
    $i=0; $k=mysql_num_rows($result);
    while($i<$k) {
        $der = mysql_fetch_array($result);
        $filelist[$i] = stripslashes($der['location']);
        $i=$i+1;
    }
	for($x=0;$x<count($filelist);$x++) {
		// echo "$filelist[$x] \n";
		$filearray["$filelist[$x]"]=true;
	}
	while(list ($key, $file) = each ($dirfiles))  {
        if($file!=".") {
            if($file!="..") {
                if(is_dir($dir."/".$file)){
  				if( (substr($file,0,1)!=".") &&
					(substr($file,0,1)!="$") &&
					($file!="lost+found") )
				    orphan_scan($dir."/".$file,$RFS_CMD_LINE);
				}
				else {
					
					
					if(	($file!="desktop.ini") &&
						($file!="Thumbs.db") &&
						($file!="Folder.jpg") ) {
						
						
						
							
							
							
							
							$url="$dir/$file";
							$loc=addslashes("$dir/$file");
							if(isset($filearray["$url"])){

							}
							else{						
									$time=date("Y-m-d H:i:s");
									$filetype=lib_file_getfiletype($file);						
									$tdir=getcwd()."/$dir/$file";
								
									$filesizebytes=filesize("$tdir");
									$name=addslashes($file);
									$infile=addslashes($file);							
									lib_mysql_query("INSERT INTO `files` (`name`) VALUES('$infile');");
									$fid=mysql_insert_id();
									$loc=addslashes("$dir/$file");
									lib_mysql_query("UPDATE files SET `location`='$loc' where id='$fid'");
									$dname="system";
									if(!empty($data)) $dname=$data->name;							
									lib_mysql_query("UPDATE files SET `submitter`='$dname' where id='$fid'");
									lib_mysql_query("UPDATE files SET `category`='unsorted' where id='$fid'");
									lib_mysql_query("UPDATE files SET `hidden`='no' where id='$fid'");
									lib_mysql_query("UPDATE files SET `time`='$time' where id='$fid'");
									lib_mysql_query("UPDATE files SET filetype='$filetype' where id='$fid'");
									lib_mysql_query("UPDATE files SET size='$filesizebytes' where id='$fid'");
									$tmd5=md5_file ("$dir/$file");									
									lib_mysql_query("UPDATE files SET md5='$tmd5' where id='$fid'");

									echo "Added [$url] size[$filesizebytes] to database \n"; if(!$RFS_CMD_LINE) echo "<br>";
									if(!$RFS_CMD_LINE) lib_rfs_flush_buffers();
									$dir_count++;
							}
						}
					}
				}
			}
		}
	}
function m_files_purge_files($RFS_CMD_LINE){
	if(!$RFS_CMD_LINE)  {
		if(!lib_access_check("files","purge")) {
			echo "You don't have access to purge files. \n"; if(!$RFS_CMD_LINE) echo "<br>";
			return;
		}
	}
	$r=lib_mysql_query("select * from files");
	for($i=0;$i<mysql_num_rows($r);$i++){
		$file=mysql_fetch_object($r);
		if(!file_exists($file->location)) {
			echo "$file->location purged \n"; if(!$RFS_CMD_LINE) echo "<br>";
			$dloc=addslashes($file->location);
			lib_mysql_query("delete from files where location = '$dloc'");
		}
	}
}
function m_files_duplicate_add($loc1,$size1,$loc2,$size2,$md5) {
	$loc1=addslashes($loc1);
	$size1=addslashes($size1);
	$loc2=addslashes($loc2);
	$size2=addslashes($size2);
	$md5=addslashes($md5);
	$r=lib_mysql_query("select * from file_duplicates where loc1 = '$loc1'");
	if($r) if(mysql_num_rows($r)) return;
	lib_mysql_query("INSERT INTO `file_duplicates` (`loc1`,   `size1`,   `loc2`, `size2`,    `md5` ) VALUES ( '$loc1', '$size1', '$loc2',  '$size2', '$md5' ) ;");
}
function m_files_show_one_scanned_duplicate($RFS_CMD_LINE,$id,$color) {
		$f=lib_mysql_fetch_one_object("select * from files where id='$id'");
			echo "<tr>";		
		echo "<td	class='$color'>";
		echo " <input type=\"checkbox\" name=\"check_".$f->id."\">";		
		echo "</td>";
			echo "<td	class='$color'>";
		rfs_img_button_x("$addon_folder?action=del&id=".$f->id."&retpage=".urlencode(rfs_canonical_url()),"Delete ","$RFS_SITE_URL/images/icons/Delete.png",16,16);
		echo "</td>";	
		echo "<td class='$color'>";
		echo "<a href=\"$addon_folder?action=get_file&id=$f->id\">";
		echo $f->location;
		echo "</a>";
		echo "</td>";
		echo "<td class='$color'>";
		echo $f->size;
		echo "</td>";
		echo "<td class='$color'>";		
		lib_ajax("","files","id",$f->id,"category",70,"select,table,categories,name","files","edit","");
		echo "</td>";
		echo "<td class='$color'>";
		echo $f->md5;
		echo "</td>";
		echo "</tr>";
}
function m_files_show_scanned_duplicates($RFS_CMD_LINE) {
	eval(lib_rfs_get_globals());
	echo "<h1>Duplicate files</h1>";	
	$x=lib_mysql_row_count("file_duplicates");
	echo "There are $x duplicate files total";
	if(empty($fdlo)) $fdlo="0";
	if(empty($fdhi)) $fdhi="5";
		$limit=" limit $fdlo,$fdhi ";
	echo "<form enctype=application/x-www-form-URLencoded action=\"$addon_folder\" method=post>\n";
	echo "<input type=hidden name=action value=f_dup_rem_checked>";
	$r=lib_mysql_query("select * from file_duplicates $limit");
	echo "<div style=\"padding: 15px;\">";
	echo "<table border=0>";
	echo "<tr><th>";
	echo "<input type=checkbox name=whatly_diddly_do onclick=\"	\" >";	
	echo "</th><th>id</th><th>file location</th><th>file size</th><th>category</th><th>md5</th></tr>";
	for($i=0;$i<mysql_num_rows($r);$i++) {
		$dupe=mysql_fetch_object($r);
		$clr++; if($clr>2) $clr=1;
		$color="rfs_project_table_$clr";
		$rr=lib_mysql_query("select * from files where md5 = '$dupe->md5'");
		for($u=0;$u<mysql_num_rows($rr);$u++)  {		
			$f=mysql_fetch_object($rr);
			m_files_show_one_scanned_duplicate($RFS_CMD_LINE,$f->id,$color);
		}
		// m_files_show_one_scanned_duplicate($RFS_CMD_LINE,$filelist[$dupe->loc2]['id'],$color);		
	}
	echo "</table>";
	echo "<input type=submit name=submit value=\"Delete All Checked\">";
	echo "</form>";
	echo "</div>";
}
function m_files_show_duplicate_files($RFS_CMD_LINE) {
	$result = lib_mysql_query("select * from files");
	$i=0; $k=mysql_num_rows($result);	
	while($i<$k) {
		$der = mysql_fetch_object($result);
		$r2 = 
		lib_mysql_query("select * from files where (md5 = '$der->md5' ) and 
												 (location != '$der->location') ");
		if($r2)
		for($z=0;$z<mysql_num_rows($r2);$z++) {
			$dupe = mysql_fetch_object($r2);
		m_files_duplicate_add( $der->location, $der->size, $dupe->location,$dupe->size,$der->md5);
			echo "F1: $der->md5 $der->size $der->location \n"; if(!$RFS_CMD_LINE) echo "<br>";
			echo "F2: $dupe->md5 $dupe->size $dupe->location \n"; if(!$RFS_CMD_LINE) echo "<br>";
			echo "\n"; if(!$RFS_CMD_LINE) echo "<br>";
		}
		if(!$RFS_CMD_LINE) lib_rfs_flush_buffers();
		$i++;
	}
}
function m_files_scan_duplicate_files2($RFS_CMS_LINE) {
	$result = lib_mysql_query("select * from files");
	$i=0; $k=mysql_num_rows($result);
	while($i<$k) {	
		$der = mysql_fetch_array($result);

		$filelist[$i]  = 	$der['location'];
		$filemd5[$i]   = 	$der['md5'];
		$filesize[$i]  = 	$der['size'];

		$x				= $der['location'];
		$loc_md5[$x] 	= $der['md5'];
		$loc_size[$x] = $der['size'];
		$i=$i+1;
	}
	echo "TOTAL FILES ".count($filelist)." \n"; if(!$RFS_CMD_LINE) echo "<br>";
	if(!$RFS_CMD_LINE) echo "<table border=0>";
	for($i=0;$i<count($filelist);$i++) {
		$tmd5=$filemd5[$i];
		foreach($loc_md5 as $k => $v) {
			if(!empty($v)) {
				if($v==$tmd5) {
					if($k!=$filelist[$i]) {
						if(!isset($dupefound[$filelist[$i]])) {
							echo "$k = $filelist[$i]\n";
							m_files_duplicate_add( $filelist[$x],$filesize[$x],$k,$loc_size[$filelist[$x]],$tmd5);
							$dupefound["$k"]=true;							
						}
					}
				}
			}
		}
	}
	if(!$RFS_CMD_LINE) 
		echo "</table>";	
}
function m_files_show_duplicate_files2($RFS_CMD_LINE) {
	echo "MD5 SEARCH \n"; if(!$RFS_CMD_LINE) echo "<br>";
    $result = lib_mysql_query("select * from files");
    $i=0; $k=mysql_num_rows($result);
    while($i<$k) {
        $der = mysql_fetch_array($result);
        $filelist[$i] = $der['location'];
		$filemd5[$i]  = $der['md5'];
		$x=$der['location'];
		$filearray[$x]=$der['md5'];
        $i=$i+1;
    }	
	echo "TOTAL FILES ".count($filelist)." \n"; if(!$RFS_CMD_LINE) echo "<br>";
	if(!$RFS_CMD_LINE) echo "<table border=0>";
	for($i=0;$i<count($filelist);$i++) {
		$tmd5=$filemd5[$i];
		foreach($filearray as $k => $v) {
			if(!empty($v)) {
				if($v==$tmd5) {
					if($k!=$filelist[$i]) {
						if(!isset($dupefound[$filelist[$i]])) {
							echo "$k = $filelist[$i]\n";
							$dupefound["$k"]=true;
						}
					}
				}
			}
		}
	}
	if(!$RFS_CMD_LINE) 
		echo "</table>";
}
function m_files_delete($fid,$annihilate) {
	eval(lib_rfs_get_globals());
	$filedata=m_files_getfiledata($fid);
	lib_mysql_query("delete from files where id = '$fid'");
	lib_mysql_query("delete from file_duplicates where loc1 = '$filedata->location'");
	lib_mysql_query("delete from file_duplicates where loc2 = '$filedata->location'");
	if($annihilate=="yes") {
		lib_file_delete($RFS_SITE_PATH."/".$filedata->location);
	}
	echo "<font style='color: red;'>Deleted [$filedata->id]...</font>";
}
function m_files_update_file($fid) {
	$file=lib_mysql_fetch_one_object("select * from files where id = '$fid'");
	if($file->id!=$fid) return;
	$time=date("Y-m-d H:i:s");
	$filetype=lib_file_getfiletype($file->name);						
	$filesizebytes=filesize($file->location);
	if(empty($file->submitter)) lib_mysql_query("UPDATE files SET `submitter`='system' where id='$fid'");
	if(empty($file->category))  lib_mysql_query("UPDATE files SET `category`='unsorted' where id='$fid'");
	if(empty($file->hidden))    lib_mysql_query("UPDATE files SET `hidden`='no' where id='$fid'");
	if(empty($file->time))      lib_mysql_query("UPDATE files SET `time`='$time' where id='$fid'");
	if(empty($file->filetype))  lib_mysql_query("UPDATE files SET filetype='$filetype' where id='$fid'");
	if(empty($file->size))  	   lib_mysql_query("UPDATE files SET size='$filesizebytes' where id='$fid'");
	if(empty($file->md5)) { $tmd5=md5_file ($file->location);									
		lib_mysql_query("UPDATE files SET md5='$tmd5' where id='$fid'");
	}
}
function m_files_is_link($fid) {
	$filedata=m_files_getfiledata($fid);
	if( (stristr($filedata->location,"http://")) ||
		(stristr($filedata->location,"https://")) ||
		(stristr($filedata->location,"ftp://")) ||
		(stristr($filedata->location,"ftps://")) )
			return true;
	return false;	
}
?>