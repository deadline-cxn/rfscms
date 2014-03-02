<?
$title="FORUMS";
chdir("../../");
include("header.php");
if(empty($action)) $action="forum_list";

function forum_put_buttons($forum_which) { eval(scg());

	$thispage=explode("?",sc_canonical_url());
	$opage=$thispage[1];
	if($forum_list!="yes") {
        echo "[<a href=\"$RFS_SITE_URL/modules/forums/forums.php\">List Forums</a>]";
        echo "[<a href=\"$RFS_SITE_URL/modules/forums/forums.php?action=forum_showposts&forum_which=$forum_which\">List Threads</a>]";
        echo "[<a href=\"$RFS_SITE_URL/modules/forums/forums.php?action=start_thread&forum_which=$forum_which\">Start New Thread</a>]";
    }
	if(sc_access_check("forums","admin")) {
        if($_SESSION['forum_admin']=="yes")
        echo "[<a href=\"$RFS_SITE_URL/modules/forums/forums.php?action=forum_admin_off&$opage\">Forum Admin Off</a>]";
        else
        echo "[<a href=\"$RFS_SITE_URL/modules/forums/forums.php?action=forum_admin_on&$opage\">Forum Admin On</a>]";
    }
	echo "<hr>";
}

$message=str_replace("<meta","(meta tags are unauthorized)<no ",$message);
$message=str_replace("<input","(form tags are unauthorized)<no ",$message);
$message=str_replace("<form enctype=application/x-www-form-URLencoded","(form tags are unauthorized)<no ",$message);
$message=str_replace("<textarea","(form tags are unauthorized)<no ",$message);
$message=str_replace("<select","(form tags are unauthorized)<no ",$message);

function bumpthread($id) {
    $bumptime=date("Y-m-d H:i:s"); // 0000-00-00 00:00:00
    sc_query("update forum_posts set `bumptime`='$bumptime' where id='$id'");
} 

if($action=="forum_admin_on") { $_SESSION['forum_admin']="yes"; }
if($action=="forum_admin_off") { $_SESSION['forum_admin']="no"; }
if($_SESSION['forum_admin']=="yes") {
    if(sc_access_check("admin","forums")) 
		echo "<p> ".smiles(":X")." You do not have access to the Forum Administration Panel (FAP)!</p>";
	$_SESSION['forum_admin']=="no";
}

$folder=mysql_fetch_object(sc_query("select * from `forum_list` where `id`='$forum_which';"));

