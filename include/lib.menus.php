<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFS CMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
lib_div(__FILE__);
/////////////////////////////////////////////////////////////////////////////////////////
$RFS_MENU_OPTION = array();
/////////////////////////////////////////////////////////////////////////////////////////
function lib_menus_register($short,$url) { 
	global $RFS_MENU_OPTION;
	$RFS_MENU_OPTION[$short]=$url;
}
function lib_menus_options() {
	echo "<h1>Menu Options registered by Modules</h1>";
	echo "<hr>";
	global $RFS_MENU_OPTION;
	echo "<table border=0>";
	echo "<tr><th></th><th></th><th>Link Short Name</th><th>Link URL</th></tr>";
	asort($RFS_MENU_OPTION);
	foreach($RFS_MENU_OPTION as $k => $v) {
		echo "<tr>";
		if(lib_access_check("admin","access")) {
			echo "<td>";
			lib_button("$RFS_SITE_URL/admin/adm.php?action=f_menu_top_add_link&lname=$k&lurl=$v","Add to Top Menu");
			echo "</td>";
			echo "<td>";
			lib_button("$RFS_SITE_URL/admin/adm.php?action=f_menu_admin_add_link&lname=$k&lurl=$v","Add to Admin Menu");
			echo "</td>";
		}
		
		echo "<td>";		
		echo wikitext("$k</td><td>[$v]</td></tr>");
		
	}
	echo "</table>";
}
function lib_menus_draw($menu_location) {   eval(lib_rfs_get_globals());

    $res=lib_mysql_query("select * from `menu_top` order by `sort_order` asc");
    if($menu_location=="left") echo "<table  border=0 cellspacing=0 cellpadding=0 align=center>\n";
    while($link=mysql_fetch_object($res))    {        
		$link->link=urldecode($link->link);		
        $showlink=0;
		if($link->access == 0) $showlink=1;		
		$access_check=explode(",",$link->access_method);
		if(!empty($access_check[1])) {
			$showlink=0;
			if(lib_access_check($access_check[0],$access_check[1])) $showlink=1;
		}
		
		if(!empty($link->other_requirement)) {
			$showlink=0;
			$req=explode("=",$link->other_requirement);
			switch($req[0]) {
				
				case "loggedin":
					
					if(lib_rfs_bool_true($logged_in)==lib_rfs_bool_true($req[1]))
						$showlink=1;
				
					break;
				
				default:
					break;
			}
		}

        if($showlink==1) {
                if($menu_location=="left") {
                        echo "<tr><td width=5 class=lefttd>";
                }
                if($menu_location=="top"){
                        echo "<td class=sc_top_menu_table_td >";
                }

                if(lib_rfs_bool_true($RFS_THEME_NAV_BUTTONS)) {
                    lib_button(lib_rfs_get($link->link),$link->name);
                }
                else {
                    echo "<a class=sc_top_menu_link href=\"";
                    lib_rfs_echo($link->link);
					echo "\" ";
					if(!empty($link->target)) {
						lib_rfs_echo("target=\"$link->target\" ");
					}					
					echo ">";

                    $clr = lib_images_html2rgb($RFS_THEME_NAV_FONT_COLOR);
                    $bclr= lib_images_html2rgb($RFS_THEME_NAV_FONT_BGCOLOR);

					if($RFS_THEME_NAV_IMG == 1) {
						$fntsz=16;
						if($RFS_THEME_NAV_FONT_SIZE>0)
							$fntsz=$RFS_THEME_NAV_FONT_SIZE;
						lib_images_text($link->name,
									$RFS_THEME_NAV_FONT,
									$fntsz,
									155,1,
									0+$RFS_THEME_NAV_FONT_X_OFFSET,
									0+$RFS_THEME_NAV_FONT_Y_OFFSET,
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
                        echo "</td>";
                }

                if($menu_location=="left") {
                        echo "</td></tr>\n";
                }
            }
        }

    if($menu_location=="left") echo "</table>\n";
}



?>