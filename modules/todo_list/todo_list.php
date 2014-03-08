<?
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////

if(stristr(getcwd(),"modules")) { chdir("../../"); }
include_once("include/lib.all.php");
include("header.php");

function todo_list_action_f_rfs_db_element_del1() { adm_action_f_rfs_db_element_del1(); }
function todo_list_action_f_rfs_db_element_ed1() { adm_action_f_rfs_db_element_ed1(); }

function todo_list_action_() { eval(lib_rfs_get_globals());
	echo "<h1>TODO List</h1>";
	echo "<hr>";
	lib_button("$RFS_SITE_URL/modules/todo_list/todo_list.php?action=new_todo_list","New List");
	echo "<hr>";
	$r=lib_mysql_query("select * from todo_list");
	for($i=0;$i<mysql_num_rows($r);$i++) {
		$tdl=mysql_fetch_object($r);
		
		rfs_db_element_edit($tdl->name,
							"$RFS_SITE_URL/modules/todo_list/todo_list.php",
							"",
							"todo_list",
							$tdl->id); 
		echo "<br>";
		// todo_list: 			name	description	assigned_to	owner
	}
}

function todo_list_status_icon() { eval(lib_rfs_get_globals());
	
}

function todo_list_action_open_task_go() { eval(lib_rfs_get_globals());
echo "INSERTING";
	lib_mysql_query("insert into `todo_list_task` (`name`,`list`) values ('$name','$list');");
	$id=mysql_insert_id();
	lib_mysql_update_database("todo_list_task","id","$id","");
	$id=$list;
	todo_list_action_view_todo_list($list);
}

function todo_list_action_open_task() { eval(lib_rfs_get_globals());
	$tdl=lib_mysql_fetch_one_object("select * from todo_list where id='$tdl'");
	echo "<h1>$tdl->name</h1>";
	echo "Open task<br>";
	
	lib_forms_build(	lib_domain_phpself(),
			"action=open_task_go".$RFS_SITE_DELIMITER."list=$tdl->id",
			"todo_list_task",
			"",
			"",
			"id",
			"omit",
			"",
			60,
			"Open" );	
}

function todo_list_action_search() { eval(lib_rfs_get_globals());
	$tdl=lib_mysql_fetch_one_object("select * from todo_list where id='$tdl'");
	echo "<h1>$tdl->name</h1>";
	echo "Search tasks<br>";
	sc_bqf("SHOW_TEXT_Name","Search");
}

function todo_list_action_edit_task_go() { eval(lib_rfs_get_globals());
	lib_mysql_update_database("todo_list_task","id",$id,"");
	todo_list_action_view_todo_list($list);
}

function todo_list_action_edit_task() { eval(lib_rfs_get_globals());
	
	$task=lib_mysql_fetch_one_object("select * from todo_list_task where id='$task'");
	echo "<h1>$task->name</h1>";
	echo "Edit task<br>";
	
	lib_forms_build(	lib_domain_phpself(),
			"action=edit_task_go".$RFS_SITE_DELIMITER.
			"id=$task->id".$RFS_SITE_DELIMITER.
			"list=$task->list",
			"todo_list_task",
			"select * from `todo_list_task` where `id`='$task->id'",
			"",
			"id",
			"omit",
			"",
			60,
			"Modify" );
	
}

