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

function todo_list_action_open() { eval(scg());
	$tdl=mfo1("select * from todo_list where id='$tdl'");
	echo "<h1>$tdl->name</h1>";
	echo "Open task<br>";
}

function todo_list_action_search() { eval(scg());
	$tdl=mfo1("select * from todo_list where id='$tdl'");
	echo "<h1>$tdl->name</h1>";
	echo "Search tasks<br>";
	sc_bqf("SHOW_TEXT_Name","Search");
}

function todo_list_action_view_todo_list() { eval(scg());
	$r=sc_query("select * from todo_list where id=$id");
	$tdl=mysql_fetch_object($r);
	
	echo "<h1>$tdl->name</h1>";
	echo "$tdl->description<br>";
	sc_button("$RFS_SITE_URL/modules/todo_list/todo_list.php?action=search&tdl=$tdl->id","Search");
	sc_button("$RFS_SITE_URL/modules/todo_list/todo_list.php?action=open&tdl=$tdl->id","Open");
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
	
	</style>";
	
$r=sc_query("
select * from `todo_list_task` 
where (`list`='$tdl->id') and
	  (`name`='Stuff') ;");
	  
	$n=mysql_num_rows($r);

	if($n>0) {
		
		echo "<table border=0 cellpadding=5 cellspacing=0>";
			echo "<tr>";
			
			echo "<th>";
			echo "</th>";
			
			echo "<th>";
			echo "Status";
			echo "</th>";
			
			echo "<th>";
			echo "Priority";
			echo "</th>";
			
			echo "<th>";
			echo "Name";
			echo "</th>";
			
			echo "<th>";
			echo "Opened";
			echo "</th>";
			
			echo "<th>";
			echo "Due";
			echo "</th>";
			
			echo "<th>";
			echo "Step";
			echo "</th>";
			
			echo "<th>";
			echo "Action";
			echo "</th>";
			
			echo "</tr>";		
		
		while($task=mysql_fetch_object($r)) {
			
			// todo_list_task: name opened due list priority step
			
			echo "<tr>";
			if(empty($task->status)) $task->status="Unknown";
			
			echo "<td class=\"todo_$task->status\">";
			echo "<img src=\"$RFS_SITE_URL/modules/todo_list/icons/$task->status.png\">	";
			echo "</td>";
			
			echo "<td class=\"todo_$task->status\">";
			//echo "$task->status<br>";

			// if(sc_access_check());
			
			sc_ajax("Status","todo_list_task","id",$task->id,"status","30",
					"select,table,todo_list_status,name,nolabel","admin","access","");
					
			
			echo "</td>";
			
			echo "<td class=\"todo_$task->status\">";
			echo "$task->priority";
			echo "</td>";
			
			echo "<td class=\"todo_$task->status\">";
			echo "$task->name";
			echo "</td>";
			
			echo "<td class=\"todo_$task->status\">";
			echo "$task->opened";
			echo "</td>";
			
			echo "<td class=\"todo_$task->status\">";
			echo "$task->due";
			echo "</td>";
			
			echo "<td class=\"todo_$task->status\">";
			echo "$task->step";
			echo "</td>";
			
			echo "<td class=\"todo_$task->status\">";
			echo "$task->action";
			echo "</td>";
			
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