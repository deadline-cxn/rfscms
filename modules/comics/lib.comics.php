<?
include_once("include/lib.all.php");

sc_access_method_add("comics", "create");
sc_access_method_add("comics", "delete");
sc_access_method_add("comics", "deleteothers");
sc_access_method_add("comics", "edit");
sc_access_method_add("comics", "editothers");

//////////////////////////////////////////////////////////////////////////////////
// MODULE COMICS
function sc_module_mini_comics($x) { eval(scg());
    sc_div("COMIC MODULE SECTION");
    echo "<h2>Last $x Comics</h2>";
    
	
	
    $res=sc_query("select * from comics where `published`='yes' order by time desc limit 1");
    $numc=mysql_num_rows($res);
    for($i=0;$i<$numc;$i++) {
		
		echo "<div class=\"memeboxmini\" >";

$comic=mysql_fetch_object($res);
$page=mysql_fetch_object(sc_query("select * from `comics_pages` where `parent`='$comic->id' order by `page` asc limit 1"));
sc_comics_mini_preview_link($page->pid,"$RFS_SITE_URL/modules/comics/comics.php?action=viewcomic&id=$comic->id");
echo "<a href=\"$RFS_SITE_URL/modules/comics/comics.php?action=viewcomic&id=$comic->id\">$comic->title vol. $comic->volume issue $comic->issue</a>";
echo "</div>";

    }

    echo "(<a href=$RFS_SITE_URL/comics.php class=a_cat>More...</a>)";
}


function sc_comics_mini_preview_link($id,$link){
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
        echo "<a href=\"$link\">";
		echo sc_picthumb($url,$x,$y,0); 
		//<img src=\"$url\" width=$x height=$y border=0>
		echo "</a> ";
        if($l=="yes") echo "<br> ";
    }
    echo "</td></tr></table>";
}

function sc_comics_page_mini_preview($id){
    $site_url=$GLOBALS['site_url'];
    $page=mysql_fetch_array(sc_query("select * from `comics_pages` where `pid`='$id'"));
    $tid=$page['template'];
    $template=mysql_fetch_array(sc_query("select * from `comics_page_templates` where `id`='$tid'"));
    echo "<table border=0><tr><td> ";
    for($i=0;$i<$template['panels'];$i++) {
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
//echo "<img src='$RFS_SITE_URL/include/generate.image.php/$url.png?mid=$m->id&owidth=$meme_thumbwidth&forcerender=1' border=0>";
echo sc_picthumb($url,$x,$y,0);
        //echo "<img src=\"$url\" width=$x height=$y> ";
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
