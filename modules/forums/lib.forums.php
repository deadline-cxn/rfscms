<?
include_once("include/lib.all.php");

sc_menus_register("Forums","$RFS_SITE_URL/modules/forums/forums.php");

sc_access_method_add("forums", "admin"); 
sc_access_method_add("forums", "add"); 
sc_access_method_add("forums", "edit");
sc_access_method_add("forums", "delete");
sc_access_method_add("forums", "moderate");

////////////////////////////////////////////////////////////////////////////////////////////////////////
// MODULE FORUM
function sc_module_mini_latest_forum_threads($x) { eval(scg());
    sc_div("FORUMS MODULE SECTION");
    echo "<h2>Last $x Threads</h2>";
    echo "<table border=0 cellspacing=0 width=190>";
    $result = sc_query("select * from forum_posts 
							where `thread_top`='yes' order by bumptime desc limit 0,$x");
    $numposts=mysql_num_rows($result);    
	if($numposts) {
		$gt=1;$i=0;
		for($i=0;$i<$numposts;$i++) {
				$gt++; if($gt>2) $gt=1;
				echo "<tr><td class=\"sc_forum_table_$gt\">";
				$thread=mysql_fetch_object($result);
				$lastreply=mfo1("	select * from `forum_posts` where `thread` = '$thread->thread'
										order by time desc limit 1");
				echo "<a href=\"$RFS_SITE_URL/modules/forums/forums.php?action=get_thread&thread=$thread->thread&forum_which=$thread->forum#$lastreply->id\">";
				echo "<img src=\"$RFS_SITE_URL/images/icons/icon_minipost.gif\" border=0 >";
				echo "$thread->title </a>";
				echo "</td></tr>";
		}
		
	}
	
	echo "<tr><td class=contenttd>(<a href=$RFS_SITE_URL/modules/forums/forums.php class=\"a_cat\" align=right>More...</a>)</td></tr>";
    echo "</table>";
    
}


////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_FORUMS

function adm_action_f_add_forum_folder() { eval( scg() );
	sc_query( "insert into forum_list (`name`,`folder`,`parent`) VALUES ('$name','no','$parent') ; " );
	adm_action_forum_admin();
}

function adm_action_f_add_forum_folder() { eval( scg() );
	sc_query( "insert into forum_list (`name`,`folder`) VALUES ('$name','yes') ; " );
	adm_action_forum_admin();
}
function adm_action_forum_admin() { eval( scg() );
	
	// Select forum folders
	$r=sc_query( "select * from forum_list where folder='yes'" );
	$n=mysql_num_rows( $r );

	if( $n==0 ) {
		rfs_warn("<p>No forum folders defined.</p>");
	}
	else {
		
		for( $i=0; $i<$n; $i++ ) {

			$folder=mysql_fetch_object( $r );
			
			echo "<div class=forum_box>";
			
			echo "<h2>FORUM CATEGORY:";
						
			rfs_db_element_edit(
			"$folder->name",
			"$RFS_SITE_URL/admin/adm.php",
			"forum_admin",
			"forum_list",$folder->id);
			echo "</h2>";		
			
			
			$rr=sc_query("select * from `forum_list` where parent='$folder->id'");

			$nn=mysql_num_rows($rr);
			if($nn==0) {
				echo "<p>No forums defined.</p>";
				
				
				sc_bf(
					"$RFS_SITE_URL/admin/adm.php",
					"action=f_add_forum".$RFS_SITE_DELIMITER.
					"parent=$folder->id".$RFS_SITE_DELIMITER.
					"SHOW_TEXT_#20#name=forum",
				    "forum_list", "", "", "", "include", "", 100, "Add" );
				
			}
			else {
				for($j=0;$j<$nn;$j++) {
					$forum=mysql_fetch_object($r);
					echo " --- $forum->name <br>";
					// TODO: edit forum
					
					echo "EDIT";
					
				}
			}
			echo "</div>";
		}
	}
	

	sc_bf(
		"$RFS_SITE_URL/admin/adm.php",
		"action=f_add_forum_folder".$RFS_SITE_DELIMITER.
		"SHOW_TEXT_#20#name=folder",
		"forum_list", "", "", "", "include", "", 100, "Add" );


}


?>
