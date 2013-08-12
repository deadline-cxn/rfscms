<?
include_once("include/lib.all.php");

sc_add_menu_option("Files","$RFS_SITE_URL/modules/files/files.php");

sc_access_method_add("files", "upload");
sc_access_method_add("files", "addlink");
sc_access_method_add("files", "orphanscan");
sc_access_method_add("files", "purge");
sc_access_method_add("files", "sort");
sc_access_method_add("files", "edit");
sc_access_method_add("files", "delete");
sc_access_method_add("files", "xplorer");
sc_access_method_add("files", "xplorershell");

sc_database_add("files","md5",		"text",	"NOT NULL");

/////////////////////////////////////////////////////////////////////////////////////////////////////////
// MODULE FILES
function sc_module_mini_files($x) { eval(scg());
    sc_div("FILES MODULE SECTION");
    echo "<h2>Last $x Files</h2>";
    $result=sc_query("select * from files where category !='unsorted' order by `time` desc limit 0,$x");
    $numfiles=mysql_num_rows($result);
    echo "<table border=0 cellspacing=0 cellpadding=0 >";
    $gt=2;
    for($i=0;$i<$numfiles;$i++){
        $file=mysql_fetch_object($result);
        $link="$RFS_SITE_URL/modules/files/files.php?action=get_file&id=$file->id";
        $fdescription=str_replace('"',"&quote;",stripslashes($file->description));
        $gt++; if($gt>2)$gt=1;
        echo "<tr><td class=sc_file_table_$gt>";
        echo "<a href=\"$link\">$file->name</a> ";
        echo"</td><td class=sc_file_table_$gt>";
        echo sc_sizefile($file->size);
		// echo "<br>";
        echo "</td></tr>";
    }
    echo "</table>";
    //echo "<p align=right>(<a href=$RFS_SITE_URL/modules/files/files.php class=a_cat>More...</a>)</p>";
}


function sc_scrubfiledatabase() {
	sc_query(" CREATE TABLE files2 like files; ");
	sc_query(" INSERT files2 SELECT * FROM files GROUP BY location;" );
	sc_query(" RENAME TABLE `files`  TO `files_scrub`; ");
	sc_query(" RENAME TABLE `files2` TO `files`; " );
	sc_query(" DROP TABLE files_scrub; ");
}

function sc_getfiledata($file){
    $query = "select * from files where `name` = '$file' ";
    if(intval($file)!=0)
    $query = "select * from files where `id` = '$file'";
    $result = sc_query($query);
    if(mysql_num_rows($result) >0 ) $filedata = mysql_fetch_object($result);
    return $filedata;
}

function sc_getfilelist($filesearch,$limit){
    $query = "select * from files";
    if(!empty($filesearch)) $query.=" ".$filesearch;
    $query.=" order by `name` asc ";
    if(!empty($limit)) $query.=" limit $limit";
    $result = sc_query($query);
    $i=0; $k=mysql_num_rows($result);
    while($i<$k) {
        $der = mysql_fetch_array($result);
        $filelist[$i] = $der['id'];
        $i=$i+1;
    }
    return $filelist;
}


function md5_scan($RFS_CMD_LINE) {
	$filelist=sc_getfilelist(" ",0);
	for($i=0;$i<count($filelist);$i++) {
		$filedata=sc_getfiledata($filelist[$i]);
		$fl=stripslashes($filedata->location);
		$tmd5=@md5_file ($fl);
		if($tmd5) {
			if($tmd5!=$filedata->md5) {
				if(!empty($filedata->md5))
					echo "(MD5 WARNING) $filedata->location $tmd5 (database: $filedata->md5)  \n"; if(!$RFS_CMD_LINE) echo "<br>";
				else {
					echo "(MD5 UPDATED) $filedata->location $tmd5  \n"; if(!$RFS_CMD_LINE) echo "<br>";
					sc_query("UPDATE files SET md5='$tmd5' where id='$filedata->id'");
				}
			} 
			else {
				/// echo ".";
				// echo "(MD5 MATCHES) $filedata->location $tmd5 $filedata->md5 \n";  if(!$RFS_CMD_LINE) echo "<br>";
			}
		}
	}
}


