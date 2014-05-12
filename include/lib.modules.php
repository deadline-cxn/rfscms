<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
function lib_modules_get_name($module) {
	global $RFS_MODULE;
	foreach($RFS_MODULE as $k => $v) {
		echo "$k <br>";
	}
}
function lib_modules_get_properties($module) {
		global $RFS_MODULE;
		return $RFS_MODULE[$x];
}
function lib_modules_get_property($x,$property) {
	global $RFS_MODULE;
	return $RFS_MODULE[$x][$property];
}

function lib_modules_register_property($x,$property,$property_value) {
	global $RFS_MODULE;
	$RFS_MODULE[$x][$property]=$property_value;
}

function lib_modules_register($name,$core,$loc,$version,$sub_version,$release,$description,$requirements,$cost,$license,$dependencies,
$author,$author_email,$author_website,$images,$file_url,$git_repository) {
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
	
	lib_modules_register_property($name,"version",$version);
	lib_modules_register_property($name,"sub_version",$sub_version);
	lib_modules_register_property($name,"file",$loc);
	lib_modules_register_property($name,"author",$author);
	lib_modules_register_property($name,"author_email",$sub_version);
	lib_modules_register_property($name,"author_website",$sub_version);
	
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
function lib_modules_get_url($z) {
    global $RFS_SITE_PATH,$RFS_SITE_URL;
    global $RFS_MODULE;
	if(!empty($z)) return $RFS_MODULE[$z]["url"];
	$x=lib_domain_canonical_url();
	$y=explode("?",$x); $x=$y[0];
    $x=explode("/",$x);
	for($i=0;$i<count($x);$i++) {
		if(strstr($x[$i],"modules")) {
			$addon=$x[$i+1];
		}
	}
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
    $loc=$RFS_MODULE[$addon]["base_url"];
    return $loc;
}

function lib_modules_properties($module) {    
    eval(lib_rfs_get_globals());
	
    lib_forms_info("REGISTERED MODULE [$module]<br>","white","green");
	echo "PROPERTIES:<br>";
	echo "<table border=0>";
    foreach($RFS_MODULE[$module] as $k => $v) {
        if(!empty($v))
            echo "<tr><td>[$k]</td><td>=</td><td>[$v]</td></tr>";
    }
    echo "<tr><td></td><td></td><td></td></tr>";
	echo "</table>";
	
}
function lib_modules_array() {
    eval(lib_rfs_get_globals());
	$dr="$RFS_SITE_PATH/modules";
	$modules=array();
    $d=opendir($dr) or die("MODULE PATH ERROR lib.modules.php - lib_modules_array() -> [$dr]");
	while(false!==($entry = readdir($d))) {
        if( ($entry == '.') || ($entry == '..') ) { }
        else {
            if(is_dir($dr."/".$entry)) {
                $core=false;
                if(stristr($entry,"core_")) $core=true;
                $entry2=str_replace("core_","",$entry);
                $module="$dr/$entry/module.$entry2.php";
                $loc="$dr/$entry/$entry2.php";		
				/*
				global $RFS_ADDON_NAME="";
				global $RFS_ADDON_VERSION="";
				global $RFS_ADDON_SUB_VERSION="";
				global $RFS_ADDON_RELEASE="";
				global $RFS_ADDON_DESCRIPTION="";
				global $RFS_ADDON_REQUIREMENTS="";
				global $RFS_ADDON_COST="";
				global $RFS_ADDON_LICENSE="";
				global $RFS_ADDON_DEPENDENCIES="";
				global $RFS_ADDON_AUTHOR="";
				global $RFS_ADDON_AUTHOR_EMAIL="";
				global $RFS_ADDON_AUTHOR_WEBSITE="";
				global $RFS_ADDON_IMAGES="";
				global $RFS_ADDON_FILE_URL="";
				global $RFS_ADDON_GIT_REPOSITORY="";
				 */
								
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
				
				lib_modules_register(
						$RFS_ADDON_NAME,
						$core,
						$loc,
						$RFS_ADDON_VERSION,
						$RFS_ADDON_SUB_VERSION,
						$RFS_ADDON_RELEASE,
						$RFS_ADDON_DESCRIPTION,
						$RFS_ADDON_REQUIREMENTS,
						$RFS_ADDON_COST,
						$RFS_ADDON_LICENSE,
						$RFS_ADDON_DEPENDENCIES,
						$RFS_ADDON_AUTHOR,
						$RFS_ADDON_AUTHOR_EMAIL,
						$RFS_ADDON_AUTHOR_WEBSITE,
						$RFS_ADDON_IMAGES,
						$RFS_ADDON_FILE_URL,
						$RFS_ADDON_GIT_REPOSITORY
						);					
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

////////////////////////////////////////////////////////////////////////////////////////////////////
// Module Admin Panel Plugins
function adm_action_modules() {
    eval(lib_rfs_get_globals());
	echo "<h1>Module Management</h1><hr>";
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=f_module_menu","Module Registered Menu Items");
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=f_module_store","Module Store");
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=f_module_store_update_force","Manual Database Update");
	adm_action_f_module_store_update(0);	
	echo "<hr>";
	adm_action_f_show_installed_modules();
	include( "footer.php" );
	exit();
}

function adm_action_f_module_menu() {
    eval(lib_rfs_get_globals());
	echo "<h1>Menu Options registered by Modules</h1>";
	echo "<hr>";
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=modules","Module Management");
	echo "<hr>";
	echo "<div class='forum_box'>";
	global $RFS_MENU_OPTION;
	echo "<table border=0>";
	echo "<tr><th></th><th></th><th>Link Short Name</th><th>Link URL</th></tr>";
	asort($RFS_MENU_OPTION);
	foreach($RFS_MENU_OPTION as $k => $v) {
		echo "<tr>";
		if(lib_access_check("admin","access")) {
			echo "<td>";
			lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=f_menu_top_add_link&lname=$k&lurl=$v","Add to Top Menu");
			echo "</td>";
			echo "<td>";
			lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=f_menu_admin_add_link&lname=$k&lurl=$v","Add to Admin Menu");
			echo "</td>";
		}
		
		echo "<td>";		
		echo wikitext("$k</td><td>[$v]</td></tr>");
		
	}
	echo "</table>";
	echo "</div>";
	include( "footer.php" );
	exit();
}
function adm_action_f_show_installed_modules() {
    eval(lib_rfs_get_globals());
	echo "<h1>Modules Installed</h1><hr>";
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=modules","Module Management");
	echo "<hr>";	
	
	echo "<table border=0>";
	
	global $RFS_MODULE;
	asort($RFS_MODULE);
	foreach($RFS_MODULE as $k => $v) {
	   
	   	echo "<tr>";        
        
        foreach($v as $hh => $jj) {
        echo "<td>";
       
        if($hh=="active" and $jj==true)	
            echo "<font style='color:white; background-color:green;'>ACTIVE</font> $v3 ";
        
        echo "</td>";       
		
        echo "<td>";
        if($hh=="core" and $jj==true)  
             echo "<font style='color:white; background-color:blue;'>CORE</font> ";
        echo "</td>";
        }
		
		echo "<td>";
        lib_modules_properties($k);
		echo "</td>";
		echo "</tr>";
		
	}
	echo "</table>";
	
    
	include( "footer.php" );
	exit();
}

?>
