<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
$this_dir=getcwd();
include_once("session.php");
include_once("lib.div.php");
include_once("lib.log.php");
include_once("version.php");
include_once("lib.debug.php");
include_once("lib.mysql.php");
include_once("lib.sitevars.php");
if(empty($RFS_SITE_NAME)) { include("install/install.php"); exit(); }
include_once("lib.rfs.php");
include_once("lib.social_buttons.php");
include_once("lib.domain.php");
include_once("lib.menus.php");
include_once("themes/_templates/theme_templates.php");
include_once("lib.buttons.php");
include_once("lib.file.php");
include_once("lib.images.php");
include_once("lib.modules.php");
include_once("lib.network.php");
/////////////////////////////////////////////////////////////////////////////////////////
$data=sc_getuserdata($_SESSION['valid_user']);
?>
