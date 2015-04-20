<?php
include_once("themes/_templates/theme_templates.php");
function lib_themes_get_array() {
	$dr=$GLOBALS['RFS_SITE_PATH']."/themes/";
	$themes=array();
	$d = opendir($dr) or die("Wrong path: $dr");
	while(false!==($entry = readdir($d))) {
		if(($entry != '.') && ($entry != '..') && (!is_dir($dir.$entry)) ) {
			if($entry!="_templates")
				if(!strstr($entry,"."))
					array_push($themes,$entry);
		}
	}
	closedir($d);
	natcasesort($themes);
	reset($themes);
	return $themes;
}

function lib_themes_get_image($x) {
    if(file_exists("$RFS_SITE_PATH/themes/$theme/$x"))
        $rx="$RFS_SITE_URL/themes/$theme/$x";
    else $rx="$RFS_SITE_URL/$x";
    return $rx;
}

