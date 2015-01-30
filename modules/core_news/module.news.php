<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
// NEWS CORE MODULE
/////////////////////////////////////////////////////////////////////////////////////////
include_once("include/lib.all.php");

$RFS_ADDON_NAME="news";
$RFS_ADDON_VERSION="4.0.0";
$RFS_ADDON_SUB_VERSION="0";
$RFS_ADDON_RELEASE="";
$RFS_ADDON_DESCRIPTION="RFSCMS News";
$RFS_ADDON_REQUIREMENTS="";
$RFS_ADDON_COST="";
$RFS_ADDON_LICENSE="";
$RFS_ADDON_DEPENDENCIES="";
$RFS_ADDON_AUTHOR="Seth T. Parson";
$RFS_ADDON_AUTHOR_EMAIL="seth.parson@rfscms.org";
$RFS_ADDON_AUTHOR_WEBSITE="http://rfscms.org/";
$RFS_ADDON_IMAGES="";
$RFS_ADDON_FILE_URL="";
$RFS_ADDON_GIT_REPOSITORY="";
$RFS_ADDON_URL=lib_modules_get_base_url_from_file(__FILE__);

lib_menus_register("News","$RFS_SITE_URL/modules/core_news/news.php");
//////////////////////////////////////////////////////////////////////////////////
// ADMIN
function adm_action_lib_news_news_submit() {
    eval(lib_rfs_get_globals());
    $RFS_ADDON_URL=lib_modules_get_url("news");
    lib_domain_gotopage("$RFS_ADDON_URL?showform=yes");
}
function adm_action_lib_news_news_edit() {
    eval(lib_rfs_get_globals());
    $RFS_ADDON_URL=lib_modules_get_url("news");
    lib_domain_gotopage("$RFS_ADDON_URL?action=edityournews");
}
//////////////////////////////////////////////////////////////////////////////////
// PANELS
function m_panel_news_list($x) {
    eval(lib_rfs_get_globals());
    $RFS_ADDON_URL=lib_modules_get_url("news");
    lib_div("NEWS MODULE SECTION");
    echo "<h2>Last $x News Articles</h2>";
    $newslist=rfs_getnewslist($newssearch);
    echo "<table border=0 cellspacing=0>";
    $ct=count($newslist); if($ct>$x) $ct=$x;
    for($cci=0;$cci<$ct;$cci++){
        echo "<tr><td class=contenttd width=2% >";
        $news=rfs_getnewsdata($newslist[$cci]);
        if(!file_exists("$RFS_SITE_PATH/$news->image_url"))
            $news->image_url="$RFS_SITE_URL/images/icons/404.png";
        if(empty($news->image_url))
            $news->image_url="$RFS_SITE_URL/images/icons/news.png";
        if(!stristr($news->image_url,$RFS_SITE_URL))
            $news->image_url=$RFS_SITE_URL."/".ltrim($news->image_url,"/");

        $altern=stripslashes($news->image_alt);
        $picf="$RFS_SITE_PATH/$news->image_url";
        $picf=str_replace($RFS_SITE_URL,"",$picf);
        echo "<a href=\"$RFS_ADDON_URL?action=view&nid=$news->id\">".lib_images_thumb("$picf",30,0,1	)."</a>\n";
        echo "</td><td valign=top  class=contenttd 90%>";
        echo "<a href=\"$RFS_ADDON_URL?action=view&nid=$news->id\" class=\"a_cat\">".lib_string_truncate("$news->headline",50)."</a>";
        $ntext=str_replace("<p>"," ",$ntext);
        $ntext=str_replace("</p>"," ",$ntext);
        $ntext=str_replace("<","&lt;",$ntext);
        echo "<font class=rfs_black>$ntext</font>";
        echo "</td></tr>";
    }
    echo "</table>";
    echo "<p align=right>(<a href=$RFS_ADDON_URL class=\"a_cat\" align=right>More...</a>)</p>";
}
function m_panel_news_list_popular($x) {
    eval(lib_rfs_get_globals());
    $RFS_ADDON_URL=lib_modules_get_url("news");
    lib_div("NEWS MODULE SECTION");
    echo "<h2>Popular News Articles</h2>";
    //search method dictate sort order?
    $result=lib_mysql_query("select * from news where topstory!='yes' and published='yes' order by views desc limit $x");
    echo "<table border=0>";
    $ct=$result->num_rows;
    for($i=0;$i<$ct;$i++)    {
        $news=$result->fetch_object();
        echo "<tr><td>";
        echo "<table border=0 cellpadding=1 cellspacing=0><tr><td>";
        if(!file_exists("$RFS_SITE_PATH/$news->image_url"))
            $news->image_url="$RFS_SITE_URL/images/icons/404.png";
        if(empty($news->image_url))
            $news->image_url="$RFS_SITE_URL/images/icons/news.png";
        if(!stristr($news->image_url,$RFS_SITE_URL))
            $news->image_url=$RFS_SITE_URL."/".ltrim($news->image_url,"/");

        $altern=stripslashes($news->image_alt);
        echo "<a href=\"$RFS_ADDON_URL?action=view&nid=$news->id\"><img src=\"$news->image_url\" border=\"0\" title=\"$altern\" alt=\"$altern\" width=30 height=30></a>\n";
        echo "</td></tr></table>";
        echo "</td><td valign=top>";
        echo "<a href=\"$RFS_ADDON_URL?action=view&nid=$news->id\" class=\"a_cat\">".lib_string_truncate("$news->headline",50)."</a><br>";
        $ntext=str_replace("<br>"," ",stripslashes(lib_string_truncate("$news->message",70)));
        $ntext=str_replace("<img",$news->headline,$ntext);
        $ntext=str_replace("<iframe",$news->headline,$ntext);
        $ntext=str_replace("</body>","<nobody>",$ntext);
        $ntext=str_replace("<p>"," ",$ntext);
        $ntext=str_replace("</p>"," ",$ntext);
        echo "<font class=rfs_black>$ntext</font>";
        echo "</td></tr>";
    }
    echo "</table>";
}
//////////////////////////////////////////////////////////////////////////////////
// FUNCTIONS
function news_buttons() {
    eval(lib_rfs_get_globals());
    $RFS_ADDON_URL=lib_modules_get_url("news");
	if(lib_access_check("news","submit")) {
		lib_buttons_make_button("$RFS_ADDON_URL?showform=yes","Submit News");
	}
}
function module_news_top_story() {
    rfs_show_top_news();
}
function m_panel_news_blog_style($x) {
    eval(lib_rfs_get_globals());
	rfs_show_top_news();
	$newslist=rfs_getnewslist(""); $ct=count($newslist); if($ct>$x) $ct=$x;
	echo "Older news...<br>";
	for($cci=0;$cci<$ct;$cci++) {
		$news=rfs_getnewsdata($newslist[$cci]);
		rfs_show_news($news->id);
    }
}
function rfs_getnewstopstory(){
    $result=lib_mysql_query("select * from news where topstory='yes' and published='yes'");
    $news=$result->fetch_object();
    return $news;
}
function rfs_getnewsdata($news){
    $query="select * from news where id = '$news'";
    $result=lib_mysql_query($query);
    if($result->num_rows>0)
        $news=$result->fetch_object();
    return $news;
}
function rfs_getnewslist($newssearch) {
    $newsbeg=$GLOBALS['top'];
    $newsend=$GLOBALS['bot'];
    $query = "select * from news where topstory!='yes' and published='yes' ";
    if(!empty($newssearch)) { $query.=" ".$newssearch; unset($newssearch); }
    if(empty($newsbeg)) $newsbeg=0; if(empty($newsend)) $newsend=10;
    $query .= " order by time desc";
    $result = lib_mysql_query($query);
    $numnews=$result->num_rows;
    $i=0;
    while($i<$numnews) {
        $der = $result->fetch_array();
        $newslist[$i] = $der['id'];
        $i=$i+1;
    }
    return $newslist;
}
function rfs_get_news_headline($id){
    $result=lib_mysql_query("select * from news where id='$id'");
    $news=$result->fetch_object();
    return $news->headline;
}
function rfs_get_top_news_id(){
    $result=lib_mysql_query("select * from news where topstory='yes' and published='yes'");
    $news=$result->fetch_object();
   return $news->id;
}
function rfs_show_top_news() {
    $news=lib_mysql_fetch_one_object("select * from news where topstory='yes' and published='yes'");    
    rfs_show_news($news->id);
}
function rfs_show_news($nid) {
    eval(lib_rfs_get_globals());
    $RFS_ADDON_URL=lib_modules_get_url("news");
	if(empty($RFSW_BULLET_IMAGE)) $RFSW_BULLET_IMAGE	= $RFS_SITE_URL."/modules/core_wiki/images/bullet.gif";
	if(empty($RFSW_LINK_IMAGE))   $RFSW_LINK_IMAGE     = $RFS_SITE_URL."/modules/core_wiki/images/link2.png";
	if(empty($nid)) {		
		news_buttons();
		return;
	}
	$result=lib_mysql_query("select * from news where id='$nid'");
    $news=$result->fetch_object();
    $userdata=lib_mysql_fetch_one_object("select * from `users` where id='$news->submitter'");
	
	echo "<div class=\"news_box\">";
		echo "<div class=\"news_headline_bar\">";
			echo "<div class=\"news_headline\">$news->headline</div>";
			echo "<div class=\"news_time\">Posted on ".lib_string_current_time($news->time)."</div>";
		echo "</div>";
		echo "<div class=\"news_article\">";
		
		echo "<div class=\"news_image\">";
		
		$out_link=urlencode("$RFS_ADDON_URL?action=view&nid=$news->id");

    if(!empty($news->image_url)) {
		$news->image_url=str_replace("$RFS_SITE_PATH/","",$news->image_url);
		$news->image_url=str_replace("$RFS_SITE_URL/","",$news->image_url);		
		$altern=stripslashes($news->image_alt);		
		if(empty($news->image_link))
			$news->image_link="$RFS_ADDON_URL?action=view&nid=$news->id";
		echo "<a href=\"$news->image_link\" target=\"_blank\" class=\"news_a\" >";
		if(!file_exists("$RFS_SITE_PATH/".ltrim($news->image_url,"/"))) {
			$oldimage=$news->image_url;
			$news->image_url="$RFS_SITE_URL/images/icons/404.png";
			echo "<br>($oldimage)";
		}
		if(!stristr($news->image_url,$RFS_SITE_URL)) {
			
			$news->image_url=$RFS_SITE_URL."/".ltrim($news->image_url,"/");		
			
		}
		echo "<img src=\"".lib_images_thumb_raw($news->image_url,100,0,1)."\" border=\"0\" title = '$altern' alt='$altern' class='rfs_thumb'>";
	}	
	if(!empty($news->image_url)) {
		echo  "</a>";
	}
		echo "</div>";
	
		

    if(!empty($news->wiki)) {
            $wikipage=lib_mysql_fetch_one_object("select * from wiki where `name`='$news->wiki' order by revision desc limit 1");
            echo lib_string_convert_smiles(wikiimg(wikitext($wikipage->text)));
    }	else {
        $news->message=str_replace("<a h","<a class=news_a h",$news->message);
        lib_rfs_echo(lib_string_convert_smiles(stripslashes(wikiimg((wikitext($news->message))))));
    }
	
	
		
	
	echo "</div>";
    
	echo "<div class=\"news_edit_bar\">";
    $data=$GLOBALS['data'];
	
    if( ($data->name==$userdata->name) ||
		(lib_access_check("news","editothers")) ) {

		echo "<div>";
		if(!empty($news->wiki)) {
			echo "[<a href=\"$RFS_SITE_URL/modules/core_wiki/wiki.php?action=edit&name=$news->wiki\" class=news_a>edit (wiki page)</a>] \n";
			echo "[<a href=\"$RFS_ADDON_URL?action=editnews&nid=$nid\" class=news_a>edit (news)</a>] \n";			
		} else {
			echo "[<a href=\"$RFS_ADDON_URL?action=editnews&nid=$nid\" class=news_a>edit</a>] \n";
		}
		
        echo "[<a href=\"$RFS_ADDON_URL?action=deletenews&nid=$nid\" class=news_a>remove</a>] \n";
		echo "<p>&nbsp;</p>";
		echo "</div>";
    }
		echo "<div>";
		
			echo "<div style='clear: both;'></div>";
		
		
		
		$page="$RFS_ADDON_URL?action=view&nid=$nid";
		
		// echo "[$page] [$RFS_ADDON_URL]";
		
		if(lib_rfs_bool_true($RFS_SITE_NEWS_SOCIALS)) {
			lib_social_share_bar2($page,$news->image_url,$news->headline);
		}
		
				echo "<div style='clear: both;'></div>";
		
		if(lib_rfs_bool_true($RFS_SITE_NEWS_FACEBOOK_COMMENTS)) {			
			lib_social_facebook_comments($page);
			
		}
				echo "<div style='clear: both;'></div>";
		
			echo "</div>";
		echo "</div>";
	echo "</div>";
}
function shownews() {
    eval(lib_rfs_get_globals());
    $RFS_ADDON_URL=lib_modules_get_url("news");
	$month_name=$GLOBALS['month_name'];
	$day_name=$GLOBALS['day_name'];
	$data=$GLOBALS['data'];
	if($GLOBALS['action']=="catsrch") {
        $derr=$cat_desc[$GLOBALS['crit']];
        echo "<p>Category [$derr] search...</p>";
        $t=$GLOBALS['crit']; $kt=sprintf("%03d|",$t);
        $newssearch="and categories like '%$kt%'";
    }
    if($GLOBALS['action']=="search") {
        $t=$GLOBALS['crit'];
        $newssearch="and message like '%$t%' or headline like '%$t%'";
    }
    if(empty($GLOBALS['top'])) $GLOBALS['top']=0;
    if(empty($GLOBALS['bot'])) $GLOBALS['bot']=1500;
    $newslist=rfs_getnewslist($newssearch);
	
    // search method dictate sort order?
	
	echo "<table border=0 cellspacing=0 cellpadding=4 width=100%>";
	
	if($data->access==255) {
		echo "<tr>";
		echo "<td class=contenttd width=2%>Views</td>";
		echo "<td class=contenttd width=2%> &nbsp;  </td>";
		echo "<td class=contenttd> &nbsp; </td>";

		
		echo "</tr>";
	}
    
	for($i=0;$i<count($newslist);$i++) {
        $news=rfs_getnewsdata($newslist[$i]);
		echo "<tr>";
		
	
		//////////////////
		echo "<td class=contenttd>";		
			if($data->access==255) echo "$news->views";
		echo "</td>";
		//////////////////

		$altern=stripslashes($news->image_alt);		
		if(empty($news->image_url))
			$news->image_url="images/icons/news.png";			
		if(!file_exists("$RFS_SITE_PATH/".ltrim($news->image_url,"/"))) {
			$oldimage=$news->image_url;
			$news->image_url="$RFS_SITE_URL/images/icons/404.png";
		}
		if(!stristr($news->image_url,$RFS_SITE_URL))
			$news->image_url=$RFS_SITE_URL."/".ltrim($news->image_url,"/");
		
		/////////////////
		echo "<td class=contenttd>";
		echo "<a href=\"$RFS_ADDON_URL?action=view&nid=$news->id\">";
		echo "<img src=\"$news->image_url\" border=\"0\" title=\"$altern\" alt=\"$altern\" width=30 height=30>";
		echo "</a>\n";
		echo "</td>";
		/////////////////

		/////////////////
		echo "<td class=contenttd valign=top>";		
		echo "<a href=\"$RFS_ADDON_URL?action=view&nid=$news->id\" class=\"a_cat\">$news->headline</a><br>";
       $ntext=str_replace("<br>"," ",stripslashes(lib_string_truncate("$news->message",80)));
       $ntext=str_replace("<p>"," ",$ntext);
       $ntext=str_replace("</p>"," ",$ntext);
		$ntext=str_replace("<","&lt;",$ntext);
       echo $ntext;
       echo "</td>";
	   
	   
	   /////////////////
	   echo "</tr>";
    }
    
    echo "</table>";
}
?>
