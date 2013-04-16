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
    $dir_count=0;
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

function sc_echo_file($file) {
	if(file_exists($file)) {
		echo "Filename: $file\n";
		echo file_get_contents($file);
	}
}

function sc_file_get_readme($file_name) { eval (scg());
	
	system("yes| rm $RFS_SITE_PATH/tmp/*");	
	system("cd $RFS_SITE_PATH/tmp");
	
	exec("yes| 7z e -o/var/www/tmp $file_name");
	
	sc_echo_file("$RFS_SITE_PATH/tmp/FILE_ID.DIZ");
	sc_echo_file("$RFS_SITE_PATH/tmp/README.md");
	sc_echo_file("$RFS_SITE_PATH/tmp/Readme.txt");
	sc_echo_file("$RFS_SITE_PATH/tmp/Readme");
	sc_echo_file("$RFS_SITE_PATH/tmp/ReadMe");
	sc_echo_file("$RFS_SITE_PATH/tmp/README");
	sc_echo_file("$RFS_SITE_PATH/tmp/README.TXT");
	sc_echo_file("$RFS_SITE_PATH/tmp/readme.txt");
	sc_echo_file("$RFS_SITE_PATH/tmp/README.1ST");
	
	sc_echo_file("$RFS_SITE_PATH/tmp/License.txt");
	
		
}

?>
