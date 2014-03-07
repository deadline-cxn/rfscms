<?
include_once("include/lib.all.php");

lib_menus_register("Private Messages","$RFS_SITE_URL/modules/messages/messages.php");

function adm_action_lib_messages_messages() { eval(scg());
    lib_domain_gotopage("$RFS_SITE_URL/modules/messages/messages.php");
}

function get_unread_messages() { eval(scg());
    $q= "select * from `pmsg` where
    (
    `to` = '$data->name' or
    `to` = '$data->name_shown' or ";
    for($i=0;$i<count($dax);$i++) {
        $q.="`to` = '$data->alias' or ";
    }
    $q.= "`to`='null' )";
    $q.= " and  `read` = 'no'";

    $urresult=lib_mysql_query($q);
    $numunread=mysql_num_rows($urresult);
    if(empty($numunread)) $numunread=0;
    return $numunread;
}

function send_message($to,$from,$subject,$message) {
   $mtime=date("Y-m-d H:i:s");
   $subject=addslashes($subject);
   $message=addslashes($message);
	lib_mysql_query("  insert into `pmsg` (`to`, `from`,`subject`, `message`,   `time`, `read`)
                           VALUES ('$to','$from','$subject','$message', '$mtime', 'no');");
	echo "<p>Message to $to sent!</p>";
}

function send_all($from,$subject,$message) {
    $r=lib_mysql_query("select * from users");
    $n=mysql_num_rows($r);
    for($i=0;$i<$n;$i++) {
        $u=mysql_fetch_object($r);

    }

}

function sc_module_mini_messages_link() { eval(scg());

		echo "<h2>Private Messages</h2>";
		if($_SESSION["logged_in"]!="true") return;
		echo "<table border=0 cellspacing=0 cellpadding=3>";
		echo "<tr class='message_mini_indicator'>";
       echo "<td>";
       echo "<a href=$RFS_SITE_URL/modules/messages/messages.php>";
       echo "<img border=0 width=16 height=16 src=$RFS_SITE_URL/modules/messages/mail.png>";
       echo "</a>";
       echo "</td>";
       echo "<td>";
       echo "<a href=$RFS_SITE_URL/modules/messages/messages.php>";
       echo "Messages</a>";
       echo "</td>";
       echo "</tr></table>";
}

function sc_module_mini_messages_indicator_small() { eval(scg());
	echo "<h2>Private Messages</h2>";
	if($_SESSION["logged_in"]!="true") return;
    $ur=get_unread_messages();
    if($ur) {
        echo "<table border=0 cellspacing=0 cellpadding=3>";
        echo "<tr class='message_mini_indicator'>";
        echo "<td>";
        echo "<a href=$RFS_SITE_URL/modules/messages/messages.php>";
        echo "<img border=0 width=16 height=16 src=$RFS_SITE_URL/modules/messages/mail.png>";
        echo "</a>";
        echo "</td>";
        echo "<td>";
        echo "<a href=$RFS_SITE_URL/modules/messages/messages.php>";
        echo " $ur unread messages</a>";
        echo "</td>";
        echo "</tr></table>";
    } else  {
		
		echo "<table border=0 cellspacing=0 cellpadding=3>";
		echo "<tr class='message_mini_indicator'>";
       echo "<td>";
       echo "<a href=$RFS_SITE_URL/modules/messages/messages.php>";
       echo "<img border=0 width=16 height=16 src=$RFS_SITE_URL/modules/messages/mail.png>";
       echo "</a>";
       echo "</td>";
       echo "<td>";
       echo "<a href=$RFS_SITE_URL/modules/messages/messages.php>";
       echo "Messages</a>";
       echo "</td>";
       echo "</tr></table>";
	}

}

function sc_module_mini_latest_messages($x) { eval(scg());
    lib_div("MESSAGES MODULE SECTION");
    echo "<h2>Private Messages</h2>";

    echo "<table border=0 cellspacing=0>";

    $result = lib_mysql_query("select * from pmsg where 'to' = '$data->name' ");
    if($result) $numposts=mysql_num_rows($result);
    else $numposts=0;
    if($numposts) {
       $gt=1; $i=0;

        echo "<tr><td class=contenttd width=2% >";
        $thread=mysql_fetch_object($result);


        echo "</td></tr>";
    }
    echo "</table>";

    echo "<p align=right>(<a href=$RFS_SITE_URL/modules/messages/messages.php class=\"a_cat\" align=right>More...</a>)</p>";
}

function sc_module_f_messages_send($to,$from,$title,$message) { eval(scg()); 
/*   "admin",  $usr->name,  "REQUEST TO ACCESS POD: $pod",        "	<a class=pmsglink href=$RFS_SITE_URL/index.php?action=netman_add_pod&useradd=$usr->id&pod=$pod > 		$usr->name is requesting access to pod: $pod 		</a>"         */
	

}




?>
