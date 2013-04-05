<?
include_once("include/lib.all.php");

//////////////////////////////////////////////////////////////////////////////////
// MODULE COMICS
function sc_module_mini_comics($x) { eval(scg());
    sc_div("COMIC MODULE SECTION");
    echo "<h2>Last $x Comics</h2>";
    echo "<table border=0 width=100%><tr><td align=center class=contenttd>";
    $res=sc_query("select * from comics where `published`='yes' order by time desc limit 1");
    $numc=mysql_num_rows($res);
    for($i=0;$i<$numc;$i++)    {
        $comic=mysql_fetch_object($res);
        $page=mysql_fetch_object(sc_query("select * from `comics_pages` where `parent`='$comic->id' order by `page` asc limit 1"));
        page_module_mini_preview_link($page->pid,"comics.php?action=viewcomic&id=$comic->id");
        //echo "<a href=\"comics.php?action=viewcomic&id=$comic->id\" class=\"a_cat\">$comic->title vol. $comic->volume issue $comic->issue</a>";
        //echo "<br>";
    }
    echo "</td></tr></table>";
    echo "<p align=right>(<a href=$RFS_SITE_URL/comics.php class=a_cat>More...</a>)</p>";
}


function page_mini_preview_link($id,$link){
    $site_url=$GLOBALS['site_url'];
    $page=mysql_fetch_array(sc_query("select * from `comics_pages` where `pid`='$id'"));
    $tid=$page['template'];
    $template=mysql_fetch_array(sc_query("select * from `comics_page_templates` where `id`='$tid'"));
    echo "<table border=0><tr><td> ";
    for($i=0;$i<$template['panels'];$i++)
    {
        $var="panel".($i+1)."_x";
        $x=$template[$var];
        if($x) $x=$x/2;
        $var="panel".($i+1)."_y";
        $y=$template[$var];
        if($y) $y=$y/2;
        $var="panel".($i+1)."_l";
        $l=$template[$var];
        $var="panel".($i+1);
        $url=$page[$var];
        if(empty($url)) $url="$site_url/images/comics_page_bkg.gif";
        echo "<a href=\"$link\"><img src=\"$url\" width=$x height=$y border=0></a> ";
        if($l=="yes") echo "<br> ";
    }
    echo "</td></tr></table>";
}

function page_mini_preview($id){
    $site_url=$GLOBALS['site_url'];
    $page=mysql_fetch_array(sc_query("select * from `comics_pages` where `pid`='$id'"));
    $tid=$page['template'];
    $template=mysql_fetch_array(sc_query("select * from `comics_page_templates` where `id`='$tid'"));
    echo "<table border=0><tr><td> ";
    for($i=0;$i<$template['panels'];$i++)
    {
        $var="panel".($i+1)."_x";
        $x=$template[$var];
        if($x) $x=$x/6;
        $var="panel".($i+1)."_y";
        $y=$template[$var];
        if($y) $y=$y/6;
        $var="panel".($i+1)."_l";
        $l=$template[$var];
        $var="panel".($i+1);
        $url=$page[$var];
        if(empty($url)) $url="$site_url/images/comics_page_bkg.gif";
        echo "<img src=\"$url\" width=$x height=$y> ";
        if($l=="yes") echo "<br> ";
    }
    echo "</td></tr></table>";
}

function page_full_view($id){
    $site_url=$GLOBALS['site_url'];
    $page=mysql_fetch_array(sc_query("select * from `comics_pages` where `pid`='$id'"));
    $template=mysql_fetch_array(sc_query("select * from `comics_page_templates` where `id`='".$page['template']."'"));
    echo "<table border=0><tr><td> ";
    for($i=0;$i<$template['panels'];$i++)
    {
        $var="panel".($i+1)."_x";
        $x=$template[$var];
        $var="panel".($i+1)."_y";
        $y=$template[$var];
        $var="panel".($i+1)."_l";
        $l=$template[$var];
        $var="panel".($i+1);
        $url=$page[$var];
        if(empty($url)) $url="$site_url/images/comics_page_bkg.gif";
        echo "<img src=\"$url\" width=$x height=$y> ";
        if($l=="yes") echo "<br> ";
    }
    echo "</td></tr></table>";
}


?>
