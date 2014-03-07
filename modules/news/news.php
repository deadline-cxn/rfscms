<?php

////////////////////////////////////////////////////
// RFSCMS News v4.0

$title="NEWS";
chdir("../../");
include("header.php");

if(!empty($GLOBALS['title'])) if(empty($GLOBALS['headline'])) $GLOBALS['headline']=$GLOBALS['title'];

if($give_file=="news_cat_add") $image=put_news_image('userfile');
if($give_file=="news_cat_mod") $image=put_news_image('userfile');
if($give_file=="news"){
    if(empty($data->name)) echo "<p>No...</p>\n";
    else {
        $httppath=put_news_image('userfile');
    }
    lib_mysql_query("update `news` set `image_url`='$httppath' where `id`='$nid'");
    $action="ed";
}

if($give_file=="news_sup") {
    if(empty($data->name)) echo "<p>No...</p>\n";
    else {
        put_news_image('userfile');
        put_news_image('userfile2');
        put_news_image('userfile3');
        put_news_image('userfile4');
        put_news_image('userfile5');
    }
    $action="ed";
}

if(!empty($bot)) {
	if(empty($HTTP_SESSION_VARS['bot'])) {
		$HTTP_SESSION_VARS['bot']=$bot;
	}
}
else {
	$bot=$HTTP_SESSION_VARS['bot'];
}

if($showform=="yes"){
	
	if(!lib_access_check("news","submit")) {
        	echo smiles("<p>:X</p><p>You can not edit or submit news!</p>");
	}
	else {
			echo "<div class=\"forum_box\">";
        echo "<h1>Submit News</h1>";
        echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"> \n";
        echo "<br><form enctype=application/x-www-form-URLencoded method=POST action=\"news.php\">\n";
        echo "<input type=hidden name=action value=createnewsgo>";
        echo "<tr><td>Headline:</td><td><input type=\"text\" name=\"headline\" size=\"100\"></td></tr>\n";
        echo "<tr><td></td><td><input type=\"submit\" value=\"Add News\" class=b4button></td></tr>\n";
        echo "</table></form><br>\n";
		echo "</div>";
    }
}


