<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
sc_div(__FILE__);
/////////////////////////////////////////////////////////////////////////////////////////
function sc_getfiletype($filen){
	$finfo=pathinfo($filen);
	return strtolower( $finfo['extension']	);
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_sizefile($bytesize) {
    $size = $bytesize." bytes";
	
    if($bytesize>1024)       		$size = (round($bytesize/1024,2))." kB"; 				// kilobyte 2^10
    if($bytesize>1048576)    		$size = (round($bytesize/1048576,2))." MB";			// megabyte 2^20
    if($bytesize>1073741824) 		$size = (round($bytesize/1073741824,2))." GB";		// gigabyte 2^30
	 if($bytesize>1099511627776) 	$size = (round($bytesize/1099511627776,2))." TB";	// terabyte 2^40
	 // PB petabyte 2^50
	 // EB exabyte 2^60
	 // ZB zettabyte 2^70
	 // YB yottabyte 2^80
    return $size;
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_folder_to_array($folder) {
	$dirfiles = array();
	$handle=opendir($folder);
	if(!$handle) return 0;
	while (false!==($file = readdir($handle)))
		array_push($dirfiles,$file);
	closedir($handle);
	reset($dirfiles);
	asort($dirfiles);
	return $dirfiles;
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_get_folder_files($folder){
    $dirfiles = array();
    $handle=opendir($folder);
    if(!$handle) return 0;
    while (false!==($file = readdir($handle)))
		array_push($dirfiles,$file);
    closedir($handle);
	reset($dirfiles);
	asort($dirfiles);
    return $dirfiles;
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_multi_rename($folder,$old_pattern,$new_pattern) {
	$dirfiles=sc_get_folder_files($folder);
    while(list ($key, $file) = each ($dirfiles)) {
        if($file!=".") {
            if($file!="..") {
                if(is_dir($file)){ }
                else {
                    $nfile=str_replace($old_pattern,$new_pattern,$file);
						// echo getcwd()." OLD[$folder/$file] NEW[$folder/$nfile]<BR>";
						system("mv $folder/$file $folder/$nfile");

                }
            }
        }
    }
}
/////////////////////////////////////////////////////////////////////////////////////////
// Echo file
function sc_echo_file($file) { eval(scg()); 
	if(file_exists($file)) {
		echo "Filename: $file\n";
		$f=file_get_contents($file);
		$f=str_replace("<","&lt;",$f);
		return $f;
	}
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_file_get_readme($file_name) { eval (scg());
	
	system("yes| rm -R $RFS_SITE_PATH/tmp/*");	
	system("yes| rm -R $RFS_SITE_PATH/tmp/.*");
	
	system("cd $RFS_SITE_PATH/tmp");
	
	exec("yes| 7z e -o/var/www/tmp '$file_name'");
	
	system("renlow $RFS_SITE_PATH/tmp");
	$dirfiles=sc_get_folder_files("$RFS_SITE_PATH/tmp");
	
	while(list ($key, $file) = each ($dirfiles)) {
		
		// TODO: Add customizable filetype results
		
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
			if(( 	(stristr($file,"read")) ||
					(stristr($file,".nfo")) ||
					(stristr($file,"version")) ||
					(stristr($file,".diz")) ||
					(stristr($file,".doc")) ||
					(stristr($file,".txt")) ||
					(stristr($file,".msg")) ||					
					(stristr($file,".cat")) ||
					(stristr($file,".dat")) ||
					(stristr($file,".h")) ||
					(stristr($file,".hpp")) ||
					(stristr($file,".ttf")) ||
					(stristr($file,"index"))  
				
					)  &&
					
					(!stristr($file,".com")) &&
					(!stristr($file,".exe")) &&
					(!stristr($file,".chm")) &&
					(!stristr($file,".class"))
				) {
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
/////////////////////////////////////////////////////////////////////////////////////////
function sc_touch_dir($dir) {
	if(!file_exists($dir)) {
		echo " making $dir <br>";
		mkdir($dir,0775);
	}
}
?>