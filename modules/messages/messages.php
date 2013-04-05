<?
$title="Private Messages";

chdir("../../");
include("header.php");


if($_SESSION["logged_in"]!="true") {
	
	sc_info("You must be logged in to access private messages.","WHITE","RED");
	include("footer.php");
	exit();
	
}

if(empty($to)) if(!empty($sto)) $to=$sto;
if(empty($subject)) if(!empty($sj)) $subject=$sj;

sc_info("Private Messages","dark green", "green");
echo "<hr>";
if($action=="mark as unread"){
	sc_query("update `pmsg` set `read` = 'no' where `id` = '$id'");
	unset($action);
}

if($action=="delete_single"){
	echo "<p><h1>Are you sure you want to delete the message?</h1></p>";
	echo "<form action=$RFS_SITE_URL/modules/messages/messages.php method=post><input type=hidden name=id value=$id>";
	echo "<input type=submit name=action value=\"delete message\"></form>";
}

if($action=="delete message"){
	sc_query("delete from `pmsg` where `id`='$id'");
	unset($action);
}

if($action=="reply"){
	$result=sc_query("select * from `pmsg` where `id`='$id'"); $msg=mysql_fetch_object($result);
	echo "<p><form action=$RFS_SITE_URL/modules/messages/messages.php method=post>";
	echo "To: <input name=to value=\"$msg->from\">";
	echo "Subject: <input name=subject value=\"re: $msg->subject\"><br>";
	echo "<textarea name=message cols=80 rows=20>$msg->message</textarea><br>";
	echo "<input type=submit name=go value=go>";
	echo "<input type=hidden name=action value=messagego>";
	echo "</form> </p>";
}

if($action=="new message"){
	echo "<p><form action=$RFS_SITE_URL/modules/messages/messages.php method=post>";
    if(!empty($to))
    echo "To:<select name=to><option>$to";
    else
	echo "To:<select name=to>";

	$res=sc_query("select * from users order by  `name` asc");
	$count=mysql_num_rows($res);

	for($i=0;$i<$count;$i++)	{
			$userdata=mysql_fetch_object($res);
    		echo "<option>$userdata->name";
    }

	echo "</select><br>";
    if(!empty($subject))    {
        $subject=str_replace("_"," ",$subject);
        echo "Subject: <input name=subject value=\"$subject\" size=80><br>";
    }
    else	{
		echo "Subject: <input name=subject><br>";
	}
	echo "<textarea name=message cols=80 rows=20></textarea><br>";
	echo "<input type=submit name=go value=go>";
	echo "<input type=hidden name=action value=messagego>";
	echo "</form> </p>";
}

if($action=="messagego"){
    $from=$data->name;
    sc_module_f_messages_send($to,$from,$subject,$message);
	unset($action);
}

if($action=="read"){
	sc_query("update `pmsg` set `read` = 'yes' where `id` = '$id'");
	$urresult=sc_query("select * from `pmsg` where `id` = '$id'");
	$msg=mysql_fetch_object($urresult);

	$userdatar=sc_query("select * from users where `name`='$msg->from'");

	$userdata=mysql_fetch_object($userdatar);

    if(empty($msg->from)) $msg->from=" *unknown* ";

	echo "<table border=0 cellspacing=0><tr><td>";

	echo "[<a href=\"$RFS_SITE_URL/modules/messages/messages.php?action=new message&id=$msg->id\">New Message</a>] ";
	echo "</td><td>";

	echo "[<a href=$RFS_SITE_URL/modules/messages/messages.php?action=delete_single&id=$msg->id>Delete</a>] ";
	echo "</td><td>";

	echo "[<a href=\"$RFS_SITE_URL/modules/messages/messages.php?action=mark as unread&id=$msg->id\">Mark Unread</a>] ";
	echo "</td><td>";

	echo "[<a href=\"$RFS_SITE_URL/modules/messages/messages.php?action=reply&id=$msg->id\">Reply</a>] ";
	echo "</td><td>";

	echo "[<a href=$RFS_SITE_URL/modules/messages/messages.php>Inbox</a>] ";
	echo "</td><td>";


	echo "&nbsp;</td></tr></table>";

	echo "<br>";

	echo "<table width=90% border=0 cellspacing=0 cellpadding=0><tr><td>";
 	echo "</td><td width=100%>";

    echo "<table width=90% border=0 cellspacing=0 cellpadding=0><tr><td width=100% class=private_message_from> From...: $msg->from              </td></tr></table>";
    echo "<table width=90% border=0 cellspacing=0 cellpadding=0><tr><td width=100% class=private_message_sent> Sent...: ".sc_time($msg->time)." </td></tr></table>";
    echo "<table width=90% border=0 cellspacing=0 cellpadding=0><tr><td width=100% class=private_message_subject> Subject: $msg->subject        </td></tr></table>";



 	echo "<table width=90% border=0 cellspacing=0 cellpadding=0> <tr><td width=100% class=private_message_body>";

    echo nl2br($msg->message) ."</td></tr></table>";

    echo "</td></tr></table>";

//	echo "<table width=90% border=0 cellspacing=0 cellpadding=0><tr><td width=100 % >";
//	echo "</td></tr></table>";
//	echo "<p>&nbsp;</p>";
}

