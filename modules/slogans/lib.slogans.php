<?
include_once("include/lib.all.php");

sc_menus_register("Slogans","$RFS_SITE_URL/modules/slogans/slogan_admin.php");
sc_access_method_add("slogan", "edit");

?>