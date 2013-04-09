<?
include_once("include/lib.all.php");

///////////////////////////////////////////////////////////////
// MODULE LINK FRIENDS
function sc_module_mini_link_friends() { eval(scg());
        $result=sc_query("select * from link_bin where friend='yes' order by time");
        $numlinks=mysql_num_rows($result);
        echo "<h2>Link Friends</h2>";
        for($i=0;$i<$numlinks;$i++) {
            $link=mysql_fetch_object($result);
            $url=$link->link;
			$url = urlencode($url);
            // $url = str_ireplace("http://","",  $url);
            //$url = str_ireplace("http%3A%2F%2F","", $url);            

	echo "<div><a href=\"$RFS_SITE_URL/link_out.php?link=$url\" target=_blank>$link->sname</a></div>";
    
   }		
        if($data->access=="255") {
            echo "<div style='float: left;'>";
echo "<form action=\"$RFS_SITE_URL/admin/adm.php\" method=post><input type=hidden name=action value=edit_linkbin>";
            echo "<input type=submit name=submit value=\"edit links\"></form></div>";
        }
}

?>
