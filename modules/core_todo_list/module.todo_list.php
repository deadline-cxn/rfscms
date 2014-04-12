<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
// TODO CORE MODULE
/////////////////////////////////////////////////////////////////////////////////////////
include_once("include/lib.all.php");

$RFS_ADDON_NAME="todo_list";
$RFS_ADDON_VERSION="1.0.0";
$RFS_ADDON_SUB_VERSION="0";
$RFS_ADDON_RELEASE="";
$RFS_ADDON_DESCRIPTION="RFSCMS Todo List";
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

lib_menus_register("TODO","$RFS_SITE_URL/modules/core_todo_list/todo_list.php");
/////////////////////////////////////////////////////////////////////////////////////////
// PANELS
function m_panel_todo_list($x) {
	eval(lib_rfs_get_globals());
	lib_div("TODO MODULE SECTION");
	 echo "<h2>TODO</h2>";
	$result=lib_mysql_query("select * from todo_list limit 0,$x");
	$num=mysql_num_rows($result);
	echo "<table border=0 cellspacing=0 cellpadding=0 >";
	for($i=0;$i<$num;$i++){
		$task=mysql_fetch_object($result);
		$link="$RFS_SITE_URL/modules/core_todo_list/todo_list.php?action=view_todo_list&id=$task->id";
		echo "<tr><td>";
		echo "<a href=\"$link\">$task->name</a>";
		echo"</td></tr>";
	}
	echo "</table>";
}

?>
