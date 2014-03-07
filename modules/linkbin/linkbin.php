<?
chdir("../../");
include("header.php");

function linkbin_showaddform() {
    echo "<h2>For consistency please put all link short names in all lower case unless it is necessary, thanks management</h2>\n";
    echo "<table border=0>\n";
    echo "<form enctype=application/x-www-form-URLencoded
    action=\"".
    $GLOBALS['RFS_SITE_URL']."/modules/linkbin/linkbin.php\" method=\"post\">\n";
    echo "<tr><td align=right>Link URL:       </td><td><input name=linkurl value=\"http://\" size=80></td></tr>\n";
    echo "<tr><td align=right>Link Short Name:</td><td><input name=short_name size=40></td></tr>\n";
    echo "<tr><td align=right>Description:    </td><td><textarea name=description rows=10 cols=70></textarea></td></tr>\n";
    echo "<tr><td align=right>Category:</td><td><select name=category>\n";
    $result=lib_mysql_query("select * from link_bin_categories");
    $numcats=mysql_num_rows($result);
    for($i=0;$i<$numcats;$i++)
    {
    	$cat=mysql_fetch_object($result);
    	echo "<option>$cat->name\n";
    }
    echo "</select></td></tr>\n";


    echo "<tr><td align=right>&nbsp;          </td><td><input type=submit name=submit value=\"add\"></td></tr>\n";
    echo "<input type=hidden name=action value=addlinkgo>\n";
    echo "</form>\n";
    echo "</table>\n";
}

if($action=="addlink") {
    linkbin_showaddform();
}

if($action=="addlinkgo") {
    $description=addslashes($description);
    $short_name=addslashes($short_name);
    $time=date("Y-m-d H:i:s");
    if($data->id==0) $data->id=999;
    lib_mysql_query("insert into `link_bin` values('','$linkurl','$data->id','$time','$short_name','0','0','$description','0','3','')");
    addsp($data->name,10);
    addlinks($data->name,1);
    echo "<p>Link [$short_name][$linkurl] added to linkbin...</p>\n";
    $action="editlinkbin";
}

