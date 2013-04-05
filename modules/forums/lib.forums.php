<?
include_once("include/lib.all.php");



function sc_module_mini_latest_forum_threads($x) { eval(scg());
    sc_div("FORUMS MODULE SECTION");
    echo "<h2>Last $x Forum Threads</h2>";

    echo "<table border=0 cellspacing=0>";

    $result = sc_query("select * from forum_posts where `forum`='$forum_which' and `thread_top`='yes' order by bumptime desc limit 0,30");
    if($result) $numposts=mysql_num_rows($result);
    else $numposts=0;
    if($numposts) {
       $gt=1; $i=0;

        echo "<tr><td class=contenttd width=2% >";
        $thread=mysql_fetch_object($result);


        echo "</td></tr>";
    }
    echo "</table>";
    echo "<p align=right>(<a href=$RFS_SITE_URL/modules/forums/forums.php class=\"a_cat\" align=right>More...</a>)</p>";
}


?>
