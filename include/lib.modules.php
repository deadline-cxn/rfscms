<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
if(function_exists("lib_div")) lib_div(__FILE__);
function lib_modules_get_property($x,$property) {
	global $RFS_MODULE;
	return $RFS_MODULE[$x][$property];
}

function lib_modules_register_property($x,$property,$property_value) {
	global $RFS_MODULE;
	$RFS_MODULE[$x][$property]=$property_value;
}

function lib_modules_register($x,$core,$loc) {
    global $RFS_SITE_PATH,$RFS_SITE_URL;
    global $RFS_MODULE;
    $RFS_MODULE[$x]=array();
    $RFS_MODULE[$x]["core"]=$core;
    $url=str_replace("$RFS_SITE_PATH","$RFS_SITE_URL",$loc);    
	$RFS_MODULE[$x]["url"]=$url;
	// lib_menus_register($x,$url);
	$url=str_replace("/$x.php","",$url);
	$RFS_MODULE[$x]["base_url"]=$url;
	$loc=str_replace("/$x.php","",$loc);
	$RFS_MODULE[$x]["loc"]=$loc;
}
function lib_modules_get_url($z) {
    global $RFS_SITE_PATH,$RFS_SITE_URL;
    global $RFS_MODULE;	
	if(!empty($z)) return $RFS_MODULE[$z]["url"];
	$x=lib_domain_canonical_url();
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
	echo "<div class=forum_box>";
    lib_forms_info("REGISTERED MODULE [$module]<br>","white","green");
	echo "PROPERTIES:<br>";
	echo "<table border=0>";
    foreach($RFS_MODULE[$module] as $k => $v) {
            echo "<tr><td>[$k]</td><td>=</td><td>[$v]</td></tr>";
    }
	echo "</table>";
	echo "</div>";
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
                if(stristr($entry,"core")) $core=true;
                $entry2=str_replace("core_","",$entry);
                $module="$dr/$entry/module.$entry2.php";
                $loc="$dr/$entry/$entry2.php";
                lib_modules_register($entry2,$core,$loc);
                lib_div($module);
                include($module);
            }
        }
    }
	closedir($d);
}
function lib_modules_draw($location) {
	if(stristr(lib_domain_canonical_url(),"admin/adm.php")) return;
	$r=lib_mysql_query("select * from arrangement where location='$location' order by sequence");
	if($r) {
		$n=mysql_num_rows($r);
		for($i=0;$i<$n;$i++) {
			$ar=mysql_fetch_object($r);
			if(function_exists("module_$ar->mini")) {
				eval("module_$ar->mini($ar->num);");
				echo "<hr>";
			}
		}
	}
}

////////////////////////////////////////////////////////////////////////////////////////////////////
// Module Admin Panel Plugins
function adm_action_modules() {
    eval(lib_rfs_get_globals());
	echo "<h1>Module Management</h1><hr>";
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=f_show_installed_modules","Installed modules");
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=f_module_menu","Module Registered Menu Items");
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=f_module_store","Module Store");
	echo "<hr>";
	///////////////////////////////////////////////
	// get list of modules from sethcoder.com
	// 
	// download the database once every 24 hours to addon_database table
	// add rfs_site_addon_database_time to check for time
	if(empty($RFS_SITE_ADDON_DATABASE_CHECK_INTERVAL))
		lib_sitevars_assign("RFS_SITE_ADDON_DATABASE_CHECK_INTERVAL","86400");
	$time=time();
	$x=$time-intval($RFS_SITE_ADDON_DATABASE_TIME);
	// echo "[$time] [$RFS_SITE_ADDON_DATABASE_TIME] [$x] [$RFS_SITE_ADDON_DATABASE_CHECK_INTERVAL]";
	if($x>$RFS_SITE_ADDON_DATABASE_CHECK_INTERVAL) {
		lib_sitevars_assign("RFS_SITE_ADDON_DATABASE_TIME",$time);
		$addon_database=file_get_contents("http://www.sethcoder.com/files/addon_database.sql");
		lib_mysql_query($addon_database);
		lib_forms_info("ADDON DATABASE UPDATED...","white","green");
	}
	include( "footer.php" );
	exit();
}

function adm_action_f_module_store() {
    eval(lib_rfs_get_globals());
	echo "<h1>Module Store</h1>";
	echo "<hr>";
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=modules","Module Management");
	echo "<hr>";
	//lib_mysql_data_add("addon_database","name","TEST!!!".time(),"");	
	// id name datetime_added	datetime_updated	version	sub_version	release	description	requirements	cost	license	dependencies	author	author_email	author_website	rating	images		
	echo "MODULES... <br>";
	$r=lib_mysql_query("select * from `addon_database`");
	while($module=mysql_fetch_object($r)) {
		echo "Name: $module->name <br>";
	}
	
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
	
	echo "<div class='forum_box'>";
	echo "<table border=0>";
	
	global $RFS_MODULE;
	asort($RFS_MODULE);
	foreach($RFS_MODULE as $k => $v) {
	   
		echo "<tr>";
		echo "<td>";
		echo "<font style='color:white; background-color:green;'>ACTIVE</font> ";
		echo "</td>";
		echo "<td>";
        foreach($v as $k2 => $v2) {
            if($k2=="core" and $v2==true)
                echo "<font style='color:white; background-color:blue;'>CORE</font> ";
        }
		echo "</td>";		
		echo "<td>";      
        
        lib_modules_properties($k);
		echo "</td>";
		echo "</tr>";
		
	}
	echo "</table>";
	echo "</div>";
	echo "<br>";
	include( "footer.php" );
	exit();
}

?>
