<?
include_once("include/lib.all.php");

lib_access_add_method("static_html","edit");
lib_mysql_add("static_html","html","text","not null");
lib_mysql_add("static_html","owner","text","not null");

function module_system_linefeed($x) { for($i=0;$i<$x;$i++) echo "<br>"; }
function module_system_custom($x)   { echo $x; }

function module_system_static_html($x) {
	eval(lib_rfs_get_globals());
	$r=lib_mysql_query("select * from `static_html` where id='$x'");
	if($r) {
		$shtml=mysql_fetch_object($r);	
		echo lib_rfs_echo(nl2br($shtml->html));
		if ( ($shtml->owner==$data->name) ||
			 (lib_access_check("static_html","edit")) ||
			  (lib_access_check("admin","access")) ) {
				echo "<br>";
		}
	}
	else 
		adm_function_module_system_static_edit();
}
/*
function adm_action_f_module_system_static_edit() {
	eval(lib_rfs_get_globals());
	$RFS_ADDON_URL=lib_modules_get_url("system");
	$r=lib_mysql_query("select * from `static_html` where id='$id'");
	$shtml=mysql_fetch_object($r);
	echo "<h1> Editing custom static HTML</h1>";
	echo "<form action=\"$RFS_SITE_URL/admin/adm.php\" method=\"POST\" enctype=\"application/x-www-form-URLencoded\">";
    echo "<input type=\"hidden\" name=\"action\" value=\"f_module_system_static_edit_go\">";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
    echo "<textarea rows=20  cols=120 name=\"shtml\">";
    $shtml->html=str_replace("</textarea>","&lt;/textarea>",$shtml->html);
    echo stripslashes($shtml->html);
    echo "</textarea><br>";
    echo "<input type=\"submit\" name=\"submit\" value=\"submit query\">";
    echo "</form>";
	finishadminpage();
}
function adm_action_f_module_system_static_edit_go() {
	eval(lib_rfs_get_globals());
	$RFS_ADDON_URL=lib_modules_get_url("system");
	$r=lib_mysql_query("delete from static_html where id='$id'");
	$nshtml=addslashes($shtml);
	$q="insert into `static_html` (`id`,`html`,`owner`) values ('$id','$nshtml','$data->name') ";
	lib_mysql_query($q);
	echo lib_rfs_echo(nl2br($shtml));
	finishadminpage();
}
*/

?>
