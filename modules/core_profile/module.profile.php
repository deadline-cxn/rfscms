<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.rfscms.org/
/////////////////////////////////////////////////////////////////////////////////////////
// PROFILE CORE MODULE
/////////////////////////////////////////////////////////////////////////////////////////
include_once("include/lib.all.php");
lib_menus_register("My Profile","$RFS_SITE_URL/modules/core_profile/profile.php");

//////////////////////////////////////////////////////////////////////////////////
// MODULE profile
function m_panel_profile($x) { eval(lib_rfs_get_globals());
    lib_div("PROFILE MODULE SECTION");
    echo "<h2>Profile</h2>";
    /*
    echo "<table border=0 cellspacing=0>";
    $ct=count($profilelist); if($ct>$x) $ct=$x;
    for($cci=0;$cci<$ct;$cci++){
        echo "<tr><td class=contenttd width=2% >";       
		$profile=rfs_getprofiledata($profilelist[$cci]);
        if(empty($profile->image_url)) $profile->image_url="images/noimage.gif";
        $altern=stripslashes($profile->image_alt);
        $picf="$RFS_SITE_PATH/$profile->image_url";
        $picf=str_replace($RFS_SITE_URL,"",$picf);
        echo "<a href=\"$RFS_SITE_URL/profile.php?action=view&nid=$profile->id\">".lib_images_thumb("$picf",30,0,1	)."</a>\n";
        echo "</td><td valign=top  class=contenttd 90%>";
        echo "<a href=\"$RFS_SITE_URL/profile.php?action=view&nid=$profile->id\" class=\"a_cat\">".lib_string_truncate("$profile->headline",50)."</a>";
        $ntext=str_replace("<p>"," ",$ntext);
        $ntext=str_replace("</p>"," ",$ntext);
        $ntext=str_replace("<","&lt;",$ntext);
        echo "<font class=rfs_black>$ntext</font>";
        echo "</td></tr>";
    }
    echo "</table>";

    */
    echo "<p align=right>(<a href=profile.php class=\"a_cat\" align=right>More...</a>)</p>";
}

?>
