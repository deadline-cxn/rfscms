<?
$title="My Bullets";
chdir("../../");
include("header.php");

function bullet_log_action_change_category_image() { eval(scg());
	$page ="$RFS_SITE_URL/include/lib.mysql.php?act=select_image_chdir&";
	$page.="rtnpage=modules/bullet_log/bullet_log.php&";
	$page.="rtnact=edit_categories&";
	$page.="id=$bcid&";
	$page.="npath=icons&";
	$page.="table=rfsm_bullet_category&";
	$page.="image_field=image&";
	$page.="spath=$RFS_SITE_PATH";
	sc_gotopage($page);
}

function bullet_log_action_edit_categories() { eval(scg());
	echo "<h1>Edit Bullet Categories</h1>";
	$r=sc_query("select * from rfsm_bullet_category");
	$x=mysql_num_rows($r);
	for($i=0;$i<$x;$i++) {
		$bcat=mysql_fetch_object($r);
		
		if(empty($bcat->image)) $bcat->image="$RFS_SITE_URL/images/icons/noimage.gif";
		
		echo " $bcat->name <br>
				<a href=\"$RFS_SITE_URL/modules/bullet_log/bullet_log.php?action=change_category_image&bcid=$bcat->id\"><img src=\"$bcat->image\" width=32></a> <BR>";
	}
	
}