function forums_action_start_thread() { eval(scg());
	$folder=mysql_fetch_object(sc_query("select * from `forum_list` where `id`='$forum_which';"));   
    echo "<h1>$folder->name</h1>";
	forum_put_buttons($forum_which);
    if($logged_in=="true") {
	    $folder=mysql_fetch_object(sc_query("select * from `forum_list` where `id`='$forum_which';"));	
		echo "<div class=\"forum_box\">";
	    echo "<h2>Start a new thread in $folder->name</h2>";
        echo "<form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/modules/forums/forums.php\" method=post>\n";
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

function forums_action_start_thread_go() { eval(scg());
    if($logged_in=="true") {
		
        $user=$data->id;
        if($anonymous=="yes") $user=999;
        if($data->name=="Visitor") $user=999;
        $time=date("Y-m-d H:i:s");
        sc_query("INSERT INTO `forum_posts` (`id`, `title`) VALUES ('', '__chkdel');");
        $fart=sc_query("select * from `forum_posts` where `title`='__chkdel'");
        $lick=mysql_fetch_array($fart);
        $id=$lick['id'];
        $thread=$id;
        $theader=addslashes($theader);
        $reply=addslashes($reply);
        sc_query("UPDATE `forum_posts` set `poster`       = '$user' where `id`='$id';");
        sc_query("UPDATE `forum_posts` set `poster_name`  = '$name' where `id`='$id';");
        sc_query("UPDATE `forum_posts` set `title`        = '$theader' where `id`='$id';");
        sc_query("UPDATE `forum_posts` set `message`      = '$reply' where `id`='$id';");
        sc_query("UPDATE `forum_posts` set `thread`       = '$thread' where `id`='$id';");
        sc_query("UPDATE `forum_posts` set `forum`        = '$forum_which' where `id`='$id';");
        sc_query("UPDATE `forum_posts` set `time`         = '$time' where `id`='$id';");
        sc_query("UPDATE `forum_posts` set `thread_top`   = 'yes' where `id`='$id';");
        sc_query("DELETE from `forum_posts` where `title` ='__chkdel'");
        $data->forumposts+=1;
        sc_query("update users set `forumposts`='$data->forumposts' where `id`='$data->id'");
        sc_query("UPDATE `forum_list` set `last_post` = '$id' where `id` = '$forum_which';");
        bumpthread($id);
        sc_log("*****> $data->name started a new thread! [$header]");
    }
    else echo "<p class=sc_site_urlr>You must be logged in to post or reply!</p>\n";
    forums_action_get_thread($thread,$forum_which);
}

function show1message($post,$gx) { eval(scg());
	$pster=sc_getuserdata($post['poster']);	
	$forum=mysql_fetch_object(sc_query("select * from forum_list where id=".$post['forum']));	
	
	if(	($forum->private=="yes") &&	(	($logged_in!="true") || (sc_access_check("forums","admin"))))
	{ echo "<p>You don't have access to this forum.</p>";}
	else {
		
		echo "<a id=\"".$post['id']."\"></a>";
		
		echo "<div class=\"forum_box\">";
        echo "<h2>".$post['title']."</h2>";
		 echo "<div class=\"sc_forum_table_1\">";
		
		$pname=$pster->name;
			
			echo "<div class=\"forum_user\" >";
			sc_useravatar($pname);
			echo "<br><a href=\"$RFS_SITE_URL/modules/profile/showprofile.php?user=$pname\">$pname</a><br>";
			echo "posts:$pster->forumposts<br>";
			echo "replies:$pster->forumreplies";
			echo "</div>";				
			echo  "</div>";
		
			echo "<div class=\"forum_message\">";
			echo smiles(wikitext(stripslashes($post['message'])));
			echo "</div>";
		
		echo "<div class=\"forum_time\">";
        $time=sc_time($post['time']);
        echo "posted $time by <a href=\"$RFS_SITE_URL/modules/profile/showprofile.php?user=$pname\">$pname</a> ";
        if($logged_in=="true") {
            if( ($pster->name==$data->name) || (sc_access_check("forums","admin"))  ) {
                $thread=$post['thread'];
                $whichrep=$post['id'];
                $forum_witch=$post['forum'];
                echo "[<a href=\"$RFS_SITE_URL/modules/forums/forums.php?action=delete_post_s&forum_which=$forum_witch&reply=$whichrep&thread=$thread\">delete</a>] ";
                echo "[<a href=\"$RFS_SITE_URL/modules/forums/forums.php?action=edit_reply&forum_which=$forum_witch&reply=$whichrep&thread=$thread\">edit</a>]";
            }
        }
		echo "</div>";
        

		
		echo " </div>";
    }
}

function forums_action_get_thread($thread,$forum_which) {    eval(scg());

   
	$gt=1; $gx=4+$gt;
	$result = sc_query("select * from `forum_posts` 
							where `thread_top`='yes' and 
							`thread`='".$thread."' order by time limit 0,30");
							
	if($result) $numposts=mysql_num_rows($result);
	if($numposts>0) $post=mysql_fetch_array($result);
	
	
   if(empty($forum_which)){
		$th=mfo1("select * from `forum_posts` where `thread`='$thread'");
		$forum_which=$th->forum;
		
		
   }
   if(empty($forum_which)) {
	   forums_action_forum_list();
	   exit();
   }

	if($forum_which!=$post['forum']) { echo "<p>Error! This post or reply has been moved or deleted.</p>"; return; }
	$forum_which=$post['forum'];
	$folder=mysql_fetch_array(sc_query("select * from `forum_list` where `id`='$forum_which';"));
	$title=stripslashes($post['title']);
	$thread=$post['id'];
	
   	$folder=mysql_fetch_object(sc_query("select * from `forum_list` where `id`='$forum_which';"));
    echo "<h1>$folder->name >> $title</h1>";
	forum_put_buttons($forum_which);
	
	$GLOBALS['forum_list']="no";
	if($numposts>0) {
		$views=$post['views']+1;
		sc_query("update forum_posts set views ='$views' where thread_top='yes' and thread=$thread");
		show1message($post,$gx);                
		$fart = sc_query("select * from forum_posts where `forum`='".($forum_which)."' and `thread`='".$thread."' and `thread_top`='no' order by time limit 0,30");
		if($fart) $numreplies=mysql_num_rows($fart);
		if($numreplies>0) {
			for($i=0;$i<$numreplies;$i++) {
				$gt++; if($gt>2) $gt=1; $gx=4+$gt;
				$post = mysql_fetch_array($fart);
				show1message($post,$gx);
			}
		}
		// form to add another reply
        if($logged_in=="true") {
			
				echo "<div class=\"forum_box\">";
				echo "<h2>Reply</h2>";
				
				echo "<form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/modules/forums/forums.php\" method=post>\n";
				echo "<input type=hidden name=action value=reply_to_thread>\n";
				echo "<input type=hidden name=forum_which value=\"$forum_which\">\n";
				echo "<input type=hidden name=thread value=\"".$thread."\">\n";
				
				echo "Title:<input type=text name=header value=\"re:";
				
				echo stripslashes($title);
				echo "\" size=78>";
				
				echo "<div class=forum_message>";
				echo "<textarea name=reply rows=15 style='width: 100%;'></textarea>";
				echo "</div>";
				
				echo "<br>Anonymous:";
				echo "<select id=\"anonymous\" name=anonymous style=\"width:160px; min-width: 160px;\" width=160>
						<option>no<option>yes</select> &nbsp;<input type=submit name=submit value=\"Go!\">";
				echo "</form>\n";
				echo "</div>";
        } else {
            echo "<p class=sc_site_urlr><a href=$RFS_SITE_URL/login.php>Login</a> to reply to this post!</p>\n";
        }
    }
    echo "<br>\n";
	include("footer.php");
}

if($action=="move_thread") {
    $tofor=sc_query("select * from forum_list where name='$move'");
    $toforum=mysql_fetch_object($tofor);
    sc_query("update forum_posts set forum='$toforum->id' where id='$id'");
    sc_query("update forum_posts set forum='$toforum->id' where thread='$id'");
    echo "<p>Move thread $id to $move ($toforum->id)</p>";
    $action="get_thread";
}

if($action=="delete_post_s") {
    if($logged_in=="true") {
        echo "<table border=\"0\" align=center><tr><td class=\"sc_warning\"><center>".smiles("^X")."\n";
        echo "<br>WARNING:<br>The forum post and ALL replies will be completely removed are you sure?</center>\n";
        echo "</td></tr></table>\n";
        echo "<table align=center><tr><td><form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/modules/forums/forums.php\">\n";
        echo "<input type=hidden name=action value=delete_post><input type=hidden name=reply value=$reply>\n";
        echo "<input type=\"submit\" name=\"submit\" value=\"Delete!\"></form></td>\n";
        echo "<td><form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/index.php\"><input type=\"submit\" name=\"no\" value=\"No\"></form></td></tr></table>\n";    
    }
}

if($action=="delete_post") {
    if($logged_in=="true") {
       sc_query("delete from forum_posts where thread='$thread';");
       sc_warn("<h2><font color=red>Post was deleted...</font></h2>");
		$forum_list="no";
		forums_action_get_thread($thread,$forum_which);
    }
}
 
if($action=="delete_reply_s") {
    if($logged_in=="true") {
        echo "<table border=\"0\" align=center><tr><td class=\"sc_warning\"><center>".smiles(":X")."\n";
        echo "<br>WARNING:<br>The forum reply will be completely removed are you sure?</center>\n";
        echo "</td></tr></table>\n";
        echo "<table align=center><tr><td><form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/modules/forums/forums.php\">\n";
        echo "<input type=hidden name=action value=delete_reply><input type=hidden name=reply value=$reply>\n";
        echo "<input type=\"submit\" name=\"submit\" value=\"Fuck Yeah!\"></form></td>\n";
        echo "<td><form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/index.php\"><input type=\"submit\" name=\"no\" value=\"No\"></form></td></tr></table>\n";
    }
}

if($action=="delete_reply") {
    if($logged_in=="true") {
        sc_query("delete from forum_posts where id='$reply';");
        echo "<h2><font color=red>Reply was deleted...</font></h2>";
        $action="get_thread";
    }
}

if($action=="edit_reply") {
    if($logged_in=="true") {
        $posttt=sc_query("select * from forum_posts where id='$reply';");
        $post=mysql_fetch_object($posttt);
        $fw=$forum_which;
		echo "<div class=\"forum_box\">";
		echo "<h2>Editing reply #$reply: $post->title</h2>";
        echo "<table border=0 width=100%>\n";
        echo "<form enctype=application/x-www-form-URLencoded action=$RFS_SITE_URL/modules/forums/forums.php method=post>\n";
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

if($action=="edit_reply_go"){
    if($logged_in=="true")    {
		$message=$_POST['message'];
		$message=addslashes($message);
		$theader=addslashes($theader);
		sc_query("UPDATE forum_posts SET message = '$message' where id = '$reply'");
		sc_query("UPDATE forum_posts SET title   = '$theader'   where id = '$reply'");
		forums_action_get_thread($thread,$forum_which);
		exit();
    }
}

if($action=="reply_to_thread"){
    if($logged_in=="true")    {
        $user=$data->id; if($anonymous=="yes") $user=999;
        $time=date("Y-m-d H:i:s");
        $header=addslashes($header);
        $reply=addslashes($reply);
        $query  = "INSERT  INTO  `forum_posts` ";
        $query .= "( `id`, `poster`, `title`, `message`, `thread`, `forum`, `time`, `thread_top` ) ";
        $query .= "VALUES (  '',  '$user',  '$header',  '$reply',  '$thread',  '$forum_which',  '$time',  'no' );";
        sc_query($query);
        $data->forumreplies+=1;
        sc_query("update `users` set `forumreplies`='$data->forumreplies' where `id`='$data->id'");
        
        $fart=sc_query("select * from `forum_posts` order by `time` desc limit 1");
        $lick=mysql_fetch_object($fart); 
        sc_query("UPDATE `forum_list` set `last_post` = '$lick->id' where `id` = '$forum_which';");
        bumpthread($lick->thread);
        sc_log("*****> $data->name replied to thread [$header]");
    }    else    {
        echo "<p class=sc_site_urlr><a href=$RFS_SITE_URL/login>Login</a> to reply</p>\n";
    }
	forums_action_get_thread($thread,$forum_which);
}

if($action=="create_forum"){
    if(sc_access_check("forums","admin")){
        sc_query("INSERT INTO `forum_list` 
                (`name`        ,  `comment`     , `folder`        , `parent` )
         VALUES ('$forum_name' ,  '$forum_desc' , '$forum_folder' , '$forum_parent' );");
        sc_forum_modification_panel($forum_name);
        $forum_list="no";
    }
}

if($action=="rename_forum"){
    $forum_name=addslashes($forum_name);
    $old_name=addslashes($old_name);
    if(sc_access_check("forums","admin")) sc_query("UPDATE forum_list SET `name`='$forum_name' where `name`='$old_name' limit 1;");
    // now show the forum modification panel (FMP)
    sc_forum_modification_panel(stripslashes($forum_name));
    $forum_list="no";
}

if($action=="modify_forum"){
    if(sc_access_check("forums","admin"))    {
        $con=1;
        if($forum_usepass=="yes")        {
            if($forum_pass1!=$forum_pass2)            {
                echo "<p>The two passwords you entered do not match!</p>\n";
                $con=0;
                sc_forum_modification_panel($forum_name);
                $forum_list="no";
            }
        }
        if($con==1)        {
            $forum_name=stripslashes($forum_name);
            echo "$forum_name";
            sc_setvar("forum_list","name",addslashes($forum_name),"name",$old_name);
            sc_setvar("forum_list","comment",addslashes($forum_desc),"name",addslashes($forum_name));
            sc_setvar("forum_list","usepass",$forum_usepass,"name",addslashes($forum_name));
            sc_setvar("forum_list","password",$forum_pass1,"name",addslashes($forum_name));
            sc_setvar("forum_list","private",$forum_private,"name",addslashes($forum_name));
            sc_setvar("forum_list","moderated",$forum_moderated,"name",addslashes($forum_name));
            $userdata=sc_getuserdata($forum_moderator);
            sc_setvar("forum_list","moderator",$userdata->id,   "name",addslashes($forum_name));
            // sc_setvar("forum_list","bgcolor",  $forum_bgcolor,  "name",addslashes($forum_name));
            sc_setvar("forum_list","priority", $forum_priority, "name",addslashes($forum_name));
            /*
            $tym=sc_query("select * from `access_groups`");
            $tymr=mysql_num_rows($tym);
            $nags="";
            for($tymi=0;$tymi<$tymr;$tymi++)
            {
                $tcb=$_REQUEST["agcb_$tymi"];
                if(!empty($tcb))
                {
                    $nags=$nags."$tcb,";
                }
            }
            sc_query("UPDATE `forum_list` set `access_groups` = '$nags' where `name`='$forum_name'");
            */
        }
    }    else    {
        echo "<p> ".smiles("^X")." You do not have access to modify the forums!</p>";
        sc_log("*****> $data->name tried to access the forum administration panel...");
    }
}


function forums_action_forum_list() { eval(scg());

	echo "<h1>Forums</h1>";

    $fres = sc_query("select * from forum_list where `folder`='yes' order by priority");
    $numfolder=mysql_num_rows($fres);
    if($numfolder>0)    {
        $fihg=0;
        while($fihg<$numfolder) {
            $fihg++;
            $dfold=mysql_fetch_object($fres);
              
            $seefolder=false;
            if($data->access_groups=="") $seefolder=false;
            if(sc_access_check("forums","admin")) $seefolder=true;
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

                $result = sc_query("select * from forum_list where `parent`='$dfold->id' order by priority");
                $numforums=mysql_num_rows($result);
                
                if($numforums>0) {
                    $gt=1; $i=0;
                    while($i<$numforums) {
                        $new=0;
                        $folder = mysql_fetch_object($result);
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
                        
                        if($dumpforum) {
                            $fork = sc_query("select * from forum_posts where `forum`= '$folder->id' and `thread_top`='yes'");
                            $posts=0;
                            if($fork) $posts=mysql_num_rows($fork);
                            for($star=0;$star<$posts;$star++) {
                                $fart=mysql_fetch_array($fork);
                                if($fart['time']>=$data->last_login) $new=1;
                                $lip = sc_query("select * from forum_posts where `thread`=".($fart['thread']));
                                $postst=0;
                                if($lip) $postst=mysql_num_rows($lip);
                                for($stary=0;$stary<$postst;$stary++)  {
                                    $farter=mysql_fetch_array($lip);
                                    if($farter['time']>=$data->last_login) $new=1;
                                }
                            }
							$link="$RFS_SITE_URL/modules/forums/forums.php?forum_which=$folder->id&action=forum_showposts";
                            $gt=$gt+1; if($gt>2) $gt=1; $gx=$gt+2; $gy=$gt+4;
                            
							$alttxt="No new posts";
							$folder_filename="folder_big.gif";
							if($new==1) {
								$folder_filename="folder_new_big.gif";
								$alttxt="New posts";
							}
							
							
							echo"<tr><td>";
							
							echo "<table><tr><td>";							
							$folderpic=sc_get_theme_image("images/icons/$folder_filename");
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
								$foruser=sc_getuserdata($moder);
								echo "<a href=$RFS_SITE_URL/modules/profile/showprofile.php?user=$foruser->name>$foruser->name</a>]";
                            }
							
							echo "</td>";
							echo "<td>";
							

                            $topres=sc_query("select id from `forum_posts` where `forum`= '$folder->id' and `thread_top`='yes'");
                            $topics=mysql_num_rows($topres);
                            $postres=sc_query("select id from `forum_posts` where `forum`='$folder->id';");
                            $posts=mysql_num_rows($postres);
							echo "$topics";
							echo "</td>";
							echo "<td>";
							echo "$posts";
							echo "</td>";
							echo "<td>";
                            $ruhroh=sc_query("select * from `forum_posts` where `forum` = '$folder->id' order by time desc limit 1");
                            
                            if(mysql_num_rows($ruhroh)) {
                                $lastpost=mysql_fetch_object($ruhroh);
                                $link="$RFS_SITE_URL/modules/forums/forums.php?action=get_thread&thread=$lastpost->id";
                                $link="$RFS_SITE_URL/modules/forums/forums.php?forum_list=no&action=get_thread&thread=$lastpost->thread&forum_which=$lastpost->forum";
                                echo "<a href=\"$link#$lastpost->id\" title=\"$lastpost->title\">$lastpost->title</a><br>";
                                echo sc_time($lastpost->time);
                                $udata=sc_getuserdata($lastpost->poster);
                                echo " by <a href=\"$RFS_SITE_URL/modules/profile/showprofile.php?user=$udata->name\">$udata->name</a>";
                                echo " <a href=\"$link\"><img src=\"$RFS_SITE_URL/images/icons/icon_latest_reply.gif\" width=\"18\" height=\"9\" class=\"imgspace\" border=\"0\" alt=\"View latest post\" title=\"View latest post\" /></a>";
                            }

							echo "</td>";
							echo "</tr>";
							
                        }
                        $i=$i+1;
						
						
                    }
					echo "</table>";
					echo "</div>";
					
                }
                else {
                    echo "There are no forums defined!\n";
                }
            }
			
        }
    }
    else    {
        echo "<p> Forums not configured </p>";
    }
	include("footer.php");
}

function forums_action_forum_showposts() { eval(scg());

    $res=sc_query("select * from `forum_list` where `id`='$forum_which'");
    $fold=mysql_fetch_object($res);
    $res=sc_query("select * from `forum_list` where `id`='$fold->parent'");
    $fold=mysql_fetch_object($res);
    $folder=mysql_fetch_object(sc_query("select * from `forum_list` where `id`='$forum_which';"));   
    echo "<h1>$folder->name</h1>";
	
	forum_put_buttons($forum_which);

    $result = sc_query("select * from forum_posts where `forum`='$forum_which' and `thread_top`='yes' order by bumptime desc limit 0,30");
    if($result) $numposts=mysql_num_rows($result);
    else $numposts=0;
    if($numposts) {
       $gt=1; $i=0;
		
		
		echo "<div class=\"forum_box\">";
		echo "<table border=0 cellpadding=0 cellspacing=0>";
		echo "<tr><td>Topics</td><td>Replies</td><td>Views</td><td>Latest Post</td><td></td></tr>";
        
        for($i=0;$i<$numposts;$i++) {
            $new=0;
            $gt=$gt+1; if($gt>2) $gt=1; $gx=$gt+2; $gy=$gt+4;
			
            $post=mysql_fetch_array($result);
            $fork = sc_query("select * from forum_posts where `thread`=".$post['thread']." and `thread_top`='no'");
            $posts=0;
            if($fork) $posts=mysql_num_rows($fork);
            for($star=0;$star<$posts;$star++) {
                $fart=mysql_fetch_array($fork);
                if($fart['time']>=$data->last_login) $new=1;
            }
			$flink="<a href=\"$RFS_SITE_URL/modules/forums/forums.php?action=get_thread&thread=".$post['thread']."&forum_which=$forum_which\">";
			
			echo "<tr>";			
			echo "<td>";
			
			echo "<table border=0><tr><td>";
			
            echo $flink;
            echo "<img src=\"$RFS_SITE_URL/images/icons/Documents.png\" height=32 border=0 >\n";
			echo "</a>";
			echo "</td><td>";
			echo $flink;
			echo stripslashes($post['title']);
			echo "</a><br>";
			$great=sc_getuserdata($post['poster']);

			$time=sc_time($post['time']);

            echo " posted $time by ".$great->name;
			
			echo "</td></tr></table>";
			
			echo "</td><td>";
                        			
			echo $posts;
			echo "</td><td>";
			
		    echo $post['views'];
			
			echo "</td><td>";
			
			$lreply="";
			$lrepr=sc_query("select * from forum_posts where `thread`=".$post['thread']." and `thread_top`='no' order by `time` desc limit 1");
			if($lrepr) $lreply=mysql_fetch_object($lrepr);
			if($lreply) {

				$great=sc_getuserdata($lreply->poster);
				// echo "<a href=\"$RFS_SITE_URL/modules/forums/forums.php?action=get_thread&thread=$lreply->thread&forum_which=$forum_which\">".stripslashes($lreply->title)."</a>\n";
				
				
				echo "<a href=\"$RFS_SITE_URL/modules/profile/showprofile.php?user=$great->name\">$great->name</a><br>";
				echo sc_time($lreply->time);
				
				
				}
				else {
				echo " ";
			}
			
			echo "</td><td>";

           if( (sc_access_check("forums","admin")) & ($_SESSION['forum_admin']=="yes")) {
                echo "<form enctype=application/x-www-form-URLencoded action=\"$RFS_SITE_URL/modules/forums/forums.php\">\n";
                echo "<input type=hidden name=action value=move_thread><input type=hidden name=id value=".$post['id'].">\n";
                
                $resultj = sc_query("select * from forum_list where `folder`!='yes' order by priority");
                $numforumsj=mysql_num_rows($resultj);
		        echo "Move to:<select name=move>";
                for($jji=0;$jji<$numforumsj;$jji++) {
                    $forumj=mysql_fetch_object($resultj);
                    echo "<option>$forumj->name";
                }

                echo "</select>";
                echo "<input type=\"submit\" name=\"submit\" value=\"go\"></form>";
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
