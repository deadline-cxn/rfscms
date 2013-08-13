<?
include_once("include/lib.all.php");

sc_menus_register("WAB","$RFS_SITE_URL/modules/wab/wab.php");

function adm_action_lib_wab_wab() { eval(scg());
    sc_gotopage("$RFS_SITE_URL/modules/wab/wab.php?runapp=1");
} 

?>
