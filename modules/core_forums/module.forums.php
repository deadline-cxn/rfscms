<?
include_once("include/lib.all.php");

lib_menus_register("Forums","$RFS_SITE_URL/modules/core_forums/forums.php");

////////////////////////////////////////////////////////////////////////////////////////////////////////
// MODULE FORUM
function m_panel_forum_threads($x) {
    eval(lib_rfs_get_globals());
    $RFS_ADDON_URL=lib_modules_get_url("forums");
    lib_div("FORUMS MODULE SECTION");
    echo "<h2>Last $x Threads</h2>";
    echo "<table border=0 cellspacing=0>";
    $result = lib_mysql_query("select * from `forum_posts` where `thread_top`='yes' order by bumptime desc limit 0,$x");
    $numposts=mysql_num_rows($result);    
	if($numposts) { $gt=1;
		while($thread=mysql_fetch_object($result)) {
				$gt++; if($gt>2) $gt=1;
				echo "<tr><td class=\"rfs_forum_table_$gt\">";				
				$lastreply=lib_mysql_fetch_one_object("	select * from `forum_posts` where `thread` = '$thread->thread' order by time desc limit 1");
				echo "<a href=\"$RFS_ADDON_URL?action=get_thread&thread=$thread->thread&forum_which=$thread->forum#$lastreply->id\">";
				echo "<img src=\"$RFS_SITE_URL/images/icons/icon_minipost.gif\" border=0 >";
				echo "$thread->title </a>";
				echo "</td></tr>";
		}
	}	
    echo "</table><br>";
	echo "(<a href=\"$RFS_ADDON_URL\" class=\"a_cat\" align=right>More...</a>)
}


////////////////////////////////////////////////////////////////////////////////////////////////////
// ADM_FORUMS

function adm_action_f_add_forum() {
    eval( lib_rfs_get_globals() );
	lib_mysql_query( "insert into forum_list (`name`,`folder`,`parent`) VALUES ('$name','no','$parent') ; " );
	adm_action_forum_admin();
}

function adm_action_f_add_forum_folder() {
    eval( lib_rfs_get_globals() );
	lib_mysql_query( "insert into forum_list (`name`,`folder`) VALUES ('$name','yes') ; " );
	adm_action_forum_admin();
}
function adm_action_forum_admin() {
    eval( lib_rfs_get_globals() );	
	// Select forum folders
	$r=lib_mysql_query( "select * from forum_list where folder='yes' order by priority asc" );
	$n=mysql_num_rows( $r );
	if( $n==0 ) {
	   lib_forms_warn("<p>No forum folders defined.</p>");
	}
	else {
		for( $i=0; $i<$n; $i++ ) {
			$folder=mysql_fetch_object( $r );
			echo "<div class=forum_box>";
			
			echo "<img src=$RFS_SITE_URL/images/icons/folder_big.gif style='float: left;'>";
			
			echo "<div>";
				
						echo "<h2>";						
						rfs_db_element_edit(
						"$folder->name",
						"$RFS_SITE_URL/admin/adm.php",
						"forum_admin",
						"forum_list",$folder->id);
						echo " Priority: $folder->priority</h2>";
			echo "</div>";
			echo "<div style='clear: both;'>"; echo "</div>";	
			
			
			
			$rr=lib_mysql_query("select * from `forum_list` where parent='$folder->id' order by priority asc");

			$nn=mysql_num_rows($rr);
			if($nn==0) {
				echo "<p>No forums defined.</p>";				
			}
			else {
				
				echo "<div style='margin-left: 100px;'>";
				
				for($j=0;$j<$nn;$j++) {
					$forum=mysql_fetch_object($rr);
					echo "<div>";
					
					echo "<img src=$RFS_SITE_URL/images/icons/icon_minipost.gif>";
					
					
			
					rfs_db_element_edit(
						"$forum->name",
						"$RFS_SITE_URL/admin/adm.php",
						"forum_admin",
						"forum_list",$forum->id);
					echo " Priority: $forum->priority";	
					echo "</div>";
				}
				echo "</div>";
			}
			
			echo "<div style='margin-left: 200px;'>";
			
			lib_forms_build(
				"$RFS_SITE_URL/admin/adm.php",
				"action=f_add_forum".$RFS_SITE_DELIMITER.
				"parent=$folder->id".$RFS_SITE_DELIMITER.
				"SHOW_TEXT_#20#name=forum",
				"forum_list", "", "", "", "include", "", 100, "Add" );
				
				
			echo "</div>";
			echo "</div>";
		}
	}
	

	echo "<div class='forum_box'>";
	lib_forms_build(
		"$RFS_SITE_URL/admin/adm.php",
		"action=f_add_forum_folder".$RFS_SITE_DELIMITER.
		"SHOW_TEXT_#20#name=folder",
		"forum_list", "", "", "", "include", "", 100, "Add" );
	echo "</div>";


}

?>