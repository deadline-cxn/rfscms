<?
include_once("include/lib.all.php");

///////////////////////////////////////////////////////////////
// MODULE TOP REFERRERS
function sc_module_mini_top_referrers($x) { eval(scg());
   $result=sc_query("select * from link_bin where hidden != '1' and `referral`='yes' order by `referrals` desc limit $x");
    $numlinks=mysql_num_rows($result);
    echo "<h2>Top $x Referrers</h2>";
	for($i=0;$i<$numlinks;$i++) {
		//$link=mysql_fetch_object($result);
		//$link->link=str_replace("&","%26",$link->link);
		//$link->link=str_replace("?","%3F",$link->link);
		$link=mysql_fetch_object($result);
		$url=$link->link;
		$url = urlencode($url);
		$url = str_ireplace("http://","",  $url);
		$url = str_ireplace("http%3A%2F%2F","", $url);
		$url = str_ireplace("%2F","", $url);
       echo "<a class=\"a_cat\" href=\"$site_url/link_out.php?link=$url\" \n";
       echo " target=\"_blank\" title=\"$link->sname (in[$link->referrals] out[$link->clicks])\"\n";
        echo ">".sc_trunc($link->sname,24)."</a> ";
        
        echo " <font class=sc_black>[$link->referrals] <br>";
    }
}

?>
