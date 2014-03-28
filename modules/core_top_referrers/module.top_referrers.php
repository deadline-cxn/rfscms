<?
include_once("include/lib.all.php");

///////////////////////////////////////////////////////////////
// MODULE TOP REFERRERS
function m_panel_top_referrers($x) { eval(lib_rfs_get_globals());

   $result=lib_mysql_query("select * from link_bin where hidden != '1' and `referral`='yes' order by `referrals` desc limit $x");
    $numlinks=mysql_num_rows($result);
    echo "<h2>Top $x Referrers</h2>";
	for($i=0;$i<$numlinks;$i++) {

		$link=mysql_fetch_object($result);
		$url=$link->link;
		$url=str_replace(":","_rfs_colon_",$url);
       echo "<a class=\"a_cat\" href=\"$site_url/link_out.php?link=$url\" \n";
       echo " target=\"_blank\" title=\"$link->sname (in[$link->referrals] out[$link->clicks])\"\n";
        echo ">".lib_string_truncate($link->sname,24)."</a> ";        
        echo " <font class=rfs_black>[$link->referrals] <br>";
    }
}

?>
