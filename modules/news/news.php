<?php
////////////////////////////////////////////////////// Seth Coder News v3.2
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
    sc_query("update `news` set `image_url`='$httppath' where `id`='$nid'");
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
	if($data->access!=255){
        	echo smiles("<p>:X</p><p>You can not edit or submit news!</p>");
	}
	else {
        echo "<h1>Submit News</h1>";
        echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"> \n";
        echo "<br><form enctype=application/x-www-form-URLencoded method=POST action=\"news.php\">\n";
        echo "<input type=hidden name=action value=createnewsgo>";
        echo "<tr><td>Headline:</td><td><input type=\"text\" name=\"headline\" size=\"100\"></td></tr>\n";
        echo "<tr><td></td><td><input type=\"submit\" value=\"Add News\" class=b4button></td></tr>\n";
        echo "</table></form><br>\n";
    }
}


if($data->access==255) {
	
	if($action == "clearnewsimage") {
		sc_query("update news set image_url='' where id='$nid'");			
		$action="ed";
	}
	if($action == "imageurl") {
		sc_query("update news set image_url='$userfile' where id='$nid'");
		$action="ed";
	}
	
	
	
	if($action == "modifycategory"){
		if(empty($image)) $image="$RFS_SITE_URL/images/news/test.jpg";
		if(!empty($cname)) sc_query("update `categories` set `name`='$cname' where `id`='$id'");
		sc_query("update `categories` set `image`='$image' where `id`='$id'");		
		$action="editcategories";
	}
	if($action == "addcategory"){
		if(empty($image)) $image="$RFS_SITE_URL/images/news/test.jpg";
		sc_query("insert into `categories` (`name`,`image`) VALUES ('$cname','$image');");
		$action="editcategories";
	}

	if($action == "deletecategory"){
		sc_query("delete from `categories` where `id`='$id'");
		$action="editcategories";
	}
	
	if($action == "createnewsgo"){
    $time=date("Y-m-d H:i:s");
    $result=sc_query("INSERT INTO `news` (`headline`, `submitter`,`time`, `published`)
                                  VALUES ('$headline','$data->id','$time','no');");
    echo "<p>News headline entered into database... The story is unpublished.</p>";
    $result=sc_query("select * from news where `headline`='$headline' and `submitter`='$data->id'");
    $news=mysql_fetch_object($result);
    $nid=$news->id;
    $action="ed";
	}

	if($action=="publish"){
		echo "Publishing news article $nid";
		sc_query("update `news` set `published`='yes' where `id`='$nid'");
		$action="edityournews";
	}

	if($action=="unpublish"){
		echo "Unpublishing news article $nid";
		sc_query("update `news` set `published`='no' where `id`='$nid'");
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
		sc_show_news($nid);
		//shownewsarticle($nid,0);
		//editnews($nid);
	}	
}

if($action == "editcategories"){
    if($data->access!=255)    {
        echo smiles("<p>:X</p><p>You can not edit news categories!</p>");
    }
    else {
        echo "<h1>Edit News Categories</h1>";
        $res=sc_query("select * from `categories` order by `name` asc");
        // id, name, image
        $numcats=mysql_num_rows($res);
        echo "<table border=0>";
        echo "<tr><td>Name</td><td>Image</td><td></td><td></td></tr>";
        for($i=0;$i<$numcats;$i++) {
            $cat=mysql_fetch_object($res);
            echo "<form action=news.php enctype=\"multipart/form-data\" method=post><tr><td>";
            echo "<input type=hidden name=action value=modifycategory><input type=hidden name=\"id\" value=\"$cat->id\">";
            echo "<input name=cname value=\"$cat->name\"></td><td>";

            echo "<input type=hidden name=give_file value=news_cat_mod>\n";
            echo "<input type=hidden name=hidden value=yes>\n";
            echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"99900000\">";
            echo "<input name=\"userfile\" type=\"file\">\n";
            echo "</td>";
            echo "<td><img src=\"$cat->image\" width=100 height=24><br>$cat->image</td>";
            echo "<td><input type=submit name=modify value=modify></form></td><td><form enctype=application/x-www-form-URLencoded action=news.php method=post>";
            echo "<input type=hidden name=action value=deletecategory><input type=hidden name=id value=\"$cat->id\">";
            echo "<input type=submit name=delete value=delete></form></td></tr>";
        }
        echo "<form action=news.php enctype=\"multipart/form-data\" method=post><tr><td><input type=hidden name=action value=addcategory>";
        echo "<input name=cname value=\"\"></td><td>";

        echo "<input type=hidden name=give_file value=news_cat_add>\n";
        echo "<input type=hidden name=hidden value=yes>\n";
        echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"99900000\">";
        echo "<input name=\"userfile\" type=\"file\">\n";

        // echo "<input name=image value=\"\">";

        echo "</td>";
        echo "<td><input type=submit name=add value=add></td><td></td></tr></form>";
        echo "</table>";
    }
}

/*
if($action=="edc"){
    sc_showeditcommentform("news",$nid,$cid);
    //end_news_page();
}

if(($action=="decgo")&&
   ($submit=="Yes")) {
    sc_deletecommentgo($nid,$cid);
    //end_news_page();
}

if($action=="dec"){
    sc_showdeletecommentform($nid,$cid);
    //end_news_page();
}
*/

if($action=="edgo_make_wiki"){
	$n=mfo1("select * from news where id='$nid'");
	$n->wiki=addslashes($name);
	sc_query("update news set wiki = '$n->wiki' where id='$nid'");
	$action="edityournews";
}

if(($action=="view") || ($action=="ad")) {

    echo "<table border=0 cellspacing=0 cellpadding=1 width=95%><tr><td>";
    echo "<table border=0 width=100% ><tr>";
    echo "<td valign=top class=td_cat>";
    
	sc_show_news($nid);
	
    // sc_showcomments("news",$nid);
    echo "<br>\n";
    //sc_showaddcommentform($headline,"news",$nid);
    echo "<p align=right><a href=news.php  class=\"a_cat\" align=right>More news stories...</a></p>";
    echo "</td></tr></table>";
    echo "</td></tr></table>";
    echo "<br>";

    /////////////////////////////////////////////////////////////////////////////////////////////////////////

    echo "<table border=0 cellspacing=0 cellpadding=1 width=100%><tr><td>";
    echo "<table border=0 width=100% ><tr>";
    echo "<td valign=top class=td_cat>";

    sc_module_mini_news(10);

    sc_module_popular_news(10);


    echo "<p align=right><a href=news.php  class=\"a_cat\" align=right>More news stories...</a></p>";
    echo "</td></tr></table>";

    echo "</td></tr></table>";


    //end_news_page();
}
/*
if($action=="edcgo") {
    if(($userid=="invalid_user")||
       ($anon=="yes")) {
        sc_updatecommentgo($cid,$headline,$posttext,999);
    }
	else {
        sc_updatecommentgo($cid,$headline,$posttext,$data->id);
    }
    //end_news_page();
} */



if($action=="edityournews"){

    echo "<h1>Editing your news stories</h1>";
    echo "<table border=0 cellspacing=0 cellpadding=5 width=100%><tr><td class=contenttd>";
    echo "<p>Unpublished:</p>";
    echo "<p align=left>";
    $res=sc_query("select * from news where submitter='$data->id' and published='no' order by time desc");
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
    $res=sc_query("select * from news where submitter='$data->id' and published='yes' order by time desc");
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
    $res=sc_query("select * from news where submitter!='$data->id' and published='no' order by time desc");

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
    $res=sc_query("select * from news where submitter!='$data->id' and published='yes' order by time desc");
    $count=mysql_num_rows($res);
    for($i=0;$i<$count;$i++) {
        $news=mysql_fetch_object($res);
        $userdata=sc_getuserdata($news->submitter);
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