if(lib_access_check("news","edit")) {
	
	if($action == "clearnewsimage") {
		lib_mysql_query("update news set image_url='' where id='$nid'");
		$action="ed";
	}
	if($action == "imageurl") {
		lib_mysql_query("update news set image_url='$userfile' where id='$nid'");
		$action="ed";
	}
	
	if($action == "createnewsgo"){
    $time=date("Y-m-d H:i:s");
    $result=lib_mysql_query("INSERT INTO `news` (`headline`, `submitter`,`time`, `published`)
                                  VALUES ('$headline','$data->id','$time','no');");
    echo "<p>News headline entered into database... The story is unpublished.</p>";
    $result=lib_mysql_query("select * from news where `headline`='$headline' and `submitter`='$data->id'");
    $news=mysql_fetch_object($result);
    $nid=$news->id;
    $action="ed";
	}

	if($action=="publish"){
		echo "Publishing news article $nid";
		lib_mysql_query("update `news` set `published`='yes' where `id`='$nid'");
		$action="edityournews";
	}

	if($action=="unpublish"){
		echo "Unpublishing news article $nid";
		lib_mysql_query("update `news` set `published`='no' where `id`='$nid'");
		$action="edityournews";
	}

	if($action=="de") {
		deletenews($nid);
		$action="edityournews";
	}

	if($action=="dego") 	  {
		deletenewsgo($nid);
		$action="edityournews";
	}

	if($action=="ed") {
		editnews($nid);
	}

	if($action=="edgo") {
		updatenews($nid);
		editnews($nid);
	}	
}

if(($action=="view") || ($action=="ad")) {

    //echo "<table border=0 cellspacing=0 cellpadding=1 width=95 % ><tr><td>";
    //echo "<table border=0 width=100% ><tr>";
    //echo "<td valign=top class=td_cat>";
	sc_show_news($nid);
    echo "<br>\n";
    echo "<p align=right><a href=news.php  class=\"a_cat\" align=right>More news stories...</a></p>";
    //echo "</td></tr></table>";
    //echo "</td></tr></table>";
    echo "<br>";

    /////////////////////////////////////////////////////////////////////////////////////////////////////////

    //echo "<table border=0 cellspacing=0 cellpadding=1 width=100 % ><tr><td>";
    //echo "<table border=0 width=100% ><tr>";
    //echo "<td valign=top class=td_cat>";

    sc_module_news_list(10);
    // sc_module_popular_news(10);

    echo "<p align=right><a href=news.php  class=\"a_cat\" align=right>More news stories...</a></p>";
    //echo "</td></tr></table>";
    //echo "</td></tr></table>";

}

if($action=="edityournews"){
    echo "<h1>Editing your news stories</h1>";
	lib_button("$RFS_SITE_URL/modules/news/news.php?showform=yes","Submit new news article");

    echo "<table border=0 cellspacing=0 cellpadding=5 width=100%><tr><td class=contenttd>";
    echo "<p>Unpublished:</p>";
    echo "<p align=left>";
    $res=lib_mysql_query("select * from news where submitter='$data->id' and published='no' order by time desc");
    $count=mysql_num_rows($res);
    for($i=0;$i<$count;$i++) {
        $news=mysql_fetch_object($res);
        echo "[<a href=news.php?action=de&nid=$news->id>Delete</a>] ";
        echo "[<a href=news.php?action=ed&nid=$news->id>Edit</a>] ";
        echo "[<a href=news.php?action=publish&nid=$news->id>Publish</a>] ";
        echo "<a href=\"news.php?action=view&nid=$news->id\">link: $news->headline</a><br>";
    }
    echo "</p>";

    echo "<p>Published:</p>";

    echo "<p align=left>";
    $res=lib_mysql_query("select * from news where submitter='$data->id' and published='yes' order by time desc");
    $count=mysql_num_rows($res);
    for($i=0;$i<$count;$i++) {
        $news=mysql_fetch_object($res);
        echo "[<a href=news.php?action=de&nid=$news->id>Delete</a>] ";
        echo "[<a href=news.php?action=ed&nid=$news->id>Edit</a>] ";
        echo "[<a href=news.php?action=unpublish&nid=$news->id>Unpublish</a>] ";
        echo "<a href=\"news.php?action=view&nid=$news->id\">link: $news->headline</a><br>";
    }
    echo "</p>";

    echo "<p>Other people's news stories:</p>";

    echo "<p>Unpublished:</p>";
    echo "<p align=left>";
    $res=lib_mysql_query("select * from news where submitter!='$data->id' and published='no' order by time desc");

    $count=mysql_num_rows($res);
    for($i=0;$i<$count;$i++) {
        $news=mysql_fetch_object($res);
        $userdata=getuserdata($news->submitter);
        echo "[<a href=news.php?action=de&nid=$news->id>Delete</a>] ";
        echo "[<a href=news.php?action=ed&nid=$news->id>Edit</a>] ";
        echo "[<a href=news.php?action=publish&nid=$news->id>Publish</a>] ";
        echo "<a href=\"news.php?action=view&nid=$news->id\">link: $news->headline</a> ($userdata->name)<br>";
    }
    echo "</p>";

    echo "<p>Published:</p>";

    echo "<p align=left>";
    $res=lib_mysql_query("select * from news where submitter!='$data->id' and published='yes' order by time desc");
    $count=mysql_num_rows($res);
    for($i=0;$i<$count;$i++) {
        $news=mysql_fetch_object($res);
        $userdata=lib_users_get_data($news->submitter);
        echo "[<a href=news.php?action=de&nid=$news->id>Delete</a>] ";
        echo "[<a href=news.php?action=ed&nid=$news->id>Edit</a>] ";
        echo "[<a href=news.php?action=unpublish&nid=$news->id>Unpublish</a>] ";
        echo "<a href=\"news.php?action=view&nid=$news->id\">link: $news->headline</a> ($userdata->name)<br>";
    }
    echo "</p>";
    echo "</td></tr></table>";

}

if($action!="view") shownews();

include("footer.php");

?>