function bullet_log_action_share_bullet() { eval(scg());
	echo "<h1>Share Bullet</h1>";
	$bullet=mfo1("select * from rfsm_bullet_log where id=$bid");
	sc_warn("Share bullet:<hr>
				$bullet->name $bullet->what $bullet->how $bullet->impact
				<hr>
				Clone this bullet to another user's bullet list.	");
	echo "Choose the user to share this bullet with.<hr>";
	echo "<select name=to><option>Select recipient for this bullet";
	$res=sc_query("select * from users order by  `name` asc");
	$count=mysql_num_rows($res);
	for($i=0;$i<$count;$i++)	{
			$userdata=mysql_fetch_object($res);
    		echo "<option>$userdata->name";
    }
	echo "</select><br>";
}

function bullet_log_action_add_bullet_go() { eval(scg());
	echo "<h1>Update Bullet</h1>";	
	
	$name=addslashes($name);
	sc_query("update `rfsm_bullet_log` set `name`     = '$name'     where `id`='$bid'");
	$what=addslashes($what);
	sc_query("update `rfsm_bullet_log` set `what`     = '$what'     where `id`='$bid'");
	$how=addslashes($how);
	sc_query("update `rfsm_bullet_log` set `how`      = '$how'      where `id`='$bid'");
	sc_query("update `rfsm_bullet_log` set `impact`   = '$impact'   where `id`='$bid'");	
	sc_query("update `rfsm_bullet_log` set `other` 	 = '$other' 	 where `id`='$bid'");
	if( (empty($category)) || ($category=="Select Category")) {
		$category="Job Related";
	}	
	sc_query("update `rfsm_bullet_log` set `category` = '$category' where `id`='$bid'");
	echo "Bullet updated.<br>";	
	sc_gotopage("$RFS_SITE_URL/modules/bullet_log/bullet_log.php");
	include("footer.php");
}

function bullet_log_action_edit_bullet() { eval(scg());
	echo "<h1>Edit Bullet</h1>";
	$ncat="Select Category";
	$bcat=mfo1("select * from `rfsm_bullet_log` where id='$bid'");
	if(!empty($bcat->category)) $ncat=$bcat->category;
	sc_bf( "$RFS_SITE_URL/modules/bullet_log/bullet_log.php",
	       "action=add_bullet_go".$RFS_SITE_DELIMITER.
			"SHOW_SELECTOR_rfsm_bullet_category#name#category#$ncat".$RFS_SITE_DELIMITER.
		    "bid=$bid",		   
	       "rfsm_bullet_log", "select * from `rfsm_bullet_log` where `id`='$bid'",
	       "id".$RFS_SITE_DELIMITER.
		    "username".$RFS_SITE_DELIMITER.
		    "when".$RFS_SITE_DELIMITER."category",
		    "","omit","",100,"Go" );
	include("footer.php");	
}

function bullet_log_action_add_bullet() { eval(scg());
	echo "<h1>Add New Bullet</h1>";
	echo $bullet;
	sc_query("insert into rfsm_bullet_log (`name`,`username`) 
										VALUES('$bullet','$data->name');");
	global $bid;
	$bid=mysql_insert_id();
	bullet_log_action_edit_bullet();
}

function bullet_log_action_f_delete_bullet_go () { eval(scg());
	echo "<h1>Delete Bullet</h1>";
	sc_query("delete from rfsm_bullet_log where id='$bid'");
	echo "Bullet deleted.<br>";
	sc_gotopage("$RFS_SITE_URL/modules/bullet_log/bullet_log.php");	
	include("footer.php");
}
 
function bullet_log_action_delete_bullet() { eval(scg());
	echo "<h1>Delete Bullet</h1>";
	$bullet=mfo1("select * from rfsm_bullet_log where id=$bid");
	sc_confirmform(	"Are you sure you want to delete this bullet:<br><hr>$bullet->name $bullet->what $bullet->how $bullet->impact $bullet->category ? <br>
						<hr>WARNING: This can not be undone.",
						"$RFS_SITE_URL/modules/bullet_log/bullet_log.php",
						"action=f_delete_bullet_go".$RFS_SITE_DELIMITER."bid=$bullet->id" );
	include("footer.php");
}

function bullet_log_action_edit_bullets() { eval(scg());
	echo "<h1>Edit My Bullets</h1>";
	echo "<div class=\"forum_box\">";
	echo "<h2>Add a New Bullet</h2>";
	sc_bf( "$RFS_SITE_URL/modules/bullet_log/bullet_log.php",
	       "action=add_bullet".$RFS_SITE_DELIMITER.	       
	       "SHOW_CLEARFOCUSTEXT_50#50#bullet=Enter bullet name",
	       "","","","","","",50,"New Bullet" );
	echo "</div>";
	echo "<hr>";
	
	sc_button("$RFS_SITE_URL/modules/bullet_log/bullet_log.php?action=short_list","Brief List");
	sc_button("$RFS_SITE_URL/modules/bullet_log/bullet_log.php?action=epr_format","Format for EPR");
	
	if(sc_access_check("bullet_log","admin")) {
		sc_button("$RFS_SITE_URL/modules/bullet_log/bullet_log.php?action=edit_categories","Edit Categories");
		
	}
	
	echo "<hr>";			   
	$r=sc_query("select * from `rfsm_bullet_log` where `username`='$data->name' order by `when` desc");
	$x=mysql_num_rows($r);
	if($x) {
		for($i=0;$i<$x;$i++) {
			$bullet=mysql_fetch_object($r);			
			if(!empty($bullet->name))  {
				echo "<div class=\"forum_box\">";
					echo "<h2>$bullet->name</h2>";
					echo "<div class=\"forum_user\">";
					sc_button("$RFS_SITE_URL/modules/bullet_log/bullet_log.php?action=edit_bullet&bid=$bullet->id","Edit");
					echo "<br>";
					sc_button("$RFS_SITE_URL/modules/bullet_log/bullet_log.php?action=delete_bullet&bid=$bullet->id","Delete");
					echo "<br>";
					sc_button("$RFS_SITE_URL/modules/bullet_log/bullet_log.php?action=share_bullet&bid=$bullet->id","Share");
					echo "</div>";
					echo "<div class=\"forum_message\">";
					echo "CATEGORY: $bullet->category<br>";
					echo "WHEN: $bullet->when<br>";
					echo "WHAT: $bullet->what<br>";
					echo "HOW: $bullet->how<br>";
					echo "IMPACT: $bullet->impact <br>";
					echo "OTHER: $bullet->other <br>";
					
					echo "</div>";					
				echo "</div>";
			}
		}
	}
	else {
		echo "No bullets yet.<br>";
	}
	include("footer.php");
}

function bullet_log_action_epr_format() { eval(scg());
	echo "<h1>Bullets in EPR Format</h1>";
	echo "<hr>";

	echo "<textarea
			rows=40 
			style='font-family: Times New Roman;
					font-size: 12px;
					min-width:590px;					
					width: 590px; '>";	
					
	echo "NOTE: THIS TEXT AREA IS THE APPROXIMATE WIDTH OF THE ELECTRONIC EPR FORM.\n";
	echo "The font used is Times New Roman 12\n";
	echo "===================================================================================\n";
	
	$r=sc_query("select * from `rfsm_bullet_log` where `username`='$data->name' 
	and category ='Job Related'
	order by `when` desc");
	echo "JOB RELATED:\n";
	$x=mysql_num_rows($r);
	for($i=0;$i<$x;$i++) {
		$bullet=mysql_fetch_object($r);
		echo " - $bullet->what; $bullet->how; $bullet->impact\n";
	}
	
	$r=sc_query("select * from `rfsm_bullet_log` where `username`='$data->name' 
	and category ='Volunteer'
	order by `when` desc");
	echo "VOLUNTEER:\n";
	$x=mysql_num_rows($r);
	for($i=0;$i<$x;$i++) {
		$bullet=mysql_fetch_object($r);
		echo " - $bullet->what; $bullet->how; $bullet->impact\n";
	}
	
	$r=sc_query("select * from `rfsm_bullet_log` where `username`='$data->name' 
	and category ='Self Improvement'
	order by `when` desc");
	echo "SELF IMPROVEMENT:\n";
	$x=mysql_num_rows($r);
	for($i=0;$i<$x;$i++) {
		$bullet=mysql_fetch_object($r);
		echo " - $bullet->what; $bullet->how; $bullet->impact\n";
	}
	
	
		
	echo "</textarea>";

	include("footer.php");

}

function bullet_log_action_short_list() { eval(scg());
	 sc_module_bullet_log_long(10000); 
}

function bullet_log_action_() { eval(scg());
	bullet_log_action_edit_bullets();
	include("footer.php");
}



?>

