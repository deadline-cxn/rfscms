<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
// MESSAGES CORE MODULE
/////////////////////////////////////////////////////////////////////////////////////////
include_once("include/lib.all.php");

$RFS_ADDON_NAME="messages";
$RFS_ADDON_VERSION="1.0.0";
$RFS_ADDON_SUB_VERSION="0";
$RFS_ADDON_RELEASE="";
$RFS_ADDON_DESCRIPTION="RFSCMS Messages";
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

lib_menus_register("Private Messages","$RFS_SITE_URL/modules/core_messages/messages.php");

function adm_action_lib_messages_messages() { eval(lib_rfs_get_globals());
    lib_domain_gotopage("$RFS_SITE_URL/modules/core_messages/messages.php");
}

function get_unread_messages() { eval(lib_rfs_get_globals());
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
    $numunread=$urresult->num_rows;
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
    $n=$r->num_rows;
    for($i=0;$i<$n;$i++) {
        $u=$r->fetch_object($r);

    }

}

function m_panel_messages_link() { eval(lib_rfs_get_globals());
	if($_SESSION["logged_in"]!="true") return;
	echo "<h2>Private Messages</h2>";
	echo "<table border=0 cellspacing=0 cellpadding=3>";
	echo "<tr class='message_mini_indicator'>";
	echo "<td>";
	echo "<a href=$RFS_SITE_URL/modules/core_messages/messages.php>";
	echo "<img border=0 width=16 height=16 src=$RFS_SITE_URL/modules/core_messages/mail.png>";
	echo "</a>";
	echo "</td>";
	echo "<td>";
	echo "<a href=$RFS_SITE_URL/modules/core_messages/messages.php>";
	echo "Messages</a>";
	echo "</td>";
	echo "</tr></table>";
}

function m_panel_messages_indicator_small() { eval(lib_rfs_get_globals());
	if($_SESSION["logged_in"]!="true") return;
	echo "<h2>Private Messages</h2>";	
    $ur=get_unread_messages();
    if($ur) {
        echo "<table border=0 cellspacing=0 cellpadding=3>";
        echo "<tr class='message_mini_indicator'>";
        echo "<td>";
        echo "<a href=$RFS_SITE_URL/modules/core_messages/messages.php>";
        echo "<img border=0 width=16 height=16 src=$RFS_SITE_URL/modules/core_messages/mail.png>";
        echo "</a>";
        echo "</td>";
        echo "<td>";
        echo "<a href=$RFS_SITE_URL/modules/core_messages/messages.php>";
        echo " $ur unread messages</a>";
        echo "</td>";
        echo "</tr></table>";
    } else  {
		
		echo "<table border=0 cellspacing=0 cellpadding=3>";
		echo "<tr class='message_mini_indicator'>";
       echo "<td>";
       echo "<a href=$RFS_SITE_URL/modules/core_messages/messages.php>";
       echo "<img border=0 width=16 height=16 src=$RFS_SITE_URL/modules/core_messages/mail.png>";
       echo "</a>";
       echo "</td>";
       echo "<td>";
       echo "<a href=$RFS_SITE_URL/modules/core_messages/messages.php>";
       echo "Messages</a>";
       echo "</td>";
       echo "</tr></table>";
	}

}

function module_latest_messages($x) { eval(lib_rfs_get_globals());
    lib_div("MESSAGES MODULE SECTION");
    echo "<h2>Private Messages</h2>";

    echo "<table border=0 cellspacing=0>";

    $result = lib_mysql_query("select * from pmsg where 'to' = '$data->name' ");
    if($result) $numposts=$result->num_rows;
    else $numposts=0;
    if($numposts) {
       $gt=1; $i=0;

        echo "<tr><td class=contenttd width=2% >";
        $thread=$result->fetch_object($result);


        echo "</td></tr>";
    }
    echo "</table>";

    echo "<p align=right>(<a href=$RFS_SITE_URL/modules/core_messages/messages.php class=\"a_cat\" align=right>More...</a>)</p>";
}

function messages_f_send($to,$from,$title,$message) { eval(lib_rfs_get_globals()); 
/*   "admin",  $usr->name,  "REQUEST TO ACCESS POD: $pod",        "	<a class=pmsglink href=$RFS_SITE_URL/index.php?action=netman_add_pod&useradd=$usr->id&pod=$pod > 		$usr->name is requesting access to pod: $pod 		</a>"         */
	

}




?>