function todo_list_action_view_todo_list($list) { eval(lib_rfs_get_globals());
	if(!empty($list)) $id=$list;
	$r=lib_mysql_query("select * from todo_list where id=$id");
	$tdl=mysql_fetch_object($r);
	
	echo "<h1>$tdl->name</h1>";
	echo "$tdl->description<br>";
	lib_button("$RFS_SITE_URL/modules/todo_list/todo_list.php?action=search&tdl=$tdl->id","Search");
	lib_button("$RFS_SITE_URL/modules/todo_list/todo_list.php?action=open_task&tdl=$tdl->id","Open");
	// if(!empty($tdl->assigned_to)) echo "Assigned to: $tdl->assigned_to<br>";
	// if(!empty($tdl->owner))       echo "Owner: $tdl->owner<br>";
	echo "<hr>";
	
echo "<style>
	
.todo_Resolved {
	margin: 5px;
	padding: 5px;
	background-color: #AFA;
	color: #000;
}

.todo_Closed {
	margin: 5px;
	padding: 5px;
	background-color: #AFA;
	color: #000;
}

.todo_Open {
	margin: 5px;
	padding: 5px;
	background-color: #FAA;
	color: #000;
}

.todo_Unknown {
	margin: 5px;
	padding: 5px;
	background-color: #FAA;
	color: #000;
}

.todo_In_Progress {
	margin: 5px;
	padding: 5px;
	background-color: #FFA;
	color: #000;
}
	
	</style>";
	
$r=lib_mysql_query("
select * from `todo_list_task` 
where (`list`='$tdl->id')  ;");
	  
	$n=mysql_num_rows($r);

	if($n>0) {
		
		echo "<table border=0 cellpadding=5 cellspacing=0>";
			echo "<tr>";
			
			if(empty($tdl->type)) $tdl->type="Task";
			echo "<th>$tdl->type # </th>";
			echo "<th> </th>";
			echo "<th> </th>";
			echo "<th>Status</th>";
			//echo "<th>Priority</th>";
			echo "<th>Name</th>";
			echo "<th>Description</th>";
			echo "<th>Opened</th>";
			echo "<th>Opened By</th>";
			echo "<th>Closed</th>";
			echo "<th>Closed By</th>";
			echo "<th>Resolve Action</th>";
			
			echo "</tr>";		
		
		while($task=mysql_fetch_object($r)) {
			
			// todo_list_task: name opened due list priority step
			
			echo "<tr>";
			if(empty($task->status)) $task->status="Unknown";
			$task_status=str_replace(" ","_",$task->status);
			
			echo "<td class=\"todo_$task_status\">$task->id</td>";
			
			echo "<td class=\"todo_$task_status\">";
			lib_button("$RFS_SITE_URL/modules/todo_list/todo_list.php?action=edit_task&task=$task->id","Edit");
			echo "</td>";
			
			echo "<td class=\"todo_$task_status\"><img src=\"$RFS_SITE_URL/modules/todo_list/icons/$task->status.png\"></td>";
			
			echo "<td class=\"todo_$task_status\">";
			lib_ajax("Status","todo_list_task","id",$task->id,"status","30",
					"select,table,todo_list_status,name,nolabel","admin","access","");

			echo "</td>";
			
			//echo "<td class=\"todo_$task_status\">$task->priority</td>";
			
			echo "<td class=\"todo_$task_status\"> $task->name </td>";			
			echo "<td class=\"todo_$task_status\"> $task->description </td>";			
			echo "<td class=\"todo_$task_status\"> $task->opened </td>";
			echo "<td class=\"todo_$task_status\"> $task->opened_by</td>";
			echo "<td class=\"todo_$task_status\"> $task->closed </td>";
			echo "<td class=\"todo_$task_status\"> $task->closed_by</td>";
			echo "<td class=\"todo_$task_status\"> $task->resolve_action</td>";
			
			
			echo "</tr>";
		}
		echo "</table>";
		
	}
	else {
		echo "There are no tasks.<br>";
	}
}

function todo_list_action_new_todo_list_go() { eval(lib_rfs_get_globals());
	lib_mysql_query("insert into todo_list (`name`,`owner`) values ('$name','$data->name')");
	todo_list_action_();
}
function todo_list_action_new_todo_list() { eval(lib_rfs_get_globals());
	echo "<h1> Create TODO List</h1>";
	lib_forms_build( 	lib_domain_phpself(),
			"action=new_todo_list_go".$RFS_SITE_DELIMITER,
			"todo_list","","id","","","",50,"New TODO List" );
}		   

include("footer.php");
?>