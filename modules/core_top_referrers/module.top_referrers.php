<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
// TOP REFERRERS CORE MODULE
/////////////////////////////////////////////////////////////////////////////////////////
include_once("include/lib.all.php");

$RFS_ADDON_NAME="top_referrers";
$RFS_ADDON_VERSION="1.0.0";
$RFS_ADDON_SUB_VERSION="0";
$RFS_ADDON_RELEASE="";
$RFS_ADDON_DESCRIPTION="RFSCMS Top Referrers";
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

///////////////////////////////////////////////////////////////
// PANELS
function m_panel_top_referrers($x) {
	eval(lib_rfs_get_globals());
	$result=lib_mysql_query("select * from link_bin where hidden != '1' and `referral`='yes' order by `referrals` desc limit $x");
	echo "<h2>Top $x Referrers</h2>";
	while($link=$result->fetch_object()){
		$url=$link->link;
		$url=str_replace(":","_rfs_colon_",$url);
       echo "<a class=\"a_cat\" href=\"$site_url/link_out.php?link=$url\" \n";
       echo " target=\"_blank\" title=\"$link->sname (in[$link->referrals] out[$link->clicks])\"\n";
		echo ">".lib_string_truncate($link->sname,24)."</a> ";        
		echo " <font class=rfs_black>[$link->referrals] <br>";
    }
}
?>