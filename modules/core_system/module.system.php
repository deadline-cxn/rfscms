<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
// SYSTEM CORE MODULE
/////////////////////////////////////////////////////////////////////////////////////////
include_once("include/lib.all.php");

$RFS_ADDON_NAME="system";
$RFS_ADDON_VERSION="1.0.0";
$RFS_ADDON_SUB_VERSION="0";
$RFS_ADDON_RELEASE="";
$RFS_ADDON_DESCRIPTION="RFSCMS System";
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

lib_access_add_method("static_html","edit");
lib_mysql_add("static_html","html","text","not null");
lib_mysql_add("static_html","owner","text","not null");
/////////////////////////////////////////////////////////////////////////////////////////
// PANELS
function m_panel_system_seperator($x) { echo "<hr>"; }
function m_panel_system_linefeed($x) { for($i=0;$i<$x;$i++) echo "<br>"; }
function m_panel_system_custom($x)   { echo $x; }
function m_panel_system_static_html($arx) {
	eval(lib_rfs_get_globals());
	$arr=lib_mysql_query("select * from `arrangement` where id='$arx'");
	$ar=mysql_fetch_object($arr);	
	$shr=lib_mysql_query("select * from `static_html` where `name`='$ar->page'");
	$shtml=mysql_fetch_object($shr);
	// echo "<h1>$shtml->name</h1>";
	// echo $shtml->html;
	echo lib_rfs_echo(nl2br($shtml->html));		
		// if ( ($shtml->owner==$data->name) ||			 (lib_access_check("static_html","edit")) ||			  (lib_access_check("admin","access")) ) {				echo "<br>";		}
		// se 	adm_function_module_system_static_edit();
}
?>
