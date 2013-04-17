<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFS CMS (c) 2012 Seth Parson http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
sc_div(__FILE__);

function sc_scrubfiles() {
    sc_query(" CREATE TABLE files2 like files; ");
	sc_query(" INSERT files2 SELECT * FROM files GROUP BY location;" );
	sc_query(" RENAME TABLE `files`  TO `files_goto_hell`; ");
	sc_query(" RENAME TABLE `files2` TO `files`; " );
	sc_query(" DROP TABLE files_goto_hell; ");
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
    while($i<$k)
    {
        $der = mysql_fetch_array($result);
        $filelist[$i] = $der['id'];
        $i=$i+1;
    }
    return $filelist;
}


function sc_getfiletype($filen){
	$finfo=pathinfo($filen);
	return strtolower( $finfo['extension']	);
    // $ext = explode(".",$filen,40); $j = count ($ext)-1; $f_ext = "$ext[$j]"; $f_ext
}

function sc_sizefile($bytesize) {
    $size = $bytesize." bytes";
    if($bytesize>1024)       $size = (round($bytesize/1024,2))." Kb";
    if($bytesize>1048576)    $size = (round($bytesize/1048576,2))." Mb";
    if($bytesize>1073741824) $size = (round($bytesize/1073741824,2))." Gb";
    return $size;
}

function sc_get_folder_files($folder){
    $dirfiles = array();
    $handle=opendir($folder);
    if(!$handle) return 0;
    while (false!==($file = readdir($handle))) array_push($dirfiles,$file);
    closedir($handle); reset($dirfiles); asort($dirfiles);
    return $dirfiles;
}

function sc_multi_rename($folder,$old_pattern,$new_pattern) {
    
    $dirfiles=sc_get_folder_files($folder);
    while(list ($key, $file) = each ($dirfiles)) {
        if($file!=".") {
            if($file!="..") {
                if(is_dir($file)){ }
                else {
                    $nfile=str_replace($old_pattern,$new_pattern,$file);
  //                  echo getcwd();
//                    echo "OLD[$folder/$file] NEW[$folder/$nfile]<BR>";
						system("mv $folder/$file $folder/$nfile");

                }
            }
        }
    }
}

function sc_echo_file($file) { eval(scg());
	if(file_exists($file)) {
		echo "Filename: $file\n";
		$f=file_get_contents($file);
		$f=str_replace("<","&lt;",$f);
		return $f;
	}
}

function sc_file_get_readme($file_name) { eval (scg());
	
	system("yes| rm -R $RFS_SITE_PATH/tmp/*");	
	system("yes| rm -R $RFS_SITE_PATH/tmp/.*");
	
	system("cd $RFS_SITE_PATH/tmp");
	
	exec("yes| 7z e -o/var/www/tmp '$file_name'");
	
	system("renlow $RFS_SITE_PATH/tmp");
	$dirfiles=sc_get_folder_files("$RFS_SITE_PATH/tmp");
	
	while(list ($key, $file) = each ($dirfiles)) {
		
		
		if(substr($file,0,1)!=".") {
			if(stristr($file,".ico")) {
				echo system("icontopbm -x $RFS_SITE_URL/tmp/$file");
				echo "$file<br>";
				echo "<img src=\"$RFS_SITE_URL/tmp/$file\"><hr>";
				
			}
		}
			
		if(substr($file,0,1)!=".") {
			if((stristr($file,".png")) ||
				(stristr($file,".jpg")) ||
				(stristr($file,".bmp")) ||
				
				(stristr($file,".jpeg")) ||
				(stristr($file,".gif")) )  { 
					echo "$file<br>";
					echo "<img src=\"$RFS_SITE_URL/tmp/$file\"><hr>";
				}
		}
	}

	reset($dirfiles);
    while(list ($key, $file) = each ($dirfiles)) {
		if(substr($file,0,1)!=".") {
			if(( (stristr($file,"read")) ||
				(stristr($file,".nfo")) ||
				
				(stristr($file,".diz")) ||
				(stristr($file,".doc")) ||
				(stristr($file,".txt")) ||
				(stristr($file,".msg")) ||
				(stristr($file,".c")) ||
				(stristr($file,".h")) ||
				(stristr($file,".hpp")) ||
				(stristr($file,".ttf")) ||
				(stristr($file,"index"))  
				
					)  &&
					
				(!stristr($file,".com")) ) {
				$x=sc_echo_file("$RFS_SITE_PATH/tmp/$file");
				echo $x;
				echo "<hr>";
				
				if(stristr($file,".diz")) {
					$db_file=str_replace("$RFS_SITE_PATH/","",$file_name);
					$x=addslashes($x);
					$cf=mfo1("select * from files where location='$db_file'");
					if(empty($cf->description)) {
						sc_query("update files set description=\"$x\" where location='$db_file'");
					}
				}				
			}
		}
	}
	
}



?>
