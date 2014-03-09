<?
include_once("include/lib.all.php");

lib_menus_register("WAB","$RFS_SITE_URL/modules/wab/wab.php");

function adm_action_lib_wab_wab() { eval(lib_rfs_get_globals());
    lib_domain_gotopage("$RFS_SITE_URL/modules/wab/wab.php?runapp=1");
} 

?>