if($action=="delete") {

    //echo "Delete messages...<br>";
    $dax=explode(",",$data->alias);
    $q="select * from `pmsg` where
    `to` = '$data->name' or
    `to` = '$data->name_shown' or ";
    for($i=0;$i<count($dax);$i++) {
        $q.="`to` = '$data->alias' or ";
    }
    $q.= "`to`='null' ";
    $q.=" order by id desc ;";
    $result=sc_query($q);

    for($i=0;$i<mysql_num_rows($result);$i++){
        $p=mysql_fetch_object($result);

        if(!empty($_POST["pmsg_$p->id"])) {

            sc_query("delete from pmsg where id = '$p->id'");

        }
    }

    unset($action);
   // echo "<hr>";

}

if($action=="mark read") {
    //echo "Mark messages as read...<br>";
    $dax=explode(",",$data->alias);
    $q="select * from `pmsg` where
    `to` = '$data->name' or
    `to` = '$data->name_shown' or ";
    for($i=0;$i<count($dax);$i++) {
        $q.="`to` = '$data->alias' or ";
    }
    $q.= "`to`='null' ";
    $q.=" order by id desc ;";
    $result=sc_query($q);

    for($i=0;$i<mysql_num_rows($result);$i++){
        $p=mysql_fetch_object($result);

        if(!empty($_POST["pmsg_$p->id"])) {

            sc_query("update `pmsg` set `read` = 'yes' where `id` = '$p->id'");
//            echo "MARKING MESSAGE $p->id as READ<BR>";

        }
    }

    unset($action);
    //echo "<hr>";
}

if($action=="mark unread") {
    //echo "Mark messages as unread...<br>";
    $dax=explode(",",$data->alias);
    $q="select * from `pmsg` where
    `to` = '$data->name' or
    `to` = '$data->name_shown' or ";
    for($i=0;$i<count($dax);$i++) {
        $q.="`to` = '$data->alias' or ";
    }
    $q.= "`to`='null' ";
    $q.=" order by id desc ;";
    $result=sc_query($q);

    for($i=0;$i<mysql_num_rows($result);$i++){
        $p=mysql_fetch_object($result);

        if(!empty($_POST["pmsg_$p->id"])) {

            sc_query("update `pmsg` set `read` = 'no' where `id` = '$p->id'");

            //echo "MARKING MESSAGE $p->id as UNREAD<BR>";

        }
    }

    unset($action);
//    echo "<hr>";
}

if(empty($action)) {

    $ot="$data->name";
    if(!empty($data->alias))        $ot.=" alias ($data->alias)";
    if(!empty($data->name_shown))   $ot.=" name_shown ($data->name_shown)";

    echo $ot;
    $numunread=sc_module_f_messages_get_unread();

    echo "<hr>";

    $dax=explode(",",$data->alias);
    $q="select * from `pmsg` where
   ( `to` = '$data->name' or
    `to` = '$data->name_shown' or ";
    for($i=0;$i<count($dax);$i++) {
        $q.="`to` = '$data->alias' or ";
    }
    $q.= "`to`='null')";
    $q.=" order by id desc ;";
    $result=sc_query($q);

	$numpmsg=mysql_num_rows($result); if(empty($numpmsg)) $numpmsg=0;

	echo "<table border=0><tr><td>[<a href=\"$RFS_SITE_URL/modules/messages/messages.php?action=new message\">New Message</a>]</td></tr></table><br>";

	if($numpmsg > 0) {

        echo "You have $numpmsg private messages! ($numunread unread messages)";
		echo "<table border=0 cellspacing=0 cellpadding=5 width=90%>";
		echo "<tr><td width=5%>&nbsp;</td><td width=5%>&nbsp;</td><td width=12%>From</td><td width=20%>Date / Time</td><td>Subject</td></tr>";

        echo "<form action=\"$RFS_SITE_URL/modules/messages/messages.php\" method=\"post\">";


        for($i=0;$i<$numpmsg;$i++) {
            $gt++; if($gt>1) $gt=0;
            $msg=mysql_fetch_object($result);
            $lnk="<a href=$RFS_SITE_URL/modules/messages/messages.php?action=read&id=$msg->id>";

            echo "<tr class=\"sc_project_table_$gt\">";

            echo "<td><input type=checkbox name=\"pmsg_$msg->id\"></td>";

            if(strcmp($msg->read,"yes")) {
                echo "<td><img border=0 width=16 height=16 src=mail.png>";
            }
            else {
                echo "<td><img border=0 width=16 height=16 src=mailopen.png>";
            }

//./ 			echo "&nbsp;"; //reserve for importance?

			echo "</td>";
            if ( empty($msg->from)) $msg->from=" *unknown* ";
			echo "<td>$lnk$msg->from</a></td><td>$lnk$msg->time</a></td><td>$lnk$msg->subject</a></td></tr>";
		}
		echo "</table>";


        echo " Checked:
        <input type=submit name=action value=\"delete\">
        <input type=submit name=action value=\"mark read\">
        <input type=submit name=action value=\"mark unread\">
       ";

        echo "</form>";
	}
	else
	{
		echo "<p>You have no private messages!</p>";
	}
}

include("footer.php");
?>
