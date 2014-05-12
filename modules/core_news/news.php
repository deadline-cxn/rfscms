<?php

////////////////////////////////////////////////////
// RFSCMS News v4.0

$title="NEWS";
chdir("../../");
include("header.php");

if(!empty($GLOBALS['title'])) if(empty($GLOBALS['headline'])) $GLOBALS['headline']=$GLOBALS['title'];

function put_news_image($fname) { 
    eval(lib_rfs_get_globals());
    $RFS_ADDON_URL=lib_modules_get_url("news");
	$file=$_FILES[$fname]['name'];
    $f_ext=lib_file_getfiletype($file);
    $uploadFile=$RFS_SITE_PATH."/images/news/$file";
    if( ($f_ext=="gif") ||
		($f_ext=="png") ||
		($f_ext=="jpg") ||
		($f_ext=="swf")) {
        $oldname=$file;
        if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadFile)) {
            system("chmod 755 $uploadFile");
            $httppath="$RFS_SITE_PATH/images/news/".$_FILES['userfile']['name'];
			echo "<p>File stored as [<a href=\"$httppath\" target=\"_blank\">$httppath</a>]</p>\n";
        }
    }
    return $httppath;
}


function news_action_give_file() {
	eval(lib_rfs_get_globals());
    if(empty($data->name)) echo "<p>No...</p>\n";
    else {
        $httppath=put_news_image('userfile');
    }
    lib_mysql_query("update `news` set `image_url`='$httppath' where `id`='$nid'");
    news_action_editnews($nid);
}

if(!empty($bot)) {
	if(empty($HTTP_SESSION_VARS['bot'])) {
		$HTTP_SESSION_VARS['bot']=$bot;
	}
}
else {
	$bot=$HTTP_SESSION_VARS['bot'];
}

function news_action_createnews() {
	eval(lib_rfs_get_globals());
	if(!lib_access_check("news","submit")) {
        echo lib_string_convert_smiles("<p>:X</p><p>You can not edit or submit news!</p>");
	}
	else {
		echo "<div class=\"forum_box\">";
		echo "<h1>Submit News</h1>";
		echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"> \n";
		echo "<br>";
		echo "<form enctype=application/x-www-form-URLencoded method=POST action=\"$RFS_ADDON_URL\">\n";
		echo "<input type=hidden name=action value=createnewsgo>";
		echo "<input type=hidden name=showform value=no>";
		echo "<tr><td>Headline:</td><td><input type=\"text\" name=\"headline\" size=\"100\"></td></tr>\n";
		echo "<tr><td></td><td>";
		echo "<input type=\"submit\" value=\"Add News\" class=b4button></td></tr>\n";
		echo "</table></form><br>\n";
		echo "</div>";
    }
}

