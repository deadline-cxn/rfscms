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

lib_menus_register("Files","$RFS_SITE_URL/modules/core_files/files.php");

//lib_mysql_data_add("addon_database","name","TEST!!!".time(),"");	
// id name datetime_added	datetime_updated	version	sub_version	release	description	requirements	cost	license	dependencies	author	author_email	author_website	rating	images		

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

?>