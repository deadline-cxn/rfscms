<?php
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
// SLOGANS CORE MODULE
/////////////////////////////////////////////////////////////////////////////////////////
include_once("include/lib.all.php");

$RFS_ADDON_NAME="slogans";
$RFS_ADDON_VERSION="1.0.0";
$RFS_ADDON_SUB_VERSION="0";
$RFS_ADDON_RELEASE="";
$RFS_ADDON_DESCRIPTION="RFSCMS Slogans";
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

lib_menus_register("Slogans","$RFS_SITE_URL/modules/core_slogans/slogan_admin.php");
lib_access_add_method("slogan", "edit");

?>