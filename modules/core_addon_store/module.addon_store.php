<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
// ADDON STORE CORE MODULE
/////////////////////////////////////////////////////////////////////////////////////////
include_once("include/lib.all.php");

$RFS_ADDON_NAME="addon_store";
$RFS_ADDON_VERSION="1.0.0";
$RFS_ADDON_SUB_VERSION="0";
$RFS_ADDON_RELEASE="";
$RFS_ADDON_DESCRIPTION="RFSCMS Addon Store";
$RFS_ADDON_REQUIREMENTS="";
$RFS_ADDON_COST="";
$RFS_ADDON_LICENSE="";
$RFS_ADDON_DEPENDENCIES="";
$RFS_ADDON_AUTHOR="Seth T. Parson";
$RFS_ADDON_AUTHOR_EMAIL="seth.parson@rfscms.org";
$RFS_ADDON_AUTHOR_WEBSITE="http://rfscms.org/";
$RFS_ADDON_IMAGES="";
$RFS_ADDON_FILE_URL="";
$RFS_ADDON_GIT_REPOSITORY="";
$RFS_ADDON_URL=lib_modules_get_base_url_from_file(__FILE__);

lib_menus_register("Addon Store","$RFS_SITE_URL/modules/core_addon_store/addon_store.php");

// lib_mysql_data_add("addon_database","name","TEST!!!".time(),"");	
// id name datetime_added datetime_updated version sub_version release description requirements
// cost license dependencies author author_email author_website rating images		

function adm_action_f_module_store_update_force() {
	adm_action_f_module_store_update(true);
	adm_action_f_module_store();
}
function adm_action_f_module_store_update($force) {
	eval(lib_rfs_get_globals());
	///////////////////////////////////////////////
	// get list of modules from rfscms.org
	// download the database once every 24 hours to addon_database table
	// add rfs_site_addon_database_time to check for time
	if(empty($RFS_SITE_ADDON_DATABASE_CHECK_INTERVAL))
		lib_sitevars_assign("RFS_SITE_ADDON_DATABASE_CHECK_INTERVAL","86400");
	$time=time();
	$x=$time-intval($RFS_SITE_ADDON_DATABASE_TIME);
	if( ($x>$RFS_SITE_ADDON_DATABASE_CHECK_INTERVAL) ||
		($force)) {
		lib_sitevars_assign("RFS_SITE_ADDON_DATABASE_TIME",$time);
		$addon_database=file_get_contents("http://rfscms.org/files/addon_database.sql");
		 mkdir("$RFS_SITE_PATH/tmp");
		$filename="$RFS_SITE_PATH/tmp/addon_database.sql";
		file_put_contents($filename,$addon_database);
		lib_mysql_import_sql($filename);
		lib_forms_info("ADDON DATABASE UPDATED...","white","green");
	}
}
function adm_action_f_module_store() {
    eval(lib_rfs_get_globals());
	echo "<h1>Module Store</h1>";
	echo "<hr>";
	lib_buttons_make_button("$RFS_SITE_URL/admin/adm.php?action=modules","Module Management");
	echo "<hr>";
	echo "MODULES... <br>";
	$r=lib_mysql_query("select * from `addon_database`");
	while($module=$r->fetch_object()) {
		echo "Name: $module->name <br>";
		echo "      $module->git_repository<br>";
	}
	
	include( "footer.php" );
	exit();
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