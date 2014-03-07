<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
include_once("lib.sitevars.php");
session_name(str_replace(" ","_",$RFS_SITE_SESSION_ID));
session_cache_expire(99999);
session_start();

include_once("lib.div.php");
include_once("lib.users.php");
lib_div(__FILE__);
if(isset($_SESSION["logged_in"])) $logged_in=$_SESSION["logged_in"];
if(isset($_SESSION["valid_user"])) $RFS_SITE_SESSION_USER = sc_getusername($_SESSION["valid_user"]);
if(isset($_REQUEST['admin_show_top'])) $_SESSION['admin_show_top']=$_REQUEST['admin_show_top'];
?>