<?
$title="My Bullets";
chdir("../../");
include("header.php");


function bullet_log_action_add_bullet_go() { eval(scg());
	echo "<h1>Add New Bullet</h1>";	
	sc_query("update `rfsm_bullet_log` set `name`     = '$name'     where `id`='$bid'");
	sc_query("update `rfsm_bullet_log` set `what`     = '$what'     where `id`='$bid'");
	sc_query("update `rfsm_bullet_log` set `how`      = '$how'      where `id`='$bid'");
	sc_query("update `rfsm_bullet_log` set `impact`   = '$impact'   where `id`='$bid'");
	sc_query("update `rfsm_bullet_log` set `category` = '$category' where `id`='$bid'");
	
	echo "Bullet added.<br>";
	
	bullet_log_action();



	
	include("footer.php");

}

function bullet_log_action_add_bullet() { eval(scg());
	echo "<h1>Add New Bullet</h1>";
	echo $bullet;
	sc_query("insert into rfsm_bullet_log (`name`,`username`) VALUES('$bullet','$data->name');");
	$bid=mysql_insert_id();
	
	
	sc_bf( "$RFS_SITE_URL/admin/adm.php",
	       "action=add_bullet_go".$RFS_SITE_DELIMITER.
		    "bid=$bid",		   
	       "rfsm_bullet_log", "select * from `rfsm_bullet_log` where `id`='$bid'",
	       "id".$RFS_SITE_DELIMITER.
		   "username".$RFS_SITE_DELIMITER.
		   "when",
		   "","omit","",100,"Go" );
		   
	include("footer.php");
	
}

function bullet_log_action_edit_bullets() { eval(scg());
	echo "<h1>Edit My Bullets</h1>";
	sc_bf( sc_phpself(),
	       "action=add_bullet".$RFS_SITE_DELIMITER.	       
	       "SHOW_CLEARFOCUSTEXT_50#50#bullet=Enter bullet name",
	       "","","","","","",50,"New Bullet" );
		   
	$r=sc_query("select * from `rfsm_bullet_log` where `username`='$data->name' limit $x");
	if($r) {
		for($i=0;$i<$x;$i++) {
				$bullet=mysql_fetch_object($r);			
				if(!empty($bullet->name)) 
					echo "$bullet->date $bullet->name <br>";		
		}		
	}
	else {
		echo "No bullets yet.<br>";
	}		   
		
	include("footer.php");
	
}


function bullet_log_action() { eval(scg());
	sc_module_bullet_log(100);
	include("footer.php");
}



?>

