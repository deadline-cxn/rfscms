<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
// SYSTEM CORE MODULE
/////////////////////////////////////////////////////////////////////////////////////////
include_once("include/lib.all.php");
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
