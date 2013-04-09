<?
include_once("include/lib.all.php");

sc_access_method_add("files", "upload");
sc_access_method_add("files", "addlink");
sc_access_method_add("files", "orphanscan");
sc_access_method_add("files", "purge");
sc_access_method_add("files", "sort");
sc_access_method_add("files", "edit");
sc_access_method_add("files", "delete");
sc_access_method_add("files", "xplorer");
sc_access_method_add("files", "xplorershell");

/////////////////////////////////////////////////////////////////////////////////////////////////////////
// MODULE FILES
function sc_module_mini_files($x) { eval(scg());
    sc_div("FILES MODULE SECTION");
    echo "<h2>Last $x Files</h2>";

    $result=sc_query("select * from files order by `time` desc limit 0,$x");
    $numfiles=mysql_num_rows($result);
    echo "<table border=0 cellspacing=0 cellpadding=0 width=100%>";
    $gt=2;
    for($i=0;$i<$numfiles;$i++){
        $file=mysql_fetch_object($result);
        $link="$RFS_SITE_URL/modules/files/files.php?action=get_file&id=$file->id";
        $fdescription=str_replace('"',"&quote;",stripslashes($file->description));
        $gt++; if($gt>2)$gt=1;
        echo "<tr><td class=sc_project_table_$gt>";
        echo "<a href=\"$link\">$file->name</a> ";
        echo"</td><td class=sc_project_table_$gt>";
        echo sc_sizefile($file->size);
        echo "</td></tr>";
        echo "<tr><td class=sc_project_table_$gt></td><td class=sc_project_table_$gt>";
        echo "$file->description\n";
        echo "</td></tr>";
    }
    echo "</table>";
    echo "<p align=right>(<a href=$RFS_SITE_URL/modules/files/files.php class=a_cat>More...</a>)</p>";
}

?>
