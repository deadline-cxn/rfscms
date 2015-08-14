<?php
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
function lib_file_getfiletype($filen){
	$finfo=pathinfo($filen);
	return strtolower( $finfo['extension']	);
}
function lib_file_process_upload($filedata,$chmod,$filepath,$pre,$suf,$table,$key) {
	eval(lib_rfs_get_globals());
		$fx=$_FILES[$filedata];
		$uploadFile=$filepath."/".$pre.$fx['name'].$suf;
		$uploadFile =str_replace("//","/",$uploadFile);
		if(!stristr($uploadFile,$RFS_SITE_PATH)) $uploadFile=$RFS_SITE_PATH.$uploadFile;
		if(move_uploaded_file($fx['tmp_name'], $uploadFile)) {
			system("chmod $chmod $uploadFile");
			$error="File is valid, and was successfully uploaded. ";
			echo "<P>You sent: ".$fx['name'].", a ".$fx['size']." byte file with a mime type of ";
			echo $fx['type']."</p>\n";
			echo "<p>It was stored as [$httppath"."/".$fx['name']."]</p>\n";
			return $uploadFile;
		}
		else {
			echo "[".$fx['name']."]";
			echo "[".$fx['error']."]";
			echo "[".$fx['tmp_name']."]";
			echo "[".$uploadFile."]\n";
		}
		if(!$error) $error .= "No files have been selected for upload";
	return false;
}
function lib_file_is_link($file) {
	if( (stristr($file,"http://")) ||
		(stristr($file,"https://")) ||
		(stristr($file,"ftp://")) ||
		(stristr($file,"ftps://")) )
			return true;
	return false;	
}
function lib_file_size($file) {
	if(lib_file_is_link($file)) return lib_file_get_remote_filesize($file);
	return filesize($file);
}
function lib_file_get_remote_filesize($url) {
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, TRUE);
	curl_setopt($ch, CURLOPT_NOBODY, TRUE);
	$data = curl_exec($ch);
	$size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
	curl_close($ch);
	return $size;
}
// function lib_file_get_size($file) { return lib_file_sizefile(filesize($file)); }
function lib_file_sizefile($bytesize) {
    $size = $bytesize." bytes";	
    if($bytesize>1024)       		$size = (round($bytesize/1024,2))."kB"; 				// kilobyte 2^10
    if($bytesize>1048576)    		$size = (round($bytesize/1048576,2))."MB";			// megabyte 2^20
    if($bytesize>1073741824) 		$size = (round($bytesize/1073741824,2))."GB";		// gigabyte 2^30
	 if($bytesize>1099511627776) 	$size = (round($bytesize/1099511627776,2))."TB";	// terabyte 2^40
	 // PB petabyte 2^50
	 // EB exabyte 2^60
	 // ZB zettabyte 2^70
	 // YB yottabyte 2^80
    return $size;
}
function lib_file_folder_to_array($folder) {
	$dirfiles = array();
	$handle=opendir($folder);
	if(!$handle) return 0;
	while (false!==($file = readdir($handle))) {
		if( 
		($file!=".") && 
		($file!="..") && 
		(!empty($file)) )		
		array_push($dirfiles,$file);
	}
	closedir($handle);
	reset($dirfiles);
	asort($dirfiles);
	return $dirfiles;
}
function lib_file_get_folder_files($folder){
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
function lib_file_multi_rename($folder,$old_pattern,$new_pattern) {
	$dirfiles=lib_file_get_folder_files($folder);
    while(list ($key, $file) = each ($dirfiles)) {
        if($file!=".") {
            if($file!="..") {
                if(is_dir($file)){ }
                else {
                    $nfile=str_replace($old_pattern,$new_pattern,$file);
					system("mv $folder/$file $folder/$nfile");
                }
            }
        }
    }
}
function lib_file_echo_file($file) { 
	eval(lib_rfs_get_globals()); 
	if(file_exists($file)) {
		$f="Filename: $file\n";
		$f.=file_get_contents($file);
		$f=str_replace("<","&lt;",$f);
		return $f;
	}
}
function lib_file_file_get_readme($file_name) { 
	eval (lib_rfs_get_globals());
	system("yes| rm -R $RFS_SITE_PATH/tmp/*");	
	system("yes| rm -R $RFS_SITE_PATH/tmp/.*");
	system("cd $RFS_SITE_PATH/tmp");

    $go="yes| 7z e -o/var/www/tmp '$file_name'";
    if(stristr($file_name,".rar")) $go="yes| unrar x '$file_name' /var/www/tmp";
     
	exec($go);
    
	system("renlow $RFS_SITE_PATH/tmp");
	$dirfiles=lib_file_get_folder_files("$RFS_SITE_PATH/tmp");
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
				// echo "$file ";
				echo "<img src=\"$RFS_SITE_URL/tmp/$file\" title=\"$file\">";
			}
		}
	}

	reset($dirfiles);
    while(list ($key, $file) = each ($dirfiles)) {
		if(substr($file,0,1)!=".") {
		  
			if(( 	(stristr($file,"read")) ||            					
                    (stristr($file,"history")) ||
					(stristr($file,"version")) ||
                    (stristr($file,"install")) ||
                    (stristr($file,"licen")) ||
                    (stristr($file,"todo")) ||
                    (stristr($file,"copying")) ||
                    (stristr($file,"changes")) ||
                    
                    
                    (stristr($file,".asc")) ||
                    (stristr($file,".dsc")) ||
                    (stristr($file,".md")) ||
                    (stristr($file,".sig")) ||
					(stristr($file,".diz")) ||
					(stristr($file,".doc")) ||
                    (stristr($file,"document")) ||
					(stristr($file,".txt")) ||
                    (stristr($file,"text")) ||
                    (stristr($file,".nfo")) ||
                    (stristr($file,"info")) ||
					(stristr($file,".msg")) ||					
					(stristr($file,".cat")) ||
                    (stristr($file,".url")) ||
					(stristr($file,".hpp")) ||
                    (stristr($file,".cpp")) ||
                    (stristr($file,".sh")) ||
                    (stristr($file,".pl")) ||
                    (stristr($file,".cgi")) ||
                    (stristr($file,".bat"))// ||
                    
                    
					//(stristr($file,".ttf"))  
				
					)  &&
					
					(!stristr($file,".htm")) &&
                    (!stristr($file,".html")) &&
					(!stristr($file,".dll")) &&					
					(!stristr($file,".exe")) &&
					(!stristr($file,".chm")) &&
					(!stristr($file,".class"))
				) {
                
                echo "<hr>";
                
				$x=lib_file_echo_file("$RFS_SITE_PATH/tmp/$file");
				echo "<pre>";
                echo $x;
                echo "</pre>";
				
                
				
				if(stristr($file,".diz")) {
					$db_file=str_replace("$RFS_SITE_PATH/","",$file_name);
					$x=addslashes($x);
					$cf=lib_mysql_fetch_one_object("select * from files where location='$db_file'");
					if(empty($cf->description)) {
						lib_mysql_query("update files set description=\"$x\" where location='$db_file'");
					}
				}				
			}
		}
	}
}
function lib_file_dir_empty($dir){ 
     return (($files = @scandir($dir)) && count($files) <= 2);
} 
function lib_file_touch_dir($dir) { 
	eval(lib_rfs_get_globals());
	if(!file_exists($dir)) {
		system("$RFS_SITE_SUDO_CMD mkdir $dir");
		system("$RFS_SITE_SUDO_CMD chmod 775 $dir");
		system("$RFS_SITE_SUDO_CMD chown www-data:www-data $dir");
	}
}
function lib_file_delete($file) {
	unlink($file);
}
