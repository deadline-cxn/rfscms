<?
include("rfs/header.php");
$newslogn   = addslashes($_REQUEST['newslogn']);

if($data->access=="255")
{
    if($action=="add_slogan")
    {
        dm_query("insert into slogans values (0,'$newslogn')");
        echo "<p>New slogan [".stripslashes($newslogn)."] added...</p>\n";
    }
    if($action=="del_slogan")
    {
        dm_query("delete from slogans where `id` = '$sid'");
        echo "<p>Slogan removed...</p>\n";
    }
    if($action=="ren_slogan")
    {
        dm_query("update slogans set `slogan` = '$newslogn' where `id` = '$sid'");
        echo "<p>Slogan renamed...</p>\n";
    }
    $result=dm_query("select * from slogans");
    $numslogans=mysql_num_rows($result);

    echo "<table border=0 width=100%>\n";
    for($i=0;$i<$numslogans;$i++)
    {
        $slog=mysql_fetch_array($result);
        echo "<tr><td><form action=\"$site_url/slogan_admin.php\" method\"post\">\n";
        echo "<input type=\"hidden\" name=\"action\" value=\"del_slogan\">\n";
        echo "<input type=\"hidden\" name=\"sid\" value=\"".$slog['id']."\">\n";
        echo "<input type=\"submit\" name=\"submit\" value=\"delete\">\n";       
        echo "</form></td><td>\n";
        echo "<form action=\"$site_url/slogan_admin.php\" method=\"post\">\n";
        echo "<input type=\"hidden\" name=\"action\" value=\"ren_slogan\">\n";
        $slogn=str_replace("\"","&quote;",$slog['slogan']);
        echo "<input type=\"textbox\" name=\"newslogn\" value=\"$slogn\" size=\"100\"></td><td>\n";
        echo "<input type=\"hidden\" name=\"sid\" value=\"".$slog['id']."\">\n";
        echo "<input type=\"submit\" name=\"submit\" value=\"change\">\n";
        echo "</form>&nbsp;</td></tr>\n";        
    }
    
    echo "<tr><td>&nbsp;</td><td><form action=\"$site_url/slogan_admin.php\" method\"post\">\n";
    echo "<input type=\"hidden\" name=\"action\" value=\"add_slogan\">\n";
    echo "<input type=\"textbox\" name=\"newslogn\" value=\"\" size=\"100\"></td><td align=left>\n";
    echo "<input type=\"submit\" name=\"submit\" value=\"add\"></form>\n";
    echo "&nbsp;</td></tr>\n";        
    echo "</table>\n";
}
else
{
    echo "<p>You do not have access to edit slogans!</p>\n";
    dm_log("*****> $data->name tried to access the slogans admin area!");
}

include("footer.php");
exit();

?>

