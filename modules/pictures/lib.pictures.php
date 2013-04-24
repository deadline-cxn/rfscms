<?
include_once("include/lib.all.php");

sc_access_method_add("pictures", "orphanscan");
sc_access_method_add("pictures", "upload");
sc_access_method_add("pictures", "edit");
sc_access_method_add("pictures", "delete");
sc_access_method_add("pictures", "sort");


////////////////////////////////////////////////////////////////////////////////////////////////////////
// MODULE PICTURES
function sc_module_mini_pictures($x) { eval(scg());
    sc_div("PICTURES MODULE SECTION");
    echo "<h2>Last $x Pictures</h2>";
    $res2=sc_query("select * from `pictures` where `hidden`='no' order by time desc limit 0,$x");
    $numpics=mysql_num_rows($res2); // make pictures table...
	echo "<table border=0 cellspacing=0 cellpadding=0>";
    for($i=0;$i<$numpics;$i++) {
        $picture=mysql_fetch_object($res2);
        if($picture->sfw=="no") $picture->url="$RFS_SITE_URL/files/pictures/NSFW.gif";        
        echo "<tr><td class=contenttd>";
        echo "<a href=\"$RFS_SITE_URL/modules/pictures/pics.php?action=view&id=$picture->id\">".sc_picthumb("$RFS_SITE_PATH/$picture->url",50,0,1)."</a>";        
        echo "</td><td class=contenttd width='95%' valign=top>";
        echo "<a href=\"$RFS_SITE_URL/modules/pictures/pics.php?action=view&id=$picture->id\">";
        echo "$picture->sname</a><br>";
        echo sc_trunc($picture->description,50);        
        echo "</td></tr>";
    }
	echo "</table>";
    echo "<p align=right>(<a href=$RFS_SITE_URL/modules/pictures/pics.php?action=random class=a_cat>Random Picture</a>)";
    echo "(<a href=$RFS_SITE_URL/modules/pictures/pics.php class=a_cat>More...</a>)</p>";
}

?>
