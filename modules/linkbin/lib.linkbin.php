<?
include_once("include/lib.all.php");

sc_menus_register("Links","$RFS_SITE_URL/modules/linkbin/linkbin.php");

///////////////////////////////////////////////////////////////
// MODULE LINK FRIENDS
function sc_module_mini_link_friends($x) { eval(scg());
	$result=sc_query("select * from link_bin where friend='yes' order by time limit $x");
	$numlinks=mysql_num_rows($result);
	echo "<h2>Link Friends</h2>";
	for($i=0;$i<$numlinks;$i++) {
		$link=mysql_fetch_object($result);
		$url=$link->link;
		$url = str_replace(":","_rfs_colon_",  $url);	
		$url=urlencode($url);
		echo "<div class=contenttd><a href=\"$RFS_SITE_URL/link_out.php?link=$url\" target=_blank>$link->sname</a></div>";
   }
	if($data->access=="255") {
		echo "<div style='float: left;'>";
		echo "<form action=\"$RFS_SITE_URL/admin/adm.php\" method=post><input type=hidden name=action value=edit_linkbin>";
		echo "<input type=submit name=submit value=\"edit links\"></form></div>";
		echo "<div style='clear: left;'></div>";
	}
}


///////////////////////////////////////////////////////////////////////////////////////////////
// ADM_LINK EDIT
function adm_action_f_add_link() {
	eval( scg() );
	$link=$_REQUEST['link'];
	$time=date( "Y-m-d H:i:s" );
	if( $data->id==0 ) $data->id=999;
	sc_query( "insert into `link_bin` (`link`,`sname`,`time`,`bumptime`,`poster`,`description`)
	          values('$link','$sname','$time','$time',   '$id','$description')" );
	echo "<p>Link [$link][$sname] added to linkbin...</p>\n";
	sc_log( "*****> $data->name added a link to the linkbin [$link]" );
	adm_action_edit_linkbin();
}
function adm_action_f_modify_link() {
	eval( scg() );
	if( $deletelink=="delete" ) {
		$l=mfo1( "select * from link_bin where `id`='$linkid'" );
		sc_confirmform( "Are you sure you want to delete $l->link ?",
                        "$RFS_SITE_URL/admin/adm.php",
                        "action=f_modify_link".$RFS_SITE_DELIMITER."deletelink=delete_go".$RFS_SITE_DELIMITER."linkid=$linkid" );
	}
	if( $deletelink=="delete_go" ) {

		$l=mfo1( "select * from link_bin where `id`='$linkid'" );
		sc_query( "DELETE FROM link_bin where `id` = '$linkid' limit 1", $mysql );
		sc_log( "*****> $data->name deleted a link from the linkbin $l->short_name $l->link" );

		sc_info( "$l->link deleted from the link bin","white","red" );

		adm_action_edit_linkbin();
	}
	if( $renamelink=="modify" ) {
		echo "<p><h3>Modifying Link!</h3></p>\n";
		$short_name=addslashes( $short_name );
		$linkurl=addslashes( $linkurl );
		$description=addslashes( $description );
		$category=addslashes( $category );
		sc_query( "update link_bin set `sname` = '$short_name' where `id` = '$linkid'" );
		sc_query( "update link_bin set `link` = '$linkurl' where `id` = '$linkid'" );
		sc_query( "update link_bin set `description` = '$description' where `id` = '$linkid'" );
		$hide=0;
		if( $hidden=="yes" ) {
			$hide=1;
		}
		if( $hidden=="no" )  {
			$hide=0;
		}
		sc_query( "update link_bin set `friend` = '$friend' where `id` = '$linkid'");
		sc_query( "update link_bin set `hidden` = '$hide' where `id` = '$linkid'" );
		sc_query( "update link_bin set `referral` = '$referral' where `id` = '$linkid'" );
		sc_query( "update link_bin set `referrals` = '$referrals' where `id` = '$linkid'" );
		sc_query( "update link_bin set `clicks` = '$clicks' where `id` = '$linkid'" );
		sc_query( "update link_bin set `category` = '$category' where `id` = '$linkid'" );
		sc_query( "update link_bin set `rating` = '$rating' where `id` = '$linkid'" );
		adm_action_edit_linkbin();
	}
}
function adm_action_edit_linkbin() {
	eval( scg() );
	echo "<h3>Link Bin Editor</h3>\n";
	$result=sc_query( "select * from link_bin order by time desc" );
	$numlinks=mysql_num_rows( $result );
	echo "<table width=100% border=0 cellspacing=0 cellpadding=4 align=center>\n";
	$gt=2;
	for( $i=0; $i<$numlinks; $i++ ) {
		$gt++;
		if( $gt>2 )$gt=1;
		echo "<tr><td class=sc_project_table_$gt><br>\n";
		$link=mysql_fetch_object( $result );
		$userdata=sc_getuserdata( $link->poster );
		echo "<table border=0 cellspacing=0 cellpadding=0 width=100% >\n";
		echo "<form enctype=\"application/x-www-form-URLencoded\" action=\"$RFS_SITE_URL/admin/adm.php\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"f_modify_link\">\n";
		echo "<input type=\"hidden\" name=\"linkid\" value=\"$link->id\">\n";

echo "<tr class=sc_project_table_$gt>\n";

echo "<td class=sc_project_table_$gt>Short Name</td>";
echo "<td class=sc_project_table_$gt width=230><input type=text name=short_name value=\"$link->sname\" size=28></td>";

echo "<td class=sc_project_table_$gt>URL</td>";
echo "<td class=sc_project_table_$gt width=250><input type=text name=linkurl value=\"$link->link\" size=40> </td>\n";

echo "<td class=sc_project_table_$gt width=300>(submitted by $userdata->name on ".sc_time( $link->time ).")</td>\n";
echo "<td class=sc_project_table_$gt>Rating:</td>\n";
echo "<td class=sc_project_table_$gt width=100 align=center><input type=submit name=renamelink value=modify></td>\n";
echo "</tr>\n";

echo "<tr>\n";

echo "<td class=sc_project_table_$gt>Category</td>";

		echo "<td class=sc_project_table_$gt>\n";
		echo "<select name=category>\n";
		echo "<option>$link->category\n";

		$result2=sc_query( "select * from `categories` order by name asc" );
		$numcats=mysql_num_rows( $result2 );
		for( $i2=0; $i2<$numcats; $i2++ ) {
			$cat=mysql_fetch_object( $result2 );
			echo "<option>$cat->name\n";
		}

		echo "</select>\n";
		echo "</td>\n";

	echo "<td class=sc_project_table_$gt>Description</td>";
		echo "<td class=sc_project_table_$gt><input type=text name=description value=\"$link->description\" size=40></td>\n";
		echo "<td class=sc_project_table_$gt><table border=0><tr>\n";
		echo "<td class=sc_project_table_$gt>referrals</td><td class=sc_project_table_$gt><input type=text size=4 name=referrals value=\"$link->referrals\"></td>\n";
		echo "<td class=sc_project_table_$gt>clicks</td><td class=sc_project_table_$gt><input type=text size=4 name=clicks value=\"$link->clicks\"></td>\n";
		
		
		echo "<td class=sc_project_table_$gt>";
		
		
		if( sc_yes($link->hidden) ) echo "hidden <select name=hidden><option>yes<option>no</select>\n";
		else echo "hidden <select name=hidden><option>no<option>yes</select>\n";
		echo "<br>";
				
		if( sc_yes($link->friend) ) echo "friend <select name=friend><option>yes<option>no</select>\n";
		else echo "friend <select name=friend><option>no<option>yes</select>\n";		
		echo "<br>";
		
		if( sc_yes($link->referral) ) echo "referral <select name=referral><option>yes<option>no</select>\n";
		else echo "referral <select name=referral><option>no<option>yes</select>\n";		
		echo "<br>";
		
		echo "</tr></table></td>\n";
		echo "<td class=sc_project_table_$gt><select name=rating><option>$link->rating\n";
		for( $j=1; $j<6; $j++ ) echo "<option>$j\n";
		echo "</select></td>\n";
		echo "<td class=sc_project_table_$gt align=center><input type=submit name=deletelink value=delete></td>\n";

		echo "</tr>\n";
		echo "<tr><td class=sc_project_table_$gt>&nbsp;</td><td class=sc_project_table_$gt>&nbsp;</td><td class=sc_project_table_$gt>&nbsp;</td><td class=sc_project_table_$gt>&nbsp;</td><td class=sc_project_table_$gt>&nbsp;</td></tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		echo "</td></tr>\n";
	}

	echo "<tr><td>\n";
	echo "</td></tr></table>\n";
	// add a new link here...

	echo "<h2>Add Link</h2>\n";

	sc_bf(  "$RFS_SITE_URL/admin/adm.php", "action=f_add_link",
            "link_bin", "", "id",
            "sname".$RFS_SITE_DELIMITER."link".$RFS_SITE_DELIMITER."description",
            "include", "category",
            20, "add link" );
	include("footer.php");
	exit();
}
?>