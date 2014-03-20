<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
lib_div(__FILE__);
/////////////////////////////////////////////////////////////////////////////////////////
function lib_file_getfiletype($filen){
	$finfo=pathinfo($filen);
	return strtolower( $finfo['extension']	);
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_file_process_upload($filedata,$chmod,$filepath,$pre,$suf,$table,$key) { eval(lib_rfs_get_globals());
		echo "<p> Upload file</p>\n";		
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
		/* if($fu_hidden=="no") {
				$httppath=$httppath."/".$fx['name'];					
				$finfo=pathinfo($uploadFile);
				$filetype=strtolower($finfo['extension']);
				// if(empty($fu_name)) $fu_name=$fx['name'];				// $fu_name=addslashes($fu_name);				// $time1=date("Y-m-d H:i:s");				lib_mysql_query("INSERT INTO `files` 	(`name`, 		`submitter`, 		`time`, `worksafe`, 	`hidden`, 		`category`, 	 `filetype`)											VALUES	('$fu_name',	'$data->name', '$time1', '$fu_sfw',	'$fu_hidden','$fu_category', '$filetype');");$id=mysql_insert_id();echo "DATABASE ID[$id]<br>";
				echo "<a href=\"$RFS_SITE_URL/modules/core_files/files.php?action=get_file&id=$id\">View file information</a><br>";
				$httppath=str_replace("$RFS_SITE_URL/","",$httppath);
				lib_mysql_query("UPDATE files SET location	='$httppath' 		where id='$id'");								
				$fu_desc=addslashes($fu_desc);
				lib_mysql_query("UPDATE files SET description	='$fu_desc'	 	where id='$id'");	
				$filesizebytes=$fx['size'];
				lib_mysql_query("UPDATE files SET size			='$filesizebytes'	where id='$id'");					
				$extra_sp=$fx['size']/10240;
				$data->files_uploaded=$data->files_uploaded+1;
				lib_mysql_query("update `users` set `files_uploaded`='$data->files_uploaded' where `name`='$data->name'");
				} */
			return $uploadFile;
		}
		else {
			//UPLOAD_ERR_OK        //Value: 0; There is no error, the file uploaded with success.
			//UPLOAD_ERR_INI_SIZE  //Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini.
			//UPLOAD_ERR_FORM_SIZE //Value: 2; The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.
			//UPLOAD_ERR_PARTIAL   //Value: 3; The uploaded file was only partially uploaded.
			//UPLOAD_ERR_NO_FILE
			$error ="File upload error!";
			echo "File upload error! ";
			echo "[".$fx['name']."]";
			echo "[".$fx['error']."]";
			echo "[".$fx['tmp_name']."]";
			echo "[".$uploadFile."]\n";
		}
		if(!$error) {
			$error .= "No files have been selected for upload";
		}		
	return false;
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_file_get_size($file) {
	return lib_file_sizefile(filesize($file));
}
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
/////////////////////////////////////////////////////////////////////////////////////////
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
/////////////////////////////////////////////////////////////////////////////////////////
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
/////////////////////////////////////////////////////////////////////////////////////////
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
/////////////////////////////////////////////////////////////////////////////////////////
function lib_file_echo_file($file) { 
	eval(lib_rfs_get_globals()); 
	if(file_exists($file)) {
		echo "Filename: $file\n";
		$f=file_get_contents($file);
		$f=str_replace("<","&lt;",$f);
		return $f;
	}
}
/////////////////////////////////////////////////////////////////////////////////////////
function lib_file_file_get_readme($file_name) { 
	eval (lib_rfs_get_globals());
	system("yes| rm -R $RFS_SITE_PATH/tmp/*");	
	system("yes| rm -R $RFS_SITE_PATH/tmp/.*");
	system("cd $RFS_SITE_PATH/tmp");
	exec("yes| 7z e -o/var/www/tmp '$file_name'");
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
					
					(stristr($file,".h")) ||
					(stristr($file,".hpp")) ||
					(stristr($file,".ttf")) ||
					(stristr($file,"index"))  
				
					)  &&
					
					(!stristr($file,".com")) &&
					(!stristr($file,".dll")) &&					
					(!stristr($file,".exe")) &&
					(!stristr($file,".chm")) &&
					(!stristr($file,".class"))
				) {
				$x=lib_file_echo_file("$RFS_SITE_PATH/tmp/$file");
				echo $x;
				echo "<hr>";
				
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
/////////////////////////////////////////////////////////////////////////////////////////
function lib_file_touch_dir($dir) { 
	eval(lib_rfs_get_globals());
	if(!file_exists($dir)) {
		system("$RFS_SITE_SUDO_CMD mkdir $dir");
		system("$RFS_SITE_SUDO_CMD chmod 775 $dir");
		system("$RFS_SITE_SUDO_CMD chown www-data:www-data $dir");
	}
}
/////////////////////////////////////////////////////////////////////////////////////////
?>