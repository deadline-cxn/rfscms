<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
// TOP REFERRERS CORE MODULE
/////////////////////////////////////////////////////////////////////////////////////////
include_once("include/lib.all.php");
///////////////////////////////////////////////////////////////
// PANELS
function m_panel_top_referrers($x) {
	eval(lib_rfs_get_globals());
	$result=lib_mysql_query("select * from link_bin where hidden != '1' and `referral`='yes' order by `referrals` desc limit $x");
	echo "<h2>Top $x Referrers</h2>";
	while($link=mysql_fetch_object($result)){
		$url=$link->link;
		$url=str_replace(":","_rfs_colon_",$url);
       echo "<a class=\"a_cat\" href=\"$site_url/link_out.php?link=$url\" \n";
       echo " target=\"_blank\" title=\"$link->sname (in[$link->referrals] out[$link->clicks])\"\n";
		echo ">".lib_string_truncate($link->sname,24)."</a> ";        
		echo " <font class=rfs_black>[$link->referrals] <br>";
    }
}
?>