function news_action_createnewsgo() {
	eval(lib_rfs_get_globals());
	if(lib_access_check("news","submit")) {
		$time=date("Y-m-d H:i:s");
		$result=lib_mysql_query("INSERT INTO `news` (`headline`, `submitter`,`time`, `published`)
									  VALUES ('$headline','$data->id','$time','no');");
		echo "<p>News headline entered into database... The story is unpublished.</p>";
		$result=lib_mysql_query("select * from news where `headline`='$headline' and `submitter`='$data->id'");
		$news=$result->fetch_object($result);
		$nid=$news->id;
	}
	news_action_editnews($nid);
	
}
function news_action_editnewsgo($nid) { 
    eval(lib_rfs_get_globals());
    $RFS_ADDON_URL=lib_modules_get_url("news");
	$p=addslashes($GLOBALS['headline']); lib_mysql_query("UPDATE news SET headline ='$p' where id = '$nid'");
	$name=$_REQUEST['name'];
	if(stristr($name,"Choose a wiki page to use for this news article")) $name="";
	if(stristr($name,"--- NONE ---")) $name="";
	lib_mysql_query("update news set `wiki` = '$name' where `id`='$nid'");

	$p=addslashes($GLOBALS['posttext']);
	lib_mysql_query("UPDATE news SET message ='$p' where id = '$nid'");
	$p=addslashes($GLOBALS['category1']);
	lib_mysql_query("UPDATE `news` SET `category1` ='$p' where id = '$nid'");
	$p=addslashes($GLOBALS['category2']);
	if($p!="none") lib_mysql_query("UPDATE `news` SET `category2` ='$p' where id = '$nid'");
	$p=addslashes($GLOBALS['category3']);
	if($p!="none") lib_mysql_query("UPDATE `news` SET `category3` ='$p' where id = '$nid'");
	$p=addslashes($GLOBALS['category4']);
	if($p!="none") lib_mysql_query("UPDATE `news` SET `category4` ='$p' where id = '$nid'");

	$p=$GLOBALS['topstory'];
	if($p=="yes") {
		lib_mysql_query("update news set topstory='no'");
		lib_mysql_query("update news set topstory='yes' where id='$nid'");
	}

	$p=$GLOBALS['published'];
	if($p=="yes") lib_mysql_query("update news set published='yes' where id='$nid'");
	else          lib_mysql_query("update news set published='no' where id='$nid'");

	echo "<p>News article [$nid] has been updated...</p>\n";
	$loggit="*****> ".$GLOBALS['data']->name." updated news article $nid...";
	
}

function news_action_editnews($nid) { 
    eval(lib_rfs_get_globals());
    $RFS_ADDON_URL=lib_modules_get_url("news");
	$res=lib_mysql_query("select * from news where id='$nid'")
    $news=$res->fetch_object();    
	echo "<a href=$RFS_ADDON_URL?action=view&nid=$nid>Preview</a>";
	
	$news->image_url=str_replace("$RFS_SITE_PATH/","",$news->image_url);
	$news->image_url=str_replace("$RFS_SITE_URL/","",$news->image_url);
	
    if(!file_exists("$RFS_SITE_PATH/".ltrim($news->image_url,"/"))) {
		 $oldimage=$news->image_url;
        $news->image_url="$RFS_SITE_URL/images/icons/404.png";	
	}
	$outimage=$news->image_url;
    if(empty($news->image_url)) {
		$outimage="$RFS_SITE_URL/images/icons/news.png";
	}
    if(!stristr($outimage,$RFS_SITE_URL))
        $outimage=$RFS_SITE_URL."/".ltrim($outimage,"/");
    
    echo "<table border=0 width=100%><tr><td>";
    
	echo "<img src=\"";
	echo lib_images_thumb_raw($outimage,100,0,1); 
	echo "\" border=\"0\" title = '$altern' alt='$altern' align=left class='rfs_thumb'>";

    echo "</td><td>";
    echo "<table border=0><tr>";
    echo "<td align=left>";	
	
	if(!empty($news->image_url)) {
		if(!stristr($news->image_url,":"))
			$news->image_url=$RFS_SITE_URL."/$news->image_url";			
		echo "($news->image_url)";		
		
		echo "<table border=0>\n";
		echo "<form enctype=\"multipart/form-data\" action=\"$RFS_ADDON_URL\" method=\"post\">\n";
		echo "<input type=hidden name=action value=clearnewsimage>\n";
		echo "<input type=hidden name=nid value=$nid>";
		
		echo "<tr><td>Clear current image: <input type=\"submit\" name=\"submit\" value=\"Clear\"></td></tr>\n";
		echo "</form>\n";
		echo "</table>\n";		
	}	
	else {
		

	
	
	
    echo "<p>150 x 150</p>";
    echo "Enter a url";
    echo "<table border=0>\n";	
    echo "<form enctype=application/x-www-form-URLencoded ";
    echo " enctype=\"multipart/form-data\" action=\"$RFS_ADDON_URL\" method=\"post\">\n";
    echo "<input type=hidden name=action value=imageurl>\n";
    echo "<input type=hidden name=nid value=$nid>";
    echo "<tr><td><input name=\"userfile\"> </td><td>";
    echo "<input type=\"submit\" name=\"submit\" value=\"URL\"></td></tr>\n";
    echo "</form>\n";
    echo "</table>\n";
    echo "Or select a file to upload";
    echo "<table border=0>\n";
    echo "<form enctype=\"multipart/form-data\" action=\"$RFS_ADDON_URL\" method=\"post\">\n";
	echo "<input type=hidden name=action value=give_file>";
    echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"99900000\">";
    echo "<input type=hidden name=local value=\"images/news\">";
    echo "<input type=hidden name=hidden value=yes>\n";
    echo "<input type=hidden name=nid value=$nid>";
    echo "<tr><td><input name=\"userfile\" type=\"file\"> </td><td><input type=\"submit\" name=\"submit\" value=\"Upload!\"></td></tr>\n";
    echo "</form>\n";
    echo "</table>\n";

    
    
	
	}
	echo "</td>";
    echo "</tr></table>";
	echo "</td>";
    echo "</tr></table>";

    echo "<form enctype=application/x-www-form-URLencoded method=post action=\"$RFS_ADDON_URL\">\n";
    echo "<table border=0>";
	
	
	echo "<tr><td>";
	
    echo "Select a wiki page to use instead of text </td><td>";
    
	$wikistatus=$news->wiki;
	if(empty($wikistatus))
		$wikistatus="Choose a wiki page to use for this news article";
    
	lib_forms_optionize("INLINE",
					"nid=$nid",
					"wiki",
					"name",
					0,
					$wikistatus,
					1);
                    
		echo "</td></tr></table>";
					
	
    echo "<table border=0 width=100%>";
    echo "<input type=\"hidden\" name=\"action\" value=\"editnewsgo\">\n";
    echo "<input type=\"hidden\" name=\"nid\" value=\"$nid\">\n";
	
    echo "<tr><td>Headline</td><td><input name=headline value=\"$news->headline\" size=100></td></tr>\n";
	
	
	if(empty($news->wiki))	{
		$otxt=$news->message;
		$otxt=str_replace("<","&lt;",$otxt);
		$otxt=stripslashes($otxt);
		echo "<tr><td>Message</td><td>
			<textarea 
				cols=\"70\" 
				rows=\"30\" 
				style=\"width: 80%;\"
				name=\"posttext\" >$otxt</textarea>
			</td></tr>\n";		
	}
	else echo "<tr><td>WIKI PAGE:</td><td>$news->wiki</td></tr>";
    
	echo "<tr><td>Top Story:   </td><td>
	<select name=topstory>";
	echo "<option>$news->topstory";
	echo "
	<option>no<option>yes
	</select></td></tr>\n";
	
    echo "<tr><td>Publish:   </td><td><select name=published>";
    echo "<option>$news->published";
    echo "<option>no<option>yes</select></td></tr>\n";
    echo "<tr><td>Main Category:</td><td><select name=category1>";
    if(!empty($news->category1)) echo "<option>$news->category1";
    $res=lib_mysql_query("select * from `categories` order by `name` asc");
    $ncats=$res->num_rows;
    for($i=0;$i<$ncats;$i++) {
        $cat=$res->fetch_object($res);
        echo "<option>$cat->name";
    }
    echo "</select></td></tr>\n";
    echo "<tr><td>Sub Category 1:</td><td><select name=category2>";
    if(!empty($news->category2)) echo "<option>$news->category2";
    echo "<option>none";
    $res=lib_mysql_query("select * from `categories` order by `name` asc");
    $ncats=$res->num_rows;
    for($i=0;$i<$ncats;$i++) {
        $cat=$res->fetch_object();
        echo "<option>$cat->name";
    }
    echo "</select></td></tr>\n";
    echo "<tr><td>Sub Category 2:</td><td><select name=category3>";
    if(!empty($news->category3)) echo "<option>$news->category3";
    echo "<option>none";
    $res=lib_mysql_query("select * from `categories` order by `name` asc");
    $ncats=$res->num_rows;
    for($i=0;$i<$ncats;$i++) {
        $cat=$res->fetch_object();
        echo "<option>$cat->name";
    }
    echo "</select></td></tr>\n";
    echo "<tr><td>Sub Category:</td><td><select name=category4>";
    if(!empty($news->category4)) echo "<option>$news->category4";
    echo "<option>none";
    $res=lib_mysql_query("select * from `categories` order by `name` asc");
    $ncats=$res->num_rows;
    for($i=0;$i<$ncats;$i++) {
        $cat=$res->fetch_object();
        echo "<option>$cat->name";
    }
    echo "</select></td></tr>\n";
    echo "<tr><td>&nbsp; </td><td><input type=\"submit\" value=\"Update News\" class=b4button></td></tr>\n";
    echo "</form></table>\n";
	
	// echo " </td></tr></table>\n";
}


function news_action_clearnewsimage($nid) {
	eval(lib_rfs_get_globals());
	if(lib_access_check("news","edit")) {
		lib_mysql_query("update news set image_url='' where id='$nid'");
		news_action_editnews($nid);	
	}
}

function news_action_imageurl() {
	eval(lib_rfs_get_globals());
		if(lib_access_check("news","edit")) {
		lib_mysql_query("update news set image_url='$userfile' where id='$nid'");
		news_action_editnews($nid);
	}
}

function news_action_publish($nid){
	eval(lib_rfs_get_globals());
	if(lib_access_check("news","publish")) {
		echo "Publishing news article $nid";
		lib_mysql_query("update `news` set `published`='yes' where `id`='$nid'");
		news_action_edityournews();
	}
}

function news_action_unpublish(){
	eval(lib_rfs_get_globals());
	if(lib_access_check("news","unpublish")) {
		echo "Unpublishing news article $nid";
		lib_mysql_query("update `news` set `published`='no' where `id`='$nid'");
		news_action_edityournews();
	}
}


function news_action_deletenews($nid) {
    eval(lib_rfs_get_globals());
	if(lib_access_check("news","delete")) {
		$RFS_ADDON_URL=lib_modules_get_url("news");
		echo "<table border=\"0\" align=center><tr><td class=\"lib_forms_warning\"><center>".lib_string_convert_smiles(":X")."\n";
		echo "<br>WARNING:<br>The news article will be completely removed are you sure?</center>\n";
		echo "</td></tr></table>\n";
		echo "<table align=center><tr><td><form enctype=application/x-www-form-URLencoded action=\"$RFS_ADDON_URL\">\n";
		echo "<input type=hidden name=action value=deletenewsgo><input type=hidden name=nid value=$nid>\n";
		echo "<input type=\"submit\" name=\"submit\" value=\"Yes\"></form></td>\n";
		echo "<td><form enctype=application/x-www-form-URLencoded action=\"$RFS_ADDON_URL\"><input type=\"submit\" name=\"no\" value=\"No\"></form></td></tr></table>\n";
	}
}
function news_action_deletenewsgo($nid){
    eval(lib_rfs_get_globals());
	if(lib_access_check("news","delete")) {
		$RFS_ADDON_URL=lib_modules_get_url("news");
		lib_mysql_query("DELETE FROM news where id = '$nid'");
		echo "<p>News article $nid has been deleted...</p>\n";
		$loggit="*****> ".$GLOBALS['data']->name." deleted news article $nid...";
		lib_log_add_entry($loggit);
	}
}

function news_action_view() {
	eval(lib_rfs_get_globals());
	rfs_show_news($nid);
    echo "<br>\n";
    echo "<p align=right><a href=\"$RFS_ADDON_URL\"  class=\"a_cat\" align=right>More news stories...</a></p>";
    echo "<br>";
    module_news_list(10);
    echo "<p align=right><a href=\"$RFS_ADDON_URL\" class=\"a_cat\" align=right>More news stories...</a></p>";
}

function news_action_edityournews(){
	eval(lib_rfs_get_globals());
	if(lib_access_check("news","edit")) {
    echo "<h1>Editing your news stories</h1>";
	lib_buttons_make_button("$RFS_ADDON_URL?showform=yes","Submit new news article");
    echo "<table border=0 cellspacing=0 cellpadding=5 width=100%><tr><td class=contenttd>";
    echo "<p>Unpublished:</p>";
    echo "<p align=left>";
    $res=lib_mysql_query("select * from news where submitter='$data->id' and published='no' order by time desc");
    $count=$res->num_rows;
    for($i=0;$i<$count;$i++) {
        $news=$res->fetch_object();
        echo "[<a href=\"$RFS_ADDON_URL?action=deletenews&nid=$news->id\">Delete</a>] ";
        echo "[<a href=\"$RFS_ADDON_URL?action=editnews&nid=$news->id\">Edit</a>] ";
        echo "[<a href=\"$RFS_ADDON_URL?action=publish&nid=$news->id\">Publish</a>] ";
        echo " <a href=\"$RFS_ADDON_URL?action=view&nid=$news->id\">link: $news->headline</a><br>";
    }
    echo "</p>";
    echo "<p>Published:</p>";
    echo "<p align=left>";
    $res=lib_mysql_query("select * from news where submitter='$data->id' and published='yes' order by time desc");
    $count=$res->num_rows;
    for($i=0;$i<$count;$i++) {
        $news=$res->fetch_object();
        echo "[<a href=\"$RFS_ADDON_URL?action=deletenews&nid=$news->id\">Delete</a>] ";
        echo "[<a href=\"$RFS_ADDON_URL?action=editnews&nid=$news->id\">Edit</a>] ";
        echo "[<a href=\"$RFS_ADDON_URL?action=unpublish&nid=$news->id\">Unpublish</a>] ";
        echo " <a href=\"$RFS_ADDON_URL?action=view&nid=$news->id\">link: $news->headline</a><br>";
    }
    echo "</p>";

    echo "<p>Other people's news stories:</p>";

    echo "<p>Unpublished:</p>";
    echo "<p align=left>";
    $res=lib_mysql_query("select * from news where submitter!='$data->id' and published='no' order by time desc");

    $count=$res-num_rows;
    for($i=0;$i<$count;$i++) {
        $news=$res->fetch_object();
        $userdata=getuserdata($news->submitter);
        echo "[<a href=\"$RFS_ADDON_URL?action=deletenews&nid=$news->id\">Delete</a>] ";
        echo "[<a href=\"$RFS_ADDON_URL?action=editnews&nid=$news->id\">Edit</a>] ";
        echo "[<a href=\"$RFS_ADDON_URL?action=publish&nid=$news->id\">Publish</a>] ";
        echo " <a href=\"$RFS_ADDON_URL?action=view&nid=$news->id\">link: $news->headline</a> ($userdata->name)<br>";
    }
    echo "</p>";

    echo "<p>Published:</p>";

    echo "<p align=left>";
    $res=lib_mysql_query("select * from news where submitter!='$data->id' and published='yes' order by time desc");
    $count=$res->num_rows;
    for($i=0;$i<$count;$i++) {
        $news=$res->fetch_object();
        $userdata=lib_users_get_data($news->submitter);
        echo "[<a href=\"$RFS_ADDON_URL?action=deletenews&nid=$news->id\">Delete</a>] ";
        echo "[<a href=\"$RFS_ADDON_URL?action=editnews&nid=$news->id\">Edit</a>] ";
        echo "[<a href=\"$RFS_ADDON_URL?action=unpublish&nid=$news->id\">Unpublish</a>] ";
        echo " <a href=\"$RFS_ADDON_URL?action=view&nid=$news->id\">link: $news->headline</a> ($userdata->name)<br>";
    }
    echo "</p>";
    echo "</td></tr></table>";
	}
}

function news_action_() {
	shownews();
}

include("footer.php");

?>