if($action=="modifylinknow") {
	if($deletelink=="delete")
    {
        echo "<p><h1>Deleting Link!</h1></p>\n";
        lib_mysql_query("DELETE FROM link_bin where `id` = '$linkid' limit 1", $mysql);
        //lib_log_add_entry("*****> $data->name deleted a link from the linkbin $short_name $link");
        $action="editlinkbin";
    }
    if($renamelink=="modify")
    {
    	echo "<p><h1>Modifying Link!</h1></p>\n";
        $short_name=addslashes($short_name);
        $linkurl=addslashes($linkurl);
        $description=addslashes($description);
        $category=addslashes($category);
    	lib_mysql_query("update link_bin set `sname` = '$short_name' where `id` = '$linkid'");
    	lib_mysql_query("update link_bin set `link` = '$linkurl' where `id` = '$linkid'");
    	lib_mysql_query("update link_bin set `description` = '$description' where `id` = '$linkid'");
    	$hide=0; if($hidden=="yes") { $hide=1; } if($hidden=="no")  { $hide=0; }
    	lib_mysql_query("update link_bin set `hidden` = '$hide' where `id` = '$linkid'");
    	lib_mysql_query("update link_bin set `referrals` = '$referrals' where `id` = '$linkid'");
    	lib_mysql_query("update link_bin set `clicks` = '$clicks' where `id` = '$linkid'");
    	lib_mysql_query("update link_bin set `category` = '$category' where `id` = '$linkid'");
    	lib_mysql_query("update link_bin set `rating` = '$rating' where `id` = '$linkid'");
        $action="editlinkbin";
    }

}
if($action=="addlink2bin") {
    echo "<h1>Dump a Link in the Bin!</h1>\n";
    $time=date("Y-m-d H:i:s");
    lib_mysql_query("INSERT INTO link_bin VALUES ('', '$linkurl', '".$data->id."', '".$time."', '".$_REQUEST['linksn']."', '', '' ,'','3');");
    lib_log_add_entry("*****> $data->name added a link to the linkbin [".$_REQUEST['linksn']."]");
    $action="linkbin";
}
if($action=="editlinkbin") {
    echo "<p><h1>Link Bin Edirator</h1></p>\n";
    // list all the links here with edit or delete buttons...
    $result=lib_mysql_query("select * from link_bin order by time desc");
    $numlinks=mysql_num_rows($result);
    echo "<table width=100% border=0 cellspacing=0 cellpadding=0 align=center>\n";

    $gt=2;
    for($i=0;$i<$numlinks;$i++) {
    	    $gt++;if($gt>3)$gt=2;
            echo "<tr><td bgcolor=$forum_color[$gt]>\n";

            $link=mysql_fetch_object($result);
            $userdata=lib_users_get_data($link->poster);

            echo "<table border=0 cellspacing=0 cellpadding=0 width=100% bgcolor=$forum_color[$gt]>\n";
            echo "<form enctype=application/x-www-form-URLencoded action=linkbin.php method=\"post\">\n";
            echo "<input type=\"hidden\" name=\"action\" value=\"modifylinknow\">\n";
            echo "<input type=\"hidden\" name=\"linkid\" value=\"$link->id\">\n";

            echo "<tr bgcolor=$forum_color[$gt]>\n";
            echo "<td bgcolor=$forum_color[$gt] width=130><input type=text name=short_name value=\"$link->sname\" size=18></td>";
            echo "<td width=250><input type=text name=linkurl value=\"$link->link\" size=40> </td>\n";
            echo "<td width=300>(submitted by $userdata->name on ".sc_time($link->time).")</td>\n";
            echo "<td>Rating:</td>\n";
            echo "<td bgcolor=$forum_color[$gt] width=100 align=center><input type=submit name=renamelink value=modify></td>\n";
            echo "</tr>\n";
            echo "<tr>\n";

            echo "<td>\n";
                echo "<select name=category>\n";
                echo "<option>$link->category\n";
                
                $result2=lib_mysql_query("select * from categories order by name asc");
                $numcats=mysql_num_rows($result2);
                for($i2=0;$i2<$numcats;$i2++) {
                	$cat=mysql_fetch_object($result2);
                	echo "<option>$cat->name\n";
                }
                
                echo "</select>\n";
                echo "</td>\n";

            echo "<td><input type=text name=description value=\"$link->description\" size=40></td>\n";
            echo "<td><table border=0><tr>\n";
            echo "<td>referrals</td><td><input type=text size=3 name=referrals value=\"$link->referrals\"></td>\n";
            echo "<td>clicks</td><td><input type=text size=3 name=clicks value=\"$link->clicks\"></td>\n";
            if($link->hidden==0) echo "<td>hidden</td><td><select name=hidden><option>no<option>yes</td>\n";
            if($link->hidden==1) echo "<td>hidden</td><td><select name=hidden><option>yes<option>no</td>\n";
            echo "</tr></table></td>\n";
            echo "<td><select name=rating><option>$link->rating\n";
            for($j=1;$j<6;$j++) echo "<option>$j\n";
            echo "</select></td>\n";
            echo "<td align=center><input type=submit name=deletelink value=delete></td>\n";

            echo "</tr>\n";
            echo "<tr bgcolor=$forum_color[1]><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
            echo "</table>\n";
            echo "</form>\n";
            echo "</td></tr>\n";
    }

    echo "<tr><td>\n";
    echo "</td></tr></table>\n";
    // add a new link here...
    linkbin_showaddform();
}



$result=lib_mysql_query("select * from link_bin order by time desc");
$numlinks=mysql_num_rows($result);
echo "<table width=100% cellspacing=0 cellpadding=0 border=0>\n";
$gt=0;
for($u=0;$u<$numlinks;$u++) {
    $gt++; if($gt>1) $gt=0;
    $link=mysql_fetch_object($result);
    $userdata=lib_users_get_data($link->poster);
    list($lmonth,$lday,$lyear,$ltime,$lampm) = explode(" ",sc_time($link->time));
    if($lmonth!=$lastmonth) {
        echo "<tr><td><h1>$lmonth $lyear</h1></td></tr>\n";
        $lastmonth=$lmonth;
    }
    if(empty($link->description)) $link->description="<i>No description</i>";
    echo "<tr class=sc_project_table_$gt>";

	$link->sname=str_replace("www.","",$link->sname);
	$link->sname=str_replace(".com","",$link->sname);
	$link->sname=str_replace(".net","",$link->sname);
	$link->sname=str_replace(".org","",$link->sname);
	
	$link->link = str_replace(":","_rfs_colon_",  $link->link);
	$link->link=urlencode($link->link);

	echo "<td><a href=\"".$GLOBALS['RFS_SITE_URL']."/link_out.php?link=$link->link\" target=\"_blank\">$link->sname</a></td>\n";
	
    echo "</tr>\n";

   // echo "<tr bgcolor=$forum_color[$gt]><td></td>\n";
   // echo "<td>$link->description</td>\n";
   // echo "<td>refs: $link->referrals clicks:$link->clicks</td> <td>\n";
   //       // insert rating vote here
   // echo "</td></tr>\n";
    //echo "<tr><td>\n";
 //   echo "</td><td></td><td></td><td></td></tr>\n";
}
echo "</table>\n";

include("footer.php");
?>

