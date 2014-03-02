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
		// todo_list: 			name	description	assigned_to	owner
	}
}

function todo_list_status_icon() { eval(scg());
	
}

function todo_list_action_open_task_go() { eval(scg());
echo "INSERTING";
	sc_query("insert into `todo_list_task` (`name`,`list`) values ('$name','$list');");
	$id=mysql_insert_id();
	sc_updb("todo_list_task","id","$id","");
	$id=$list;
	todo_list_action_view_todo_list();
}

function todo_list_action_open_task() { eval(scg());
	$tdl=mfo1("select * from todo_list where id='$tdl'");
	echo "<h1>$tdl->name</h1>";
	echo "Open task<br>";
	
	sc_bf(	sc_phpself(),
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

function todo_list_action_search() { eval(scg());
	$tdl=mfo1("select * from todo_list where id='$tdl'");
	echo "<h1>$tdl->name</h1>";
	echo "Search tasks<br>";
	sc_bqf("SHOW_TEXT_Name","Search");
}

function todo_list_action_edit_task_go() { eval(scg());
	sc_updb("todo_list_task","id",$id,"");
	$id=$list;
	todo_list_action_view_todo_list();
}

function todo_list_action_edit_task() { eval(scg());
	
	$task=mfo1("select * from todo_list_task where id='$task'");
	echo "<h1>$task->name</h1>";
	echo "Edit task<br>";
	
	sc_bf(	sc_phpself(),
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

function todo_list_action_view_todo_list() { eval(scg());
	$r=sc_query("select * from todo_list where id=$id");
	$tdl=mysql_fetch_object($r);
	
	echo "<h1>$tdl->name</h1>";
	echo "$tdl->description<br>";
	sc_button("$RFS_SITE_URL/modules/todo_list/todo_list.php?action=search&tdl=$tdl->id","Search");
	sc_button("$RFS_SITE_URL/modules/todo_list/todo_list.php?action=open_task&tdl=$tdl->id","Open");
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
	
$r=sc_query("
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
			// echo "<th>Due</th>";
			// echo "<th>Step</th>";
			
			echo "</tr>";		
		
		while($task=mysql_fetch_object($r)) {
			
			// todo_list_task: name opened due list priority step
			
			echo "<tr>";
			if(empty($task->status)) $task->status="Unknown";
			$task_status=str_replace(" ","_",$task->status);
			
			echo "<td class=\"todo_$task_status\">$task->id</td>";
			
			echo "<td class=\"todo_$task_status\">";
			sc_button("$RFS_SITE_URL/modules/todo_list/todo_list.php?action=edit_task&task=$task->id","Edit");
			echo "</td>";
			
			echo "<td class=\"todo_$task_status\"><img src=\"$RFS_SITE_URL/modules/todo_list/icons/$task->status.png\"></td>";
			
			echo "<td class=\"todo_$task_status\">";
			sc_ajax("Status","todo_list_task","id",$task->id,"status","30",
					"select,table,todo_list_status,name,nolabel","admin","access","");

			echo "</td>";
			
			//echo "<td class=\"todo_$task_status\">$task->priority</td>";
			
			echo "<td class=\"todo_$task_status\"> $task->name </td>";			
			echo "<td class=\"todo_$task_status\"> $task->description </td>";			
			echo "<td class=\"todo_$task_status\"> $task->opened </td>";
			
			// echo "<td class=\"todo_$task_status\"> $task->due </td>";			
			// echo "<td class=\"todo_$task_status\">$task->step</td>";
			
			
			echo "</tr>";
		}
		echo "</table>";
		
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