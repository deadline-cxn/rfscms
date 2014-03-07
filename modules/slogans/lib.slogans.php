<?
include_once("include/lib.all.php");

lib_menus_register("Slogans","$RFS_SITE_URL/modules/slogans/slogan_admin.php");
lib_access_add_method("slogan", "edit");

?>