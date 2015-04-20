<?php
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////

function lib_modules_register($name,$core,$loc,$version,$sub_version,$release,$description,$requirements,$cost,$license,$dependencies,$author,$author_email,$author_website,$images,$file_url,$git_repository) {
	global $RFS_SITE_PATH,$RFS_SITE_URL;
	global $RFS_MODULE;
    $RFS_MODULE[$name]=array();
    $RFS_MODULE[$name]["core"]=$core;
    $url=str_replace("$RFS_SITE_PATH","$RFS_SITE_URL",$loc);
	$RFS_MODULE[$name]["url"]=$url;
	$url=str_replace("/$name.php","",$url);
	$RFS_MODULE[$name]["base_url"]=$url;
	$loc=str_replace("/$name.php","",$loc);
	$RFS_MODULE[$name]["loc"]=$loc;	
	$RFS_MODULE[$name]["version"]=$version;		
	$RFS_MODULE[$name]["sub_version"]=$sub_version;
	$RFS_MODULE[$name]["file"]=$loc;
	$RFS_MODULE[$name]["author"]=$author;
	$RFS_MODULE[$name]["author_email"]=$sub_version;
	$RFS_MODULE[$name]["author_website"]=$sub_version;
	// Store the main database at rfscms.org only
	if($RFS_SITE_URL=="https://rfscms.org") {
		// TODO: CHECK VERSION
		$r=lib_mysql_query("select * from addon_database where `name`='$name'");
		if($r) {
			$addon=$r->fetch_object();
			if(!empty($addon->name)) {
				if($addon->version<$version) { }
			}
		}
		else {
			if(!empty($name)) {
				$q="
				insert into `addon_database`
						(`name`,`core`,`version`,`sub_version`,`release`,`description`,`requirements`,`cost`,`license`,`dependencies`,`author`,`author_email`,`author_website`,`images`,`file_url`,`git_repository`)
				VALUES  ('$name','$core','$version','$sub_version','$release','$description','$requirements','$cost','$license','$dependencies','$author','$author_email','$author_website','$images','$file_url','$git_repository') ";			
				lib_log_add_entry($q);
				lib_mysql_query($q);
			}
		}
	}
}

function lib_modules_get_url($z) {
    global $RFS_SITE_PATH,$RFS_SITE_URL;
    global $RFS_MODULE;
    // include("$RFS_SITE_PATH/include/lib.domain.php");
	if(!empty($z)) return $RFS_MODULE[$z]["url"];
	$x=lib_domain_canonical_url();
	$y=explode("?",$x); $x=$y[0];
    $x=explode("/",$x);
	for($i=0;$i<count($x);$i++) {
		if(strstr($x[$i],"modules")) {
			$addon=$x[$i+1];
		}
	}
    if(empty($addon)) return;
    if(empty($RFS_MODULE[$addon])) $RFS_MODULE[$addon]=array();
    if(empty($RFS_MODULE[$addon]["url"])) $RFS_MODULE[$addon]["url"]=""; 
    $loc=$RFS_MODULE[$addon]["url"];
    return $loc;
}

function lib_modules_get_base_url_from_file($f) {
	global $RFS_SITE_PATH;
	global $RFS_SITE_URL;
	$f=str_replace($RFS_SITE_PATH,"",$f);
	$x=explode("/",$f);
	$outurl=$RFS_SITE_URL;
	
	for($i=0;$i<count($x);$i++) {
		$outurl.=$x[$i];
		$outurl.="/";
		if(strstr($x[$i],"modules")) {
			$addon=$x[$i+1];
			$outurl.=$x[$i+1];
			break;
		}
	}
    $RFS_MODULE[$addon]["base_url"]=$outurl;
    return $RFS_MODULE[$addon]["base_url"];
}

function lib_modules_get_base_url($z) {
    global $RFS_SITE_PATH,$RFS_SITE_URL;
    global $RFS_MODULE;
	if(!empty($z)) return $RFS_MODULE[$z]["base_url"];
	$x=lib_domain_canonical_url();
    $x=explode("/",$x);
	for($i=0;$i<count($x);$i++) {
		if(strstr($x[$i],"modules")) {
			$addon=$x[$i+1];			
		}
	}
    if(empty($addon)) return;
    if(empty($RFS_MODULE[$addon]["base_url"])) $RFS_MODULE[$addon]["base_url"]="";
    $loc=$RFS_MODULE[$addon]["base_url"];
    return $loc;
}

function lib_modules_discover() {
    eval(lib_rfs_get_globals());
	$dr="$RFS_SITE_PATH/modules";
	$modules=array();
    $d=opendir($dr) or die("MODULE PATH ERROR");	
	while(false!==($entry = readdir($d))) {
        if( ($entry == '.') || ($entry == '..') ) { }
        else {
            if(is_dir($dr."/".$entry)) {
                $core=false;
                if(stristr($entry,"core_")) $core=true;
                $entry2=str_replace("core_","",$entry);
                $module="$dr/$entry/module.$entry2.php";
                $loc="$dr/$entry/$entry2.php";
				include($module);
				global $RFS_ADDON_NAME;
				global $RFS_ADDON_VERSION;
				global $RFS_ADDON_SUB_VERSION;
				global $RFS_ADDON_RELEASE;
				global $RFS_ADDON_DESCRIPTION;
				global $RFS_ADDON_REQUIREMENTS;
				global $RFS_ADDON_COST;
				global $RFS_ADDON_LICENSE;
				global $RFS_ADDON_DEPENDENCIES;
				global $RFS_ADDON_AUTHOR;
				global $RFS_ADDON_AUTHOR_EMAIL;
				global $RFS_ADDON_AUTHOR_WEBSITE;
				global $RFS_ADDON_IMAGES;
				global $RFS_ADDON_FILE_URL;
				global $RFS_ADDON_GIT_REPOSITORY;
				if(!empty($RFS_ADDON_NAME)) {
					lib_modules_register(
						$RFS_ADDON_NAME,$core,$loc,$RFS_ADDON_VERSION,$RFS_ADDON_SUB_VERSION,$RFS_ADDON_RELEASE,
						$RFS_ADDON_DESCRIPTION,$RFS_ADDON_REQUIREMENTS,$RFS_ADDON_COST,$RFS_ADDON_LICENSE,
						$RFS_ADDON_DEPENDENCIES,$RFS_ADDON_AUTHOR,$RFS_ADDON_AUTHOR_EMAIL,$RFS_ADDON_AUTHOR_WEBSITE,
						$RFS_ADDON_IMAGES,$RFS_ADDON_FILE_URL,$RFS_ADDON_GIT_REPOSITORY );
						// echo "what lib_modules_register(stuff) -> $RFS_ADDON_NAME<br> ";
				}
			}
		}
    }
	closedir($d);
}

	

function lib_modules_draw($location) {
	if(stristr(lib_domain_canonical_url(),"admin/adm.php")) return;
	$r=lib_mysql_query("select * from arrangement where location='$location' order by sequence");
	if($r) {
		for($i=0;$i<$r->num_rows;$i++) {
			$ar=$r->fetch_object();
			if(function_exists("m_panel_$ar->panel")) {
				$x=$ar->num;
				if($ar->type=="static") $x=$ar->id;
				eval(("m_panel_$ar->panel($x);"));
			}
		}
	}
}

