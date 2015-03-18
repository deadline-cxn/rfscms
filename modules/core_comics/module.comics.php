<?php
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
// COMICS CORE MODULE
/////////////////////////////////////////////////////////////////////////////////////////
include_once("include/lib.all.php");

$RFS_ADDON_NAME="comics";
$RFS_ADDON_VERSION="1.0.0";
$RFS_ADDON_SUB_VERSION="0";
$RFS_ADDON_RELEASE="";
$RFS_ADDON_DESCRIPTION="RFSCMS Comics";
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

lib_menus_register("Comics", "$RFS_SITE_URL/modules/core_comics/comics.php");
lib_access_add_method("comics", "admin");
lib_access_add_method("comics", "create");
lib_access_add_method("comics", "delete");
lib_access_add_method("comics", "deleteothers");
lib_access_add_method("comics", "edit");
lib_access_add_method("comics", "editothers");
lib_access_add_method("comics", "publish");
lib_access_add_method("comics", "unpublish");
///////////////////////////////////////////////////////////////////
// PANELS
function m_panel_comics($x) {
    eval(lib_rfs_get_globals());
    $RFS_ADDON_URL = lib_modules_get_url("comics");
    lib_div("COMIC MODULE SECTION");
    echo "<h2>Last $x Comics</h2>";
    $res = lib_mysql_query("select * from comics where `published`='yes' order by time desc limit 1");
    $numc = $res->num_rows;
    for ($i = 0; $i < $numc; $i++) {
        echo "<div class=\"memeboxmini\" >";
        $comic = $res->fetch_object();
		$rc2=lib_mysql_query("select * from `comics_pages` where `parent`='$comic->id' order by `page` asc limit 1");
        $page = $rc2->fetch_object();
        rfs_comics_mini_preview_link($page->pid, "$RFS_ADDON_URL?action=viewcomic&id=$comic->id");
        echo "<a href=\"$RFS_ADDON_URL?action=viewcomic&id=$comic->id\">$comic->title vol. $comic->volume issue $comic->issue</a>";
        echo "</div>";
    }
    echo "(<a href=\"$RFS_ADDON_URL\" class=a_cat>More...</a>)";
}
///////////////////////////////////////////////////////////////////
// FUNCTIONS
function rfs_comics_mini_preview_link($pid, $link) {
    eval(lib_rfs_get_globals());
    $RFS_ADDON_URL = lib_modules_get_url("comics");
	$res=lib_mysql_query("select * from `comics_pages` where `pid`='$pid'");
    $page= $res->fetch_array();
    $tid = $page['template'];
	$res=lib_mysql_query("select * from `comics_page_templates` where `id`='$tid'");
    $template = $res->fetch_array();
    echo "<table border=0><tr><td> ";
    for ($i = 0; $i < $template['panels']; $i++) {
        $var = "panel" . ($i + 1) . "_x";
        $x = $template[$var];
        if ($x)
            $x = $x / 2;
        $var = "panel" . ($i + 1) . "_y";
        $y = $template[$var];
        if ($y)
            $y = $y / 2;
        $var = "panel" . ($i + 1) . "_l";
        $l = $template[$var];
        $var = "panel" . ($i + 1);
        $url = $page[$var];
        if (empty($url))
            $url = "$RFS_SITE_URL/images/comics_page_bkg.gif";
        echo "<a href=\"$link\">";
        echo lib_images_thumb($url, $x, $y, 0);
        echo "</a> ";
        if ($l == "yes")
            echo "<br> ";
    }
    echo "</td></tr></table>";
}
function rfs_comics_page_mini_preview($pid) {
    eval(lib_rfs_get_globals());
    $RFS_ADDON_URL = lib_modules_get_url("comics");
	$res=lib_mysql_query("select * from `comics_pages` where `pid`='$pid'");
    $page= $res->fetch_array();
    $tid = $page['template'];
	
    $template= $res->fetch_array();
    echo "<table border=0><tr><td> ";
    for ($i = 0; $i < $template['panels']; $i++) {
        $var = "panel" . ($i + 1) . "_x";
        $x = $template[$var];
        if ($x)
            $x = $x / 6;
        $var = "panel" . ($i + 1) . "_y";
        $y = $template[$var];
        if ($y)
            $y = $y / 6;
        $var = "panel" . ($i + 1) . "_l";
        $l = $template[$var];
        $var = "panel" . ($i + 1);
        $url = $page[$var];
        if (empty($url))
            $url = "$RFS_SITE_URL/images/comics_page_bkg.gif";
        echo lib_images_thumb($url, $x, $y, 0);
        if ($l == "yes")
            echo "<br> ";
    }
    echo "</td></tr></table>";
}
function page_full_view($pid) {
    eval(lib_rfs_get_globals());
    $RFS_ADDON_URL  = lib_modules_get_url("comics");
	$res=lib_mysql_query("select * from `comics_pages` where `pid`='$pid'");
    $page           = $res->fetch_array();
	$res=lib_mysql_query("select * from `comics_page_templates` where `id`='".$page['template']."'");
    $template       = $res->fetch_array();
    echo "<table border=0><tr><td> ";
    $pfvzz=$template['panels'];
    for($pfvi = 0; $pfvi < $pfvzz; $pfvi++) {
        $pfvx = $template["panel".($pfvi+1)."_x"];        
        $pfvy = $template["panel".($pfvi+1)."_y"];
        $pfvl = $template["panel".($pfvi+1)."_l"];
        $pfvurl = $page["panel".($pfvi+1)];
        if(empty($pfvurl)) $pfvurl = "$RFS_SITE_URL/images/comics_page_bkg.gif";
        echo "<img src=\"$pfvurl\" width=$pfvx height=$pfvy> ";
        if ($pfvl == "yes")
            echo "<br> ";
    }
    echo "</td></tr></table>";
}

?>