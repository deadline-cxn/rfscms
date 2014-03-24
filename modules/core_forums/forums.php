<?
$title="FORUMS";
chdir("../../");
$RFS_LITTLE_HEADER=true;
include("header.php");
$message=str_replace("<meta","(meta tags are unauthorized)<no ",$message);
$message=str_replace("<input","(form tags are unauthorized)<no ",$message);
$message=str_replace("<form","(form tags are unauthorized)<no ",$message);
$message=str_replace("<textarea","(form tags are unauthorized)<no ",$message);
$message=str_replace("<select","(form tags are unauthorized)<no ",$message);

if(lib_rfs_bool_true($_SESSION['forum_admin'])) {
    if(lib_access_check("admin","forums")) {
		echo "<p> ".lib_string_convert_smiles(":X")." You do not have access to the Forum Administration Panel (FAP)!</p>";
		$_SESSION['forum_admin']=="no";
	}
}
function forums_buttons($forum_which) { 
	eval(lib_rfs_get_globals());
	if($forum_list!="yes") {
        echo "[<a href=\"$RFS_SITE_URL/modules/core_forums/forums.php\">List Forums</a>]";
        echo "[<a href=\"$RFS_SITE_URL/modules/core_forums/forums.php?action=forum_showposts&forum_which=$forum_which\">List Threads</a>]";
        echo "[<a href=\"$RFS_SITE_URL/modules/core_forums/forums.php?action=start_thread&forum_which=$forum_which\">Start New Thread</a>]";
    }
	if(lib_access_check("forums","admin")) {
        if($_SESSION['forum_admin']=="yes")
			echo "[<a href=\"$RFS_SITE_URL/modules/core_forums/forums.php?action=forum_admin_off&forum_which=$forum_which\">Forum Admin Off</a>]";
        else
			echo "[<a href=\"$RFS_SITE_URL/modules/core_forums/forums.php?action=forum_admin_on&forum_which=$forum_which\">Forum Admin On</a>]";
    }
	echo "<hr>";
}
function forums_bumpthread($id) { $bumptime=date("Y-m-d H:i:s"); lib_mysql_query("update forum_posts set `bumptime`='$bumptime' where id='$id'"); }
function forums_action_forum_admin_on()  { $_SESSION['forum_admin']="yes"; forums_action_forum_showposts(); }
function forums_action_forum_admin_off() { $_SESSION['forum_admin']="no";  forums_action_forum_showposts(); }
function forums_action_start_thread() {
	eval(lib_rfs_get_globals());
	$folder=mysql_fetch_object(lib_mysql_query("select * from `forum_list` where `id`='$forum_which';"));   
    echo "<h1>$folder->name</h1>";
	forums_buttons($forum_which);
    if($logged_in=="true") {
	    $folder=mysql_fetch_object(lib_mysql_query("select * from `forum_list` where `id`='$forum_which';"));	
		echo "<div class=\"forum_box\">";
	    echo "<h2>Start a new thread in $folder->name</h2>";
        echo "<form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/modules/core_forums/forums.php\" method=post>\n";
        echo "<input type=hidden name=action value=start_thread_go>\n";
        echo "<input type=hidden name=forum_which value=\"$forum_which\">\n";
		echo "Title: ";
		echo "<input type=text name=\"theader\" value=\"\" size=78><br>";
		echo "Message:";
		echo "<textarea name=reply cols=78 rows=10></textarea><br>";
        if($data->name=="Visitor") { 
			echo "Name:";
			echo "<input type=hidden name=visitor value=true>";
        }
        else {
			echo "Anonymous:";
			echo "	<select id=\"anonymous\" name=anonymous
						style=\"width:160px; min-width: 160px;\"
						width=160>
			<option>no<option>yes</select> &nbsp;<input type=submit name=submit value=\"Go!\">";
			}
		echo "</form>\n";
    }
    else  {
		echo "<h2>You must be logged in to post!</h2>\n";
	}
	echo "</div>";
	include("footer.php");
}
function forums_action_start_thread_go() {
	eval(lib_rfs_get_globals());
    if($logged_in=="true") {
		
        $user=$data->id;
        if($anonymous=="yes") $user=999;
        if($data->name=="Visitor") $user=999;
        $time=date("Y-m-d H:i:s");
        lib_mysql_query("INSERT INTO `forum_posts` (`id`, `title`) VALUES ('', '__chkdel');");
        $fart=lib_mysql_query("select * from `forum_posts` where `title`='__chkdel'");
        $lick=mysql_fetch_array($fart);
        $id=$lick['id'];
        $thread=$id;
        $theader=addslashes($theader);
        $reply=addslashes($reply);
        lib_mysql_query("UPDATE `forum_posts` set `poster`       = '$user' where `id`='$id';");
        lib_mysql_query("UPDATE `forum_posts` set `poster_name`  = '$name' where `id`='$id';");
        lib_mysql_query("UPDATE `forum_posts` set `title`        = '$theader' where `id`='$id';");
        lib_mysql_query("UPDATE `forum_posts` set `message`      = '$reply' where `id`='$id';");
        lib_mysql_query("UPDATE `forum_posts` set `thread`       = '$thread' where `id`='$id';");
        lib_mysql_query("UPDATE `forum_posts` set `forum`        = '$forum_which' where `id`='$id';");
        lib_mysql_query("UPDATE `forum_posts` set `time`         = '$time' where `id`='$id';");
        lib_mysql_query("UPDATE `forum_posts` set `thread_top`   = 'yes' where `id`='$id';");
        lib_mysql_query("DELETE from `forum_posts` where `title` ='__chkdel'");
        $data->forumposts+=1;
        lib_mysql_query("update `users` set `forumposts`='$data->forumposts' where `id`='$data->id'");
        lib_mysql_query("UPDATE `forum_list` set `last_post` = '$id' where `id` = '$forum_which';");
        forums_bumpthread($id);
        lib_log_add_entry("*****> $data->name started a new thread! [$header]");
    }
    else echo "<p class=rfs_site_urlr>You must be logged in to post or reply!</p>\n";
    forums_action_get_thread($thread,$forum_which);
}
function forums_get_message($post) {
	eval(lib_rfs_get_globals());
	$poster=lib_users_get_data($post['poster']);
	$forum=mysql_fetch_object(lib_mysql_query("select * from forum_list where id=".$post['forum']));
	if(	($forum->private=="yes") &&
		(($logged_in!="true") || (lib_access_check("forums","admin"))) ) {
		echo "<p>You don't have access to this forum.</p>";
	}
	else {
		echo "<a id=\"".$post['id']."\"></a>";
		echo "<div class=\"forum_box\">";
        echo "<h2>".$post['title']."</h2>";
		echo "<div class=\"rfs_forum_table_1\">";
		echo "<div class=\"forum_user\" >";
		echo lib_users_avatar_code($poster->name);
		echo "<br><a href=\"$RFS_SITE_URL/modules/profile/showprofile.php?user=$poster->name\">$poster->name</a><br>";
		echo "posts:$poster->forumposts<br>";
		echo "replies:$poster->forumreplies";
		echo "</div>";
		echo  "</div>";
		echo "<div class=\"forum_message\">";
		echo lib_string_convert_smiles(wikitext(stripslashes($post['message'])));
		echo "</div>";
		echo "<div class=\"forum_time\">";
        $time=lib_string_current_time($post['time']);
        echo "posted $time by <a href=\"$RFS_SITE_URL/modules/profile/showprofile.php?user=$poster->name\">$poster->name</a> ";
        if($logged_in=="true") {
            if(($poster->name==$data->name) || (lib_access_check("forums","admin"))) {
				
				if(lib_rfs_bool_true($post['thread_top'])) {
					echo "[<a href=\"$RFS_SITE_URL/modules/core_forums/forums.php?action=delete_post_s&forum_which=".$post['forum']."&reply=".$post['id']."&thread=".$post['thread']."\">delete</a>] ";
					echo "[<a href=\"$RFS_SITE_URL/modules/core_forums/forums.php?action=edit_reply&forum_which=".$post['forum']."&reply=".$post['id']."&thread=".$post['thread']."\">edit</a>] ";
				}
				else {
					echo "[<a href=\"$RFS_SITE_URL/modules/core_forums/forums.php?action=delete_reply_s&forum_which=".$post['forum']."&reply=".$post['id']."&thread=".$post['thread']."\">delete</a>] ";
					echo "[<a href=\"$RFS_SITE_URL/modules/core_forums/forums.php?action=edit_reply&forum_which=".$post['forum']."&reply=".$post['id']."&thread=".$post['thread']."\">edit</a>] ";
				}
				
            }
        }
		echo "</div>";
		echo "</div>";
    }
}
function forums_action_get_thread($thread) {
	eval(lib_rfs_get_globals());
	$result = lib_mysql_query("select * from `forum_posts` where `thread_top`='yes' and `thread`='$thread' order by time limit 0,30");
	if($result) 	$numposts=mysql_num_rows($result);
	if($numposts>0) $post=mysql_fetch_array($result);
	if(empty($forum_which)) {
		$th=lib_mysql_fetch_one_object("select * from `forum_posts` where `thread`='$thread'");
		$forum_which=$th->forum;
	}
	if($forum_which!=$post['forum']) {
		echo "<p>Error! This post or reply has been moved or deleted.</p>";
		return;
	}
	$forum_which=$post['forum'];
	$folder=mysql_fetch_array(lib_mysql_query("select * from `forum_list` where `id`='$forum_which';"));
	$title=stripslashes($post['title']);
	$thread=$post['id'];
   	$folder=mysql_fetch_object(lib_mysql_query("select * from `forum_list` where `id`='$forum_which';"));
    echo "<h1>$folder->name >> $title</h1>";
	forums_buttons($forum_which);
	$GLOBALS['forum_list']="no";
	if($numposts>0) {
		$views=$post['views']+1;
		lib_mysql_query("update `forum_posts` set `views` ='$views' where `thread_top`='yes' and `thread`='$thread'");
		forums_get_message($post,$gx);
		$thread_res=lib_mysql_query("select * from `forum_posts` where `forum`='$forum_which' and `thread`='$thread' and `thread_top`='no' order by time limit 0,30");
		while($post = mysql_fetch_array($thread_res)) {
				forums_get_message($post);
			}
		}
        if($logged_in=="true") {
				echo "<div class=\"forum_box\">";
				echo "<h2>Reply</h2>";				
				echo "<form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/modules/core_forums/forums.php\" method=post>\n";
				echo "<input type=hidden name=action value=reply_to_thread>\n";
				echo "<input type=hidden name=forum_which value=\"$forum_which\">\n";
				echo "<input type=hidden name=thread value=\"".$thread."\">\n";				
				echo "Title:<input type=text name=theader value=\"re:";				
				echo stripslashes($title);
				echo "\" size=78>";
				echo "<div class=forum_message>";
				echo "<textarea name=reply rows=15 style='width: 100%;'></textarea>";
				echo "</div>";
				echo "<br>Anonymous:";
				echo "<select id=\"anonymous\" name=anonymous style=\"width:160px; min-width: 160px;\" width=160><option>no<option>yes</select> &nbsp;<input type=submit name=submit value=\"Go!\">";
				echo "</form>\n";
				echo "</div>";
        }
		else {
            echo "<p class=rfs_site_urlr><a href=$RFS_SITE_URL/login.php>Login</a> to reply to this post!</p>\n";
        }
    echo "<br>\n";
	include("footer.php");
}
function forums_action_move_thread() {
	eval(lib_rfs_get_globals());
    $tofor=lib_mysql_query("select * from forum_list where name='$move'");
    $toforum=mysql_fetch_object($tofor);
    lib_mysql_query("update forum_posts set forum='$toforum->id' where id='$id'");
    lib_mysql_query("update forum_posts set forum='$toforum->id' where thread='$id'");
	$thread=lib_mysql_fetch_one_object("select * from forum_posts where id='$id'");
    echo "<p style='color:white; background-color:green;'>Moved thread $id ($thread->title) to forum $toforum->id ($move)</p>";
    forums_action_get_thread($id,$toforum->id);
}
function forums_action_delete_post_s() {
	eval(lib_rfs_get_globals());
    if($logged_in=="true") {
        echo "<table border=\"0\" align=center><tr><td class=\"lib_forms_warning\"><center>".
		lib_string_convert_smiles("^X")."\n";
        echo "<br>WARNING:<br>The forum post and ALL replies will be completely removed are you sure?</center>\n";
        echo "</td></tr></table>\n";
        echo "<table align=center><tr><td>
			<form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/modules/core_forums/forums.php\">\n";
        echo "	<input type=hidden name=action value=delete_post>
				<input type=hidden name=thread value=\"$thread\">
				<input type=hidden name=forum_which value=\"$forum_which\">
				
				<input type=hidden name=reply value=$reply>\n";
        echo "<input type=\"submit\" name=\"submit\" value=\"Delete!\"></form></td>\n";
		
        echo "<td>
		<form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/modules/core_forums/forums.php\">
		<input type=hidden name=action value=get_thread>
		<input type=hidden name=thread value=\"$thread\">
		<input type=\"submit\" name=\"no\" value=\"No\"></form>
		</td></tr></table>\n";    
    }
}
function forums_action_delete_post() {
	eval(lib_rfs_get_globals());
    if($logged_in=="true") {
       lib_mysql_query("delete from forum_posts where thread='$thread';");
       lib_forms_warn("<h2><font color=red>Post was deleted...</font></h2>");
		$forum_list="no";
		forums_action_forum_showposts();
		// forums_action_get_thread($thread,$forum_which);
    }
}
function forums_action_delete_reply_s() {
	eval(lib_rfs_get_globals());
    if($logged_in=="true") {
        echo "<table border=\"0\" align=center><tr><td class=\"lib_forms_warning\">
				<center>".lib_string_convert_smiles("^X")."\n";
        echo "<br>WARNING:<br>The forum reply will be completely removed are you sure?</center>\n";
        echo "</td></tr></table>\n";
        echo "<table align=center><tr><td>
				<form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/modules/core_forums/forums.php\">\n";
        echo "  <input type=hidden name=action value=delete_reply>
				<input type=hidden name=thread value=\"$thread\">
				<input type=hidden name=reply value=\"$reply\">\n";
        echo "<input type=\"submit\" name=\"submit\" value=\"Yes\"></form></td>\n";
        echo "<td><form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/modules/core_forums/forums.php\">
				<input type=hidden name=action value=get_thread>
				<input type=hidden name=thread value=\"$thread\">
				<input type=\"submit\" name=\"no\" value=\"No\"></form></td></tr></table>\n";
    }
}
function forums_action_delete_reply() {
	eval(lib_rfs_get_globals());
    if($logged_in=="true") {
        lib_mysql_query("delete from `forum_posts` where id='$reply';");
        echo "<h2><font color=red>Reply [$reply] was deleted...</font></h2>";
        forums_action_get_thread($thread);
    }
	
}
function forums_action_edit_reply() {
	eval(lib_rfs_get_globals());
    if($logged_in=="true") {
        $posttt=lib_mysql_query("select * from forum_posts where id='$reply';");
        $post=mysql_fetch_object($posttt);
        $fw=$forum_which;
		echo "<div class=\"forum_box\">";
		echo "<h2>Editing reply #$reply: $post->title</h2>";
        echo "<table border=0 width=100%>\n";
        echo "<form enctype=application/x-www-form-URLencoded action=$RFS_SITE_URL/modules/core_forums/forums.php method=post>\n";
        echo "<input type=hidden name=action value=edit_reply_go>\n";
        echo "<input type=hidden name=reply value=$reply>\n";
        echo "<input type=hidden name=forum_which value=$forum_which>\n";
        echo "<input type=hidden name=thread value=$thread>\n";
        echo "<tr><td align=right>Message Title:</td><td><input type=text name=theader value=\"";
	    echo stripslashes($post->title);
	    echo "\"></td></tr>\n";
        echo "<tr><td align=right>Message:</td><td><textarea name=message cols=110 rows=20>";
	    echo stripslashes($post->message);
	    echo "</textarea></td></tr>\n";
        echo "<tr><td>&nbsp;</td><td><input type=submit name=submit value=go></td></tr>\n";
        echo "</form></table>\n";
		echo "</div>";
    }
}
function forums_action_edit_reply_go(){
	eval(lib_rfs_get_globals());
    if($logged_in=="true")    {
		$message=$_POST['message'];
		$message=addslashes($message);
		$theader=addslashes($theader);
		lib_mysql_query("UPDATE forum_posts SET message = '$message' where id = '$reply'");
		lib_mysql_query("UPDATE forum_posts SET title   = '$theader'   where id = '$reply'");
		forums_action_get_thread($thread,$forum_which);
		exit();
    }
}
function forums_action_reply_to_thread() {
	eval(lib_rfs_get_globals());
    if($logged_in=="true")    {
        $user=$data->id; if($anonymous=="yes") $user=999;
        $time=date("Y-m-d H:i:s");
        $theader=addslashes($theader);
        $reply=addslashes($reply);
        $query  = "INSERT  INTO  `forum_posts` ";
        $query .= "( `id`, `poster`, `title`, `message`, `thread`, `forum`, `time`, `thread_top` ) ";
        $query .= "VALUES (  '',  '$user',  '$theader',  '$reply',  '$thread',  '$forum_which',  '$time',  'no' );";
        lib_mysql_query($query);
        $data->forumreplies+=1;
        lib_mysql_query("update `users` set `forumreplies`='$data->forumreplies' where `id`='$data->id'");
        $posts=lib_mysql_query("select * from `forum_posts` order by `time` desc limit 1");
        $post=mysql_fetch_object($posts); 
        lib_mysql_query("UPDATE `forum_list` set `last_post` = '$post->id' where `id` = '$forum_which';");
        forums_bumpthread($post->thread);
        lib_log_add_entry("*****> $data->name replied to thread [$theader]");
    }
	else {
        echo "<p class=rfs_site_urlr><a href=$RFS_SITE_URL/login>Login</a> to reply</p>\n";
    }
	forums_action_get_thread($thread,$forum_which);
}
function forums_action_forum_list() {
	eval(lib_rfs_get_globals());
	echo "<h1>Forums</h1>";
    $folder_res = lib_mysql_query("select * from forum_list where `folder`='yes' order by priority");
    while($dfold=mysql_fetch_object($folder_res)) {
		$seefolder=false;		
		if(lib_access_check("forums","admin")) {
			$seefolder=true;
		}
		else {
			$agar=explode(",",$dfold->access_groups); $agarc=count($agar);
			$uagar=explode(",",$data->access_groups); $uagarc=count($uagar);
			for($agari=0;$agari<$agarc;$agari++)
			for($uagari=0;$uagari<$uagarc;$uagari++) {
				if(empty($agar[$agari]) || empty($uagar[$uagari])) {}
				else if($agar[$agari]==$uagar[$uagari]) $seefolder=true;
			}
		}
		$seefolder=true;
		if($seefolder==true) {
			echo "<div class=\"forum_box\" >";
			echo "<table border=0 cellpadding=0 cellspacing=0>";				
			echo "<tr>
			<td class=forum_table_head>$dfold->name</td>
			<td class=forum_table_head>Moderator</td>
			<td class=forum_table_head>Topics</td>
			<td class=forum_table_head>Posts</td>
			<td class=forum_table_head>Last Post</td>
			</tr>";
			$result = lib_mysql_query("select * from forum_list where `parent`='$dfold->id' order by priority");
			while($folder=mysql_fetch_object($result)) {
				
				$name=stripslashes($folder->name);
				$comment=stripslashes($folder->comment);
				$moder=$folder->moderator;
				$dumpforum=1;
				if(($folder->private=="yes")&&($data->access<254)) $dumpforum=0;
				
				$agar=explode(",",$folder->access_groups); $agarc=count($agar);
				$uagar=explode(",",$data->access_groups); $uagarc=count($uagar);
				for($agari=0;$agari<$agarc;$agari++)
				for($uagari=0;$uagari<$uagarc;$uagari++) {
					if( (empty($agar[$agari])) ||
						(empty($uagar[$uagari])) ) {}		            	    
					else if($agar[$agari]==$uagar[$uagari]){
						$dumpforum=1;
					 }
				}
				
				$new=0;
				if($dumpforum) {
					$forum_r=lib_mysql_query("select * from forum_posts where `forum`= '$folder->id' and `thread_top`='yes'");
					while($timecheck=mysql_fetch_array($forum_r)) {
						if($timecheck['time']>=$data->last_login) $new=1;
						$thread_r=lib_mysql_query("select * from forum_posts where `thread`=".($fart['thread']));
						while($thread_time_check=mysql_fetch_array($thread_r)) {
							if($thread_time_check['time']>=$data->last_login) $new=1;
						}
					}
					$link="$RFS_SITE_URL/modules/core_forums/forums.php?forum_which=$folder->id&action=forum_showposts";
					$alttxt="No new posts";
					$folder_filename="folder_big.gif";
					if($new==1) {
						$folder_filename="folder_new_big.gif";
						$alttxt="New posts";
					}
					echo"<tr><td width=500>";					
					echo "<table><tr><td>";							
					$folderpic=lib_themes_get_image("images/icons/$folder_filename");
					echo "<a href=\"$link\" class=\"forumlink\">";
					echo "<img src=\"$folderpic\" alt=\"$alttxt\" title=\"$alttxt\" border=0>";
					echo "</a>";							
					echo "</td><td>";
					echo "<a href=\"$link\" class=\"forumlink\">$name</a>";
					echo "<br> $comment";							
					echo "</td></tr></table>";
					
					echo "</td>";
					echo "<td>";
					
					if($folder->moderated=="yes") {
						echo "<b>Moderator [</b>";
						$foruser=lib_users_get_data($moder);
						echo "<a href=$RFS_SITE_URL/modules/profile/showprofile.php?user=$foruser->name>$foruser->name</a>]";
					}
					else {
						echo "no one";
					}
					
					echo "</td>";
					echo "<td>";
					

					$topres=lib_mysql_query("select id from `forum_posts` where `forum`= '$folder->id' and `thread_top`='yes'");
					$topics=mysql_num_rows($topres);
					$postres=lib_mysql_query("select id from `forum_posts` where `forum`='$folder->id';");
					$posts=mysql_num_rows($postres);
					echo "$topics";
					echo "</td>";
					echo "<td>";
					echo "$posts";
					echo "</td>";
					echo "<td>";
					$last_post_r=lib_mysql_query("select * from `forum_posts` where `forum` = '$folder->id' order by time desc limit 1");					
					if($lastpost=mysql_fetch_object($last_post_r)) {
						$link="$RFS_SITE_URL/modules/core_forums/forums.php?action=get_thread&thread=$lastpost->id";
						$link="$RFS_SITE_URL/modules/core_forums/forums.php?forum_list=no&action=get_thread&thread=$lastpost->thread&forum_which=$lastpost->forum";
						echo "<a href=\"$link\" title=\"$lastpost->title\">$lastpost->title</a><br>";
						echo lib_string_current_time($lastpost->time);
						$udata=lib_users_get_data($lastpost->poster);
						echo " by <a href=\"$RFS_SITE_URL/modules/profile/showprofile.php?user=$udata->name\">$udata->name</a>";
						echo " <a href=\"$link\"><img src=\"$RFS_SITE_URL/images/icons/icon_latest_reply.gif\" width=\"18\" height=\"9\" class=\"imgspace\" border=\"0\" alt=\"View latest post\" title=\"View latest post\" /></a>";
					}

					echo "</td>";
					echo "</tr>";
					
				}
			}
			echo "</table>";
			echo "</div>";
			
		}
		else {
			echo "There are no forums defined!\n";
		}
    }
	include("footer.php");
}
function forums_action_forum_showposts() {
	eval(lib_rfs_get_globals());
    $res=lib_mysql_query("select * from `forum_list` where `id`='$forum_which'");
    $fold=mysql_fetch_object($res);
    $res=lib_mysql_query("select * from `forum_list` where `id`='$fold->parent'");
    $fold=mysql_fetch_object($res);
    $folder=mysql_fetch_object(lib_mysql_query("select * from `forum_list` where `id`='$forum_which';"));   
    echo "<h1>$folder->name</h1>";
	
	forums_buttons($forum_which);

    $result = lib_mysql_query("select * from forum_posts where `forum`='$forum_which' and `thread_top`='yes' order by bumptime desc limit 0,30");
    if($result) $numposts=mysql_num_rows($result);
    else $numposts=0;
    if($numposts) {
		
		echo "<div class=\"forum_box\">";
		echo "<table border=0 cellpadding=0 cellspacing=0>";
		echo "<tr>";
		echo "<td class=\"forum_table_head\">Topics</td>";
		echo "<td class=\"forum_table_head\">Replies</td>";
		echo "<td class=\"forum_table_head\">Views</td>";
		echo "<td class=\"forum_table_head\">Latest Post</td>";
		echo "<td class=\"forum_table_head\"> </td>";
		echo "</tr>";
        
        while($post=mysql_fetch_array($result)) {
            $new=0;
            $fork = lib_mysql_query("select * from forum_posts where `thread`=".$post['thread']." and `thread_top`='no'");
            $posts=0;
            if($fork) $posts=mysql_num_rows($fork);
            for($star=0;$star<$posts;$star++) {
                $fart=mysql_fetch_array($fork);
                if($fart['time']>=$data->last_login) $new=1;
            }
			$flink="<a href=\"$RFS_SITE_URL/modules/core_forums/forums.php?action=get_thread&thread=".$post['thread']."&forum_which=$forum_which\">";
			
			echo "<tr>";			
			echo "<td>";
			
			echo "<table border=0><tr><td>";
			
            echo $flink;
            echo "<img src=\"$RFS_SITE_URL/images/icons/Documents.png\" height=32 border=0 >\n";
			echo "</a>";
			echo "</td><td width=500>";
			echo $flink;
			echo stripslashes($post['title']);
			echo "</a><br>";
			$great=lib_users_get_data($post['poster']);

			$time=lib_string_current_time($post['time']);

            echo " posted $time by ".$great->name;
			
			echo "</td></tr></table>";
			
			echo "</td><td>";
                        			
			echo $posts;
			echo "</td><td>";
			
		    echo $post['views'];
			
			echo "</td><td>";
			
			$lreply="";
			$lrepr=lib_mysql_query("select * from forum_posts where `thread`=".$post['thread']." and `thread_top`='no' order by `time` desc limit 1");
			if($lrepr) $lreply=mysql_fetch_object($lrepr);
			if($lreply) {

				$great=lib_users_get_data($lreply->poster);
				// echo "<a href=\"$RFS_SITE_URL/modules/core_forums/forums.php?action=get_thread&thread=$lreply->thread&forum_which=$forum_which\">".stripslashes($lreply->title)."</a>\n";
				
				
				echo "<a href=\"$RFS_SITE_URL/modules/profile/showprofile.php?user=$great->name\">$great->name</a><br>";
				echo lib_string_current_time($lreply->time);
				
				
				}
				else {
				echo " ";
			}
			
			echo "</td><td>";
			
			

           if( (lib_access_check("forums","admin")) & ($_SESSION['forum_admin']=="yes")) {
			   
			   echo "<div style='float: left;'>";
			   
				lib_buttons_image_sizeable(
				"$RFS_SITE_URL/modules/core_forums/forums.php?action=sticky_thread&thread=".$post['thread'],
				"Sticky",
				"$RFS_SITE_URL/images/icons/stickyico.gif",
				16,
				16);
				
				lib_buttons_image_sizeable(
				"$RFS_SITE_URL/modules/core_forums/forums.php?action=delete_post_s&thread=".$post['thread'],
				"Delete",
				"$RFS_SITE_URL/images/icons/Delete.png",
				16,
				16);
				
				lib_buttons_image_sizeable(
				"$RFS_SITE_URL/modules/core_forums/forums.php?action=sticky_thread&thread=".$post['thread'],
				"Lock",
				"$RFS_SITE_URL/images/icons/Lock.png",
				16,
				16);
				
				echo "</div>";
				echo "<div>";
			   
                echo "<form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/modules/core_forums/forums.php\">\n";
                echo "<input type=hidden name=action value=move_thread><input type=hidden name=id value=".$post['id'].">\n";
                $resultj = lib_mysql_query("select * from forum_list where `folder`!='yes' order by priority");
		        echo "Move to:<select name=move>";
                while($forumj=mysql_fetch_object($resultj)) {
                    echo "<option>$forumj->name";
                }

                echo "</select>";
                echo "<input type=\"submit\" name=\"submit\" value=\"go\"></form>";
				echo "</div>";
            }
			
			echo "</td></tr>";

        }
		echo "</table>";
		echo "</div>";
    }
    else echo "<p align=center> There are no threads! </p>\n";    
	
	
	include("footer.php");
    
}
function forums_action_() {
	forums_action_forum_list();
}

?>