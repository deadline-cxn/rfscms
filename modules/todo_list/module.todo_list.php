<?
include_once("include/lib.all.php");

lib_menus_register("TODO","$RFS_SITE_URL/modules/todo_list/todo_list.php");

/////////////////////////////////////////////////////////////////////////////////////////////////////////
// MODULE TODO LIST
function module_todo_list($x) { eval(lib_rfs_get_globals());
    lib_div("TODO MODULE SECTION");
    echo "<h2>TODO</h2>";
    $result=lib_mysql_query("select * from todo_list limit 0,$x");
    $num=mysql_num_rows($result);
    echo "<table border=0 cellspacing=0 cellpadding=0 >";
    for($i=0;$i<$num;$i++){
        $task=mysql_fetch_object($result);
        $link="$RFS_SITE_URL/modules/todo_list/todo_list.php?action=view_todo_list&id=$task->id";
        echo "<tr><td>";
        echo "<a href=\"$link\">$task->name</a>";
        echo"</td></tr>";
    }
    echo "</table>";
}

?>
