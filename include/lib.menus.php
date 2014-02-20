<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFS CMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
sc_div(__FILE__);
/////////////////////////////////////////////////////////////////////////////////////////
$RFS_MENU_OPTION = array();
/////////////////////////////////////////////////////////////////////////////////////////
function sc_menus_register($short,$url) { 
	global $RFS_MENU_OPTION;
	$RFS_MENU_OPTION[$short]=$url;
}
function sc_show_menu_options() {
	echo "<h1>Menu Options registered by Modules</h1>";
	echo "<hr>";
	global $RFS_MENU_OPTION;
	echo "<table border=0>";
	echo "<tr><th></th><th></th><th>Link Short Name</th><th>Link URL</th></tr>";
	asort($RFS_MENU_OPTION);
	foreach($RFS_MENU_OPTION as $k => $v) {
		echo "<tr>";
		if(sc_access_check("admin","access")) {
			echo "<td>";
			sc_button("$RFS_SITE_URL/admin/adm.php?action=f_menu_top_add_link&lname=$k&lurl=$v","Add to Top Menu");
			echo "</td>";
			echo "<td>";
			sc_button("$RFS_SITE_URL/admin/adm.php?action=f_menu_admin_add_link&lname=$k&lurl=$v","Add to Admin Menu");
			echo "</td>";
		}
		
		echo "<td>";		
		echo wikitext("$k</td><td>[$v]</td></tr>");
		
	}
	echo "</table>";
}
/////////////////////////////////////////////////////////////////////////////////////////
function sc_menu_draw($menu_location) {
    eval(scg());
    $res=sc_query("select * from `menu_top` order by `sort_order` asc");
    $num=mysql_num_rows($res);
    if($menu_location=="left") {
        echo "<table  border=0 cellspacing=0 cellpadding=0 align=center>\n";
    }
    for($i=0;$i<$num;$i++)    {
        $link=mysql_fetch_object($res);
		$link->link=urldecode($link->link);
        $showlink=0;
        if($data->access >= $link->access) $showlink=1;
        if($link->access == 0) $showlink=1;
        
        //if(!stristr($link->link,$RFS_SITE_URL)) $link->link="$RFS_SITE_URL/$link->link";

        if($showlink==1) {
                if($menu_location=="left") {
                        echo "<tr><td width=5 class=lefttd>";
                }
                if($menu_location=="top"){
                        echo "<td class=sc_top_menu_table_td >";
                }

                if(sc_yes($RFS_SITE_NAV_BUTTONS)) {
                    sc_button(rfs_get($link->link),$link->name);
                }
                else {
                    echo "<a class=sc_top_menu_link href=\"";
                    rfs_echo($link->link);
					echo "\" ";
					if(!empty($link->target)) {
						rfs_echo("target=\"$link->target\" ");
					}					
					echo ">";

                    $clr = sc_html2rgb($RFS_SITE_NAV_FONT_COLOR);
                    $bclr= sc_html2rgb($RFS_SITE_NAV_FONT_BGCOLOR);

                    // d_echo("\$RFS_SITE_NAV_IMG = $RFS_SITE_NAV_IMG");

						if($RFS_SITE_NAV_IMG == 1) {
						$fntsz=16;
						if($RFS_SITE_NAV_FONT_SIZE>0) $fntsz=$RFS_SITE_NAV_FONT_SIZE;
						sc_image_text($link->name,
										$RFS_SITE_NAV_FONT,
										$fntsz,
										155,1,
										0+$RFS_SITE_NAV_FONT_X_OFFSET,
										0+$RFS_SITE_NAV_FONT_Y_OFFSET,
										$clr[0], $clr[1], $clr[2],
										$bclr[0], $bclr[1], $bclr[2],
										1,0 );
                    }
                    else {


                        echo $link->name;


                    }

                    echo "</a>";
                }

                if($menu_location=="top") {
                        echo "</td> <td class=sc_top_menu_table_td> &nbsp;&nbsp; </td>";
                }


                if($menu_location=="left") {
                        echo "</td></tr>\n";
                }
            }
        }

    if($menu_location=="left")     {
        echo "</table>\n";

    }
}



?>