<?
include_once("include/lib.all.php");

sc_module_register("TODO");
sc_menus_register("TODO","$RFS_SITE_URL/modules/todo_list/todo_list.php");

sc_database_add("todo_list","name","text","not null");
sc_database_add("todo_list","description","text","not null");
sc_database_add("todo_list","assigned_to","text","not null");
sc_database_add("todo_list","owner","text","not null");

sc_database_add("todo_list_task","name","text","not null");
sc_database_add("todo_list_task","list","text","not null");
sc_database_add("todo_list_task","priority","text","not null");
sc_database_add("todo_list_task","step","text","not null");
sc_database_add("todo_list_task","opened","timestamp","DEFAULT CURRENT_TIMESTAMP");
sc_database_add("todo_list_task","due","timestamp","not null");



sc_access_method_add("todo_list", "add");

/////////////////////////////////////////////////////////////////////////////////////////////////////////
// MODULE TODO LIST
function sc_module_todo_list($x) { eval(scg());
    sc_div("TODO MODULE SECTION");
    echo "<h2>TODO</h2>";
    $result=sc_query("select * from todo_list limit 0,$x");
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
