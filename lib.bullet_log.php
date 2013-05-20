<?
include_once("include/lib.all.php");

sc_database_add("rfsm_bullet_log","username","text","NOT NULL");
sc_database_add("rfsm_bullet_log","name","text","NOT NULL");
sc_database_add("rfsm_bullet_log","category","text","NOT NULL");
sc_database_add("rfsm_bullet_log","what","text","NOT NULL");
sc_database_add("rfsm_bullet_log","how","text","NOT NULL");
sc_database_add("rfsm_bullet_log","impact","text","NOT NULL");
sc_database_add("rfsm_bullet_log","when","datetime"," ");
sc_query("ALTER TABLE  `rfsm_bullet_log` CHANGE  `when`  `when` DATETIME ON UPDATE CURRENT_TIMESTAMP NULL DEFAULT NULL ;");

function sc_module_bullet_log($x) { eval(scg());
	echo "<h2>My Bullets</h2>";
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
	sc_button("$RFS_SITE_URL/modules/bullet_log/bullet_log.php?action=edit_bullets","Edit Bullets");

}

?>