function quick_md5_scan($RFS_CMD_LINE) {
	$filelist=sc_getfilelist(" ",0);
	for($i=0;$i<count($filelist);$i++) {
		$filedata=sc_getfiledata($filelist[$i]);
		$fl=stripslashes($filedata->location);
		if(empty($filedata->md5)) {
			$tmd5=@md5_file ($fl);
			if($tmd5) {
				if($tmd5!=$filedata->md5) {
					if(!empty($filedata->md5))
						echo "(MD5 WARNING) $filedata->location $tmd5 (database: $filedata->md5)  \n"; if(!$RFS_CMD_LINE) echo "<br>";
					else {
						echo "(MD5 UPDATED) $filedata->location $tmd5  \n"; if(!$RFS_CMD_LINE) echo "<br>";
						sc_query("UPDATE files SET md5='$tmd5' where id='$filedata->id'");
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



function orphan_scan($dir,$RFS_CMD_LINE) { eval(scg());
	if(!$RFS_CMD_LINE) {
		if(!sc_access_check("files","orphanscan")) {
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
	
    $result = sc_query("select * from files");
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
                    $url="$dir/$file";
					$loc=addslashes("$dir/$file");
					
					// echo ".. $url ".$filearray[$url]."\n";
					if(isset($filearray["$url"])){

                    }
                    else{						
                        $time=date("Y-m-d H:i:s");
                        $filetype=sc_getfiletype($file);						
						$tdir=getcwd()."/$dir/$file";
						//echo "--- $tdir --- \n ";
                        $filesizebytes=filesize("$tdir");
						//echo "$filesizebytes \n";

						// if($filesizebytes>0) {

								$name=addslashes($file);
								$infile=addslashes($file);							
								sc_query("INSERT INTO `files` (`name`) VALUES('$infile');");
								$fid=mysql_insert_id();
								$loc=addslashes("$dir/$file");
								sc_query("UPDATE files SET `location`='$loc' where id='$fid'");
								$dname="system";
								if(!empty($data)) $dname=$data->name;							
								sc_query("UPDATE files SET `submitter`='$dname' where id='$fid'");
								sc_query("UPDATE files SET `category`='unsorted' where id='$fid'");
								sc_query("UPDATE files SET `hidden`='no' where id='$fid'");
								sc_query("UPDATE files SET `time`='$time' where id='$fid'");
								sc_query("UPDATE files SET filetype='$filetype' where id='$fid'");
								sc_query("UPDATE files SET size='$filesizebytes' where id='$fid'");
								
								$tmd5=md5_file ("$dir/$file");
								
								sc_query("UPDATE files SET md5='$tmd5' where id='$fid'");

								echo "Added [$url] size[$filesizebytes] to database \n"; if(!$RFS_CMD_LINE) echo "<br>";
								if(!$RFS_CMD_LINE) sc_flush_buffers();
								$dir_count++;
								
						// }
					}
				}
            }
        }
    }
}

function purge_files($RFS_CMD_LINE){
	if(!$RFS_CMD_LINE)  {
		if(!sc_access_check("files","purge")) {
			echo "You don't have access to purge files. \n"; if(!$RFS_CMD_LINE) echo "<br>";
			return;
		}
	}
	$r=sc_query("select * from files");
	for($i=0;$i<mysql_num_rows($r);$i++){
		$file=mysql_fetch_object($r);
		if(!file_exists($file->location)) {
			echo "$file->location purged \n"; if(!$RFS_CMD_LINE) echo "<br>";
			$dloc=addslashes($file->location);
			sc_query("delete from files where location = '$dloc'");
		}
	}
}

function sc_show_duplicate_files($RFS_CMD_LINE) {
	
	echo "MD5 SEARCH \n"; if(!$RFS_CMD_LINE) echo "<br>";
		
	// $filelist=sc_getfilelist(" ",0);

    $result = sc_query("select * from files");
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
?>
