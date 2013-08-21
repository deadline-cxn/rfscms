<?
if(stristr(getcwd(),"modules")) { chdir("../../"); }
include_once("header.php");
$newslogn   = addslashes($_REQUEST['newslogn']);
   
if(sc_access_check("slogan","edit")) {
	if($action=="add_slogan") {
        sc_query("insert into slogans values (0,'$newslogn')");
        echo "<p>New slogan [".stripslashes($newslogn)."] added...</p>\n";
    }
    if($action=="del_slogan"){
        sc_query("delete from slogans where `id` = '$sid'");
        echo "<p>Slogan removed...</p>\n";
    }
    if($action=="ren_slogan") {
        sc_query("update slogans set `slogan` = '$newslogn' where `id` = '$sid'");
        echo "<p>Slogan renamed...</p>\n";
    }
    $result=sc_query("select * from slogans");
    $numslogans=mysql_num_rows($result);

    echo "<table border=0 width=100%>\n";
    for($i=0;$i<$numslogans;$i++) {
        $slog=mysql_fetch_array($result);
        echo "<tr><td><form action=\"$RFS_SITE_URL/slogan_admin.php\" method\"post\">\n";
        echo "<input type=\"hidden\" name=\"action\" value=\"del_slogan\">\n";
        echo "<input type=\"hidden\" name=\"sid\" value=\"".$slog['id']."\">\n";
        echo "<input type=\"submit\" name=\"submit\" value=\"delete\">\n";       
        echo "</form></td><td>\n";
        echo "<form action=\"$RFS_SITE_URL/slogan_admin.php\" method=\"post\">\n";
        echo "<input type=\"hidden\" name=\"action\" value=\"ren_slogan\">\n";
        $slogn=str_replace("\"","&quote;",$slog['slogan']);
        echo "<input type=\"textbox\" name=\"newslogn\" value=\"$slogn\" size=\"100\"></td><td>\n";
        echo "<input type=\"hidden\" name=\"sid\" value=\"".$slog['id']."\">\n";
        echo "<input type=\"submit\" name=\"submit\" value=\"change\">\n";
        echo "</form>&nbsp;</td></tr>\n";        
    }
    echo "<tr><td>&nbsp;</td><td><form action=\"$RFS_SITE_URL/slogan_admin.php\" method\"post\">\n";
    echo "<input type=\"hidden\" name=\"action\" value=\"add_slogan\">\n";
    echo "<input type=\"textbox\" name=\"newslogn\" value=\"\" size=\"100\"></td><td align=left>\n";
    echo "<input type=\"submit\" name=\"submit\" value=\"add\"></form>\n";
    echo "&nbsp;</td></tr>\n";        
    echo "</table>\n";
}
else {
    echo "<p>You do not have access to edit slogans!</p>\n";
    // sc_log("*****> $data->name tried to access the slogans admin area!");
}
include("footer.php");
exit();
?>
