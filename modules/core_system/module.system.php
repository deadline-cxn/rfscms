<?
include_once("include/lib.all.php");
function module_system_linefeed($x) { for($i=0;$i<$x;$i++) echo "<br>"; }
function module_system_custom($x)   { echo $x; }
function module_system_static_html($x) { echo "TODO: STATIC HTML<BR>"; }
?>