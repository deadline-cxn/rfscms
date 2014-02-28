<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////

if(stristr(getcwd(),"modules")) { chdir("../../"); }
include_once("include/lib.all.php");
include("header.php");

function todo_list_action_f_rfs_db_element_del1() { adm_action_f_rfs_db_element_del1(); }
function todo_list_action_f_rfs_db_element_ed1() { adm_action_f_rfs_db_element_ed1(); }

function todo_list_action_() { eval(scg());
	echo "<h1>TODO List</h1>";
	echo "<hr>";
	sc_button("$RFS_SITE_URL/modules/todo_list/todo_list.php?action=new_todo_list","New List");
	echo "<hr>";
	$r=sc_query("select * from todo_list");
	for($i=0;$i<mysql_num_rows($r);$i++) {
		$tdl=mysql_fetch_object($r);
		
		rfs_db_element_edit($tdl->name,
							"$RFS_SITE_URL/modules/todo_list/todo_list.php",
							"",
							"todo_list",
							$tdl->id); 
		echo "<br>";

/*	todo_list: 			name	description	assigned_to	owner
	todo_list_task: 	name	opened	due	 */

	}
}


function todo_list_action_view_todo_list() { eval(scg());
	$r=sc_query("select * from todo_list where id=$id");
	$tdl=mysql_fetch_object($r);
	
	echo "<h1>$tdl->name</h1>";
	echo "$tdl->description<br>";
	echo "Assigned to: $tdl->assigned_to Owner: $tdl->owner <br>";
	echo "<hr>";
	
	
	$r=sc_query("select * from todo_list_tasks where list='$tdl->id'");
	$n=mysql_num_rows($r);
	if($n) {
		echo "Open tasks:<br>";
		
	}
	else {
		echo "There are no tasks.<br>";
	}
		
	
}

function todo_list_action_new_todo_list_go() { eval(scg());
	sc_query("insert into todo_list (`name`,`owner`) values ('$name','$data->name')");
	todo_list_action_();
}
function todo_list_action_new_todo_list() { eval(scg());
	echo "<h1> Create TODO List</h1>";
	sc_bf( 	sc_phpself(),
			"action=new_todo_list_go".$RFS_SITE_DELIMITER,
			"todo_list","","id","","","",50,"New TODO List" );
}		   

include("footer.php");
?>